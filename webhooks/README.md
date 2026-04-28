# Webhook receivers

Cryptohopper can POST events to a URL you control — fills, signal triggers, account events. These samples show how to receive and verify those webhooks.

| Language | Directory | Notes |
|----------|-----------|-------|
| **PHP** | [`php/`](./php/) | Receives a JSON body; logs to a file |

More languages coming as part of the next batch. For now, see the existing PHP receiver as a starting point — the key concept (read body, verify signature header if present, dispatch by event type) ports cleanly to any language.

## Registering a webhook

Use any SDK's `webhooks` resource:

```bash
# Node
ch.webhooks.create({ url: "https://your.app/cryptohopper", events: ["order_filled"] })

# Python
ch.webhooks.create({"url": "https://your.app/cryptohopper", "events": ["order_filled"]})

# Go
ch.Webhooks.Create(ctx, map[string]any{"url": "https://your.app/cryptohopper", "events": []string{"order_filled"}})
```

Or via the CLI:

```bash
cryptohopper webhooks create --url https://your.app/cryptohopper --event order_filled
```
