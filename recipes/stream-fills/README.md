# Recipe — Stream new fills

Cryptohopper's public API doesn't expose a fills WebSocket. For tooling that wants near-real-time fill notifications, the canonical pattern is **poll orders, dedupe by id, emit on transition to `"filled"`**.

This works for dashboards, ad-hoc CLI tools, and any case where webhooks are overkill. **For production, register a webhook** ([`webhooks/`](../../webhooks/)) — push beats poll for event delivery.

## The pattern

1. Maintain a set of already-seen order IDs in memory.
2. Loop:
   - `hoppers.orders(hopper_id)` — list recent orders
   - For each order: if `id ∉ seen` and `status == "filled"`, emit it
   - Add `id` to `seen` regardless (so we don't keep re-checking it)
3. Sleep ~10 seconds between polls.

## Rate-limit note

Polling one hopper every 10 seconds is 6 req/min — well under the `normal` bucket (30 req/min). For multiple hoppers, share the same poll loop and increase the interval rather than running parallel pollers.

## Restarting

The `seen` set is in-memory only. On restart, the first poll re-reads recent orders and may re-emit fills it had already emitted before the restart. For exactly-once delivery you need a persistent dedupe store — webhooks are a much better answer.

## Available implementations

| Language | File |
|----------|------|
| Node.js | [`nodejs.js`](./nodejs.js) |
| Python | [`python.py`](./python.py) |
