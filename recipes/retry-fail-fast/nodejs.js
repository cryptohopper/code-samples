// Retry on transient errors, fail fast on auth/validation errors.
//
// The SDK auto-retries 429s. For 5xx and network errors you may want a
// tighter retry. Auth errors should *never* be retried — they only get
// worse on each attempt (and 401 in particular won't change).
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-bearer
//   node nodejs.js

import { CryptohopperClient, CryptohopperError } from "@cryptohopper/sdk";

const FAIL_FAST_CODES = new Set([
  "UNAUTHORIZED",
  "FORBIDDEN",
  "NOT_FOUND",
  "VALIDATION_ERROR",
  "DEVICE_UNAUTHORIZED",
]);

async function withRetry(fn, { maxAttempts = 3 } = {}) {
  for (let attempt = 0; attempt < maxAttempts; attempt++) {
    try {
      return await fn();
    } catch (e) {
      const isFailFast =
        e instanceof CryptohopperError && FAIL_FAST_CODES.has(e.code);
      if (isFailFast) throw e;
      if (attempt + 1 === maxAttempts) throw e;
      const backoff = 500 * 2 ** attempt;
      console.error(`  retry ${attempt + 1}/${maxAttempts} after ${backoff}ms (${e.code ?? e.message})`);
      await new Promise((r) => setTimeout(r, backoff));
    }
  }
}

const token = process.env.CRYPTOHOPPER_TOKEN;
if (!token) {
  console.error("Set CRYPTOHOPPER_TOKEN");
  process.exit(1);
}

const ch = new CryptohopperClient({ apiKey: token });
const me = await withRetry(() => ch.user.get());
console.log(`Logged in as ${me.email ?? me.username ?? me.id}`);
