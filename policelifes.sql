-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 04. Jul 2020 um 18:42
-- Server Version: 5.6.47
-- PHP-Version: 7.2.26-1+0~20191218.33+debian8~1.gbpb5a34b

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `policelifes`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Bilder`
--

CREATE TABLE IF NOT EXISTS `Bilder` (
`ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Bild` varchar(55) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `CalloutLog`
--

CREATE TABLE IF NOT EXISTS `CalloutLog` (
`ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `Duration` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=314234 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Data`
--

CREATE TABLE IF NOT EXISTS `Data` (
  `name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `wert` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ExpLog`
--

CREATE TABLE IF NOT EXISTS `ExpLog` (
`ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Exp` int(11) NOT NULL,
  `Reason` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Forgotpw`
--

CREATE TABLE IF NOT EXISTS `Forgotpw` (
`ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Time` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `LiveTicker`
--

CREATE TABLE IF NOT EXISTS `LiveTicker` (
`ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Message` text COLLATE utf8_unicode_ci NOT NULL,
  `Time` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=501287 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Online`
--

CREATE TABLE IF NOT EXISTS `Online` (
  `IP` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `Time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Profile_Log`
--

CREATE TABLE IF NOT EXISTS `Profile_Log` (
`ID` int(11) NOT NULL,
  `Timestamp` int(11) NOT NULL,
  `ProfileId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1445 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

CREATE TABLE IF NOT EXISTS `User` (
`ID` int(11) NOT NULL,
  `Username` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `SocialClubName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Password` text COLLATE utf8_unicode_ci NOT NULL,
  `EMail` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `StaffRang` int(11) NOT NULL,
  `Fraktion` int(11) NOT NULL,
  `FirstLogin` tinyint(1) NOT NULL,
  `Aktiv` tinyint(1) NOT NULL,
  `Banned` tinyint(1) NOT NULL,
  `Code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `LastCode` int(11) NOT NULL,
  `LastTick` int(11) NOT NULL,
  `Created` int(11) NOT NULL,
  `userAuthToken` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ArrestedPeds` int(11) NOT NULL,
  `Money` int(11) NOT NULL,
  `BankMoney` int(11) NOT NULL,
  `Exp` bigint(20) NOT NULL,
  `PosX` float NOT NULL,
  `PosY` float NOT NULL,
  `PosZ` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3544 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Bilder`
--
ALTER TABLE `Bilder`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `CalloutLog`
--
ALTER TABLE `CalloutLog`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `Data`
--
ALTER TABLE `Data`
 ADD PRIMARY KEY (`name`);

--
-- Indizes für die Tabelle `ExpLog`
--
ALTER TABLE `ExpLog`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `Forgotpw`
--
ALTER TABLE `Forgotpw`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `LiveTicker`
--
ALTER TABLE `LiveTicker`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `Online`
--
ALTER TABLE `Online`
 ADD PRIMARY KEY (`IP`);

--
-- Indizes für die Tabelle `Profile_Log`
--
ALTER TABLE `Profile_Log`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Bilder`
--
ALTER TABLE `Bilder`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT für Tabelle `CalloutLog`
--
ALTER TABLE `CalloutLog`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT für Tabelle `ExpLog`
--
ALTER TABLE `ExpLog`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `Forgotpw`
--
ALTER TABLE `Forgotpw`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT für Tabelle `LiveTicker`
--
ALTER TABLE `LiveTicker`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT für Tabelle `Profile_Log`
--
ALTER TABLE `Profile_Log`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT für Tabelle `User`
--
ALTER TABLE `User`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
