# Recipes

Cross-language patterns for common SDK use cases. Each recipe shows the same operation in 2-3 languages so you can copy whichever language fits your stack.

| Recipe | Why | Implementations |
|--------|-----|-----------------|
| [`poll-backtest/`](./poll-backtest/) | Backtests run async server-side; you must poll for completion | Node, Python, Go |
| [`fan-out-positions/`](./fan-out-positions/) | Fetch positions across many hoppers in parallel without tripping rate limits | Node, Python |
| [`retry-fail-fast/`](./retry-fail-fast/) | Retry transient errors (5xx, network) but fail fast on auth/validation | Node, Python |
| [`stream-fills/`](./stream-fills/) | Detect new fills via polling — for ad-hoc tools, not production (use webhooks) | Node, Python |

## Why these specifically

These four recipes cover the most common API patterns that aren't obvious from the per-method docs:

- **Polling** — you'll need it for backtests and any other async resource. The pattern is identical regardless of resource.
- **Bounded fan-out** — naive `Promise.all` / `asyncio.gather` over many hoppers will trip rate limits. The bounded-concurrency pattern is a one-liner once you've seen it.
- **Smart retry** — auth errors should never retry; transient errors should. The classification is the hard part, not the loop.
- **Polling for events** — a placeholder until you wire up webhooks. Unavoidable in dev / debug.

Each recipe's README explains the *why* in detail. The code is small enough to copy-paste; the README is the reference.

## Run any of them

```bash
export CRYPTOHOPPER_TOKEN=your-bearer
# ... plus any recipe-specific env vars (HOPPER_ID, etc.)
node recipes/<name>/nodejs.js
python recipes/<name>/python.py
go run recipes/<name>/go.go      # where Go is provided
```
