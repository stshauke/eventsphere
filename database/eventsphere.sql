-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 11 oct. 2024 à 11:39
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eventsphere`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240929150010', '2024-09-29 15:00:43', 187),
('DoctrineMigrations\\Version20240929152123', '2024-09-29 15:21:41', 241),
('DoctrineMigrations\\Version20240929202151', '2024-09-29 20:22:10', 132),
('DoctrineMigrations\\Version20241001190533', '2024-10-01 19:05:58', 274);

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_evenement` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_evenement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_evenement` datetime DEFAULT NULL,
  `lieu_evenement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_max_participants` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id`, `nom_evenement`, `description_evenement`, `date_evenement`, `lieu_evenement`, `nb_max_participants`) VALUES
(1, 'Robotique', 'conférence sur la robotique', '2024-10-02 23:16:00', 'Paris', 5),
(2, 'Coloc', 'évènement sur le multiculturel', '2024-10-02 23:16:00', 'Londres', 9),
(3, 'Marketting', 'Évènement crée par notre école', '2024-10-25 19:54:00', 'France', 100),
(7, 'Exemple 1', 'Exemple 1Exemple 1', '2024-10-24 21:05:00', 'Lille', 3);

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE IF NOT EXISTS `inscription` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `evenement_id` int NOT NULL,
  `date_inscription` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E90F6D6A76ED395` (`user_id`),
  KEY `IDX_5E90F6D6FD02F13` (`evenement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id`, `user_id`, `evenement_id`, `date_inscription`) VALUES
(1, 2, 3, '2024-10-10 22:01:00'),
(2, 3, 2, '2024-10-25 11:12:00'),
(3, 4, 7, '2024-10-31 23:49:00'),
(4, 1, 1, '2024-10-18 21:03:00');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`) VALUES
(1, 'tshaukemulumba@yahoo.com', '[\"ROLE_ADMIN\"]', '$2y$13$p0AW3jLzfH7MjjbixvNv7u0rY7hkd/XKqCmbx8Ic1zpXSbzUNNYpK', 'Salomon'),
(2, 'alexandrejoyce@yahoo.com', '[]', '$2y$13$7wRwMPf7NpPw.9jM9QpjPuPaXO70F/0TUqaM7GonZm7icB1se3gIq', 'Alexander'),
(3, 'hajjriadh@gmail.com', '[]', '$2y$13$r167FJbBVLy7xqqJ41cRq.xYXY4WxIVG2CQIaqFue0JBtNG9xk25K', 'Hajji'),
(4, 'contact@idia-tech.com', '[]', '$2y$13$PP1mZrFlPIrrkHtz7cB46uqIf85ujoJAebrZTznbxJlSL7aXIyeTC', 'Mounir');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `FK_5E90F6D6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_5E90F6D6FD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
