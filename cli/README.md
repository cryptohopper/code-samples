# CLI scripting recipes

Short Bash scripts that wrap the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) for ops and automation. Every script uses `--json` output piped into `jq` — no SDK code, no language runtime, just `bash`/`jq`/`cryptohopper`.

## Scripts

| Script | What it does |
|--------|--------------|
| [`monitor-positions.sh`](./monitor-positions.sh) | Live `watch`-style display of open positions across every hopper |
| [`batch-backtests.sh`](./batch-backtests.sh) | Submit backtests for every hopper in parallel, poll until all finish |

## Requirements

- The [`cryptohopper`](https://github.com/cryptohopper/cryptohopper-cli) CLI, signed in (`cryptohopper login`).
- `jq` for JSON parsing.
- `bash` 4+ (or any POSIX-ish shell — most of these will work in `zsh` and `dash`).

## Why CLI scripting instead of an SDK?

For ops tasks that:

- Run on shared infra without a Node/Python/Go runtime
- Need to be readable by anyone on the team (no language barrier)
- Compose well with other Unix tools (`watch`, `cron`, `xargs`, etc.)

…the CLI is the right level. Once your script grows past ~50 lines or you need real error handling, switch to one of the SDKs.

## Pattern: every CLI command supports `--json`

Every `cryptohopper` subcommand accepts `--json` and emits a stable shape:

```json
{
  "ok": true,
  "data": <result>
}
```

On error:

```json
{
  "ok": false,
  "error": {
    "code": "RATE_LIMITED",
    "message": "...",
    "retry_after_ms": 1000
  }
}
```

So `jq -r '.data.id'` works after any successful command, and `jq -r '.error.code'` after any failed one. The exit-code contract is: 0 = ok, 1 = generic error, 2 = rate-limited, 3 = auth error.

## Pattern: pipe into `xargs` or a `while read`

```bash
# Run something for every hopper:
cryptohopper hoppers list --json | jq -r '.data[].id' \
  | while read -r id; do
      # ... per-hopper work ...
    done
```

That's the spine of every script in this directory.
