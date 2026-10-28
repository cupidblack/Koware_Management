-- Optional lightweight cache/state table only. Do not store historical logs here.
-- Confirm the schema name and permissions before running.
CREATE TABLE IF NOT EXISTS `koware_iapd`.`millennium_timezone_state` (
  `state_key` VARCHAR(64) NOT NULL PRIMARY KEY,
  `payload_json` LONGTEXT NOT NULL,
  `generated_at_ts` BIGINT UNSIGNED NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
