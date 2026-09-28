# Buzzjuice Payment Gateway Bridge v14

## Architecture

Streams no longer performs a server-side HTTP/cURL request to the WooCommerce domain.

```text
WoWonder payment button
        |
        v
streams/assets/wow-pgb/wow-pgb_init.php
        |
        v
shared/payment-gateway/pgb-client-streams.php
        |
        | direct database insert
        v
wp_bzj_pgb_transactions (pending intent)
        |
        | signed browser redirect
        v
https://buzzjuice.net/pgb-checkout/
        |
        v
MU plugin: wp-content/mu-plugins/bzj-payment-gateway.php
        |
        | native PHP
        v
WC_Order
        |
        | official get_checkout_payment_url()
        v
WooCommerce order-pay / payment gateway
        |
        v
woocommerce_payment_complete
        |
        +--> WoWonder fulfillment
        +--> WooCommerce Subscriptions
        +--> AffiliateWP
        +--> Jewel Affiliate
        |
        v
WoWonder success URL
```

## Required environment values

The same `.env` / server environment must expose:

- `BZJ_PGB_HANDOFF_SECRET` — a long random secret shared by Streams and WordPress.
- `WP_BASE_SITE_URL` — normally `https://buzzjuice.net`.
- Existing WordPress/WoWonder DB variables used by `shared/db_helpers.php`.

Do not use a fallback handoff secret in production.

## Files

### Replace
- `streams/assets/wow-pgb/wow-pgb_init.php`

### Add
- `wp-content/mu-plugins/bzj-payment-gateway.php`
- `shared/payment-gateway/pgb-client-streams.php`
- `shared/payment-gateway/core/class-pgb-logger.php`
- `shared/payment-gateway/core/class-pgb-config.php`
- `shared/payment-gateway/core/class-pgb-streams-db.php`
- `shared/payment-gateway/core/class-pgb-manager.php`
- `shared/payment-gateway/addons/pgb-addon-wowonder.php`
- `shared/payment-gateway/addons/pgb-addon-subscriptions.php`
- `shared/payment-gateway/addons/pgb-addon-affiliate-wp.php`
- `shared/payment-gateway/integrations/pgb-int-jewel-affiliate.php`

### Retire after validation
- `wp-content/plugins/blue-crown-wp/wow-pgb_sync/wow-pgb_sync.php`
- `wp-content/mu-plugins/buzzjuice-affwp-order-complete.php`
- `streams/wow-pgb_webhook.php`

The old files must not execute concurrently with v14.

## Important implementation notes

1. The old WooCommerce REST credentials are no longer part of the payment path.
2. WooCommerce remains the payment authority.
3. The bridge creates a native `WC_Order` in WordPress.
4. The order payment URL is obtained with `$order->get_checkout_payment_url()`.
5. Fulfillment occurs only after WooCommerce reports the order as paid.
6. Every fulfillment component has an order-meta idempotency flag.
7. Failed non-critical components remain retryable.
8. Component logs are written to `shared/payment-gateway/log/` using the generating PHP filename.
9. The PGB pending ledger is separate from WoWonder's paid fulfillment tables.
10. No subdirectory is created under `wp-content/mu-plugins`.

## Streams request contract

The existing payment button can continue posting to `wow-pgb_init.php` with:

- `amount`
- `product_name`
- `product_price`
- `product_units`
- `product_owner_id`
- `transaction_kind`
- `wow_currency_code`
- optional `wow_post_id`
- optional `address_id`

The replacement init also resolves the WordPress user ID from WoWonder's `wp_user_id` field.

## Testing order

Run these in a staging copy first:

1. Product purchase — unpaid intent.
2. Product purchase — payment cancelled.
3. Product purchase — payment succeeds.
4. Duplicate button click.
5. Browser refresh on `/pgb-checkout/`.
6. PRO subscription.
7. Wallet top-up.
8. Fund/donation.
9. Affiliate-linked purchase.
10. Non-affiliate purchase.
11. Jewel variation + rebate.
12. Payment gateway timeout/retry.
13. WordPress/PHP restart between intent creation and checkout.
14. Replay an old handoff URL.
15. Verify no old webhook/sync code executes.

## Security

- Handoff signatures use HMAC-SHA256.
- Handoff timestamps expire.
- Tokens are random.
- Secrets are redacted from logs.
- No WooCommerce consumer key/secret is needed by Streams.
- The PGB database row is not accepted solely because it is present; the signed browser handoff is required.
