/**
 * Cloudflare Turnstile verifier.
 *
 * Falls open when TURNSTILE_SECRET is unset — keeps dev/staging unblocked
 * without a captcha keypair. In production, set the env var and the verifier
 * gates every full inquiry submission.
 *
 * Partial submissions are NOT verified: they ride sendBeacon, which cannot
 * carry a captcha challenge response, and the partial path already returns
 * 204 silently with no useful response shape for an attacker.
 */
import { logger } from "./logger.js";

const VERIFY_URL = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
const REQUEST_TIMEOUT_MS = 4000;

export function isTurnstileConfigured(): boolean {
  return !!process.env.TURNSTILE_SECRET;
}

export async function verifyTurnstile(
  token: string | undefined,
  remoteIp: string,
  context: { lead_id?: string } = {}
): Promise<boolean> {
  if (!isTurnstileConfigured()) {
    logger.info("Turnstile not configured — skipping verification");
    return true;
  }

  if (!token || token.length < 10) {
    logger.warn("Turnstile token missing or too short", {
      error_code: "TURNSTILE_TOKEN_MISSING",
      ...context,
    });
    return false;
  }

  const body = new URLSearchParams();
  body.set("secret", process.env.TURNSTILE_SECRET!);
  body.set("response", token);
  if (remoteIp && remoteIp !== "unknown") {
    body.set("remoteip", remoteIp);
  }

  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), REQUEST_TIMEOUT_MS);

  try {
    const res = await fetch(VERIFY_URL, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body,
      signal: controller.signal,
    });

    if (!res.ok) {
      logger.error("Turnstile verify endpoint returned non-2xx", {
        error_code: `TURNSTILE_HTTP_${res.status}`,
        ...context,
      });
      return false;
    }

    const json = (await res.json()) as { success?: boolean; "error-codes"?: string[] };
    if (!json.success) {
      logger.warn("Turnstile verification failed", {
        error_code: `TURNSTILE_REJECTED_${(json["error-codes"] ?? ["unknown"]).join("_")}`,
        ...context,
      });
      return false;
    }

    return true;
  } catch (err) {
    const aborted = (err as Error)?.name === "AbortError";
    logger.error("Turnstile verify request errored", {
      error_code: aborted ? "TURNSTILE_TIMEOUT" : "TURNSTILE_NETWORK_ERROR",
      ...context,
    });
    return false;
  } finally {
    clearTimeout(timeout);
  }
}
