
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
DROP TABLE IF EXISTS `wp_revisr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_revisr` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` text,
  `event` varchar(42) NOT NULL,
  `user` varchar(60) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_revisr` WRITE;
/*!40000 ALTER TABLE `wp_revisr` DISABLE KEYS */;
INSERT INTO `wp_revisr` VALUES (1,'2017-06-16 13:11:34','Successfully backed up the database.','backup','admin'),(2,'2017-06-16 13:11:34','Error staging files.','error','admin'),(3,'2017-06-16 13:11:34','There was an error committing the changes to the local repository.','error','admin'),(4,'2017-06-16 13:12:30','Successfully backed up the database.','backup','admin'),(5,'2017-06-16 13:12:33','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=d89c74b&success=true\">#d89c74b</a> to the local repository.','commit','admin'),(6,'2017-06-16 13:12:34','Error pushing changes to the remote repository.','error','admin'),(7,'2017-06-16 13:26:24','There was an error committing the changes to the local repository.','error','admin'),(8,'2017-06-16 13:28:57','There was an error committing the changes to the local repository.','error','admin'),(9,'2017-06-16 13:29:23','There was an error committing the changes to the local repository.','error','admin'),(10,'2017-06-16 13:29:40','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=d582355&success=true\">#d582355</a> to the local repository.','commit','admin'),(11,'2017-06-16 13:29:40','Error pushing changes to the remote repository.','error','admin'),(12,'2017-06-16 13:35:59','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=66553dc&success=true\">#66553dc</a> to the local repository.','commit','admin'),(13,'2017-06-16 13:35:59','Error pushing changes to the remote repository.','error','admin'),(14,'2017-06-16 13:52:26','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=73b04e2&success=true\">#73b04e2</a> to the local repository.','commit','admin'),(15,'2017-06-16 13:52:27','Error pushing changes to the remote repository.','error','admin'),(16,'2017-06-16 13:59:08','Error staging files.','error','admin'),(17,'2017-06-16 13:59:09','There was an error committing the changes to the local repository.','error','admin'),(18,'2017-06-16 13:59:31','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=0495d54&success=true\">#0495d54</a> to the local repository.','commit','admin'),(19,'2017-06-16 13:59:31','Error pushing changes to the remote repository.','error','admin'),(20,'2017-06-16 14:01:28','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=080115a&success=true\">#080115a</a> to the local repository.','commit','admin'),(21,'2017-06-16 14:01:28','Error pushing changes to the remote repository.','error','admin'),(22,'2017-06-16 14:02:08','Reverted to commit <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=080115a\">#080115a</a>.','revert','admin'),(23,'2017-06-16 14:02:44','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=7b9cc4a&success=true\">#7b9cc4a</a> to the local repository.','commit','admin'),(24,'2017-06-16 14:02:45','Error pushing changes to the remote repository.','error','admin'),(25,'2017-06-16 14:05:07','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=b35d9d7&success=true\">#b35d9d7</a> to the local repository.','commit','admin'),(26,'2017-06-16 14:05:07','Error pushing changes to the remote repository.','error','admin'),(27,'2017-06-16 14:06:37','There was an error committing the changes to the local repository.','error','admin'),(28,'2017-06-16 14:06:46','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=3c743cb&success=true\">#3c743cb</a> to the local repository.','commit','admin'),(29,'2017-06-16 14:06:46','Error pushing changes to the remote repository.','error','admin'),(30,'2017-06-16 14:14:28','Error pushing changes to the remote repository.','error','admin'),(31,'2017-06-16 14:16:24','Error pushing changes to the remote repository.','error','admin'),(32,'2017-06-16 14:23:15','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=5506533&success=true\">#5506533</a> to the local repository.','commit','admin'),(33,'2017-06-16 14:23:15','Error pushing changes to the remote repository.','error','admin'),(34,'2017-06-16 14:28:05','Committed <a href=\"http://138.68.54.230/wp-admin/admin.php?page=revisr_view_commit&commit=77e8187&success=true\">#77e8187</a> to the local repository.','commit','admin'),(35,'2017-06-16 14:28:05','Error pushing changes to the remote repository.','error','admin'),(36,'2017-06-16 14:29:47','Successfully pushed 2 commits to origin/master.','push','admin');
/*!40000 ALTER TABLE `wp_revisr` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

