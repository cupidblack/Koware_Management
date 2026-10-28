CREATE TABLE IF NOT EXISTS `koware_iapd`.`millennium_timezone_snapshots` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `snapshot_at` DATETIME NOT NULL,
  `payload_json` JSON NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_millennium_timezone_snapshot_at` (`snapshot_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `koware_iapd`.`millennium_timezone_probe_cache` (
  `cache_key` VARCHAR(100) NOT NULL,
  `generated_at` DATETIME NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `payload_json` JSON NOT NULL,
  PRIMARY KEY (`cache_key`),
  KEY `idx_millennium_timezone_probe_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
