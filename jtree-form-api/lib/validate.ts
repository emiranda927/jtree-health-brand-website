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

export const ProgramInterest = z.enum(["IOP", "PHP", "Not sure"]);
export const BestTimeToCall = z.enum(["Morning", "Afternoon", "Evening"]);
export const HowDidYouHear = z.enum([
  "Google",
  "Referral",
  "Doctor",
  "School",
  "Other",
]);

export const InquirySchema = z.object({
  parent_first_name: z
    .string()
    .min(1, "First name is required")
    .max(50, "First name must be 50 characters or fewer"),

  parent_last_name: z
    .string()
    .min(1, "Last name is required")
    .max(50, "Last name must be 50 characters or fewer"),

  parent_email: z
    .string()
    .email("Invalid email address")
    .max(100, "Email must be 100 characters or fewer"),

  parent_phone: z
    .string()
    .min(1, "Phone number is required")
    .transform((val) => normalizePhone(val)),

  teen_age: z.coerce
    .number()
    .int("Age must be a whole number")
    .min(10, "Age must be between 10 and 17")
    .max(17, "Age must be between 10 and 17"),

  program_interest: ProgramInterest,

  best_time_to_call: BestTimeToCall,

  how_did_you_hear: HowDidYouHear.optional(),

  consent_contact: z
    .union([z.boolean(), z.literal("true"), z.literal("on")])
    .transform((val) => val === true || val === "true" || val === "on")
    .refine((val) => val === true, "Consent to contact is required"),

  // Optional — present only when the form's partial-capture path generated
  // one in this tab. Lets admissions correlate a partial → completed lead.
  session_id: z
    .string()
    .max(64, "session_id too long")
    .regex(/^[A-Za-z0-9_-]*$/, "session_id has invalid characters")
    .optional(),

  hp_field: z.string().max(0, "Invalid submission").optional().default(""),
});

export type InquiryInput = z.input<typeof InquirySchema>;
export type InquiryData = z.output<typeof InquirySchema>;

export interface Lead extends Omit<InquiryData, "hp_field" | "consent_contact"> {
  lead_id: string;
  submitted_at: string;
  session_id?: string;
}

/**
 * Partial inquiry — fires when a parent interacted with the form but didn't
 * submit. By design, carries NO PII (no name, email, phone): only the
 * non-identifying interest signals + a session_id + acquisition source.
 *
 * Privacy contract: a partial row should never expose who the parent is.
 * Adding any PII field here is a privacy regression — talk to product first.
 */
export const PartialInquirySchema = z.object({
  session_id: z
    .string()
    .min(1, "session_id is required")
    .max(64, "session_id too long")
    .regex(/^[A-Za-z0-9_-]+$/, "session_id has invalid characters"),

  teen_age: z.coerce.number().int().min(10).max(17).optional(),
  program_interest: ProgramInterest.optional(),
  best_time_to_call: BestTimeToCall.optional(),
  how_did_you_hear: HowDidYouHear.optional(),

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
