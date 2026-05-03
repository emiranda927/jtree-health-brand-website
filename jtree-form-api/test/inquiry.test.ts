import { describe, it, expect, vi, beforeEach } from "vitest";
import { InquirySchema, normalizePhone } from "../lib/validate.js";

// ---------------------------------------------------------------------------
// Validation tests
// ---------------------------------------------------------------------------

const validInput = {
  parent_first_name: "Jane",
  parent_last_name: "Doe",
  parent_email: "jane@example.com",
  parent_phone: "(555) 123-4567",
  teen_age: 14,
  program_interest: "IOP",
  best_time_to_call: "Morning",
  how_did_you_hear: "Google",
  consent_contact: true,
  hp_field: "",
};

describe("InquirySchema validation", () => {
  it("accepts a valid submission", () => {
    const result = InquirySchema.safeParse(validInput);
    expect(result.success).toBe(true);
    if (result.success) {
      expect(result.data.parent_phone).toBe("+15551234567");
    }
  });

  it("rejects missing required field (parent_first_name)", () => {
    const input = { ...validInput, parent_first_name: "" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects missing required field (parent_last_name)", () => {
    const input = { ...validInput, parent_last_name: "" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects missing parent_email", () => {
    const input = { ...validInput, parent_email: "" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects invalid email", () => {
    const input = { ...validInput, parent_email: "not-an-email" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects email over 100 characters", () => {
    const input = { ...validInput, parent_email: "a".repeat(90) + "@example.com" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects first name over 50 characters", () => {
    const input = { ...validInput, parent_first_name: "A".repeat(51) };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects teen_age below 10", () => {
    const input = { ...validInput, teen_age: 9 };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects teen_age above 17", () => {
    const input = { ...validInput, teen_age: 18 };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("accepts teen_age as string (coercion)", () => {
    const input = { ...validInput, teen_age: "15" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(true);
    if (result.success) {
      expect(result.data.teen_age).toBe(15);
    }
  });

  it("rejects invalid program_interest", () => {
    const input = { ...validInput, program_interest: "Residential" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects invalid best_time_to_call", () => {
    const input = { ...validInput, best_time_to_call: "Midnight" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("allows how_did_you_hear to be omitted", () => {
    const { how_did_you_hear, ...input } = validInput;
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(true);
  });

  it("rejects consent_contact = false", () => {
    const input = { ...validInput, consent_contact: false };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("accepts consent_contact = 'true' (string)", () => {
    const input = { ...validInput, consent_contact: "true" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(true);
  });

  it("detects honeypot field with content", () => {
    const input = { ...validInput, hp_field: "bot-filled-this" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("allows empty honeypot field", () => {
    const input = { ...validInput, hp_field: "" };
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(true);
  });

  it("defaults hp_field when omitted", () => {
    const { hp_field, ...input } = validInput;
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(true);
  });
});

// ---------------------------------------------------------------------------
// Phone normalization tests
// ---------------------------------------------------------------------------

describe("normalizePhone", () => {
  it("normalizes 10-digit to E.164", () => {
    expect(normalizePhone("5551234567")).toBe("+15551234567");
  });

  it("normalizes (XXX) XXX-XXXX format", () => {
    expect(normalizePhone("(555) 123-4567")).toBe("+15551234567");
  });

  it("normalizes XXX-XXX-XXXX format", () => {
    expect(normalizePhone("555-123-4567")).toBe("+15551234567");
  });

  it("handles 11-digit starting with 1", () => {
    expect(normalizePhone("15551234567")).toBe("+15551234567");
  });

  it("handles already-formatted E.164", () => {
    expect(normalizePhone("+15551234567")).toBe("+15551234567");
  });

  it("throws on invalid phone", () => {
    expect(() => normalizePhone("12345")).toThrow("Invalid phone number");
  });

  it("throws on empty string", () => {
    expect(() => normalizePhone("")).toThrow("Invalid phone number");
  });
});

// ---------------------------------------------------------------------------
// Dual delivery integration tests (mocked)
// ---------------------------------------------------------------------------

describe("Dual delivery logic", () => {
  // We test the handler logic by simulating the Promise.allSettled pattern

  async function simulateDualDelivery(
    crmFn: () => Promise<void>,
    emailFn: () => Promise<void>
  ) {
    const [crmResult, emailResult] = await Promise.allSettled([crmFn(), emailFn()]);
    const crmOk = crmResult.status === "fulfilled";
    const emailOk = emailResult.status === "fulfilled";

    if (!crmOk && !emailOk) return { status: 500, warning: false };
    if (!crmOk || !emailOk) return { status: 200, warning: true };
    return { status: 200, warning: false };
  }

  it("returns 200 when both succeed", async () => {
    const result = await simulateDualDelivery(
      async () => {},
      async () => {}
    );
    expect(result.status).toBe(200);
    expect(result.warning).toBe(false);
  });

  it("returns 200 with warning when CRM fails", async () => {
    const result = await simulateDualDelivery(
      async () => { throw new Error("CRM down"); },
      async () => {}
    );
    expect(result.status).toBe(200);
    expect(result.warning).toBe(true);
  });

  it("returns 200 with warning when email fails", async () => {
    const result = await simulateDualDelivery(
      async () => {},
      async () => { throw new Error("Email down"); }
    );
    expect(result.status).toBe(200);
    expect(result.warning).toBe(true);
  });

  it("returns 500 when both fail", async () => {
    const result = await simulateDualDelivery(
      async () => { throw new Error("CRM down"); },
      async () => { throw new Error("Email down"); }
    );
    expect(result.status).toBe(500);
    expect(result.warning).toBe(false);
  });
});

// ---------------------------------------------------------------------------
// Rate limiter tests
// ---------------------------------------------------------------------------

describe("Rate limiter", () => {
  beforeEach(async () => {
    const { resetRateLimitStore } = await import("../lib/rateLimit.js");
    resetRateLimitStore();
  });

  it("allows requests under the limit", async () => {
    const { checkRateLimit } = await import("../lib/rateLimit.js");
    for (let i = 0; i < 5; i++) {
      expect(checkRateLimit("1.2.3.4").allowed).toBe(true);
    }
  });

  it("blocks the 6th request from same IP", async () => {
    const { checkRateLimit } = await import("../lib/rateLimit.js");
    for (let i = 0; i < 5; i++) {
      checkRateLimit("1.2.3.4");
    }
    const result = checkRateLimit("1.2.3.4");
    expect(result.allowed).toBe(false);
    expect(result.retryAfterMs).toBeGreaterThan(0);
  });

  it("allows different IPs independently", async () => {
    const { checkRateLimit } = await import("../lib/rateLimit.js");
    for (let i = 0; i < 5; i++) {
      checkRateLimit("1.1.1.1");
    }
    expect(checkRateLimit("2.2.2.2").allowed).toBe(true);
  });
});
