import { describe, it, expect } from "vitest";
import { countRecentLeadRows } from "../lib/sheets.js";

// Rows are Leads!B:K slices: index 0 = submitted_at, index 9 = status.
function row(ts: string, status?: string): unknown[] {
  const r: unknown[] = [ts, "Jane", "", "jane@example.com", "+15551234567", "14", "IOP", "", ""];
  r[9] = status;
  return r;
}

const NOW = Date.parse("2026-07-18T18:00:00.000Z");
const DAY = 24 * 60 * 60 * 1000;

describe("countRecentLeadRows", () => {
  it("counts an ISO-timestamped lead inside the window", () => {
    const rows = [row("2026-07-18T10:00:00.000Z", "lead")];
    expect(countRecentLeadRows(rows, DAY, NOW)).toBe(1);
  });

  it("does not count an ISO-timestamped lead outside the window", () => {
    const rows = [row("2026-07-11T10:00:00.000Z", "lead")];
    expect(countRecentLeadRows(rows, DAY, NOW)).toBe(0);
  });

  it("counts legacy rows with a blank or missing status as leads", () => {
    const rows = [row("2026-07-18T10:00:00.000Z", ""), row("2026-07-18T11:00:00.000Z", undefined)];
    expect(countRecentLeadRows(rows, DAY, NOW)).toBe(2);
  });

  it("does not count partial rows", () => {
    const rows = [row("2026-07-18T10:00:00.000Z", "partial")];
    expect(countRecentLeadRows(rows, DAY, NOW)).toBe(0);
  });

  it("does not count the header row", () => {
    const rows = [row("submitted_at", "status")];
    expect(countRecentLeadRows(rows, DAY, NOW)).toBe(0);
  });

  it("does not count a stale hand-formatted date as recent (lexicographic trap)", () => {
    // "7/15/2026" >= "2026-07-17T..." is TRUE as a string compare ("7" > "2"),
    // which silenced the watchdog forever. Date.parse must see it as July 15.
    const rows = [row("7/15/2026", ""), row("7/15/2026 10:30:00", "lead")];
    expect(countRecentLeadRows(rows, 2 * DAY, Date.parse("2026-07-18T18:00:00.000Z"))).toBe(0);
  });

  it("counts a hand-formatted date that really is inside the window", () => {
    // Parsed in the function's local zone; use a generous window so the test
    // is timezone-independent.
    const rows = [row("7/18/2026", "lead")];
    expect(countRecentLeadRows(rows, 2 * DAY, Date.parse("2026-07-19T12:00:00.000Z"))).toBe(1);
  });

  it("ignores unparseable and non-string timestamps", () => {
    const rows = [row("not a date", "lead"), [42, "", "", "", "", "", "", "", "", "lead"], []];
    expect(countRecentLeadRows(rows, DAY, NOW)).toBe(0);
  });
});
