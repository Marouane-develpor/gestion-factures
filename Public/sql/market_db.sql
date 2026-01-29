-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 29 jan. 2026 à 23:33
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `market_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `idArticle` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `prix` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idArticle`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`idArticle`, `reference`, `nom`, `description`, `prix`, `stock`, `created_at`) VALUES
(1, 'ART-001', 'Ordinateur Portable', 'PC Portable 15 pouces, 8GB RAM, 256GB SSD', 4500.00, 20, '2026-01-29 15:50:48'),
(2, 'ART-002', 'Imprimante Laser', 'Imprimante Noir et Blanc, 20ppm', 1200.00, 15, '2026-01-29 15:50:48'),
(3, 'ART-003', 'Smartphone', 'Smartphone 128GB, 6.5 pouces', 2800.00, 30, '2026-01-29 15:50:48'),
(4, 'ART-004', 'Tablette', 'Tablette 10 pouces, 64GB', 1800.00, 25, '2026-01-29 15:50:48'),
(5, 'ART-005', 'Écran 24 pouces', 'Écran LED Full HD', 900.00, 18, '2026-01-29 15:50:48'),
(6, 'ART-006', 'Clavier Souris', 'Clavier et souris sans fil', 350.00, 40, '2026-01-29 15:50:48'),
(7, 'ART-007', 'Disque Dur 1TB', 'Disque dur externe USB 3.0', 600.00, 22, '2026-01-29 15:50:48'),
(8, 'ART-008', 'Routeur WiFi', 'Routeur dual band 300Mbps', 450.00, 35, '2026-01-29 15:50:48');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `idClient` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`idClient`, `nom`, `prenom`, `email`, `telephone`, `ville`, `created_at`) VALUES
(1, 'Alami', 'Ahmed', 'ahmed.alami@email.com', '0612345678', 'Casablanca', '2026-01-29 15:50:48'),
(2, 'Benjelloun', 'Fatima', 'fatima.benjelloun@email.com', '0623456789', 'Rabat', '2026-01-29 15:50:48'),
(3, 'El Fassi', 'Karim', 'karim.elfassi@email.com', '0634567890', 'Marrakech', '2026-01-29 15:50:48'),
(4, 'Idrissi', 'Samira', 'samira.idrissi@email.com', '0645678901', 'Fès', '2026-01-29 15:50:48'),
(5, 'Ouazzani', 'Youssef', 'youssef.ouazzani@email.com', '0656789012', 'Tanger', '2026-01-29 15:50:48'),
(6, 'NITCHI', 'AKAI', 'obitochi307@gmail.com', '0700000000', 'ERRACHIDIA', '2026-01-29 22:12:55');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

DROP TABLE IF EXISTS `factures`;
CREATE TABLE IF NOT EXISTS `factures` (
  `idFacture` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_facture` date NOT NULL,
  `idClient` int NOT NULL,
  `montant_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `statut` enum('réglée','non réglée') COLLATE utf8mb4_unicode_ci DEFAULT 'non réglée',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idFacture`),
  UNIQUE KEY `numero` (`numero`),
  KEY `idClient` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`idFacture`, `numero`, `date_facture`, `idClient`, `montant_total`, `statut`, `created_at`) VALUES
(1, 'FACT-2024001', '2024-01-15', 1, 5300.00, 'réglée', '2026-01-29 15:50:48'),
(2, 'FACT-2024002', '2024-01-20', 2, 2800.00, 'non réglée', '2026-01-29 15:50:48'),
(3, 'FACT-2024003', '2024-02-05', 3, 12500.00, 'réglée', '2026-01-29 15:50:48'),
(4, 'FACT-2024004', '2024-02-10', 1, 3500.00, 'réglée', '2026-01-29 15:50:48'),
(5, 'FACT-2024005', '2024-02-15', 4, 900.00, 'non réglée', '2026-01-29 15:50:48'),
(6, 'FACT-20260129-221306', '2026-01-29', 6, 551000.00, 'non réglée', '2026-01-29 22:14:32');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_facture`
--

DROP TABLE IF EXISTS `ligne_facture`;
CREATE TABLE IF NOT EXISTS `ligne_facture` (
  `idLigneFacture` int NOT NULL AUTO_INCREMENT,
  `idFacture` int NOT NULL,
  `idArticle` int NOT NULL,
  `quantite` int NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idLigneFacture`),
  KEY `idFacture` (`idFacture`),
  KEY `idArticle` (`idArticle`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ligne_facture`
--

INSERT INTO `ligne_facture` (`idLigneFacture`, `idFacture`, `idArticle`, `quantite`, `prix`, `montant`) VALUES
(1, 1, 1, 1, 4500.00, 4500.00),
(2, 1, 6, 2, 350.00, 700.00),
(3, 2, 3, 1, 2800.00, 2800.00),
(4, 3, 1, 2, 4500.00, 9000.00),
(5, 3, 4, 1, 1800.00, 1800.00),
(6, 3, 7, 1, 600.00, 600.00),
(7, 3, 8, 2, 450.00, 900.00),
(8, 4, 5, 1, 900.00, 900.00),
(9, 4, 6, 4, 350.00, 1400.00),
(10, 4, 8, 2, 450.00, 900.00),
(11, 5, 5, 1, 900.00, 900.00),
(12, 6, 3, 120, 2800.00, 336000.00),
(13, 6, 4, 100, 1800.00, 180000.00),
(14, 6, 6, 100, 350.00, 35000.00);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `clients` (`idClient`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD CONSTRAINT `ligne_facture_ibfk_1` FOREIGN KEY (`idFacture`) REFERENCES `factures` (`idFacture`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_facture_ibfk_2` FOREIGN KEY (`idArticle`) REFERENCES `articles` (`idArticle`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
