"""Retry on transient errors, fail fast on auth/validation errors.

The SDK auto-retries 429s. For 5xx and network errors you may want a
tighter retry. Auth errors should *never* be retried — they only get
worse on each attempt.

Run:
    export CRYPTOHOPPER_TOKEN=your-bearer
    python python.py
"""

import os
import sys
import time
from typing import Callable, TypeVar

from cryptohopper import CryptohopperClient, CryptohopperError

T = TypeVar("T")

FAIL_FAST_CODES = {
    "UNAUTHORIZED",
    "FORBIDDEN",
    "NOT_FOUND",
    "VALIDATION_ERROR",
    "DEVICE_UNAUTHORIZED",
}


def with_retry(fn: Callable[[], T], *, max_attempts: int = 3) -> T:
    last_exc: Exception | None = None
    for attempt in range(max_attempts):
        try:
            return fn()
        except CryptohopperError as e:
            if e.code in FAIL_FAST_CODES:
                raise
            last_exc = e
            if attempt + 1 == max_attempts:
                raise
            backoff_s = 0.5 * (2 ** attempt)
            print(
                f"  retry {attempt + 1}/{max_attempts} after {backoff_s:.1f}s ({e.code})",
                file=sys.stderr,
            )
            time.sleep(backoff_s)
    raise last_exc or RuntimeError("retry budget exhausted")


def main() -> None:
    token = os.environ.get("CRYPTOHOPPER_TOKEN")
    if not token:
        print("Set CRYPTOHOPPER_TOKEN", file=sys.stderr)
        sys.exit(1)

    with CryptohopperClient(api_key=token) as ch:
        me = with_retry(lambda: ch.user.get())
        print(f"Logged in as {me.get('email') or me.get('username') or me.get('id')}")


if __name__ == "__main__":
    main()
