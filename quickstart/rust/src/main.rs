// Cryptohopper Rust SDK — hello world.
//
// Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
// two calls: user.get() and exchange.ticker().
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
//   cargo run

use cryptohopper::{Client, ErrorCode};
use serde_json::json;
use std::env;

#[tokio::main]
async fn main() -> Result<(), Box<dyn std::error::Error>> {
    let token = env::var("CRYPTOHOPPER_TOKEN").map_err(|_| {
        "Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.".to_string()
    })?;

    let ch = Client::builder().api_key(token).build()?;

    match ch.user.get().await {
        Ok(me) => {
            let id = me["email"].as_str()
                .or_else(|| me["username"].as_str())
                .or_else(|| me["id"].as_str())
                .unwrap_or("?");
            println!("Logged in as {id}");
        }
        Err(e) => {
            eprintln!("API error [{:?}]: {}", e.code, e.message);
            if let Some(ip) = &e.ip_address {
                eprintln!("  Server saw your IP as: {ip}");
            }
            std::process::exit(2);
        }
    }

    let ticker = ch
        .exchange
        .ticker(&json!({ "exchange": "binance", "market": "BTC/USDT" }))
        .await?;
    println!("BTC/USDT last: {}", ticker["last"]);

    let _ = ErrorCode::RateLimited; // demonstrates the enum is in scope
    Ok(())
}
