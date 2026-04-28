# Dart quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
dart pub get
dart run
```

## What it shows

- How to construct `CryptohopperClient` with a bearer token (and `close()` it via `try/finally`)
- One authenticated call: `await ch.user.get()`
- One market-data call: `await ch.exchange.ticker(exchange: ..., market: ...)`
- Typed error handling via `on CryptohopperException catch (e)`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/`](../../oauth/) for runnable OAuth examples, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

Just one — `cryptohopper` (resolved via `git:` ref while the package isn't on pub.dev yet — see the [Dart SDK README](https://github.com/cryptohopper/cryptohopper-dart-sdk) for status). Requires Dart 3.2+.
