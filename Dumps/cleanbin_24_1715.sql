-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2016 at 05:15 
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cleanbin`
--

-- --------------------------------------------------------

--
-- Table structure for table `camion`
--

CREATE TABLE `camion` (
  `Id` int(6) NOT NULL,
  `Peso_max` int(6) NOT NULL,
  `Peso_attuale` int(6) NOT NULL,
  `Serbatoio` int(6) NOT NULL,
  `Tipologia_id` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cassonetti`
--

CREATE TABLE `cassonetti` (
  `Id` int(11) NOT NULL,
  `Peso_max` int(4) NOT NULL,
  `Peso_attuale` int(4) NOT NULL,
  `Volume` int(4) NOT NULL,
  `Colore` varchar(20) NOT NULL,
  `Tipologia_id` int(2) DEFAULT NULL,
  `Clusters_id` int(11) DEFAULT NULL,
  `Priorita` int(11) NOT NULL,
  `DaSostituire` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clusters`
--

CREATE TABLE `clusters` (
  `Id` int(11) NOT NULL,
  `Latitudine` float(10,6) NOT NULL,
  `Longitudine` float(10,6) NOT NULL,
  `Via` varchar(70) NOT NULL,
  `NroCivico` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `percorsi`
--

CREATE TABLE `percorsi` (
  `Id` int(11) NOT NULL,
  `Data` date NOT NULL,
  `Cluster_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tipologie`
--

CREATE TABLE `tipologie` (
  `Id` int(2) NOT NULL,
  `Tipo` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `turni`
--

CREATE TABLE `turni` (
  `Utente_id` int(6) NOT NULL,
  `Percorso_id` int(11) NOT NULL,
  `Cluster_id` int(11) NOT NULL,
  `Camion_id` int(11) DEFAULT NULL,
  `Percorso_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--

CREATE TABLE `utenti` (
  `Id` int(6) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Role` varchar(20) NOT NULL,
  `Password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `camion`
--
ALTER TABLE `camion`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Tipologia_perCamion` (`Tipologia_id`);

--
-- Indexes for table `cassonetti`
--
ALTER TABLE `cassonetti`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Tipologie_perCassonetti` (`Tipologia_id`),
  ADD KEY `fk_Clusters_perCassonetti` (`Clusters_id`);

--
-- Indexes for table `clusters`
--
ALTER TABLE `clusters`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `percorsi`
--
ALTER TABLE `percorsi`
  ADD PRIMARY KEY (`Id`,`Data`,`Cluster_id`),
  ADD KEY `fk_Cluster_perPercorsi` (`Cluster_id`);

--
-- Indexes for table `tipologie`
--
ALTER TABLE `tipologie`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `turni`
--
ALTER TABLE `turni`
  ADD PRIMARY KEY (`Utente_id`,`Percorso_id`,`Cluster_id`,`Percorso_data`),
  ADD KEY `fk_Camion_perTurni` (`Camion_id`),
  ADD KEY `fk_Percorso_perTurni` (`Percorso_id`,`Cluster_id`),
  ADD KEY `fk_Percorsi_perTurni` (`Percorso_id`,`Percorso_data`,`Cluster_id`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `camion`
--
ALTER TABLE `camion`
  MODIFY `Id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cassonetti`
--
ALTER TABLE `cassonetti`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `clusters`
--
ALTER TABLE `clusters`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `percorsi`
--
ALTER TABLE `percorsi`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tipologie`
--
ALTER TABLE `tipologie`
  MODIFY `Id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `utenti`
--
ALTER TABLE `utenti`
  MODIFY `Id` int(6) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `camion`
--
ALTER TABLE `camion`
  ADD CONSTRAINT `fk_Tipologia_perCamion` FOREIGN KEY (`Tipologia_id`) REFERENCES `tipologie` (`Id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `cassonetti`
--
ALTER TABLE `cassonetti`
  ADD CONSTRAINT `fk_Clusters_perCassonetti` FOREIGN KEY (`Clusters_id`) REFERENCES `clusters` (`Id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Tipologie_perCassonetti` FOREIGN KEY (`Tipologia_id`) REFERENCES `tipologie` (`Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `percorsi`
--
ALTER TABLE `percorsi`
  ADD CONSTRAINT `fk_Cluster_perPercorsi` FOREIGN KEY (`Cluster_id`) REFERENCES `clusters` (`Id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `turni`
--
ALTER TABLE `turni`
  ADD CONSTRAINT `fk_Camion_perTurni` FOREIGN KEY (`Camion_id`) REFERENCES `camion` (`Id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Percorsi_perTurni` FOREIGN KEY (`Percorso_id`,`Percorso_data`,`Cluster_id`) REFERENCES `percorsi` (`Id`, `Data`, `Cluster_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Utente_perTurni` FOREIGN KEY (`Utente_id`) REFERENCES `utenti` (`Id`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
