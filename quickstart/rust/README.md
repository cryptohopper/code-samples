# Rust quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
cargo run
```

## What it shows

- How to construct a client with `Client::builder().api_key(...).build()?`
- One authenticated call: `ch.user.get().await?` returns `serde_json::Value`
- One market-data call: `ch.exchange.ticker(&json!({...})).await?`
- Typed error handling — every error has an `ErrorCode` enum variant

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/`](../../oauth/) for runnable OAuth examples, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

- [`cryptohopper`](https://crates.io/crates/cryptohopper)
- `tokio` for the async runtime, `serde_json` for the request payloads.

Requires Rust 1.75+ (2021 edition).
