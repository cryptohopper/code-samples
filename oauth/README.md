# OAuth flow examples

Each example here drives the [three-leg OAuth2 authorization-code flow](https://www.cryptohopper.com/api-documentation/how-the-api-works) end to end:

1. Redirect the browser to `cryptohopper.com/oauth2/authorize` with your `client_id`.
2. User logs in and grants consent → browser redirected to your callback with a `code`.
3. Exchange `code` for an `access_token` at `cryptohopper.com/oauth2/token`.
4. Use the token on `api.cryptohopper.com/v1/...` calls.

| Language | Directory | Notes |
|----------|-----------|-------|
| **Node.js** | [`nodejs/`](./nodejs/) | Express + Node 18+ stdlib `fetch`. Zero third-party HTTP/OAuth libs. |
| **Python** | [`python/`](./python/) | `requests` |
| **PHP** | [`php/`](./php/) | curl + Bootstrap form |

For a pure-curl walkthrough (any language), see [`../quickstart/curl/`](../quickstart/curl/).

## Production-quality alternatives

The OAuth dance is the same regardless of language — every SDK keeps it as a one-time setup step you do *outside* the SDK. Once you have a token, the SDK handles everything from there.

The simplest end-to-end:

```bash
cryptohopper login              # browser consent, token persisted to ~/.cryptohopper/config.json
cat ~/.cryptohopper/config.json # extract the token if you need it elsewhere
```

That's the [`cryptohopper-cli`](https://github.com/cryptohopper/cryptohopper-cli) doing all of the steps in this directory for you. Use it during development; reach for these per-language samples when you're embedding the flow in your own server-side app.
