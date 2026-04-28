#!/usr/bin/env bash
# Monitor open positions across every hopper, refreshed every 30 seconds.
#
# Combines `cryptohopper hoppers list --json` to enumerate hoppers with
# `cryptohopper positions <id> --json` to fetch each hopper's positions,
# then formats with jq into a single rolling table.
#
# Requires: cryptohopper CLI, jq.
#
# Run:
#   chmod +x monitor-positions.sh
#   ./monitor-positions.sh

set -euo pipefail

command -v cryptohopper >/dev/null || { echo "Install the cryptohopper CLI first." >&2; exit 1; }
command -v jq >/dev/null || { echo "Install jq first." >&2; exit 1; }

INTERVAL_S="${INTERVAL_S:-30}"

while :; do
  clear
  printf 'Cryptohopper position monitor — %s (refresh every %ss, Ctrl-C to exit)\n\n' "$(date)" "$INTERVAL_S"
  printf '%-10s  %-25s  %-15s  %-12s  %-15s\n' 'HOPPER' 'NAME' 'COIN' 'AMOUNT' 'RATE'
  printf '%-10s  %-25s  %-15s  %-12s  %-15s\n' '------' '----' '----' '------' '----'

  cryptohopper hoppers list --json \
    | jq -r '.data[] | "\(.id)\t\(.name // "?")"' \
    | while IFS=$'\t' read -r hopper_id hopper_name; do
        cryptohopper positions "$hopper_id" --json 2>/dev/null \
          | jq -r --arg id "$hopper_id" --arg name "$hopper_name" '
              .data[]?
              | "\($id)\t\($name)\t\(.coin // "?")\t\(.amount // "?")\t\(.rate // "?")"
            ' \
          | while IFS=$'\t' read -r id name coin amount rate; do
              printf '%-10s  %-25s  %-15s  %-12s  %-15s\n' "$id" "$name" "$coin" "$amount" "$rate"
            done
      done

  sleep "$INTERVAL_S"
done
