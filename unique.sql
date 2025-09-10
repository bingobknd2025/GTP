-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 09:18 AM
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
-- Database: `unique`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('unique_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:5:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"d\";s:10:\"group_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:82:{i:0;a:5:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"role-list\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Role Master\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:4;i:2;i:6;i:3;i:7;i:4;i:8;i:5;i:10;i:6;i:11;}}i:1;a:5:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"role-create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Role Master\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:4;i:2;i:6;i:3;i:7;i:4;i:8;i:5;i:10;i:6;i:11;}}i:2;a:5:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"role-edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Role Master\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:4;i:2;i:6;i:3;i:7;i:4;i:8;i:5;i:10;i:6;i:11;}}i:3;a:5:{s:1:\"a\";i:6;s:1:\"b\";s:11:\"role-delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Role Master\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:4;i:2;i:6;i:3;i:7;i:4;i:8;i:5;i:10;i:6;i:11;}}i:4;a:5:{s:1:\"a\";i:7;s:1:\"b\";s:9:\"User List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"User Master\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:8;i:4;i:11;}}i:5;a:5:{s:1:\"a\";i:8;s:1:\"b\";s:8:\"User Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"User Master\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:8;i:4;i:11;}}i:6;a:5:{s:1:\"a\";i:9;s:1:\"b\";s:9:\"User Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"User Master\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:8;i:4;i:11;}}i:7;a:5:{s:1:\"a\";i:10;s:1:\"b\";s:11:\"User Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"User Master\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:8;i:4;i:11;}}i:8;a:5:{s:1:\"a\";i:11;s:1:\"b\";s:22:\"User Excel Import View\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"User Master\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:8;i:4;i:11;}}i:9;a:5:{s:1:\"a\";i:12;s:1:\"b\";s:17:\"User Excel Import\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"User Master\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:8;i:4;i:11;}}i:10;a:5:{s:1:\"a\";i:13;s:1:\"b\";s:9:\"Zone List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Zone Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:11;a:5:{s:1:\"a\";i:14;s:1:\"b\";s:8:\"Zone Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Zone Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:12;a:5:{s:1:\"a\";i:15;s:1:\"b\";s:9:\"Zone Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Zone Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:13;a:5:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"Zone Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"Zone Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:14;a:5:{s:1:\"a\";i:17;s:1:\"b\";s:10:\"Brand List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Brand Master\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:9;i:2;i:11;}}i:15;a:5:{s:1:\"a\";i:18;s:1:\"b\";s:9:\"Brand Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Brand Master\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:9;i:2;i:11;}}i:16;a:5:{s:1:\"a\";i:19;s:1:\"b\";s:10:\"Brand Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Brand Master\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:9;i:2;i:11;}}i:17;a:5:{s:1:\"a\";i:20;s:1:\"b\";s:12:\"Brand Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Brand Master\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:9;i:2;i:11;}}i:18;a:5:{s:1:\"a\";i:21;s:1:\"b\";s:21:\"Product Category List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Product Category Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:19;a:5:{s:1:\"a\";i:22;s:1:\"b\";s:20:\"Product Category Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Product Category Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:20;a:5:{s:1:\"a\";i:23;s:1:\"b\";s:21:\"Product Category Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Product Category Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:21;a:5:{s:1:\"a\";i:24;s:1:\"b\";s:23:\"Product Category Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Product Category Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:22;a:5:{s:1:\"a\";i:25;s:1:\"b\";s:9:\"User View\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:11:\"User Master\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:8;i:4;i:11;}}i:23;a:5:{s:1:\"a\";i:26;s:1:\"b\";s:10:\"Model List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Model Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:24;a:5:{s:1:\"a\";i:27;s:1:\"b\";s:9:\"Model Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Model Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:25;a:5:{s:1:\"a\";i:28;s:1:\"b\";s:10:\"Model Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Model Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:26;a:5:{s:1:\"a\";i:29;s:1:\"b\";s:12:\"Model Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:12:\"Model Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:27;a:5:{s:1:\"a\";i:30;s:1:\"b\";s:17:\"Concern Type List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Concern Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:28;a:5:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"Concern Type Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Concern Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:29;a:5:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"Concern Type Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Concern Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:30;a:5:{s:1:\"a\";i:33;s:1:\"b\";s:19:\"Concern Type Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Concern Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:31;a:5:{s:1:\"a\";i:34;s:1:\"b\";s:21:\"Concern Sub Type List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Concern Sub Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:32;a:5:{s:1:\"a\";i:35;s:1:\"b\";s:20:\"Concern Sub Type Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Concern Sub Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:33;a:5:{s:1:\"a\";i:36;s:1:\"b\";s:21:\"Concern Sub Type Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Concern Sub Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:34;a:5:{s:1:\"a\";i:37;s:1:\"b\";s:23:\"Concern Sub Type Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Concern Sub Type Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:35;a:5:{s:1:\"a\";i:38;s:1:\"b\";s:17:\"Call Concern List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Call Concern Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:36;a:5:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"Call Concern Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Call Concern Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:37;a:5:{s:1:\"a\";i:40;s:1:\"b\";s:17:\"Call Concern Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Call Concern Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:38;a:5:{s:1:\"a\";i:41;s:1:\"b\";s:19:\"Call Concern Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:19:\"Call Concern Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:39;a:5:{s:1:\"a\";i:42;s:1:\"b\";s:17:\"Generate Call Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:20:\"Generate Call Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:40;a:5:{s:1:\"a\";i:43;s:1:\"b\";s:19:\"Monitoring Platform\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Job Monitoring Platform\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:41;a:5:{s:1:\"a\";i:44;s:1:\"b\";s:17:\"Creating Platform\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:24:\"Creating Platform Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:42;a:5:{s:1:\"a\";i:45;s:1:\"b\";s:23:\"Multiple Cases Platform\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:30:\"Multiple Cases Platform Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:43;a:5:{s:1:\"a\";i:46;s:1:\"b\";s:26:\"Multiple Call Cancellation\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:33:\"Multiple Call Cancellation Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:44;a:5:{s:1:\"a\";i:49;s:1:\"b\";s:17:\"Job Assign Failed\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:24:\"Job Assign Failed Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:45;a:5:{s:1:\"a\";i:50;s:1:\"b\";s:15:\"Job Status List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Job Status Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:46;a:5:{s:1:\"a\";i:51;s:1:\"b\";s:14:\"Job Status Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Job Status Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:47;a:5:{s:1:\"a\";i:52;s:1:\"b\";s:15:\"Job Status Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Job Status Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:48;a:5:{s:1:\"a\";i:53;s:1:\"b\";s:17:\"Job Status Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Job Status Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:49;a:5:{s:1:\"a\";i:54;s:1:\"b\";s:16:\"Import Call View\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:21:\"Batch Activity Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:50;a:5:{s:1:\"a\";i:55;s:1:\"b\";s:21:\"Generate Part No List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Generate Part No Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:51;a:5:{s:1:\"a\";i:56;s:1:\"b\";s:20:\"Generate Part No Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Generate Part No Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:52;a:5:{s:1:\"a\";i:57;s:1:\"b\";s:21:\"Generate Part No Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Generate Part No Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:53;a:5:{s:1:\"a\";i:58;s:1:\"b\";s:29:\"Upload PartNumber Using Excel\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:26:\"Stock and Inventory Master\";s:1:\"r\";a:1:{i:0;i:11;}}i:54;a:5:{s:1:\"a\";i:59;s:1:\"b\";s:20:\"Generate Call Update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:20:\"Generate Call Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:55;a:5:{s:1:\"a\";i:60;s:1:\"b\";s:23:\"Job Monitoring Platform\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:23:\"Job Monitoring Platform\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:56;a:5:{s:1:\"a\";i:61;s:1:\"b\";s:25:\"Job Monitoring With Image\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:25:\"Job Monitoring With Image\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:57;a:5:{s:1:\"a\";i:62;s:1:\"b\";s:10:\"Accept Job\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:10:\"Accept Job\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:58;a:5:{s:1:\"a\";i:65;s:1:\"b\";s:15:\"Engineer Master\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Engineer Master\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:59;a:5:{s:1:\"a\";i:67;s:1:\"b\";s:20:\"Generate Call Master\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:20:\"Generate Call Master\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:60;a:5:{s:1:\"a\";i:68;s:1:\"b\";s:22:\"Job Monitring Platform\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:22:\"Job Monitring Platform\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:61;a:5:{s:1:\"a\";i:69;s:1:\"b\";s:14:\"Batch Activity\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:14:\"Batch Activity\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:62;a:5:{s:1:\"a\";i:70;s:1:\"b\";s:17:\"Stock & Inventory\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Stock & Inventory\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:11;}}i:63;a:5:{s:1:\"a\";i:71;s:1:\"b\";s:17:\"Reports Managment\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Reports Managment\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:11;}}i:64;a:5:{s:1:\"a\";i:72;s:1:\"b\";s:13:\"Setting Panel\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:13:\"Setting Panel\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:65;a:5:{s:1:\"a\";i:73;s:1:\"b\";s:14:\"Core Managment\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:14:\"Core Managment\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:66;a:5:{s:1:\"a\";i:74;s:1:\"b\";s:17:\"Invoice Managment\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Invoice Managment\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:67;a:5:{s:1:\"a\";i:75;s:1:\"b\";s:27:\"Job Monitoring Platform ASP\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:27:\"Job Monitoring Platform ASP\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:68;a:5:{s:1:\"a\";i:76;s:1:\"b\";s:13:\"Engineer List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Engineer Master\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:69;a:5:{s:1:\"a\";i:77;s:1:\"b\";s:12:\"Engineer Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Engineer Master\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:70;a:5:{s:1:\"a\";i:78;s:1:\"b\";s:13:\"Engineer Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Engineer Master\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:71;a:5:{s:1:\"a\";i:79;s:1:\"b\";s:15:\"Engineer Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:15:\"Engineer Master\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:11;}}i:72;a:5:{s:1:\"a\";i:80;s:1:\"b\";s:10:\"My Profile\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:14:\"Profile Master\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:11;}}i:73;a:5:{s:1:\"a\";i:81;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:74;a:5:{s:1:\"a\";i:82;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:75;a:5:{s:1:\"a\";i:83;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:76;a:5:{s:1:\"a\";i:84;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:77;a:5:{s:1:\"a\";i:85;s:1:\"b\";s:14:\"Franchise List\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:78;a:5:{s:1:\"a\";i:86;s:1:\"b\";s:13:\"Franchise Add\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:79;a:5:{s:1:\"a\";i:87;s:1:\"b\";s:14:\"Franchise Edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:80;a:5:{s:1:\"a\";i:88;s:1:\"b\";s:14:\"Franchise View\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}i:81;a:5:{s:1:\"a\";i:89;s:1:\"b\";s:16:\"Franchise Delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";N;s:1:\"r\";a:1:{i:0;i:11;}}}s:5:\"roles\";a:9:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"Super Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:13:\"Administrator\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:8:\"SubAdmin\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:9:\"Only View\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:5:\"Stock\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:5:\"Zonal\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:3:\"ASP\";s:1:\"c\";s:3:\"web\";}}}', 1757053319);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchises`
--

CREATE TABLE `franchises` (
  `id` int(11) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `pincode` varchar(6) NOT NULL,
  `contact_no` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_person_name` varchar(255) NOT NULL,
  `contact_person_number` varchar(255) NOT NULL,
  `store_lat` varchar(100) DEFAULT NULL,
  `store_long` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Approved','Reject') NOT NULL DEFAULT 'Pending',
  `image` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `franchises`
--

INSERT INTO `franchises` (`id`, `code`, `name`, `address`, `pincode`, `contact_no`, `email`, `password`, `contact_person_name`, `contact_person_number`, `store_lat`, `store_long`, `status`, `image`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 'jkk', 'jks', 'idka kj', '899889', '5445455454', 'admin@gmail.com', '$2y$12$eU8vq4IFk9TnarrovlHY7.3tQt0DaAeXnGQnN9wpUAgUjlvI3MIDO', 'jitu', '8998989898', '65432', '67543', 'Pending', '/images/franchises/20250904070556.heic', 1, 0, '2025-09-04 07:05:56', '2025-09-04 07:05:56');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_20_130120_create_products_table', 2),
(5, '2025_05_20_130505_create_permission_tables', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 11),
(11, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(3, 'role-list', 'web', 'Role Master', '2025-05-21 05:10:03', '2025-05-21 05:10:03'),
(4, 'role-create', 'web', 'Role Master', '2025-05-21 05:10:03', '2025-05-21 05:10:03'),
(5, 'role-edit', 'web', 'Role Master', '2025-05-21 05:10:03', '2025-05-21 05:10:03'),
(6, 'role-delete', 'web', 'Role Master', '2025-05-21 05:10:03', '2025-05-21 05:10:03'),
(7, 'User List', 'web', 'User Master', '2025-05-30 12:19:35', '2025-05-30 12:19:35'),
(8, 'User Add', 'web', 'User Master', '2025-05-30 12:21:13', '2025-05-30 12:21:13'),
(9, 'User Edit', 'web', 'User Master', '2025-06-04 05:12:13', '2025-06-04 05:12:13'),
(10, 'User Delete', 'web', 'User Master', '2025-06-04 05:12:13', '2025-06-04 05:12:13'),
(11, 'User Excel Import View', 'web', 'User Master', '2025-06-04 05:14:30', '2025-06-04 05:14:30'),
(12, 'User Excel Import', 'web', 'User Master', '2025-06-04 05:14:30', '2025-06-04 05:14:30'),
(13, 'Zone List', 'web', 'Zone Master', '2025-06-04 05:19:20', '2025-06-04 05:19:20'),
(14, 'Zone Add', 'web', 'Zone Master', '2025-06-04 05:19:20', '2025-06-04 05:19:20'),
(15, 'Zone Edit', 'web', 'Zone Master', '2025-06-04 05:19:50', '2025-06-04 05:19:50'),
(16, 'Zone Delete', 'web', 'Zone Master', '2025-06-04 05:19:50', '2025-06-04 05:19:50'),
(17, 'Brand List', 'web', 'Brand Master', '2025-06-04 05:21:55', '2025-06-04 05:21:55'),
(18, 'Brand Add', 'web', 'Brand Master', '2025-06-04 05:21:55', '2025-06-04 05:21:55'),
(19, 'Brand Edit', 'web', 'Brand Master', '2025-06-04 05:22:47', '2025-06-04 05:22:47'),
(20, 'Brand Delete', 'web', 'Brand Master', '2025-06-04 05:22:47', '2025-06-04 05:22:47'),
(21, 'Product Category List', 'web', 'Product Category Master', '2025-06-04 05:24:17', '2025-06-04 05:24:17'),
(22, 'Product Category Add', 'web', 'Product Category Master', '2025-06-04 05:24:17', '2025-06-04 05:24:17'),
(23, 'Product Category Edit', 'web', 'Product Category Master', '2025-06-04 05:24:33', '2025-06-04 05:24:33'),
(24, 'Product Category Delete', 'web', 'Product Category Master', '2025-06-04 05:24:52', '2025-06-04 05:24:52'),
(25, 'User View', 'web', 'User Master', '2025-06-04 05:32:53', '2025-06-04 05:32:53'),
(26, 'Model List', 'web', 'Model Master', '2025-06-04 06:29:33', '2025-06-04 06:29:33'),
(27, 'Model Add', 'web', 'Model Master', '2025-06-04 06:29:33', '2025-06-04 06:29:33'),
(28, 'Model Edit', 'web', 'Model Master', '2025-06-04 06:30:08', '2025-06-04 06:30:08'),
(29, 'Model Delete', 'web', 'Model Master', '2025-06-04 06:30:08', '2025-06-04 06:30:08'),
(30, 'Concern Type List', 'web', 'Concern Type Master', '2025-06-04 08:53:54', '2025-06-04 08:53:54'),
(31, 'Concern Type Add', 'web', 'Concern Type Master', '2025-06-04 08:53:54', '2025-06-04 08:53:54'),
(32, 'Concern Type Edit', 'web', 'Concern Type Master', '2025-06-04 08:54:31', '2025-06-04 08:54:31'),
(33, 'Concern Type Delete', 'web', 'Concern Type Master', '2025-06-04 08:54:31', '2025-06-04 08:54:31'),
(34, 'Concern Sub Type List', 'web', 'Concern Sub Type Master', '2025-06-04 10:03:28', '2025-06-04 10:03:28'),
(35, 'Concern Sub Type Add', 'web', 'Concern Sub Type Master', '2025-06-04 10:03:28', '2025-06-04 10:03:28'),
(36, 'Concern Sub Type Edit', 'web', 'Concern Sub Type Master', '2025-06-04 10:04:08', '2025-06-04 10:04:08'),
(37, 'Concern Sub Type Delete', 'web', 'Concern Sub Type Master', '2025-06-04 10:04:08', '2025-06-04 10:04:08'),
(38, 'Call Concern List', 'web', 'Call Concern Master', '2025-06-04 10:42:15', '2025-06-04 10:42:15'),
(39, 'Call Concern Add', 'web', 'Call Concern Master', '2025-06-04 10:42:15', '2025-06-04 10:42:15'),
(40, 'Call Concern Edit', 'web', 'Call Concern Master', '2025-06-04 10:42:42', '2025-06-04 10:42:42'),
(41, 'Call Concern Delete', 'web', 'Call Concern Master', '2025-06-04 10:42:42', '2025-06-04 10:42:42'),
(42, 'Generate Call Add', 'web', 'Generate Call Master', '2025-06-04 12:27:12', '2025-06-04 12:27:12'),
(43, 'Monitoring Platform', 'web', 'Job Monitoring Platform', '2025-06-07 11:27:03', '2025-06-07 11:27:03'),
(44, 'Creating Platform', 'web', 'Creating Platform Master', '2025-06-09 07:55:07', '2025-06-09 07:55:07'),
(45, 'Multiple Cases Platform', 'web', 'Multiple Cases Platform Master', '2025-06-09 08:53:20', '2025-06-09 08:53:20'),
(46, 'Multiple Call Cancellation', 'web', 'Multiple Call Cancellation Master', '2025-06-09 09:11:46', '2025-06-09 09:11:46'),
(49, 'Job Assign Failed', 'web', 'Job Assign Failed Master', '2025-06-09 09:39:56', '2025-06-09 09:39:56'),
(50, 'Job Status List', 'web', 'Job Status Master', '2025-06-09 12:26:43', '2025-06-09 12:26:43'),
(51, 'Job Status Add', 'web', 'Job Status Master', '2025-06-09 12:26:43', '2025-06-09 12:26:43'),
(52, 'Job Status Edit', 'web', 'Job Status Master', '2025-06-09 12:27:11', '2025-06-09 12:27:11'),
(53, 'Job Status Delete', 'web', 'Job Status Master', '2025-06-09 12:27:42', '2025-06-09 12:27:42'),
(54, 'Import Call View', 'web', 'Batch Activity Master', '2025-06-09 12:51:28', '2025-06-09 12:51:28'),
(55, 'Generate Part No List', 'web', 'Generate Part No Master', '2025-06-10 05:36:38', '2025-06-10 05:36:38'),
(56, 'Generate Part No Add', 'web', 'Generate Part No Master', '2025-06-10 05:36:38', '2025-06-10 05:36:38'),
(57, 'Generate Part No Edit', 'web', 'Generate Part No Master', '2025-06-10 05:36:57', '2025-06-10 05:36:57'),
(58, 'Upload PartNumber Using Excel', 'web', 'Stock and Inventory Master', '2025-06-10 08:27:24', '2025-06-10 08:27:24'),
(59, 'Generate Call Update', 'web', 'Generate Call Master', '2025-06-10 12:09:05', '2025-06-10 12:09:05'),
(60, 'Job Monitoring Platform', 'web', 'Job Monitoring Platform', '2025-06-13 08:10:24', '2025-06-13 08:10:24'),
(61, 'Job Monitoring With Image', 'web', 'Job Monitoring With Image', '2025-06-13 08:10:57', '2025-06-13 08:10:57'),
(62, 'Accept Job', 'web', 'Accept Job', '2025-06-13 08:10:57', '2025-06-13 08:10:57'),
(65, 'Engineer Master', 'web', 'Engineer Master', '2025-06-13 08:11:43', '2025-06-13 08:11:43'),
(67, 'Generate Call Master', 'web', 'Generate Call Master', '2025-06-13 08:17:11', '2025-06-13 08:17:11'),
(68, 'Job Monitring Platform', 'web', 'Job Monitring Platform', '2025-06-13 08:17:11', '2025-06-13 08:17:11'),
(69, 'Batch Activity', 'web', 'Batch Activity', '2025-06-13 08:19:19', '2025-06-13 08:19:19'),
(70, 'Stock & Inventory', 'web', 'Stock & Inventory', '2025-06-13 08:19:19', '2025-06-13 08:19:19'),
(71, 'Reports Managment', 'web', 'Reports Managment', '2025-06-13 08:19:34', '2025-06-13 08:19:34'),
(72, 'Setting Panel', 'web', 'Setting Panel', '2025-06-13 08:20:04', '2025-06-13 08:20:04'),
(73, 'Core Managment', 'web', 'Core Managment', '2025-06-13 08:20:04', '2025-06-13 08:20:04'),
(74, 'Invoice Managment', 'web', 'Invoice Managment', '2025-06-13 08:20:16', '2025-06-13 08:20:16'),
(75, 'Job Monitoring Platform ASP', 'web', 'Job Monitoring Platform ASP', '2025-06-13 08:28:02', '2025-06-13 08:28:02'),
(76, 'Engineer List', 'web', 'Engineer Master', '2025-06-13 10:23:56', '2025-06-13 10:23:56'),
(77, 'Engineer Add', 'web', 'Engineer Master', '2025-06-13 10:23:56', '2025-06-13 10:23:56'),
(78, 'Engineer Edit', 'web', 'Engineer Master', '2025-06-13 10:24:27', '2025-06-13 10:24:27'),
(79, 'Engineer Delete', 'web', 'Engineer Master', '2025-06-13 10:24:27', '2025-06-13 10:24:27'),
(80, 'My Profile', 'web', 'Profile Master', '2025-06-13 11:01:29', '2025-06-13 11:01:29'),
(81, 'create products', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(82, 'view products', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(83, 'edit products', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(84, 'delete products', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(85, 'Franchise List', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(86, 'Franchise Add', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(87, 'Franchise Edit', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(88, 'Franchise View', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51'),
(89, 'Franchise Delete', 'web', NULL, '2025-09-04 00:51:51', '2025-09-04 00:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`id`, `category_name`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'LED TV', 0, '2025-06-03 14:01:40', '2025-06-03 14:05:41'),
(2, 'Cooler', 0, '2025-06-04 12:41:40', '2025-06-04 12:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2025-05-20 23:09:22', '2025-05-20 23:09:22'),
(4, 'Administrator', 'web', '2025-06-09 06:30:59', '2025-06-09 06:30:59'),
(5, 'ASP', 'web', '2025-06-09 06:31:15', '2025-06-09 06:31:15'),
(6, 'SubAdmin', 'web', '2025-06-09 06:31:35', '2025-06-09 06:31:35'),
(7, 'Employee', 'web', '2025-06-09 06:31:48', '2025-06-09 06:31:48'),
(8, 'Only View', 'web', '2025-06-09 06:32:01', '2025-06-09 06:32:01'),
(9, 'Zonal', 'web', '2025-06-09 06:32:15', '2025-06-09 06:32:15'),
(10, 'Stock', 'web', '2025-06-09 06:32:36', '2025-06-09 06:32:36'),
(11, 'admin', 'web', '2025-09-04 00:51:51', '2025-09-04 00:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(3, 1),
(3, 4),
(3, 6),
(3, 7),
(3, 8),
(3, 10),
(3, 11),
(4, 1),
(4, 4),
(4, 6),
(4, 7),
(4, 8),
(4, 10),
(4, 11),
(5, 1),
(5, 4),
(5, 6),
(5, 7),
(5, 8),
(5, 10),
(5, 11),
(6, 1),
(6, 4),
(6, 6),
(6, 7),
(6, 8),
(6, 10),
(6, 11),
(7, 1),
(7, 6),
(7, 7),
(7, 8),
(7, 11),
(8, 1),
(8, 6),
(8, 7),
(8, 8),
(8, 11),
(9, 1),
(9, 6),
(9, 7),
(9, 8),
(9, 11),
(10, 1),
(10, 6),
(10, 7),
(10, 8),
(10, 11),
(11, 1),
(11, 6),
(11, 7),
(11, 8),
(11, 11),
(12, 1),
(12, 6),
(12, 7),
(12, 8),
(12, 11),
(13, 1),
(13, 11),
(14, 1),
(14, 11),
(15, 1),
(15, 11),
(16, 1),
(16, 11),
(17, 1),
(17, 9),
(17, 11),
(18, 1),
(18, 9),
(18, 11),
(19, 1),
(19, 9),
(19, 11),
(20, 1),
(20, 9),
(20, 11),
(21, 1),
(21, 11),
(22, 1),
(22, 11),
(23, 1),
(23, 11),
(24, 1),
(24, 11),
(25, 1),
(25, 6),
(25, 7),
(25, 8),
(25, 11),
(26, 1),
(26, 11),
(27, 1),
(27, 11),
(28, 1),
(28, 11),
(29, 1),
(29, 11),
(30, 1),
(30, 11),
(31, 1),
(31, 11),
(32, 1),
(32, 11),
(33, 1),
(33, 11),
(34, 1),
(34, 11),
(35, 1),
(35, 11),
(36, 1),
(36, 11),
(37, 1),
(37, 11),
(38, 1),
(38, 11),
(39, 1),
(39, 11),
(40, 1),
(40, 11),
(41, 1),
(41, 11),
(42, 1),
(42, 11),
(43, 1),
(43, 11),
(44, 1),
(44, 11),
(45, 1),
(45, 11),
(46, 1),
(46, 11),
(49, 1),
(49, 11),
(50, 1),
(50, 11),
(51, 1),
(51, 11),
(52, 1),
(52, 11),
(53, 1),
(53, 11),
(54, 1),
(54, 11),
(55, 1),
(55, 11),
(56, 1),
(56, 11),
(57, 1),
(57, 11),
(58, 11),
(59, 1),
(59, 11),
(60, 1),
(60, 11),
(61, 5),
(61, 11),
(62, 5),
(62, 11),
(65, 5),
(65, 11),
(67, 1),
(67, 11),
(68, 1),
(68, 11),
(69, 1),
(69, 11),
(70, 1),
(70, 5),
(70, 11),
(71, 1),
(71, 5),
(71, 11),
(72, 1),
(72, 11),
(73, 1),
(73, 11),
(74, 1),
(74, 11),
(75, 5),
(75, 11),
(76, 5),
(76, 11),
(77, 5),
(77, 11),
(78, 5),
(78, 11),
(79, 5),
(79, 11),
(80, 1),
(80, 5),
(80, 11),
(81, 11),
(82, 11),
(83, 11),
(84, 11),
(85, 11),
(86, 11),
(87, 11),
(88, 11),
(89, 11);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1hlCEY7g5NuK1dEd56fL1lQf24HRXoJ7yXzbGl7h', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHRra1h3TmJ6eGdiQUZOelg5OUZ6S2hpcUhld3JJd2NsQnZNTUpDbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756964724),
('1NqUkNIKq87X09sYNdsrRWa7YFQXXUbUiZ4NsJfN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZExCWnh0VUk0TEJxQzg2cGNvMDVZZHZsQU14ZTZlbUVWWVQ3NFU1dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756963916),
('6MrWBcBv649L3xiWrnFxise0dyHOR02oJU28LiYT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMzZkZHRSNHl3TTVLZ1BjQ0l6S2tyc080TzlGMTV4b2E4M21BQ2g3MCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756964153),
('8MQkD01PwLVyfv7lCPgOQECQeLdwk3jj5GQGMuXz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNTd1UTdoaXFCMUJRTkVjOVplZnJjc2xoNmNtNEdiZ0RyZFhvbXdHUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964727),
('bJj0CLKMjQH1lzeEHpSRLSNskWpGyAcKXx6tKgPL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVkFVanB2ZWN6UWtwc3hNc2hUNzFqWFltYkdvdWRlVGZMWHRnb29oeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756964157),
('C8EyUfTzyGRGXYXb0H5kA9p36PmFkFcRjtYcWpD7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiY3lMZlJEd0xqalhGWDJ3SzgxU3RkZG1wM0dadUlKZXFpdUpsQlJPeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964267),
('CIb66xbfXeJSq0BAwZ7RwvqidqRFwgRMWpqrfpYD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjlQTmhDbmlrMjdjQVYya3BsSXRqUkxlNlU1T20zRVZQYWttMjZxZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756964447),
('d2bthcdmRpbgQNSOKzlc2L6mMtlhSOqWx52mvNSX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZERqSDZ3bXhqdUNHalRsbVJxU0tXN2VyelVtZ2R3cVpiUE1UTmZUQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756963912),
('eefZA3b6hKHYOwVzvd8FTC6MMhgb1KbysLVFDPIQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidXFCWDdyRDZyWnJ0Y1N2NllPa0dFVlF4dlFxNnpsUlp5bmhWQWVBVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756964046),
('eNsekR7chmIcBsJ4eWpvhxD9XK0bJdBxTO4MxS2g', 11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibm9KOGY3SzlXUDM1SXU1S3RTRVo3MGdBUm9HU2JIa3FmQmdCOGtpVCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cDovL2xvY2FsaG9zdC91bmlxdWUvcHVibGljL2FkbWluL2ZyYW5jaGlzZXMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NjoiaHR0cDovL2xvY2FsaG9zdC91bmlxdWUvcHVibGljL2FkbWluL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO30=', 1756970160),
('h5R2PxrlNgmYn9GNwDjaMyK4ewGV6GLZAGYJLij1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlRLd0ZpZmhLYnBpNktpQzJaSlBlVThiYkYxRUdFTExORUxUUFF4MiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756963866),
('Hpl2xvCPqrIYYFpLyZI4Ps7X9simGP3MDEOZzMDL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVmsyZnMycWhoWkpZYjJvSWl0QVMwdjNQUzNVM1Y5YWEyZkpWbW92eiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964648),
('hQSbGPzqFASdCzlEZZdVPl9XzlFc6AmlA6Sb2k4d', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRUFnZVJWUVZPbGVaTmo4TTdCZ1lrRnNBblM4UWJwRkxkRjlHdjdzNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964037),
('JeUcjHO1nB0Cr5VUZCQXSgUTE1oejfDM9nyyYyq7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY3FBd1gyUG1QZ1FhU050Vjh6cjZwNmlIMmpKaGhuSDZ6OEhDQ0VPUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756963945),
('owjrXIBpR20f4n0EbNXxyYGurxT3TMPOj4UrAg8a', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieDdiWmx3QzFiTVpDazBVak5iSjNRc1lDZFM0MnI0b3AyU3p2RlFIUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756964631),
('pE0Uz2W0ahYVKqAtC7EX42JoJ77YOo7QHydLCKpR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWXB6YnU4dmdZaDZFeTJOaEZUTFJ3T1dzNXRxdU5UZG4yemJsbmJjWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964464),
('psyR7HMbfP4Zvp6eO3EzDMRu1q5kGdpLWuxISqFR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYjc4OHZ6RThoWUhMWTFUaGRlNU9pVlhjZjlFWW9xSXJCZ1pKOWY2VSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964755),
('QSJAsqOvNaedNVGtrhqAIQ6K8oemSaIhDdaHiRyA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWGQwb0J4aEd2MGdLVlZWOGNUcWFsRlhGUXBTaTFMdDYzQUFDRDI4NSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756963900),
('rZ3eS1HYBnTVzgXF9wkCHmUMf3bCBOTMLgfItc9P', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMWJNT2xSeGZ3d1RuNUI2cWVPRERWSUtqOHJvVEVNWHlRQ3lYb2F6YSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756963924),
('T8ghxvFhqNtUvnoaTSZdWRe4AF7VeNmhy26uv1Df', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicGJuVTNGS3RiaUdmdk9XOVEydmdyM24wc3RjTzNScEo3dDVTYVNINiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964779),
('VzQvdX6ICJ6aFUdnPe83ynP33NmPPDjF0dxf2PtE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZTFNZkZndU4ycHJ4WDRHTFFabkV3cEZaSGo3MWNyM2FzaW9laVVrcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly9sb2NhbGhvc3QvdW5pcXVlL3B1YmxpYy9hZG1pbi9mcmFuY2hpc2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1756970129),
('WAI1yZJp8SMQh6j3VJNVUW3LrACmlXnyLm2btg3z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzNBcjF6c1k3alk3TmE0OUJla3lIdEg4NHBGcmVDYWRIbWRvQk16MCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756964149),
('yiDi1Z931ybC0dhXhTYLkJa0e5bk46AbhIeLqoJY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUzRvWTFPR2hqRlM5ZFh5RHRCWnlMcldvOXNWaEhtTVBEVm00V1E2TSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756963946),
('ypVx9mp146aXtg4Ba9cTlbHZtEpEgYXPL0DsY5ZZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoia1BNWndYRmxFZ2JWZ0VsQkE1UHJwZXB2aDMyY1pPSlUwckVoczVzZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756964510),
('YSopA1TOztipYjcgQfE7ayh6ITTBVeucGbVpIhLc', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib25iOU1wN1FqS2NGQUVMYzJTeUhseExSTG1ZVmtGNGdXck9wbFlZQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cDovL2xvY2FsaG9zdC91bmlxdWUvcHVibGljL3JvbGUvaW5kZXgiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MToiaHR0cDovL2xvY2FsaG9zdC91bmlxdWUvcHVibGljL3JvbGUvaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756966919);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state_name`) VALUES
(1, 'Andhra Pradesh'),
(2, 'Arunachal Pradesh'),
(3, 'Assam'),
(4, 'Bihar'),
(5, 'Chhattisgarh'),
(6, 'Goa'),
(7, 'Gujarat'),
(8, 'Haryana'),
(9, 'Himachal Pradesh'),
(10, 'Jharkhand'),
(11, 'Karnataka'),
(12, 'Kerala'),
(13, 'Madhya Pradesh'),
(14, 'Maharashtra'),
(15, 'Manipur'),
(16, 'Meghalaya'),
(17, 'Mizoram'),
(18, 'Nagaland'),
(19, 'Odisha'),
(20, 'Punjab'),
(21, 'Rajasthan'),
(22, 'Sikkim'),
(23, 'Tamil Nadu'),
(24, 'Telangana'),
(25, 'Tripura'),
(26, 'Uttar Pradesh'),
(27, 'Uttarakhand'),
(28, 'West Bengal'),
(29, 'Andaman and Nicobar Islands'),
(30, 'Chandigarh'),
(31, 'Dadra and Nagar Haveli and Daman and Diu'),
(32, 'Delhi'),
(33, 'Jammu and Kashmir'),
(34, 'Ladakh'),
(35, 'Lakshadweep'),
(36, 'Puducherry');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `pan_no` varchar(255) DEFAULT NULL,
  `pin_code` varchar(11) DEFAULT NULL,
  `role_id` varchar(255) DEFAULT NULL,
  `zone` varchar(255) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `brand_ids` varchar(255) DEFAULT NULL,
  `company_owner_name` varchar(255) DEFAULT NULL,
  `official_telephone` varchar(255) DEFAULT NULL,
  `total_engineer` varchar(255) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `mobile_no`, `remember_token`, `company_name`, `user_id`, `address`, `city`, `state`, `gst_no`, `pan_no`, `pin_code`, `role_id`, `zone`, `zone_id`, `brand_ids`, `company_owner_name`, `official_telephone`, `total_engineer`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@gmail.com', NULL, '$2y$12$R9UVehO1u7kqoMCTk/2jM.8SJWoTVw5IvahpmLZZeZKDWQlwSz602', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 0, '2025-05-20 06:39:00', '2025-09-04 00:19:55'),
(11, 'arjun', 'arj@gmail.com', NULL, '$2y$12$ZusFtfIXVdG3NTtdzeTOu.JgYq6dZAUwIhfS8FdVE3TWTpLOhQA.G', '9636617401', NULL, 'A J CARE CENTRE', 'arj', 'DEVKALI ,JOGAPUR PRATAPGARH UTTAR PRADESH 230001', 'Pratapgarh', 'UTTAR PRADESH', '09DQOPP8042Q1ZP', '', '303604', '2', 'North', 1, '[]', 'aa', '1234111111', '12', 0, '2025-06-03 07:38:11', '2025-09-04 01:45:58'),
(12, 'Numani aa', 'NOMANI96@GMAIL.COM', NULL, '$2y$12$QTSdVsMBkOv7nxVzGBLVDO2wxd5Jr9u3ho8JpKyHcc1SW28gLJfw6', '7007306892', NULL, 'A N Service Point', NULL, 'munshipura old sell tax road near chappanbhog sweet\'s home Mau UTTAR PRADESH 275101', 'MAU', 'UTTAR PRADESH', '09BPEPN8874D1ZG', '', '275101', '2', 'North', 1, '[\"1\",\"2\",\"3\"]', NULL, NULL, NULL, 1, '2025-06-03 07:38:12', '2025-06-13 02:02:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `franchises`
--
ALTER TABLE `franchises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `franchises`
--
ALTER TABLE `franchises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
