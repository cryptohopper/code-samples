# curl quickstart — language-agnostic

Raw HTTP calls against the Cryptohopper API with `curl`. Useful as a reference: every official SDK in the suite ultimately makes the same calls. If something behaves unexpectedly via an SDK, drop down to curl to confirm what the wire layer is actually doing.

## Three things you need

1. A Cryptohopper **OAuth app** (`client_id` + `client_secret`) — created at <https://www.cryptohopper.com> developer dashboard.
2. An **authorization code** — obtained by sending a user through the consent flow.
3. An **access token** — exchanged from the auth code at `/oauth2/token`.

The first call below gets you a `code`; the second turns the code into a token; the third uses the token.

## Step 1 — Authorization Code Grant

Open this URL in a browser (or send the user to it):

```
https://www.cryptohopper.com/oauth2/authorize
  ?response_type=code
  &client_id=YOUR_CLIENT_ID
  &state=any-csrf-string
  &scope=read,notifications,manage,trade
  &redirect_uri=http://localhost/
```

The user logs in, accepts the consent prompt, and the browser is redirected to your `redirect_uri` with a `code` query param:

```
HTTP/1.1 302 Found
Location: http://localhost/?code=ABC123&state=any-csrf-string
```

Save that `code` — it's only valid for a short window.

| Parameter | Required | Notes |
|-----------|---------|-------|
| `response_type` | yes | Must be `code`. |
| `client_id` | yes | From the Cryptohopper developer dashboard. |
| `state` | yes | CSRF nonce. Generate per-session and verify on the redirect. |
| `scope` | yes | Comma-separated. Available scopes are listed in the dashboard. |
| `redirect_uri` | yes | Must match what you registered for the OAuth app. |

## Step 2 — Exchange the code for an access token

```bash
curl -X POST \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "grant_type=authorization_code" \
  -d "code=ABC123" \
  -d "redirect_uri=http://localhost/" \
  -d "client_id=YOUR_CLIENT_ID" \
  -d "client_secret=YOUR_CLIENT_SECRET" \
  https://www.cryptohopper.com/oauth2/token
```

Response:

```json
{
  "access_token": "your-40-char-bearer",
  "expires_in": 31556952,
  "token_type": "Bearer",
  "scope": "read,notifications,manage,trade",
  "refresh_token": "your-refresh-token"
}
```

The `access_token` is what you'll send on every API call.

## Step 3 — Use the access token

```bash
curl -i \
  -H "access-token: your-40-char-bearer" \
  https://api.cryptohopper.com/v1/hopper
```

Note the header is **`access-token`** (with a hyphen), **not** `Authorization: Bearer`. The AWS API Gateway in front of `api.cryptohopper.com` rejects `Authorization: Bearer` with `405 Missing Authentication Token`. See [How the API Works](https://www.cryptohopper.com/api-documentation/how-the-api-works) for the authoritative reference.

## Refresh tokens

When the access token expires, exchange the refresh token for a new pair:

```bash
curl -X POST \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "grant_type=refresh_token" \
  -d "refresh_token=your-refresh-token" \
  -d "client_id=YOUR_CLIENT_ID" \
  -d "client_secret=YOUR_CLIENT_SECRET" \
  https://www.cryptohopper.com/oauth2/token
```

## Beyond curl

Once you've confirmed the wire calls work, switch to one of the SDKs — they handle retry on 429, error mapping, and JSON envelope unwrapping for free:

- [`@cryptohopper/sdk`](https://www.npmjs.com/package/@cryptohopper/sdk) (Node)
- [`cryptohopper`](https://pypi.org/project/cryptohopper/) (Python)
- [`github.com/cryptohopper/cryptohopper-go-sdk`](https://pkg.go.dev/github.com/cryptohopper/cryptohopper-go-sdk) (Go)
- [`cryptohopper`](https://rubygems.org/gems/cryptohopper) (Ruby)
- [`cryptohopper`](https://crates.io/crates/cryptohopper) (Rust)
- [`cryptohopper/sdk`](https://packagist.org/packages/cryptohopper/sdk) (PHP)
- [`cryptohopper-dart-sdk`](https://github.com/cryptohopper/cryptohopper-dart-sdk) (Dart, git-install)
- [`cryptohopper-swift-sdk`](https://github.com/cryptohopper/cryptohopper-swift-sdk) (Swift, SwiftPM)
- [`cryptohopper-kotlin-sdk`](https://github.com/cryptohopper/cryptohopper-kotlin-sdk) (Kotlin/JVM, Maven Central pending)

Or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) which handles the OAuth flow for you (`cryptohopper login`).
