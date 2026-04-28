plugins {
    kotlin("jvm") version "2.3.21"
    application
}

repositories {
    mavenCentral()
    mavenLocal() // resolves the Kotlin SDK from `./gradlew publishToMavenLocal` until Maven Central is wired up
}

dependencies {
    implementation("com.cryptohopper:cryptohopper:0.1.0-alpha.2")
    implementation("org.jetbrains.kotlinx:kotlinx-coroutines-core:1.10.2")
    implementation("org.jetbrains.kotlinx:kotlinx-serialization-json:1.11.0")
}

application {
    mainClass = "HelloKt"
}

kotlin {
    jvmToolchain(17)
}
