# AGENTS.md — Buzzjuice Network

## 1\. Purpose

This repository contains the Buzzjuice Network production codebase, including:

* WordPress/BuddyBoss
* Buzzjuice Streams (WoWonder)
* Buzzjuice Socials (QuickDate)
* WooCommerce integrations
* WooCommerce Subscriptions
* AffiliateWP integrations
* Buzzjuice custom bridges and MU plugins
* Shared integration utilities

This file defines the mandatory rules for AI coding agents and automated development agents working in this repository.

Detailed architecture and feature specifications live in the `docs/` directory and take precedence for feature-specific implementation details.

\---

# 2\. Agent Operating Principles

Agents MUST:

1. Inspect the existing implementation before changing it.
2. Understand the surrounding architecture before introducing new code.
3. Prefer minimal, targeted changes over broad rewrites.
4. Preserve existing working behaviour unless the task explicitly requires a change.
5. Follow the architecture and implementation documents associated with the task.
6. Add or update tests for behaviour being changed.
7. Never silently change unrelated functionality.
8. Never assume that an existing implementation is wrong merely because it differs from a preferred design.
9. Document important architectural decisions.
10. Stop and report when a required architectural decision is ambiguous or conflicts with existing project rules.

The repository is the source of truth for implementation state.

Conversation is for reasoning and planning.

GitHub Issues, Pull Requests, commits, tests, documentation, and code are the persistent engineering record.

\---

# 3\. Repository Architecture

The Buzzjuice Network consists of multiple application layers.

## WordPress / BuddyBoss

WordPress is the authoritative platform for:

* WordPress users
* BuddyBoss relationships
* WordPress memberships/subscriptions where applicable
* central account/session authority
* WordPress-side integrations

## Buzzjuice Streams

Streams is a WoWonder application.

Important:

* Do NOT assume WordPress functions are available.
* Do NOT use `wp-load.php` from Streams unless an explicit architecture decision authorizes it.
* Prefer existing Streams integration mechanisms and shared bridge utilities.
* Preserve existing WoWonder behaviour unless the task explicitly changes it.

## Buzzjuice Socials

Socials is a QuickDate application.

Important:

* Do NOT assume WordPress functions are available.
* Do NOT use `wp-load.php` from Socials unless explicitly authorized.
* Preserve existing QuickDate behaviour unless the task explicitly changes it.

## Shared Integration Layer

Shared integration code is located under:

`shared/`

Important shared utilities include:

* `shared/db\_helpers.php`
* `shared/wwqd\_bridge.php`

Use existing shared helpers where appropriate instead of duplicating database, authentication, logging, or bridge functionality.

\---

# 4\. Database Rules

## WordPress

Use the existing WordPress database mechanisms and APIs where appropriate.

## IAPD Database

New payment-gateway transaction/intention tables MUST be created in:

`koware\_iapd\_db`

Use:

`get\_iapd\_db\_conn()`

from:

`shared/db\_helpers.php`

Do not create new database credentials when an existing approved connection helper exists.

Do not move IAPD payment data into another database without an explicit architecture decision.

## Database Safety

Agents MUST NOT:

* silently change production schemas;
* delete existing transaction data;
* introduce destructive migrations without explicit approval;
* create filesystem-driven automatic schema rebuilds;
* drop tables as part of normal plugin initialization;
* assume a missing table should automatically be recreated in production.

Schema migrations must be explicit, reviewable, and reversible where practical.

\---

# 5\. Security Rules

Security-sensitive code must be treated as production code.

Agents MUST NOT:

* disable TLS certificate verification;
* hard-code credentials, API keys, tokens, or secrets;
* log passwords;
* log access tokens;
* log refresh tokens;
* log API credentials;
* log payment credentials;
* log complete signatures;
* trust browser-supplied financial values;
* trust browser-supplied user identity;
* bypass authorization;
* weaken authentication to make an integration work;
* introduce SQL injection vulnerabilities;
* construct SQL from untrusted input without appropriate parameterization;
* introduce open redirects;
* expose internal database credentials.

Sensitive values must be redacted in logs.

Security failures must fail closed where appropriate.

\---

# 6\. Payment Gateway Rules

Payment processing is safety-critical.

The payment architecture separates:

1. Business intent
2. WooCommerce order
3. Payment
4. Subscription lifecycle
5. Fulfilment
6. Affiliate processing
7. Redirect/status presentation

Do not collapse these into one operation.

## Authority

Buzzjuice Streams originates the purchase intent.

WooCommerce is the authority for WooCommerce orders and payment.

WooCommerce Subscriptions controls subscription lifecycle.

AffiliateWP remains the authority for AffiliateWP referral/commission processing.

The appropriate existing Jewel Affiliate integration remains the authority for Jewel Affiliate processing.

## Browser

The browser is a transport/UI mechanism.

The browser MUST NOT be treated as the financial authority.

Never trust browser-supplied:

* price;
* amount;
* currency;
* product price;
* subscription price;
* user identity;
* affiliate commission;
* transaction state.

Commercial values must be validated or calculated server-side.

## Payment Success

Payment completion MUST NOT automatically mean that all downstream fulfilment has completed.

Payment, fulfilment, affiliate effects, Jewel effects, and redirects may have independent states.

## WooCommerce Subscriptions

Do not manually activate a subscription before the corresponding payment lifecycle permits activation.

Use the deployed WooCommerce Subscriptions lifecycle and APIs.

Do not replace WooCommerce's subscription lifecycle with custom activation logic unless explicitly authorized.

## AffiliateWP

Existing working AffiliateWP logic is protected functionality.

Do not rewrite or replace AffiliateWP business logic as part of unrelated payment-gateway work.

Extract/refactor only when explicitly required and after preserving equivalent behaviour.

## Redirects

Existing payment redirects are part of the compatibility contract.

Do not remove or change an existing redirect without identifying:

* its current trigger;
* its current destination;
* its current conditions;
* its relationship to payment completion;
* its relationship to fulfilment.

\---

# 7\. Currency Rules

WooCommerce uses GHS/GHC as the configured base/shop currency unless an explicit architecture decision changes this.

Buzzjuice Streams may originate a transaction in another currency.

For example:

`ZAR → GHS → EUR`

may be required when:

* Streams source currency = ZAR;
* WooCommerce base currency = GHS;
* customer's active WooCommerce/WOOCS currency = EUR.

Do not incorrectly treat the ZAR amount as though it were already a GHS amount.

Payment systems must preserve, where applicable:

* source currency;
* source amount;
* base currency;
* base amount;
* checkout currency;
* checkout amount;
* exchange-rate information;
* conversion timestamp.

Do not trust a browser-supplied converted amount.

Rounding must be deterministic and performed according to the approved currency implementation.

\---

# 8\. Idempotency and Concurrency

Payment-related operations MUST be designed for retries and duplicate requests.

Assume that:

* the browser can retry;
* callbacks can be duplicated;
* requests can time out;
* workers can terminate;
* users can double-click;
* two requests can arrive simultaneously;
* a downstream service can respond successfully while the caller times out.

Use appropriate:

* unique constraints;
* idempotency keys;
* transaction identifiers;
* locking;
* state checks;
* retry controls.

Do not rely solely on JavaScript or browser state to prevent duplicates.

\---

# 9\. State Machines

Where a feature defines explicit states, implement valid state transitions rather than allowing arbitrary status mutation.

Payment state and fulfilment state must remain conceptually independent where the architecture requires it.

Illegal transitions should be rejected or safely ignored according to the feature specification.

\---

# 10\. Logging

Logging is encouraged for difficult integration code, but logs must be safe.

Logs should help answer:

* what operation occurred;
* when it occurred;
* which subsystem initiated it;
* what state transition occurred;
* whether the operation succeeded;
* what error category occurred;
* what correlation/transaction identifier was involved.

Do NOT log:

* passwords;
* API keys;
* access tokens;
* refresh tokens;
* complete payment credentials;
* secrets;
* raw authentication headers;
* complete signed payloads.

Use existing logging conventions where available.

Debug logging must be toggleable where the relevant feature requires it.

\---

# 11\. MU Plugin Rules

Buzzjuice MU plugins should use the approved naming convention.

New Buzzjuice MU plugins should normally use:

`bzj-\*.php`

For the Payment Gateway Bridge, the approved primary MU entry point is:

`wp-content/mu-plugins/bzj-payment-gateway.php`

Supporting payment-gateway code belongs under the approved shared payment-gateway directory rather than creating unnecessary additional MU plugin entry points.

Do not create multiple competing MU bootstrap files for the same subsystem without an explicit architecture decision.

\---

# 12\. Payment Gateway Bridge Architecture

For BZJ-PGB work, read the relevant documents before modifying code.

Expected documentation includes:

* `docs/payment-gateway/BZJ-PGB-001-architecture.md`
* `docs/payment-gateway/BZJ-PGB-002-implementation-spec.md`
* the current BZJ-PGB task document

The approved high-level architecture is:

Streams
→ durable payment intent
→ signed browser handoff
→ WordPress/MU
→ native WooCommerce APIs
→ WooCommerce order
→ WooCommerce checkout/payment
→ WooCommerce lifecycle
→ downstream fulfilment
→ reconciliation

Prefer native WooCommerce PHP APIs when execution is already inside WordPress.

Do not introduce server-to-self WooCommerce REST/cURL as the primary architecture merely because an API endpoint exists.

\---

# 13\. BZJ-PGB-003 Scope Control

When implementing BZJ-PGB-003, limit changes to the approved scope.

BZJ-PGB-003 establishes the payment-intent foundation.

It includes, where specified:

* evidence inventory;
* IAPD database abstraction;
* payment-intent schema;
* payment-intent creation/retrieval;
* idempotency;
* state management;
* locking/concurrency protection;
* expiry/retry foundation;
* currency abstraction;
* automated tests.

BZJ-PGB-003 does NOT authorize:

* production replacement of the existing payment bridge;
* deletion of legacy payment code;
* WooCommerce order creation;
* checkout replacement;
* manual subscription activation;
* AffiliateWP rewrite;
* Jewel Affiliate rewrite;
* webhook replacement;
* unreviewed redirect changes.

Do not implement later phases merely because they appear logically convenient.

\---

# 14\. Legacy Systems

Existing payment functionality may contain production behaviour that is not obvious from the architecture documentation.

Before replacing or removing legacy functionality:

1. identify current callers;
2. identify hooks;
3. identify redirects;
4. identify database records;
5. identify webhook dependencies;
6. identify AffiliateWP dependencies;
7. identify subscription dependencies;
8. identify rollback implications.

Legacy code should be removed only after the replacement is proven and the migration plan explicitly permits removal.

\---

# 15\. Testing Requirements

Changes should include appropriate automated tests.

Depending on the feature, test:

* normal operation;
* invalid input;
* duplicate requests;
* concurrent requests;
* retries;
* failure recovery;
* state transitions;
* authorization;
* currency conversion;
* rounding;
* database failures;
* downstream failures;
* backwards compatibility.

For payment work, tests should specifically consider:

* duplicate payment intents;
* duplicate order creation;
* payment completion followed by retry;
* fulfilment retry;
* browser closed before fulfilment completes;
* browser reopened after payment;
* source currency different from WooCommerce base currency;
* active checkout currency different from both.

Never remove a regression test merely to make CI pass.

\---

# 16\. Git and Branch Rules

Do not commit directly to `main`.

Use task-specific branches.

Recommended naming:

`pgb/bzj-pgb-003`

`pgb/bzj-pgb-004`

For focused fixes:

`fix/pgb-003-idempotency`

`fix/pgb-003-currency`

`fix/pgb-003-concurrency`

One major architecture phase should normally correspond to one reviewable Pull Request.

Do not combine unrelated features into the payment-gateway PR.

Keep commits logically grouped and descriptive.

Do not rewrite shared branch history unless explicitly authorized.

\---

# 17\. Agent Roles

The project may use multiple AI development agents.

The preferred responsibility model is:

### ChatGPT/Cove

Architecture, requirements analysis, specifications, implementation planning, integration reasoning, and review orchestration.

### GitHub Copilot

Primary implementation agent.

### Jules

Independent implementation/engineering validation and review.

### Claude

Adversarial architecture and security review.

### GitHub Actions

Deterministic automated validation.

No AI agent is the final authority for production payment changes.

Human approval is required before merging critical payment changes.

\---

# 18\. Multi-Agent Review

For significant payment changes:

1. Primary implementation is performed on the task branch.
2. A Pull Request is opened.
3. Automated tests run.
4. Jules independently reviews the implementation.
5. Claude performs adversarial/security review.
6. Findings are returned to the implementation agent.
7. Fixes include regression tests where appropriate.
8. Automated tests run again.
9. Critical and High findings must be resolved.
10. Human review occurs.
11. Only then is the PR merged.

Review agents should not silently modify the primary implementation branch unless explicitly instructed.

\---

# 19\. Pull Request Requirements

Every significant PR should explain:

* what changed;
* why it changed;
* which architecture decision authorizes it;
* files changed;
* database changes;
* security implications;
* compatibility implications;
* tests performed;
* rollback considerations.

For payment changes, also document:

* transaction states affected;
* idempotency behaviour;
* currency behaviour;
* fulfilment behaviour;
* redirect behaviour.

\---

# 20\. Stop Conditions

An agent MUST stop and report rather than guessing when:

* an architecture decision is contradictory;
* required credentials are unavailable;
* a database schema is unclear;
* a payment lifecycle assumption cannot be verified;
* an existing production behaviour would need to be changed unexpectedly;
* a security control would need to be weakened;
* a destructive migration appears necessary;
* a requested change exceeds the current issue scope.

Do not invent missing infrastructure.

Do not silently broaden the task.

\---

# 21\. Documentation

When a change introduces an important architectural decision, update the relevant documentation.

Prefer:

`docs/`

for detailed architecture and implementation specifications.

Keep this `AGENTS.md` focused on durable repository-wide rules.

Do not turn this file into a complete technical manual.

\---

# 22\. Final Principle

For Buzzjuice payment and integration work:

**Understand before modifying.**

**Preserve working behaviour.**

**Keep financial authority server-side.**

**Make operations idempotent.**

**Separate payment from fulfilment.**

**Test failure paths, not only success paths.**

**Use GitHub as the engineering source of truth.**

**One phase at a time.**

**No production payment change without independent review and human approval.**

