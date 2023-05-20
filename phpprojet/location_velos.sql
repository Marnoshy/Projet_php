-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : sam. 20 mai 2023 à 12:53
-- Version du serveur : 5.7.39
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `location_velos`
--

-- --------------------------------------------------------

--
-- Structure de la table `bikes`
--

CREATE TABLE `bikes` (
  `velos_id` int(11) NOT NULL,
  `velos_nom` varchar(255) DEFAULT NULL,
  `velos_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `bikes`
--

INSERT INTO `bikes` (`velos_id`, `velos_nom`, `velos_image`) VALUES
(1, 'Vélo de VTT - Sport', 'photovelos/VTTSport.jpeg'),
(2, 'Vélo de randonnée', 'photovelos/vtc-randonnee.jpeg'),
(3, 'Vélo de ville', 'photovelos/velodeville.png'),
(4, 'Vélo électrique', 'photovelos/vtcelectrique.jpeg'),
(5, 'Vélo de ville / Confort ', 'photovelos/velodeville2.png');

-- --------------------------------------------------------

--
-- Structure de la table `rentals`
--

CREATE TABLE `rentals` (
  `location_id` int(11) NOT NULL,
  `velos_id` int(11) DEFAULT NULL,
  `location_start_date` datetime DEFAULT NULL,
  `location_end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rentals`
--

INSERT INTO `rentals` (`location_id`, `velos_id`, `location_start_date`, `location_end_date`) VALUES
(1, 1, '2023-05-01 10:00:00', '2023-05-23 12:00:00'),
(2, 2, '2023-05-02 10:00:00', '2023-05-12 12:00:00'),
(3, 3, '2023-05-03 10:00:00', '2023-05-10 12:00:00'),
(4, 4, '2023-05-04 10:00:00', '2023-05-23 00:00:00'),
(5, 5, '2023-05-05 10:00:00', '2023-05-10 12:00:00'),
(6, 1, '2023-05-27 00:00:00', '2023-05-31 00:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bikes`
--
ALTER TABLE `bikes`
  ADD PRIMARY KEY (`velos_id`);

--
-- Index pour la table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `velos_id` (`velos_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bikes`
--
ALTER TABLE `bikes`
  MODIFY `velos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`velos_id`) REFERENCES `bikes` (`velos_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
