-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for vaja1
DROP DATABASE IF EXISTS `vaja1`;
CREATE DATABASE IF NOT EXISTS `vaja1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `vaja1`;

-- Dumping structure for table vaja1.ads
DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text,
  `description` text,
  `lastUpdate` date DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `image` text,
  `views` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table vaja1.ads: ~4 rows (approximately)
DELETE FROM `ads`;
INSERT INTO `ads` (`id`, `title`, `description`, `lastUpdate`, `user_id`, `image`, `views`) VALUES
	(8, 'nov', 'nov', '2023-03-21', 1, 'images/822505.jpg', 31),
	(9, 'nov1', 'Ja', '2023-03-20', 1, 'images/822505.jpg', 11),
	(10, 'latest', 'ajah', '2023-03-20', 1, 'images/822505.jpg', 5),
	(11, 'adad', 'ad', '2023-03-20', 1, 'images/822505.jpg', 2);

-- Dumping structure for table vaja1.ads_categories
DROP TABLE IF EXISTS `ads_categories`;
CREATE TABLE IF NOT EXISTS `ads_categories` (
  `fk_idAds` int DEFAULT NULL,
  `fk_idCategory` int DEFAULT NULL,
  KEY `fk_idAds` (`fk_idAds`),
  KEY `fk_idCategory` (`fk_idCategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table vaja1.ads_categories: ~17 rows (approximately)
DELETE FROM `ads_categories`;
INSERT INTO `ads_categories` (`fk_idAds`, `fk_idCategory`) VALUES
	(6, 1),
	(6, 2),
	(5, 1),
	(5, 2),
	(5, 3),
	(7, 1),
	(7, 2),
	(9, 1),
	(9, 2),
	(9, 3),
	(8, 1),
	(8, 2),
	(11, 1),
	(11, 2),
	(10, 1),
	(10, 2),
	(10, 3);

-- Dumping structure for table vaja1.category
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `idCat` int NOT NULL AUTO_INCREMENT,
  `value` text NOT NULL,
  PRIMARY KEY (`idCat`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table vaja1.category: ~3 rows (approximately)
DELETE FROM `category`;
INSERT INTO `category` (`idCat`, `value`) VALUES
	(1, 'avto-moto'),
	(2, 'računalništvo'),
	(3, 'dom');

-- Dumping structure for table vaja1.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` text,
  `password` text,
  `naslov` text,
  `posta` int DEFAULT NULL,
  `tel` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table vaja1.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `password`, `naslov`, `posta`, `tel`) VALUES
	(1, 'viktordenac', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Maribor', 2000, 70774525),
	(2, 'denacviktor', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Maribor', 2000, 70774525);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
