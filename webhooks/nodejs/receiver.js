// Cryptohopper webhook receiver — Node.js (Express).
//
// Listens on /cryptohopper, parses the JSON body, and dispatches by
// event type. Verifies an HMAC-SHA256 signature header if a webhook
// secret is configured (recommended; see notes below).
//
// Run:
//   export CRYPTOHOPPER_WEBHOOK_SECRET=optional-shared-secret
//   npm install
//   node receiver.js

import express from "express";
import crypto from "node:crypto";

const PORT = process.env.PORT ?? 3000;
const SECRET = process.env.CRYPTOHOPPER_WEBHOOK_SECRET ?? "";

const app = express();
// Capture the raw body so we can verify the signature.
app.use(
  express.json({
    verify: (req, _res, buf) => {
      req.rawBody = buf;
    },
  }),
);

app.post("/cryptohopper", (req, res) => {
  if (SECRET) {
    const sig = req.get("x-cryptohopper-signature") ?? "";
    if (!verifySignature(req.rawBody, sig, SECRET)) {
      console.error("invalid signature, rejecting");
      return res.status(401).send("invalid signature");
    }
  }

  const { event, payload } = req.body ?? {};
  console.log(`[${new Date().toISOString()}] event=${event}`);
  console.log(JSON.stringify(payload, null, 2));

  switch (event) {
    case "order_filled":
      handleOrderFilled(payload);
      break;
    case "order_cancelled":
      handleOrderCancelled(payload);
      break;
    default:
      console.log(`  (no handler for event=${event})`);
  }

  res.status(200).send("ok");
});

function handleOrderFilled(p) {
  console.log(`  → fill: ${p.market} ${p.type} ${p.amount} @ ${p.price}`);
}
function handleOrderCancelled(p) {
  console.log(`  → cancelled: order ${p.id}`);
}

function verifySignature(rawBody, headerSig, secret) {
  if (!headerSig) return false;
  const expected = crypto
    .createHmac("sha256", secret)
    .update(rawBody)
    .digest("hex");
  // Constant-time compare to defeat timing attacks.
  const a = Buffer.from(headerSig);
  const b = Buffer.from(expected);
  return a.length === b.length && crypto.timingSafeEqual(a, b);
}

app.listen(PORT, () => {
  console.log(`Webhook receiver listening on http://localhost:${PORT}/cryptohopper`);
  if (!SECRET) {
    console.warn("⚠ CRYPTOHOPPER_WEBHOOK_SECRET not set — signature verification skipped.");
  }
});
