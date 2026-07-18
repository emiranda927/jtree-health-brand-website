import { describe, it, expect, vi, beforeEach } from "vitest";

vi.mock("../lib/sheets.js", () => ({
  countRecentLeads: vi.fn(),
}));
vi.mock("../lib/email.js", () => ({
  sendAlertEmail: vi.fn(),
  escapeHtml: (s: string) => s,
}));

import handler from "../api/watchdog.js";
import { countRecentLeads } from "../lib/sheets.js";
import { sendAlertEmail } from "../lib/email.js";
import type { VercelRequest, VercelResponse } from "@vercel/node";

const mockedCount = vi.mocked(countRecentLeads);
const mockedAlert = vi.mocked(sendAlertEmail);

function mockRes() {
  const res = {
    statusCode: 0,
    body: undefined as unknown,
    status(code: number) {
      this.statusCode = code;
      return this;
    },
    json(payload: unknown) {
      this.body = payload;
      return this;
    },
  };
  return res;
}

const req = {} as VercelRequest;

beforeEach(() => {
  vi.clearAllMocks();
});

describe("watchdog handler", () => {
  it("does not alert when leads arrived, and reports alerted:false", async () => {
    mockedCount.mockResolvedValue(3);
    const res = mockRes();
    await handler(req, res as unknown as VercelResponse);
    expect(mockedAlert).not.toHaveBeenCalled();
    expect(res.statusCode).toBe(200);
    expect(res.body).toEqual({ checked: true, count: 3, alerted: false });
  });

  it("alerts on zero leads and reports alerted:true", async () => {
    mockedCount.mockResolvedValue(0);
    mockedAlert.mockResolvedValue();
    const res = mockRes();
    await handler(req, res as unknown as VercelResponse);
    expect(mockedAlert).toHaveBeenCalledTimes(1);
    expect(mockedAlert.mock.calls[0][0]).toContain("0 inquiries");
    expect(res.statusCode).toBe(200);
    expect(res.body).toEqual({ checked: true, count: 0, alerted: true });
  });

  it("returns 500 when the zero-lead alert cannot be sent", async () => {
    mockedCount.mockResolvedValue(0);
    mockedAlert.mockRejectedValue(new Error("OWNER_ALERT_EMAIL not set"));
    const res = mockRes();
    await handler(req, res as unknown as VercelResponse);
    expect(res.statusCode).toBe(500);
    expect(res.body).toEqual({ error: "Watchdog alert failed" });
  });

  it("alerts about an unreadable sheet and returns 500", async () => {
    mockedCount.mockRejectedValue(new Error("The caller does not have permission"));
    mockedAlert.mockResolvedValue();
    const res = mockRes();
    await handler(req, res as unknown as VercelResponse);
    expect(mockedAlert).toHaveBeenCalledTimes(1);
    expect(mockedAlert.mock.calls[0][0]).toContain("cannot read the leads sheet");
    expect(res.statusCode).toBe(500);
    expect(res.body).toEqual({ error: "Watchdog check failed" });
  });

  it("still returns 500 when both the sheet read and the failure alert fail", async () => {
    mockedCount.mockRejectedValue(new Error("sheet down"));
    mockedAlert.mockRejectedValue(new Error("OWNER_ALERT_EMAIL not set"));
    const res = mockRes();
    await handler(req, res as unknown as VercelResponse);
    expect(res.statusCode).toBe(500);
    expect(res.body).toEqual({ error: "Watchdog check failed" });
  });
});
