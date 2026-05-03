/**
 * In-memory IP-based rate limiter.
 * 5 requests per 10 minutes per IP.
 * Expired entries are cleaned up on each check.
 */

const WINDOW_MS = 10 * 60 * 1000; // 10 minutes
const MAX_REQUESTS = 5;

const store = new Map<string, number[]>();

// Periodic cleanup every 5 minutes to prevent memory leaks
let cleanupTimer: ReturnType<typeof setInterval> | null = null;

function ensureCleanupTimer() {
  if (cleanupTimer) return;
  cleanupTimer = setInterval(() => {
    const now = Date.now();
    for (const [ip, timestamps] of store) {
      const valid = timestamps.filter((t) => now - t < WINDOW_MS);
      if (valid.length === 0) {
        store.delete(ip);
      } else {
        store.set(ip, valid);
      }
    }
  }, 5 * 60 * 1000);
  // Allow the process to exit even if the timer is still running
  if (cleanupTimer && typeof cleanupTimer === "object" && "unref" in cleanupTimer) {
    cleanupTimer.unref();
  }
}

export function checkRateLimit(ip: string): { allowed: boolean; retryAfterMs?: number } {
  ensureCleanupTimer();

  const now = Date.now();
  const timestamps = store.get(ip) ?? [];

  // Remove expired entries
  const valid = timestamps.filter((t) => now - t < WINDOW_MS);

  if (valid.length >= MAX_REQUESTS) {
    const oldestInWindow = valid[0]!;
    const retryAfterMs = WINDOW_MS - (now - oldestInWindow);
    return { allowed: false, retryAfterMs };
  }

  valid.push(now);
  store.set(ip, valid);
  return { allowed: true };
}

/** Reset the store — used in tests */
export function resetRateLimitStore(): void {
  store.clear();
}
