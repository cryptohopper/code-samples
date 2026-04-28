#!/usr/bin/env bash
# Submit backtests for every hopper in parallel, then poll until all are done.
#
# Reads a date range from $FROM and $TO, lists all hoppers, submits one
# backtest per hopper, then polls each in a loop until terminal status.
# Output is one summary line per backtest.
#
# Requires: cryptohopper CLI, jq.
#
# Run:
#   export FROM=2026-01-01
#   export TO=2026-04-01
#   ./batch-backtests.sh

set -euo pipefail

command -v cryptohopper >/dev/null || { echo "Install the cryptohopper CLI first." >&2; exit 1; }
command -v jq >/dev/null || { echo "Install jq first." >&2; exit 1; }

: "${FROM:?Set FROM=YYYY-MM-DD}"
: "${TO:?Set TO=YYYY-MM-DD}"

echo "Submitting backtests for every hopper, $FROM → $TO…" >&2

# 1. Submit one backtest per hopper. Save (hopper_id, backtest_id) pairs.
mapping=$(mktemp)
trap 'rm -f "$mapping"' EXIT

cryptohopper hoppers list --json \
  | jq -r '.data[].id' \
  | while read -r hopper_id; do
      bt_id=$(
        cryptohopper backtest new "$hopper_id" --from "$FROM" --to "$TO" --json \
          | jq -r '.data.id'
      )
      printf '%s\t%s\n' "$hopper_id" "$bt_id" >> "$mapping"
      echo "  hopper=$hopper_id backtest=$bt_id submitted" >&2
      # The backtest bucket is 1 req/2s; pace.
      sleep 3
    done

echo >&2
echo "Polling…" >&2

# 2. Poll each backtest until terminal.
while IFS=$'\t' read -r hopper_id bt_id; do
  while :; do
    status=$(cryptohopper backtest status "$bt_id" --json | jq -r '.data.status')
    case "$status" in
      completed)
        profit=$(cryptohopper backtest status "$bt_id" --json | jq -r '.data.profit_pct // .data.profit // "?"')
        printf '%s\thopper=%s\tbacktest=%s\tprofit=%s\n' "$status" "$hopper_id" "$bt_id" "$profit"
        break
        ;;
      failed)
        printf '%s\thopper=%s\tbacktest=%s\n' "$status" "$hopper_id" "$bt_id"
        break
        ;;
    esac
    sleep 5
  done
done < "$mapping"
