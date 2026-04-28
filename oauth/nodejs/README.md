# Node.js OAuth2 sample

A minimal Express app demonstrating the three-leg OAuth2 authorization-code flow against [cryptohopper.com](https://www.cryptohopper.com), then calling one authenticated endpoint on `api.cryptohopper.com`.

## Requirements

- Node.js **18 or newer** (uses the built-in `fetch`)
- A Cryptohopper [developer-app](https://www.cryptohopper.com) `client_id` and `client_secret`

The sample has only **one third-party dep** (`express`) — `axios` and `simple-oauth2` were removed in favour of `globalThis.fetch` + `URLSearchParams` so there's no audit-debt to maintain.

## Run it

```bash
export CRYPTOHOPPER_CLIENT_ID=your-40-char-client-id
export CRYPTOHOPPER_CLIENT_SECRET=your-client-secret

npm install
npm start
```

Then open <http://localhost:3000/> — it'll prompt you to authorize, redirect through cryptohopper.com, and call one authenticated API endpoint on return.

## What the three endpoints do

| Path | Purpose |
|------|---------|
| `/` | Protected resource. With a token, calls `GET /v1/hopper` and returns the JSON. Without one, links to `/auth`. |
| `/auth` | Redirects the browser to `cryptohopper.com/oauth2/authorize` to begin the consent flow. |
| `/callback` | Receives the auth code, exchanges it for an access token at `cryptohopper.com/oauth2/token`, then redirects back to `/`. |

## API auth header

The Cryptohopper public API expects the `access-token` HTTP header, **not** `Authorization: Bearer`. The AWS API Gateway in front of the production API rejects Bearer with a SigV4 parser error. See [How the API Works](https://www.cryptohopper.com/api-documentation/how-the-api-works) for the authoritative reference.

## Production notes

This sample stores `token` in a process-local variable for clarity. Real apps should:

- Persist tokens per session (e.g. in a session store) — this sample's single global is single-user only.
- Verify the OAuth `state` parameter against the value stashed at `/auth` to defeat CSRF.
- Refresh tokens via `grant_type: refresh_token` when the access token expires.

## Alternative: use the official Node SDK

For most production use cases, the [`@cryptohopper/sdk`](https://www.npmjs.com/package/@cryptohopper/sdk) handles request signing, retry, and error mapping for you — drop the OAuth flow into a one-time setup script and pass the resulting bearer to the SDK:

```js
import { CryptohopperClient } from '@cryptohopper/sdk';

const ch = new CryptohopperClient({ apiKey: token.access_token });
const hoppers = await ch.hoppers.list();
```

This sample is intentionally low-level so you can see what the SDK does for you.
