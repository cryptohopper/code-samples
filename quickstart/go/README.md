# Go quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
go mod tidy
go run .
```

## What it shows

- How to construct a client with `cryptohopper.NewClient(token)`
- One authenticated call: `ch.User.Get(ctx)` returns `map[string]any`
- One market-data call: `ch.Exchange.Ticker(ctx, exchange, market)`
- Typed error handling via `*cryptohopper.Error` with `errors.As`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/`](../../oauth/) for runnable OAuth examples in other languages, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

Just one — [`github.com/cryptohopper/cryptohopper-go-sdk`](https://pkg.go.dev/github.com/cryptohopper/cryptohopper-go-sdk). Requires Go 1.22+.
