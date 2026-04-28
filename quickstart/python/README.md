# Python quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
pip install -r requirements.txt
python hello.py
```

## What it shows

- How to construct `CryptohopperClient` with a bearer token (and use it as a context manager — closes the underlying `httpx.Client` cleanly)
- One authenticated call: `client.user.get()`
- One market-data call: `client.exchange.ticker(exchange=..., market=...)`
- Typed error handling via `CryptohopperError`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/python/`](../../oauth/python/) for a runnable OAuth example, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

Just one — [`cryptohopper`](https://pypi.org/project/cryptohopper/). Requires Python 3.10+.
