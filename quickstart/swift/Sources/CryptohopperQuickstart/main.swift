// Cryptohopper Swift SDK — hello world.
//
// Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
// two calls: client.user.get() and client.exchange.ticker(...).
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
//   swift run

import Cryptohopper
import Foundation

guard let token = ProcessInfo.processInfo.environment["CRYPTOHOPPER_TOKEN"], !token.isEmpty else {
    FileHandle.standardError.write(Data(
        "Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.\n".utf8
    ))
    exit(1)
}

let client = try Client(apiKey: token)

do {
    if let me = try await client.user.get() as? [String: Any] {
        let identity = (me["email"] as? String)
            ?? (me["username"] as? String)
            ?? "\(me["id"] ?? "?")"
        print("Logged in as \(identity)")
    }

    if let ticker = try await client.exchange.ticker(
        exchange: "binance",
        market: "BTC/USDT"
    ) as? [String: Any] {
        print("BTC/USDT last: \(ticker["last"] ?? "?")")
    }
} catch let e as CryptohopperError {
    FileHandle.standardError.write(Data(
        "API error [\(e.code.rawValue)]: \(e.message)\n".utf8
    ))
    if let ip = e.ipAddress {
        FileHandle.standardError.write(Data(
            "  Server saw your IP as: \(ip)\n".utf8
        ))
    }
    exit(2)
}
