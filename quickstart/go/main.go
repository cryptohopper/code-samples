// Cryptohopper Go SDK — hello world.
//
// Authenticates with a bearer token from $CRYPTOHOPPER_TOKEN, then makes
// two calls: User.Get and Exchange.Ticker.
//
// Run:
//   export CRYPTOHOPPER_TOKEN=your-40-char-bearer
//   go run .

package main

import (
	"context"
	"errors"
	"fmt"
	"os"

	cryptohopper "github.com/cryptohopper/cryptohopper-go-sdk"
)

func main() {
	token := os.Getenv("CRYPTOHOPPER_TOKEN")
	if token == "" {
		fmt.Fprintln(os.Stderr, "Set CRYPTOHOPPER_TOKEN to a 40-char OAuth bearer first.")
		os.Exit(1)
	}

	ch, err := cryptohopper.NewClient(token)
	if err != nil {
		fmt.Fprintln(os.Stderr, "client init failed:", err)
		os.Exit(1)
	}

	ctx := context.Background()

	me, err := ch.User.Get(ctx)
	if err != nil {
		var ce *cryptohopper.Error
		if errors.As(err, &ce) {
			fmt.Fprintf(os.Stderr, "API error [%s]: %s\n", ce.Code, ce.Message)
			if ce.IPAddress != "" {
				fmt.Fprintf(os.Stderr, "  Server saw your IP as: %s\n", ce.IPAddress)
			}
			os.Exit(2)
		}
		fmt.Fprintln(os.Stderr, "transport error:", err)
		os.Exit(2)
	}
	fmt.Printf("Logged in as %v\n", firstNonEmpty(me, "email", "username", "id"))

	ticker, err := ch.Exchange.Ticker(ctx, "binance", "BTC/USDT")
	if err != nil {
		fmt.Fprintln(os.Stderr, "ticker error:", err)
		os.Exit(2)
	}
	fmt.Printf("BTC/USDT last: %v\n", ticker["last"])
}

func firstNonEmpty(m map[string]any, keys ...string) any {
	for _, k := range keys {
		if v, ok := m[k]; ok && v != nil && v != "" {
			return v
		}
	}
	return "?"
}
