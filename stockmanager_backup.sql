-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: stockmanager
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sma_banners`
--

DROP TABLE IF EXISTS `sma_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_banners`
--

LOCK TABLES `sma_banners` WRITE;
/*!40000 ALTER TABLE `sma_banners` DISABLE KEYS */;
INSERT INTO `sma_banners` VALUES (1,'sssss',1,'',NULL,'2026-10-21 08:08:45','2026-10-21 08:08:45');
/*!40000 ALTER TABLE `sma_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_brands`
--

DROP TABLE IF EXISTS `sma_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_brands`
--

LOCK TABLES `sma_brands` WRITE;
/*!40000 ALTER TABLE `sma_brands` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_cart_items`
--

DROP TABLE IF EXISTS `sma_cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sma_cart_items_cart_id_product_id_unique` (`cart_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_cart_items`
--

LOCK TABLES `sma_cart_items` WRITE;
/*!40000 ALTER TABLE `sma_cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_carts`
--

DROP TABLE IF EXISTS `sma_carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `cart_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sma_carts_cart_key_unique` (`cart_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_carts`
--

LOCK TABLES `sma_carts` WRITE;
/*!40000 ALTER TABLE `sma_carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_categories`
--

DROP TABLE IF EXISTS `sma_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_categories`
--

LOCK TABLES `sma_categories` WRITE;
/*!40000 ALTER TABLE `sma_categories` DISABLE KEYS */;
INSERT INTO `sma_categories` VALUES (1,'Category 1','category-1','2026-10-22 15:25:11','2026-10-22 15:25:11');
/*!40000 ALTER TABLE `sma_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_companies`
--

DROP TABLE IF EXISTS `sma_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` bigint unsigned DEFAULT NULL,
  `group_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_houses` int DEFAULT NULL,
  `street` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint unsigned DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_companies`
--

LOCK TABLES `sma_companies` WRITE;
/*!40000 ALTER TABLE `sma_companies` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_failed_jobs`
--

DROP TABLE IF EXISTS `sma_failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sma_failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_failed_jobs`
--

LOCK TABLES `sma_failed_jobs` WRITE;
/*!40000 ALTER TABLE `sma_failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_groups`
--

DROP TABLE IF EXISTS `sma_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_groups`
--

LOCK TABLES `sma_groups` WRITE;
/*!40000 ALTER TABLE `sma_groups` DISABLE KEYS */;
INSERT INTO `sma_groups` VALUES (1,'Owner'),(2,'Admin'),(3,'Biller'),(4,'Customer'),(5,'Suppliers');
/*!40000 ALTER TABLE `sma_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_migrations`
--

DROP TABLE IF EXISTS `sma_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_migrations`
--

LOCK TABLES `sma_migrations` WRITE;
/*!40000 ALTER TABLE `sma_migrations` DISABLE KEYS */;
INSERT INTO `sma_migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2024_11_25_185411_create_brands_table',1),(7,'2024_12_17_203933_create_categories_table',1),(8,'2025_05_03_183809_create_quality_table',1),(9,'2025_05_10_094743_create_sub_categories_table',1),(10,'2025_05_11_114423_create_products_table',1),(11,'2025_05_11_115741_create_product_images_table',1),(12,'2025_05_11_115911_create_purchases_table',1),(13,'2025_05_11_120130_create_purchase_item_table',1),(14,'2025_05_16_180856_create_banners_table',1),(15,'2025_05_31_121542_create_sales_table',1),(16,'2025_07_30_171056_create_payments_table',1),(17,'2025_08_02_000000_create_shop_infos_table',1),(18,'2025_08_06_191102_create_companies_table',1),(19,'2025_08_06_191136_create_groups_table',1),(20,'2025_08_06_191200_create_warehouses_table',1),(21,'2025_08_09_165025_create_units_table',1),(22,'2026_01_04_115835_add__country_fields_to_comapny_table',1),(23,'2026_01_09_180833_create_pos_registers_table',1),(24,'2026_02_23_114707_create_password_otps_table',1),(25,'2026_04_06_084649_create_carts_table',1),(26,'2026_04_06_084650_create_cart_items_table',1),(27,'2026_09_26_232925_create_register_cash_movements_table',1);
/*!40000 ALTER TABLE `sma_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_password_otps`
--

DROP TABLE IF EXISTS `sma_password_otps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_password_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `last_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_password_otps_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_password_otps`
--

LOCK TABLES `sma_password_otps` WRITE;
/*!40000 ALTER TABLE `sma_password_otps` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_password_otps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_password_reset_tokens`
--

DROP TABLE IF EXISTS `sma_password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_password_reset_tokens`
--

LOCK TABLES `sma_password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `sma_password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_password_resets`
--

DROP TABLE IF EXISTS `sma_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_password_resets`
--

LOCK TABLES `sma_password_resets` WRITE;
/*!40000 ALTER TABLE `sma_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_payments`
--

DROP TABLE IF EXISTS `sma_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned DEFAULT NULL,
  `purchase_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `paid_at` timestamp NULL DEFAULT NULL,
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_paid` decimal(10,2) DEFAULT NULL,
  `pos_balance` decimal(10,2) DEFAULT NULL,
  `created_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_payments_sale_id_foreign` (`sale_id`),
  KEY `sma_payments_purchase_id_foreign` (`purchase_id`),
  CONSTRAINT `sma_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `sma_purchases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sma_payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sma_sales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_payments`
--

LOCK TABLES `sma_payments` WRITE;
/*!40000 ALTER TABLE `sma_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_personal_access_tokens`
--

DROP TABLE IF EXISTS `sma_personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sma_personal_access_tokens_token_unique` (`token`),
  KEY `sma_personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_personal_access_tokens`
--

LOCK TABLES `sma_personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `sma_personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_pos_registers`
--

DROP TABLE IF EXISTS `sma_pos_registers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_pos_registers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `cash_in_hand` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_cash` decimal(15,2) DEFAULT NULL,
  `status` enum('open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `opened_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_pos_registers_closed_by_foreign` (`closed_by`),
  KEY `sma_pos_registers_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `sma_pos_registers_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `sma_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sma_pos_registers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `sma_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_pos_registers`
--

LOCK TABLES `sma_pos_registers` WRITE;
/*!40000 ALTER TABLE `sma_pos_registers` DISABLE KEYS */;
INSERT INTO `sma_pos_registers` VALUES (1,'POS-20261021-1',1,50.00,NULL,'open',NULL,NULL,'2026-10-21 20:46:44',NULL);
/*!40000 ALTER TABLE `sma_pos_registers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_product_images`
--

DROP TABLE IF EXISTS `sma_product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_review` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `sma_product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sma_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_product_images`
--

LOCK TABLES `sma_product_images` WRITE;
/*!40000 ALTER TABLE `sma_product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_products`
--

DROP TABLE IF EXISTS `sma_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `second_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` int DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `unit_id` bigint unsigned DEFAULT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `subcategory_id` bigint unsigned DEFAULT NULL,
  `quality_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sma_products_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_products`
--

LOCK TABLES `sma_products` WRITE;
/*!40000 ALTER TABLE `sma_products` DISABLE KEYS */;
INSERT INTO `sma_products` VALUES (1,'Black Kampot Pepper','ម្រេចខ្មៅកំពត','KH-PEP-01',NULL,148,5.50,12.00,NULL,NULL,'2027-12-31',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 09:09:32'),(2,'Golden Silk Krama','ក្រមាខ្មែរថ្នៃ','KH-SILK-02',NULL,-6,25.00,45.00,NULL,NULL,'2030-01-01',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 09:10:32'),(3,'Ibis Jasmine Rice','អង្ករផ្ការំដួល','KH-RICE-03',NULL,499,0.90,1.50,NULL,NULL,'2026-06-20',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 09:09:32'),(4,'Thnot Palm Sugar','ស្ករត្នោតកំពង់ស្ពឺ','KH-SUG-04',NULL,300,2.00,3.50,NULL,NULL,'2026-01-15',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 08:42:48'),(5,'Jaya Palm Wine','ស្រាត្នោតជ័យ','KH-WINE-05',NULL,79,8.00,15.00,NULL,NULL,'2027-05-10',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 09:09:32'),(6,'Angkor Beer (Can)','ស្រាបៀរអង្គរ','KH-BEER-06',NULL,1200,0.45,0.65,NULL,NULL,'2026-11-20',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 08:42:48'),(7,'Rattan Basket','កន្ត្រកផ្តៅ','KH-HAND-07',NULL,60,4.00,9.00,NULL,NULL,'2035-12-31',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 08:42:48'),(8,'Bodia Lemongrass Oil','ប្រេងរឹតពោធិ៍សាត់','KH-SPA-08',NULL,120,6.50,14.50,NULL,NULL,'2027-08-15',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 08:42:48'),(9,'Dried Mango Slices','ស្វាយសម្ងួត','KH-SNK-09',NULL,400,1.20,2.50,NULL,NULL,'2026-04-10',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 08:42:48'),(10,'Phamuong Skirt','សំពត់ផាមួង','KH-SILK-10',NULL,20,85.00,150.00,NULL,NULL,'2032-01-01',NULL,NULL,NULL,NULL,NULL,'2026-10-22 08:42:48','2026-10-22 08:42:48');
/*!40000 ALTER TABLE `sma_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_purchase_items`
--

DROP TABLE IF EXISTS `sma_purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `sma_purchase_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sma_purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sma_products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sma_purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `sma_purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_purchase_items`
--

LOCK TABLES `sma_purchase_items` WRITE;
/*!40000 ALTER TABLE `sma_purchase_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_purchases`
--

DROP TABLE IF EXISTS `sma_purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_purchases`
--

LOCK TABLES `sma_purchases` WRITE;
/*!40000 ALTER TABLE `sma_purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_qualitys`
--

DROP TABLE IF EXISTS `sma_qualitys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_qualitys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_qualitys`
--

LOCK TABLES `sma_qualitys` WRITE;
/*!40000 ALTER TABLE `sma_qualitys` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_qualitys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_register_cash_movements`
--

DROP TABLE IF EXISTS `sma_register_cash_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_register_cash_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `register_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_register_cash_movements_register_id_index` (`register_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_register_cash_movements`
--

LOCK TABLES `sma_register_cash_movements` WRITE;
/*!40000 ALTER TABLE `sma_register_cash_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_register_cash_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_sale_items`
--

DROP TABLE IF EXISTS `sma_sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_sale_items_sale_id_foreign` (`sale_id`),
  KEY `sma_sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sma_sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sma_products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sma_sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sma_sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_sale_items`
--

LOCK TABLES `sma_sale_items` WRITE;
/*!40000 ALTER TABLE `sma_sale_items` DISABLE KEYS */;
INSERT INTO `sma_sale_items` VALUES (1,1,1,NULL,1,12.00,0.00,'2026-10-22 09:06:39','2026-10-22 09:06:39'),(2,2,1,NULL,1,12.00,0.00,'2026-10-22 09:09:32','2026-10-22 09:09:32'),(3,2,2,NULL,1,45.00,0.00,'2026-10-22 09:09:32','2026-10-22 09:09:32'),(4,2,3,NULL,1,1.50,0.00,'2026-10-22 09:09:32','2026-10-22 09:09:32'),(5,2,5,NULL,1,15.00,0.00,'2026-10-22 09:09:32','2026-10-22 09:09:32'),(6,3,2,NULL,50,45.00,0.00,'2026-10-22 09:10:32','2026-10-22 09:10:32');
/*!40000 ALTER TABLE `sma_sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_sales`
--

DROP TABLE IF EXISTS `sma_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biller_id` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `cash_register_id` bigint unsigned DEFAULT NULL,
  `sale_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sma_sales_reference_unique` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_sales`
--

LOCK TABLES `sma_sales` WRITE;
/*!40000 ALTER TABLE `sma_sales` DISABLE KEYS */;
INSERT INTO `sma_sales` VALUES (1,'SALE-6AD9D21F51CEA',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,12.00,'pending','pending',NULL,'2026-10-22','2026-10-22 09:06:39','2026-10-22 09:06:39'),(2,'SALE-6AD9D2CCC9EDB',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,73.50,'pending','pending',NULL,'2026-10-22','2026-10-22 09:09:32','2026-10-22 09:09:32'),(3,'SALE-6AD9D3087AA96',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,2250.00,'pending','pending',NULL,'2026-10-22','2026-10-22 09:10:32','2026-10-22 09:10:32');
/*!40000 ALTER TABLE `sma_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_shop_infos`
--

DROP TABLE IF EXISTS `sma_shop_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_shop_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_shop` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_shop_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `close_shop` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_shop` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `facebook` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_shop_infos`
--

LOCK TABLES `sma_shop_infos` WRITE;
/*!40000 ALTER TABLE `sma_shop_infos` DISABLE KEYS */;
INSERT INTO `sma_shop_infos` VALUES (1,'Mart@',NULL,NULL,NULL,NULL,NULL,NULL,'S',NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-21 08:14:38');
/*!40000 ALTER TABLE `sma_shop_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_sub_categories`
--

DROP TABLE IF EXISTS `sma_sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_sub_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sma_sub_categories_category_id_foreign` (`category_id`),
  CONSTRAINT `sma_sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `sma_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_sub_categories`
--

LOCK TABLES `sma_sub_categories` WRITE;
/*!40000 ALTER TABLE `sma_sub_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_sub_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_units`
--

DROP TABLE IF EXISTS `sma_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_units`
--

LOCK TABLES `sma_units` WRITE;
/*!40000 ALTER TABLE `sma_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_users`
--

DROP TABLE IF EXISTS `sma_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `group_id` bigint unsigned DEFAULT NULL,
  `company_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sma_users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_users`
--

LOCK TABLES `sma_users` WRITE;
/*!40000 ALTER TABLE `sma_users` DISABLE KEYS */;
INSERT INTO `sma_users` VALUES (1,'owner',NULL,NULL,'owner@sa.com','$2y$12$qm0tlsbTZGv/Lb/Mb/lh7uo7B9DP3QQ/cXLTbo9WWbdb74vuJjPV6',NULL,'avatars/X4Svkw0wnCgJbuIBJleybD1MAdxyVtlxz0b8ybdF.jpg',NULL,'active',1,NULL,'192.168.65.1','8yv1pExwmH9Q4vBqF0tHRjEbErT7GDSrHQRakFqjbnTr4TaI6LJaqJhAbVzm','2026-10-21 08:03:25','2026-10-21 20:45:38');
/*!40000 ALTER TABLE `sma_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sma_warehouses`
--

DROP TABLE IF EXISTS `sma_warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sma_warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sma_warehouses`
--

LOCK TABLES `sma_warehouses` WRITE;
/*!40000 ALTER TABLE `sma_warehouses` DISABLE KEYS */;
/*!40000 ALTER TABLE `sma_warehouses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-23  6:08:53
