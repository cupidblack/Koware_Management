# Buzzjuice Payment Gateway Bridge 5.2

## Production flow

Streams browser -> `requests.php?f=payment` -> `wow-pgb_init.php` -> `shared/payment-gateway/pgb-client-streams.php` -> signed WordPress REST request -> PGB ledger -> WooCommerce order -> customer payment -> `woocommerce_payment_complete` -> Streams/AffiliateWP/Subscriptions/Jewel fulfillment.

The browser return URL is never the fulfillment trigger.

## Install

1. Back up WordPress DB, Streams DB, `shared/`, `wp-content/mu-plugins/`, old `wow-pgb_sync`, `streams/assets/wow-pgb`, `streams/wow-pgb_webhook.php`, and WooCommerce webhook configuration.
2. Create the supplied directory tree under the Buzzjuice root. Keep only `bzj-payment-gateway.php` directly in `wp-content/mu-plugins/`.
3. Set `BZJ_PGB_SECRET` in the server environment/.env. The same value must be available to WordPress and Streams.
4. Set `BZJ_PGB_STREAMS_LOGGING=1` only while diagnosing Streams-side requests.
5. Optional Jewel variables:
   - `BZJ_JEWEL_AFFILIATE_WEBHOOK_URL`
   - `BZJ_JEWEL_AFFILIATE_SECRET`
6. Replace `streams/assets/wow-pgb/wow-pgb_init.php` with the supplied wrapper.
7. Replace the old `wow-pgb_sync.php` execution path with the supplied compatibility shim, then remove it after acceptance testing.
8. Retire the WooCommerce webhook pointing to `streams/wow-pgb_webhook.php`.
9. Add/retain the payment button's existing POST fields. The bridge requires: amount, product_name, product_price, product_units, transaction_kind, wow_currency_code and, for a product, product_id/wow_market_id mapping. Existing PRO/WALLET/DONATE mappings are resolved server-side from Streams settings.
10. Verify `wp_bzj_pgb_transactions` and `Wo_PGB_Fulfillment` are created automatically.
11. Test one unpaid/cancelled order: no Streams purchase, user-order, wallet credit, PRO activation, notification or referral may be created.
12. Test one successful payment and repeat the completion hook: fulfillment must remain single-shot.

## Important v5.2 decisions

- AffiliateWP browser cookies are not transported. AffiliateWP is resolved in WordPress from the customer/order relationship.
- WooCommerce is the payment authority.
- Streams only records its existing payment-intent row and waits for the paid fulfillment path; it does not create a purchase.
- The transaction ledger is durable and is the retry/idempotency anchor.
- `wow-pgb_webhook.php` is retired.
- The old hard-coded webhook secret is not used.
- WooCommerce API consumer credentials are no longer required in Streams for PGB.
