
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
DROP TABLE IF EXISTS `wp_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_comments` WRITE;
/*!40000 ALTER TABLE `wp_comments` DISABLE KEYS */;
INSERT INTO `wp_comments` VALUES (1,1,'Mr WordPress','','https://wordpress.org/','','2016-12-21 06:48:26','2016-12-21 06:48:26','Hi, this is a comment.\nTo delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.',0,'1','','',0,0),(2,120,'WooCommerce','woocommerce@138.68.54.230','','','2016-12-22 11:26:59','2016-12-22 11:26:59','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(3,138,'admin','pankaj.01sharma@gmail.com','','','2016-12-26 12:26:38','2016-12-26 12:26:38','Order status changed to Pending Payment.',0,'1','WooCommerce','order_note',0,0),(4,138,'admin','pankaj.01sharma@gmail.com','','','2016-12-26 12:26:38','2016-12-26 12:26:38','New order email notification manually sent.',0,'1','WooCommerce','order_note',0,0),(5,229,'WooCommerce','woocommerce@138.68.54.230','','','2016-12-28 10:49:50','2016-12-28 10:49:50','Paypal Credit Card Payment Failed with message: \'This transaction cannot be processed due to an invalid merchant configuration.\'',0,'1','WooCommerce','order_note',0,0),(6,229,'WooCommerce','woocommerce@138.68.54.230','','','2016-12-28 10:51:50','2016-12-28 10:51:50','Paypal Credit Card Payment Failed with message: \'This transaction cannot be processed due to an invalid merchant configuration.\'',0,'1','WooCommerce','order_note',0,0),(7,229,'WooCommerce','woocommerce@138.68.54.230','','','2016-12-28 10:56:37','2016-12-28 10:56:37','Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(8,229,'WooCommerce','woocommerce@138.68.54.230','','','2016-12-28 10:56:37','2016-12-28 10:56:37','Paypal Credit Card payment completed with Transaction Id of \'1BW150771L155580P\'',0,'1','WooCommerce','order_note',0,0),(9,437,'WooCommerce','woocommerce@138.68.54.230','','','2017-01-25 20:31:54','2017-01-25 20:31:54','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(10,437,'admin','pankaj.01sharma@gmail.com','','','2017-01-30 11:57:42','2017-01-30 11:57:42','Customer invoice email notification manually sent.',0,'1','WooCommerce','order_note',0,0),(11,437,'admin','pankaj.01sharma@gmail.com','','','2017-01-30 11:58:23','2017-01-30 11:58:23','Customer invoice email notification manually sent.',0,'1','WooCommerce','order_note',0,0),(12,138,'WooCommerce','woocommerce@138.68.54.230','','','2017-01-30 12:00:35','2017-01-30 12:00:35','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(13,456,'admin','pankaj.01sharma@gmail.com','','','2017-02-01 09:54:55','2017-02-01 09:54:55','Order status changed to Pending Payment.',0,'1','WooCommerce','order_note',0,0),(14,456,'admin','pankaj.01sharma@gmail.com','','','2017-02-01 09:54:56','2017-02-01 09:54:56','Order status changed to Pending Payment.',0,'1','WooCommerce','order_note',0,0),(15,456,'admin','pankaj.01sharma@gmail.com','','','2017-02-01 09:55:26','2017-02-01 09:55:26','Customer invoice email notification manually sent.',0,'1','WooCommerce','order_note',0,0),(16,456,'admin','pankaj.01sharma@gmail.com','','','2017-02-01 09:58:15','2017-02-01 09:58:15','Customer invoice email notification manually sent.',0,'1','WooCommerce','order_note',0,0),(17,456,'WooCommerce','woocommerce@138.68.54.230','','','2017-02-01 10:01:10','2017-02-01 10:01:10','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(18,457,'WooCommerce','woocommerce@138.68.54.230','','','2017-02-07 21:26:53','2017-02-07 21:26:53','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(19,460,'WooCommerce','woocommerce@138.68.54.230','','','2017-02-16 08:11:17','2017-02-16 08:11:17','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(20,359,'admin','pankaj.01sharma@gmail.com','','45.127.194.8','2017-02-16 11:27:28','2017-02-16 11:27:28','The leaf shape is perfect for my brand and promotion strategy. I love the selection of shapes, paper and options. The proof service is excellent and so helpful. I used the design it online feature for the first time and found it easy and very helpful.',0,'1','Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36','',0,1),(21,359,'admin','pankaj.01sharma@gmail.com','','45.127.194.8','2017-02-16 11:28:03','2017-02-16 11:28:03','Excellent service',0,'1','Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36','',0,1),(22,361,'admin','pankaj.01sharma@gmail.com','','169.149.134.246','2017-02-25 16:40:42','2017-02-25 16:40:42','Very fine print',0,'1','Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36','',0,1),(23,478,'WooCommerce','woocommerce@138.68.54.230','','','2017-03-17 20:16:00','2017-03-17 20:16:00','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(24,480,'WooCommerce','woocommerce@138.68.54.230','','','2017-03-29 04:11:30','2017-03-29 04:11:30','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(25,481,'WooCommerce','woocommerce@138.68.54.230','','','2017-03-29 08:08:56','2017-03-29 08:08:56','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(26,496,'WooCommerce','woocommerce@138.68.54.230','','','2017-04-07 11:01:47','2017-04-07 11:01:47','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(27,544,'WooCommerce','woocommerce@138.68.54.230','','','2017-05-02 03:21:09','2017-05-02 03:21:09','Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(28,544,'WooCommerce','woocommerce@138.68.54.230','','','2017-05-02 03:21:09','2017-05-02 03:21:09','Autorize.net payment successful<br/>Ref Number/Transaction ID: 60118148193',0,'1','WooCommerce','order_note',0,0),(29,544,'WooCommerce','woocommerce@138.68.54.230','','','2017-05-02 03:21:09','2017-05-02 03:21:09','Your payment has been processed successfully.',0,'1','WooCommerce','order_note',0,0),(30,545,'WooCommerce','woocommerce@138.68.54.230','','','2017-05-04 03:43:51','2017-05-04 03:43:51','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(31,582,'WooCommerce','woocommerce@138.68.54.230','','','2017-06-02 19:50:01','2017-06-02 19:50:01','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(32,584,'WooCommerce','woocommerce@138.68.54.230','','','2017-06-13 11:03:58','2017-06-13 11:03:58','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(33,590,'WooCommerce','woocommerce@138.68.54.230','','','2017-06-13 13:58:45','2017-06-13 13:58:45','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(34,699,'WooCommerce','woocommerce@138.68.54.230','','','2017-06-16 07:50:54','2017-06-16 07:50:54','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0),(35,706,'WooCommerce','woocommerce@138.68.54.230','','','2017-06-16 11:32:22','2017-06-16 11:32:22','Payment to be made upon delivery. Order status changed from Pending Payment to Processing.',0,'1','WooCommerce','order_note',0,0);
/*!40000 ALTER TABLE `wp_comments` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

