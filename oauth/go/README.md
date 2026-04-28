# Go OAuth flow

A minimal `net/http` server that drives the three-leg OAuth2 authorization-code flow against [cryptohopper.com](https://www.cryptohopper.com), then calls one authenticated endpoint on `api.cryptohopper.com` to confirm the token works.

## Requirements

- Go 1.22+
- A Cryptohopper [developer-app](https://www.cryptohopper.com) `client_id` and `client_secret`
- Stdlib only — no third-party deps (`go.mod` has no `require` block).

## Run it

```bash
export CRYPTOHOPPER_CLIENT_ID=your-40-char-client-id
export CRYPTOHOPPER_CLIENT_SECRET=your-client-secret

go run main.go
```

Then open <http://localhost:3000/auth> in a browser. After the consent flow you'll be redirected back to `/` which calls `GET /v1/hopper` and prints the response.

## What the three endpoints do

| Path | Purpose |
|------|---------|
| `/` | Protected resource. With a token, calls `GET /v1/hopper` and returns the JSON. Without one, links to `/auth`. |
| `/auth` | Generates a CSRF state, stashes it, redirects to `cryptohopper.com/oauth2/authorize`. |
| `/callback` | Verifies state, receives the auth code, exchanges it for an access token, redirects to `/`. |

## Production notes

This sample stores `accessToken` and `expectedState` in process-local globals for clarity. Real apps should:

- Persist tokens per session (cookie + session store).
- Persist `state` per session, not in a global — single-state-per-process means concurrent OAuth flows collide.
- Refresh tokens via `grant_type=refresh_token` when the access token expires.

## Production alternative — use the SDK

For most production apps you'd drive the OAuth flow once at app-bootstrap time (or via the `cryptohopper login` CLI) and pass the resulting bearer to the SDK:

```go
ch, _ := cryptohopper.NewClient(token)
hoppers, _ := ch.Hoppers.List(ctx, nil)
```

The SDK handles retry, error mapping, and the access-token header. This sample is intentionally low-level so you can see what the SDK does for you.
