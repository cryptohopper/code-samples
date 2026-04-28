# Recipe — Fan out: fetch positions for every hopper in parallel

Sequential polling of N hoppers takes N requests serially — slow. Naive `Promise.all` / `asyncio.gather` blasts all N at once and trips the `normal` rate bucket (30 req/min) past ~30 hoppers. The right tool is **bounded concurrency**: at most M requests in flight at a time, where M is well under the rate limit.

## The pattern

1. List all hoppers.
2. Spawn a worker pool of ~10 concurrent tasks.
3. Each worker pulls hopper IDs off a queue, calls `hoppers.positions(id)`, returns the result.
4. Collect results.

## Rate-limit math

- `normal` bucket: 30 req/min ≈ 1 req every 2 seconds in steady state.
- 10 concurrent workers, each waiting ~200ms per request: 50 req/sec peak — *will* trip the limit on bursts.
- The SDK auto-retries 429s, so this works *eventually* even under bursts. But you'll be slower than just running 10 workers with explicit per-worker pacing.

For 100+ hoppers, drop concurrency to 5 and add a per-request sleep, or chunk the work.

## Available implementations

| Language | File | Concurrency primitive |
|----------|------|----------------------|
| Node.js | [`nodejs.js`](./nodejs.js) | `Promise.all` over N workers consuming a shared index |
| Python | [`python.py`](./python.py) | `concurrent.futures.ThreadPoolExecutor(max_workers=10)` |

The Go pattern (with `errgroup` + `semaphore`) is similar and works the same way; deferred to a future addition.
