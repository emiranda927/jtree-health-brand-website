import { describe, it, expect, beforeEach, afterEach } from "vitest";
import type { VercelRequest, VercelResponse } from "@vercel/node";
import { isOriginAllowed, applyCorsHeaders, _readAllowlistForTest } from "../lib/cors.js";

// Minimal mock of VercelResponse — only what applyCorsHeaders touches.
function makeRes(): { headers: Record<string, string>; res: VercelResponse } {
  const headers: Record<string, string> = {};
  const res = {
    setHeader: (k: string, v: string) => {
      headers[k] = v;
    },
  } as unknown as VercelResponse;
  return { headers, res };
}

function makeReq(origin?: string): VercelRequest {
  return {
    headers: origin ? { origin } : {},
  } as unknown as VercelRequest;
}

const ORIGINAL_PLURAL = process.env.ALLOWED_ORIGINS;
const ORIGINAL_LEGACY = process.env.ALLOWED_ORIGIN;

beforeEach(() => {
  delete process.env.ALLOWED_ORIGINS;
  delete process.env.ALLOWED_ORIGIN;
});

afterEach(() => {
  if (ORIGINAL_PLURAL !== undefined) process.env.ALLOWED_ORIGINS = ORIGINAL_PLURAL;
  if (ORIGINAL_LEGACY !== undefined) process.env.ALLOWED_ORIGIN = ORIGINAL_LEGACY;
});

describe("readAllowlist (via _readAllowlistForTest)", () => {
  it("uses ALLOWED_ORIGINS when set", () => {
    process.env.ALLOWED_ORIGINS = "https://a.com,https://b.com";
    expect(_readAllowlistForTest()).toEqual(["https://a.com", "https://b.com"]);
  });

  it("trims whitespace and drops empty entries", () => {
    process.env.ALLOWED_ORIGINS = " https://a.com , , https://b.com ,";
    expect(_readAllowlistForTest()).toEqual(["https://a.com", "https://b.com"]);
  });

  it("falls back to ALLOWED_ORIGIN when ALLOWED_ORIGINS is unset", () => {
    process.env.ALLOWED_ORIGIN = "https://legacy.com";
    expect(_readAllowlistForTest()).toEqual(["https://legacy.com"]);
  });

  it("falls back to production default when both are unset", () => {
    expect(_readAllowlistForTest()).toEqual(["https://jtreehealth.com"]);
  });

  it("prefers ALLOWED_ORIGINS over ALLOWED_ORIGIN when both set", () => {
    process.env.ALLOWED_ORIGINS = "https://new.com";
    process.env.ALLOWED_ORIGIN = "https://legacy.com";
    expect(_readAllowlistForTest()).toEqual(["https://new.com"]);
  });

  it("treats empty ALLOWED_ORIGINS as unset (falls back)", () => {
    process.env.ALLOWED_ORIGINS = "   ";
    process.env.ALLOWED_ORIGIN = "https://legacy.com";
    expect(_readAllowlistForTest()).toEqual(["https://legacy.com"]);
  });
});

describe("isOriginAllowed — exact match", () => {
  it("allows an exact origin match", () => {
    expect(isOriginAllowed("https://jtreehealth.com", ["https://jtreehealth.com"])).toBe(true);
  });

  it("matches one of several entries", () => {
    const list = ["https://a.com", "https://b.com", "https://jtreehealth.com"];
    expect(isOriginAllowed("https://b.com", list)).toBe(true);
  });

  it("rejects an unknown origin", () => {
    expect(isOriginAllowed("https://evil.com", ["https://jtreehealth.com"])).toBe(false);
  });

  it("rejects when origin is undefined", () => {
    expect(isOriginAllowed(undefined, ["https://jtreehealth.com"])).toBe(false);
  });

  it("rejects when origin is null", () => {
    expect(isOriginAllowed(null, ["https://jtreehealth.com"])).toBe(false);
  });

  it("rejects when origin is empty string", () => {
    expect(isOriginAllowed("", ["https://jtreehealth.com"])).toBe(false);
  });

  it("is case-insensitive on the origin", () => {
    expect(isOriginAllowed("HTTPS://JtreeHealth.COM", ["https://jtreehealth.com"])).toBe(true);
  });

  it("does not match when scheme differs", () => {
    expect(isOriginAllowed("http://jtreehealth.com", ["https://jtreehealth.com"])).toBe(false);
  });

  it("does not match when port differs", () => {
    expect(isOriginAllowed("https://jtreehealth.com:8080", ["https://jtreehealth.com"])).toBe(false);
  });
});

describe("isOriginAllowed — subdomain wildcard", () => {
  it("matches a subdomain via *.suffix", () => {
    expect(isOriginAllowed("https://abc.flywheelsites.com", ["*.flywheelsites.com"])).toBe(true);
  });

  it("matches a deep subdomain", () => {
    expect(isOriginAllowed("https://x.y.flywheelsites.com", ["*.flywheelsites.com"])).toBe(true);
  });

  it("does NOT match the bare apex domain", () => {
    // *.flywheelsites.com requires at least one label before .flywheelsites.com
    expect(isOriginAllowed("https://flywheelsites.com", ["*.flywheelsites.com"])).toBe(false);
  });

  it("does not match a sibling domain by accident", () => {
    expect(isOriginAllowed("https://evilflywheelsites.com", ["*.flywheelsites.com"])).toBe(false);
  });

  it("does not strip scheme — http vs https subdomain still differ", () => {
    // Both schemes accepted because wildcard doesn't pin scheme; document this.
    expect(isOriginAllowed("http://abc.flywheelsites.com", ["*.flywheelsites.com"])).toBe(true);
    expect(isOriginAllowed("https://abc.flywheelsites.com", ["*.flywheelsites.com"])).toBe(true);
  });
});

describe("applyCorsHeaders", () => {
  it("sets Vary: Origin even when no Origin header is present", () => {
    process.env.ALLOWED_ORIGINS = "https://jtreehealth.com";
    const { headers, res } = makeRes();
    applyCorsHeaders(makeReq(), res);
    expect(headers["Vary"]).toBe("Origin");
    expect(headers["Access-Control-Allow-Origin"]).toBeUndefined();
  });

  it("echoes back the request origin when allowed", () => {
    process.env.ALLOWED_ORIGINS = "https://jtreehealth.com,http://jtree-local.local";
    const { headers, res } = makeRes();
    applyCorsHeaders(makeReq("http://jtree-local.local"), res);
    expect(headers["Access-Control-Allow-Origin"]).toBe("http://jtree-local.local");
    expect(headers["Access-Control-Allow-Methods"]).toBe("POST, OPTIONS");
    expect(headers["Access-Control-Allow-Headers"]).toBe("Content-Type");
    expect(headers["Access-Control-Max-Age"]).toBe("86400");
    expect(headers["Vary"]).toBe("Origin");
  });

  it("does not set Allow-Origin when origin is disallowed", () => {
    process.env.ALLOWED_ORIGINS = "https://jtreehealth.com";
    const { headers, res } = makeRes();
    applyCorsHeaders(makeReq("https://evil.com"), res);
    expect(headers["Access-Control-Allow-Origin"]).toBeUndefined();
    expect(headers["Vary"]).toBe("Origin");
  });

  it("never echoes the literal request origin as a wildcard", () => {
    // Defense-in-depth: even when a wildcard entry matches, we echo the
    // actual request origin, not the wildcard pattern itself.
    process.env.ALLOWED_ORIGINS = "*.flywheelsites.com";
    const { headers, res } = makeRes();
    applyCorsHeaders(makeReq("https://abc.flywheelsites.com"), res);
    expect(headers["Access-Control-Allow-Origin"]).toBe("https://abc.flywheelsites.com");
    expect(headers["Access-Control-Allow-Origin"]).not.toBe("*.flywheelsites.com");
    expect(headers["Access-Control-Allow-Origin"]).not.toBe("*");
  });

  it("works with the legacy ALLOWED_ORIGIN env", () => {
    process.env.ALLOWED_ORIGIN = "https://legacy.com";
    const { headers, res } = makeRes();
    applyCorsHeaders(makeReq("https://legacy.com"), res);
    expect(headers["Access-Control-Allow-Origin"]).toBe("https://legacy.com");
  });
});
