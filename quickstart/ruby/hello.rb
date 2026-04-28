#!/usr/bin/env ruby
# frozen_string_literal: true

# Cryptohopper Ruby SDK — hello world.
#
# Authenticates with a bearer token from CRYPTOHOPPER_TOKEN, then makes
# two calls: user.get and exchange.ticker.
#
# Run:
#   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
#   bundle install
#   ruby hello.rb

require "cryptohopper"

token = ENV.fetch("CRYPTOHOPPER_TOKEN") do
  warn "Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first."
  exit 1
end

ch = Cryptohopper::Client.new(api_key: token)

begin
  me = ch.user.get
  identity = me[:email] || me[:username] || me[:id] || "?"
  puts "Logged in as #{identity}"

  ticker = ch.exchange.ticker(exchange: "binance", market: "BTC/USDT")
  puts "BTC/USDT last: #{ticker[:last]}"
rescue Cryptohopper::Error => e
  warn "API error [#{e.code}]: #{e.message}"
  warn "  Server saw your IP as: #{e.ip_address}" if e.ip_address
  exit 2
end
