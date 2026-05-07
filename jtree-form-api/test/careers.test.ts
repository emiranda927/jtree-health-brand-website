import { describe, it, expect } from "vitest";
import { CareerApplicationSchema } from "../lib/validate.js";

const validApplication = {
  applicant_first_name: "Sam",
  applicant_last_name: "Lopez",
  applicant_email: "sam@example.com",
  applicant_phone: "(555) 123-4567",
  role_interest: "Therapist (PHP / IOP)",
  message: "Five years of adolescent IOP experience.",
  resume_url: "https://www.linkedin.com/in/sam-lopez/",
  consent_contact: true,
  hp_field: "",
};

describe("CareerApplicationSchema validation", () => {
  it("accepts a complete application", () => {
    const result = CareerApplicationSchema.safeParse(validApplication);
    expect(result.success).toBe(true);
    if (result.success) {
      expect(result.data.applicant_phone).toBe("+15551234567");
    }
  });

  it("accepts an application without a resume URL", () => {
    const { resume_url, ...input } = validApplication;
    const result = CareerApplicationSchema.safeParse(input);
    expect(result.success).toBe(true);
  });

  it("accepts an application without a message", () => {
    const { message, ...input } = validApplication;
    const result = CareerApplicationSchema.safeParse(input);
    expect(result.success).toBe(true);
    if (result.success) {
      expect(result.data.message).toBe("");
    }
  });

  it("rejects a non-URL resume value", () => {
    const result = CareerApplicationSchema.safeParse({
      ...validApplication,
      resume_url: "not a url",
    });
    expect(result.success).toBe(false);
  });

  it("rejects a missing role_interest", () => {
    const result = CareerApplicationSchema.safeParse({
      ...validApplication,
      role_interest: "",
    });
    expect(result.success).toBe(false);
  });

  it("rejects a missing consent_contact", () => {
    const { consent_contact, ...input } = validApplication;
    const result = CareerApplicationSchema.safeParse(input);
    expect(result.success).toBe(false);
  });

  it("detects honeypot content", () => {
    const result = CareerApplicationSchema.safeParse({
      ...validApplication,
      hp_field: "bot-filled",
    });
    expect(result.success).toBe(false);
  });

  it("caps message at 2000 chars", () => {
    const result = CareerApplicationSchema.safeParse({
      ...validApplication,
      message: "x".repeat(2001),
    });
    expect(result.success).toBe(false);
  });
});
