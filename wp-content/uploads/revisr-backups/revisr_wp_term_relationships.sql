
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
DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (1,1,0),(31,2,0),(32,2,0),(33,2,0),(34,2,0),(35,2,0),(36,2,0),(41,5,0),(41,7,0),(41,8,0),(41,9,0),(41,15,0),(55,3,0),(55,7,0),(55,9,0),(163,2,0),(164,2,0),(165,2,0),(255,1,0),(257,1,0),(259,1,0),(261,1,0),(263,1,0),(265,1,0),(267,1,0),(317,3,0),(317,9,0),(317,15,0),(319,3,0),(319,9,0),(319,15,0),(321,3,0),(321,9,0),(321,15,0),(323,3,0),(323,9,0),(323,15,0),(325,3,0),(325,9,0),(325,15,0),(327,3,0),(327,9,0),(327,15,0),(329,3,0),(329,8,0),(329,9,0),(329,15,0),(331,3,0),(331,8,0),(331,9,0),(331,15,0),(332,2,0),(336,3,0),(336,9,0),(336,15,0),(338,3,0),(338,12,0),(340,3,0),(340,12,0),(342,3,0),(342,12,0),(346,3,0),(346,9,0),(346,15,0),(352,3,0),(352,9,0),(352,15,0),(354,3,0),(354,9,0),(354,15,0),(357,3,0),(357,9,0),(357,15,0),(359,3,0),(359,9,0),(359,15,0),(361,3,0),(361,9,0),(361,15,0),(363,3,0),(363,9,0),(363,15,0),(365,3,0),(365,9,0),(365,15,0),(367,3,0),(367,8,0),(367,9,0),(367,15,0),(369,3,0),(369,9,0),(369,15,0),(371,3,0),(371,11,0),(373,3,0),(373,11,0),(375,3,0),(375,11,0),(377,3,0),(377,11,0),(379,3,0),(379,11,0),(381,3,0),(381,10,0),(383,3,0),(383,10,0),(385,3,0),(385,10,0),(387,3,0),(387,10,0),(389,3,0),(389,12,0),(391,3,0),(391,12,0),(393,3,0),(393,12,0),(395,3,0),(395,12,0),(397,3,0),(397,12,0),(399,3,0),(399,12,0),(401,3,0),(401,12,0),(410,3,0),(410,8,0),(410,9,0),(410,15,0),(446,2,0),(448,2,0),(449,2,0),(451,2,0),(452,3,0),(452,8,0),(452,9,0),(452,15,0),(548,3,0),(548,9,0),(548,15,0),(550,3,0),(550,9,0),(550,15,0),(552,3,0),(552,9,0),(552,15,0),(553,3,0),(553,9,0),(553,15,0),(554,3,0),(554,9,0),(554,15,0),(557,3,0),(557,9,0),(557,15,0),(558,3,0),(558,9,0),(558,15,0),(561,3,0),(561,9,0),(561,15,0);
/*!40000 ALTER TABLE `wp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
