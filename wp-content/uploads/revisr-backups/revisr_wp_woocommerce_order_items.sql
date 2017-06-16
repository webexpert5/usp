
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
DROP TABLE IF EXISTS `wp_woocommerce_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_order_items` (
  `order_item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_item_name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_item_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `order_id` bigint(20) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_woocommerce_order_items` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_order_items` DISABLE KEYS */;
INSERT INTO `wp_woocommerce_order_items` VALUES (1,'DOOR HANGERS','line_item',120),(2,'PRESENTATION FOLDERS','line_item',138),(3,'Product Cost','fee',138),(8,'DOOR HANGERS','line_item',229),(9,'Flat Rate','shipping',229),(10,'FLYERS','line_item',437),(11,'Local Pickup','shipping',437),(12,'CATALOGS','line_item',456),(13,'CALENDARS','line_item',457),(14,'Local Pickup','shipping',457),(15,'Brouchures','line_item',460),(16,'Local Pickup','shipping',460),(17,'CANVAS ROLL','line_item',478),(18,'Local Pickup','shipping',478),(19,'1 Color Envelopes','line_item',480),(20,'Local Pickup','shipping',480),(21,'FLYERS','line_item',481),(22,'Brochures','line_item',481),(23,'Local Pickup','shipping',481),(24,'CATALOGS','line_item',496),(25,'DVD Inserts','line_item',496),(26,'CATALOGS','line_item',496),(27,'Local Pickup','shipping',496),(28,'Business Cards','line_item',544),(29,'Local Pickup','shipping',544),(30,'Brochures','line_item',545),(31,'Local Pickup','shipping',545),(32,'US-CA-CA STANDARD-1','tax',545),(33,'SHORT RUN FLYERS','line_item',582),(34,'Local Pickup','shipping',582),(35,'US-CA-CA STANDARD-1','tax',582),(36,'Brochures','line_item',584),(37,'Local Pickup','shipping',584),(38,'US-CA-CA STANDARD-1','tax',584),(39,'Brochures','line_item',590),(40,'Local Pickup','shipping',590),(41,'US-CA-CA STANDARD-1','tax',590),(42,'Hang Tags','line_item',699),(43,'Local Pickup','shipping',699),(44,'US-CA-CA STANDARD-1','tax',699),(45,'Hang Tags','line_item',706),(46,'Local Pickup','shipping',706),(47,'US-CA-CA STANDARD-1','tax',706);
/*!40000 ALTER TABLE `wp_woocommerce_order_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

