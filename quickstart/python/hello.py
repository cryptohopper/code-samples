"""Cryptohopper Python SDK — hello world.

Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
two calls: user.get() and exchange.ticker().

Run:
    export CRYPTOHOPPER_TOKEN=your-40-char-bearer
    pip install -r requirements.txt
    python hello.py
"""

import os
import sys

from cryptohopper import CryptohopperClient, CryptohopperError


def main() -> int:
    token = os.environ.get("CRYPTOHOPPER_TOKEN")
    if not token:
        print("Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.", file=sys.stderr)
        return 1

    try:
        with CryptohopperClient(api_key=token) as ch:
            me = ch.user.get()
            print(f"Logged in as {me.get('email') or me.get('username') or me.get('id')}")

            ticker = ch.exchange.ticker(exchange="binance", market="BTC/USDT")
            print(f"BTC/USDT last: {ticker.get('last')}")
    except CryptohopperError as e:
        print(f"API error [{e.code}]: {e}", file=sys.stderr)
        if e.ip_address:
            print(f"  Server saw your IP as: {e.ip_address}", file=sys.stderr)
        return 2

    return 0


if __name__ == "__main__":
    sys.exit(main())
