# Recipe — Poll a backtest until it finishes

Backtests run **asynchronously** server-side. `backtest.create` returns immediately with an ID; the actual computation happens in the background. To get the result, you poll `backtest.get` until `status` is terminal (`"completed"` or `"failed"`).

## The pattern, in any language

1. Submit the backtest with `backtest.create({hopper_id, start_date, end_date})`.
2. Save the returned `id`.
3. Loop:
   - `backtest.get(id)` → check `status`
   - If `"completed"` or `"failed"`, return the result
   - Otherwise sleep ~5 seconds and re-check

## Rate-limit note

The `backtest` rate bucket is 1 request per 2 seconds. **5-second polling stays well clear** of the limit. Don't poll faster than that — you'll start eating 429s.

## Available implementations

| Language | File |
|----------|------|
| Node.js | [`nodejs.js`](./nodejs.js) |
| Python | [`python.py`](./python.py) |
| Go | [`go.go`](./go.go) |

Each takes the same env vars: `CRYPTOHOPPER_TOKEN`, `HOPPER_ID`. Run command is in the file header.

## Picking a backtest range

The example uses Q1 2026. Substitute your own dates as `YYYY-MM-DD`. The server caps backtest spans (typically 2 years); see `backtest.limits()` for your account's quota.
