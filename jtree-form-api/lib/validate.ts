import { z } from "zod";

/**
 * Normalize a US phone number to E.164 format.
 * Accepts: (555) 123-4567, 555-123-4567, 5551234567, +15551234567, etc.
 */
export function normalizePhone(raw: string): string {
  const digits = raw.replace(/\D/g, "");

  if (digits.length === 10) {
    return `+1${digits}`;
  }
  if (digits.length === 11 && digits.startsWith("1")) {
    return `+${digits}`;
  }

  // Already includes country code with +
  if (raw.startsWith("+") && digits.length >= 10 && digits.length <= 15) {
    return `+${digits}`;
  }

  throw new Error("Invalid phone number");
}

export const ProgramInterest = z.enum(["IOP", "PHP", "Unsure"]);
export const BestTimeToCall = z.enum(["Morning", "Afternoon", "Evening"]);
export const HowDidYouHear = z.enum([
  "Google",
  "Referral",
  "Doctor",
  "School",
  "Other",
]);

export const InquirySchema = z.object({
  // One name — the form no longer asks "parent vs teen"; the pre-screen call
  // clarifies who this is and captures the patient's name.
  name: z
    .string()
    .min(1, "Please enter your name")
    .max(100, "Name must be 100 characters or fewer"),

  email: z
    .string()
    .email("Invalid email address")
    .max(100, "Email must be 100 characters or fewer"),

  phone: z
    .string()
    .min(1, "Phone number is required")
    .transform((val) => normalizePhone(val)),

  teen_age: z.coerce
    .number()
    .int("Age must be a whole number")
    .min(10, "Age must be between 10 and 17")
    .max(17, "Age must be between 10 and 17"),

  // Level-of-care interest (IOP / PHP / Unsure). Captured verbatim — this is
  // what the family says, not the program we assign them (staff set that in
  // the CRM at pre-screen).
  program_interest: ProgramInterest,

  // Optional, friction-reducing fields.
  best_time_to_call: BestTimeToCall.optional(),
  how_did_you_hear: HowDidYouHear.optional(),
  zip: z.string().max(10, "ZIP too long").optional(),
  insurance: z.string().max(60, "Insurance too long").optional(),
  notes: z.string().max(1000, "Please keep this under 1000 characters").optional(),

  // Marketing attribution — captured silently from the URL. Carried through to
  // the CRM so channel performance / CAC can be measured on completed leads.
  utm_source: z.string().max(100).optional(),
  utm_medium: z.string().max(100).optional(),
  utm_campaign: z.string().max(100).optional(),
  referrer: z.string().max(500).optional(),

  // Present when the tab generated one — links a completed lead to its partial.
  session_id: z
    .string()
    .max(64, "session_id too long")
    .regex(/^[A-Za-z0-9_-]*$/, "session_id has invalid characters")
    .optional(),

  // Cloudflare Turnstile challenge response. Verified server-side; the
  // verifier falls open when TURNSTILE_SECRET is unset, so this can stay
  // optional in the schema.
  cf_turnstile_response: z
    .string()
    .max(2048, "Turnstile token too long")
    .optional(),

  hp_field: z.string().max(0, "Invalid submission").optional().default(""),
});

export type InquiryInput = z.input<typeof InquirySchema>;
export type InquiryData = z.output<typeof InquirySchema>;

export interface Lead extends Omit<InquiryData, "hp_field"> {
  lead_id: string;
  submitted_at: string;
}

/**
 * Partial inquiry — fires when someone interacted with the form but didn't
 * submit. Product decision (2026-07-15): partials are now *followable* —
 * whatever contact info was entered before abandoning (name/email/phone) is
 * captured so the team can reach out, alongside the interest signals and
 * acquisition source. All fields except session_id are optional; a partial is
 * a best-effort snapshot of a half-filled form, so nothing here is required or
 * strictly validated (a half-typed email must not reject the whole beacon).
 */
export const PartialInquirySchema = z.object({
  session_id: z
    .string()
    .min(1, "session_id is required")
    .max(64, "session_id too long")
    .regex(/^[A-Za-z0-9_-]+$/, "session_id has invalid characters"),

  name: z.string().max(100).optional(),
  email: z.string().max(100).optional(),
  phone: z.string().max(30).optional(),

  teen_age: z.coerce.number().int().min(10).max(17).optional(),
  program_interest: ProgramInterest.optional(),
  best_time_to_call: BestTimeToCall.optional(),
  how_did_you_hear: HowDidYouHear.optional(),
  zip: z.string().max(10).optional(),
  insurance: z.string().max(60).optional(),
  notes: z.string().max(1000).optional(),

  utm_source: z.string().max(100).optional(),
  utm_medium: z.string().max(100).optional(),
  utm_campaign: z.string().max(100).optional(),
  referrer: z.string().max(500).optional(),

  hp_field: z.string().max(0).optional().default(""),
});

export type PartialInquiryInput = z.input<typeof PartialInquirySchema>;
export type PartialInquiryData = z.output<typeof PartialInquirySchema>;

export interface PartialLead
  extends Omit<PartialInquiryData, "hp_field"> {
  lead_id: string;
  submitted_at: string;
}

/**
 * Career application — separate funnel from clinical inquiry. Lighter
 * schema: name + email + role interest + free-form message. The optional
 * resume link lets the applicant paste a public Drive / Dropbox / LinkedIn
 * URL; we don't accept binary uploads in v1.
 */
export const CareerApplicationSchema = z.object({
  applicant_first_name: z
    .string()
    .min(1, "First name is required")
    .max(50),
  applicant_last_name: z
    .string()
    .min(1, "Last name is required")
    .max(50),
  applicant_email: z
    .string()
    .email("Invalid email address")
    .max(100),
  applicant_phone: z
    .string()
    .min(1, "Phone number is required")
    .transform((val) => normalizePhone(val)),
  role_interest: z
    .string()
    .min(1, "Tell us which role you're interested in")
    .max(80),
  message: z
    .string()
    .max(2000, "Message must be 2000 characters or fewer")
    .optional()
    .default(""),
  resume_url: z
    .string()
    .url("Resume link must be a full URL")
    .max(500)
    .optional(),

  consent_contact: z
    .union([z.boolean(), z.literal("true"), z.literal("on")])
    .transform((val) => val === true || val === "true" || val === "on")
    .refine((val) => val === true, "Consent to contact is required"),

  cf_turnstile_response: z.string().max(2048).optional(),
  hp_field: z.string().max(0).optional().default(""),
});

export type CareerApplicationInput = z.input<typeof CareerApplicationSchema>;
export type CareerApplicationData = z.output<typeof CareerApplicationSchema>;

export interface CareerApplication
  extends Omit<CareerApplicationData, "hp_field" | "consent_contact" | "cf_turnstile_response"> {
  application_id: string;
  submitted_at: string;
}
