import { describe, it, expect, beforeEach, afterEach } from "vitest";
import { sendAlertEmail } from "../lib/email.js";

const originalOwner = process.env.OWNER_ALERT_EMAIL;
const originalResendKey = process.env.RESEND_API_KEY;

afterEach(() => {
  if (originalOwner === undefined) delete process.env.OWNER_ALERT_EMAIL;
  else process.env.OWNER_ALERT_EMAIL = originalOwner;
  if (originalResendKey === undefined) delete process.env.RESEND_API_KEY;
  else process.env.RESEND_API_KEY = originalResendKey;
});

describe("sendAlertEmail configuration guards", () => {
  beforeEach(() => {
    delete process.env.OWNER_ALERT_EMAIL;
    delete process.env.RESEND_API_KEY;
  });

  it("throws (not silent no-op) when OWNER_ALERT_EMAIL is unset", async () => {
    process.env.RESEND_API_KEY = "re_test_key";
    await expect(sendAlertEmail("subject", "<p>body</p>")).rejects.toThrow(
      "OWNER_ALERT_EMAIL not set"
    );
  });

  it("throws when OWNER_ALERT_EMAIL is an empty string", async () => {
    process.env.OWNER_ALERT_EMAIL = "";
    process.env.RESEND_API_KEY = "re_test_key";
    await expect(sendAlertEmail("subject", "<p>body</p>")).rejects.toThrow(
      "OWNER_ALERT_EMAIL not set"
    );
  });

  it("throws when RESEND_API_KEY is unset", async () => {
    process.env.OWNER_ALERT_EMAIL = "owner@example.com";
    await expect(sendAlertEmail("subject", "<p>body</p>")).rejects.toThrow(
      "RESEND_API_KEY not set"
    );
  });
});
