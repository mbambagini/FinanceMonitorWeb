-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Lug 14, 2016 alle 23:05
-- Versione del server: 5.5.49-0+deb8u1
-- PHP Version: 5.6.20-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_marioapp`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
`id` int(4) NOT NULL,
  `state` enum('todo','done','reje') NOT NULL,
  `description` varchar(255) NOT NULL,
  `loc` enum('app','web','common') NOT NULL,
  `whenStored` date NOT NULL,
  `idUser` int(4) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `activity`
--

TRUNCATE TABLE `activity`;
-- --------------------------------------------------------

--
-- Struttura della tabella `app_input`
--

CREATE TABLE IF NOT EXISTS `app_input` (
`id` int(4) NOT NULL,
  `id_entry` int(4) NOT NULL,
  `amount` double NOT NULL,
  `currency` int(1) NOT NULL,
  `date_move` date NOT NULL,
  `user` varchar(40) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `app_input`
--

TRUNCATE TABLE `app_input`;
-- --------------------------------------------------------

--
-- Struttura della tabella `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id` int(4) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `category`
--

TRUNCATE TABLE `category`;
--
-- Dump dei dati per la tabella `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(15, 'generico'),
(14, 'viaggi'),
(10, 'casa'),
(16, 'mangiare'),
(17, 'spesa');

-- --------------------------------------------------------

--
-- Struttura della tabella `entry`
--

CREATE TABLE IF NOT EXISTS `entry` (
`id` int(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `id_category` int(4) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `entry`
--

TRUNCATE TABLE `entry`;
--
-- Dump dei dati per la tabella `entry`
--

INSERT INTO `entry` (`id`, `name`, `id_category`) VALUES
(9, 'affitto', 10),
(10, 'manutenzione', 10),
(11, 'internet', 10),
(12, 'mobili', 10),
(13, 'utenze', 10),
(14, 'treno', 14),
(15, 'aereo', 14),
(16, 'bus', 14),
(17, 'hotel', 14),
(18, 'taxi', 14),
(19, 'tasse', 10),
(20, 'trasferimento', 15),
(22, 'ristorante', 16),
(23, 'pub', 16),
(24, 'caffe', 16),
(25, 'stipendio', 15),
(26, 'rimborsi', 15),
(32, 'musica', 17),
(28, 'rent', 14),
(29, 'cibo', 17),
(30, 'vari', 17),
(31, 'medicine', 17);

-- --------------------------------------------------------

--
-- Struttura della tabella `move`
--

CREATE TABLE IF NOT EXISTS `move` (
`id` int(4) NOT NULL,
  `amount` double NOT NULL,
  `currency` enum('euro','dollar','pound') NOT NULL,
  `id_entry` int(4) NOT NULL,
  `execution_time` date NOT NULL,
  `id_user` int(4) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `move`
--

TRUNCATE TABLE `move`;
-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(4) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(41) NOT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Svuota la tabella prima dell'inserimento `user`
--

TRUNCATE TABLE `user`;
--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`id`, `username`, `pass`, `isadmin`) VALUES
(2, 'mario', '*9548B43F3CF8FD7025FDF1A94256EFFE98C29537', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `app_input`
--
ALTER TABLE `app_input`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `entry`
--
ALTER TABLE `entry`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `move`
--
ALTER TABLE `move`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `app_input`
--
ALTER TABLE `app_input`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `entry`
--
ALTER TABLE `entry`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `move`
--
ALTER TABLE `move`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
