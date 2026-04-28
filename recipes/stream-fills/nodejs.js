// Stream new fills: poll a hopper's orders and emit each newly-filled one.
//
// Cryptohopper doesn't expose a fills WebSocket on the public API, so the
// canonical pattern is "poll orders, dedupe by id, emit on transition to
// 'filled'". For production you'd register a webhook instead — see
// ../../webhooks/. This recipe is for ad-hoc tooling and dashboards.
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-bearer
//   export HOPPER_ID=42
//   node nodejs.js

import { CryptohopperClient } from "@cryptohopper/sdk";

const POLL_MS = 10_000;
const token = mustEnv("CRYPTOHOPPER_TOKEN");
const hopperId = Number(mustEnv("HOPPER_ID"));

const ch = new CryptohopperClient({ apiKey: token });
const seen = new Set();

console.log(`Watching hopper ${hopperId} for fills (polling every ${POLL_MS}ms)…`);

while (true) {
  try {
    const orders = (await ch.hoppers.orders(hopperId)) ?? [];
    for (const o of orders) {
      const id = String(o.id ?? "");
      if (!id || seen.has(id)) continue;
      seen.add(id);
      if (o.status === "filled") {
        console.log(
          `Fill: ${o.market} ${o.type} ${o.amount} @ ${o.price} (id=${id})`,
        );
      }
    }
  } catch (e) {
    console.error("poll error:", e.code ?? e.message);
  }
  await new Promise((r) => setTimeout(r, POLL_MS));
}

function mustEnv(name) {
  const v = process.env[name];
  if (!v) {
    console.error(`Set ${name}`);
    process.exit(1);
  }
  return v;
}
