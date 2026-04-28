# Quickstarts

A "hello world" per supported language. Each one:

1. Reads a bearer token from `$CRYPTOHOPPER_TOKEN`.
2. Calls `user.get` (authenticated) and prints who's logged in.
3. Calls `exchange.ticker` for `binance:BTC/USDT` and prints the last price.
4. Catches the SDK's typed error and exits with a non-zero status on failure.

Pick your language. Each directory has its own `README.md` with the run command.

| Language | Directory | SDK package |
|----------|-----------|-------------|
| **Node.js** | [`nodejs/`](./nodejs/) | [`@cryptohopper/sdk`](https://www.npmjs.com/package/@cryptohopper/sdk) on npm |
| **Python** | [`python/`](./python/) | [`cryptohopper`](https://pypi.org/project/cryptohopper/) on PyPI |
| **Go** | [`go/`](./go/) | [`github.com/cryptohopper/cryptohopper-go-sdk`](https://pkg.go.dev/github.com/cryptohopper/cryptohopper-go-sdk) |
| **Ruby** | [`ruby/`](./ruby/) | [`cryptohopper`](https://rubygems.org/gems/cryptohopper) on RubyGems |
| **Rust** | [`rust/`](./rust/) | [`cryptohopper`](https://crates.io/crates/cryptohopper) on crates.io |
| **PHP** | [`php/`](./php/) | [`cryptohopper/sdk`](https://packagist.org/packages/cryptohopper/sdk) on Packagist |
| **Dart** | [`dart/`](./dart/) | `cryptohopper` (resolved via `git:` until pub.dev publish) |
| **Swift** | [`swift/`](./swift/) | [`cryptohopper-swift-sdk`](https://github.com/cryptohopper/cryptohopper-swift-sdk) via SwiftPM |
| **Kotlin** | [`kotlin/`](./kotlin/) | `com.cryptohopper:cryptohopper` (Maven Central pending — uses `mavenLocal()` for now) |
| **curl** | [`curl/`](./curl/) | (none — raw HTTP, language-agnostic reference) |

## Where to get a token

Every quickstart needs an OAuth bearer in `$CRYPTOHOPPER_TOKEN`. Three ways:

1. **CLI** — `cryptohopper login` ([`cryptohopper-cli`](https://github.com/cryptohopper/cryptohopper-cli)) walks you through the browser consent and stores the token at `~/.cryptohopper/config.json`. `cat` it from there.
2. **Manual OAuth flow** — see the [`oauth/`](../oauth/) directory or the [curl quickstart](./curl/) for the underlying HTTP calls.
3. **Developer dashboard** — for some app types you can issue a long-lived token directly without driving the consent flow.

## Auth-header gotcha

The Cryptohopper public API expects `access-token: <token>` (with a hyphen), **not** `Authorization: Bearer`. Every SDK in the table above sends the right header automatically. If you're hand-rolling HTTP, see the [curl quickstart](./curl/) for the rule.
