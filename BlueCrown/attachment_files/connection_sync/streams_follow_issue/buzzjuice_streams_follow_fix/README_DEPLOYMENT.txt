BUZZJUICE STREAMS FOLLOW/SESSION FIX
=====================================

Files:
1. init.php
   Replace streams/assets/init.php.
   Important: this removes destructive automatic session recovery.

2. functions_two_session_replacements.php
   Replace only these four existing definitions in
   streams/assets/includes/functions_two.php:
   - Wo_CreateSession()
   - Wo_CheckSession()
   - Wo_CreateMainSession()
   - Wo_CheckMainSession()

3. Wo_GetMedia.php
   Replace the existing Wo_GetMedia() in
   streams/assets/includes/functions_one.php.

4. timeline_avatar_replacement.phtml
   Replace the avatar <img> block in
   streams/themes/sunshine/layout/timeline/content.phtml.
   Do not use the raw avatar_full value as data-image.

5. follow_user.php
   Replace streams/xhr/follow_user.php.
   This keeps Wo_CheckMainSession() strict and returns explicit JSON errors.
   It also returns follow_state=following after a successful immediate follow.

6. Wo_RegisterFollow.js
   Replace the current Wo_RegisterFollow() JavaScript function in
   streams/themes/sunshine/layout/extra_js/content.phtml.
   The AJAX request now explicitly sends:
       hash_id: $('.main_session').first().val()
   The button is NOT changed optimistically.

IMPORTANT:
- Do not modify social/core.php for this Streams issue.
- Do not weaken Wo_CheckMainSession() to accept a missing hash.
- Do not call session_destroy(), session_unset(), or session_regenerate_id()
  as automatic recovery in init.php.
- Wo_RegisterFollow() must continue inserting active=1 for the immediate
  follow policy.
- All new diagnostic logging goes to:
  /buzzjuice.net/data/logs/buzzjuice-streams.log

SESSION COOKIE:
The repository evidence shows that a profile refresh changing the
BUZZSTREAMSESSID is a separate issue from hash generation. After deploying
the changes, inspect Network -> response headers for Set-Cookie on:
  /streams
  /streams/<profile>
  /streams/requests.php?f=follow_user

The same host/path must consistently use BUZZSTREAMSESSID. If another
application or endpoint overwrites the cookie, that must be corrected at
the source rather than worked around by making the hash check permissive.

VALIDATION:
Expected after fix:
  page load:     session A / main_hash X
  page refresh:  session A / main_hash X
  profile load:  session A / main_hash X
  profile reload:session A / main_hash X

Follow:
  click Follow
  -> AJAX includes hash_id
  -> Wo_CheckMainSession(hash_id) == true
  -> Wo_RegisterFollow()
  -> followers.active = 1
  -> JSON follow_state = following
  -> page reload shows Following

Failure:
  invalid/missing hash
  -> JSON 403
  -> UI remains in its previous state
  -> no false "Requested" state

Do not perform a broad SQL repair until the live followers table and
current relationship policy have been verified.
