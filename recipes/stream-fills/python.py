"""Stream new fills: poll a hopper's orders and emit each newly-filled one.

Cryptohopper doesn't expose a fills WebSocket on the public API, so the
canonical pattern is "poll orders, dedupe by id, emit on transition to
'filled'". For production you'd register a webhook instead — see
../../webhooks/. This recipe is for ad-hoc tooling and dashboards.

Run:
    export CRYPTOHOPPER_TOKEN=your-bearer
    export HOPPER_ID=42
    python python.py
"""

import os
import sys
import time

from cryptohopper import CryptohopperClient, CryptohopperError

POLL_S = 10


def must_env(name: str) -> str:
    v = os.environ.get(name)
    if not v:
        print(f"Set {name}", file=sys.stderr)
        sys.exit(1)
    return v


def main() -> None:
    token = must_env("CRYPTOHOPPER_TOKEN")
    hopper_id = int(must_env("HOPPER_ID"))
    seen: set[str] = set()

    print(f"Watching hopper {hopper_id} for fills (polling every {POLL_S}s)…")

    with CryptohopperClient(api_key=token) as ch:
        while True:
            try:
                orders = ch.hoppers.orders(hopper_id) or []
                for o in orders:
                    oid = str(o.get("id", ""))
                    if not oid or oid in seen:
                        continue
                    seen.add(oid)
                    if o.get("status") == "filled":
                        print(
                            f"Fill: {o.get('market')} {o.get('type')} "
                            f"{o.get('amount')} @ {o.get('price')} (id={oid})"
                        )
            except CryptohopperError as e:
                print(f"poll error: {e.code}", file=sys.stderr)
            time.sleep(POLL_S)


if __name__ == "__main__":
    main()
