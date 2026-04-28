<?php
// Cryptohopper PHP SDK — hello world.
//
// Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
// two calls: user->get() and exchange->ticker().
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
//   composer install
//   php hello.php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Cryptohopper\Sdk\Client;
use Cryptohopper\Sdk\CryptohopperException;

$token = getenv('CRYPTOHOPPER_TOKEN') ?: '';
if ($token === '') {
    fwrite(STDERR, "Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.\n");
    exit(1);
}

$ch = new Client(apiKey: $token);

try {
    $me = $ch->user->get();
    $identity = $me['email'] ?? $me['username'] ?? $me['id'] ?? '?';
    echo "Logged in as {$identity}\n";

    $ticker = $ch->exchange->ticker(exchange: 'binance', market: 'BTC/USDT');
    echo "BTC/USDT last: {$ticker['last']}\n";
} catch (CryptohopperException $e) {
    fwrite(STDERR, "API error [{$e->getErrorCode()}]: {$e->getMessage()}\n");
    if ($e->getIpAddress()) {
        fwrite(STDERR, "  Server saw your IP as: {$e->getIpAddress()}\n");
    }
    exit(2);
}
