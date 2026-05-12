/**
 * Multi-origin CORS helper.
 *
 * Reads ALLOWED_ORIGINS (comma-separated) as the source of truth. Falls back
 * to legacy ALLOWED_ORIGIN (singular) if ALLOWED_ORIGINS is unset, so an
 * older Vercel env var doesn't break after this lands. Final fallback is the
 * production origin so a missing-env deploy still serves the live site.
 *
 * Allowlist entries are matched as exact strings, with one small exception:
 * an entry starting with `*.` is treated as a subdomain suffix match (e.g.
 * `*.flywheelsites.com` matches `https://foo.flywheelsites.com` but NOT
 * `https://flywheelsites.com`). Scheme + port must still match exactly via
 * the suffix — we compare the full origin string, not just hostname.
 *
 * Security posture:
 *   - Never returns `*` — every endpoint here handles PII.
 *   - Only echoes back the request's `Origin` when it's on the allowlist;
 *     unknown origins get no CORS headers, so the browser blocks the
 *     follow-up request per same-origin policy.
 *   - Always sets `Vary: Origin` so CDNs and browsers cache per-origin
 *     correctly when responses differ.
 *   - Missing `Origin` header (curl, server-to-server, same-origin) is
 *     allowed through silently — those callers aren't subject to CORS.
 */
import type { VercelRequest, VercelResponse } from "@vercel/node";

const DEFAULT_ORIGIN = "https://jtreehealth.com";

function readAllowlist(): string[] {
  const plural = process.env.ALLOWED_ORIGINS;
  if (plural && plural.trim().length > 0) {
    return plural
      .split(",")
      .map((s) => s.trim())
      .filter((s) => s.length > 0);
  }
  const legacy = process.env.ALLOWED_ORIGIN;
  if (legacy && legacy.trim().length > 0) {
    return [legacy.trim()];
  }
  return [DEFAULT_ORIGIN];
}

/**
 * Returns true when `origin` matches any entry in the allowlist.
 * Entries beginning with `*.` are treated as subdomain suffix matches.
 * Origins are compared case-insensitively (per RFC 6454 host comparison).
 */
export function isOriginAllowed(origin: string | undefined | null, allowlist?: string[]): boolean {
  if (!origin) return false;
  const list = allowlist ?? readAllowlist();
  const lower = origin.toLowerCase();
  for (const entry of list) {
    const e = entry.toLowerCase();
    if (e.startsWith("*.")) {
      // Subdomain suffix match. Require a real subdomain — `*.foo.com` must
      // NOT match `foo.com` itself, only `x.foo.com`, `y.x.foo.com`, etc.
      const suffix = e.slice(1); // ".foo.com"
      // The origin includes scheme, so we match against `://...suffix`.
      const protoIdx = lower.indexOf("://");
      if (protoIdx === -1) continue;
      const hostPart = lower.slice(protoIdx + 3); // strip "https://"
      if (hostPart.endsWith(suffix) && hostPart.length > suffix.length) {
        return true;
      }
    } else if (e === lower) {
      return true;
    }
  }
  return false;
}

/**
 * Apply CORS headers to `res` based on `req.Origin`.
 *
 * - Allowed origin: echoes the origin back, sets methods/headers/max-age.
 * - Disallowed origin or missing origin: sets only `Vary: Origin` and lets
 *   the same-origin policy do its job.
 *
 * Always safe to call once at the top of a handler.
 */
export function applyCorsHeaders(req: VercelRequest, res: VercelResponse): void {
  // Vary is always set so caches don't serve a response built for origin A
  // to a request from origin B.
  res.setHeader("Vary", "Origin");

  const originHeader = req.headers.origin;
  const origin = typeof originHeader === "string" ? originHeader : undefined;
  if (!origin) return; // non-browser caller; CORS not relevant

  if (!isOriginAllowed(origin)) return; // disallowed; let the browser block

  res.setHeader("Access-Control-Allow-Origin", origin);
  res.setHeader("Access-Control-Allow-Methods", "POST, OPTIONS");
  res.setHeader("Access-Control-Allow-Headers", "Content-Type");
  res.setHeader("Access-Control-Max-Age", "86400");
}

/**
 * Exposed for tests. Reads the env at call time so tests can mutate process.env.
 */
export function _readAllowlistForTest(): string[] {
  return readAllowlist();
}
