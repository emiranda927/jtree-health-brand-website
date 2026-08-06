/**
 * Covers the fields added so admissions can work a lead without re-keying it:
 * the teen's name, and a single referral-attribution shape shared by the parent
 * form (/get-started) and the provider Quick referral (/refer).
 */
import { describe, it, expect } from "vitest";
import { InquirySchema, type Lead } from "../lib/validate.js";
import { buildLeadRow } from "../lib/sheets.js";
import { buildLeadEmailSubject } from "../lib/email.js";
import { buildClientRecord } from "../lib/supabase.js";

const base = {
  lead_id: "JT-test123456",
  submitted_at: "2026-08-05T14:00:00.000Z",
  email: "parent@example.com",
  phone: "+19195550142",
  teen_age: 15,
  program_interest: "IOP",
} as const;

const parentLead: Lead = {
  ...base,
  name: "Dana Reyes",
  client_name: "Sam Reyes",
  lead_type: "self",
};

const providerLead: Lead = {
  ...base,
  name: "Dr. Jane Smith",
  client_name: "J.M.",
  lead_type: "provider",
  email: "jsmith@northsidepeds.com",
  how_did_you_hear: "Referral",
  referral_organization: "Northside Pediatrics",
  referral_provider_name: "Dr. Jane Smith",
  referral_provider_contact: "jsmith@northsidepeds.com",
  notes: "Provider referral (via website Quick referral).\nRole: Pediatrician / PCP",
};

describe("InquirySchema", () => {
  it("accepts the new fields from the parent form", () => {
    const parsed = InquirySchema.safeParse({
      name: "Dana Reyes",
      client_name: "Sam Reyes",
      lead_type: "self",
      email: "parent@example.com",
      phone: "9195550142",
      teen_age: "15",
      program_interest: "IOP",
      referral_provider_name: "Dr. Jane Smith",
      referral_organization: "Northside Pediatrics",
      referral_provider_contact: "jsmith@northsidepeds.com",
    });
    expect(parsed.success).toBe(true);
    if (parsed.success) {
      expect(parsed.data.client_name).toBe("Sam Reyes");
      expect(parsed.data.referral_provider_name).toBe("Dr. Jane Smith");
    }
  });

  it("defaults lead_type to self", () => {
    const parsed = InquirySchema.parse({
      name: "Dana Reyes",
      email: "parent@example.com",
      phone: "9195550142",
      teen_age: 15,
      program_interest: "IOP",
    });
    expect(parsed.lead_type).toBe("self");
  });

  it("still accepts a submission with no client_name", () => {
    // Required in the browser, optional here: the site and this API deploy from
    // the same push, so a page already open on the previous build must not 422.
    const parsed = InquirySchema.safeParse({
      name: "Dana Reyes",
      email: "parent@example.com",
      phone: "9195550142",
      teen_age: 15,
      program_interest: "IOP",
    });
    expect(parsed.success).toBe(true);
  });
});

describe("buildLeadRow", () => {
  it("puts the teen's name in column D and the referral block in N–P", () => {
    const row = buildLeadRow(providerLead);
    expect(row).toHaveLength(17);
    expect(row[2]).toBe("Dr. Jane Smith"); // C: submitter
    expect(row[3]).toBe("J.M."); // D: client
    expect(row[10]).toBe("lead"); // K: status, read by the watchdog
    expect(row[12]).toBe("provider"); // M
    expect(row[13]).toBe("Northside Pediatrics"); // N
    expect(row[14]).toBe("Dr. Jane Smith"); // O
    expect(row[15]).toBe("jsmith@northsidepeds.com"); // P
  });

  it("carries notes into column Q", () => {
    // Referral role/timeframe/reason live here. The old 12-column row dropped
    // this entirely, which is why provider referrals read as blank rows.
    expect(buildLeadRow(providerLead)[16]).toContain("Pediatrician / PCP");
  });

  it("leaves the referral columns empty for a plain parent inquiry", () => {
    const row = buildLeadRow(parentLead);
    expect(row.slice(13, 17)).toEqual(["", "", "", ""]);
  });

  it("keeps B and K where countRecentLeads expects them", () => {
    const row = buildLeadRow(parentLead);
    expect(row[1]).toBe("2026-08-05T14:00:00.000Z");
    expect(row[10]).toBe("lead");
  });
});

describe("buildLeadEmailSubject", () => {
  it("leads with the teen, not the parent", () => {
    expect(buildLeadEmailSubject(parentLead)).toBe("New inquiry: Sam Reyes (IOP)");
  });

  it("calls a provider referral what it is", () => {
    expect(buildLeadEmailSubject(providerLead)).toBe("New provider referral: J.M. (IOP)");
  });

  it("falls back to the submitter when there is no client name", () => {
    expect(buildLeadEmailSubject({ ...parentLead, client_name: undefined })).toBe(
      "New inquiry: Dana Reyes (IOP)"
    );
  });

  it("says patient TBD for a referral with no initials", () => {
    expect(buildLeadEmailSubject({ ...providerLead, client_name: undefined })).toBe(
      "New provider referral: patient TBD (IOP)"
    );
  });
});

describe("buildClientRecord", () => {
  it("titles the card after the teen and moves the submitter to guardian", () => {
    const { data } = buildClientRecord(parentLead, 1);
    expect(data.name).toBe("Sam Reyes");
    expect(data.guardian).toBe("Dana Reyes");
  });

  it("does not title a provider referral after the referring clinician", () => {
    const { data } = buildClientRecord(providerLead, 1);
    expect(data.name).toBe("J.M.");
    expect(data.guardian).toBe("Dr. Jane Smith");
  });

  it("falls back to the submitter's name when no client name was given", () => {
    const { data } = buildClientRecord({ ...parentLead, client_name: undefined }, 1);
    expect(data.name).toBe("Dana Reyes");
    expect(data.guardian).toBe("");
  });

  it("surfaces referral attribution as first-class fields", () => {
    const { data } = buildClientRecord(providerLead, 1);
    expect(data.leadType).toBe("provider");
    expect(data.referralOrg).toBe("Northside Pediatrics");
    expect(data.referredBy).toBe("Dr. Jane Smith");
    expect(data.referredByContact).toBe("jsmith@northsidepeds.com");
  });

  it("omits referral fields entirely on a plain parent inquiry", () => {
    const { data } = buildClientRecord(parentLead, 1);
    expect(data.referralOrg).toBeUndefined();
    expect(data.referredBy).toBeUndefined();
  });
});
