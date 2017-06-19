
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `wp_woocommerce_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_sessions` (
  `session_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `session_key` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_expiry` bigint(20) NOT NULL,
  PRIMARY KEY (`session_key`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1613 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_woocommerce_sessions` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_sessions` DISABLE KEYS */;
INSERT INTO `wp_woocommerce_sessions` VALUES (1612,'1','a:18:{s:4:\"cart\";s:3830:\"a:5:{s:32:\"0f3ce1900e3f7b0c587a529c9664bf2a\";a:20:{s:14:\"option_id-Size\";s:11:\"#9 Envelope\";s:15:\"option_id-Paper\";s:24:\"24# Uncoated Smooth Text\";s:15:\"option_id-Color\";s:26:\"1/0 (Black Ink Front Side)\";s:17:\"option_id-Coating\";s:8:\"Uncoated\";s:20:\"option_id-Turnaround\";s:8:\"2-3 Days\";s:18:\"option_id-quantity\";s:4:\"1000\";s:21:\"option_id-front_image\";s:108:\"<a href=\"http://138.68.54.230/wp-content/uploads/2017/06/banner-12-06.jpg\" target=\"_blank\">Attached File</a>\";s:20:\"option_id-back_image\";s:0:\"\";s:15:\"option_id-proof\";s:0:\"\";s:3:\"sku\";s:14:\"1colorenvelope\";s:10:\"unique_key\";s:32:\"2f96924762a6c7b27bc3b24d4c546cee\";s:10:\"product_id\";i:55;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:10:\"line_total\";d:79;s:8:\"line_tax\";i:0;s:13:\"line_subtotal\";d:79;s:17:\"line_subtotal_tax\";i:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"b347230ccfcee713a6b05d5489bccaa0\";a:20:{s:14:\"option_id-Size\";s:12:\"#10 Envelope\";s:15:\"option_id-Paper\";s:26:\"70# Uncoated Offset Smooth\";s:15:\"option_id-Color\";s:28:\"4/0 ( Full Color Front Side)\";s:17:\"option_id-Coating\";s:8:\"Uncoated\";s:20:\"option_id-Turnaround\";s:8:\"2-3 Days\";s:18:\"option_id-quantity\";s:4:\"1000\";s:21:\"option_id-front_image\";s:0:\"\";s:20:\"option_id-back_image\";s:0:\"\";s:15:\"option_id-proof\";s:0:\"\";s:3:\"sku\";s:14:\"4colorenvelope\";s:10:\"unique_key\";s:32:\"fcc1b3b25bbcb44a6c7a484fd9bf3e29\";s:10:\"product_id\";i:369;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:10:\"line_total\";d:249;s:8:\"line_tax\";i:0;s:13:\"line_subtotal\";d:249;s:17:\"line_subtotal_tax\";i:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"7bbedf697c3fc6b0b0a07c2f13eec1ec\";a:20:{s:14:\"option_id-Size\";s:22:\"#10 Envelope w/ Window\";s:15:\"option_id-Paper\";s:26:\"70# Uncoated Offset Smooth\";s:15:\"option_id-Color\";s:28:\"4/0 ( Full Color Front Side)\";s:17:\"option_id-Coating\";s:8:\"Uncoated\";s:20:\"option_id-Turnaround\";s:8:\"2-3 Days\";s:18:\"option_id-quantity\";s:4:\"1000\";s:21:\"option_id-front_image\";s:0:\"\";s:20:\"option_id-back_image\";s:0:\"\";s:15:\"option_id-proof\";s:0:\"\";s:3:\"sku\";s:14:\"4colorenvelope\";s:10:\"unique_key\";s:32:\"a8bb2b87557dc72c75a3a6f5a84ca6d2\";s:10:\"product_id\";i:369;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:10:\"line_total\";d:269;s:8:\"line_tax\";i:0;s:13:\"line_subtotal\";d:269;s:17:\"line_subtotal_tax\";i:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"9a9ef1604b6569b8bcbdc8775ca60859\";a:18:{s:14:\"option_id-Size\";s:7:\"6\" x 9\"\";s:15:\"option_id-Paper\";s:43:\"Recycled 80 lb. Dull Text with Matte Finish\";s:17:\"option_id-Folding\";s:7:\"Trifold\";s:18:\"option_id-quantity\";s:3:\"100\";s:21:\"option_id-front_image\";s:0:\"\";s:20:\"option_id-back_image\";s:0:\"\";s:15:\"option_id-proof\";s:0:\"\";s:3:\"sku\";s:9:\"brochures\";s:10:\"unique_key\";s:32:\"17516639e0e303b82b7a6a467deafa08\";s:10:\"product_id\";i:452;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:10:\"line_total\";d:40.909999999999997;s:8:\"line_tax\";i:0;s:13:\"line_subtotal\";d:40.909999999999997;s:17:\"line_subtotal_tax\";i:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"4c28aeab55f25ba302ca5b42441b7701\";a:18:{s:14:\"option_id-Size\";s:7:\"6\" x 9\"\";s:15:\"option_id-Paper\";s:43:\"Recycled 80 lb. Dull Text with Matte Finish\";s:17:\"option_id-Folding\";s:7:\"Trifold\";s:18:\"option_id-quantity\";s:3:\"100\";s:21:\"option_id-front_image\";s:0:\"\";s:20:\"option_id-back_image\";s:0:\"\";s:15:\"option_id-proof\";s:0:\"\";s:3:\"sku\";s:9:\"brochures\";s:10:\"unique_key\";s:32:\"baae840b4b2ebc0cbceabbf6d084f782\";s:10:\"product_id\";i:452;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:10:\"line_total\";d:40.909999999999997;s:8:\"line_tax\";i:0;s:13:\"line_subtotal\";d:40.909999999999997;s:17:\"line_subtotal_tax\";i:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}}\";s:15:\"applied_coupons\";s:6:\"a:0:{}\";s:23:\"coupon_discount_amounts\";s:6:\"a:0:{}\";s:27:\"coupon_discount_tax_amounts\";s:6:\"a:0:{}\";s:21:\"removed_cart_contents\";s:6:\"a:0:{}\";s:19:\"cart_contents_total\";d:678.81999999999994;s:5:\"total\";i:0;s:8:\"subtotal\";d:678.81999999999994;s:15:\"subtotal_ex_tax\";d:678.81999999999994;s:9:\"tax_total\";i:0;s:5:\"taxes\";s:6:\"a:0:{}\";s:14:\"shipping_taxes\";s:6:\"a:0:{}\";s:13:\"discount_cart\";i:0;s:17:\"discount_cart_tax\";i:0;s:14:\"shipping_total\";i:0;s:18:\"shipping_tax_total\";i:0;s:9:\"fee_total\";i:0;s:4:\"fees\";s:6:\"a:0:{}\";}',1498024538);
/*!40000 ALTER TABLE `wp_woocommerce_sessions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

