/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currency` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `precision` tinyint unsigned NOT NULL,
  `trade` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `platform_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `currency_code_platform_id_unique` (`code`,`platform_id`),
  KEY `currency_code_index` (`code`),
  KEY `currency_platform_fk` (`platform_id`),
  CONSTRAINT `currency_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exchange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exchange` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `exchange` double unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `platform_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exchange_product_id_created_at_platform_id_id_index` (`product_id`,`created_at`,`platform_id`,`id`),
  KEY `exchange_platform_fk` (`platform_id`),
  KEY `exchange_created_at_product_id_index` (`created_at`,`product_id`),
  CONSTRAINT `exchange_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exchange_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ip_lock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ip_lock` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `end_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_iso_unique` (`iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double unsigned NOT NULL DEFAULT '0',
  `price` double unsigned NOT NULL DEFAULT '0',
  `price_stop` double unsigned NOT NULL DEFAULT '0',
  `value` double unsigned NOT NULL DEFAULT '0',
  `fee` double unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `side` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filled` tinyint(1) NOT NULL DEFAULT '0',
  `custom` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `wallet_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_code_index` (`code`),
  KEY `order_platform_fk` (`platform_id`),
  KEY `order_product_fk` (`product_id`),
  KEY `order_user_fk` (`user_id`),
  KEY `order_wallet_fk` (`wallet_id`),
  KEY `order_reference_index` (`reference`),
  CONSTRAINT `order_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_wallet_fk` FOREIGN KEY (`wallet_id`) REFERENCES `wallet` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `platform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `platform` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` double(5,3) unsigned NOT NULL DEFAULT '0.000',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trailing_stop` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `platform_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `platform_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `settings` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `platform_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `platform_user_platform_fk` (`platform_id`),
  KEY `platform_user_user_fk` (`user_id`),
  CONSTRAINT `platform_user_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `platform_user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `acronym` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ask_price` double unsigned NOT NULL DEFAULT '0',
  `ask_quantity` double unsigned NOT NULL DEFAULT '0',
  `ask_sum` double unsigned NOT NULL DEFAULT '0',
  `bid_price` double unsigned NOT NULL DEFAULT '0',
  `bid_quantity` double unsigned NOT NULL DEFAULT '0',
  `bid_sum` double unsigned NOT NULL DEFAULT '0',
  `crypto` tinyint(1) NOT NULL DEFAULT '0',
  `trade` tinyint(1) NOT NULL DEFAULT '0',
  `tracking` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `currency_base_id` bigint unsigned NOT NULL,
  `currency_quote_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `precision` smallint unsigned NOT NULL DEFAULT '0',
  `price_min` double unsigned NOT NULL DEFAULT '0',
  `price_max` double unsigned NOT NULL DEFAULT '0',
  `price_decimal` smallint unsigned NOT NULL DEFAULT '0',
  `quantity_min` double unsigned NOT NULL DEFAULT '0',
  `quantity_max` double unsigned NOT NULL DEFAULT '0',
  `quantity_decimal` smallint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_code_index` (`code`),
  KEY `product_currency_base_fk` (`currency_base_id`),
  KEY `product_currency_quote_fk` (`currency_quote_id`),
  KEY `product_platform_fk` (`platform_id`),
  CONSTRAINT `product_currency_base_fk` FOREIGN KEY (`currency_base_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_currency_quote_fk` FOREIGN KEY (`currency_quote_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `favorite` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `platform_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_user_platform_fk` (`platform_id`),
  KEY `product_user_product_fk` (`product_id`),
  KEY `product_user_user_fk` (`user_id`),
  CONSTRAINT `product_user_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_user_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `queue_fail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue_fail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ticker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticker` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` double unsigned NOT NULL,
  `exchange_reference` double unsigned NOT NULL DEFAULT '0',
  `value_reference` double unsigned NOT NULL DEFAULT '0',
  `exchange_current` double unsigned NOT NULL DEFAULT '0',
  `exchange_min` double unsigned NOT NULL DEFAULT '0',
  `exchange_max` double unsigned NOT NULL DEFAULT '0',
  `value_current` double unsigned NOT NULL DEFAULT '0',
  `value_min` double unsigned NOT NULL DEFAULT '0',
  `value_max` double unsigned NOT NULL DEFAULT '0',
  `date_at` datetime NOT NULL,
  `exchange_min_at` datetime DEFAULT NULL,
  `exchange_max_at` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `currency_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ticker_currency_fk` (`currency_id`),
  KEY `ticker_platform_fk` (`platform_id`),
  KEY `ticker_product_fk` (`product_id`),
  KEY `ticker_user_fk` (`user_id`),
  CONSTRAINT `ticker_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticker_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticker_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticker_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double unsigned NOT NULL,
  `amount` double unsigned NOT NULL,
  `subtotal` double unsigned NOT NULL,
  `fee` double unsigned NOT NULL,
  `total` double unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `wallet_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_code_index` (`code`),
  KEY `transaction_currency_fk` (`currency_id`),
  KEY `transaction_platform_fk` (`platform_id`),
  KEY `transaction_user_fk` (`user_id`),
  KEY `transaction_wallet_fk` (`wallet_id`),
  CONSTRAINT `transaction_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_wallet_fk` FOREIGN KEY (`wallet_id`) REFERENCES `wallet` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transaction_quote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_quote` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange` double unsigned NOT NULL,
  `buy` double unsigned NOT NULL DEFAULT '0',
  `reference` double unsigned NOT NULL DEFAULT '0',
  `price` double unsigned NOT NULL,
  `amount` double unsigned NOT NULL,
  `subtotal` double unsigned NOT NULL,
  `fee` double unsigned NOT NULL,
  `total` double unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `exchange_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `wallet_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_quote_currency_fk` (`currency_id`),
  KEY `transaction_quote_exchange_fk` (`exchange_id`),
  KEY `transaction_quote_platform_fk` (`platform_id`),
  KEY `transaction_quote_user_fk` (`user_id`),
  KEY `transaction_quote_wallet_fk` (`wallet_id`),
  CONSTRAINT `transaction_quote_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_quote_exchange_fk` FOREIGN KEY (`exchange_id`) REFERENCES `exchange` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_quote_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_quote_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_quote_wallet_fk` FOREIGN KEY (`wallet_id`) REFERENCES `wallet` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tfa_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferences` json DEFAULT NULL,
  `investment` double unsigned NOT NULL DEFAULT '0',
  `tfa_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `language_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_unique` (`email`),
  KEY `user_language_fk` (`language_id`),
  CONSTRAINT `user_language_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_session` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_session_ip_index` (`ip`),
  KEY `user_session_user_fk` (`user_id`),
  CONSTRAINT `user_session_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` smallint unsigned NOT NULL DEFAULT '0',
  `amount` double unsigned NOT NULL,
  `buy_exchange` double unsigned NOT NULL DEFAULT '0',
  `buy_value` double unsigned NOT NULL DEFAULT '0',
  `current_exchange` double unsigned NOT NULL DEFAULT '0',
  `current_value` double unsigned NOT NULL DEFAULT '0',
  `sell_stop` tinyint(1) NOT NULL DEFAULT '0',
  `sell_stop_reference` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_amount` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_max_exchange` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_max_value` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_max_percent` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_max_at` datetime DEFAULT NULL,
  `sell_stop_max_executable` tinyint(1) NOT NULL DEFAULT '0',
  `sell_stop_min_exchange` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_min_value` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_min_percent` double unsigned NOT NULL DEFAULT '0',
  `sell_stop_min_at` datetime DEFAULT NULL,
  `sell_stop_min_executable` tinyint(1) NOT NULL DEFAULT '0',
  `buy_stop` tinyint(1) NOT NULL DEFAULT '0',
  `buy_stop_reference` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_amount` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_max_exchange` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_max_value` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_max_percent` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_max_at` datetime DEFAULT NULL,
  `buy_stop_max_executable` tinyint(1) NOT NULL DEFAULT '0',
  `buy_stop_max_follow` tinyint(1) NOT NULL DEFAULT '0',
  `buy_stop_min_exchange` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_min_value` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_min_percent` double unsigned NOT NULL DEFAULT '0',
  `buy_stop_min_at` datetime DEFAULT NULL,
  `buy_stop_min_executable` tinyint(1) NOT NULL DEFAULT '0',
  `sell_stoploss_exchange` double unsigned NOT NULL DEFAULT '0',
  `sell_stoploss_value` double unsigned NOT NULL DEFAULT '0',
  `sell_stoploss_percent` double unsigned NOT NULL DEFAULT '0',
  `sell_stoploss` tinyint(1) NOT NULL DEFAULT '0',
  `sell_stoploss_at` datetime DEFAULT NULL,
  `custom` tinyint(1) NOT NULL DEFAULT '0',
  `crypto` tinyint(1) NOT NULL DEFAULT '0',
  `trade` tinyint(1) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `currency_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `sell_stoploss_executable` tinyint(1) NOT NULL DEFAULT '0',
  `order_buy_stop_id` bigint unsigned DEFAULT NULL,
  `order_sell_stop_id` bigint unsigned DEFAULT NULL,
  `processing_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_address_index` (`address`),
  KEY `wallet_currency_fk` (`currency_id`),
  KEY `wallet_platform_fk` (`platform_id`),
  KEY `wallet_product_fk` (`product_id`),
  KEY `wallet_user_fk` (`user_id`),
  KEY `wallet_order_buy_stop_id_fk` (`order_buy_stop_id`),
  KEY `wallet_order_sell_stop_id_fk` (`order_sell_stop_id`),
  CONSTRAINT `wallet_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_order_buy_stop_id_fk` FOREIGN KEY (`order_buy_stop_id`) REFERENCES `order` (`id`) ON DELETE SET NULL,
  CONSTRAINT `wallet_order_sell_stop_id_fk` FOREIGN KEY (`order_sell_stop_id`) REFERENCES `order` (`id`) ON DELETE SET NULL,
  CONSTRAINT `wallet_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wallet_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `currency_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `wallet_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_history_address_index` (`address`),
  KEY `wallet_history_currency_fk` (`currency_id`),
  KEY `wallet_history_platform_fk` (`platform_id`),
  KEY `wallet_history_product_fk` (`product_id`),
  KEY `wallet_history_user_fk` (`user_id`),
  KEY `wallet_history_wallet_fk` (`wallet_id`),
  CONSTRAINT `wallet_history_currency_fk` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_history_platform_fk` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_history_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_history_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_history_wallet_fk` FOREIGN KEY (`wallet_id`) REFERENCES `wallet` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2021_11_04_230000_base',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2021_11_04_231500_wallet_sell_stoploss_executable',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2021_11_05_1730_platform_seed_kucoin',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2021_11_26_080000_user_investment',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2021_12_30_234500_user_enabled_admin',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2022_01_03_221500_wallet_sell_buy_percent',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2022_01_05_183000_wallet_buy_sell_exchange',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2022_01_10_221500_wallet_history_payload',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2022_01_10_223000_wallet_buy_market',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2022_01_11_083000_wallet_buy_sell_rename',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2022_04_04_231400_wallet_buy_stop_max_follow',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2022_05_16_170000_order_custom',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2024_03_07_230000_forecast_delete',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2024_03_09_150000_platform_trailing_stop',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2024_03_09_151000_order_reference',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2024_03_09_153000_wallet_order_buy_sell_stop_id',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2024_03_26_183000_wallet_buy_market_delete',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2024_04_10_003000_wallet_processing_at',14);
