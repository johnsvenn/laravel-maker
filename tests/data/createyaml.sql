SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `examples`;
CREATE TABLE `examples` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT 'defaultslug',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'This is a comment',
  `doublefield` double NOT NULL,
  `decimalfield` decimal(8,2) NOT NULL,
  `decimalfielddefault` decimal(8,2) NOT NULL DEFAULT '20.00',
  `options` enum('one','two','three') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


