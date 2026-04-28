// Cryptohopper Dart SDK — hello world.
//
// Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
// two calls: user.get() and exchange.ticker().
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
//   dart pub get
//   dart run

import 'dart:io';

import 'package:cryptohopper/cryptohopper.dart';

Future<void> main() async {
  final token = Platform.environment['CRYPTOHOPPER_TOKEN'];
  if (token == null || token.isEmpty) {
    stderr.writeln('Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.');
    exitCode = 1;
    return;
  }

  final ch = CryptohopperClient(apiKey: token);
  try {
    final me = await ch.user.get() as Map<String, dynamic>;
    final identity = me['email'] ?? me['username'] ?? me['id'] ?? '?';
    print('Logged in as $identity');

    final ticker = await ch.exchange.ticker(
      exchange: 'binance',
      market: 'BTC/USDT',
    ) as Map<String, dynamic>;
    print('BTC/USDT last: ${ticker['last']}');
  } on CryptohopperException catch (e) {
    stderr.writeln('API error [${e.code}]: ${e.message}');
    if (e.ipAddress != null) {
      stderr.writeln('  Server saw your IP as: ${e.ipAddress}');
    }
    exitCode = 2;
  } finally {
    ch.close();
  }
}
