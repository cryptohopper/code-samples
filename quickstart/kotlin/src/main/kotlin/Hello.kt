// Cryptohopper Kotlin SDK — hello world.
//
// Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
// two calls: ch.user.get() and ch.exchange.ticker(...).
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
//   ./gradlew run

import com.cryptohopper.Client
import com.cryptohopper.CryptohopperError
import kotlinx.coroutines.runBlocking
import kotlinx.serialization.json.contentOrNull
import kotlinx.serialization.json.jsonObject
import kotlinx.serialization.json.jsonPrimitive
import kotlin.system.exitProcess

fun main() = runBlocking {
    val token = System.getenv("CRYPTOHOPPER_TOKEN")
    if (token.isNullOrEmpty()) {
        System.err.println("Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.")
        exitProcess(1)
    }

    val ch = Client.create(token)

    try {
        val me = ch.user.get()?.jsonObject
        val identity = me?.get("email")?.jsonPrimitive?.contentOrNull
            ?: me?.get("username")?.jsonPrimitive?.contentOrNull
            ?: me?.get("id")?.jsonPrimitive?.contentOrNull
            ?: "?"
        println("Logged in as $identity")

        val ticker = ch.exchange.ticker(exchange = "binance", market = "BTC/USDT")?.jsonObject
        println("BTC/USDT last: ${ticker?.get("last")?.jsonPrimitive?.contentOrNull}")
    } catch (e: CryptohopperError) {
        System.err.println("API error [${e.code}]: ${e.message}")
        e.ipAddress?.let { System.err.println("  Server saw your IP as: $it") }
        exitProcess(2)
    }
}
