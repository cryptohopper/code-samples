// Poll a backtest until it terminates, then print the result.
//
// Backtests run async server-side. Backtest.Create returns immediately
// with an ID; you poll Backtest.Get until Status is "completed" or
// "failed". The backtest rate bucket is 1 req / 2s — 5-second polling
// stays well clear.
//
// Run (after `go mod tidy`):
//   export CRYPTOHOPPER_TOKEN=your-bearer
//   export HOPPER_ID=42
//   go run go.go

package main

import (
	"context"
	"encoding/json"
	"fmt"
	"os"
	"strconv"
	"time"

	cryptohopper "github.com/cryptohopper/cryptohopper-go-sdk"
)

func mustEnv(name string) string {
	v := os.Getenv(name)
	if v == "" {
		fmt.Fprintln(os.Stderr, "Set", name)
		os.Exit(1)
	}
	return v
}

func main() {
	token := mustEnv("CRYPTOHOPPER_TOKEN")
	hopperID, err := strconv.Atoi(mustEnv("HOPPER_ID"))
	if err != nil {
		fmt.Fprintln(os.Stderr, "HOPPER_ID must be an integer")
		os.Exit(1)
	}

	ch, err := cryptohopper.NewClient(token)
	if err != nil {
		panic(err)
	}
	ctx := context.Background()

	submitted, err := ch.Backtest.Create(ctx, map[string]any{
		"hopper_id":  hopperID,
		"start_date": "2026-01-01",
		"end_date":   "2026-04-01",
	})
	if err != nil {
		panic(err)
	}
	btID := submitted["id"]
	fmt.Printf("Submitted backtest %v\n", btID)

	for {
		bt, err := ch.Backtest.Get(ctx, fmt.Sprintf("%v", btID))
		if err != nil {
			panic(err)
		}
		status, _ := bt["status"].(string)
		fmt.Printf("  status=%s\n", status)
		if status == "completed" || status == "failed" {
			pretty, _ := json.MarshalIndent(bt, "", "  ")
			fmt.Println(string(pretty))
			return
		}
		time.Sleep(5 * time.Second)
	}
}
