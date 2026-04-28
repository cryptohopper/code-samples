# Kotlin quickstart

A short "hello world" that authenticates with the Cryptohopper API and prints your account info plus the BTC/USDT price.

## Run it

```bash
export CRYPTOHOPPER_TOKEN=your-40-char-bearer
./gradlew run
```

> **Maven Central pending.** Until the Kotlin SDK is published, the `mavenLocal()` line in `build.gradle.kts` resolves it from a local install:
> ```bash
> git clone https://github.com/cryptohopper/cryptohopper-kotlin-sdk
> cd cryptohopper-kotlin-sdk
> ./gradlew publishToMavenLocal
> ```
> Once Maven Central is wired up, drop the `mavenLocal()` line.

## What it shows

- How to construct a client with `Client.create(token)`
- One authenticated call: `ch.user.get()` (suspend function, returns `JsonElement?`)
- One market-data call: `ch.exchange.ticker(exchange = ..., market = ...)`
- Typed error handling via `catch (e: CryptohopperError)`

## Where to get a token

[cryptohopper.com](https://www.cryptohopper.com) developer dashboard → **Create OAuth app** → drive the consent flow once. See [`oauth/`](../../oauth/) for runnable OAuth examples in other languages, or use the [`cryptohopper` CLI](https://github.com/cryptohopper/cryptohopper-cli) (`cryptohopper login`) which handles the flow for you.

## Dependencies

- [`com.cryptohopper:cryptohopper`](https://github.com/cryptohopper/cryptohopper-kotlin-sdk) — the SDK itself.
- `kotlinx-coroutines-core` and `kotlinx-serialization-json` — both are transitive deps of the SDK; declared explicitly here so Gradle resolves them at compile time.

Requires JDK 17+.
