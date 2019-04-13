# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.25)
# Database: dummy
# Generation Time: 2019-04-13 19:44:09 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `text` varchar(280) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_deleted` (`is_deleted`),
  KEY `user_id` (`user_id`,`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;

INSERT INTO `posts` (`id`, `user_id`, `user_name`, `text`, `created_at`, `updated_at`, `is_deleted`)
VALUES
	(1,1,'GDEHwsTdC','Hello everybody!','2019-04-12 15:53:36','2019-04-12 16:41:40',1),
	(2,1,'GDEHwsTdC','i change you','2019-04-12 16:10:09','2019-04-13 18:33:45',1),
	(3,1,'GDEHwsTdC','Hello everybody!12','2019-04-12 16:10:12','2019-04-13 18:33:46',1),
	(4,1,'GDEHwsTdC','Hello everybody!123','2019-04-12 16:10:14','2019-04-13 18:33:47',1),
	(5,1,'GDEHwsTdC','Hello everybody!123','2019-04-12 16:57:15','2019-04-12 16:58:19',1),
	(6,1,'GDEHwsTdC','Common!','2019-04-12 17:20:08','2019-04-13 18:33:54',1),
	(7,1,'GDEHwsTdC','I can see ypu!','2019-04-12 17:20:25','2019-04-13 18:33:57',1),
	(8,1,'GDEHwsTdC','I can feel you!','2019-04-12 17:20:35','2019-04-13 18:33:58',1),
	(9,2,'3LTBZoYOMf','hello mr. Anderson','2019-04-12 17:39:23','2019-04-13 18:34:47',1),
	(10,2,'3LTBZoYOMf','Ð´Ð»Ñ‹Ñ‹Ð²Ð°Ñ‹Ð²Ð° Ñ‹Ð²Ð°Ñ‹Ð´Ð²Ð°Ð¾ Ñ‹Ð»Ð²Ð´Ð°Ð¾Ñ‹Ð»Ð¾ Ð²Ð´Ñ‹Ð¾Ð² Ð´Ð°Ñ‹Ð¾Ð²Ð»Ð´Ð° Ð¾Ñ‹Ð´Ð²Ð°Ð¾ Ð´Ñ‹Ð²Ð¾ Ð°Ð´Ñ‹Ð¾Ð²Ð°Ð´Ð»Ñ‹Ð¾Ð²Ð´Ð°Ð»Ð¾Ñ‹Ð´Ð²Ð»Ð°Ð¾Ð´Ñ‹Ð²Ð°Ð»Ð¾Ñ‹Ð´Ð²Ð°Ð¾ Ð´Ñ‹Ð»Ð²Ð¾Ð°Ð´Ñ‹Ð»Ð²Ð»Ð¾Ð²Ñ„Ð´Ð»Ñ‹Ð¾Ð²Ð´ Ð¾Ñ„Ð´Ñ‹Ð²Ð»Ð¾ Ð´Ñ„Ð»Ñ‹Ð¾Ð²Ð´Ñ„Ð»Ð¾Ñ‹Ð´Ð² Ñ‹Ð²Ð°Ð° Ñ„Ñ‹Ð²','2019-04-12 17:50:19','2019-04-13 18:34:55',1),
	(11,2,'3LTBZoYOMf','new post','2019-04-13 18:10:15','2019-04-13 18:34:56',1),
	(12,2,'3LTBZoYOMf','new post #2','2019-04-13 18:14:40','2019-04-13 18:34:57',1),
	(13,2,'3LTBZoYOMf','new post #33','2019-04-13 18:37:42','2019-04-13 18:37:42',1),
	(14,2,'3LTBZoYOMf','new post #33','2019-04-13 18:38:44','2019-04-13 18:38:44',1),
	(15,2,'3LTBZoYOMf','new post #33','2019-04-13 18:40:45','2019-04-13 18:53:00',1),
	(16,2,'3LTBZoYOMf','new post #33','2019-04-13 18:41:20','2019-04-13 18:53:02',1),
	(17,2,'3LTBZoYOMf','new post #33','2019-04-13 18:41:29','2019-04-13 18:53:04',1),
	(18,2,'3LTBZoYOMf','new post #33','2019-04-13 18:41:32','2019-04-13 18:53:07',1),
	(19,2,'3LTBZoYOMf','new post #35','2019-04-13 18:41:47','2019-04-13 18:53:08',1),
	(20,2,'3LTBZoYOMf','new post #36','2019-04-13 18:42:37','2019-04-13 18:53:10',1),
	(21,2,'3LTBZoYOMf','test #1','2019-04-13 18:56:11','2019-04-13 18:56:11',0),
	(22,2,'3LTBZoYOMf','test #2','2019-04-13 18:58:05','2019-04-13 18:58:05',0),
	(23,2,'3LTBZoYOMf','test #3','2019-04-13 19:01:44','2019-04-13 19:01:44',0),
	(24,2,'3LTBZoYOMf','test #4','2019-04-13 19:02:03','2019-04-13 19:02:03',0),
	(25,2,'3LTBZoYOMf','test #5','2019-04-13 19:02:17','2019-04-13 19:02:17',0),
	(26,2,'3LTBZoYOMf','test #6','2019-04-13 19:02:40','2019-04-13 19:02:40',0),
	(27,2,'3LTBZoYOMf','test #7','2019-04-13 19:03:36','2019-04-13 19:03:36',0),
	(28,1,'GDEHwsTdC','Edited','2019-04-13 19:04:31','2019-04-13 19:05:09',0),
	(29,1,'GDEHwsTdC','User#1 more!','2019-04-13 19:09:57','2019-04-13 19:09:57',0),
	(30,1,'GDEHwsTdC','User#1 more 1!','2019-04-13 19:11:23','2019-04-13 19:11:23',0);

/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
