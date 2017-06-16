
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
DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=330 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'comment_shortcuts','false'),(7,1,'admin_color','fresh'),(8,1,'use_ssl','0'),(9,1,'show_admin_bar_front','true'),(10,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(11,1,'wp_user_level','10'),(12,1,'dismissed_wp_pointers','vc_pointers_backend_editor,advance_search_for_woocommerce_admin_pointers1_0_advance_search_for_woocommerce_admin_pointers'),(13,1,'show_welcome_panel','0'),(14,1,'session_tokens','a:4:{s:64:\"b3ac652ac69f54cc95478665c7bcbbc3658da9357b1cee45a9bac5aed2e5535e\";a:4:{s:10:\"expiration\";i:1497377580;s:2:\"ip\";s:13:\"23.242.17.224\";s:2:\"ua\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36\";s:5:\"login\";i:1496167980;}s:64:\"0f5a84daa0b5b75f457a3ee4ee2d54f14836c970846b33a839e7e49d4deb80bb\";a:4:{s:10:\"expiration\";i:1497641278;s:2:\"ip\";s:15:\"173.196.130.210\";s:2:\"ua\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36\";s:5:\"login\";i:1496431678;}s:64:\"e5b0b7dcd37405d79051d6dcca1c7f2b6fa9466fc23a5373acb0d134bdec89bf\";a:4:{s:10:\"expiration\";i:1498569872;s:2:\"ip\";s:13:\"45.127.194.37\";s:2:\"ua\";s:74:\"Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:53.0) Gecko/20100101 Firefox/53.0\";s:5:\"login\";i:1497360272;}s:64:\"2333afe8fd23ddb97ab1ff0cf52c3d349bd58d3121a309d4293bff09b0b91f3b\";a:4:{s:10:\"expiration\";i:1498571291;s:2:\"ip\";s:14:\"122.173.136.55\";s:2:\"ua\";s:102:\"Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36\";s:5:\"login\";i:1497361691;}}'),(15,1,'wp_dashboard_quick_press_last_post_id','583'),(16,1,'wp_user-settings','libraryContent=browse&editor=html&edit_element_vcUIPanelWidth=868&edit_element_vcUIPanelLeft=96px&edit_element_vcUIPanelTop=43px&hidetb=1&editor_plain_text_paste_warning=1'),(17,1,'wp_user-settings-time','1497422691'),(18,1,'managenav-menuscolumnshidden','a:5:{i:0;s:11:\"link-target\";i:1;s:11:\"css-classes\";i:2;s:3:\"xfn\";i:3;s:11:\"description\";i:4;s:15:\"title-attribute\";}'),(19,1,'metaboxhidden_nav-menus','a:2:{i:0;s:12:\"add-post_tag\";i:1;s:15:\"add-post_format\";}'),(20,1,'manageedit-shop_ordercolumnshidden','a:1:{i:0;s:15:\"billing_address\";}'),(22,1,'meta-box-order_page','a:3:{s:4:\"side\";s:36:\"submitdiv,pageparentdiv,postimagediv\";s:6:\"normal\";s:90:\"wpb_visual_composer,revisionsdiv,postcustom,commentstatusdiv,commentsdiv,slugdiv,authordiv\";s:8:\"advanced\";s:0:\"\";}'),(23,1,'screen_layout_page','2'),(24,1,'nav_menu_recently_edited','2'),(25,1,'closedpostboxes_product','a:0:{}'),(26,1,'metaboxhidden_product','a:0:{}'),(27,2,'nickname','rimpy.codedrill'),(28,2,'first_name','rimpy'),(29,2,'last_name','codedrill'),(30,2,'description',''),(31,2,'rich_editing','true'),(32,2,'comment_shortcuts','false'),(33,2,'admin_color','fresh'),(34,2,'use_ssl','0'),(35,2,'show_admin_bar_front','true'),(36,2,'locale',''),(37,2,'wp_capabilities','a:1:{s:8:\"customer\";b:1;}'),(38,2,'wp_user_level','0'),(41,2,'last_update','1482922190'),(42,2,'billing_first_name','rimpy'),(43,2,'billing_last_name','codedrill'),(44,2,'billing_company',''),(45,2,'billing_email','rimpy.codedrill@gmail.com'),(46,2,'billing_phone','7837511008'),(47,2,'billing_country','IN'),(48,2,'billing_address_1','8b mohali'),(49,2,'billing_address_2',''),(50,2,'billing_city','mohali'),(51,2,'billing_state','PB'),(52,2,'billing_postcode','160055'),(53,2,'shipping_first_name','rimpy'),(54,2,'shipping_last_name','codedrill'),(55,2,'shipping_company',''),(56,2,'shipping_country','IN'),(57,2,'shipping_address_1','8b mohali'),(58,2,'shipping_address_2',''),(59,2,'shipping_city','mohali'),(60,2,'shipping_state','PB'),(61,2,'shipping_postcode','160055'),(62,3,'nickname','rimpy'),(63,3,'first_name',''),(64,3,'last_name',''),(65,3,'description',''),(66,3,'rich_editing','true'),(67,3,'comment_shortcuts','false'),(68,3,'admin_color','fresh'),(69,3,'use_ssl','0'),(70,3,'show_admin_bar_front','true'),(71,3,'locale',''),(72,3,'wp_capabilities','a:1:{s:10:\"subscriber\";b:1;}'),(73,3,'wp_user_level','0'),(74,3,'dismissed_wp_pointers',''),(75,3,'rm_user_status','0'),(76,3,'RM_UMETA_FORM_ID','6'),(77,3,'RM_UMETA_SUB_ID','4'),(78,4,'nickname','rimpy258'),(79,4,'first_name',''),(80,4,'last_name',''),(81,4,'description',''),(82,4,'rich_editing','true'),(83,4,'comment_shortcuts','false'),(84,4,'admin_color','fresh'),(85,4,'use_ssl','0'),(86,4,'show_admin_bar_front','true'),(87,4,'locale',''),(88,4,'wp_capabilities','a:1:{s:10:\"subscriber\";b:1;}'),(89,4,'wp_user_level','0'),(90,4,'dismissed_wp_pointers',''),(91,4,'rm_user_status','0'),(92,4,'RM_UMETA_FORM_ID','6'),(93,4,'RM_UMETA_SUB_ID','5'),(95,4,'manageedit-shop_ordercolumnshidden','a:1:{i:0;s:15:\"billing_address\";}'),(96,5,'nickname','test'),(97,5,'first_name',''),(98,5,'last_name',''),(99,5,'description',''),(100,5,'rich_editing','true'),(101,5,'comment_shortcuts','false'),(102,5,'admin_color','fresh'),(103,5,'use_ssl','0'),(104,5,'show_admin_bar_front','true'),(105,5,'locale',''),(106,5,'wp_capabilities','a:1:{s:10:\"subscriber\";b:1;}'),(107,5,'wp_user_level','0'),(108,5,'dismissed_wp_pointers',''),(109,5,'rm_user_status','0'),(110,5,'RM_UMETA_FORM_ID','6'),(111,5,'RM_UMETA_SUB_ID','9'),(113,5,'manageedit-shop_ordercolumnshidden','a:1:{i:0;s:15:\"billing_address\";}'),(114,1,'account_status','approved'),(115,1,'role','admin'),(116,3,'account_status','approved'),(117,3,'role','member'),(118,2,'account_status','approved'),(119,2,'role','member'),(120,4,'account_status','approved'),(121,4,'role','member'),(122,5,'account_status','approved'),(123,5,'role','member'),(124,1,'wp_r_tru_u_x','a:2:{s:2:\"id\";i:0;s:7:\"expires\";i:1483343911;}'),(126,1,'um_user_profile_url_slug_user_login','admin'),(129,6,'nickname','chalk'),(130,6,'first_name','alona'),(131,6,'last_name','tumpalan'),(132,6,'description',''),(133,6,'rich_editing','true'),(134,6,'comment_shortcuts','false'),(135,6,'admin_color','fresh'),(136,6,'use_ssl','0'),(137,6,'show_admin_bar_front','true'),(138,6,'locale',''),(139,6,'wp_capabilities','a:1:{s:10:\"subscriber\";b:1;}'),(140,6,'wp_user_level','0'),(141,6,'dismissed_wp_pointers',''),(142,6,'role','member'),(143,6,'submitted','a:10:{s:7:\"form_id\";s:3:\"271\";s:9:\"timestamp\";s:10:\"1483604173\";s:7:\"request\";s:0:\"\";s:4:\"role\";s:6:\"member\";s:8:\"_wpnonce\";s:10:\"61040aa088\";s:16:\"_wp_http_referer\";s:10:\"/register/\";s:10:\"first_name\";s:5:\"alona\";s:9:\"last_name\";s:8:\"tumpalan\";s:10:\"user_login\";s:5:\"chalk\";s:10:\"user_email\";s:21:\"alona@imationsoft.com\";}'),(145,6,'last_update','1483604227'),(146,6,'um_user_profile_url_slug_user_login','chalk'),(147,6,'full_name','chalk'),(148,6,'reset_pass_hash','CelWZGqu8H7Kq6V66yWfqiWClqt1hkO3LlEY5erP'),(149,6,'account_status','approved'),(151,6,'manageedit-shop_ordercolumnshidden','a:1:{i:0;s:15:\"billing_address\";}'),(154,6,'session_tokens','a:1:{s:64:\"9313177891a181168a88ad6fb7f08debd14fef5cb11c8a5f4858c71317f8df7e\";a:4:{s:10:\"expiration\";i:1484814262;s:2:\"ip\";s:13:\"50.184.35.122\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36\";s:5:\"login\";i:1483604662;}}'),(155,6,'_um_last_login','1483604662'),(158,5,'um_user_profile_url_slug_user_login','test'),(159,4,'um_user_profile_url_slug_user_login','rimpy258'),(160,3,'um_user_profile_url_slug_user_login','rimpy'),(161,2,'um_user_profile_url_slug_user_login','rimpy-codedrill'),(164,1,'meta-box-order_product','a:3:{s:4:\"side\";s:109:\"submitdiv,product_catdiv,tagsdiv-product_tag,um-admin-access-settings,postimagediv,woocommerce-product-images\";s:6:\"normal\";s:55:\"woocommerce-product-data,postcustom,slugdiv,postexcerpt\";s:8:\"advanced\";s:0:\"\";}'),(165,1,'screen_layout_product','2'),(167,7,'nickname','hoomanshirian'),(168,7,'first_name','hooman'),(169,7,'last_name','shirian'),(170,7,'description',''),(171,7,'rich_editing','true'),(172,7,'comment_shortcuts','false'),(173,7,'admin_color','fresh'),(174,7,'use_ssl','0'),(175,7,'show_admin_bar_front','true'),(176,7,'locale',''),(177,7,'wp_capabilities','a:1:{s:8:\"customer\";b:1;}'),(178,7,'wp_user_level','0'),(179,7,'dismissed_wp_pointers',''),(182,7,'last_update','1489781760'),(183,7,'billing_first_name','hooman'),(184,7,'billing_last_name','shirian'),(185,7,'billing_company','usa'),(186,7,'billing_email','hoomanshirian@yahoo.com'),(187,7,'billing_phone','310-560-8770'),(188,7,'billing_country','US'),(189,7,'billing_address_1','7925 SANTA MONICA BLVD'),(190,7,'billing_address_2',''),(191,7,'billing_city','WEST HOLLYWOOD'),(192,7,'billing_state','CA'),(193,7,'billing_postcode','90046'),(194,7,'shipping_first_name','hooman'),(195,7,'shipping_last_name','shirian'),(196,7,'shipping_company','usa'),(197,7,'shipping_country','US'),(198,7,'shipping_address_1','7925 SANTA MONICA BLVD'),(199,7,'shipping_address_2',''),(200,7,'shipping_city','WEST HOLLYWOOD'),(201,7,'shipping_state','CA'),(202,7,'shipping_postcode','90046'),(203,7,'manageedit-shop_ordercolumnshidden','a:1:{i:0;s:15:\"billing_address\";}'),(221,7,'session_tokens','a:1:{s:64:\"47b6845f7eea52ec9fddc2f1148020fc48f2823f55408e1bc078ea341a65f955\";a:4:{s:10:\"expiration\";i:1490814164;s:2:\"ip\";s:14:\"173.198.50.234\";s:2:\"ua\";s:108:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\";s:5:\"login\";i:1489604564;}}'),(231,1,'closedpostboxes_dashboard','a:2:{i:0;s:18:\"dashboard_activity\";i:1;s:17:\"dashboard_primary\";}'),(232,1,'metaboxhidden_dashboard','a:0:{}'),(233,1,'um_account_secure_fields','a:0:{}'),(234,7,'_um_last_login','1489781693'),(235,7,'um_account_secure_fields','a:0:{}'),(236,7,'_woocommerce_persistent_cart','a:1:{s:4:\"cart\";a:1:{s:32:\"b1704f5bd52fa48b618b71053354e125\";a:19:{s:14:\"option_id-Size\";s:6:\"8.5x11\";s:15:\"option_id-Paper\";s:15:\"100# Gloss Text\";s:15:\"option_id-Color\";s:26:\"4/4 (Full Color Both Side)\";s:17:\"option_id-Coating\";s:31:\"AQ 2 Sides (Semi-Gloss Coating)\";s:20:\"option_id-Turnaround\";s:8:\"2-3 Days\";s:18:\"option_id-quantity\";s:4:\"1000\";s:15:\"option_id-proof\";s:15:\"No Proof Needed\";s:21:\"option_id-front_image\";s:21:\"No file was uploaded.\";s:20:\"option_id-back_image\";s:21:\"No file was uploaded.\";s:10:\"unique_key\";s:32:\"a47783b764940952e7ce8c577d615ea8\";s:10:\"product_id\";i:410;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:10:\"line_total\";d:159;s:8:\"line_tax\";i:0;s:13:\"line_subtotal\";d:159;s:17:\"line_subtotal_tax\";i:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}}}'),(241,1,'billing_first_name','asdf'),(242,1,'billing_last_name','asdf'),(243,1,'billing_company','asdsafasfsaf'),(244,1,'billing_email','pankaj.01sharma@gmail.com'),(245,1,'billing_phone','1231231231'),(246,1,'billing_country','IN'),(247,1,'billing_address_1','asdfasfaf'),(248,1,'billing_address_2','asdfasfasdf'),(249,1,'billing_city','asdfasf df'),(250,1,'billing_state','BR'),(251,1,'billing_postcode','123123'),(252,1,'shipping_first_name','asdf'),(253,1,'shipping_last_name','asdf'),(254,1,'shipping_company','asdsafasfsaf'),(255,1,'shipping_country','IN'),(256,1,'shipping_address_1','asdfasfaf'),(257,1,'shipping_address_2','asdfasfasdf'),(258,1,'shipping_city','asdfasf df'),(259,1,'shipping_state','BR'),(260,1,'shipping_postcode','123123'),(272,8,'nickname','hooman'),(273,8,'first_name','hooman'),(274,8,'last_name','shirian'),(275,8,'description',''),(276,8,'rich_editing','true'),(277,8,'comment_shortcuts','false'),(278,8,'admin_color','fresh'),(279,8,'use_ssl','0'),(280,8,'show_admin_bar_front','true'),(281,8,'locale',''),(282,8,'wp_capabilities','a:1:{s:8:\"customer\";b:1;}'),(283,8,'wp_user_level','0'),(284,8,'dismissed_wp_pointers',''),(285,8,'synced_gravatar_hashed_id','e6fc7a58e923aa101df1d392a11f6b55'),(288,8,'last_update','1496433001'),(289,8,'billing_first_name','hooman'),(290,8,'billing_last_name','shirian'),(291,8,'billing_company','usa printing'),(292,8,'billing_email','hooman@usaprintingtrade.com'),(293,8,'billing_phone','310-560-8770'),(294,8,'billing_country','US'),(295,8,'billing_address_1','7925 santa'),(296,8,'billing_address_2',''),(297,8,'billing_city','la'),(298,8,'billing_state','CA'),(299,8,'billing_postcode','90046'),(300,8,'shipping_first_name','hooman'),(301,8,'shipping_last_name','shirian'),(302,8,'shipping_company','usa printing'),(303,8,'shipping_country','US'),(304,8,'shipping_address_1','7925 santa'),(305,8,'shipping_address_2',''),(306,8,'shipping_city','la'),(307,8,'shipping_state','CA'),(308,8,'shipping_postcode','90046'),(309,8,'um_account_secure_fields','a:0:{}'),(310,8,'manageedit-shop_ordercolumnshidden','a:1:{i:0;s:15:\"billing_address\";}'),(311,8,'_woocommerce_persistent_cart','a:1:{s:4:\"cart\";a:0:{}}'),(315,1,'_um_last_login','1497361691'),(316,1,'wp_media_library_mode','list'),(317,8,'account_status','approved'),(318,7,'account_status','approved'),(319,8,'um_user_profile_url_slug_user_login','hooman'),(320,7,'um_user_profile_url_slug_user_login','hoomanshirian'),(321,2,'session_tokens','a:1:{s:64:\"556de67667436cfc9951efd22fd2ac2213af119c77983dc6a4dc2f0df30ba330\";a:4:{s:10:\"expiration\";i:1497601364;s:2:\"ip\";s:15:\"103.239.234.236\";s:2:\"ua\";s:133:\"Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/58.0.3029.110 Chrome/58.0.3029.110 Safari/537.36\";s:5:\"login\";i:1497428564;}}'),(322,2,'_um_last_login','1497428564'),(323,2,'um_account_secure_fields','a:0:{}'),(324,2,'manageedit-shop_ordercolumnshidden','a:1:{i:0;s:15:\"billing_address\";}'),(325,2,'_woocommerce_persistent_cart','a:1:{s:4:\"cart\";a:0:{}}'),(326,1,'closedpostboxes_wccpf','a:0:{}'),(327,1,'metaboxhidden_wccpf','a:3:{i:0;s:7:\"acf_461\";i:1;s:7:\"acf_471\";i:2;s:7:\"slugdiv\";}'),(329,1,'_woocommerce_persistent_cart','a:1:{s:4:\"cart\";a:1:{s:32:\"3ea1ed88da3221bc413ee9949dde7644\";a:19:{s:14:\"option_id-Size\";s:10:\"8.5\" x 11\"\";s:20:\"option_id-coverpaper\";s:68:\"14 pt. Gloss Coated Cover (C2S) with High Gloss AQ Coating All Sides\";s:15:\"option_id-Paper\";s:43:\"Recycled 80 lb. Dull Text with Matte Finish\";s:14:\"option_id-Page\";s:29:\"24 pages (4 cover, 20 inside)\";s:18:\"option_id-quantity\";s:3:\"100\";s:21:\"option_id-front_image\";s:103:\"<a href=\"http://138.68.54.230/wp-content/uploads/2017/06/youtube.jpg\" target=\"_blank\">Attached File</a>\";s:20:\"option_id-back_image\";s:101:\"<a href=\"http://138.68.54.230/wp-content/uploads/2017/06/cap11.jpg\" target=\"_blank\">Attached File</a>\";s:15:\"option_id-proof\";s:0:\"\";s:3:\"sku\";s:17:\"shortruncalendars\";s:10:\"unique_key\";s:32:\"fc7c1435aa84d5ae3c32a03b7929d2ed\";s:10:\"product_id\";i:389;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:1;s:10:\"line_total\";d:950.46000000000004;s:8:\"line_tax\";d:85.541399999999996;s:13:\"line_subtotal\";d:950.46000000000004;s:17:\"line_subtotal_tax\";d:85.541399999999996;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:1:{i:1;d:85.541399999999996;}s:8:\"subtotal\";a:1:{i:1;d:85.541399999999996;}}}}}');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

