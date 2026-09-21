-- BUZZJUICE STREAMS FOLLOW DIAGNOSTICS
-- Reference/working Wo_Users.user_id: 66
--
-- READ-ONLY SECTION FIRST.
-- Run these in phpMyAdmin against the Streams database.
--
-- NOTE:
-- Wo_Followers in the supplied schema uses `time`, not `created_at`.
-- Do not use a diagnostic query that assumes a created_at column.

-- ================================================================
-- 1. Exact working account snapshot
-- ================================================================

SELECT
    user_id,
    username,
    active,
    start_up,
    startup_follow,
    confirm_followers,
    follow_privacy,
    type,
    admin,
    verified,
    is_pro,
    joined,
    lastseen,
    wp_user_id
FROM Wo_Users
WHERE user_id = 66;


-- ================================================================
-- 2. Compare every account with account 66.
-- Only the fields relevant to the registration/follow path are shown.
-- ================================================================

SELECT
    u.user_id,
    u.username,
    u.active,
    u.start_up,
    u.startup_follow,
    u.confirm_followers,
    u.follow_privacy,
    u.type,
    u.admin,
    u.verified,
    u.is_pro,
    u.joined,
    u.lastseen,
    u.wp_user_id,

    CASE WHEN u.active = r.active THEN 1 ELSE 0 END AS same_active,
    CASE WHEN u.start_up = r.start_up THEN 1 ELSE 0 END AS same_start_up,
    CASE WHEN u.startup_follow = r.startup_follow THEN 1 ELSE 0 END AS same_startup_follow,
    CASE WHEN u.confirm_followers = r.confirm_followers THEN 1 ELSE 0 END AS same_confirm_followers,
    CASE WHEN u.follow_privacy = r.follow_privacy THEN 1 ELSE 0 END AS same_follow_privacy,
    CASE WHEN u.type = r.type THEN 1 ELSE 0 END AS same_type,
    CASE WHEN u.verified = r.verified THEN 1 ELSE 0 END AS same_verified,
    CASE WHEN u.is_pro = r.is_pro THEN 1 ELSE 0 END AS same_is_pro

FROM Wo_Users AS u
CROSS JOIN (
    SELECT
        active,
        start_up,
        startup_follow,
        confirm_followers,
        follow_privacy,
        type,
        verified,
        is_pro
    FROM Wo_Users
    WHERE user_id = 66
) AS r
ORDER BY u.user_id;


-- ================================================================
-- 3. Flag suspicious/missing account values.
-- ================================================================

SELECT
    user_id,
    username,
    active,
    start_up,
    startup_follow,
    confirm_followers,
    follow_privacy,
    type,
    admin,
    verified,
    is_pro,
    wp_user_id
FROM Wo_Users
WHERE
    active IS NULL
    OR active NOT IN (0,1)
    OR start_up IS NULL
    OR startup_follow IS NULL
    OR confirm_followers IS NULL
    OR follow_privacy IS NULL
    OR username IS NULL
    OR username = ''
    OR wp_user_id IS NULL
ORDER BY user_id;


-- ================================================================
-- 4. Show every follow relationship, including pending rows.
-- ================================================================

SELECT
    f.id,
    f.following_id,
    f.follower_id,
    f.active,
    f.is_typing,
    f.notify,
    f.time,
    fu.username AS follower_username,
    tu.username AS following_username
FROM Wo_Followers AS f
LEFT JOIN Wo_Users AS fu ON fu.user_id = f.follower_id
LEFT JOIN Wo_Users AS tu ON tu.user_id = f.following_id
ORDER BY f.id DESC;


-- ================================================================
-- 5. Find duplicate relationships.
-- ================================================================

SELECT
    following_id,
    follower_id,
    COUNT(*) AS relationship_count,
    GROUP_CONCAT(
        CONCAT(id, ':active=',active)
        ORDER BY id DESC
        SEPARATOR ', '
    ) AS rows_found
FROM Wo_Followers
GROUP BY following_id, follower_id
HAVING COUNT(*) > 1
ORDER BY relationship_count DESC;


-- ================================================================
-- 6. Find all pending rows.
-- Under Buzzjuice immediate-follow mode, these are legacy/suspicious.
-- ================================================================

SELECT
    f.id,
    f.following_id,
    f.follower_id,
    f.active,
    f.is_typing,
    f.notify,
    f.time,
    fu.username AS follower_username,
    tu.username AS following_username
FROM Wo_Followers AS f
LEFT JOIN Wo_Users AS fu ON fu.user_id = f.follower_id
LEFT JOIN Wo_Users AS tu ON tu.user_id = f.following_id
WHERE f.active = 0
ORDER BY f.id DESC;


-- ================================================================
-- 7. Working account's outgoing relationships.
-- ================================================================

SELECT
    f.id,
    f.following_id,
    f.follower_id,
    f.active,
    f.is_typing,
    f.notify,
    f.time,
    u.username,
    u.active AS target_active,
    u.confirm_followers,
    u.follow_privacy
FROM Wo_Followers AS f
LEFT JOIN Wo_Users AS u ON u.user_id = f.following_id
WHERE f.follower_id = 66
ORDER BY f.id DESC;


-- ================================================================
-- 8. Working account's incoming relationships.
-- ================================================================

SELECT
    f.id,
    f.following_id,
    f.follower_id,
    f.active,
    f.is_typing,
    f.notify,
    f.time,
    u.username AS follower_username,
    u.active AS follower_active
FROM Wo_Followers AS f
LEFT JOIN Wo_Users AS u ON u.user_id = f.follower_id
WHERE f.following_id = 66
ORDER BY f.id DESC;


-- ================================================================
-- 9. Check the exact two relationships mentioned in the incident.
-- ================================================================

SELECT *
FROM Wo_Followers
WHERE id IN (97,98)
ORDER BY id;


-- ================================================================
-- OPTIONAL REPAIR -- DO NOT RUN UNTIL THE READ-ONLY RESULTS HAVE
-- BEEN REVIEWED.
--
-- Since Buzzjuice is intentionally immediate-follow, these legacy
-- pending rows can be promoted to active.
--
-- First, review:
-- SELECT * FROM Wo_Followers WHERE active = 0;
--
-- Then, if the policy is confirmed:
--
-- UPDATE Wo_Followers
-- SET active = 1
-- WHERE active = 0;
--
-- For the incident specifically:
--
-- UPDATE Wo_Followers
-- SET active = 1
-- WHERE id IN (97,98)
--   AND active = 0;
--
-- The application code MUST be deployed first, otherwise new pending
-- rows could be created again.
-- ================================================================


-- ================================================================
-- 10. Optional index after duplicate review.
-- Do NOT create a unique constraint until existing duplicates are
-- understood and cleaned.
-- ================================================================

-- ALTER TABLE Wo_Followers
-- ADD INDEX idx_following_follower_active
-- (following_id, follower_id, active);
