# PHP quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
composer install
php hello.php
```

## What it shows

- How to construct a `Client` with named-argument syntax (PHP 8.0+)
- One authenticated call: `$ch->user->get()`
- One market-data call: `$ch->exchange->ticker(exchange: ..., market: ...)`
- Typed error handling via `CryptohopperException`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/php/`](../../oauth/php/) for a runnable OAuth example, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

Just one — [`cryptohopper/sdk`](https://packagist.org/packages/cryptohopper/sdk). Requires PHP 8.1+.
