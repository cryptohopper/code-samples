// Fan out: fetch positions for every hopper in parallel, with a concurrency cap.
//
// Sequential polling of N hoppers takes N requests serially. Promise.all
// blasts all N at once and trips the `normal` rate bucket (30 req/min)
// past ~30 hoppers. The right tool is bounded concurrency.
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-bearer
//   node nodejs.js

import { CryptohopperClient, CryptohopperError } from "@cryptohopper/sdk";

const CONCURRENCY = 10;
const token = process.env.CRYPTOHOPPER_TOKEN;
if (!token) {
  console.error("Set CRYPTOHOPPER_TOKEN");
  process.exit(1);
}

const ch = new CryptohopperClient({ apiKey: token });

const hoppers = await ch.hoppers.list();
console.log(`Fetching positions for ${hoppers.length} hoppers, ${CONCURRENCY} at a time…`);

const results = await mapWithConcurrency(hoppers, CONCURRENCY, async (h) => {
  try {
    const positions = await ch.hoppers.positions(h.id);
    return { id: h.id, name: h.name, positions };
  } catch (e) {
    if (e instanceof CryptohopperError) {
      return { id: h.id, name: h.name, error: e.code };
    }
    throw e;
  }
});

for (const r of results) {
  if (r.error) {
    console.log(`hopper ${r.id} (${r.name}): ERROR ${r.error}`);
  } else {
    console.log(`hopper ${r.id} (${r.name}): ${r.positions.length} positions`);
  }
}

// Bounded-concurrency map: never has more than `n` promises in flight.
async function mapWithConcurrency(items, n, fn) {
  const results = new Array(items.length);
  let i = 0;
  async function worker() {
    while (i < items.length) {
      const idx = i++;
      results[idx] = await fn(items[idx]);
    }
  }
  await Promise.all(Array.from({ length: Math.min(n, items.length) }, worker));
  return results;
}
