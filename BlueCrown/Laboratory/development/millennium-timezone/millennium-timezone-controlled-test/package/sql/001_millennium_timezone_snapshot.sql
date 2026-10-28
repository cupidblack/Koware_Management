-- Apply to the existing koware_iapd database during controlled testing.
-- Historical calculation logs remain files; this table stores only the latest reusable snapshot.
CREATE TABLE IF NOT EXISTS `koware_iapd`.`millennium_timezone_snapshot` (
  `id` TINYINT UNSIGNED NOT NULL,
  `payload_json` LONGTEXT NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
