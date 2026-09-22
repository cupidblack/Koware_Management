/*
Buzzjuice Streams Follow Diagnostics
Reference account: Wo_Users.user_id = 66

READ ONLY.

Run this in phpMyAdmin against the Buzzjuice Streams database.

Do NOT run repair UPDATE/DELETE statements until the result set has been
reviewed and a backup has been taken.
*/

-- 1. Confirm the reference account.
SELECT
    user_id,
    username,
    email,
    wp_user_id,
    active,
    start_up,
    startup_follow,
    confirm_followers,
    follow_privacy,
    type,
    verified,
    is_pro,
    joined,
    lastseen
FROM Wo_Users
WHERE user_id = 66;

-- 2. Compare the core account attributes of every user against user 66.
SELECT
    u.user_id,
    u.username,
    u.email,
    u.wp_user_id,
    u.active,
    u.start_up,
    u.startup_follow,
    u.confirm_followers,
    u.follow_privacy,
    u.type,
    u.verified,
    u.is_pro,
    CASE WHEN u.active <> r.active THEN 'DIFF' ELSE '' END AS active_diff,
    CASE WHEN u.start_up <> r.start_up THEN 'DIFF' ELSE '' END AS start_up_diff,
    CASE WHEN u.startup_follow <> r.startup_follow THEN 'DIFF' ELSE '' END AS startup_follow_diff,
    CASE WHEN u.confirm_followers <> r.confirm_followers THEN 'DIFF' ELSE '' END AS confirm_followers_diff,
    CASE WHEN u.follow_privacy <> r.follow_privacy THEN 'DIFF' ELSE '' END AS follow_privacy_diff,
    CASE WHEN u.type <> r.type THEN 'DIFF' ELSE '' END AS type_diff,
    CASE WHEN u.verified <> r.verified THEN 'DIFF' ELSE '' END AS verified_diff,
    CASE WHEN u.is_pro <> r.is_pro THEN 'DIFF' ELSE '' END AS is_pro_diff
FROM Wo_Users u
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
) r
ORDER BY u.user_id;

-- 3. Focused list of accounts with suspicious core values.
SELECT
    user_id,
    username,
    email,
    wp_user_id,
    active,
    start_up,
    startup_follow,
    confirm_followers,
    follow_privacy,
    type,
    verified,
    is_pro
FROM Wo_Users
WHERE active NOT IN (0,1)
   OR start_up IS NULL
   OR startup_follow IS NULL
   OR confirm_followers IS NULL
   OR follow_privacy IS NULL
   OR username IS NULL
   OR username = ''
   OR email IS NULL
   OR email = ''
ORDER BY user_id;

-- 4. All pending relationships. These are candidates for legacy Requested
-- state and should be reviewed before any bulk repair.
SELECT
    f.id,
    f.following_id,
    f.follower_id,
    f.active,
    f.notify,
    f.time,
    u1.username AS following_username,
    u2.username AS follower_username
FROM Wo_Followers f
LEFT JOIN Wo_Users u1 ON u1.user_id = f.following_id
LEFT JOIN Wo_Users u2 ON u2.user_id = f.follower_id
WHERE f.active = 0
ORDER BY f.id DESC;

-- 5. Duplicate relationship pairs.
SELECT
    following_id,
    follower_id,
    COUNT(*) AS row_count,
    SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) AS active_rows,
    SUM(CASE WHEN active = 0 THEN 1 ELSE 0 END) AS pending_rows,
    GROUP_CONCAT(id ORDER BY id DESC) AS row_ids
FROM Wo_Followers
GROUP BY following_id, follower_id
HAVING COUNT(*) > 1
ORDER BY row_count DESC;

-- 6. Inspect every relationship involving the reference account 66.
SELECT
    f.id,
    f.following_id,
    f.follower_id,
    f.active,
    f.notify,
    f.time,
    u1.username AS following_username,
    u2.username AS follower_username
FROM Wo_Followers f
LEFT JOIN Wo_Users u1 ON u1.user_id = f.following_id
LEFT JOIN Wo_Users u2 ON u2.user_id = f.follower_id
WHERE f.following_id = 66
   OR f.follower_id = 66
ORDER BY f.id DESC;

-- 7. Inspect relationship state for the two currently known rows.
SELECT
    f.*,
    u1.username AS following_username,
    u2.username AS follower_username
FROM Wo_Followers f
LEFT JOIN Wo_Users u1 ON u1.user_id = f.following_id
LEFT JOIN Wo_Users u2 ON u2.user_id = f.follower_id
WHERE f.id IN (97,98);

-- 8. Find users whose Wo_Users record is active but whose required SSO
-- mapping is missing.
SELECT
    user_id,
    username,
    email,
    wp_user_id,
    active
FROM Wo_Users
WHERE active = 1
  AND (wp_user_id IS NULL OR wp_user_id = 0)
ORDER BY user_id;

-- 9. Count all follow rows by state.
SELECT
    active,
    COUNT(*) AS row_count
FROM Wo_Followers
GROUP BY active
ORDER BY active DESC;

/*
OPTIONAL REPAIR EXAMPLES — DO NOT RUN BLINDLY.

A) Promote one specific legacy pending relationship:
    UPDATE Wo_Followers
    SET active = 1
    WHERE id = 123
    LIMIT 1;

B) Remove one duplicate row after selecting the correct surviving row:
    DELETE FROM Wo_Followers
    WHERE id = 123
    LIMIT 1;

Do not use:
    UPDATE Wo_Followers SET active = 1 WHERE active = 0;

because that could convert unrelated or intentionally pending legacy records.
*/
