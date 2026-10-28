-- ============================================
-- WATCH PLUS Admin — Database Schema
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------
-- Table: admins
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(200) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('super_admin','admin','editor') NOT NULL DEFAULT 'admin',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: categories
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `type` ENUM('general','series','channel','match') NOT NULL DEFAULT 'general',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: channels
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `channels` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `logo` VARCHAR(500) DEFAULT NULL,
    `stream_url` TEXT NOT NULL,
    `category_id` INT DEFAULT NULL,
    `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: series
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `series` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(300) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `release_year` YEAR DEFAULT NULL,
    `rating` DECIMAL(3,1) DEFAULT NULL,
    `poster` VARCHAR(500) DEFAULT NULL,
    `banner` VARCHAR(500) DEFAULT NULL,
    `category_id` INT DEFAULT NULL,
    `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: seasons
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `seasons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `series_id` INT NOT NULL,
    `season_number` INT NOT NULL,
    `release_year` YEAR DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`series_id`) REFERENCES `series`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: episodes
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `episodes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `season_id` INT NOT NULL,
    `episode_number` INT NOT NULL,
    `title` VARCHAR(300) DEFAULT NULL,
    `video_url` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`season_id`) REFERENCES `seasons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: matches
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `matches` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `competition_name` VARCHAR(200) NOT NULL,
    `home_team_name` VARCHAR(150) NOT NULL,
    `home_team_logo` VARCHAR(500) DEFAULT NULL,
    `away_team_name` VARCHAR(150) NOT NULL,
    `away_team_logo` VARCHAR(500) DEFAULT NULL,
    `kickoff_time` DATETIME NOT NULL,
    `status` ENUM('upcoming','live','finished') NOT NULL DEFAULT 'upcoming',
    `live_minute` VARCHAR(20) DEFAULT NULL,
    `stream_url` TEXT DEFAULT NULL,
    `broadcaster_name` VARCHAR(150) DEFAULT NULL,
    `broadcaster_logo` VARCHAR(500) DEFAULT NULL,
    `commentator_name` VARCHAR(150) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: ad_settings
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `ad_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `network_name` VARCHAR(50) NOT NULL UNIQUE,
    `settings_json` JSON DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: app_settings
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `app_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------
-- Seed Data: app_settings
-- -------------------------------------------
INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES
('app_name', 'WATCH PLUS'),
('app_version', '1.0.0'),
('app_description', 'تطبيقك المفضل لمشاهدة المباريات والمسلسلات والقنوات بجودة عالية'),
('maintenance_mode', '0'),
('force_update', '0'),
('show_ads', '1'),
('enable_channels', '1'),
('enable_series', '1'),
('enable_matches', '1');

-- -------------------------------------------
-- Seed Data: categories
-- -------------------------------------------
INSERT INTO `categories` (`name`, `type`) VALUES
('أكشن', 'general'),
('دراما', 'general'),
('كوميديا', 'general'),
('رياضة', 'channel'),
('أخبار', 'channel'),
('الدوري الإسباني', 'match'),
('دوري أبطال أوروبا', 'match'),
('جريمة', 'series'),
('خيال علمي', 'series');
