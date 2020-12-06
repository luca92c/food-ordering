-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Dic 06, 2020 alle 18:06
-- Versione del server: 8.0.22-0ubuntu0.20.04.3
-- Versione PHP: 5.6.40-33+ubuntu20.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `ID` int NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_admin`
--

INSERT INTO `tbl_admin` (`ID`, `username`, `password`) VALUES
(0, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_menu`
--

CREATE TABLE `tbl_menu` (
  `menuID` int NOT NULL,
  `menuName` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_menu`
--

INSERT INTO `tbl_menu` (`menuID`, `menuName`) VALUES
(2, 'Primi piatti'),
(3, 'Secondi piatti'),
(4, 'Dessert'),
(5, 'Bevande'),
(8, 'Menu del giorno');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_menuitem`
--

CREATE TABLE `tbl_menuitem` (
  `itemID` int NOT NULL,
  `menuID` int NOT NULL,
  `menuItemName` text NOT NULL,
  `price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_menuitem`
--

INSERT INTO `tbl_menuitem` (`itemID`, `menuID`, `menuItemName`, `price`) VALUES
(3, 2, 'Pastasciutta al ragù', '9.00'),
(4, 2, 'Risotto con i funghi', '13.00'),
(5, 3, 'Frittura mista', '14.00'),
(6, 3, 'Trota salmonata', '11.50'),
(7, 3, 'Tagliata di manzo', '16.00'),
(8, 4, 'Tiramisu', '3.00'),
(9, 4, 'Macafame', '2.50'),
(10, 2, 'Pasta allo scoglio', '12.00'),
(11, 5, 'Acqua naturale', '1.00'),
(12, 5, 'Acqua frizzante', '1.30'),
(13, 5, 'Coca Cola', '2.50'),
(14, 5, 'Vino rosso 1lt.', '8.00'),
(15, 5, 'Caffè', '1.20'),
(17, 5, 'Aranciata', '2.30'),
(20, 8, 'Pasta al pomodoro', '6.00'),
(21, 8, 'Carne alla brace con patatine', '12.00'),
(23, 8, 'Pasticcio', '7.00');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_order`
--

CREATE TABLE `tbl_order` (
  `orderID` int NOT NULL,
  `status` text NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_order`
--

INSERT INTO `tbl_order` (`orderID`, `status`, `total`, `order_date`) VALUES
(1, 'ready', '18.00', '2020-10-06'),
(2, 'ready', '40.00', '2020-11-06'),
(3, 'ready', '39.50', '2020-11-27'),
(4, 'ready', '52.00', '2020-12-02'),
(5, 'preparing', '87.80', '2020-12-06'),
(6, 'preparing', '14.00', '2020-12-06'),
(7, 'preparing', '26.00', '2020-12-06'),
(8, 'waiting', '191.00', '2020-12-06'),
(9, 'waiting', '17.00', '2020-12-06');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_orderdetail`
--

CREATE TABLE `tbl_orderdetail` (
  `orderID` int NOT NULL,
  `orderDetailID` int NOT NULL,
  `itemID` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_orderdetail`
--

INSERT INTO `tbl_orderdetail` (`orderID`, `orderDetailID`, `itemID`, `quantity`) VALUES
(1, 1, 21, 1),
(1, 2, 20, 1),
(2, 3, 3, 3),
(2, 4, 4, 1),
(3, 5, 3, 2),
(3, 6, 4, 1),
(3, 7, 8, 2),
(3, 8, 9, 1),
(4, 9, 11, 4),
(4, 10, 21, 4),
(5, 11, 3, 2),
(5, 12, 4, 1),
(5, 13, 10, 1),
(5, 14, 14, 1),
(5, 15, 12, 4),
(5, 16, 15, 3),
(5, 17, 5, 2),
(6, 18, 6, 1),
(6, 19, 9, 1),
(7, 20, 11, 1),
(7, 21, 3, 1),
(7, 22, 7, 1),
(8, 23, 3, 2),
(8, 24, 4, 3),
(8, 25, 10, 1),
(8, 26, 5, 2),
(8, 27, 6, 2),
(8, 28, 7, 2),
(8, 29, 14, 3),
(8, 30, 15, 6),
(8, 31, 12, 6),
(9, 32, 6, 1),
(9, 33, 8, 1),
(9, 34, 13, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_role`
--

CREATE TABLE `tbl_role` (
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_role`
--

INSERT INTO `tbl_role` (`role`) VALUES
('cameriere'),
('chef');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_staff`
--

CREATE TABLE `tbl_staff` (
  `staffID` int NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` text NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_staff`
--

INSERT INTO `tbl_staff` (`staffID`, `username`, `password`, `status`, `role`) VALUES
(7, 'Luca', 'abc123', 'Online', 'cameriere'),
(8, 'Mario', 'abc123', 'Online', 'chef'),
(9, 'Alice', 'abc123', 'Offline', 'cameriere'),
(10, 'Giovanna', 'abc123', 'Offline', 'cameriere'),
(11, 'Matteo', 'abc123', 'Offline', 'chef');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD PRIMARY KEY (`menuID`);

--
-- Indici per le tabelle `tbl_menuitem`
--
ALTER TABLE `tbl_menuitem`
  ADD PRIMARY KEY (`itemID`),
  ADD KEY `menuID` (`menuID`);

--
-- Indici per le tabelle `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`orderID`);

--
-- Indici per le tabelle `tbl_orderdetail`
--
ALTER TABLE `tbl_orderdetail`
  ADD PRIMARY KEY (`orderDetailID`),
  ADD KEY `itemID` (`itemID`),
  ADD KEY `orderID` (`orderID`);

--
-- Indici per le tabelle `tbl_staff`
--
ALTER TABLE `tbl_staff`
  ADD PRIMARY KEY (`staffID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_menu`
--
ALTER TABLE `tbl_menu`
  MODIFY `menuID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `tbl_menuitem`
--
ALTER TABLE `tbl_menuitem`
  MODIFY `itemID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT per la tabella `tbl_orderdetail`
--
ALTER TABLE `tbl_orderdetail`
  MODIFY `orderDetailID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT per la tabella `tbl_staff`
--
ALTER TABLE `tbl_staff`
  MODIFY `staffID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
