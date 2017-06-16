
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
DROP TABLE IF EXISTS `wp_rm_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_rm_forms` (
  `form_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `form_name` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_type` int(6) DEFAULT NULL,
  `form_user_role` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_user_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_should_send_email` tinyint(1) DEFAULT NULL,
  `form_redirect` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_redirect_to_page` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_redirect_to_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_should_auto_expire` tinyint(1) DEFAULT NULL,
  `form_options` text COLLATE utf8mb4_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(6) DEFAULT NULL,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_rm_forms` WRITE;
/*!40000 ALTER TABLE `wp_rm_forms` DISABLE KEYS */;
INSERT INTO `wp_rm_forms` VALUES (1,'Sample Contact Form',0,NULL,NULL,1,'none','0',NULL,NULL,'O:8:\"stdClass\":59:{s:13:\"hide_username\";N;s:23:\"form_is_opt_in_checkbox\";N;s:19:\"mailchimp_relations\";N;s:16:\"form_opt_in_text\";N;s:21:\"form_should_user_pick\";N;s:20:\"form_is_unique_token\";N;s:16:\"form_description\";s:202:\"A standard contact form to get your started right away with RegistrationMagic. This form has Name, Phone No., Email and Message fields. To add this form to a page or post, use shortcode [rm_form ID=\"1\"]\";s:21:\"form_user_field_label\";N;s:16:\"form_custom_text\";s:45:\"Please fill out the form below to contact us.\";s:20:\"form_success_message\";s:69:\"Thank you! We have received your message and will reply back shortly.\";s:18:\"form_email_subject\";s:29:\"We have received your message\";s:18:\"form_email_content\";s:411:\"Dear {{Textbox_1234}},\r\n\r\nThis is a confirmation of the message you submitted through our site. We shall get back to you soon.\r\n\r\nFor your reference, below is a copy of your message. If any information is incorrect, please submit the form again with correct information.\r\n\r\nThank you!\r\n\r\nYour Name: {{Textbox_1234}}\r\n\r\nYour Phone: {{Number_1235}}\r\n\r\nYour Email: {{Email_1233}}\r\n\r\nMessage: {{Textarea_1236}}\";s:21:\"form_submit_btn_label\";s:4:\"Send\";s:21:\"form_submit_btn_color\";N;s:25:\"form_submit_btn_bck_color\";N;s:15:\"form_expired_by\";N;s:22:\"form_submissions_limit\";N;s:16:\"form_expiry_date\";N;s:25:\"form_message_after_expiry\";N;s:14:\"mailchimp_list\";N;s:22:\"mailchimp_mapped_email\";N;s:27:\"mailchimp_mapped_first_name\";N;s:26:\"mailchimp_mapped_last_name\";N;s:25:\"should_export_submissions\";i:0;s:25:\"export_submissions_to_url\";N;s:10:\"form_pages\";N;s:14:\"access_control\";N;s:14:\"style_btnfield\";N;s:10:\"style_form\";N;s:15:\"style_textfield\";N;s:10:\"auto_login\";N;s:12:\"cc_relations\";N;s:7:\"cc_list\";N;s:19:\"form_opt_in_text_cc\";N;s:26:\"form_is_opt_in_checkbox_cc\";N;s:12:\"aw_relations\";N;s:7:\"aw_list\";N;s:19:\"form_opt_in_text_aw\";N;s:26:\"form_is_opt_in_checkbox_aw\";N;s:14:\"enable_captcha\";s:7:\"default\";s:16:\"enable_mailchimp\";N;s:15:\"enable_ccontact\";N;s:13:\"enable_aweber\";N;s:20:\"display_progress_bar\";s:7:\"default\";s:18:\"sub_limit_antispam\";N;s:15:\"placeholder_css\";N;s:15:\"btn_hover_color\";N;s:20:\"field_bg_focus_color\";N;s:16:\"text_focus_color\";N;s:13:\"style_section\";N;s:11:\"style_label\";N;s:18:\"post_expiry_action\";N;s:19:\"post_expiry_form_id\";N;s:14:\"no_prev_button\";i:1;s:18:\"user_auto_approval\";s:7:\"default\";s:25:\"form_opt_in_default_state\";N;s:28:\"form_opt_in_default_state_cc\";N;s:28:\"form_opt_in_default_state_aw\";N;s:18:\"ordered_form_pages\";N;}','2016-12-15 06:31:04',1,'2016-12-15 06:51:04',1),(2,'Sample Registration Form',1,'a:0:{}','subscriber',1,'none','0',NULL,NULL,'O:8:\"stdClass\":59:{s:13:\"hide_username\";i:0;s:23:\"form_is_opt_in_checkbox\";N;s:19:\"mailchimp_relations\";N;s:16:\"form_opt_in_text\";N;s:21:\"form_should_user_pick\";N;s:20:\"form_is_unique_token\";N;s:16:\"form_description\";s:415:\"This is a sample registration form that can be used to register users on your WordPress site. The form includes Username, Password, First Name, Last Name, Email, Website and Terms and Conditions fields. Feel free to edit them, remove them or add new ones as it suits your needs.\r\n\r\nPlease note, T&C field currently has dummy text. You will need to paste actual text of your terms and condition by editing the field.\";s:21:\"form_user_field_label\";s:0:\"\";s:16:\"form_custom_text\";s:48:\"Register with us by filling out the form below.\";s:20:\"form_success_message\";s:105:\"Thank you for registering with us! Once your account is active, we\'ll send you an email with the details.\";s:18:\"form_email_subject\";s:10:\"Thank you!\";s:18:\"form_email_content\";s:183:\"Hello {{Fname_1238}},\r\n\r\nThank you for registering with us. You will soon receive an account activation email. After that you can log into our website through login page.\r\n\r\nRegards.\";s:21:\"form_submit_btn_label\";s:0:\"\";s:21:\"form_submit_btn_color\";N;s:25:\"form_submit_btn_bck_color\";N;s:15:\"form_expired_by\";N;s:22:\"form_submissions_limit\";N;s:16:\"form_expiry_date\";N;s:25:\"form_message_after_expiry\";N;s:14:\"mailchimp_list\";N;s:22:\"mailchimp_mapped_email\";N;s:27:\"mailchimp_mapped_first_name\";N;s:26:\"mailchimp_mapped_last_name\";N;s:25:\"should_export_submissions\";i:0;s:25:\"export_submissions_to_url\";N;s:10:\"form_pages\";N;s:14:\"access_control\";N;s:14:\"style_btnfield\";s:0:\"\";s:10:\"style_form\";s:0:\"\";s:15:\"style_textfield\";s:0:\"\";s:10:\"auto_login\";N;s:12:\"cc_relations\";N;s:7:\"cc_list\";N;s:19:\"form_opt_in_text_cc\";N;s:26:\"form_is_opt_in_checkbox_cc\";N;s:12:\"aw_relations\";N;s:7:\"aw_list\";N;s:19:\"form_opt_in_text_aw\";N;s:26:\"form_is_opt_in_checkbox_aw\";N;s:14:\"enable_captcha\";s:7:\"default\";s:16:\"enable_mailchimp\";N;s:15:\"enable_ccontact\";N;s:13:\"enable_aweber\";N;s:20:\"display_progress_bar\";s:7:\"default\";s:18:\"sub_limit_antispam\";N;s:15:\"placeholder_css\";s:0:\"\";s:15:\"btn_hover_color\";s:0:\"\";s:20:\"field_bg_focus_color\";s:0:\"\";s:16:\"text_focus_color\";s:0:\"\";s:13:\"style_section\";s:0:\"\";s:11:\"style_label\";s:0:\"\";s:18:\"post_expiry_action\";N;s:19:\"post_expiry_form_id\";N;s:14:\"no_prev_button\";i:1;s:18:\"user_auto_approval\";s:7:\"default\";s:25:\"form_opt_in_default_state\";N;s:28:\"form_opt_in_default_state_cc\";N;s:28:\"form_opt_in_default_state_aw\";N;s:18:\"ordered_form_pages\";N;}','2016-12-15 07:19:35',1,'2016-12-15 09:16:52',1),(6,'Registration Form',1,'a:0:{}','subscriber',NULL,'none','6','',NULL,'O:8:\"stdClass\":53:{s:23:\"form_is_opt_in_checkbox\";i:1;s:19:\"mailchimp_relations\";O:8:\"stdClass\":0:{}s:16:\"form_opt_in_text\";s:22:\"Sign up for Newsletter\";s:21:\"form_should_user_pick\";N;s:20:\"form_is_unique_token\";N;s:16:\"form_description\";s:20:\"Wp Registration Form\";s:21:\"form_user_field_label\";s:0:\"\";s:16:\"form_custom_text\";s:49:\"<h2 class=\"register-class\">Create an Account</h2>\";s:20:\"form_success_message\";s:0:\"\";s:18:\"form_email_subject\";N;s:18:\"form_email_content\";N;s:21:\"form_submit_btn_label\";s:6:\"Submit\";s:21:\"form_submit_btn_color\";N;s:25:\"form_submit_btn_bck_color\";N;s:15:\"form_expired_by\";N;s:22:\"form_submissions_limit\";N;s:16:\"form_expiry_date\";N;s:25:\"form_message_after_expiry\";N;s:14:\"mailchimp_list\";s:10:\"ecf484a1b2\";s:22:\"mailchimp_mapped_email\";s:8:\"Email_16\";s:27:\"mailchimp_mapped_first_name\";N;s:26:\"mailchimp_mapped_last_name\";N;s:25:\"should_export_submissions\";N;s:25:\"export_submissions_to_url\";N;s:10:\"form_pages\";N;s:14:\"access_control\";N;s:14:\"style_btnfield\";s:0:\"\";s:10:\"style_form\";s:0:\"\";s:15:\"style_textfield\";s:0:\"\";s:10:\"auto_login\";i:1;s:12:\"cc_relations\";N;s:7:\"cc_list\";N;s:19:\"form_opt_in_text_cc\";N;s:26:\"form_is_opt_in_checkbox_cc\";N;s:12:\"aw_relations\";N;s:7:\"aw_list\";N;s:19:\"form_opt_in_text_aw\";N;s:26:\"form_is_opt_in_checkbox_aw\";N;s:14:\"enable_captcha\";N;s:16:\"enable_mailchimp\";N;s:15:\"enable_ccontact\";N;s:13:\"enable_aweber\";N;s:20:\"display_progress_bar\";N;s:18:\"sub_limit_antispam\";N;s:15:\"placeholder_css\";s:0:\"\";s:15:\"btn_hover_color\";N;s:20:\"field_bg_focus_color\";N;s:16:\"text_focus_color\";N;s:13:\"style_section\";N;s:11:\"style_label\";N;s:18:\"post_expiry_action\";N;s:19:\"post_expiry_form_id\";N;s:25:\"form_opt_in_default_state\";s:9:\"Unchecked\";}','2016-12-28 13:06:33',1,'2016-12-29 09:36:32',1);
/*!40000 ALTER TABLE `wp_rm_forms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
