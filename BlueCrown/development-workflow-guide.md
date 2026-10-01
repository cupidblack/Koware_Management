# **BZJ-PGB-0100**

# **DEVELOPMENT WORKFLOW REVIEW**



Considering the coding being used to develop features on the Buzzjuice Network, Claude Code, ChatGPT Codex, Google Jules and GitHub Copilot platforms have been selected as the best to collaborate with to obtain reliable coding results. The current production workflow is as follows:



<<START CURRENT PRODUCTION WORKFLOW>>



1 I create a prompt then share with ChatGPT.



2\. Review ChatGPT's response to develop the prompt.



3\. Share the updated prompt with ChatGPT and GitHub.



4\. Share the prompt with ChatGPT including GitHub's response.



5\. Update the prompt if necessary then share the prompt including ChatGPT's response with GitHub.



6\. Repeat the process of updating and sharing prompts and responses between Chatgpt and GitHub untill a working solution is obtained.



<<STOP CURRENT PRODUCTION WORKFLOW>>



Thoroughly review and analyze this workflow then develop and update it into a feasibly effective, efficient and sturdy workflow infrastructure that would yield the best results.



Additionally consider the implementation of any feasible development tools that would enhance the production workflow and possibly even replace any of the currently implemented tools.



The following are previous workflow developments that were attempted:



<<START PRODUCTION WORKPLOW INFRASTRUCTURES>>



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/bcrd-production-wordkflow-0.2.txt



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/bcrd-production-wordkflow-0.1.txt



<<STOP PRODUCTION WORKPLOW INFRASTRUCTURES>>

# 

# 

# 

# **BZJ-PGB-0200**

# **Initial Architectural Analysis**



Note the following project initialization considerations:



<<START BZJ-PGB-0210 INITIAL CONSIDERATION NOTES>>



1\. Create GitHub Project: Buzzjuice Market Payment Gateway Bridge

and set project attributes and description. This project uses the 'BZJ-PGB' prefix identifier with four digits used to identify specific sections. The second digit is used as the section counter whereas the last two digits identify a specific subsection. For instance, BZJ-PGB-4324 means subsection 24 of section 43 of the Buzzjuice Payment Gateway Bridge project.



2\. Assign each agent to it's own git branch so that the agent can automatically push their responses to their assigned branch: 



Default Structure: 'main/department-name/project-name/agent-name'



Kilo Code: 'main/buzzjuice-market/bzj-pgb/kilo-code'

Google Jules: 'main/buzzjuice-market/project-name/google-jules'

GitHub Copilot: 'main/buzzjuice-market/project-name/github-copilot'

ChatGPT Codex: 'main/buzzjuice-market/project-name/chatgpt-codex'



3\. Review and update AGENTS.md (buzzjuice.net, wp-content, wp-content/mu-plugins, streams, social, shared, data)



4\. Confirm skills folder ('.git/skills/project-name' or '.github/skills/project-name' ?? buzzjuice.net/.git folder exists, .github does not exist)



5\. Confirm Architecture Decision Records location (buzzjuice.net/data/docs/ADR)



6\. Confirm Decision Ledger location (buzzjuice.net/data/docs/ADR/decisions)



7\. Create a task packet that all agents would use. (could use a centralized task packet file?).



8\. Implement an 'agent challenge before build' gate to assist in discovering potential issues after production code has been implemented.



9\. Implement explicit stop conditions.



<<STOP BZJ-PGB-0210 INITIAL CONSIDERATION NOTES>>



Thoroughly review and analyze the following workflow infrastructure guides then appropriately use the tools and techniques addressed in the guides to effectively and efficiently support and guide the implementation and production workflow of this project to completion:

<<START PRODUCTION WORKFLOW INFRASTRUCTURE GUIDES>>



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/bcrd-production-wordkflow-0.3.txt



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/bcrd-production-wordkflow-0.2.txt



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/bcrd-production-wordkflow-0.1.txt



<<STOP PRODUCTION WORKFLOW INFRASTRUCTURE GUIDES>>



Referencing the workflow infrastructure guides to take me step by step through to the completion of the project, thoroughly review and analyze the following prompt to develop it into an 'Architectural Engineering Analysis' that would be shared with Claude, Jules and Copilot agents. The agent responses to the 'Architectural Engineering Analysis' would be shared here to help produce an 'Architectural Decision Record' that would be used for the final implementation after specifications have been verified. 



# **<<START BZJ-PGB-0220 Initial Prompt>>**



The Buzzjuice Streams (WoWonder) Payment Gateway bridge experiences frequent dropped orders and requires and upgrade.



The recent development of the connections sync has proved a steady file system with codes integrated into specific Buzzjuice Streams (WoWonder) files.



The current Buzzjuice Streams (WoWonder) Payment Gateway bridge generally works in the following way:



1\. Payment setting are set in Buzzjuice Streams (WoWonder) admin.



2\. A button on a Buzzjuice Streams (WoWonder) popup modal initiates a payment when tapped.



3\. The wow-pgb\_init.php file captures and prepares the order data to send to WooCommerce.



4\. WooCommerce captures the order then processes the order for payment by the user.



5\. On successful payment, the 'wow-pgb\_sync.php' file is triggered by the complete order to finalize the payment and redirect appropriately. Affiliates rewards are also processed appropriately.



6\. The WooCommerce Subscriptions plugin handles order subscriptions on WordPress. The wow-pgb\_sync.php handles the mechanisms of appropriately assigning and activating user subscriptions.



7\. The buzzjuice-affwp-order-complete.php must-use plugin handles the processing of appropriate Affiliate commissions.



Here is a proposal for the upgrade:



<<START PROPOSAL>>



When running the following 'curl -I https://buzzjuice.net' in the local VS Code terminal on my PC the following response returns:



<<START VSCODE RESPONSE>>



enkaby@DESKTOP-UJNN8DR:\~/public\_html/buzzjuice.net$ curl -I https://buzzjuice.net

HTTP/1.1 200 OK

Date: Sun, 27 Sep 2026 21:07:37 GMT

Server: Apache

Expires: Thu, 19 Nov 1981 08:52:00 GMT

Cache-Control: no-store, no-cache, must-revalidate

Pragma: no-cache

Link: [https://buzzjuice.net/wp-json/](https://buzzjuice.net/wp-json/); rel="https://api.w.org/", [https://buzzjuice.net/wp-json/wp/v2/pages/2](https://buzzjuice.net/wp-json/wp/v2/pages/2); rel="alternate"; title="JSON"; type="application/json", [https://buzzjuice.net/](https://buzzjuice.net/); rel=shortlink

Set-Cookie: PHPSESSID=63467154d41cec54534cf79488b206f7; path=/

Strict-Transport-Security: max-age=31536000; includeSubDomains; preload

X-Content-Type-Options: nosniff

Referrer-Policy: strict-origin-when-cross-origin

X-Frame-Options: SAMEORIGIN

Vary: User-Agent,Accept-Encoding

Connection: keep-alive

Content-Type: text/html; charset=UTF-8



<<STOP VSCODE RESPONSE>>



When running the same 'curl -I https://buzzjuice.net' in the cPanel terminal, the following response is returned:



<<START CPANEL RESPONSE>>



curl: (6) Could not resolve host: buzzjuice.net



<<STOP CPANEL RESPONSE>>



After doing some research, it seems as though running curl code to query the same domain returns the host resolution error. this means that accessing the WooCommerce API on the same domain might not work.



The following is a suggested alternative solution proposal that could be developed and updated to obtain the best possible model:



<<START API CURL ALTERNATIVE PROPOSAL>>



1\. Platform and product settings are configured in Buzzjuice Streams (WoWonder) admin. Configurations in the admin assist with mappings to WooCommerce products.



2 A button on a Buzzjuice Streams (WoWonder) popup modal initiates an order placement when tapped.



3\. When an order is made by the button being tapped, Buzzjuice Streams (WoWonder) captures the order data then stores it in the database.



4\. After the data is successfully stored in the database, {database insert is successful and verified) Buzzjuice Streams redirects to a special url that the 'bzj-payment-gateway.php' must-use plugin would recognize as a prompt to check the specific database table and row for the order.



5\. When the 'bzj-payment-gateway.php' must-use plugin has got the data from the database:



5.i. The 'bzj-payment-gateway.php' must-use plugin checks the order currency then performs the necessary calculations by getting the currently stored Fox WOOCS exchange rates and then using the appropriate Fox WOOCS codes and hooks to update the order price into the WooCommerce currency being displayed to the user.



https://github.com/cupidblack/buzzjuice.net/tree/main/wp-content/plugins/woocommerce-currency-switcher



https://currency-switcher.com/codex#shortcodes



https://currency-switcher.com/codex#actions



https://currency-switcher.com/codex#filters



https://currency-switcher.com/codex#functions



https://currency-switcher.com/compatibility



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/plugins/blue-crown-wp/wow-pgb\_sync/wow-pgb\_sync.php



5.ii. After updating the order's price and currency, 'bzj-payment-gateway.php' must-use plugin checks if the order contains a subscription product then creates/associates the related subscription if a subscription exists in the order.



5.iii. The 'bzj-payment-gateway.php' must-use plugin then automatically redirects to the checkout page with the order data populated in the checkout page, ready for the user to checkout. Using this method could bypass the API call limits and costs on the cPanel shared server account.



5.iv. When using the WooCommerce store API, an API call would be made to 'https://buzzjuice.net/wp-json//wc/v3/orders' then a response from the API would contain a redetect url that WooCommerce would prepare to redirect to a special checkout page where the order details have already been prepared. The user can then tap on a button to checkout. The url supplied by the WooCommerce Store API may look something like this in the API response:



Function: $checkout\_url = $order->get\_checkout\_payment\_url(false);



Response: $checkout\_url = "/checkout/order-pay/{order\_id}/

&#x20;   ?pay\_for\_order=true

&#x20;   \&key=wc\_order\_...."



The Buzzjuice Streams (WoWonder) wow-pgb\_init.php file would find this redirect url in the curl API response and use it to redirect the browser to the checkout page.



After researching the WooCommerce plugin, the following files might be able to assist with generating the appropriate redirect url:



<<START WOOCOMMERCE ORDERS API \& REDIRECT RESOURCE>>



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/plugins/woocommerce/includes/class-wc-order.php



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/plugins/woocommerce/src/Internal/Abilities/AbilitiesRestBridge.php



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/plugins/woocommerce/includes/class-wc-auth.php



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/plugins/woocommerce/src/StoreApi/Schemas/V1/CheckoutSchema.php



<<STOP WOOCOMMERCE ORDERS API \& REDIRECT RESOURCE>>



Thoroughly review and analyze the WooCommerce plugin to identify the correct functions and codes to use to get the right checkout redirect url.



6\. On successful payment, the page would redirect as usual to the WooCommerce 'Thank You' page with invoice/receipt.



7\. The 'bzj-payment-gateway.php' must-use plugin would be triggered by the order complete action hook to:



7.i once payment is successful, activate any associated subscriptions attached to the order if any were found then sync and update roles on Buzzjuice Streams (WoWonder). Subscriptions should also be processed appropriately referencing the WooCommerce Subscriptions codes and hooks:



wcs\_order\_contains\_subscription($order\_id)



wcs\_get\_subscriptions\_for\_order(...)



https://woocommerce.com/document/subscriptions/develop/



https://woocommerce.com/document/self-service-dashboard-for-woocommerce-subscriptions/self-service-dashboard-for-woocommerce-subscriptions-hooks/



https://woocommerce.com/document/subscriptions/develop/action-reference/



https://woocommerce.com/document/subscriptions/develop/filter-reference/



https://woocommerce.com/document/subscriptions/develop/functions/



7.ii. Check for any affiliates attached to the customer then process and apply commission rewards processed appropriately if an affiliates and lifetime commissions are found. Affiliate WP hooks in the wow-pgb\_sync.php and buzzjuice-affwp-order-complete.php files available in the resources should be referenced for identifying a customers affiliates, calculating commission and awarding the deserved commissions appropriately:



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/plugins/blue-crown-wp/wow-pgb\_sync/wow-pgb\_sync.php



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/mu-plugins/buzzjuice-affwp-order-complete.php



Any Jewel affiliate rebates should be calculated at this stage.



<<STOP API CURL ALTERNATIVE PROPOSAL>>



<<STOP PROPOSAL>>



Thoroughly review and analyze the existing Buzzjuice Streams (WoWonder) payment gateway bridge codes, system and the suggested proposal then take me step-by-step through developing, updating and implementing the proposal as necessary to obtain a durable and fully functional updated Buzzjuice Streams (WoWonder) payment gateway bridge. Show the Buzzjuice Streams (WoWonder) files that need to be removed or modified and how to modify them appropriately.



Note the following:

<<START NOTES>>



1\. Affiliate WP functions have been integrated into the 'wow-pgb\_sync.php' file to successfully award affiliates. The integration is currently working well. The section can be reviewed for feasible and appropriate developments.



2\. The upgrade means that the existing files could be modified, moved or deleted.



3\. No subfolders should be created in the mu-plugins folder, only one 'bzj-payment-gateway.php' file. The remaining Payment Gateway Bridge files should reside in the 'buzzjuice.net/shared/payment-gateway' folder. The must-use plugin should technically be something like the engine that executes and manages processes between WooCommerce and the files in the payment gateway bridge in the 'buzzjuice.net/shared/payment-gateway' folder'. The Payment Gateway Bridge mediates between Buzzjuice Streams (WoWonder) and the must-use plugin.



3.i. Addon and integration folders can be created so that the must use plugin can scan those folders to effect addons and integrations such as the affiliate wp logic. For instance:



'buzzjuice.net/shared/payment-gateway/addons/pgb-addons-affiliate-wp.php'

'buzzjuice.net/shared/payment-gateway/addons/pgb-addons-{{addon-name}}.php'

'buzzjuice.net/shared/payment-gateway/int/pgb-int-jewel-affiliate.php'

'buzzjuice.net/shared/payment-gateway/int/pgb-int-{{integration-name}}.php'

'buzzjuice.net/shared/payment-gateway/js/pgp-js-{{java-script-name}}.js'



The structure is only for illustration and can be modified as required.



3.ii. The 'buzzjuice.net/shared/payment-gateway/' folder hosts the Buzzjuice Payment Gateway client files such as:



'buzzjuice.net/shared/payment-gateway/pgb-client.php'

'buzzjuice.net/shared/payment-gateway/pgb-client-streams.php'

'buzzjuice.net/shared/payment-gateway/log/pgb-client.log'

'buzzjuice.net/shared/payment-gateway/log/pgb-client-streams.log'

'buzzjuice.net/shared/payment-gateway/log/{{must-use-plugin-log-file}}.log'

'buzzjuice.net/shared/payment-gateway/log/pgb-addons-{{addon-name}}.log'



3.iii. Log files must be labeled appropriately to reference the name of the file where the error was generated in. For instance, an error in a 'buzzjuice.net/shared/payment-gateway/core/class-pgb-bootstrap.php' would result in a 'buzzjuice.net/shared/payment-gateway/log/class-pgb-bootstrap.log' file.



4\. Prefer to use Payment Gateway client hooks in in Buzzjuice Streams (WoWonder). The Payment Gateway client would then connect to the must use plugin and WooCommerce via hooks or API.



5\. The payment-settings in the Buzzjuice Streams (WoWonder) admin might need to be updated.



6\. Include extensive logging for error control and debugging purposes with enables/disabled toggle.



7\. The 'buzzjuice.net/shared/payment-gateway/js' folder could be used for java script if necessary.



8\. As WordPress is the authoritative platform, the must use plugin can directly alter Buzzjuice Streams (WoWonder) database as necessary. This might be useful for integration services like the jewel affiliate webhook after successful order.



9\. Buzzjuice Streams (WoWonder) database configurations are in the 'buzzjuice.net/shared/db\_helpers.php' file.



10\. Attempting to capture AffiliateWP context before leaving Streams is pointless. The payment gateway bridge can check if the purchasing user is linked to any affiliate then process appropriately.



11\. WooCommerce currency switcher hooks and functions have been integrated into the 'wow-pgb\_sync.php' file to handle currency conversions when necessary.



12\. As the wow-pgb\_sync.php file is large and has some long functions, the files could be split into parts where some codes would be integrations and some addons for instance. This could be adjusted as necessary.



13\. Make sure to generate full and functional codes with no truncation or code placeholders.



14\. If a WordPress admin page would be created, the admin page should be within the 'Buzzjuice' admin group that might already exists. For example:



Buzzjuice

\- Payment Gateways



15\. Note that Buzzjuice Streams (WoWonder) does not implement wp-load.php so WordPress functions are not available in Buzzjuice Streams (WoWonder).



16\. Some key file locations to note when preparing require\_once or includes:



'buzzjuice.net/streams/assets/init.php'

'buzzjuice.net/streams/requests.php'

'buzzjuice.net/shared/db\_helpers.php'



17\. The Payment Gateway Bridge must check the integrity of the database tables on first run and whenever a new addon or integration is placed in or removed from the addons or integrations folder. Database tables should be recreated if missing.



<<STOP NOTES>>



<<RESOURCES>>

https://github.com/cupidblack/buzzjuice.net/blob/main/streams/assets/includes/functions\_two.php



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/admin-panel/pages/payment-settings/content.phtml



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/assets/wow-pgb/wow-pgb\_init.php



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/requests.php



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/themes/sunshine/layout/modals/pay-go-pro.phtml



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/themes/sunshine/layout/container.phtml



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/themes/sunshine/layout/extra\_js/content.phtml



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/sources/checkout.php



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/themes/sunshine/layout/checkout/content.phtml



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/themes/sunshine/layout/checkout/item.phtml



https://github.com/cupidblack/buzzjuice.net/blob/main/shared/db\_helpers.php



https://github.com/cupidblack/buzzjuice.net/blob/main/shared/wwqd\_bridge.php



https://github.com/cupidblack/buzzjuice.net/tree/main/wp-content/plugins/woocommerce



https://github.com/cupidblack/buzzjuice.net/tree/main/wp-content/plugins/woocommerce-subscriptions



https://github.com/cupidblack/buzzjuice.net/tree/main/wp-content/plugins/woocommerce-currency-switcher



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/plugins/blue-crown-wp/wow-pgb\_sync/wow-pgb\_sync.php



https://github.com/cupidblack/buzzjuice.net/blob/main/wp-content/mu-plugins/buzzjuice-affwp-order-complete.php



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/wow-pgb\_webhook.php



https://github.com/cupidblack/buzzjuice.net/tree/main/wp-content/plugins/affiliate-wp



https://github.com/cupidblack/buzzjuice.net/tree/main/wp-content/plugins/affiliate-wp-lifetime-commissions



https://github.com/cupidblack/buzzjuice.net/tree/main/wp-content/plugins/affiliate-wp-recurring-referrals



https://github.com/cupidblack/buzzjuice.net/blob/main/streams/jewel-affiliate-webhook.php



# **<<STOP BZJ-PGB-0220 Initial Prompt>>**

# 

# 

# 

# **BZJ-PGB-0300**

# **Multi-Agent Brief Analysis**



The following 'Brief Analysis Query' generated by the lead agent must be shared with all collaborating agents for their responses to be reviewed: 



# **<<START BZJ-PGB-0305 Brief Analysis Query>>**



\# BZJ-PGB-0020 — Buzzjuice Payment Gateway Bridge



Repository: https://github.com/cupidblack/buzzjuice.net



\## CONTROLLED EXPERIMENT — ARCHITECTURE DISCOVERY ONLY



This is the first controlled experiment in the Buzzjuice multi-agent engineering workflow.



The purpose of this experiment is to establish an evidence-based understanding of the existing Buzzjuice Streams Payment Gateway Bridge before any production implementation is changed.



You are performing \*\*architecture discovery and forensic analysis only\*\*.



\---



\# 1. ABSOLUTE EXPERIMENT CONSTRAINTS



1\. Do NOT modify production code.

2\. Do NOT create replacement files.

3\. Do NOT delete or move files.

4\. Do NOT create database tables or alter database data.

5\. Do NOT provide implementation patches as the primary output.

6\. Do NOT assume the proposed architecture is correct.

7\. Do NOT infer implementation details that cannot be established from repository evidence.

8\. Do NOT redesign unrelated Buzzjuice systems.

9\. Preserve existing working functionality unless evidence demonstrates that a change is required.

10\. Treat AffiliateWP functionality as protected functionality because it is currently working.

11\. Clearly distinguish verified facts from observations, inferences, hypotheses and recommendations.

12\. When evidence is insufficient, state:



\*\*UNKNOWN / REQUIRES VERIFICATION\*\*



rather than guessing.

13\. Do not silently fill gaps with assumptions.

14\. Do not recommend deletion or replacement of existing files until their responsibilities have been mapped.

15\. Do not write production implementation code during this experiment.



The output is an \*\*Engineering Architecture Analysis Report only\*\*.



\---



\# 2. ROLE



Act as the lead Platform Developer and senior PHP/WooCommerce integration engineer working with the Buzzjuice Network Enterprise Architect.



The system under investigation consists of:



\* WordPress

\* WooCommerce

\* WooCommerce Subscriptions

\* AffiliateWP

\* AffiliateWP Lifetime Commissions

\* AffiliateWP Recurring Referrals

\* WOOCS / WooCommerce Currency Switcher

\* Buzzjuice Streams (WoWonder)

\* Buzzjuice Socials (QuickDate)

\* Buzzjuice MU plugins

\* Buzzjuice shared PHP integration code



WordPress is the authoritative platform.



Buzzjuice Streams and Buzzjuice Socials do not load WordPress using `wp-load.php`.



\---



\# 3. BUSINESS PROBLEM



The Buzzjuice Streams Payment Gateway Bridge experiences intermittent dropped or incomplete orders.



The current conceptual flow is:



1\. Payment settings are configured in Streams.

2\. A payment option is selected.

3\. Streams initiates the payment transaction.

4\. `wow-pgb\\\\\\\_init.php` prepares payment/order information.

5\. WooCommerce receives and processes the order.

6\. Payment is completed.

7\. `wow-pgb\\\\\\\_sync.php` processes the completed order.

8\. WooCommerce Subscriptions handles subscription state.

9\. AffiliateWP processes eligible commissions.

10\. Streams/account state is synchronized.

11\. The customer is redirected appropriately.



This description is only a starting hypothesis.



\*\*Reconstruct the actual lifecycle from the repository.\*\*



\---



\# 4. REQUIRED EVIDENCE CLASSIFICATION



Every significant finding must be classified as one of:



\### VERIFIED



Directly supported by repository source code, configuration or official documentation.



\### OBSERVED



Known from an actual runtime/environment observation supplied to the investigation.



\### INFERRED



A conclusion derived from verified or observed evidence.



\### HYPOTHESIS



Plausible but not sufficiently proven.



\### RECOMMENDATION



A proposed architectural decision.



\### REQUIRES APPROVAL



A decision that should not be implemented until the project owner approves it.



\---



\# 5. SOURCE-OF-TRUTH RULE



Repository source code is the primary evidence for the current Buzzjuice implementation.



Official WooCommerce/WooCommerce Subscriptions documentation should be used to validate platform behavior and APIs.



Do not rely on generic WooCommerce knowledge where the repository or official documentation can establish the actual behavior.



When sources disagree:



1\. identify the disagreement;

2\. explain it;

3\. determine whether the installed Buzzjuice version/code must take precedence;

4\. mark unresolved issues as `UNKNOWN / REQUIRES VERIFICATION`.



\---



\# 6. REPOSITORY FILES TO INVESTIGATE



\## Streams



\* `streams/assets/wow-pgb/wow-pgb\\\\\\\_init.php`

\* `streams/requests.php`

\* `streams/assets/includes/functions\\\\\\\_two.php`

\* `streams/admin-panel/pages/payment-settings/content.phtml`

\* `streams/themes/sunshine/layout/modals/pay-go-pro.phtml`

\* `streams/themes/sunshine/layout/container.phtml`

\* `streams/themes/sunshine/layout/extra\\\\\\\_js/content.phtml`

\* `streams/sources/checkout.php`

\* `streams/themes/sunshine/layout/checkout/content.phtml`

\* `streams/themes/sunshine/layout/checkout/item.phtml`

\* `streams/wow-pgb\\\\\\\_webhook.php`

\* `streams/jewel-affiliate-webhook.php`



\## Shared



\* `shared/db\\\\\\\_helpers.php`

\* `shared/wwqd\\\\\\\_bridge.php`



\## WordPress



\* `wp-content/plugins/blue-crown-wp/wow-pgb\\\\\\\_sync/wow-pgb\\\\\\\_sync.php`

\* `wp-content/mu-plugins/buzzjuice-affwp-order-complete.php`



\## Platform integrations



Inspect the relevant installed implementation of:



\* WooCommerce

\* WooCommerce Subscriptions

\* WOOCS / WooCommerce Currency Switcher

\* AffiliateWP

\* AffiliateWP Lifetime Commissions

\* AffiliateWP Recurring Referrals



\---



\# 7. EXPERIMENT PHASE 1 — FORENSIC DISCOVERY



Before recommending any architecture, reconstruct the existing system.



Determine:



\### 7.1 Initiation



What exact request/action/event starts a payment?



Identify:



\* originating PHP file;

\* originating JavaScript if applicable;

\* request parameters;

\* user identification;

\* product/plan identification;

\* amount;

\* currency;

\* payment gateway;

\* relevant session/cookie information.



\### 7.2 Streams transaction preparation



Determine exactly what `wow-pgb\\\\\\\_init.php` does.



Document:



\* validation;

\* data transformation;

\* API requests;

\* cURL behavior;

\* timeout handling;

\* response handling;

\* database writes;

\* redirect generation;

\* error handling;

\* logging;

\* retry behavior.



\### 7.3 WooCommerce handoff



Determine exactly how Streams currently communicates with WooCommerce.



Possible mechanisms include:



\* REST API;

\* HTTP;

\* browser redirect;

\* database;

\* another integration mechanism.



Do not assume which one is used.



Document the exact mechanism.



\### 7.4 WooCommerce order creation



Determine:



\* where the order is created;

\* which API creates it;

\* which product is attached;

\* how price is established;

\* how currency is established;

\* customer mapping;

\* metadata;

\* payment method;

\* order status;

\* order key;

\* checkout/payment URL.



\### 7.5 Checkout



Determine exactly how the customer reaches payment.



Investigate:



\* checkout URL generation;

\* `get\\\\\\\_checkout\\\\\\\_payment\\\\\\\_url()`;

\* order key;

\* `pay\\\\\\\_for\\\\\\\_order`;

\* session requirements;

\* guest users;

\* logged-in users;

\* repeat visits;

\* refresh behavior.



\### 7.6 Payment completion



Determine which event actually causes post-payment processing.



Map:



\* order status hooks;

\* payment gateway callbacks;

\* WooCommerce hooks;

\* Subscriptions hooks;

\* custom webhooks;

\* browser return URLs.



\### 7.7 Post-payment synchronization



Trace exactly what happens after successful payment.



Include:



\* Streams subscription activation;

\* role changes;

\* account mapping;

\* AffiliateWP;

\* Jewel Affiliate;

\* redirects;

\* logging;

\* error handling.



\---



\# 8. EXPERIMENT PHASE 2 — DROPPED-ORDER FORENSICS



Identify every realistic location where state can be lost.



At minimum investigate:



1\. Streams request fails.

2\. Streams validation fails.

3\. Database insert fails.

4\. API request fails.

5\. DNS failure.

6\. HTTP timeout.

7\. WooCommerce creates order but response is lost.

8\. Browser closes after order creation.

9\. Browser closes before checkout.

10\. Checkout URL becomes invalid.

11\. Customer refreshes.

12\. Customer submits payment twice.

13\. Payment gateway callback occurs twice.

14\. WooCommerce completion hook fires more than once.

15\. Payment succeeds but synchronization fails.

16\. Subscription processing fails.

17\. Affiliate processing fails.

18\. Jewel Affiliate processing fails.

19\. Streams database update fails.

20\. Redirect fails.

21\. A transaction remains indefinitely pending.



For every failure mode provide:



\* failure location;

\* source-code evidence;

\* current protection;

\* impact;

\* recoverability;

\* recommended mitigation.



\---



\# 9. EXPERIMENT PHASE 3 — CURRENT ARCHITECTURE MAP



Produce a concrete transaction sequence.



Use this structure:



```text

User

\\\&#x20;↓

Streams UI

\\\&#x20;↓

Streams request

\\\&#x20;↓

Payment initialization

\\\&#x20;↓

\\\\\\\[actual current mechanism]

\\\&#x20;↓

WooCommerce

\\\&#x20;↓

Checkout

\\\&#x20;↓

Payment gateway

\\\&#x20;↓

Order status/callback

\\\&#x20;↓

Post-payment processing

\\\&#x20;↓

Subscriptions

\\\&#x20;↓

AffiliateWP

\\\&#x20;↓

Jewel Affiliate

\\\&#x20;↓

Streams synchronization

\\\&#x20;↓

Final redirect

```



Replace every placeholder with the actual implementation.



For each transition identify:



\* component;

\* file;

\* function/hook;

\* data passed;

\* persistence;

\* failure mode.



\---



\# 10. EXPERIMENT PHASE 4 — ARCHITECTURAL OPTIONS



Only after reconstructing the existing architecture, evaluate:



\### Option A



Streams → WooCommerce REST/API → browser checkout



\### Option B



Streams → durable payment intent → browser → WordPress → native WooCommerce PHP APIs → checkout



\### Option C



Streams → browser → WordPress endpoint → native WooCommerce processing



\### Option D



Current architecture with targeted reliability improvements



Also consider other architectures if repository evidence warrants them.



Do not assume any option is preferred before analysis.



For each option evaluate:



\* transaction durability;

\* network dependency;

\* DNS dependency;

\* security;

\* idempotency;

\* WooCommerce compatibility;

\* Subscriptions compatibility;

\* currency handling;

\* AffiliateWP compatibility;

\* recovery;

\* observability;

\* migration complexity;

\* rollback complexity.



\---



\# 11. SPECIAL DNS INVESTIGATION



The following has been observed:



From the local development environment:



```text

curl -I https://buzzjuice.net

HTTP/1.1 200 OK

```



From the cPanel/server environment:



```text

curl -I https://buzzjuice.net

curl: (6) Could not resolve host: buzzjuice.net

```



Treat this as an \*\*OBSERVED environmental condition\*\*.



Do not conclude automatically that:



\* WooCommerce REST API is unusable;

\* WordPress cannot communicate with itself;

\* native WooCommerce APIs are mandatory.



Explicitly distinguish:



\### A



Streams server → HTTP → WordPress



\### B



WordPress PHP → local WooCommerce PHP APIs



\### C



Browser → WordPress



\### D



WordPress → Streams database



Determine which communication paths are actually required by each architecture.



\---



\# 12. TRANSACTION STATE MODEL



Design the minimum durable state model required.



Evaluate states such as:



```text

created

validated

handoff\\\\\\\_pending

woocommerce\\\\\\\_order\\\\\\\_pending

woocommerce\\\\\\\_order\\\\\\\_created

checkout\\\\\\\_ready

payment\\\\\\\_pending

payment\\\\\\\_processing

payment\\\\\\\_completed

post\\\\\\\_payment\\\\\\\_processing

completed

failed

expired

cancelled

recovery\\\\\\\_required

```



Do not adopt them automatically.



For each proposed state document:



\* purpose;

\* entry condition;

\* responsible component;

\* valid transitions;

\* retry behavior;

\* terminal/non-terminal status;

\* recovery mechanism.



\---



\# 13. IDEMPOTENCY



Determine how duplicate processing should be prevented for:



\* payment intent creation;

\* WordPress handoff;

\* WooCommerce order creation;

\* checkout requests;

\* payment callbacks;

\* order completion;

\* subscription processing;

\* AffiliateWP;

\* Jewel Affiliate.



Inspect existing guards before designing new ones.



\---



\# 14. DATABASE DESIGN



Determine whether a dedicated payment transaction table is required.



If required, propose only fields justified by the lifecycle.



Consider:



\* transaction UUID;

\* Streams user ID;

\* WordPress user ID;

\* Streams reference;

\* WooCommerce order ID;

\* subscription ID(s);

\* product/plan;

\* amount;

\* source currency;

\* WooCommerce currency;

\* exchange information if required;

\* state;

\* payment status;

\* idempotency key;

\* retry count;

\* correlation ID;

\* timestamps;

\* expiration;

\* failure/recovery data.



Specify:



\* primary key;

\* unique constraints;

\* indexes;

\* migration/version strategy.



Do not create the table.



\---



\# 15. WOOCOMMERCE ORDER LIFECYCLE



Determine the correct WooCommerce mechanism for:



\* loading the product;

\* creating the order;

\* assigning the customer;

\* adding items;

\* pricing;

\* taxes if applicable;

\* currency;

\* payment gateway;

\* metadata;

\* saving;

\* generating payment URL;

\* payment processing;

\* completion.



Investigate native WooCommerce PHP APIs versus REST.



Do not invent functions.



\---



\# 16. SUBSCRIPTIONS



Investigate the installed WooCommerce Subscriptions implementation and official documentation.



Determine:



\* when subscription objects are created;

\* relationship between parent order and subscription;

\* when subscriptions are activated;

\* authoritative payment/completion hooks;

\* renewal behavior;

\* failed payment behavior;

\* duplicate activation protection;

\* Streams synchronization.



Explicitly determine whether creating/associating a subscription before payment is appropriate.



Do not assume it is.



\---



\# 17. WOOCS / CURRENCY



Trace the existing currency implementation.



Determine:



\* source currency;

\* selected currency;

\* exchange rate;

\* conversion point;

\* order currency;

\* payment amount;

\* persistence of original amount;

\* prevention of double conversion.



Treat the current `wow-pgb\\\\\\\_sync.php` currency implementation as reference material, not automatically as correct or incorrect.



\---



\# 18. AFFILIATEWP



AffiliateWP is protected functionality.



Investigate:



\* affiliate resolution;

\* customer resolution;

\* origin metadata;

\* processed guards;

\* commission calculation;

\* Lifetime Commissions;

\* Recurring Referrals;

\* order metadata;

\* duplicate protection.



Determine what should be preserved and what, if anything, requires change.



Do not rewrite functioning AffiliateWP logic for architectural cleanliness alone.



\---



\# 19. JEWEL AFFILIATE



Determine:



\* trigger;

\* source data;

\* user mapping;

\* order mapping;

\* subscription relationship;

\* duplicate protection;

\* failure handling;

\* retry behavior.



Determine whether it should be synchronous or recoverable asynchronously.



\---



\# 20. SECURITY



Investigate:



\* authentication;

\* authorization;

\* transaction ownership;

\* request signing;

\* replay protection;

\* CSRF;

\* parameter tampering;

\* amount manipulation;

\* currency manipulation;

\* product manipulation;

\* order-ID manipulation;

\* return URL manipulation;

\* unauthorized transaction lookup;

\* secret handling;

\* log leakage;

\* race conditions.



A browser must never be trusted as the authority for:



\* amount;

\* currency;

\* product price;

\* subscription entitlement;

\* affiliate attribution.



\---



\# 21. OBSERVABILITY



Design a structured logging model.



Logging should be:



\* extensive;

\* toggleable;

\* safe for production;

\* correlation-ID aware;

\* transaction-ID aware;

\* state-transition aware.



Never log:



\* passwords;

\* tokens;

\* payment credentials;

\* secrets;

\* unnecessary personal data.



\---



\# 22. RECOVERY



Design recovery for:



\* abandoned payment intent;

\* missing WordPress handoff;

\* failed order creation;

\* failed redirect;

\* successful payment with failed synchronization;

\* failed subscription synchronization;

\* failed AffiliateWP processing;

\* failed Jewel Affiliate processing;

\* duplicate callbacks;

\* stale transactions.



For each determine whether recovery is:



\* automatic;

\* scheduled;

\* manual;

\* user initiated;

\* terminal.



\---



\# 23. FILE IMPACT MATRIX



For every relevant file classify:



\* KEEP

\* MODIFY

\* MOVE

\* REPLACE

\* DEPRECATE

\* DELETE



Do not recommend deletion without mapping its responsibilities.



\---



\# 24. MIGRATION STRATEGY



Design a staged migration.



Consider:



\* coexistence;

\* feature flags;

\* rollback;

\* old transactions;

\* existing subscriptions;

\* existing AffiliateWP attribution;

\* existing products;

\* existing currency behavior;

\* monitoring.



\---



\# 25. TEST STRATEGY



Define:



\### Unit tests



\* validation;

\* state transitions;

\* idempotency;

\* signatures;

\* database operations.



\### Integration tests



\* Streams intent;

\* WordPress handoff;

\* WooCommerce order;

\* checkout;

\* payment;

\* subscriptions;

\* AffiliateWP;

\* Jewel Affiliate;

\* currency.



\### Failure tests



\* DNS failure;

\* timeout;

\* duplicate request;

\* duplicate completion;

\* browser abandonment;

\* failed synchronization;

\* stale transaction.



\### Regression tests



All existing working payment scenarios.



\---



\# 26. REQUIRED REPORT



Produce exactly these sections:



1\. Executive Summary

2\. Verified Existing Architecture

3\. Repository Evidence

4\. Current Transaction Lifecycle

5\. Dropped-Order Failure Analysis

6\. Root-Cause Hypotheses

7\. Architectural Options

8\. Transaction State Model

9\. Idempotency Model

10\. Database Design

11\. WooCommerce Order Lifecycle

12\. Checkout Architecture

13\. WooCommerce Subscriptions

14\. WOOCS / Currency

15\. AffiliateWP

16\. Jewel Affiliate

17\. Streams Integration

18\. MU Plugin Architecture

19\. Security

20\. Observability

21\. Failure Recovery

22\. File Impact Matrix

23\. Migration Strategy

24\. Test Strategy

25\. Open Questions

26\. Decisions Requiring Approval

27\. Recommended Next Stage



For every major conclusion identify the evidence supporting it.



At the end include:



\### CONFIDENCE SUMMARY



For each major architectural conclusion:



\* High

\* Medium

\* Low

\* Unknown / Requires Verification



Do not provide production code.



Do not modify the repository.



Do not treat the proposed architecture as approved.



The report is the deliverable of this controlled experiment.



# **<<STOP BZJ-PGB-0305 Brief Analysis Query>>**



**\*\*\*\*\***

The following are the BZJ-PGB-0300 'Brief Analysis Reports' prepared by Claude, Github and Jules for BZJ-PGB-0310 Architecture Verification:



<<START REPORTS>>



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/payment-gateway/BZJ-PGB-0300\_Brief-Analysis-Report-Claude-202609302301.txt



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/payment-gateway/BZJ-PGB-0300\_Brief-Analysis-Report-GitHub-202609302303.txt



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/payment-gateway/BZJ-PGB-0300\_Brief-Analysis-Report-Jules-202609302301.txt



<<STOP REPORTS>>



Note the following:



1\. Exact Payment Intent database is koware\_iapd\_db. All new database tables should be created in this database. Existing tables in existing databases can be modified as necessary.

Each agent presents a first and second report each.

**\*\*\*\*\***

# 

# 

# 

# **BZJ-PGB-0310**

# **Architecture Verification**



Before the 'Architectural Decision Record' can be established, the following unresolved areas need to be inspected to close the remaining evidence gaps for the 'Brief Analysis' to be approved :



<<START>>



<<STOP>>



\*\*\*\*\*

The following are 'Architecture-Verification' responses from Claude and GitHub including updated 'Brief Analysis' reports from Jules:



<<START Architecture Verification>>



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/payment-gateway/BZJ-PGB-0310\_Architecture-Verification-Claude-202610011053.txt



https://github.com/cupidblack/Koware\_Management/blob/main/BlueCrown/Laboratory/development/payment-gateway/BZJ-PGB-0310\_Architecture-Verification-GitHub-202610010051.txt



https://github.com/cupidblack/buzzjuice.net/blob/architecture-discovery-report-3688616800898817807/docs/architecture/BZJ-PGB-001-ENGINEERING-ARCHITECTURE-ANALYSIS.md



https://github.com/cupidblack/buzzjuice.net/blob/architecture-discovery-report-3688616800898817807/docs/architecture/BZJ-PGB-0200-AGENT-REPORT-JULES.md



<<STOP Architecture Verification>>

\*\*\*\*\*

# 

# 

# 

# **BZJ-PGB-0320**

# **Architecture Challenge**

# 

# 

# 

# **BZJ-PGB-0330**

# **Architectural Decision Record (ADR)**



Take me step by step through implementing the next stage with the developed 'Brief Analysis' prompt as a \*\*controlled production workflow development\*\*. Analyze the existing Buzzjuice architecture and produce an Architecture Decision Record and implementation strategy. Do not modify production code.



# **<<START BZJ-PGB-0335 Architectural Decision Record (ADR)>>**



\# BZJ-PGB-0030 — Buzzjuice Payment Gateway Bridge



\## Architecture Decision \& Approval



\*\*Date:\*\* 30 September 2026

\*\*Status:\*\* ARCHITECTURE APPROVED — IMPLEMENTATION SPECIFICATION GATED

\*\*Scope:\*\* Buzzjuice Streams (WoWonder) → WordPress/WooCommerce Payment Gateway Bridge



\---



\## 1. Decision Summary



The existing Payment Gateway Bridge shall be redesigned around a \*\*durable payment-intent architecture\*\*.



The approved target flow is:



```text

Buzzjuice Streams

\\\&#x20;   ↓

Server-side validation

\\\&#x20;   ↓

Durable Payment Intent

\\\&#x20;   ↓

Signed browser handoff

\\\&#x20;   ↓

WordPress/MU Plugin

\\\&#x20;   ↓

Native WooCommerce PHP APIs

\\\&#x20;   ↓

WooCommerce Order

\\\&#x20;   ↓

Native WooCommerce Checkout / Payment Gateway

\\\&#x20;   ↓

WooCommerce Payment Lifecycle

\\\&#x20;   ↓

Post-Payment Fulfilment

\\\&#x20;   ├── WooCommerce Subscriptions

\\\&#x20;   ├── AffiliateWP

\\\&#x20;   ├── Jewel Affiliate

\\\&#x20;   └── Streams Entitlements

\\\&#x20;   ↓

Durable Reconciliation / Recovery

```



The browser is a transport mechanism, not a financial authority.



Streams initiates the transaction, WooCommerce owns the order/payment lifecycle, and the Buzzjuice bridge orchestrates durable synchronization between systems.



\---



\## 2. Architecture Decisions



\### ADR-001 — Transaction Authority



\*\*Approved\*\*



Streams is the originating business system for the payment request.



WooCommerce is authoritative for:



\* WooCommerce order

\* payment status

\* payment gateway lifecycle

\* checkout

\* subscription lifecycle where WooCommerce Subscriptions applies



The bridge owns orchestration and cross-system synchronization.



\---



\### ADR-002 — Handoff Architecture



\*\*Approved\*\*



Use:



```text

Streams

\\\&#x20;→ durable payment intent

\\\&#x20;→ signed browser handoff

\\\&#x20;→ WordPress endpoint

\\\&#x20;→ native WooCommerce PHP APIs

\\\&#x20;→ WooCommerce payment URL

```



The new design shall not depend on a server-side HTTP request from Streams back to the same WordPress installation for the primary transaction path.



The existing REST/cURL path shall remain available only during migration and rollback.



\---



\### ADR-003 — Durable Payment Intent



\*\*Approved\*\*



Every new transaction must receive a durable unique transaction/payment-intent identifier.



The intent must contain sufficient information to reconstruct and reconcile the transaction without relying on browser state.



At minimum:



\* intent UUID

\* transaction kind

\* Streams user ID

\* WordPress user ID

\* authoritative product/plan reference

\* authoritative amount

\* source currency

\* WooCommerce currency

\* WooCommerce order ID

\* status

\* idempotency key

\* timestamps

\* expiry

\* retry/recovery information

\* correlation identifier



The exact schema remains subject to the Implementation Specification.



\---



\### ADR-004 — Server-Side Commercial Authority



\*\*Approved\*\*



The client shall not be trusted to determine:



\* final price

\* final amount

\* product price

\* subscription price

\* entitlement

\* affiliate attribution

\* final currency conversion



Streams must resolve authoritative commercial information before creating the payment intent.



\---



\### ADR-005 — WooCommerce Order Authority



\*\*Approved\*\*



When WordPress receives a valid payment intent, it shall use native WooCommerce PHP APIs where available.



The bridge shall:



1\. validate the intent;

2\. verify user identity;

3\. resolve the WooCommerce product/variation;

4\. resolve authoritative pricing;

5\. create or retrieve the WooCommerce order;

6\. attach the intent/correlation metadata;

7\. generate the WooCommerce payment URL;

8\. return/redirect the customer to the payment screen.



Repeated handoff of the same intent must return the existing order rather than create another order.



\---



\### ADR-006 — WooCommerce Subscriptions Authority



\*\*Approved with verification gate\*\*



WooCommerce Subscriptions remains authoritative for subscription lifecycle.



The bridge shall not manually mark a subscription active merely because a payment intent was created.



Subscription creation, activation, renewal and failed-payment behavior must be validated against the actual deployed WooCommerce Subscriptions configuration before implementation.



\---



\### ADR-007 — AffiliateWP Protection



\*\*Approved\*\*



The existing AffiliateWP behavior is a protected subsystem.



The redesign must preserve:



\* attribution contract;

\* context snapshot;

\* customer resolution;

\* affiliate resolution;

\* commission calculation;

\* duplicate referral protection;

\* `\\\\\\\_affwp\\\\\\\_bridge\\\\\\\_processed` behavior;

\* retry-on-failure behavior.



Refactoring the file location is permitted.



Changing business behavior requires a separate decision and regression evidence.



\---



\### ADR-008 — Post-Payment Fulfilment



\*\*Approved\*\*



Payment success and downstream fulfilment are separate states.



A successful WooCommerce payment must never be treated as equivalent to successful Buzzjuice synchronization.



The system must support:



```text

PAYMENT\\\\\\\_SUCCESS

\\\&#x20;      ↓

FULFILMENT

\\\&#x20;      ├── Streams

\\\&#x20;      ├── Subscription

\\\&#x20;      ├── AffiliateWP

\\\&#x20;      └── Jewel Affiliate

\\\&#x20;      ↓

COMPLETED

```



If any downstream operation fails after payment, the transaction enters a recoverable state rather than being lost.



\---



\### ADR-009 — Idempotency



\*\*MANDATORY\*\*



Idempotency must exist at multiple boundaries:



1\. payment-intent creation;

2\. WordPress handoff;

3\. WooCommerce order creation;

4\. payment callbacks/webhooks;

5\. order completion;

6\. subscription processing;

7\. Streams entitlement updates;

8\. AffiliateWP referral processing;

9\. Jewel Affiliate rebate processing.



A single global `processed` flag is insufficient.



\---



\### ADR-010 — Transaction State Machine



\*\*Approved conceptually\*\*



The minimum lifecycle shall support:



```text

CREATED

\\\&#x20;  ↓

VALIDATED

\\\&#x20;  ↓

HANDOFF\\\\\\\_PENDING

\\\&#x20;  ↓

WC\\\\\\\_ORDER\\\\\\\_CREATED

\\\&#x20;  ↓

PAYMENT\\\\\\\_PENDING

\\\&#x20;  ↓

PAYMENT\\\\\\\_PROCESSING

\\\&#x20;  ↓

PAYMENT\\\\\\\_COMPLETED

\\\&#x20;  ↓

POST\\\\\\\_PAYMENT\\\\\\\_SYNC

\\\&#x20;  ↓

COMPLETED

```



Recovery states:



```text

FAILED

CANCELLED

EXPIRED

RECOVERY\\\\\\\_REQUIRED

```



The exact transition rules shall be finalized in the Implementation Specification.



\---



\### ADR-011 — Dedicated Transaction Storage



\*\*Approved conceptually\*\*



A dedicated Buzzjuice payment-intent/transaction table is justified.



It must not replace or corrupt existing WoWonder payment records.



The new table becomes the bridge's durable orchestration record while existing WoWonder tables remain compatibility/integration records where required.



Schema, indexes, uniqueness constraints and migration strategy require implementation-specification approval.



\---



\### ADR-012 — Reconciliation



\*\*MANDATORY\*\*



The new bridge must support recovery of:



\* payment intents without WooCommerce orders;

\* WooCommerce orders without linked payment intents;

\* paid orders without Streams fulfilment;

\* subscriptions without synchronized Streams entitlement;

\* missing AffiliateWP commissions;

\* missing Jewel Affiliate processing;

\* stale pending transactions;

\* duplicate webhook deliveries.



Recovery must be retryable and observable.



\---



\### ADR-013 — Observability



\*\*Approved\*\*



All transaction stages must share:



\* intent UUID;

\* correlation ID;

\* WooCommerce order ID where available;

\* transaction kind;

\* state;

\* transition;

\* processing attempt;

\* outcome;

\* error code.



Logs must be toggleable and must not expose:



\* passwords;

\* session tokens;

\* API secrets;

\* webhook secrets;

\* payment credentials;

\* complete POST bodies;

\* unnecessary personal information.



\---



\### ADR-014 — Legacy Migration



\*\*Approved\*\*



The existing bridge shall not be deleted immediately.



Migration shall proceed through controlled stages:



```text

Evidence

\\\&#x20; ↓

Instrumentation

\\\&#x20; ↓

Reliability/security hardening

\\\&#x20; ↓

Shadow payment-intent creation

\\\&#x20; ↓

Controlled cutover

\\\&#x20; ↓

Verification

\\\&#x20; ↓

Legacy retirement

```



Existing in-flight transactions must remain serviceable during migration.



\---



\### ADR-015 — Filesystem Architecture



\*\*Approved conceptually\*\*



The target should have one primary MU entry point:



```text

wp-content/mu-plugins/bzj-payment-gateway.php

```



Supporting implementation should live under:



```text

shared/payment-gateway/

```



The exact module structure must remain intentionally small and responsibility-driven.



The architecture must not become a large framework merely for the sake of abstraction.



\---



\## 3. Explicit Rejections



The following are NOT approved:



\### R-001



Rebuilding database tables automatically whenever plugin/addon files change.



\### R-002



Deleting the existing payment bridge before migration and regression verification.



\### R-003



Trusting browser-supplied amount, price or currency.



\### R-004



Creating/activating subscriptions prematurely outside the WooCommerce Subscriptions lifecycle.



\### R-005



Using server-to-self REST/cURL as the primary new transaction path.



\### R-006



Replacing working AffiliateWP business logic merely to simplify the new architecture.



\### R-007



Treating a successful payment as proof that every downstream fulfilment step succeeded.



\---



\## 4. Mandatory Verification Gates Before Coding



The following must be resolved before implementation begins:



1\. Confirm deployed code versus GitHub `main`.

2\. Confirm active WooCommerce payment gateway(s).

3\. Confirm deployed WooCommerce Subscriptions version/configuration.

4\. Confirm actual subscription product/variation types.

5\. Confirm WOOCS/currency behavior in the deployed environment.

6\. Confirm PHP web-SAPI DNS/loopback behavior separately from CLI cURL.

7\. Confirm WordPress authentication/session behavior when entering from Streams.

8\. Confirm existing WooCommerce order metadata contract.

9\. Confirm all current WooCommerce webhooks and delivery behavior.

10\. Map legacy `Wo\\\\\\\_Payment\\\\\\\_Transactions` usage before modifying it.

11\. Confirm Jewel Affiliate's actual production invocation path.

12\. Confirm current AffiliateWP behavior against a real test order.

13\. Confirm whether the deployed bridge differs materially from repository `main`.

14\. Determine whether existing production orders/subscriptions require backward compatibility handling.



\---



\## 5. Implementation Rule



No production code is approved by this document.



The next artifact must be:



\*\*BZJ-PGB-002 — Implementation Specification\*\*



It must define:



\* exact database schema;

\* exact state machine;

\* exact files;

\* exact classes/functions;

\* exact hooks;

\* exact endpoints;

\* exact authentication/signing mechanism;

\* exact WooCommerce order creation procedure;

\* exact subscription integration;

\* exact AffiliateWP preservation mechanism;

\* exact Jewel Affiliate integration;

\* exact reconciliation mechanism;

\* exact logging switches;

\* exact migration flags;

\* exact rollback procedure;

\* unit/integration/failure tests;

\* deployment sequence.



Only after BZJ-PGB-002 is approved should Codex/Jules/Claude begin implementation work.



# **<<STOP BZJ-PGB-0335 Architectural Decision Record (ADR)>>**

# 

# 

# 

# **BZJ-PGB-0340**

# **Implementation Specification**



Proceed with 'Verification \& Implementation-Specifications' noting the following:



<<START NOTES>>



1\. Any new tables should be created in the koware\_iapd\_db database unless specifically specified. Connect using the get\_iapd\_db\_conn() function found in the buzzjuice.net/shared/db\_helpers folder.



2\. The redirects specified in the 'wow-pgb\_sync.php' must be effected appropriately after all is completed. This way if the browser is still open the user would be redirected appropriately from the 'Thank you' page.



3\. The 'Thank You' page could display the order state in a 'one-line' basic text, for example, something like:



Subscriptions: None | Affiliate: Completed | Jewel Affiliate/Credits/Rebates: Processing | Fulfilment: Processing | Redirect: Pending



Each item could appear as it is processed. This would give the customer basic details ono the status of their order.



4\. For price currency updates the model should work as follows:



Noting that the WooCommerce base currency is GHC/GHS, if the Buzzjuice Streams (WoWonder) product currency is ZAR and the user's WooCommerce currency is location based, Euros for instance, WooCommerce Currency/WOOCS should get the exchange rate to update the price from ZAR to Euros so that the equivalent Euro price displays on the checkout page.



<<STOP NOTES>>



# **<<START BZJ-PGB-0345 Implementation Specification>>**



\# BZJ-PGB-0040 — Buzzjuice Payment Gateway Bridge



\## Implementation Specification v1



\*\*Date:\*\* 30 September 2026

\*\*Status:\*\* DRAFT FOR HUMAN APPROVAL

\*\*Architecture:\*\* BZJ-PGB-001 Approved

\*\*Implementation:\*\* NOT YET APPROVED



\---



\# 1. Purpose



This specification converts the approved BZJ-PGB-001 architecture into an implementation contract for the Buzzjuice Streams (WoWonder) Payment Gateway Bridge.



The implementation must solve the existing dropped-order and synchronization problems without breaking:



\* existing WooCommerce orders;

\* WooCommerce Subscriptions;

\* AffiliateWP;

\* Jewel Affiliate;

\* WOOCS/currency handling;

\* existing Streams transaction records;

\* existing customer redirects;

\* in-flight legacy transactions.



The implementation must be durable, idempotent, recoverable and observable.



\---



\# 2. Non-Negotiable Database Requirement



\## 2.1 Database



All \*\*new Buzzjuice Payment Gateway tables\*\* shall be created in:



```text

koware\\\\\\\_iapd\\\\\\\_db

```



unless a later approved architecture decision explicitly specifies another database.



They must NOT be created in:



```text

koware\\\\\\\_buzzjuice

```



or the WordPress database merely because the MU plugin executes inside WordPress.



\---



\## 2.2 Database Connection



The implementation must use:



```php

get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()

```



from:



```text

buzzjuice.net/shared/db\\\\\\\_helpers/

```



for connections to `koware\\\\\\\_iapd\\\\\\\_db`.



No new independent database credentials may be introduced.



No duplicate database connection mechanism may be created if the existing helper already provides the required connection.



\---



\# 3. New Durable Payment Intent



Create a dedicated payment-intent table in `koware\\\\\\\_iapd\\\\\\\_db`.



Proposed name:



```text

bzj\\\\\\\_payment\\\\\\\_intents

```



The exact SQL definition must be approved before deployment.



The table must contain, at minimum:



\### Identity



```text

id

intent\\\\\\\_uuid

idempotency\\\\\\\_key

correlation\\\\\\\_id

```



\### Buzzjuice identity



```text

streams\\\\\\\_user\\\\\\\_id

wp\\\\\\\_user\\\\\\\_id

wo\\\\\\\_user\\\\\\\_id

```



Only identifiers actually required by the deployed integration should be retained.



\### Transaction



```text

transaction\\\\\\\_kind

product\\\\\\\_id

product\\\\\\\_reference

plan\\\\\\\_reference

```



\### Currency/pricing



```text

source\\\\\\\_currency

source\\\\\\\_amount

base\\\\\\\_currency

base\\\\\\\_amount

checkout\\\\\\\_currency

checkout\\\\\\\_amount

exchange\\\\\\\_rate

exchange\\\\\\\_rate\\\\\\\_source

```



\### WooCommerce



```text

wc\\\\\\\_order\\\\\\\_id

wc\\\\\\\_order\\\\\\\_key

wc\\\\\\\_payment\\\\\\\_url

```



\### State



```text

intent\\\\\\\_status

payment\\\\\\\_status

fulfilment\\\\\\\_status

redirect\\\\\\\_status

```



\### Effect state



```text

subscription\\\\\\\_status

affiliate\\\\\\\_status

jewel\\\\\\\_affiliate\\\\\\\_status

streams\\\\\\\_fulfilment\\\\\\\_status

```



\### Recovery



```text

attempt\\\\\\\_count

last\\\\\\\_attempt\\\\\\\_at

next\\\\\\\_retry\\\\\\\_at

last\\\\\\\_error\\\\\\\_code

last\\\\\\\_error\\\\\\\_message

```



\### Lifecycle



```text

created\\\\\\\_at

validated\\\\\\\_at

handoff\\\\\\\_at

order\\\\\\\_created\\\\\\\_at

payment\\\\\\\_completed\\\\\\\_at

fulfilment\\\\\\\_completed\\\\\\\_at

completed\\\\\\\_at

expires\\\\\\\_at

updated\\\\\\\_at

```



The schema must include appropriate unique indexes, particularly around:



```text

intent\\\\\\\_uuid

idempotency\\\\\\\_key

```



and any other business key required to prevent duplicate order creation.



\---



\# 4. Existing WoWonder Transaction Records



The existing WoWonder payment transaction mechanism must NOT be discarded.



The new payment intent becomes the bridge's durable orchestration record.



Existing:



```text

Wo\\\\\\\_Payment\\\\\\\_Transactions

```



remains a compatibility/integration record where required by Streams.



The implementation must establish an explicit relationship:



```text

bzj\\\\\\\_payment\\\\\\\_intents.intent\\\\\\\_uuid

\\\&#x20;       ↕

WoWonder transaction identifier

\\\&#x20;       ↕

WooCommerce order ID

```



The exact metadata mapping must be documented.



\---



\# 5. Transaction State Machine



The implementation shall distinguish payment state from fulfilment state.



\## 5.1 Intent state



```text

CREATED

VALIDATED

HANDOFF\\\\\\\_PENDING

WC\\\\\\\_ORDER\\\\\\\_CREATED

PAYMENT\\\\\\\_PENDING

PAYMENT\\\\\\\_PROCESSING

PAYMENT\\\\\\\_COMPLETED

CANCELLED

EXPIRED

FAILED

```



\## 5.2 Fulfilment state



```text

NOT\\\\\\\_STARTED

PROCESSING

PARTIAL

COMPLETED

RECOVERY\\\\\\\_REQUIRED

```



\## 5.3 Redirect state



```text

PENDING

READY

REDIRECTED

NOT\\\\\\\_REQUIRED

FAILED

```



This separation is mandatory.



A paid order with failed downstream synchronization must be representable as:



```text

payment\\\\\\\_status = COMPLETED

fulfilment\\\\\\\_status = RECOVERY\\\\\\\_REQUIRED

```



It must never be incorrectly represented as an unpaid/failed transaction merely because a downstream integration failed.



\---



\# 6. Payment Intent Creation



Streams shall initiate a payment intent using server-side information.



The client/browser must not be trusted for:



\* final amount;

\* final price;

\* commission;

\* subscription entitlement;

\* affiliate commission;

\* Jewel rebate;

\* final currency conversion.



The browser may identify the requested transaction/product/plan, but the server must resolve the authoritative commercial data.



\---



\# 7. Currency Architecture



\## 7.1 Source Currency



The payment intent must preserve the currency in which the Streams product is defined.



Example:



```text

Streams product:

\\\&#x20;   Currency = ZAR

\\\&#x20;   Price = R500

```



The payment intent records:



```text

source\\\\\\\_currency = ZAR

source\\\\\\\_amount   = 500.00

```



This original value must never be overwritten.



\---



\# 7.2 WooCommerce Base Currency



WooCommerce's base currency is:



```text

GHS

```



The system must retain:



```text

base\\\\\\\_currency = GHS

```



WooCommerce itself supports one base currency; multi-currency conversion is provided by an extension such as WOOCS.



\---



\# 7.3 Customer Checkout Currency



The active customer currency must be determined from the actual WooCommerce/WOOCS session/context.



Example:



```text

Customer location:

\\\&#x20;   Europe



Active checkout currency:

\\\&#x20;   EUR

```



The intent records:



```text

checkout\\\\\\\_currency = EUR

```



\---



\# 7.4 Required Conversion



For:



```text

Streams source = ZAR

WooCommerce base = GHS

Customer checkout = EUR

```



the bridge must logically perform:



```text

ZAR

\\\&#x20;↓

GHS

\\\&#x20;↓

EUR

```



or an equivalent mathematically correct cross-rate calculation using the same authoritative WOOCS rate set.



The implementation must NOT treat:



```text

500 ZAR

```



as:



```text

500 GHS

```



and then call a base-currency conversion function.



\---



\# 7.5 WOOCS Integration



The implementation must use the installed WOOCS APIs/data rather than introducing a second unrelated exchange-rate system.



WOOCS exposes its currency data and PHP API through the `$WOOCS` object, including currency rates and conversion functionality.



The implementation must verify the exact installed WOOCS version and configuration before selecting the final conversion method.



The conversion service should therefore conceptually expose:



```php

convertSourceAmount(

\\\&#x20;   $amount,

\\\&#x20;   $source\\\\\\\_currency,

\\\&#x20;   $target\\\\\\\_currency

)

```



and return:



```text

converted\\\\\\\_amount

source\\\\\\\_currency

target\\\\\\\_currency

effective\\\\\\\_rate

rate\\\\\\\_source

timestamp

```



The implementation may use GHS as the mathematical bridge currency when the configured WOOCS rates are base-relative.



\---



\# 8. Currency Auditability



Every payment intent must retain enough information to reconstruct the conversion.



Example:



```text

Source:

\\\&#x20;   ZAR 500.00



Woo base:

\\\&#x20;   GHS 7,xxx.xx



Checkout:

\\\&#x20;   EUR xx.xx



Rate:

\\\&#x20;   recorded effective rate



Rate source:

\\\&#x20;   WOOCS



Rate timestamp:

\\\&#x20;   UTC timestamp

```



The final WooCommerce order currency must correspond to the currency actually presented/charged at checkout.



WOOCS supports carrying selected currencies through checkout and, when configured for multi-currency payment, recording the order in the selected currency.



\---



\# 9. Currency Rounding



The implementation must define rounding rules before order creation.



Do not repeatedly round during intermediate conversions.



Preferred process:



```text

ZAR source amount

\\\&#x20;       ↓

high-precision conversion

\\\&#x20;       ↓

target checkout amount

\\\&#x20;       ↓

WooCommerce currency precision

\\\&#x20;       ↓

final order line/total

```



The stored intent should preserve both:



```text

source\\\\\\\_amount

checkout\\\\\\\_amount

```



and the effective conversion data.



\---



\# 10. WooCommerce Order Creation



WordPress shall receive the signed payment-intent handoff.



The MU bridge must:



1\. validate the handoff;

2\. locate the payment intent;

3\. verify it is not expired/cancelled;

4\. authenticate/bind the user;

5\. acquire a transaction lock;

6\. check whether a WooCommerce order already exists;

7\. create the order only if necessary;

8\. associate the order with the intent;

9\. calculate/assign the correct checkout currency;

10\. calculate authoritative line pricing;

11\. save required metadata;

12\. generate the payment URL;

13\. release the lock.



Repeated requests must return the existing order.



\---



\# 11. Order Idempotency



This sequence must be safe:



```text

Browser request #1

\\\&#x20;   ↓

Create WC order 1234



Browser request #2

\\\&#x20;   ↓

Find intent

\\\&#x20;   ↓

Find WC order 1234

\\\&#x20;   ↓

DO NOT create order 1235

```



The same rule applies after:



\* browser refresh;

\* network timeout;

\* back button;

\* duplicate callback;

\* repeated payment link access.



\---



\# 12. Checkout Authority



WooCommerce owns the final checkout/payment process.



The bridge should not duplicate:



\* billing collection;

\* payment gateway UI;

\* checkout validation;

\* payment confirmation;

\* order payment processing.



The bridge creates or retrieves the appropriate order and sends the customer into the normal WooCommerce payment process.



\---



\# 13. Subscription Handling



WooCommerce Subscriptions remains authoritative.



When a subscription product is purchased through checkout, WooCommerce Subscriptions creates the pending subscription associated with the parent order before payment and activates it after the required payment succeeds.



Therefore the bridge must NOT:



```text

create payment intent

\\\&#x20;   ↓

manually activate subscription

```



Instead:



```text

Payment Intent

\\\&#x20;   ↓

WooCommerce Order / Checkout

\\\&#x20;   ↓

WooCommerce Subscriptions

\\\&#x20;   ↓

Pending subscription

\\\&#x20;   ↓

Payment

\\\&#x20;   ↓

Subscription lifecycle

```



The implementation must use the deployed subscription product configuration rather than assuming all products behave identically.



\---



\# 14. WooCommerce Subscription Hooks



The implementation should evaluate the appropriate lifecycle hooks, including:



```text

woocommerce\\\\\\\_checkout\\\\\\\_subscription\\\\\\\_created

subscriptions\\\\\\\_created\\\\\\\_for\\\\\\\_order

woocommerce\\\\\\\_subscription\\\\\\\_payment\\\\\\\_complete

woocommerce\\\\\\\_subscription\\\\\\\_renewal\\\\\\\_payment\\\\\\\_complete

```



The first two occur around creation of pending subscriptions before payment, while `woocommerce\\\\\\\_subscription\\\\\\\_payment\\\\\\\_complete` represents payment completion for a subscription.



The exact hook used for Buzzjuice fulfilment must be selected based on transaction type.



\---



\# 15. AffiliateWP



Existing AffiliateWP behavior is protected.



The new system must preserve:



\* attribution;

\* customer resolution;

\* referral creation;

\* commission calculation;

\* duplicate prevention;

\* processing marker;

\* retry behavior.



The existing implementation should be extracted/refactored only after its current behavior has been captured in tests.



No business-rule rewrite is authorized by this specification.



\---



\# 16. Jewel Affiliate



Jewel Affiliate must be processed as an independent fulfilment effect.



State:



```text

PENDING

PROCESSING

COMPLETED

FAILED

RECOVERY\\\\\\\_REQUIRED

```



The implementation must first establish the actual production invocation chain.



No assumption may be made about whether the current invocation is:



```text

WooCommerce webhook

```



or:



```text

WooCommerce order-complete hook

```



or another mechanism.



The verified invocation chain becomes part of the implementation contract.



Duplicate Jewel credits/rebates must be impossible.



\---



\# 17. Streams Fulfilment



Streams-specific fulfilment must also be independently idempotent.



Examples may include:



\* PRO/subscription activation;

\* wallet credit;

\* product entitlement;

\* transaction completion;

\* user role/permission changes.



Every financial side effect must have a durable first-success condition.



For example, this pattern is insufficient by itself:



```php

if ($transaction->processed) {

\\\&#x20;   return;

}



$wallet += $amount;

```



The implementation must ensure that duplicate execution cannot duplicate the financial effect.



\---



\# 18. Thank You Page



The existing WooCommerce Thank You page becomes an important customer-facing status surface.



The page must display a basic one-line status summary such as:



```text

Subscriptions: None | Affiliate: Completed | Jewel Affiliate/Credits/Rebates: Processing | Fulfilment: Processing | Redirect: Pending

```



The exact wording may be improved during UI implementation, but the concept is approved.



\---



\# 19. Dynamic Thank You Status



The status should reflect the durable transaction/fulfilment state.



Example initial state:



```text

Subscriptions: Processing |

Affiliate: Pending |

Jewel Affiliate/Credits/Rebates: Pending |

Fulfilment: Processing |

Redirect: Pending

```



After processing:



```text

Subscriptions: Active |

Affiliate: Completed |

Jewel Affiliate/Credits/Rebates: Completed |

Fulfilment: Completed |

Redirect: Ready

```



If a downstream process fails:



```text

Subscriptions: Active |

Affiliate: Completed |

Jewel Affiliate/Credits/Rebates: Processing |

Fulfilment: Processing |

Redirect: Pending

```



The customer should not see internal exception details, database identifiers or sensitive diagnostic information.



\---



\# 20. Status Refresh



The implementation should support lightweight status refresh while the Thank You page remains open.



Preferred mechanism:



```text

Thank You page

\\\&#x20;     ↓

signed/status-bound request

\\\&#x20;     ↓

payment intent status

\\\&#x20;     ↓

return minimal public status

\\\&#x20;     ↓

update one-line display

```



Polling must be:



\* bounded;

\* rate limited;

\* authenticated or signed;

\* restricted to the customer's order/intent;

\* free of sensitive information.



WebSocket infrastructure is not required for the first implementation.



\---



\# 21. Thank You Page Redirects



The redirects currently defined by:



```text

wow-pgb\\\\\\\_sync.php

```



are a \*\*required compatibility contract\*\*.



They must not be silently removed during migration.



The implementation must inventory every existing redirect condition and reproduce its intended behavior.



The final redirect must occur only after the necessary processing for that transaction type has reached its defined redirect-ready state.



\---



\# 22. Redirect State



The bridge shall maintain:



```text

redirect\\\\\\\_status

```



with:



```text

PENDING

READY

REDIRECTED

FAILED

NOT\\\\\\\_REQUIRED

```



The Thank You page may initially show:



```text

Redirect: Pending

```



and later:



```text

Redirect: Ready

```



The actual browser redirect can then occur.



\---



\# 23. Browser-Open Scenario



If the user remains on the WooCommerce Thank You page:



```text

Payment completed

\\\&#x20;      ↓

background fulfilment

\\\&#x20;      ↓

status changes

\\\&#x20;      ↓

redirect becomes READY

\\\&#x20;      ↓

browser redirects

```



This is specifically required to preserve the behavior previously handled by `wow-pgb\\\\\\\_sync.php`.



The implementation must therefore not assume that the customer has already left the Thank You page.



\---



\# 24. Browser-Closed Scenario



If the browser closes before fulfilment finishes:



```text

Payment completed

\\\&#x20;      ↓

durable fulfilment state remains

\\\&#x20;      ↓

background/retry processing continues

\\\&#x20;      ↓

fulfilment eventually completes

```



Reopening the order/appropriate Buzzjuice destination must not cause duplicate processing.



\---



\# 25. WooCommerce Thank You Integration



The implementation may use the WooCommerce Thank You lifecycle to render the customer-facing status. WooCommerce documents the `woocommerce\\\\\\\_thankyou` action as running after checkout completion, and the order-received template exposes the order to the confirmation view.



The bridge must not rely on the Thank You page itself as the durable processing mechanism.



The Thank You page is a \*\*view\*\*, not the transaction engine.



\---



\# 26. Processing Architecture



Post-payment processing should be represented as independent effects:



```text

Payment Completed

\\\&#x20;     │

\\\&#x20;     ├── Subscription Effect

\\\&#x20;     ├── AffiliateWP Effect

\\\&#x20;     ├── Jewel Affiliate Effect

\\\&#x20;     └── Streams Fulfilment Effect

```



Each effect must be independently retryable.



One failure must not cause successful effects to run again.



\---



\# 27. Effect Idempotency



Each effect must have a unique logical key.



Examples:



```text

subscription:{intent\\\\\\\_uuid}

affiliate:{intent\\\\\\\_uuid}

jewel:{intent\\\\\\\_uuid}

streams\\\\\\\_fulfilment:{intent\\\\\\\_uuid}

```



The exact implementation may use a dedicated effect table or equivalent durable mechanism.



This must be decided during final schema review.



\---



\# 28. Recovery



A transaction enters:



```text

RECOVERY\\\\\\\_REQUIRED

```



when:



\* payment succeeded;

\* but one or more downstream effects did not complete.



Recovery must be possible without requiring the customer to pay again.



Example:



```text

Order 1234 = PAID



AffiliateWP = COMPLETE

Subscription = COMPLETE

Jewel = FAILED

Streams = COMPLETE



Recovery:

\\\&#x20;   retry Jewel only

```



\---



\# 29. Reconciliation



A reconciliation process must identify:



\### Case A



```text

Payment intent exists

WC order missing

```



\### Case B



```text

WC order exists

Payment intent missing

```



\### Case C



```text

WC order paid

Streams fulfilment missing

```



\### Case D



```text

WC order paid

AffiliateWP missing

```



\### Case E



```text

WC order paid

Jewel Affiliate missing

```



\### Case F



```text

Subscription active

Streams entitlement missing

```



Each case must have a safe recovery path.



\---



\# 30. Logging



Logging remains extensive but toggleable.



Logs must contain:



```text

timestamp

intent\\\\\\\_uuid

correlation\\\\\\\_id

transaction\\\\\\\_kind

user/reference ID where appropriate

state

transition

operation

attempt

result

error code

```



Logs must NOT contain:



\* passwords;

\* access tokens;

\* refresh tokens;

\* webhook secrets;

\* payment credentials;

\* complete sensitive POST bodies;

\* unnecessary personal data.



\---



\# 31. Security Requirements



The new bridge must address the identified legacy weaknesses.



Mandatory:



\### TLS



Certificate verification must remain enabled.



No production:



```php

CURLOPT\\\\\\\_SSL\\\\\\\_VERIFYPEER => false

```



style bypasses.



\### Secrets



Webhook/API secrets must not be hard-coded into source code.



\### Replay protection



Signed handoffs must include:



```text

intent\\\\\\\_uuid

timestamp

nonce

signature

```



with expiry and replay prevention.



\### Identity binding



The payment intent must be bound to the intended user.



\### Authorization



A customer may only retrieve:



```text

their own

```



payment/fulfilment status.



\### SQL



Use prepared statements/parameterized queries through the approved DB helper.



\---



\# 32. MU Plugin Architecture



Primary entry point:



```text

wp-content/mu-plugins/bzj-payment-gateway.php

```



Supporting implementation:



```text

shared/payment-gateway/

```



Potential structure:



```text

shared/payment-gateway/

\\\&#x20;   bootstrap/

\\\&#x20;   core/

\\\&#x20;   currency/

\\\&#x20;   checkout/

\\\&#x20;   fulfilment/

\\\&#x20;   integrations/

\\\&#x20;   recovery/

\\\&#x20;   logging/

```



The exact structure must remain small.



Do not create classes solely to satisfy an architectural pattern.



\---



\# 33. No Filesystem-Driven Schema Rebuilds



The payment bridge must NOT scan source files and automatically recreate/alter production database tables whenever files change.



Schema changes must be:



```text

versioned

reviewed

explicit

idempotent

migration-controlled

```



\---



\# 34. Migration



The old bridge remains operational while the new system is introduced.



Migration must support:



```text

legacy transactions

new transactions

```



without confusing their states.



A transaction must be permanently identifiable as:



```text

legacy

```



or:



```text

new

```



during the transition.



\---



\# 35. Redirect Compatibility During Migration



Every existing `wow-pgb\\\\\\\_sync.php` redirect branch must be mapped.



Required mapping:



```text

Old condition

\\\&#x20;   ↓

Old destination

\\\&#x20;   ↓

Transaction type

\\\&#x20;   ↓

New readiness condition

\\\&#x20;   ↓

New redirect mechanism

```



No redirect may be removed because it appears unrelated to payment processing until its production behavior is verified.



\---



\# 36. Testing Requirements



Before production:



\## Unit



Test:



\* transaction state transitions;

\* idempotency;

\* currency conversion;

\* signature validation;

\* expiry;

\* duplicate requests;

\* duplicate callbacks;

\* redirect determination.



\## Integration



Test:



\* Streams → payment intent;

\* payment intent → WordPress;

\* WordPress → WooCommerce;

\* WooCommerce → payment gateway;

\* WooCommerce → Subscriptions;

\* WooCommerce → AffiliateWP;

\* WooCommerce → Jewel Affiliate;

\* WooCommerce → Streams fulfilment.



\## Failure tests



Simulate:



```text

cURL unavailable

DNS unavailable

browser timeout

browser refresh

duplicate handoff

duplicate webhook

WooCommerce order creation timeout

payment succeeds but response is lost

AffiliateWP failure

Jewel failure

Streams failure

database temporary failure

```



\## Currency tests



At minimum:



```text

ZAR → GHS

ZAR → EUR

ZAR → USD

GHS → EUR

GHS → ZAR

```



where configured currencies exist.



Verify that:



```text

displayed amount

checkout amount

order currency

payment gateway currency

```



remain consistent.



\---



\# 37. Acceptance Criteria



The implementation is not accepted unless:



1\. A transaction can survive a browser/network interruption.

2\. A duplicate handoff cannot create duplicate orders.

3\. A duplicate callback cannot duplicate financial effects.

4\. A paid order cannot become falsely marked failed because fulfilment failed.

5\. Failed downstream effects can be retried independently.

6\. Existing AffiliateWP behavior remains compatible.

7\. Existing redirects continue to work.

8\. The Thank You page displays current basic processing state.

9\. A still-open Thank You page can redirect after processing becomes ready.

10\. Closing the browser does not stop fulfilment.

11\. New tables reside in `koware\\\\\\\_iapd\\\\\\\_db`.

12\. `get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()` is used for those tables.

13\. ZAR source prices are not treated as GHS.

14\. WOOCS is used as the authoritative configured currency-rate system.

15\. The final WooCommerce order currency matches the actual checkout currency.

16\. Sensitive credentials/secrets are not logged or hard-coded.

17\. TLS verification remains enabled.

18\. Production rollback is possible.

19\. Existing in-flight legacy transactions remain recoverable.

20\. Automated tests cover the critical failure paths.



\---



\# 38. Implementation Sequence



Implementation shall occur in this order:



\### Phase 1 — Evidence Lock



\* verify production/repository versions;

\* inventory existing redirects;

\* inventory transaction types;

\* verify WOOCS configuration;

\* verify subscription products;

\* verify AffiliateWP;

\* verify Jewel Affiliate invocation;

\* verify existing database records.



\### Phase 2 — Schema



\* create `bzj\\\\\\\_payment\\\\\\\_intents`;

\* add required indexes;

\* establish schema versioning;

\* test `get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()`.



\### Phase 3 — Core Intent Engine



\* intent creation;

\* validation;

\* idempotency;

\* state transitions;

\* locking.



\### Phase 4 — Currency Engine



\* source currency handling;

\* WOOCS rate integration;

\* ZAR→GHS→target conversion;

\* rounding;

\* audit data.



\### Phase 5 — WordPress Handoff



\* signed handoff;

\* intent retrieval;

\* identity binding;

\* WooCommerce order creation;

\* payment URL.



\### Phase 6 — Thank You Status



\* status rendering;

\* bounded refresh;

\* effect status display;

\* redirect readiness.



\### Phase 7 — Fulfilment



\* Subscriptions;

\* AffiliateWP;

\* Jewel Affiliate;

\* Streams.



\### Phase 8 — Recovery



\* retries;

\* reconciliation;

\* failed-effect processing.



\### Phase 9 — Migration



\* controlled transaction-type migration;

\* monitoring;

\* rollback.



\### Phase 10 — Legacy Retirement



Only after sufficient production evidence.



\---



\# 39. Human Approval Gates



The following must be separately approved before production:



\### Gate A



Database schema.



\### Gate B



Currency conversion implementation.



\### Gate C



WooCommerce order creation implementation.



\### Gate D



Subscription lifecycle implementation.



\### Gate E



AffiliateWP compatibility.



\### Gate F



Jewel Affiliate implementation.



\### Gate G



Thank You status/redirect mechanism.



\### Gate H



Migration and rollback.



\---



\# 40. Agent Execution Contract



Once this specification is approved:



\### Codex



Primary implementation agent.



Responsibilities:



\* implement the approved specification;

\* write tests;

\* preserve existing functionality;

\* document deviations.



\### Jules



Independent implementation/CI/reliability review.



Responsibilities:



\* inspect implementation;

\* run tests;

\* identify CI failures;

\* identify edge cases;

\* verify migration safety.



\### Claude



Adversarial/security review.



Responsibilities:



\* attack transaction boundaries;

\* inspect idempotency;

\* inspect authentication/signing;

\* inspect currency manipulation;

\* inspect duplicate financial effects;

\* inspect redirect abuse;

\* inspect logging/secrets;

\* identify race conditions.



\### GitHub Actions



Deterministic validation.



Required checks should include, as appropriate:



```text

PHP syntax

PHPUnit

static analysis

security checks

schema/migration checks

```



No agent's approval substitutes for the automated tests.



\---



\# 41. Final Implementation Principle



The new Payment Gateway Bridge must be designed around this invariant:



```text

A payment may be interrupted at any point,

but it must never become untraceable.

```



And:



```text

A successful financial effect may be retried,

but it must never be applied twice.

```



Finally:



```text

The browser may disappear,

but the transaction must continue to exist.

```



These three invariants are the core reliability requirements of BZJ-PGB-002.



# **<<STOP BZJ-PGB-0345 Implementation Specification>>**

# 

# 

# 

# **BZJ-PGB-0400**

# **Database / Migration Specification**

# 

# 

# 

# **BZJ-PGB-0500**

# **IMPLEMENTATION PHASE**



# **<<START BZJ-PGB-0510 GitHub Issue>>**



\# BZJ-PGB-0050 — Payment Gateway Bridge Implementation



\## Execution Rules



This issue is the controlled implementation task for BZJ-PGB-003.



\### Branch



Implementation branch:



`Buzzjuie-Market/pgb/bzj-pgb-003`



\### Primary Implementer



GitHub Copilot is the primary implementation agent.



Copilot may modify the BZJ-PGB-003 implementation branch.



\### Independent Reviewers



Jules and Claude are independent reviewers.



They must review the implementation and report findings before BZJ-PGB-003 is considered complete.



They should \*\*not modify the primary implementation branch\*\* during the initial review.



\### Scope Lock



BZJ-PGB-003 is limited to:



1\. Evidence inventory of the existing payment bridge.

2\. IAPD database abstraction.

3\. Proposed `bzj\\\\\\\_payment\\\\\\\_intents` schema.

4\. Payment-intent foundation.

5\. Idempotency.

6\. State-machine foundation.

7\. Locking/concurrency protection.

8\. Expiry/retry foundation.

9\. Currency abstraction.

10\. Automated tests for the above.



\### Explicitly Out of Scope



Do not:



\* replace the existing payment bridge;

\* delete legacy payment files;

\* disable existing payment processing;

\* create WooCommerce orders;

\* change checkout/payment gateway behaviour;

\* manually activate subscriptions;

\* rewrite AffiliateWP logic;

\* rewrite Jewel Affiliate fulfilment;

\* change existing redirects without explicit review;

\* replace existing webhooks;

\* introduce new database credentials;

\* create filesystem-driven database schema rebuilds;

\* trust browser-supplied commercial values;

\* disable TLS verification;

\* log credentials, tokens, signatures, payment data, or other secrets.



\### Completion Gate



BZJ-PGB-003 is not complete merely because the code compiles.



It requires:



\* implementation evidence;

\* automated tests;

\* successful validation;

\* Jules review;

\* Claude review;

\* resolution of all Critical/High findings;

\* confirmation that existing payment processing remains intact.



Only after this gate should BZJ-PGB-004 begin.



\### Source of Truth



GitHub repository state, Issues, Pull Requests, commits, tests, and review comments are the authoritative implementation record.



Conversation is used for architecture and decision-making; GitHub records the resulting engineering state.



\## Objective



Implement the approved Buzzjuice Payment Gateway Bridge architecture defined by:



\* BZJ-PGB-001 — Architecture Decision \& Approval

\* BZJ-PGB-002 — Payment Gateway Bridge Implementation Specification v1



This is a \*\*staged implementation\*\*.



Do not replace the existing production payment bridge in one operation.



\---



\## Repository



`cupidblack/buzzjuice.net`



Primary systems:



\* Buzzjuice Streams / WoWonder

\* WordPress

\* WooCommerce

\* WooCommerce Subscriptions

\* AffiliateWP

\* Jewel Affiliate

\* WOOCS / WooCommerce currency switching



\---



\# Phase 1 — Evidence Lock



Before changing transaction-processing behavior, inspect and document the current implementation.



Verify:



1\. Current `wow-pgb\\\\\\\_init.php`

2\. Current `wow-pgb\\\\\\\_sync.php`

3\. Current `wow-pgb\\\\\\\_webhook.php`

4\. Current AffiliateWP integration

5\. Current Jewel Affiliate invocation

6\. Current WooCommerce order metadata

7\. Current subscription handling

8\. Current `Wo\\\\\\\_Payment\\\\\\\_Transactions` usage

9\. Existing redirects

10\. Existing currency conversion

11\. Existing WOOCS integration

12\. Existing webhook behavior

13\. Existing transaction types

14\. Existing logging



Record:



\* file;

\* function/class;

\* hook;

\* database table;

\* metadata;

\* transaction state;

\* side effect;

\* redirect;

\* external dependency.



Do not assume that repository state and production state are identical.



\---



\# Phase 2 — IAPD Payment Intent Schema



Create the new durable payment-intent storage in:



`koware\\\\\\\_iapd\\\\\\\_db`



Use:



`get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()`



from:



`buzzjuice.net/shared/db\\\\\\\_helpers/`



Do not create the new table in the WordPress database or WoWonder database.



Proposed table:



`bzj\\\\\\\_payment\\\\\\\_intents`



Do not execute a production schema migration until the proposed SQL/schema has been reviewed.



The schema must support:



\* UUID;

\* idempotency;

\* correlation;

\* Streams user;

\* WordPress user;

\* transaction kind;

\* product/plan;

\* source currency;

\* source amount;

\* WooCommerce base currency;

\* base amount;

\* checkout currency;

\* checkout amount;

\* exchange rate;

\* exchange-rate source;

\* WooCommerce order ID;

\* payment state;

\* fulfilment state;

\* redirect state;

\* subscription effect;

\* AffiliateWP effect;

\* Jewel Affiliate effect;

\* Streams fulfilment effect;

\* retry state;

\* timestamps;

\* expiry.



\---



\# Phase 3 — Core Intent Engine



Implement only the durable transaction foundation.



Required operations:



\* create intent;

\* retrieve intent;

\* validate intent;

\* idempotency lookup;

\* state transition;

\* locking;

\* expiry;

\* failure recording;

\* retry metadata.



Do not yet replace the existing payment initiation path.



\---



\# Phase 4 — Currency Engine



Implement and test the currency abstraction.



Required behaviour:



```text

Streams source currency

\\\&#x20;       ↓

WooCommerce base currency

\\\&#x20;       ↓

active WOOCS checkout currency

```



Example:



```text

Streams:

ZAR 500



WooCommerce base:

GHS



Customer checkout:

EUR

```



The implementation must not treat ZAR 500 as GHS 500.



Preserve:



\* original ZAR amount;

\* source currency;

\* base amount;

\* checkout amount;

\* checkout currency;

\* effective rate;

\* rate source;

\* conversion timestamp.



Use the deployed WOOCS configuration/rate source.



Do not introduce a second exchange-rate provider without explicit approval.



\---



\# Phase 5 — Tests



Before proceeding to WordPress/WooCommerce order creation, add tests for:



\### Idempotency



\* duplicate intent;

\* duplicate request;

\* duplicate callback;

\* duplicate fulfilment.



\### State



\* valid transitions;

\* invalid transitions;

\* expired intent;

\* failed intent;

\* retry.



\### Currency



At minimum:



\* ZAR → GHS;

\* ZAR → EUR;

\* GHS → EUR;

\* GHS → ZAR;



where those currencies are configured.



\### Database



Verify:



\* `koware\\\\\\\_iapd\\\\\\\_db`;

\* `get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()`;

\* unique intent;

\* unique idempotency key;

\* concurrent access behavior.



\---



\# Critical Rules



\## Do not:



\* delete the old payment bridge;

\* disable existing payment processing;

\* alter production redirects without mapping them;

\* manually activate subscriptions;

\* rewrite AffiliateWP business logic;

\* assume Jewel Affiliate invocation;

\* trust browser-supplied amounts;

\* create new database credentials;

\* disable TLS verification;

\* hard-code secrets;

\* log sensitive request data;

\* automatically rebuild tables based on filesystem state.



\---



\# Required Documentation



Add/update repository documentation describing:



1\. BZJ-PGB-001 architecture decisions;

2\. BZJ-PGB-002 implementation specification;

3\. database ownership;

4\. payment-intent lifecycle;

5\. currency model;

6\. migration strategy;

7\. known unknowns;

8\. implementation status.



\---



\# Completion Criteria



Phase 1–5 is complete only when:



\* evidence inventory exists;

\* schema is proposed and reviewed;

\* no production payment flow has been broken;

\* payment-intent foundation exists;

\* IAPD connection uses `get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()`;

\* currency conversion tests exist;

\* idempotency tests exist;

\* state-machine tests exist;

\* static analysis/syntax/tests pass;

\* all deviations from BZJ-PGB-002 are documented.



\## Next Gate



After this issue is complete, stop.



Do NOT continue automatically into WooCommerce order creation.



The next review will determine whether the implementation is ready for:



\*\*BZJ-PGB-004 — WordPress/WooCommerce Handoff \& Order Creation.\*\*



# **<<STOP BZJ-PGB-0510 GitHub Issue>>**

# 

# **<<START BZJ-PGB-0520 Implementation Task Packets>>**



\# Codex Task — BZJ-PGB-0052



You are the primary implementation agent for the Buzzjuice Payment Gateway Bridge redesign.



Implement \*\*only the scope defined by BZJ-PGB-0040\*\*.



Read the repository's `AGENTS.md` and relevant project documentation first.



Also inspect the existing payment bridge before modifying anything.



\## Authoritative design documents



Use these as the architecture contract:



\* BZJ-PGB-001 — Architecture Decision \& Approval

\* BZJ-PGB-002 — Payment Gateway Bridge Implementation Specification v1

\* BZJ-PGB-003 — this issue



If repository code conflicts with the specification:



1\. do not silently choose one;

2\. document the conflict;

3\. identify the exact file/function;

4\. explain the consequence;

5\. stop at the appropriate gate if the conflict changes architecture.



\## Current task



Implement:



1\. evidence inventory;

2\. payment-intent schema proposal;

3\. IAPD database abstraction;

4\. payment-intent core;

5\. state machine;

6\. idempotency foundation;

7\. currency abstraction;

8\. tests.



\## Database requirement



All new payment-intent storage belongs in:



`koware\\\\\\\_iapd\\\\\\\_db`



Use:



`get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()`



from:



`buzzjuice.net/shared/db\\\\\\\_helpers/`



Do not create another database connection mechanism.



Do not put the new payment-intent table in the WordPress database.



\## Currency requirement



The Streams product currency may differ from WooCommerce's GHS base currency.



Example:



```text

Streams product = ZAR

WooCommerce base = GHS

Customer checkout = EUR

```



The implementation must preserve the original ZAR price and correctly derive the checkout currency amount using the deployed WOOCS configuration.



Do not interpret a ZAR amount as GHS.



Do not introduce an unrelated exchange-rate provider.



\## Important



Do not yet:



\* replace `wow-pgb\\\\\\\_init.php`;

\* replace `wow-pgb\\\\\\\_sync.php`;

\* replace existing webhooks;

\* change production checkout;

\* change redirects;

\* alter AffiliateWP business rules;

\* alter Jewel Affiliate production behavior;

\* activate subscriptions manually.



This is foundation work only.



\## Required output



Produce:



\### 1. Code



Only code required for this phase.



\### 2. Tests



Include tests for:



\* idempotency;

\* state transitions;

\* expiry;

\* duplicate processing;

\* currency conversion;

\* database connection;

\* invalid currency;

\* rounding.



\### 3. Evidence report



Create a concise report identifying:



\* current payment bridge files;

\* current transaction tables;

\* current hooks;

\* current redirects;

\* current currency logic;

\* AffiliateWP path;

\* Jewel Affiliate path;

\* subscription path;

\* unresolved unknowns.



\### 4. Deviation report



If implementation differs from BZJ-PGB-0030, document:



```text

Requirement

Observed repository condition

Proposed deviation

Reason

Risk

Required approval

```



\### 5. Validation



Run all applicable:



\* PHP syntax checks;

\* PHPUnit;

\* static analysis;

\* existing repository tests;

\* security checks.



Do not claim tests passed unless they actually ran.



\## Stop condition



After completing BZJ-PGB-0052, stop.



Do not begin WooCommerce order creation.



The implementation will be independently reviewed by Jules and Claude before the next phase.





# **<<STOP BZJ-PGB-0520 Implementation Task Packets>>**



Attached is the approved specification. Independently inspect the implementation and determine whether it satisfies the specification.



# **<<START BZJ-PGB-0530 Review-1 Branch>>**



\# Jules — Independent Review of BZJ-PGB-003



Review the implementation of BZJ-PGB-003 as an independent engineering reviewer.



Do not assume that Codex's implementation is correct.



Compare:



1\. BZJ-PGB-001;

2\. BZJ-PGB-002;

3\. BZJ-PGB-003;

4\. actual repository code;

5\. actual tests.



\## Focus areas



\### Database



Verify:



\* correct database;

\* `koware\\\\\\\_iapd\\\\\\\_db`;

\* `get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn()`;

\* indexes;

\* uniqueness;

\* concurrency;

\* transaction safety.



\### Idempotency



Attempt to identify paths where:



\* duplicate intents;

\* duplicate orders;

\* duplicate callbacks;

\* duplicate effects



could occur.



\### State machine



Identify:



\* illegal transitions;

\* race conditions;

\* missing states;

\* states that cannot recover.



\### Currency



Attack the implementation using:



```text

ZAR → GHS

ZAR → EUR

GHS → EUR

```



Check:



\* source/base confusion;

\* rounding;

\* stale rate;

\* missing rate;

\* incorrect currency assumptions.



\### Security



Check:



\* authorization;

\* replay;

\* signatures;

\* secrets;

\* logging;

\* SQL;

\* user-controlled amounts;

\* race conditions.



\### Regression



Verify that the implementation does not unintentionally alter:



\* existing payment bridge;

\* AffiliateWP;

\* subscriptions;

\* Jewel Affiliate;

\* redirects.



\## Output



Produce:



\### PASS



Correct implementation areas.



\### FINDINGS



Each finding must include:



\* severity;

\* file;

\* function;

\* evidence;

\* impact;

\* recommended correction.



\### BLOCKERS



Anything that prevents moving to BZJ-PGB-004.



\### TEST GAPS



Tests that should exist but do not.



Do not modify production code unless explicitly asked to perform a fix.



# **<<STOP BZJ-PGB-0530 Review-1 Branch>>**

# 

# **<<START BZJ-PGB-0540 Review-2 Branch>>**



\# Claude — Adversarial Security \& Architecture Review



Perform an adversarial review of the BZJ-PGB-003 implementation.



The objective is to find ways the implementation could:



\* lose a transaction;

\* create a duplicate order;

\* duplicate a financial effect;

\* miscalculate currency;

\* bypass authorization;

\* replay a payment intent;

\* corrupt fulfilment state;

\* break an existing redirect;

\* break AffiliateWP;

\* break Jewel Affiliate;

\* incorrectly activate a subscription;

\* expose sensitive information.



Treat the following as invariants:



```text

A payment must never become untraceable.



A successful financial effect must never be applied twice.



A browser disappearing must not stop the transaction.



A downstream failure must not make a successful payment appear unpaid.



A client must never control the final financial amount.



A ZAR amount must never silently be interpreted as GHS.



New payment-intent data belongs in koware\\\\\\\_iapd\\\\\\\_db.



get\\\\\\\_iapd\\\\\\\_db\\\\\\\_conn() is the approved database connection.

```



\## Specifically attack



\### Concurrency



Attempt:



```text

request A + request B

```



at exactly the same time.



Determine whether two WooCommerce orders or two financial effects can be created.



\### Replay



Replay an old signed payment-intent handoff.



Determine whether it can:



\* create another order;

\* access another user's status;

\* alter transaction state.



\### Currency manipulation



Attempt to modify:



\* source amount;

\* source currency;

\* checkout currency;

\* exchange rate;

\* product price.



Determine which values are server authoritative.



\### Fulfilment



Force:



```text

AffiliateWP failure

Jewel failure

Streams failure

Subscription failure

```



after payment succeeds.



Determine whether recovery is possible without charging the customer again.



\### Existing bridge



Check whether the new foundation accidentally changes the old bridge before migration.



\## Output



Produce:



1\. Executive assessment;

2\. Critical findings;

3\. High findings;

4\. Medium findings;

5\. Low findings;

6\. Race-condition analysis;

7\. Currency analysis;

8\. Security analysis;

9\. Migration risks;

10\. Required changes before BZJ-PGB-004.



Do not redesign unrelated parts of Buzzjuice.



Do not make political, business, or product decisions.



Focus strictly on engineering correctness and security.



# **<<STOP BZJ-PGB-0540 Review-2 Branch>>**

# 

# 

# 

# **BZJ-PGB-0600**

# **Build**

# 

# 

# 

# **BZJ-PGB-0700**

# **Automated Agent Testing**

# 

# 

# 

# **BZJ-PGB-0800**

# **Challenge Before Production**

# 

# 

# 

# **BZJ-PGB-0900**

# **Staging / Migration**

# 

# 

# 

# **BZJ-PGB-1000**

# **Production Deployment**

# 

# 

# 

# **BZJ-PGB-1100**

# **Monitoring / Reconciliation**







