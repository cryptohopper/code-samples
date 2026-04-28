// Poll a backtest until it terminates, then print the result.
//
// Backtests run async server-side. `backtest.create` returns immediately
// with an ID; you poll `backtest.get` until `status` is "completed" or
// "failed". The backtest rate bucket is 1 req / 2s — 5-second polling
// stays well clear.
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-bearer
//   export HOPPER_ID=42
//   node nodejs.js

import { CryptohopperClient, CryptohopperError } from "@cryptohopper/sdk";

const token = mustEnv("CRYPTOHOPPER_TOKEN");
const hopperId = Number(mustEnv("HOPPER_ID"));

const ch = new CryptohopperClient({ apiKey: token });

const submitted = await ch.backtest.create({
  hopper_id: hopperId,
  start_date: "2026-01-01",
  end_date: "2026-04-01",
});
const btId = submitted.id;
console.log(`Submitted backtest ${btId}`);

while (true) {
  const bt = await ch.backtest.get(btId);
  console.log(`  status=${bt.status}`);
  if (bt.status === "completed" || bt.status === "failed") {
    console.log(JSON.stringify(bt, null, 2));
    break;
  }
  await sleep(5_000);
}

function mustEnv(name) {
  const v = process.env[name];
  if (!v) {
    console.error(`Set ${name}`);
    process.exit(1);
  }
  return v;
}

function sleep(ms) {
  return new Promise((r) => setTimeout(r, ms));
}
