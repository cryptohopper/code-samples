// Cryptohopper Node.js SDK — hello world.
//
// Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
// two calls: user.get() and exchange.ticker().
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
//   npm install
//   node hello.js

import { CryptohopperClient, CryptohopperError } from "@cryptohopper/sdk";

const token = process.env.CRYPTOHOPPER_TOKEN;
if (!token) {
  console.error("Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.");
  process.exit(1);
}

const ch = new CryptohopperClient({ apiKey: token });

try {
  const me = await ch.user.get();
  console.log(`Logged in as ${me.email ?? me.username ?? me.id}`);

  const ticker = await ch.exchange.ticker({
    exchange: "binance",
    market: "BTC/USDT",
  });
  console.log(`BTC/USDT last: ${ticker.last}`);
} catch (e) {
  if (e instanceof CryptohopperError) {
    console.error(`API error [${e.code}]: ${e.message}`);
    if (e.ipAddress) console.error(`  Server saw your IP as: ${e.ipAddress}`);
    process.exit(2);
  }
  throw e;
}
