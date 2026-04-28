# Swift quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
swift run
```

## What it shows

- How to construct `Client` with a bearer token
- One authenticated call: `try await client.user.get()`
- One market-data call: `try await client.exchange.ticker(exchange:, market:)`
- Typed error handling via `catch let e as CryptohopperError`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/`](../../oauth/) for runnable OAuth examples in other languages, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

Just one — [`cryptohopper-swift-sdk`](https://github.com/cryptohopper/cryptohopper-swift-sdk) via SwiftPM. Requires Swift 5.9+. Works on macOS 10.15+, iOS 13+, tvOS 13+, watchOS 6+, and Linux (Swift 5.9 image).
