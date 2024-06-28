-- Adminer 4.8.1 MySQL 10.11.3-MariaDB-1:10.11.3+maria~ubu2004 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `answer`;
CREATE TABLE `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de la réponse',
  `question_id` int(11) DEFAULT NULL COMMENT 'clé étrangère qui lie la réponse à la question',
  `user_id` int(11) DEFAULT NULL COMMENT 'clé étrangère qui lie la réponse à l''utilisateur',
  `student_answer` longtext DEFAULT NULL COMMENT 'réponse d''un élève',
  PRIMARY KEY (`id`),
  KEY `IDX_DADD4A251E27F6BF` (`question_id`),
  KEY `IDX_DADD4A25A76ED395` (`user_id`),
  CONSTRAINT `FK_DADD4A251E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
  CONSTRAINT `FK_DADD4A25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240205130535',	'2024-02-05 13:05:47',	105),
('DoctrineMigrations\\Version20240205131313',	'2024-02-05 13:13:21',	17);

DROP TABLE IF EXISTS `exercise`;
CREATE TABLE `exercise` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de l''exercice',
  `title` varchar(100) NOT NULL COMMENT 'titre de l''exercice',
  `instruction` varchar(500) NOT NULL COMMENT 'consigne de l''exercice',
  `status` int(11) NOT NULL COMMENT 'statut de l''exercice (0:exercice créé, 1:exercice publié/à faire, 2:exercice fait)',
  `created_at` datetime NOT NULL COMMENT 'date de création de l''exercice',
  `published_at` datetime DEFAULT NULL COMMENT 'date de publication de l''exercice',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de la classe',
  `name` varchar(20) NOT NULL COMMENT 'nom de la classe',
  `level` varchar(20) NOT NULL COMMENT 'niveau de la classe',
  `description` varchar(255) DEFAULT NULL COMMENT 'description de la classe',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `group_exercise`;
CREATE TABLE `group_exercise` (
  `group_id` int(11) NOT NULL COMMENT 'clé étrangère qui lie une classe à un exercice',
  `exercise_id` int(11) NOT NULL COMMENT 'clé étrangère qui lie un exercice à une classe',
  PRIMARY KEY (`group_id`,`exercise_id`),
  KEY `IDX_A7EA9963FE54D947` (`group_id`),
  KEY `IDX_A7EA9963E934951A` (`exercise_id`),
  CONSTRAINT `FK_A7EA9963E934951A` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A7EA9963FE54D947` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
  `group_id` int(11) NOT NULL COMMENT 'clé étrangère qui lie une classe à un utilisateur',
  `user_id` int(11) NOT NULL COMMENT 'clé étrangère qui lie un utilisateur à une classe',
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `IDX_A4C98D39FE54D947` (`group_id`),
  KEY `IDX_A4C98D39A76ED395` (`user_id`),
  CONSTRAINT `FK_A4C98D39A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A4C98D39FE54D947` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant d''un message',
  `content` longtext NOT NULL COMMENT 'contenu d''un message',
  `created_at` datetime NOT NULL COMMENT 'date de création d''un message',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `message_user`;
CREATE TABLE `message_user` (
  `message_id` int(11) NOT NULL COMMENT 'clé étrangère qui lie un message à un utilisateur',
  `user_id` int(11) NOT NULL COMMENT 'clé étrangère qui lie un utilisateur à un message',
  PRIMARY KEY (`message_id`,`user_id`),
  KEY `IDX_24064D90537A1329` (`message_id`),
  KEY `IDX_24064D90A76ED395` (`user_id`),
  CONSTRAINT `FK_24064D90537A1329` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_24064D90A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant d''une question',
  `number` int(11) NOT NULL COMMENT 'numéro de la question',
  `content` varchar(255) NOT NULL COMMENT 'intitulé de la question',
  `teacher_answer` longtext NOT NULL COMMENT 'correction de la question par un enseignant',
  `exercise_id` int(11) DEFAULT NULL COMMENT 'clé étrangère qui lie la question à un exercice',
  PRIMARY KEY (`id`),
  KEY `IDX_B6F7494EE934951A` (`exercise_id`),
  CONSTRAINT `FK_B6F7494EE934951A` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant d''un utilisateur',
  `email` varchar(180) NOT NULL COMMENT 'email d''un utilisateur',
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'rôle d''un utilisateur',
  `password` varchar(255) NOT NULL COMMENT 'mot de passe d''un utilisateur',
  `firstname` varchar(20) NOT NULL COMMENT 'prénom d''un utilisateur',
  `lastname` varchar(20) NOT NULL COMMENT 'nom d''un utilisateur',
  `picture` varchar(2000) DEFAULT NULL COMMENT 'avatar de l''utilisateur',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2024-02-05 13:47:09