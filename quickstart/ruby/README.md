# Ruby quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
bundle install
ruby hello.rb
```

## What it shows

- How to construct `Cryptohopper::Client` with a bearer token
- One authenticated call: `ch.user.get`
- One market-data call: `ch.exchange.ticker(exchange:, market:)`
- Typed error handling via `Cryptohopper::Error`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/`](../../oauth/) for runnable OAuth examples, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

Just one — [`cryptohopper`](https://rubygems.org/gems/cryptohopper). Requires Ruby 3.0+.
