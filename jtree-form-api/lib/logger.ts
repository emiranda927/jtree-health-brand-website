/**
 * Structured logger that NEVER persists PII or request bodies.
 * Only lead_id, timestamp, delivery statuses, and error codes are logged.
 */

type LogLevel = "info" | "warn" | "error";

interface LogEntry {
  level: LogLevel;
  timestamp: string;
  lead_id?: string;
  message: string;
  delivery?: {
    crm?: "success" | "failed" | "skipped";
    email?: "success" | "failed" | "skipped";
    supabase?: "success" | "failed" | "skipped";
    sheets?: "success" | "failed" | "skipped";
  };
  error_code?: string;
}

function emit(entry: LogEntry): void {
  const line = JSON.stringify(entry);
  if (entry.level === "error") {
    console.error(line);
  } else if (entry.level === "warn") {
    console.warn(line);
  } else {
    console.log(line);
  }
}

export const logger = {
  info(message: string, meta?: Partial<Omit<LogEntry, "level" | "message" | "timestamp">>) {
    emit({ level: "info", timestamp: new Date().toISOString(), message, ...meta });
  },

  warn(message: string, meta?: Partial<Omit<LogEntry, "level" | "message" | "timestamp">>) {
    emit({ level: "warn", timestamp: new Date().toISOString(), message, ...meta });
  },

  error(message: string, meta?: Partial<Omit<LogEntry, "level" | "message" | "timestamp">>) {
    emit({ level: "error", timestamp: new Date().toISOString(), message, ...meta });
  },
};
