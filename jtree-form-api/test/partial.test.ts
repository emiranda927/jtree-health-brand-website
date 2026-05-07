import { describe, it, expect } from "vitest";
import { PartialInquirySchema, InquirySchema } from "../lib/validate.js";

const validPartial = {
  session_id: "abc123XYZ_test-session",
  teen_age: 14,
  program_interest: "IOP",
  best_time_to_call: "Morning",
  how_did_you_hear: "Google",
  utm_source: "google",
  utm_medium: "cpc",
  utm_campaign: "iop_apex_q2",
  referrer: "https://www.google.com/",
  hp_field: "",
};

describe("PartialInquirySchema validation", () => {
  it("accepts a fully populated partial", () => {
    const result = PartialInquirySchema.safeParse(validPartial);
    expect(result.success).toBe(true);
  });

  it("accepts a minimal partial with just session_id", () => {
    const result = PartialInquirySchema.safeParse({ session_id: "sid_minimal" });
    expect(result.success).toBe(true);
  });

  it("rejects a partial with no session_id", () => {
    const { session_id, ...input } = validPartial;
    const result = PartialInquirySchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("rejects a session_id with disallowed characters", () => {
    const result = PartialInquirySchema.safeParse({
      session_id: "<script>alert(1)</script>",
    });
    expect(result.success).toBe(false);
  });

  it("rejects a session_id over 64 chars", () => {
    const result = PartialInquirySchema.safeParse({
      session_id: "a".repeat(65),
    });
    expect(result.success).toBe(false);
  });

  it("silently strips PII fields if a misconfigured client sends them", () => {
    // Privacy guarantee: PII is not part of the partial schema, so Zod's
    // default object behavior strips it from the parsed output. This test
    // pins that behavior — if it ever changes, the privacy story changes.
    const result = PartialInquirySchema.safeParse({
      session_id: "sid_test",
      parent_first_name: "Jane",
      parent_email: "jane@example.com",
      parent_phone: "555-1234",
    });
    expect(result.success).toBe(true);
    if (result.success) {
      expect(result.data).not.toHaveProperty("parent_first_name");
      expect(result.data).not.toHaveProperty("parent_email");
      expect(result.data).not.toHaveProperty("parent_phone");
    }
  });

  it("rejects an out-of-range teen_age", () => {
    const result = PartialInquirySchema.safeParse({
      session_id: "sid_test",
      teen_age: 21,
    });
    expect(result.success).toBe(false);
  });

  it("rejects an unknown program_interest", () => {
    const result = PartialInquirySchema.safeParse({
      session_id: "sid_test",
      program_interest: "Residential",
    });
    expect(result.success).toBe(false);
  });

  it("detects honeypot content", () => {
    const result = PartialInquirySchema.safeParse({
      session_id: "sid_test",
      hp_field: "bot",
    });
    expect(result.success).toBe(false);
  });

  it("caps utm fields at 100 chars", () => {
    const result = PartialInquirySchema.safeParse({
      session_id: "sid_test",
      utm_source: "x".repeat(101),
    });
    expect(result.success).toBe(false);
  });

  it("caps referrer at 500 chars", () => {
    const result = PartialInquirySchema.safeParse({
      session_id: "sid_test",
      referrer: "x".repeat(501),
    });
    expect(result.success).toBe(false);
  });
});

describe("InquirySchema accepts session_id (correlation contract)", () => {
  // The full inquiry path also accepts session_id so a partial can be
  // matched to the eventual lead row in admissions' sheet. Pin that here.
  const validFull = {
    parent_first_name: "Jane",
    parent_last_name: "Doe",
    parent_email: "jane@example.com",
    parent_phone: "(555) 123-4567",
    teen_age: 14,
    program_interest: "IOP",
    best_time_to_call: "Morning",
    consent_contact: true,
    session_id: "sid_full_submit",
    hp_field: "",
  };

  it("accepts a full inquiry that carries a session_id", () => {
    const result = InquirySchema.safeParse(validFull);
    expect(result.success).toBe(true);
    if (result.success) {
      expect(result.data.session_id).toBe("sid_full_submit");
    }
  });

  it("still accepts a full inquiry without session_id (back-compat)", () => {
    const { session_id, ...input } = validFull;
    const result = InquirySchema.safeParse(input);
    expect(result.success).toBe(true);
  });

  it("rejects a session_id with disallowed characters on full inquiry", () => {
    const result = InquirySchema.safeParse({
      ...validFull,
      session_id: "bad chars!",
    });
    expect(result.success).toBe(false);
  });
});
