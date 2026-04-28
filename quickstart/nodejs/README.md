# Node.js quickstart

A 30-line "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
npm install
npm start
```

## What it shows

- How to construct `CryptohopperClient` with a bearer token
- One authenticated call: `client.user.get()`
- One market-data call: `client.exchange.ticker({ exchange, market })`
- Typed error handling via `CryptohopperError`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/nodejs/`](../../oauth/nodejs/) for a runnable OAuth example, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

Just one — [`@cryptohopper/sdk`](https://www.npmjs.com/package/@cryptohopper/sdk). Requires Node 20+ (uses ESM and top-level await).
