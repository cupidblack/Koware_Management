BUZZJUICE STREAMS FOLLOW FIX - REFACTORED PACKAGE
=================================================

DATE
----
2026-09-21

REFERENCE WORKING USER
----------------------
Wo_Users.user_id = 66

PRIMARY FINDING
---------------
The previous fix introduced a new failure mode:

    endpoint_rejected
    reason = invalid_main_session

The normal WoWonder requests.php flow already authenticates this endpoint
through $wo['loggedin']. The follow endpoint must therefore NOT impose a second
Wo_CheckMainSession($hash_id) requirement unless the browser explicitly sends
the matching hash_id.

The refactored endpoint in this package removes that extra requirement.

SECONDARY FINDINGS
------------------
The current application code also has these problems:

1. Wo_IsFollowing() checks COUNT(...) == 1 rather than existence.
   Duplicate active rows can therefore report false.

2. Wo_RegisterFollow() inserts a new row but does not promote an existing
   active=0 row.

3. Wo_RegisterFollow() still evaluates follow_privacy and can therefore reject
   a follow instead of creating the required immediate active relationship.

4. The browser-side Wo_RegisterFollow() renders a state before the server has
   responded. This allows Requested/Follow to appear even when the database
   ultimately contains active=1.

5. follow_user.php previously returned a generic success response instead of
   verifying the final database state.

6. Wo_DeleteFollow() relied on the old state functions and could fail to remove
   inconsistent legacy rows.

NEW STATE MACHINE
-----------------
Follow:
    browser
      -> follow_user endpoint
      -> Wo_RegisterFollow()
      -> promote existing pending row OR insert active=1
      -> remove duplicate relationship rows
      -> verify active relationship
      -> endpoint returns state=following
      -> browser renders Following

Unfollow:
    browser
      -> follow_user endpoint
      -> Wo_DeleteFollow()
      -> delete ALL matching relationship rows
      -> verify no row remains
      -> endpoint returns state=follow
      -> browser renders Follow

There is no Requested state in the Buzzjuice Streams UI.

FILES
-----
functions_one_follow_replacements.php
    Exact replacement implementations for the four follow functions.

follow_user.php
    Complete endpoint replacement.

follow_users.php
    Complete bulk-follow endpoint replacement.

follow.phtml
unfollow.phtml
requested.phtml
add-friend.phtml
unfriend.phtml
    Complete button templates. They no longer pass confirm_followers.

follow_js_replacement.php.txt
    Complete replacement for only Wo_RegisterFollow() inside the large
    content.phtml file.

bzj-wo-account-diagnostics.php
    Non-mutating account comparison helper using user 66 as reference.

streams_follow_diagnostics.sql
    Read-only database diagnostics.

ww-sso-bridge_diagnostic_patch.txt
    Exact diagnostic-only SSO integration instructions.

DEPLOYMENT ORDER
----------------
1. BACKUP FIRST.

2. Back up:
   streams/assets/includes/functions_one.php
   streams/xhr/follow_user.php
   streams/xhr/follow_users.php
   streams/themes/sunshine/layout/extra_js/content.phtml
   all five button templates
   streams/ww-sso-bridge.php

3. functions_one.php:
   Replace ONLY the active implementations of:
      Wo_IsFollowing()
      Wo_RegisterFollow()
      Wo_IsFollowRequested()
      Wo_DeleteFollow()

   Do not replace the entire 569 KB file.

   Ensure there is only ONE executable definition of each function.
   The old versions are partly commented in the current file, but verify
   there is no second executable definition introduced by another include.

4. Replace:
   streams/xhr/follow_user.php

5. Replace:
   streams/xhr/follow_users.php

6. Replace all five button templates.

7. In:
   streams/themes/sunshine/layout/extra_js/content.phtml

   replace ONLY the existing Wo_RegisterFollow() function with the complete
   function in follow_js_replacement.php.txt.

8. Install:
   streams/bzj-wo-account-diagnostics.php

9. Add the diagnostic-only SSO calls described in:
   ww-sso-bridge_diagnostic_patch.txt

10. Run:
    streams_follow_diagnostics.sql

    Do NOT run the optional repair examples until the output is reviewed.

IMPORTANT DATABASE NOTE
----------------------
Your supplied Wo_Followers schema is:

    id
    following_id
    follower_id
    is_typing
    active
    notify
    time

The diagnostic package therefore uses time, not created_at.

Your currently supplied rows:

    97, 66, 38407, 0, 1, 0, 0
    98, 66, 1,     0, 1, 0, 0

appear to be pending relationships because active=0.

The new follow function promotes an existing matching pending row to active=1
rather than creating another row.

TEST PROCEDURE
--------------
A. Clear browser cache or use an incognito/private window.

B. Use a currently affected account A.

C. Open another user's profile B.

D. Tap Follow once.

EXPECTED DATABASE:
    SELECT *
    FROM Wo_Followers
    WHERE following_id = B
      AND follower_id = A;

Expected:
    exactly one row
    active = 1

EXPECTED UI:
    Following

E. Reload.

Expected:
    Following

F. Tap Following.

Expected:
    matching relationship rows = 0

Expected UI:
    Follow

G. Reload.

Expected:
    Follow

LOGS
-----
Follow request log:
    buzzjuice.net/data.logs/follow/follow-YYYY-MM-DD.log

Account diagnostic log:
    buzzjuice.net/data.logs/follow/account-diagnostics-YYYY-MM-DD.log

IMPORTANT INTERPRETATION
------------------------
If the endpoint log shows:

    state=following
    database active=1
    browser still Follow

the remaining issue is front-end replacement/cache/template loading.

If it shows:

    invalid_main_session

the old endpoint is still being served or another duplicate endpoint/code path
is active.

If it shows:

    function_returned=false
    database active=0

inspect:
    - duplicate Wo_RegisterFollow() definitions
    - Wo_IsBlocked()
    - MySQL errors
    - another request writing active=0
    - a second application copy of follow_user.php

If it shows:

    database active=1
    endpoint state=following
    browser Following
    reload -> Follow

inspect:
    - profile button generation
    - Wo_IsFollowing()
    - user cache
    - duplicate relationship rows
    - whether the edited functions_one.php is actually the loaded file

SSO / AUTO-REGISTER
-------------------
The current SSO bridge already calls:

    Wo_RegisterUser([
        'username' => $final_username,
        'email' => $wp_email,
        'password' => bin2hex(random_bytes(16)),
        'active' => 1
    ]);

and then verifies the database row.

Therefore this package does NOT rewrite SSO account creation. Doing so would
increase risk without evidence that registration is the primary cause.

The diagnostic hook records affected accounts against working account 66 so
registration differences can be proven rather than guessed.

ACCOUNT DIFFERENCES THAT MATTER
--------------------------------
The diagnostic focuses on:
    active
    start_up
    startup_follow
    confirm_followers
    follow_privacy
    type
    verified
    is_pro
    wp_user_id
    username
    email

Differences in confirm_followers/follow_privacy are NOT automatically treated
as corruption because Buzzjuice's new follow state machine intentionally does
not use those settings to create Requested relationships.

Do not bulk-normalize these fields just because they differ from user 66.

SECURITY / ARCHITECTURE
-----------------------
The endpoint relies on WoWonder's existing authenticated request gate rather
than adding a second hash gate that the current front-end does not provide.

The browser is not trusted to determine relationship state.

The database is the authoritative state for the Streams projection.

WP/BuddyBoss remains the platform source of truth. Projection failures are
logged and do not roll back a successfully committed Streams relationship.

FINAL CHECK
-----------
Search the entire Streams codebase for:

    function Wo_RegisterFollow
    function Wo_DeleteFollow
    function Wo_IsFollowing
    function Wo_IsFollowRequested
    invalid_main_session

There should be one executable implementation of each follow function, and
the invalid_main_session response should not be produced by follow_user.php.

Do not deploy a second follow endpoint under another filename.
