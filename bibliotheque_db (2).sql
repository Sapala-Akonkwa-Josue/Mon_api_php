-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 10 mars 2025 à 13:24
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bibliotheque_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE `auteur` (
  `id_auteur` int(11) NOT NULL,
  `nom_auteur` varchar(100) NOT NULL,
  `prenom_auteur` varchar(100) DEFAULT NULL,
  `id_nationalite` int(11) DEFAULT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `auteur`
--

INSERT INTO `auteur` (`id_auteur`, `nom_auteur`, `prenom_auteur`, `id_nationalite`, `image`) VALUES
(6, 'Jean-pierre', 'de ROUSSEAU', 7, 'images/auteurs/hero.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`) VALUES
(5, 'Theatre'),
(6, 'Blague'),
(7, 'Poeme');

-- --------------------------------------------------------

--
-- Structure de la table `nationalite`
--

CREATE TABLE `nationalite` (
  `id_nationalite` int(11) NOT NULL,
  `nom_nationalite` varchar(100) NOT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `nationalite`
--

INSERT INTO `nationalite` (`id_nationalite`, `nom_nationalite`, `image`) VALUES
(6, 'Anglaise', 'images/nationalites/profil0.jpg'),
(7, 'Libanais', 'images/nationalites/profil3.jpg'),
(8, 'Chinois', 'images/nationalites/profil2.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `ouvrage`
--

CREATE TABLE `ouvrage` (
  `id_ouvrage` int(11) NOT NULL,
  `titre_ouvrage` varchar(255) NOT NULL,
  `id_auteur` int(11) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `annee_publication` year(4) DEFAULT year(curdate()),
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ouvrage`
--

INSERT INTO `ouvrage` (`id_ouvrage`, `titre_ouvrage`, `id_auteur`, `id_categorie`, `annee_publication`, `image`) VALUES
(4, 'J\'ai epouse une religieuse', 6, 7, '1999', 'images/ouvrages/her.jpg'),
(5, 'J\'ai epouse une religieuse', 6, 7, NULL, 'images/ouvrages/her.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_nationalite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `username`, `prenom`, `email`, `password`, `bio`, `image`, `date_inscription`, `id_nationalite`) VALUES
(5, 'Sapala', 'josue', 'sapala@gmail.com', '$2y$10$HtEpfGss8mzzlcVwXoWpEuNj4127V3ev9yPScoB88og2EQF.3FRwu', 'Une histoire nourrit ma sante mais une contes me forme mentalement.', 'images/users/client1.jpg', '2025-03-07 12:39:46', 8),
(8, 'Iragi', 'Hervey', 'iragi@gmail.com', '$2y$10$8KrfViJyIpgroKBrkbciV./P.SvyvkJfCUMirx4P.6Id2jgdFFvEa', 'L\'afrique ecrira sa propre histoire, je suis conscient.', 'images/users/team2.jpg', '2025-03-07 13:09:11', 7),
(9, 'thierry', 'nirere', 'nirer@gmail.com', '$2y$10$/Crz7MzlQ2toRGtknltii.Ib1GthdJ6FlkUIsu89h.sXuwVxByP9K', 'l\'afrique ecrira sa propre histoire', 'images/users/team3.jpg', '2025-03-08 11:12:07', 7);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `auteur`
--
ALTER TABLE `auteur`
  ADD PRIMARY KEY (`id_auteur`),
  ADD KEY `id_nationalite` (`id_nationalite`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `nationalite`
--
ALTER TABLE `nationalite`
  ADD PRIMARY KEY (`id_nationalite`);

--
-- Index pour la table `ouvrage`
--
ALTER TABLE `ouvrage`
  ADD PRIMARY KEY (`id_ouvrage`),
  ADD KEY `id_auteur` (`id_auteur`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_nationalite` (`id_nationalite`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `auteur`
--
ALTER TABLE `auteur`
  MODIFY `id_auteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `nationalite`
--
ALTER TABLE `nationalite`
  MODIFY `id_nationalite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `ouvrage`
--
ALTER TABLE `ouvrage`
  MODIFY `id_ouvrage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `auteur`
--
ALTER TABLE `auteur`
  ADD CONSTRAINT `auteur_ibfk_1` FOREIGN KEY (`id_nationalite`) REFERENCES `nationalite` (`id_nationalite`);

--
-- Contraintes pour la table `ouvrage`
--
ALTER TABLE `ouvrage`
  ADD CONSTRAINT `ouvrage_ibfk_1` FOREIGN KEY (`id_auteur`) REFERENCES `auteur` (`id_auteur`),
  ADD CONSTRAINT `ouvrage_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_nationalite`) REFERENCES `nationalite` (`id_nationalite`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
