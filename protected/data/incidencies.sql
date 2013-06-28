-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 28, 2013 at 07:52 PM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `incidencies`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumnes`
--

CREATE TABLE IF NOT EXISTS `alumnes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(40) NOT NULL,
  `cognom` varchar(40) NOT NULL,
  `emailContacte` varchar(100) DEFAULT NULL,
  `classe` mediumint(8) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (classe) REFERENCES classes(id)',
  PRIMARY KEY (`id`),
  KEY `nom_complet` (`nom`,`cognom`),
  KEY `classe` (`classe`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `alumnes`
--

INSERT INTO `alumnes` (`id`, `nom`, `cognom`, `emailContacte`, `classe`) VALUES
(1, 'Joan', 'Sala Barceló', NULL, 1),
(2, 'Enric', 'García Curado', NULL, 2),
(3, 'Marta', 'Campos Garcia', NULL, 3),
(4, 'Marius', 'Barcelo Garcia', NULL, 4),
(5, 'Alba', 'Coma Punt', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `amonestacions`
--

CREATE TABLE IF NOT EXISTS `amonestacions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipus` tinyint(3) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (tipus) REFERENCES tipus(id)',
  `descripcio` varchar(100) NOT NULL,
  `alumne` bigint(20) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (alumne) REFERENCES alumnes(id)',
  `profe` mediumint(8) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (profe) REFERENCES profes(id)',
  `ennomde` mediumint(8) unsigned DEFAULT NULL COMMENT 'CONSTRAINT FOREIGN KEY (profe) REFERENCES profes(id)',
  `dataRegistre` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `horaLectiva` tinyint(3) unsigned DEFAULT NULL,
  `dataLectiva` date NOT NULL,
  `situacio` varchar(100) DEFAULT NULL,
  `notes` text,
  `assignadaEscrita` tinyint(1) NOT NULL DEFAULT '0',
  `jaVista` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `alumne` (`alumne`),
  KEY `tipus` (`tipus`),
  KEY `profe` (`profe`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `amonestacions`
--

INSERT INTO `amonestacions` (`id`, `tipus`, `descripcio`, `alumne`, `profe`, `ennomde`, `dataRegistre`, `horaLectiva`, `dataLectiva`, `situacio`, `notes`, `assignadaEscrita`, `jaVista`) VALUES
(1, 1, 'Blah1', 4, 3, NULL, '2013-06-26 10:06:58', 1, '2013-06-26', '', '', 0, 0),
(2, 1, 'Blah1', 4, 3, NULL, '2013-06-26 10:07:55', 1, '0000-00-00', '', '', 0, 0),
(3, 1, 'Blah1', 4, 3, NULL, '2013-06-26 10:08:05', 1, '0000-00-00', '', '', 0, 0),
(4, 1, 'Blah1', 1, 3, NULL, '2013-06-26 10:31:38', 1, '2013-06-24', '..0', '', 0, 0),
(5, 1, '1203', 1, 3, 2, '2013-06-26 11:01:33', 1, '2013-06-25', '', '', 0, 0),
(6, 1, 'Descripció1', 4, 3, 1, '2013-06-27 16:48:00', 7, '2013-04-12', 'Una situació aleatòria', '', 0, 0),
(7, 1, 'Això és un retard ', 1, 3, NULL, '2013-06-27 17:00:11', 1, '2013-06-11', '', '', 0, 0),
(8, 1, 'La descripció d''avui', 1, 3, 2, '2013-06-28 07:55:56', 1, '2013-06-27', '', '', 0, 0),
(9, 1, 'la segona descripció d''avui', 4, 3, NULL, '2013-06-28 08:04:59', 1, '2013-06-27', '', '', 0, 0),
(10, 1, 'la segona descripció d''avui', 4, 3, 2, '2013-06-28 08:05:18', 1, '2013-06-27', '', '', 0, 0),
(11, 1, 'la segona descripció d''avui', 4, 3, 1, '2013-06-28 08:05:50', 1, '2013-06-27', '', '', 0, 0),
(12, 1, 'unaincidencia', 4, 3, 2, '2013-06-28 08:27:03', 1, '2013-06-27', '', '', 0, 0),
(13, 1, 'ddd', 1, 3, NULL, '2013-06-28 08:28:04', 1, '2013-06-27', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `descr` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `descr`) VALUES
(1, '1A'),
(2, '1B'),
(3, '1C'),
(4, '1D'),
(5, '1E'),
(6, '2A'),
(7, '2B'),
(8, '2C'),
(9, '2D'),
(10, '2E'),
(11, '3A'),
(12, '3B'),
(13, '3C'),
(14, '3D'),
(15, '4A'),
(16, '4B'),
(17, '4C'),
(18, '4D'),
(19, '1BTXA'),
(20, '1BTXB'),
(21, '1BTXC'),
(22, '2BTXA'),
(23, '2BTXB'),
(24, '2BTXC');

-- --------------------------------------------------------

--
-- Table structure for table `profes`
--

CREATE TABLE IF NOT EXISTS `profes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `equip_directiu` tinyint(1) NOT NULL DEFAULT '0',
  `tutor` mediumint(8) unsigned DEFAULT NULL COMMENT 'CONSTRAINT FOREIGN KEY (tutor) REFERENCES classes(id)',
  `nom` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor` (`tutor`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `profes`
--

INSERT INTO `profes` (`id`, `equip_directiu`, `tutor`, `nom`, `username`, `email`, `password`) VALUES
(1, 0, NULL, 'John Smith', 'jsmith', 'jsmith@example.net', '1234'),
(2, 1, NULL, 'Ana-Maria Carretero', 'acarretero', 'acarret5@xtec.cat', '*A4B6157319038724E3560894F7F932C8886EBFCF'),
(3, 1, NULL, 'Alex Barcelo', 'barcelo', 'alex.barcelo@gmail.com', '$2a$13$AjH1CW0wNmIwfEJ6lL4xSOQ5/LIzjJ2m5eyX5advKg06hgcdUsSzy');

-- --------------------------------------------------------

--
-- Table structure for table `relacions_amonestacions`
--

CREATE TABLE IF NOT EXISTS `relacions_amonestacions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `petita` bigint(20) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (petita) REFERENCES amonestacions(id)',
  `escrita` mediumint(8) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (escrita) REFERENCES amonestacions(id)',
  PRIMARY KEY (`id`),
  KEY `petita` (`petita`),
  KEY `escrita` (`escrita`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tipus`
--

CREATE TABLE IF NOT EXISTS `tipus` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `descr` varchar(20) NOT NULL,
  `longDescr` text,
  `abrev` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tipus`
--

INSERT INTO `tipus` (`id`, `descr`, `longDescr`, `abrev`) VALUES
(1, 'retard', 'Arribada amb retard dels estudiants', 'R'),
(2, 'amonestacioOral', 'Amonestació de tipus oral a un estudiant per comportament o...', 'AO'),
(3, 'expulsio', 'Expulsió a un estudiant per conducta blah blah', 'E');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
