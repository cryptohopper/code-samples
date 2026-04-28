// swift-tools-version:5.9
import PackageDescription

let package = Package(
    name: "CryptohopperQuickstart",
    platforms: [
        .macOS(.v10_15),
    ],
    dependencies: [
        .package(
            url: "https://github.com/cryptohopper/cryptohopper-swift-sdk",
            from: "0.1.0-alpha.2"
        ),
    ],
    targets: [
        .executableTarget(
            name: "CryptohopperQuickstart",
            dependencies: [
                .product(name: "Cryptohopper", package: "cryptohopper-swift-sdk"),
            ]
        ),
    ]
)
