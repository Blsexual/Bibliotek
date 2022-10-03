-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 03 okt 2022 kl 15:15
-- Serverversion: 10.4.24-MariaDB
-- PHP-version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `bibliotek`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `anvandare`
--

CREATE TABLE `anvandare` (
  `ID` int(11) NOT NULL,
  `Namn` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `Ban` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `bok`
--

CREATE TABLE `bok` (
  `ISBN` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `Namn` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `LjudBok` tinyint(1) NOT NULL,
  `ReferensBok` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `bokfor`
--

CREATE TABLE `bokfor` (
  `ID` int(11) NOT NULL,
  `ISBN` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `FID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `exemplar`
--

CREATE TABLE `exemplar` (
  `ID` int(11) NOT NULL,
  `FID` int(11) NOT NULL,
  `ISBN` varchar(50) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `film`
--

CREATE TABLE `film` (
  `ID` int(11) NOT NULL,
  `Langd` time NOT NULL,
  `Titel` varchar(100) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `filmreg`
--

CREATE TABLE `filmreg` (
  `ID` int(11) NOT NULL,
  `FID` int(11) NOT NULL,
  `RID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `forfattare`
--

CREATE TABLE `forfattare` (
  `ID` int(11) NOT NULL,
  `Namn` varchar(100) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `lan`
--

CREATE TABLE `lan` (
  `ID` int(11) NOT NULL,
  `AID` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `Inlamnad` tinyint(1) NOT NULL,
  `StartD` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `SlutD` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `regissor`
--

CREATE TABLE `regissor` (
  `ID` int(11) NOT NULL,
  `Namn` varchar(100) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `anvandare`
--
ALTER TABLE `anvandare`
  ADD PRIMARY KEY (`ID`);

--
-- Index för tabell `bok`
--
ALTER TABLE `bok`
  ADD PRIMARY KEY (`ISBN`);

--
-- Index för tabell `bokfor`
--
ALTER TABLE `bokfor`
  ADD PRIMARY KEY (`ID`);

--
-- Index för tabell `exemplar`
--
ALTER TABLE `exemplar`
  ADD PRIMARY KEY (`ID`);

--
-- Index för tabell `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`ID`);

--
-- Index för tabell `filmreg`
--
ALTER TABLE `filmreg`
  ADD PRIMARY KEY (`ID`);

--
-- Index för tabell `forfattare`
--
ALTER TABLE `forfattare`
  ADD PRIMARY KEY (`ID`);

--
-- Index för tabell `lan`
--
ALTER TABLE `lan`
  ADD PRIMARY KEY (`ID`);

--
-- Index för tabell `regissor`
--
ALTER TABLE `regissor`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `anvandare`
--
ALTER TABLE `anvandare`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `bokfor`
--
ALTER TABLE `bokfor`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `exemplar`
--
ALTER TABLE `exemplar`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `film`
--
ALTER TABLE `film`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `filmreg`
--
ALTER TABLE `filmreg`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `forfattare`
--
ALTER TABLE `forfattare`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `lan`
--
ALTER TABLE `lan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `regissor`
--
ALTER TABLE `regissor`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
