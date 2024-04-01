-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2024 at 06:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ict_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `ActId` int(10) NOT NULL,
  `UserId` int(10) DEFAULT NULL,
  `OtherId` int(10) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL,
  `Point` int(4) DEFAULT 0,
  `Marks` int(3) DEFAULT 0,
  `Dict` text DEFAULT NULL,
  `InsDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` varchar(10) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adminuser`
--

CREATE TABLE `adminuser` (
  `AdminId` int(3) NOT NULL,
  `UName` text DEFAULT NULL,
  `Email` text DEFAULT NULL,
  `Password` varchar(15) DEFAULT NULL,
  `MobNum` varchar(15) DEFAULT NULL,
  `Access` text DEFAULT NULL,
  `Type` varchar(20) DEFAULT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminuser`
--

INSERT INTO `adminuser` (`AdminId`, `UName`, `Email`, `Password`, `MobNum`, `Access`, `Type`, `Status`) VALUES
(1, 'Hemal Jayawardhana', 'NipunTheekshana@gmail.com', 'else 1234', '0774036202', NULL, 'owner', 'active'),
(8, 'Nipun Theekshana', 'dilshanshehara340@gmail.com', 'else 1234', '0754004302', '[2025][2024]', 'admin-Susipwan', 'active'),
(9, 'tryhackme', 'dojatab100@otemdi.com', 'chilhop', '0749266626', NULL, NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `atendent`
--

CREATE TABLE `atendent` (
  `AtendentId` int(11) NOT NULL,
  `StudentId` int(10) NOT NULL,
  `Atendent` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Atendent`)),
  `Dict` text DEFAULT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `ClassId` int(3) NOT NULL,
  `ClassName` text DEFAULT NULL,
  `InstiName` varchar(20) DEFAULT NULL,
  `Type` varchar(8) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `Price` int(4) DEFAULT NULL,
  `Conducting` int(1) NOT NULL DEFAULT 0,
  `Dict` varchar(20) DEFAULT NULL,
  `Status` varchar(8) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`ClassId`, `ClassName`, `InstiName`, `Type`, `year`, `Price`, `Conducting`, `Dict`, `Status`) VALUES
(1, 'Revision', 'Susipwan', 'physical', 2024, 2000, 0, NULL, 'active'),
(2, 'Theory', 'Susipwan', 'physical', 2024, 2000, 0, NULL, 'active'),
(3, 'Paper', 'Susipwan', 'physical', 2024, 1000, 1, '3-05:00-23:00', 'active'),
(4, 'Revision', 'Sasip', 'physical', 2024, 2200, 0, NULL, 'active'),
(5, 'Revision ', 'Ziyowin', 'physical', 2024, 2000, 0, NULL, 'active'),
(6, 'Theroy', 'Ziyowin', 'physical', 2024, 0, 0, NULL, 'active'),
(7, 'Theory', 'Ziyowin', 'physical', 2025, 2000, 0, NULL, 'active'),
(8, 'Revision', 'Wins', 'physical', 2024, 2200, 0, NULL, 'active'),
(9, 'Theory', 'Wins', 'physical', 2024, 2200, 0, NULL, 'active'),
(10, 'Theory', 'Wins', 'physical', 2025, 2500, 0, NULL, 'active'),
(11, 'Theory', 'Susipwan', 'physical', 2025, 2200, 0, NULL, 'active'),
(12, 'neeeeeeeeeeeeeee', 'Susipwan', 'physical', 2024, 2000, 0, NULL, 'active'),
(13, 'Theyory', 'Online', 'online', 2024, 2000, 0, NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `grouplist`
--

CREATE TABLE `grouplist` (
  `GId` int(3) NOT NULL,
  `MGName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MGImage` varchar(20) DEFAULT NULL,
  `SGName` text DEFAULT NULL,
  `HideFrom` varchar(20) DEFAULT NULL,
  `Status` varchar(8) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grouplist`
--

INSERT INTO `grouplist` (`GId`, `MGName`, `MGImage`, `SGName`, `HideFrom`, `Status`) VALUES
(1, 'තොරතුරු හා සන්නිවේදන තාක්ෂණයේ මුලික සංකල්ප ', '20240311001355.jpg', NULL, '[1][5]', 'active'),
(2, 'පරිගණක පරිණාමය ', '20240311001652.jpg', NULL, NULL, 'active'),
(3, 'සංඛ්‍යා පද්ධතිය හා දත්ත නිරූපණය', '20240311002226.jpg', NULL, NULL, 'active'),
(4, 'බූලියානු  වීජ ගණිතය හා අංක ගණිතමය උපක්‍රම', '20240311002435.jpg', NULL, NULL, 'disable'),
(5, 'මෙහෙයුම් පද්ධති', '20240311003140.jpg', NULL, NULL, 'pending'),
(6, 'දත්ත සන්නිවේදන ජාලකරණය', '20240311003504.jpg', NULL, NULL, 'pending'),
(7, 'තොරතුරු පද්ධතිය', '20240311003727.jpg', NULL, NULL, 'pending'),
(8, 'දත්ත සමුදාය', '20240311005040.jpg', NULL, NULL, 'pending'),
(9, 'ක්‍රමලේඛකරණය ', '20240311005309.jpg', NULL, NULL, 'pending'),
(10, 'වෙබ් අඩවි සංවර්ධනය ', '20240311005445.jpg', NULL, NULL, 'pending'),
(11, 'IOT', '20240311005523.jpg', NULL, NULL, 'pending'),
(12, 'ව්‍යාපාර කටයුතු සදහා ICT  ', '20240311005838.jpg', NULL, NULL, 'pending'),
(13, 'නව නැඹුරුව අනාගත දිශානතිය', '20240311010123.jpg', NULL, NULL, 'pending'),
(18, 'rrrrrrrrrrrrrrrrrrrr', '20240326030143.jpg', NULL, NULL, 'pending'),
(19, 'fffffffffffffff', '20240326030204.jpg', NULL, '[1][2]', 'pending'),
(20, 'fghfghfghf', '20240326032608.jpg', NULL, '[2]', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `insti`
--

CREATE TABLE `insti` (
  `InstiId` int(2) NOT NULL,
  `InstiName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InstiPlace` varchar(15) DEFAULT NULL,
  `Dict` text NOT NULL,
  `SubDict` text DEFAULT NULL,
  `InstiPic` varchar(15) NOT NULL,
  `TimeTable` text DEFAULT NULL,
  `Status` varchar(8) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insti`
--

INSERT INTO `insti` (`InstiId`, `InstiName`, `InstiPlace`, `Dict`, `SubDict`, `InstiPic`, `TimeTable`, `Status`) VALUES
(1, 'Susipwan', 'Gampaha', 'සුසිප්වන් ආයතනයේ භෞතික පන්ති පැමිනෙන සිසුන්ට පමණක් සහභාගි විය හැක.', ' Higher Education (Pvt) Ltd - Gampaha', 'susipwan.jpg', NULL, 'active'),
(2, 'Wins', 'Veyangoda', 'Wins ආයතනයේ භෞතික පන්ති පැමිනෙන සිසුන්ට පමණක් සහභාගි විය හැක.', ' Higher Education (Pvt) Ltd - Veyangoda', 'wins.jpg', NULL, 'active'),
(3, 'Sasip', 'Nugegoda', 'Sasip ආයතනයේ භෞතික පන්ති පැමිනෙන සිසුන්ට පමණක් සහභාගි විය හැක.', ' Higher Education (Pvt) Ltd - Nugegoda', 'sasip.jpg', NULL, 'active'),
(5, 'Online', 'Online', 'Online පන්ති පැමිනෙන සිසුන්ට පමණක් සහභාගි විය හැක.                   \n\n\n                                                 ', ' Higher Education (Pvt) Ltd - Online', 'online.jpg', NULL, 'active'),
(14, 'Ziyowin', 'Kegalle', 'Ziyowin ආයතනයේ භෞතික පන්ති පැමිනෙන සිසුන්ට පමණක් සහභාගි විය හැක.\n\n', 'Higher Education (Pvt) Ltd - Kegalle', 'Ziyowin.jpg', NULL, 'active'),
(19, 'hhhhhhhhhhhhhhh', 'hhhhhhhhhhhhh', 'hhhhhhhhhhhhhhhhh', 'hhhhhhhhhhhhhh', 'hhhhhhhhhhhhhhh', 'hhhhhhhhhhhhhhhh', 'disable'),
(20, 'hhhhhhhhhhhhhh', 'hhhhhhhhhhhhhh', 'hhhhhhhhhhhhhhhh', 'hhhhhhhhhhhhh', 'hhhhhhhhhhhhhh.', '', 'disable'),
(21, 'dfgdfgdf', 'gdfgd', 'fgdfgd', 'fgdfgdfg', 'dfgdfgdf.jpg', '', 'disable');

-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE `lesson` (
  `LesId` int(10) NOT NULL,
  `LesName` text DEFAULT NULL,
  `Dict` text DEFAULT NULL,
  `Link` varchar(15) DEFAULT NULL,
  `Time` int(2) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL,
  `InsertDate` timestamp NULL DEFAULT current_timestamp(),
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson`
--

INSERT INTO `lesson` (`LesId`, `LesName`, `Dict`, `Link`, `Time`, `Type`, `InsertDate`, `Status`) VALUES
(1, 'fgyhfgyhftgy', '', 'n0one', NULL, 'video', '2024-03-24 07:00:00', 'desable'),
(2, 'computer1', '', 'https://us06web', NULL, 'video', '2024-03-24 07:00:00', 'active'),
(3, 'computer', '', 'YyaYFV0CgAo', NULL, 'video', '2024-03-24 07:00:00', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `NotifiId` int(4) NOT NULL,
  `UserId` int(10) DEFAULT NULL,
  `OtherId` int(10) DEFAULT NULL,
  `Type` varchar(20) DEFAULT NULL,
  `Title` text DEFAULT NULL,
  `Dict` text DEFAULT NULL,
  `Image` varchar(20) DEFAULT NULL,
  `Date` datetime DEFAULT current_timestamp(),
  `expDate` varchar(15) DEFAULT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`NotifiId`, `UserId`, `OtherId`, `Type`, `Title`, `Dict`, `Image`, `Date`, `expDate`, `Status`) VALUES
(1, 26, 12345, 'insti aprued', 'Your institute registstion Aprued Successfull.', NULL, NULL, '2024-03-24 22:58:49', NULL, 'pending'),
(2, 14, 202424045, 'insti aprued', 'Your institute registstion Aprued Successfull.', NULL, NULL, '2024-03-25 00:35:47', NULL, 'pending'),
(3, 14, 202424045, 'insti aprued', 'Your institute registstion Aprued Successfull.', NULL, NULL, '2024-03-25 00:35:47', NULL, 'pending'),
(4, 12, 1, 'payment aprued', 'Your Payment Aprued Successfull.', NULL, NULL, '2024-03-25 03:00:43', NULL, 'pending'),
(5, NULL, NULL, 'winner', NULL, '[\"peaperName\",\"205 ppererwsertwsert4\",\"place\",\"wertserterts\",\"winnerName\",\"dertsrtsedrtsert\",\"dict\",\"sdrtsdrtsdrtsdrt\",\"marks\",\"70%\"]', '20240325130325.jpg', '2024-03-25 13:03:25', '2024-03-12', 'disable'),
(6, NULL, NULL, 'winner', NULL, '[\"peaperName\",\"fcyutyujtyuj\",\"place\",\"fthfghyyh\",\"winnerName\",\"dtyhfdthyfdthdth\",\"dict\",\"dthdtrhdrtydtydtr\",\"marks\",\"fdthdt\"]', '20240325145820.jpg', '2024-03-25 14:58:20', '2024-03-13', 'active'),
(10, NULL, NULL, 'winner', NULL, '[\"peaperName\",\"peaper name\",\"place\",\"place\",\"winnerName\",\"student name\",\"dict\",\"discription\",\"marks\",\"90%\"]', '20240325214606.jpg', '2024-03-25 21:46:06', '2024-03-18', 'active'),
(11, NULL, NULL, 'winner', NULL, '[\"peaperName\",\"fthfthfth\",\"place\",\"fthfthft\",\"winnerName\",\"hftfhth\",\"dict\",\"fhfhfghfh\",\"marks\",\"fthft\"]', '20240326015845.jpg', '2024-03-26 01:58:45', '2024-03-15', 'active'),
(12, NULL, NULL, 'winner', NULL, '[\"peaperName\",\"tyhtyhfty\",\"place\",\"ftyhfth\",\"winnerName\",\"ftff\",\"dict\",\"tfthfthfhhfthft\",\"marks\",\"tytjfty\"]', '20240326015948.jpg', '2024-03-26 01:59:48', NULL, 'active'),
(13, 15, 7, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 02:15:23', NULL, 'pending'),
(14, 15, 8, 'payment aprued', 'Your Payment Aprued Successfull.', NULL, NULL, '2024-03-27 02:22:55', NULL, 'pending'),
(15, 15, 9, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 02:28:04', NULL, 'pending'),
(16, 15, 10, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 02:30:40', NULL, 'pending'),
(17, 15, 11, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 02:32:18', NULL, 'pending'),
(18, 15, 12, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 02:39:21', NULL, 'pending'),
(19, 15, 13, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 02:39:50', NULL, 'pending'),
(20, 15, 14, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 14:44:35', NULL, 'pending'),
(21, 15, 15, 'payment Ignored', 'Your Payment Ignored.', 'Your Payment is Ignored. Place check and try again make a new payment.', NULL, '2024-03-27 20:28:45', NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `paydata`
--

CREATE TABLE `paydata` (
  `PDId` int(10) NOT NULL,
  `PayId` int(10) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Tel1` varchar(12) DEFAULT NULL,
  `Tel2` varchar(12) DEFAULT NULL,
  `TelW` varchar(12) DEFAULT NULL,
  `Distric` text DEFAULT NULL,
  `City` text DEFAULT NULL,
  `Dict` text DEFAULT NULL,
  `Status` varchar(10) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paydata`
--

INSERT INTO `paydata` (`PDId`, `PayId`, `Address`, `Tel1`, `Tel2`, `TelW`, `Distric`, `City`, `Dict`, `Status`) VALUES
(4, 10, 'jsbfsbfkzdjsfsdgsdgsd', '0774036202', '0774036202', '0447788555', 'kagalle', 'galigamuwa', '', 'pending'),
(5, 11, 'jsbfsbfkzdjsfsdgsdgsd', '0774036202', '0774036202', '0778855999', 'kagalle', 'galigamuwa', '', 'pending'),
(6, 12, 'jsbfsbfkzdjsfsdgsdgsd', '0774036202', '0774036202', '0778888884', 'kagalle', 'galigamuwa', '', 'pending'),
(7, 13, 'jsbfsbfkzdjsfsdgsdgsd', '0774036202', '0774036202', '0778899555', 'kagalle', 'galigamuwa', '', 'pending'),
(8, 14, 'jsbfsbfkzdjsfsdgsdgsd', '0774036202', '0774036202', '0552211555', 'kagalle', 'galigamuwa', '', 'pending'),
(9, 15, 'jsbfsbfkzdjsfsdgsdgsd', '0774036202', '0774036202', '0778899666', 'kagalle', 'galigamuwa', '', 'pending'),
(10, 16, 'jsbfsbfkzdjsfsdgsdgsd', '0774036202', '0774036202', '0774488555', 'kagalle', 'galigamuwa', '', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PayId` int(10) NOT NULL,
  `UserId` int(10) DEFAULT NULL,
  `Name` text DEFAULT NULL,
  `ClassId` int(3) DEFAULT NULL,
  `Price` int(4) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL,
  `Month` int(6) DEFAULT NULL,
  `Slip` varchar(22) DEFAULT NULL,
  `InsDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PayId`, `UserId`, `Name`, `ClassId`, `Price`, `Type`, `Month`, `Slip`, `InsDate`, `Status`) VALUES
(1, 12, NULL, 2, NULL, 'active', 202403, '12_20240325041806.jpg', '2024-03-24 18:30:00', 'pending'),
(2, 3, NULL, 11, 2000, 'active', 202403, '32135465.jpg', '2024-03-26 18:40:23', 'pending'),
(3, 14, NULL, 4, NULL, 'active', 202403, '12_20240327002047.jpg', '2024-03-26 18:30:00', 'pending'),
(4, 4, NULL, 1, NULL, 'active', 202403, '12_20240327002047.jpg', '2024-03-26 18:30:00', 'pending'),
(5, 14, NULL, 1, NULL, 'active', 202402, '12_20240327002047.jpg', '2024-03-26 18:30:00', 'pending'),
(6, 12, NULL, 3, NULL, 'active', 202403, '12_20240327002104.jpg', '2024-03-26 18:30:00', 'pending'),
(16, 15, 'Nipun Theekshana', 13, 2000, 'bds', 202403, '15_20240327202926.jpg', '2024-03-26 18:30:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `pdfcount`
--

CREATE TABLE `pdfcount` (
  `PCountId` int(10) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Url` varchar(20) DEFAULT NULL,
  `Dict` text DEFAULT NULL,
  `Tiotle` int(3) NOT NULL DEFAULT 0,
  `Gave` int(3) NOT NULL DEFAULT 0,
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peaper`
--

CREATE TABLE `peaper` (
  `PeaperId` int(10) NOT NULL,
  `UserIfd` int(10) NOT NULL,
  `LesId` int(10) NOT NULL,
  `Url` varchar(20) NOT NULL,
  `Dict` text NOT NULL,
  `Marks` int(3) NOT NULL DEFAULT 0,
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quction`
--

CREATE TABLE `quction` (
  `QuizId` int(10) NOT NULL,
  `LesId` int(10) DEFAULT NULL,
  `Number` int(2) DEFAULT NULL,
  `quction` text DEFAULT NULL,
  `QuizImage` varchar(20) DEFAULT NULL,
  `Ans` text DEFAULT NULL,
  `Opt1` text DEFAULT NULL,
  `Opt2` text DEFAULT NULL,
  `Opt3` text DEFAULT NULL,
  `Opt4` text DEFAULT NULL,
  `Dict` text DEFAULT NULL,
  `Time` int(2) DEFAULT NULL,
  `Type` varchar(10) NOT NULL DEFAULT 'normal',
  `InsDate` int(10) DEFAULT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recaccess`
--

CREATE TABLE `recaccess` (
  `AccId` int(4) NOT NULL,
  `LesId` int(4) DEFAULT NULL,
  `ClassId` text DEFAULT NULL,
  `GId` text DEFAULT NULL,
  `Month` int(6) DEFAULT NULL,
  `InsDate` timestamp NULL DEFAULT current_timestamp(),
  `ExpDate` int(10) DEFAULT NULL,
  `Status` varchar(8) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recaccess`
--

INSERT INTO `recaccess` (`AccId`, `LesId`, `ClassId`, `GId`, `Month`, `InsDate`, `ExpDate`, `Status`) VALUES
(1, 1, '[1][2][13]', '[1][2]', 202403, '2024-03-24 07:00:00', NULL, 'active'),
(2, 1, '[1][2][13]', '[1][2]', 202403, '2024-03-24 07:00:00', NULL, 'active'),
(3, 2, '[1][2][3][13]', '[1][2]', 202403, '2024-03-24 07:00:00', NULL, 'active'),
(4, 3, '[1][2][3][13]', '[1][2]', 202403, '2024-03-24 07:00:00', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `spaccess`
--

CREATE TABLE `spaccess` (
  `SPId` int(9) NOT NULL,
  `RegCode` varchar(10) NOT NULL,
  `Access` text NOT NULL,
  `InsDate` int(15) NOT NULL,
  `ExpDate` int(15) NOT NULL,
  `Status` varchar(8) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserId` int(10) NOT NULL,
  `RegCode` varchar(10) DEFAULT NULL,
  `UserName` text DEFAULT NULL,
  `Email` text NOT NULL,
  `AuthId` varchar(50) DEFAULT NULL,
  `Priovider` enum('google','facebook','twitter','linkedin') NOT NULL DEFAULT 'google',
  `Picture` text DEFAULT NULL,
  `Created` datetime DEFAULT current_timestamp(),
  `Year` int(4) DEFAULT NULL,
  `InstiName` varchar(20) DEFAULT NULL,
  `InstiId` int(10) DEFAULT NULL,
  `Point` int(4) NOT NULL DEFAULT 0,
  `Status` varchar(10) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserId`, `RegCode`, `UserName`, `Email`, `AuthId`, `Priovider`, `Picture`, `Created`, `Year`, `InstiName`, `InstiId`, `Point`, `Status`) VALUES
(1, 'ICT2410000', 'Nipun Theekshana Hemal', 'theekshananipun104@gmail.com', '118021420590597001032', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocIWlHYn7y_aUbBlbrDSOKCTiDe4xrRg24n837VDw7nIebQ=s96-c', '2024-02-29 23:57:30', 2024, 'Susipwan', 11111, 0, 'active'),
(3, 'ICT2430000', 'Suraj S kumara ICT', 'suraj.sumudu.kumara100@gmail.com', '102053635391827367098', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocJ6JIDT1D5WvTvCndicv_WzjTq2pijNdzPXDFlqxnavq80=s96-c', '2024-03-01 00:27:57', 2025, 'Susipwan', 123456, 10, 'pending'),
(7, 'ICT2470000', 'c dasanayaka', 'k6js3aemch@email.edu.pl', '102820106952115451393', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocLYkChHv_HOVTMiDuEr6hsNdWBF5Nb_NSf-IMkFQZk9=s96-c', '2024-03-08 22:22:57', 2024, NULL, NULL, 0, 'pending'),
(8, 'ICT2480000', 'sadew ranura', 'ranurasadew27@gmail.com', '108867870926388543626', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocKfu13qIK__uI9SxIPpxEJlHY5w-IsIAvvhTkh9UHAT=s96-c', '2024-03-09 06:34:39', 2024, NULL, NULL, 0, 'pending'),
(9, 'ICT2590000', 'shehan rajapaksha', 'shehan774690541@gmail.com', '102245836490398846601', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocLiyAzLdZC0iCtCEl--jfEOe4MIfknpFsjeY7k1QOsr4g=s96-c', '2024-03-10 07:12:27', 2025, NULL, NULL, 0, 'pending'),
(10, NULL, 'Ravindi Imasha Wanigathunga', 'ravindiimasha543@gmail.com', '111137774136649223530', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocLoeTfCS1bcK2Medk_-UdQjaxZ4mzOaVjhJ-9-cxO4xoA=s96-c', '2024-03-10 19:01:01', NULL, NULL, NULL, 0, 'pending'),
(11, NULL, 'Thisul Bandara', 'thisulbandara77@gmail.com', '116232620238053981992', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocIjEqhUJ6TW0YegI1dqFn7YlCs6npcjcNYUPmZXZHY-_w=s96-c', '2024-03-10 23:17:45', NULL, NULL, NULL, 0, 'pending'),
(12, 'ICT2512000', 'Nipun Teekshana', 'nipunteekshana914@gmail.com', '113677026604154892760', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocLZWNr9hGYr2xqENRkBY5WVzmr1lFB3oYOIqzn4uULMxg=s96-c', '2024-03-11 01:07:25', 2024, 'Susipwan', 200329, 10, 'active'),
(13, NULL, 'Navodya Thathsarani', 'thathsaraninavodya39@gmail.com', '118147573332108781412', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocI0bn-q9XyPY7Jgpqj4zbrXwOvtrDvLjiCeQvdWif2b=s96-c', '2024-03-11 01:45:59', NULL, NULL, NULL, 0, 'pending'),
(14, 'ICT2414000', 'Maheema Parindi', 'mahee.parindi123@gmail.com', '118199783184641664574', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocKHwRnVhHtJBpLX-gntFNViCTcANTsaaaiQtBElzrBQhA=s96-c', '2024-03-11 01:46:43', 2024, 'Sasip', 202424045, 0, 'active'),
(15, 'ICT2415000', 'Nipun Theekshana', 'nipunthemal@gmail.com', '117463765454732449805', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocImXsCdQtzE_fIPVK2KDlZeQkl-7TAAb4L6qs-Yh0Daxw=s96-c', '2024-03-23 08:46:16', 2024, 'Online', 200330, 0, 'active'),
(16, 'ICT2516000', 'Udanimano Basnayaka', 'udanimanobasnayaka@gmail.com', '112029798264764494575', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocKBntUFIrSgWSPfEQ56WpND445TTzymMLxyDyw1jmdY=s96-c', '2024-03-23 17:54:39', 2025, NULL, NULL, 0, 'pending'),
(17, 'ICT2417000', 'Manisha Dilshan', 'dilshanshehara340@gmail.com', '104483711365491874788', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocJCUSPOdEkNLvSTzqHqWhbcrFX2mfhPRxNNRVu-kDcld5U=s96-c', '2024-03-23 19:47:43', 2024, NULL, NULL, 0, 'pending'),
(18, 'ICT2418000', 'kavisha dulmith', 'hiranyaakash15108@gmail.com', '112595724488392904721', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocJBMDpzQ02xzSsX7vZ3NuMJL9uOc9CqP3Fogebvcx-5p7M=s96-c', '2024-03-23 23:51:28', 2024, NULL, NULL, 0, 'pending'),
(19, NULL, 'Akash Hiranya', 'hiranyaakash78@gmail.com', '115667715960740714067', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocJWgHPtZIr51z4fLImbU7FC-HSuPwIhLDEZe975bYlfRw=s96-c', '2024-03-23 23:51:46', NULL, NULL, NULL, 0, 'pending'),
(20, NULL, 'akash hiranya', 'hiranyaakash529@gmail.com', '112279492878707671903', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocLKnbpsM1xtFyTVvWIcTPCDDLwb4gpoJIMej5aKzJ-k=s96-c', '2024-03-23 23:52:21', NULL, NULL, NULL, 0, 'pending'),
(21, 'ICT2421000', 'Sandu Dissanayaka', 'sandudissanayaka264@gmail.com', '115433385513114398628', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocKuxmmBGZQKfcaOhsxUobI3LeCejsBk_fxDEv2zzRoy6Q=s96-c', '2024-03-24 04:53:24', 2024, NULL, NULL, 0, 'pending'),
(22, 'ICT2422000', 'Nihal prasanna', 'prasannanihal400@gmail.com', '108065404444065030525', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocLbHrjkVkfdmgqaUHUx9PGxSSvrsOi3nFopJJ-_QX--eBg=s96-c', '2024-03-24 04:56:09', 2024, NULL, NULL, 0, 'pending'),
(23, 'ICT2423000', 'Sandali Nethmini', 'hathurusinghechandrika@gmail.com', '116835904420565254714', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocIEW5pBSlwtYk_f-S0QGj0wraGUthK4SrAYQUKQ9S44Swo=s96-c', '2024-03-24 06:46:43', 2024, NULL, NULL, 0, 'pending'),
(24, 'ICT2524000', 'KS Karunarathna', 'karunarathnaks96@gmail.com', '108416267609128392074', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocINv9wYFbwcHZzTpZk5Vw0qN8V1xZ6pvhUj5h7-l__Z=s96-c', '2024-03-24 07:25:06', 2025, NULL, NULL, 0, 'pending'),
(25, NULL, 'Maleesha Gangodawila', 'maleegangodawila2005@gmail.com', '113918859873767715554', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocKqxwDs3N785jFJYIfAZB4d6IA09q1gW22zhjUBXg4n=s96-c', '2024-03-24 08:37:36', NULL, NULL, NULL, 0, 'pending'),
(26, 'ICT2526000', 'Suraj ICT', 'surajskumaraict@gmail.com', '105624605170194171726', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocKA2CRPSDegvJH_h6KJk3rIjdOjxDND763b9syBJpQuEh8=s96-c', '2024-03-24 10:25:19', 2025, 'Online', 12345, 0, 'active'),
(27, NULL, 'Dewmini Sulakshana', 'deawsulakshana@gmail.com', '105650693172368425725', 'google', 'https://lh3.googleusercontent.com/a/ACg8ocKRskyMKn9LAZg2nt4PrJKHeegYaBQnoMgdnjDtbKdljyBa=s96-c', '2024-03-24 10:39:41', NULL, NULL, NULL, 0, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE `userdata` (
  `UdId` int(10) NOT NULL,
  `UserId` int(10) NOT NULL,
  `RegCode` varchar(10) NOT NULL,
  `Fname` text DEFAULT NULL,
  `Lname` text DEFAULT NULL,
  `Email` text DEFAULT NULL,
  `Nic` varchar(12) DEFAULT NULL,
  `NicPic` text DEFAULT NULL,
  `MobNum` varchar(12) DEFAULT NULL,
  `WhaNum` varchar(12) DEFAULT NULL,
  `Dob` varchar(10) DEFAULT NULL,
  `SchName` text DEFAULT NULL,
  `Year` int(4) DEFAULT NULL,
  `Streem` varchar(10) DEFAULT NULL,
  `Shy` varchar(2) DEFAULT NULL,
  `Medium` varchar(7) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Distric` varchar(15) DEFAULT NULL,
  `City` varchar(20) DEFAULT NULL,
  `InstiName` varchar(20) DEFAULT NULL,
  `InstiId` varchar(15) DEFAULT NULL,
  `InstiPic` varchar(20) DEFAULT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`UdId`, `UserId`, `RegCode`, `Fname`, `Lname`, `Email`, `Nic`, `NicPic`, `MobNum`, `WhaNum`, `Dob`, `SchName`, `Year`, `Streem`, `Shy`, `Medium`, `Address`, `Distric`, `City`, `InstiName`, `InstiId`, `InstiPic`, `Status`) VALUES
(1, 1, 'ICT2410000', 'Nipun', 'Theekshana Hemal', 'theekshananipun104@gmail.com', '200329555223', 'Nic-1-20240301.jpeg', '0774036202', '077403622', '2009-12-02', 'Dsss', 2024, 'Science', '1', 'Sinhala', 'morawaka nelundeneya', 'kegalle', 'galigamuwa', 'Susipwan', '11111', '11111_1.jpg', 'pending'),
(13, 3, 'ICT2430000', 'Suraj', 'S kumara ICT', 'suraj.sumudu.kumara100@gmail.com', '123456789108', 'Nic-3-20240303.jpeg', '0866964125', '0766964125', '2024-03-14', 'Ucc', 2024, 'Tec', '1', 'Sinhala', 'Fhjb', 'kegalle', 'galigamuwa', 'Susipwan', '123456', '_3.jpg', 'pending'),
(21, 7, 'ICT2470000', 'c', 'dasanayaka', 'k6js3aemch@email.edu.pl', '2002558877', 'Nic-7-20240309.jpg', '0701359572', '0774036202', '2009-12-29', 'dxgxdfvdx', 2024, 'Bio', '2', 'English', 'Main street kegalla', 'Hambantota', 'Kegalla', NULL, NULL, NULL, 'pending'),
(22, 8, 'ICT2480000', 'sadew', 'ranura', 'ranurasadew27@gmail.com', '200745469453', 'Nic-8-20240309.jpg', '0701131969', '701131969', '2007-08-01', 'M.k.v', 2024, 'Maths', '1', 'Sinhala', 'Kirivanahena moravaka nelundeniya', 'Kegalle', 'Nelundeniya', NULL, NULL, NULL, 'pending'),
(23, 9, 'ICT2590000', 'shehan', 'rajapaksha', 'shehan774690541@gmail.com', '200312345678', NULL, '9477469041', '94774690541', '2003-12-10', 'Hettimulla nawa maha widhyalaya', 2025, 'Tec', '2', 'Sinhala', 'kegalla', 'Kegalle', 'kegalle', NULL, NULL, NULL, 'pending'),
(26, 12, 'ICT2512000', 'Nipun', 'Teekshana', 'nipunteekshana914@gmail.com', '', NULL, '0701359572', '0774455888', '2009-12-28', 'dscc', 2025, 'Maths', '1', 'English', 'Main street kegalla', 'Kegalle', 'Kegalla', 'ziyowin', '200329', '200329_12.jpg', 'pending'),
(27, 15, 'ICT2415000', 'Nipun', 'Theekshana', 'nipunthemal@gmail.com', NULL, NULL, '0774036202', '0774036202', '2009-12-30', 'Dadly senanayaka central college ', 2024, 'Bio', '1', 'Sinhala', 'morawaka , nelundeneya', 'Kegalle', 'Kegalla', 'Online', '200330', '200330_15.jpg', 'pending'),
(28, 16, 'ICT2516000', 'Udanimano', 'Basnayaka', 'udanimanobasnayaka@gmail.com', '200678102995', NULL, '0761533599', '076153599', '2006-10-07', 'Nugawela cenral college ', 2025, 'Tec', '1', 'Sinhala', 'No 113 Adhikarampura madagoda galagedra', 'Kandy', 'Kandy', NULL, NULL, NULL, 'pending'),
(29, 17, 'ICT2417000', 'Manisha', 'Dilshan', 'dilshanshehara340@gmail.com', NULL, NULL, '0754004302', '0754004302', '2010-01-01', 'Hcc', 2024, 'Maths', '1', 'Sinhala', 'fdv', 'Badulla', 'Bsssj', NULL, NULL, NULL, 'pending'),
(30, 18, 'ICT2418000', 'kavisha', 'dulmith', 'hiranyaakash15108@gmail.com', '200510801272', 'Nic-18-20240324.jpg', '0763051598', '0763051598', '2005-04-17', 'C.W.W.Kannangara central college ', 2024, 'Tec', '1', 'Sinhala', '188/C Weragodamulla, Horampella ', 'Gampaha', 'Minuwangoda ', NULL, NULL, NULL, 'pending'),
(31, 21, 'ICT2421000', 'Sandu', 'Dissanayaka', 'sandudissanayaka264@gmail.com', '43025262', 'Nic-21-20240324.jpg', '0787166587', '0701942458', '2005-07-18', 'Karunarathna Buddhist College', 2024, 'Tec', '1', 'Sinhala', '290 Vijaya Road, Kerangapokuna, Wattala', 'Gampaha', 'Wattala', NULL, NULL, NULL, 'pending'),
(32, 23, 'ICT2423000', 'Sandali', 'Nethmini', 'hathurusinghechandrika@gmail.com', '200561701423', 'Nic-23-20240324.jpg', '0762589544', '0762589544', '2005-04-26', 'Bandaranayake Central College Veyangoda', 2024, 'Commers', '1', 'Sinhala', '182/1 Yatagama , Essalla', 'Gampaha', 'Veyangoda ', NULL, NULL, NULL, 'pending'),
(33, 22, 'ICT2422000', 'Nihal', 'prasanna', 'prasannanihal400@gmail.com', '200578900393', 'Nic-22-20240324.jpg', '0779505725', '0779505725', '2005-10-15', 'Veyangoda Bandaranayaka central college', 2024, 'Commers', '1', 'Sinhala', '554/9 sangbo mwtha, nittambuwe ', 'Gampaha', 'Nittambuwe ', NULL, NULL, NULL, 'pending'),
(34, 24, 'ICT2524000', 'KS', 'Karunarathna', 'karunarathnaks96@gmail.com', '200679003353', NULL, '0778527809', '0778527809', '2006-10-16', 'Anuradhapura central college', 2025, 'Tec', '1', 'Sinhala', 'Akkara 100 road , Horowpothana ', 'Anuradhapura', 'Horowpothana ', NULL, NULL, NULL, 'pending'),
(35, 26, 'ICT2526000', 'Suraj', 'ICT', 'surajskumaraict@gmail.com', '2000728749', NULL, '0777283197', '0777283197', '2009-12-30', 'urapola c.c ', 2025, 'Tec', '1', 'Sinhala', '136/walgammulla. veyangoda', 'Colombo', 'gampha', 'Online', '12345', '12345_26.jpg', 'pending'),
(36, 14, 'ICT2414000', 'Maheema', 'Parindi', 'mahee.parindi123@gmail.com', '200570501278', NULL, '0741803686', '0741803686', '2005-07-23', 'Mahamaya girls school', 2024, 'Art', '1', 'Sinhala', '142/16c samanala uyana kirimandala mawatha colombo 05', 'Colombo', 'Colombo', 'Sasip', '202424045', '202424045_14.jpg', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`ActId`);

--
-- Indexes for table `adminuser`
--
ALTER TABLE `adminuser`
  ADD PRIMARY KEY (`AdminId`);

--
-- Indexes for table `atendent`
--
ALTER TABLE `atendent`
  ADD PRIMARY KEY (`StudentId`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`ClassId`);

--
-- Indexes for table `grouplist`
--
ALTER TABLE `grouplist`
  ADD PRIMARY KEY (`GId`);

--
-- Indexes for table `insti`
--
ALTER TABLE `insti`
  ADD PRIMARY KEY (`InstiId`);

--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`LesId`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`NotifiId`);

--
-- Indexes for table `paydata`
--
ALTER TABLE `paydata`
  ADD PRIMARY KEY (`PDId`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PayId`);

--
-- Indexes for table `pdfcount`
--
ALTER TABLE `pdfcount`
  ADD PRIMARY KEY (`PCountId`);

--
-- Indexes for table `peaper`
--
ALTER TABLE `peaper`
  ADD PRIMARY KEY (`PeaperId`);

--
-- Indexes for table `quction`
--
ALTER TABLE `quction`
  ADD PRIMARY KEY (`QuizId`);

--
-- Indexes for table `recaccess`
--
ALTER TABLE `recaccess`
  ADD PRIMARY KEY (`AccId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `Email` (`Email`) USING HASH;

--
-- Indexes for table `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`UdId`),
  ADD UNIQUE KEY `UserId` (`UserId`),
  ADD UNIQUE KEY `RegCode` (`RegCode`),
  ADD UNIQUE KEY `Nic` (`Nic`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `ActId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adminuser`
--
ALTER TABLE `adminuser`
  MODIFY `AdminId` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `atendent`
--
ALTER TABLE `atendent`
  MODIFY `StudentId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `ClassId` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `grouplist`
--
ALTER TABLE `grouplist`
  MODIFY `GId` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `insti`
--
ALTER TABLE `insti`
  MODIFY `InstiId` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `LesId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `NotifiId` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `paydata`
--
ALTER TABLE `paydata`
  MODIFY `PDId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PayId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pdfcount`
--
ALTER TABLE `pdfcount`
  MODIFY `PCountId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peaper`
--
ALTER TABLE `peaper`
  MODIFY `PeaperId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quction`
--
ALTER TABLE `quction`
  MODIFY `QuizId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recaccess`
--
ALTER TABLE `recaccess`
  MODIFY `AccId` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `userdata`
--
ALTER TABLE `userdata`
  MODIFY `UdId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
