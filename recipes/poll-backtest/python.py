"""Poll a backtest until it terminates, then print the result.

Backtests run async server-side. backtest.create returns immediately with
an ID; you poll backtest.get until status is "completed" or "failed".
The backtest rate bucket is 1 req / 2s — 5-second polling stays well clear.

Run:
    export CRYPTOHOPPER_TOKEN=your-bearer
    export HOPPER_ID=42
    python python.py
"""

import json
import os
import sys
import time

from cryptohopper import CryptohopperClient


def must_env(name: str) -> str:
    v = os.environ.get(name)
    if not v:
        print(f"Set {name}", file=sys.stderr)
        sys.exit(1)
    return v


def main() -> None:
    token = must_env("CRYPTOHOPPER_TOKEN")
    hopper_id = int(must_env("HOPPER_ID"))

    with CryptohopperClient(api_key=token) as ch:
        submitted = ch.backtest.create({
            "hopper_id": hopper_id,
            "start_date": "2026-01-01",
            "end_date": "2026-04-01",
        })
        bt_id = submitted["id"]
        print(f"Submitted backtest {bt_id}")

        while True:
            bt = ch.backtest.get(bt_id)
            print(f"  status={bt.get('status')}")
            if bt.get("status") in {"completed", "failed"}:
                print(json.dumps(bt, indent=2))
                return
            time.sleep(5)


if __name__ == "__main__":
    main()
