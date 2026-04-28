# Cryptohopper Code Samples

Practical, runnable examples for the [Cryptohopper](https://www.cryptohopper.com) API. Pick what you need from the table — every directory has its own README with the run command.

## Browse by purpose

| Directory | Purpose |
|-----------|---------|
| [`quickstart/`](./quickstart/) | A "hello world" per language — authenticate and make one API call. Pick this if you're new. |
| [`oauth/`](./oauth/) | Drive the three-leg OAuth2 consent flow end to end (server-side apps that need to obtain tokens for users). |
| [`webhooks/`](./webhooks/) | Receive webhook events from Cryptohopper (order fills, signals). |
| [`recipes/`](./recipes/) | Cross-language patterns: poll a backtest, fan out positions, retry strategy, stream new fills. |
| [`cli/`](./cli/) | Shell scripts that combine `cryptohopper` CLI commands with `jq` for ops automation. |

## Browse by language

Every official Cryptohopper SDK ships an alpha-quality "0.x" release on its native registry. Each SDK covers all 18 public API domains with the same error taxonomy and retry contract — the [Comparison wiki](https://github.com/cryptohopper/cryptohopper-node-sdk/wiki/Comparison) shows the same operations side-by-side.

| Language | Quickstart | OAuth flow | Registry |
|----------|-----------|-----------|----------|
| **Node.js** | [`quickstart/nodejs/`](./quickstart/nodejs/) | [`oauth/nodejs/`](./oauth/nodejs/) | [`@cryptohopper/sdk`](https://www.npmjs.com/package/@cryptohopper/sdk) |
| **Python** | [`quickstart/python/`](./quickstart/python/) | [`oauth/python/`](./oauth/python/) | [`cryptohopper`](https://pypi.org/project/cryptohopper/) |
| **Go** | [`quickstart/go/`](./quickstart/go/) | [`oauth/go/`](./oauth/go/) | [`cryptohopper-go-sdk`](https://pkg.go.dev/github.com/cryptohopper/cryptohopper-go-sdk) |
| **Ruby** | [`quickstart/ruby/`](./quickstart/ruby/) | — | [`cryptohopper`](https://rubygems.org/gems/cryptohopper) |
| **Rust** | [`quickstart/rust/`](./quickstart/rust/) | — | [`cryptohopper`](https://crates.io/crates/cryptohopper) |
| **PHP** | [`quickstart/php/`](./quickstart/php/) | [`oauth/php/`](./oauth/php/) | [`cryptohopper/sdk`](https://packagist.org/packages/cryptohopper/sdk) |
| **Dart** | [`quickstart/dart/`](./quickstart/dart/) | — | (git: until pub.dev publish) |
| **Swift** | [`quickstart/swift/`](./quickstart/swift/) | — | SwiftPM |
| **Kotlin** | [`quickstart/kotlin/`](./quickstart/kotlin/) | — | (Maven Central pending) |
| **curl** | [`quickstart/curl/`](./quickstart/curl/) | — | (no SDK — raw HTTP, language-agnostic reference) |

## Get a token

You'll need a 40-character OAuth bearer token to run any of these. Three ways to obtain one:

1. **CLI** (easiest) — `cryptohopper login` from [`cryptohopper-cli`](https://github.com/cryptohopper/cryptohopper-cli) walks you through the browser consent and stores the token at `~/.cryptohopper/config.json`. `cat` it out from there.
2. **Manual OAuth flow** — see the language-specific samples in [`oauth/`](./oauth/) or the protocol-level walkthrough in [`quickstart/curl/`](./quickstart/curl/).
3. **Long-lived dev token** — for some app types you can issue one directly from the [developer dashboard](https://www.cryptohopper.com/developers).

Once you have a token, set it in your shell:

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
```

Every quickstart reads from that env var.

## Auth-header gotcha

The Cryptohopper public API uses the `access-token: <token>` header, **not** `Authorization: Bearer`. The AWS API Gateway in front of `api.cryptohopper.com` rejects `Authorization: Bearer` with `405 Missing Authentication Token`. Every official SDK in the table above sends the right header automatically. If you're hand-rolling HTTP, see the [curl quickstart](./quickstart/curl/).

## Other Cryptohopper resources

- [`cryptohopper-resources`](https://github.com/cryptohopper/cryptohopper-resources) — OpenAPI spec, public docs, the SDK suite overview
- [`cryptohopper-cli`](https://github.com/cryptohopper/cryptohopper-cli) — the official CLI
- [Public API docs](https://docs.cryptohopper.com)
- [Developer dashboard](https://www.cryptohopper.com/developers)

## License

MIT — see [LICENSE](./LICENSE).
