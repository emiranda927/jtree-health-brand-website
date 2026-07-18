import { describe, it, expect, vi, beforeEach } from "vitest";
import type { VercelRequest, VercelResponse } from "@vercel/node";

// Mock every delivery module so these tests can assert the honeypot branch
// short-circuits BEFORE any delivery is attempted (and so a regression that
// slips past the branch can't hit the network).
vi.mock("../lib/supabase.js", () => ({
  sendToSupabase: vi.fn(),
  sendPartialToSupabase: vi.fn(),
}));
vi.mock("../lib/sheets.js", () => ({
  appendLeadToSheet: vi.fn(),
  appendPartialToSheet: vi.fn(),
}));
vi.mock("../lib/email.js", () => ({
  sendEmailToAdmissions: vi.fn(),
  sendCareerApplicationEmail: vi.fn(),
}));
vi.mock("../lib/turnstile.js", () => ({
  verifyTurnstile: vi.fn(async () => true),
}));

import inquiryHandler from "../api/inquiry.js";
import partialHandler from "../api/inquiry/partial.js";
import careersHandler from "../api/careers/apply.js";
import { resetRateLimitStore } from "../lib/rateLimit.js";
import { sendToSupabase, sendPartialToSupabase } from "../lib/supabase.js";
import { appendLeadToSheet, appendPartialToSheet } from "../lib/sheets.js";
import { sendEmailToAdmissions, sendCareerApplicationEmail } from "../lib/email.js";

function mockReq(body: unknown): VercelRequest {
  return { method: "POST", headers: {}, body, socket: {} } as unknown as VercelRequest;
}

interface CapturedRes {
  statusCode: number;
  body: unknown;
}

function mockRes(): VercelResponse & CapturedRes {
  const res = {
    statusCode: 0,
    body: undefined as unknown,
    setHeader() { return res; },
    status(code: number) { res.statusCode = code; return res; },
    json(payload: unknown) { res.body = payload; return res; },
    end() { return res; },
  };
  return res as unknown as VercelResponse & CapturedRes;
}

const validInquiry = {
  name: "Jane Doe",
  email: "jane@example.com",
  phone: "(555) 123-4567",
  teen_age: 14,
  program_interest: "IOP",
};

const validCareer = {
  applicant_first_name: "Sam",
  applicant_last_name: "Lopez",
  applicant_email: "sam@example.com",
  applicant_phone: "(555) 123-4567",
  role_interest: "Therapist (PHP / IOP)",
  consent_contact: true,
};

// The point of a honeypot: a bot that fills the hidden field must receive a
// response indistinguishable in status from a real success, and nothing may
// be delivered. These branches were dead code while the schema rejected any
// non-empty hp_field with a 422 that NAMED the field.
describe("honeypot handler branches", () => {
  beforeEach(() => {
    resetRateLimitStore();
    vi.clearAllMocks();
  });

  it("inquiry: filled honeypot gets a silent 200 and no delivery", async () => {
    const res = mockRes();
    await inquiryHandler(mockReq({ ...validInquiry, hp_field: "bot-filled" }), res);

    expect(res.statusCode).toBe(200);
    expect(res.body).toEqual({
      success: true,
      message: "Thank you! Our admissions team will contact you shortly.",
    });
    expect(sendToSupabase).not.toHaveBeenCalled();
    expect(appendLeadToSheet).not.toHaveBeenCalled();
    expect(sendEmailToAdmissions).not.toHaveBeenCalled();
  });

  it("inquiry: honeypot response is indistinguishable from a real success", async () => {
    const botRes = mockRes();
    await inquiryHandler(mockReq({ ...validInquiry, hp_field: "bot-filled" }), botRes);

    const realRes = mockRes();
    await inquiryHandler(mockReq({ ...validInquiry, hp_field: "" }), realRes);

    expect(botRes.statusCode).toBe(realRes.statusCode);
    expect(botRes.body).toEqual(realRes.body);
  });

  it("inquiry: empty honeypot still delivers normally", async () => {
    const res = mockRes();
    await inquiryHandler(mockReq({ ...validInquiry, hp_field: "" }), res);

    expect(res.statusCode).toBe(200);
    expect(sendToSupabase).toHaveBeenCalledTimes(1);
    expect(sendEmailToAdmissions).toHaveBeenCalledTimes(1);
  });

  it("partial: filled honeypot gets the usual 204 and no delivery", async () => {
    const res = mockRes();
    await partialHandler(mockReq({ session_id: "sid_test", hp_field: "bot" }), res);

    expect(res.statusCode).toBe(204);
    expect(sendPartialToSupabase).not.toHaveBeenCalled();
    expect(appendPartialToSheet).not.toHaveBeenCalled();
  });

  it("partial: clean beacon still delivers", async () => {
    const res = mockRes();
    await partialHandler(mockReq({ session_id: "sid_test", teen_age: 14 }), res);

    expect(res.statusCode).toBe(204);
    expect(sendPartialToSupabase).toHaveBeenCalledTimes(1);
  });

  it("careers: filled honeypot gets a silent 200 and no email", async () => {
    const res = mockRes();
    await careersHandler(mockReq({ ...validCareer, hp_field: "bot-filled" }), res);

    expect(res.statusCode).toBe(200);
    expect(res.body).toEqual({
      success: true,
      message: "Thanks — we'll be in touch.",
    });
    expect(sendCareerApplicationEmail).not.toHaveBeenCalled();
  });

  it("careers: honeypot response is indistinguishable from a real success", async () => {
    const botRes = mockRes();
    await careersHandler(mockReq({ ...validCareer, hp_field: "bot-filled" }), botRes);

    const realRes = mockRes();
    await careersHandler(mockReq({ ...validCareer, hp_field: "" }), realRes);

    expect(botRes.statusCode).toBe(realRes.statusCode);
    expect(botRes.body).toEqual(realRes.body);
  });
});
