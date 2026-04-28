# Node.js webhook receiver

A minimal Express server that:

1. Listens on `/cryptohopper`
2. Parses the JSON body
3. Verifies an HMAC-SHA256 signature header if a shared secret is configured
4. Dispatches by event type

## Run it

```bash
export CRYPTOHOPPER_WEBHOOK_SECRET=your-shared-secret  # optional but recommended
npm install
npm start
```

Then expose `http://localhost:3000/cryptohopper` to the public internet (e.g. via [`ngrok http 3000`](https://ngrok.com/) during development) and register that URL with Cryptohopper:

```bash
cryptohopper webhooks create --url https://your-ngrok-id.ngrok.app/cryptohopper --event order_filled
```

## What it shows

- `express.json({ verify: ... })` to capture the raw body for signature verification *before* it's parsed
- HMAC-SHA256 verification with `crypto.timingSafeEqual` to defeat timing attacks
- Event dispatch via a `switch` — easy to extend with new event types

## Signature verification — why and how

If you've configured a webhook secret on your Cryptohopper app, the server signs every webhook body with HMAC-SHA256 and sends the digest in `X-Cryptohopper-Signature`. The receiver re-computes the digest from the raw body and the shared secret, then compares with `timingSafeEqual` (constant-time, defeats timing attacks).

If `CRYPTOHOPPER_WEBHOOK_SECRET` isn't set, this sample skips verification and logs a warning. **Always** configure a secret in production.

## Hardening checklist

- [x] Constant-time signature compare
- [ ] Timestamp tolerance (reject webhooks > 5 min old) — not implemented here; depends on whether the server includes a timestamp in the signed payload.
- [ ] Replay protection (stash event IDs, reject duplicates) — production receivers should idempotency-key by event ID.
- [ ] Rate limiting (e.g. `express-rate-limit`) — protect against abuse if your webhook URL leaks.

## Production notes

For real workloads:

- Run multiple receiver replicas behind a load balancer.
- Persist a "last seen event ID" per webhook in your DB to defeat replays.
- Acknowledge with 200 *immediately*; do the actual work async (push to a queue). Cryptohopper will retry slow webhooks and you don't want to tip into the retry loop.
