"""Fan out: fetch positions for every hopper in parallel, with a concurrency cap.

Sequential polling of N hoppers takes N requests serially. ThreadPoolExecutor
with `max_workers` lets us run them concurrently while still respecting
the API's `normal` rate bucket (30 req/min) — keep `max_workers` modest
to avoid hammering the limit.

Run:
    export CRYPTOHOPPER_TOKEN=your-bearer
    python python.py
"""

import os
import sys
from concurrent.futures import ThreadPoolExecutor, as_completed

from cryptohopper import CryptohopperClient, CryptohopperError


def main() -> None:
    token = os.environ.get("CRYPTOHOPPER_TOKEN")
    if not token:
        print("Set CRYPTOHOPPER_TOKEN", file=sys.stderr)
        sys.exit(1)

    with CryptohopperClient(api_key=token) as ch:
        hoppers = ch.hoppers.list()
        print(f"Fetching positions for {len(hoppers)} hoppers, 10 at a time…")

        with ThreadPoolExecutor(max_workers=10) as pool:
            futures = {
                pool.submit(_positions_for, ch, h): h
                for h in hoppers
            }
            for future in as_completed(futures):
                h = futures[future]
                try:
                    positions = future.result()
                    print(f"hopper {h['id']} ({h.get('name')}): {len(positions)} positions")
                except CryptohopperError as e:
                    print(f"hopper {h['id']} ({h.get('name')}): ERROR {e.code}")


def _positions_for(ch, h):
    return ch.hoppers.positions(h["id"])


if __name__ == "__main__":
    main()
