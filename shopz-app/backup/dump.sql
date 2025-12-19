-- MySQL dump
-- FLAG{sql_dump_found}
-- Host: localhost    Database: shopz
-- Server version: 5.7.42

CREATE TABLE `secrets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(100) DEFAULT NULL,
  `key_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `secrets` VALUES (1,'api_key','sk-live-abcd1234567890');
INSERT INTO `secrets` VALUES (2,'stripe_key','sk_test_xxxxxxxxxxxxx');
INSERT INTO `secrets` VALUES (3,'admin_backup_pass','backup_admin_2024!');

-- Dumped user passwords (MD5):
-- admin: 21232f297a57a5a743894a0e4a801fc3 (admin)
-- john_doe: 5f4dcc3b5aa765d61d8327deb882cf99 (password)
-- jane_smith: e99a18c428cb38d5f260853678922e03 (abc123)
-- test: 098f6bcd4621d373cade4e832627b4f6 (test)
