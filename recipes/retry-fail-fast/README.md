# Recipe — Retry on transient errors, fail fast on auth errors

The SDK already retries 429s with `Retry-After`. You'd wrap *additional* retry on top for **5xx server errors and network errors** — both are transient. But you should **never retry auth/validation errors**: they're not going to fix themselves and each attempt makes the problem worse (logs flooded, rate-limit budget burnt).

## The classification

| Error code | Retry? | Reason |
|------------|--------|--------|
| `UNAUTHORIZED` | ❌ no | Token's bad. Won't get better. Re-issue. |
| `FORBIDDEN` | ❌ no | Missing scope or IP allowlist. Won't get better. |
| `NOT_FOUND` | ❌ no | Resource doesn't exist. |
| `VALIDATION_ERROR` | ❌ no | Bad request. Fix the call. |
| `DEVICE_UNAUTHORIZED` | ❌ no | Mobile-flow only. |
| `RATE_LIMITED` | (handled by SDK) | SDK auto-retries with `Retry-After`. |
| `SERVER_ERROR` | ✅ yes | Upstream blip. Try again. |
| `SERVICE_UNAVAILABLE` | ✅ yes | Maintenance window. Try again. |
| `NETWORK_ERROR` | ✅ yes | DNS / TLS / connection refused. |
| `TIMEOUT` | ✅ maybe | Depends on your latency budget. |

## The pattern

```
for attempt in 0..maxAttempts:
    try:
        return fn()
    except CryptohopperError as e:
        if e.code in FAIL_FAST_CODES: raise
        if last_attempt: raise
        sleep(backoff(attempt))
```

Backoff is exponential (`500ms × 2^attempt` is a sensible default) — no need to add jitter for a single client; the server's distribution across users provides plenty.

## Available implementations

| Language | File |
|----------|------|
| Node.js | [`nodejs.js`](./nodejs.js) |
| Python | [`python.py`](./python.py) |

Same shape in every language — `withRetry` / `with_retry` higher-order function around a closure.
