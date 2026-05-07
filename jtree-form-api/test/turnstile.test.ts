import { describe, it, expect, vi, beforeEach, afterEach } from "vitest";
import { verifyTurnstile, isTurnstileConfigured } from "../lib/turnstile.js";

const ORIGINAL_SECRET = process.env.TURNSTILE_SECRET;

describe("Turnstile verifier", () => {
  beforeEach(() => {
    vi.restoreAllMocks();
  });

  afterEach(() => {
    if (ORIGINAL_SECRET === undefined) {
      delete process.env.TURNSTILE_SECRET;
    } else {
      process.env.TURNSTILE_SECRET = ORIGINAL_SECRET;
    }
  });

  it("falls open and returns true when TURNSTILE_SECRET is unset", async () => {
    delete process.env.TURNSTILE_SECRET;
    expect(isTurnstileConfigured()).toBe(false);

    const ok = await verifyTurnstile("any-token", "1.2.3.4");
    expect(ok).toBe(true);
  });

  it("rejects an empty token when configured", async () => {
    process.env.TURNSTILE_SECRET = "test-secret";
    const ok = await verifyTurnstile("", "1.2.3.4");
    expect(ok).toBe(false);
  });

  it("rejects an undefined token when configured", async () => {
    process.env.TURNSTILE_SECRET = "test-secret";
    const ok = await verifyTurnstile(undefined, "1.2.3.4");
    expect(ok).toBe(false);
  });

  it("calls Cloudflare verify endpoint with secret + response + remoteip", async () => {
    process.env.TURNSTILE_SECRET = "test-secret";

    const fetchSpy = vi.spyOn(global, "fetch").mockResolvedValue(
      new Response(JSON.stringify({ success: true }), {
        status: 200,
        headers: { "Content-Type": "application/json" },
      })
    );

    const ok = await verifyTurnstile("valid-token-12345", "203.0.113.7");
    expect(ok).toBe(true);

    expect(fetchSpy).toHaveBeenCalledOnce();
    const [, init] = fetchSpy.mock.calls[0]!;
    const body = (init?.body as URLSearchParams).toString();
    expect(body).toContain("secret=test-secret");
    expect(body).toContain("response=valid-token-12345");
    expect(body).toContain("remoteip=203.0.113.7");
  });

  it("omits remoteip when ip is unknown", async () => {
    process.env.TURNSTILE_SECRET = "test-secret";

    const fetchSpy = vi.spyOn(global, "fetch").mockResolvedValue(
      new Response(JSON.stringify({ success: true }), { status: 200 })
    );

    await verifyTurnstile("valid-token-12345", "unknown");

    const [, init] = fetchSpy.mock.calls[0]!;
    const body = (init?.body as URLSearchParams).toString();
    expect(body).not.toContain("remoteip=");
  });

  it("returns false when Cloudflare reports success: false", async () => {
    process.env.TURNSTILE_SECRET = "test-secret";

    vi.spyOn(global, "fetch").mockResolvedValue(
      new Response(
        JSON.stringify({ success: false, "error-codes": ["invalid-input-response"] }),
        { status: 200 }
      )
    );

    const ok = await verifyTurnstile("bad-token-12345", "1.2.3.4");
    expect(ok).toBe(false);
  });

  it("returns false on non-2xx from Cloudflare", async () => {
    process.env.TURNSTILE_SECRET = "test-secret";

    vi.spyOn(global, "fetch").mockResolvedValue(
      new Response("error", { status: 502 })
    );

    const ok = await verifyTurnstile("any-token-12345", "1.2.3.4");
    expect(ok).toBe(false);
  });

  it("returns false on network error", async () => {
    process.env.TURNSTILE_SECRET = "test-secret";

    vi.spyOn(global, "fetch").mockRejectedValue(new Error("ECONNRESET"));

    const ok = await verifyTurnstile("any-token-12345", "1.2.3.4");
    expect(ok).toBe(false);
  });
});
