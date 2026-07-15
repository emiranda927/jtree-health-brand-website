import { describe, it, expect, vi, beforeEach, afterEach } from "vitest";
import { buildRittenPayload, sendToRitten } from "../lib/ritten.js";
import type { Lead } from "../lib/validate.js";

const lead: Lead = {
  lead_id: "JT-test1234",
  submitted_at: "2026-04-25T10:00:00.000Z",
  name: "Jane Doe",
  email: "jane@example.com",
  phone: "+15551234567",
  teen_age: 14,
  program_interest: "IOP",
  best_time_to_call: "Morning",
  how_did_you_hear: "Google",
};

describe("buildRittenPayload", () => {
  it("maps lead fields to Ritten contact/patient/inquiry sections", () => {
    const payload = buildRittenPayload(lead) as {
      source: string;
      external_id: string;
      contact: { name: string; email: string; phone: string; role: string };
      patient: { age: number; relationship_to_contact: string };
      inquiry: { program_interest: string; best_time_to_call: string; how_did_you_hear: string | null };
    };
    expect(payload.source).toBe("website_form");
    expect(payload.external_id).toBe("JT-test1234");
    expect(payload.contact.name).toBe("Jane Doe");
    expect(payload.contact.email).toBe("jane@example.com");
    expect(payload.contact.phone).toBe("+15551234567");
    expect(payload.contact.role).toBe("unknown");
    expect(payload.patient.age).toBe(14);
    expect(payload.patient.relationship_to_contact).toBe("child");
    expect(payload.inquiry.program_interest).toBe("IOP");
    expect(payload.inquiry.best_time_to_call).toBe("Morning");
    expect(payload.inquiry.how_did_you_hear).toBe("Google");
  });

  it("emits null for missing how_did_you_hear", () => {
    const { how_did_you_hear, ...rest } = lead;
    const payload = buildRittenPayload(rest as Lead) as {
      inquiry: { how_did_you_hear: string | null };
    };
    expect(payload.inquiry.how_did_you_hear).toBeNull();
  });
});

describe("sendToRitten", () => {
  const originalFetch = globalThis.fetch;
  const originalKey = process.env.RITTEN_API_KEY;
  const originalUrl = process.env.RITTEN_API_URL;

  beforeEach(() => {
    process.env.RITTEN_API_KEY = "test-key";
    process.env.RITTEN_API_URL = "https://ritten.test/api/contacts";
  });

  afterEach(() => {
    globalThis.fetch = originalFetch;
    process.env.RITTEN_API_KEY = originalKey;
    process.env.RITTEN_API_URL = originalUrl;
  });

  it("throws RITTEN_NOT_CONFIGURED when env vars missing", async () => {
    delete process.env.RITTEN_API_KEY;
    delete process.env.RITTEN_API_URL;
    await expect(sendToRitten(lead)).rejects.toThrow("RITTEN_NOT_CONFIGURED");
  });

  it("posts JSON with Bearer auth on success", async () => {
    const fetchMock = vi.fn(async () =>
      new Response(JSON.stringify({ ok: true }), { status: 200 })
    );
    globalThis.fetch = fetchMock as unknown as typeof fetch;

    await sendToRitten(lead);

    expect(fetchMock).toHaveBeenCalledTimes(1);
    const [url, init] = fetchMock.mock.calls[0]!;
    expect(url).toBe("https://ritten.test/api/contacts");
    expect(init.method).toBe("POST");
    expect(init.headers["Authorization"]).toBe("Bearer test-key");
    expect(init.headers["Content-Type"]).toBe("application/json");
    const body = JSON.parse(init.body as string);
    expect(body.external_id).toBe("JT-test1234");
    expect(body.contact.email).toBe("jane@example.com");
  });

  it("throws RITTEN_HTTP_<status> on non-2xx response", async () => {
    globalThis.fetch = vi.fn(async () =>
      new Response("server error", { status: 503 })
    ) as unknown as typeof fetch;

    await expect(sendToRitten(lead)).rejects.toThrow("RITTEN_HTTP_503");
  });

  it("throws RITTEN_NETWORK_ERROR on network failure", async () => {
    globalThis.fetch = vi.fn(async () => {
      throw new TypeError("fetch failed");
    }) as unknown as typeof fetch;

    await expect(sendToRitten(lead)).rejects.toThrow("RITTEN_NETWORK_ERROR");
  });

  it("throws RITTEN_TIMEOUT on AbortError", async () => {
    globalThis.fetch = vi.fn(async () => {
      const err = new Error("aborted");
      err.name = "AbortError";
      throw err;
    }) as unknown as typeof fetch;

    await expect(sendToRitten(lead)).rejects.toThrow("RITTEN_TIMEOUT");
  });
});
