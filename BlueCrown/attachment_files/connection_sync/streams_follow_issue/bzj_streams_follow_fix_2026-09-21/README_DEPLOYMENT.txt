BUZZJUICE STREAMS FOLLOW FIX
Date: 2026-09-21

Purpose
-------
Make Buzzjuice Streams follow state deterministic:

    Follow -> active=1 -> Following
    Following -> delete row -> Follow

Requested is removed from the user-facing follow flow.

Root causes found
-----------------
1. The existing client-side Wo_RegisterFollow() renders Requested before the
   AJAX request completes when confirm_followers is enabled.
2. The endpoint did not return a verified final database state.
3. Wo_IsFollowing() used COUNT == 1. Duplicate rows therefore made an active
   relationship look like "not following".
4. Existing pending rows were not consistently promoted.
5. Wo_DeleteFollow() depended on the state helper and could therefore fail in
   the presence of inconsistent/duplicate legacy rows.
6. Cache invalidation was not sufficient to make the client authoritative.
7. SSO registration is not the direct cause of active=0. The current bridge
   already sends active=>1 and verifies the resulting row, but diagnostics are
   added so account inconsistencies can be compared against user 66.

Files in this package
---------------------
functions_one_follow_replacements.php
    Complete replacement implementations for the five follow-related
    functions/helpers in the large functions_one.php file.

follow_user.php
    Full drop-in replacement.

follow_users.php
    Full drop-in replacement.

follow.phtml
unfollow.phtml
requested.phtml
add-friend.phtml
unfriend.phtml
    Full drop-in button templates.

content_phtml_follow_replacement.php.txt
    Complete replacement for the existing Wo_RegisterFollow JavaScript
    section only. Do not replace the entire 2,293-line content.phtml file.

bzj-wo-account-diagnostics.php
    New non-mutating SSO/account diagnostic helper.

ww-sso-bridge_diagnostic_patch.txt
    Exact integration instructions for the new diagnostic helper.

streams_follow_diagnostics.sql
    Read-only account comparison and relationship diagnostics, plus clearly
    commented optional repair SQL.

Important schema correction
---------------------------
The supplied Wo_Followers structure contains:

    id, following_id, follower_id, is_typing, active, notify, time

It does NOT establish that a created_at column exists. The diagnostic SQL
therefore uses time and never assumes created_at.

SSO conclusion
--------------
The current ww-sso-bridge.php already calls:

    Wo_RegisterUser([
        'username' => $final_username,
        'email'    => $wp_email,
        'password' => ...,
        'active'   => 1
    ]);

and subsequently verifies Wo_Users by email. Therefore SSO should be
diagnosed, but it should not be changed as the primary fix for this follow
bug.

Deployment order
----------------
1. Back up:
   - streams/assets/includes/functions_one.php
   - streams/xhr/follow_user.php
   - streams/xhr/follow_users.php
   - the five button templates
   - streams/themes/sunshine/layout/extra_js/content.phtml
   - streams/ww-sso-bridge.php

2. Apply the functions_one follow replacements.

3. Replace follow_user.php and follow_users.php.

4. Replace all five button templates.

5. Replace only the old Wo_RegisterFollow JavaScript function in
   content.phtml with the supplied complete replacement.

6. Add bzj-wo-account-diagnostics.php and the single require_once/call changes
   described in ww-sso-bridge_diagnostic_patch.txt.

7. Run streams_follow_diagnostics.sql read-only.

8. Review pending rows and duplicates.

9. Only after application code is live, promote confirmed legacy pending rows.

10. Test:
    A. account 66 -> another user:
       Follow => Following immediately
       DB => exactly one row, active=1

    B. same account:
       Following => Follow immediately
       DB => no row for that pair

    C. reload page:
       state remains correct

    D. repeat with accounts that previously failed.

Logging
-------
Follow diagnostics:
    buzzjuice.net/data.logs/follow/follow-YYYY-MM-DD.log

SSO/account diagnostics:
    buzzjuice.net/data.logs/wo-account-diagnostics/account-YYYY-MM-DD.log

No passwords, SSO tokens, cookies or email addresses are intentionally logged.

One important architecture choice
---------------------------------
The browser never decides whether a relationship is Requested/Following.
The server writes the database first, reads it back, returns the verified
state, and only then does the browser render the button.

This makes the Wo_Followers row the authoritative state for the WoWonder UI
while preserving the existing Streams -> BuddyBoss synchronization on
unfollow.
