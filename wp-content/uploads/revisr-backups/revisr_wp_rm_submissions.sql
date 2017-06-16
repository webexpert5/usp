
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
DROP TABLE IF EXISTS `wp_rm_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_rm_submissions` (
  `submission_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(6) DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  `user_email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `child_id` int(6) NOT NULL DEFAULT '0',
  `last_child` int(6) NOT NULL DEFAULT '0',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `submitted_on` datetime DEFAULT NULL,
  `unique_token` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`submission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_rm_submissions` WRITE;
/*!40000 ALTER TABLE `wp_rm_submissions` DISABLE KEYS */;
INSERT INTO `wp_rm_submissions` VALUES (1,1,'a:4:{i:2;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Your Name\";s:5:\"value\";s:6:\"vikram\";s:4:\"type\";s:7:\"Textbox\";}i:3;O:8:\"stdClass\":3:{s:5:\"label\";s:17:\"Your Phone Number\";s:5:\"value\";s:10:\"9828170043\";s:4:\"type\";s:6:\"Number\";}i:1;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"Your Email\";s:5:\"value\";s:29:\"support@RegistrationMagic.com\";s:4:\"type\";s:5:\"Email\";}i:4;O:8:\"stdClass\":3:{s:5:\"label\";s:7:\"Message\";s:5:\"value\";s:34:\"I need help with RegistrationMagic\";s:4:\"type\";s:8:\"Textarea\";}}','support@RegistrationMagic.com',497,498,1,'2016-12-15 07:22:23','6314817865438721'),(2,1,'a:4:{i:2;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Your Name\";s:5:\"value\";s:5:\"David\";s:4:\"type\";s:7:\"Textbox\";}i:3;O:8:\"stdClass\":3:{s:5:\"label\";s:17:\"Your Phone Number\";s:5:\"value\";s:0:\"\";s:4:\"type\";s:6:\"Number\";}i:1;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"Your Email\";s:5:\"value\";s:29:\"support@RegistrationMagic.com\";s:4:\"type\";s:5:\"Email\";}i:4;O:8:\"stdClass\":3:{s:5:\"label\";s:7:\"Message\";s:5:\"value\";s:187:\"Hello There!\r\n\r\nI see you have just installed RegistrationMagic. Hope you are having fun with it. If you have any problems or questions, do not hesitate to contact me directly.\r\n\r\nCheers!\";s:4:\"type\";s:8:\"Textarea\";}}','support@RegistrationMagic.com',498,498,1,'2016-12-15 09:06:28','6314817865438721'),(3,1,'a:4:{i:2;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Your Name\";s:5:\"value\";s:5:\"David\";s:4:\"type\";s:7:\"Textbox\";}i:3;O:8:\"stdClass\":3:{s:5:\"label\";s:17:\"Your Phone Number\";s:5:\"value\";s:0:\"\";s:4:\"type\";s:6:\"Number\";}i:1;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"Your Email\";s:5:\"value\";s:29:\"support@RegistrationMagic.com\";s:4:\"type\";s:5:\"Email\";}i:4;O:8:\"stdClass\":3:{s:5:\"label\";s:7:\"Message\";s:5:\"value\";s:321:\"Hello There!\r\n\r\nI see you have installed RegistrationMagic. I am one of the developers of this plugin and we have put countless hours to make it both powerful and easy to use. \r\n\r\nWe sincerely hope you will enjoy using it. If you run into any problems or have questions, do not hesitate to contact me directly.\r\n\r\nCheers!\";s:4:\"type\";s:8:\"Textarea\";}}','support@RegistrationMagic.com',0,498,1,'2016-12-15 09:09:32','6314817865438721'),(4,6,'a:3:{i:17;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"First Name\";s:5:\"value\";s:5:\"rimpy\";s:4:\"type\";s:7:\"Textbox\";}i:18;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Last Name\";s:5:\"value\";s:4:\"code\";s:4:\"type\";s:7:\"Textbox\";}i:16;O:8:\"stdClass\":3:{s:5:\"label\";s:5:\"Email\";s:5:\"value\";s:18:\"rimpy258@gmail.com\";s:4:\"type\";s:5:\"Email\";}}','rimpy258@gmail.com',0,4,0,'2016-12-28 13:31:09','614829318696360'),(5,6,'a:3:{i:17;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"First Name\";s:5:\"value\";s:5:\"rimpy\";s:4:\"type\";s:7:\"Textbox\";}i:18;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Last Name\";s:5:\"value\";s:4:\"code\";s:4:\"type\";s:7:\"Textbox\";}i:16;O:8:\"stdClass\":3:{s:5:\"label\";s:5:\"Email\";s:5:\"value\";s:27:\"gurveer.codedrill@gmail.com\";s:4:\"type\";s:5:\"Email\";}}','gurveer.codedrill@gmail.com',0,5,0,'2016-12-28 13:34:17','61482932057161'),(6,6,'a:3:{i:17;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"First Name\";s:5:\"value\";s:5:\"rimpy\";s:4:\"type\";s:7:\"Textbox\";}i:18;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Last Name\";s:5:\"value\";s:4:\"code\";s:4:\"type\";s:7:\"Textbox\";}i:16;O:8:\"stdClass\":3:{s:5:\"label\";s:5:\"Email\";s:5:\"value\";s:18:\"rimpy258@gmail.com\";s:4:\"type\";s:5:\"Email\";}}','rimpy258@gmail.com',0,6,0,'2016-12-28 14:08:59','614829341391071'),(7,6,'a:3:{i:17;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"First Name\";s:5:\"value\";s:4:\"test\";s:4:\"type\";s:7:\"Textbox\";}i:18;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Last Name\";s:5:\"value\";s:4:\"test\";s:4:\"type\";s:7:\"Textbox\";}i:16;O:8:\"stdClass\":3:{s:5:\"label\";s:5:\"Email\";s:5:\"value\";s:14:\"code@gmail.com\";s:4:\"type\";s:5:\"Email\";}}','code@gmail.com',0,7,0,'2016-12-28 14:09:38','614829341783237'),(8,6,'a:3:{i:17;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"First Name\";s:5:\"value\";s:4:\"test\";s:4:\"type\";s:7:\"Textbox\";}i:18;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Last Name\";s:5:\"value\";s:4:\"test\";s:4:\"type\";s:7:\"Textbox\";}i:16;O:8:\"stdClass\":3:{s:5:\"label\";s:5:\"Email\";s:5:\"value\";s:12:\"test@get.com\";s:4:\"type\";s:5:\"Email\";}}','test@get.com',0,8,0,'2016-12-28 14:11:30','614829342907574'),(9,6,'a:3:{i:17;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"First Name\";s:5:\"value\";s:4:\"test\";s:4:\"type\";s:7:\"Textbox\";}i:18;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Last Name\";s:5:\"value\";s:4:\"test\";s:4:\"type\";s:7:\"Textbox\";}i:16;O:8:\"stdClass\":3:{s:5:\"label\";s:5:\"Email\";s:5:\"value\";s:12:\"test@set.com\";s:4:\"type\";s:5:\"Email\";}}','test@set.com',0,9,0,'2016-12-28 14:12:17','614829343373206'),(10,6,'a:3:{i:17;O:8:\"stdClass\":3:{s:5:\"label\";s:10:\"First Name\";s:5:\"value\";s:5:\"first\";s:4:\"type\";s:7:\"Textbox\";}i:18;O:8:\"stdClass\":3:{s:5:\"label\";s:9:\"Last Name\";s:5:\"value\";s:4:\"lats\";s:4:\"type\";s:7:\"Textbox\";}i:16;O:8:\"stdClass\":3:{s:5:\"label\";s:5:\"Email\";s:5:\"value\";s:13:\"test@set1.com\";s:4:\"type\";s:5:\"Email\";}}','test@set1.com',0,10,0,'2016-12-28 14:14:35','614829344759394');
/*!40000 ALTER TABLE `wp_rm_submissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

