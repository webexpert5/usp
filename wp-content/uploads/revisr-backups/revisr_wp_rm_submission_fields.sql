
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
DROP TABLE IF EXISTS `wp_rm_submission_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_rm_submission_fields` (
  `sub_field_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `submission_id` int(6) DEFAULT NULL,
  `field_id` int(6) DEFAULT NULL,
  `form_id` int(6) DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`sub_field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_rm_submission_fields` WRITE;
/*!40000 ALTER TABLE `wp_rm_submission_fields` DISABLE KEYS */;
INSERT INTO `wp_rm_submission_fields` VALUES (1,1,2,1,'vikram'),(2,1,3,1,'9828170043'),(3,1,1,1,'support@RegistrationMagic.com'),(4,1,4,1,'I need help with RegistrationMagic'),(5,2,2,1,'David'),(6,2,3,1,NULL),(7,2,1,1,'support@RegistrationMagic.com'),(8,2,4,1,'Hello There!\r\n\r\nI see you have just installed RegistrationMagic. Hope you are having fun with it. If you have any problems or questions, do not hesitate to contact me directly.\r\n\r\nCheers!'),(9,3,2,1,'David'),(10,3,3,1,NULL),(11,3,1,1,'support@RegistrationMagic.com'),(12,3,4,1,'Hello There!\r\n\r\nI see you have installed RegistrationMagic. I am one of the developers of this plugin and we have put countless hours to make it both powerful and easy to use. \r\n\r\nWe sincerely hope you will enjoy using it. If you run into any problems or have questions, do not hesitate to contact me directly.\r\n\r\nCheers!'),(13,4,17,6,'rimpy'),(14,4,18,6,'code'),(15,4,16,6,'rimpy258@gmail.com'),(16,5,17,6,'rimpy'),(17,5,18,6,'code'),(18,5,16,6,'gurveer.codedrill@gmail.com'),(19,6,17,6,'rimpy'),(20,6,18,6,'code'),(21,6,16,6,'rimpy258@gmail.com'),(22,7,17,6,'test'),(23,7,18,6,'test'),(24,7,16,6,'code@gmail.com'),(25,8,17,6,'test'),(26,8,18,6,'test'),(27,8,16,6,'test@get.com'),(28,9,17,6,'test'),(29,9,18,6,'test'),(30,9,16,6,'test@set.com'),(31,10,17,6,'first'),(32,10,18,6,'lats'),(33,10,16,6,'test@set1.com');
/*!40000 ALTER TABLE `wp_rm_submission_fields` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

