# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.25)
# Database: dummy
# Generation Time: 2019-04-12 15:12:57 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `access_tokens`;

CREATE TABLE `access_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token_hash` int(11) unsigned NOT NULL,
  `token` char(60) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `expire_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token_hash` (`token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `access_tokens` WRITE;
/*!40000 ALTER TABLE `access_tokens` DISABLE KEYS */;

INSERT INTO `access_tokens` (`id`, `token_hash`, `token`, `user_id`, `created_at`, `expire_at`)
VALUES
	(1,3157907568,'$2y$10$8kU8uy.EFLcM.HRG44jKpuPY0xgknbEs1P6mr7JYOIl6olEFt9Le.',1,NOW(),NOW() + INTERVAL 2 WEEK),
	(2,2769289852,'$2y$10$gnFd538LIfPklT8Rg09Nkuu8zoZX/pXmj8Nv/SnD.Q6HRar3m6AaS',2,NOW(),NOW() + INTERVAL 2 WEEK),
	(3,2180679879,'$2y$10$/2AcRkNQyUisPRC802FTke5.WFjJk/3.ne7NsfmSv6FgXlnVAjdk.',3,NOW(),NOW() + INTERVAL 2 WEEK),
	(4,3539549188,'$2y$10$O8pBRaNNErl02Nuei0u5q.WmKkbcBnJvqfrEq8AlMrVZO7Adi5hWe',4,NOW(),NOW() + INTERVAL 2 WEEK),
	(5,2916240606,'$2y$10$RP9QpR3yPt5u6.yAkMBqduT93gYx7oY/AT3kjORBeobCzSUvjZfNq',5,NOW(),NOW() + INTERVAL 2 WEEK),
	(6,4261035651,'$2y$10$/.pNaNTrBOIhwRtn0q4vROz9H0OiTWdfS9gl/QP47eDwAp/2X7.oi',6,NOW(),NOW() + INTERVAL 2 WEEK),
	(7,540231686,'$2y$10$ptRS40MBXgDO4xUKP086qOhSVH/Uf8W0PojsmMr5xZqv2xTb9wrIO',7,NOW(),NOW() + INTERVAL 2 WEEK),
	(8,3258321482,'$2y$10$McsHp1p03vnA0olrXOZsmulpTe0escHgxwFNy2ijXpIF1rCvSjU/.',8,NOW(),NOW() + INTERVAL 2 WEEK),
	(9,459894774,'$2y$10$LA3JsrRxNH8IMycLky.LDOD9AV8elU00u90/xVamQrQnHWQia2fAK',9,NOW(),NOW() + INTERVAL 2 WEEK),
	(10,456454227,'$2y$10$IPF.l.OeeQxjrUXKHZvt9.w5JVmA96ggxBdf.CSRQLWXPU0OWUo7i',10,NOW(),NOW() + INTERVAL 2 WEEK);

/*!40000 ALTER TABLE `access_tokens` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
