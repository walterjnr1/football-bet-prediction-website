-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2025 at 12:42 PM
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
-- Database: `confirmbet`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `ip_address` varchar(34) NOT NULL,
  `action_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `ip_address`, `action_time`) VALUES
(93, 8, 'logged in as Mmakamba Patrick on: 2025-07-25 17:25:49', 'users', 8, '::1', '2025-07-25 17:25:49'),
(94, 8, 'Logged Out from Website on: 2025-07-25 17:26:07', '', 0, '::1', '2025-07-25 17:26:07'),
(95, 8, 'logged in as Mmakamba Patrick on: 2025-07-25 17:26:09', 'users', 8, '::1', '2025-07-25 17:26:09'),
(96, 8, 'Logged Out from Website on: 2025-07-25 17:26:26', '', 0, '::1', '2025-07-25 17:26:26'),
(97, 1, 'logged in as Esther Sunday on: 2025-07-25 17:26:32', 'users', 1, '::1', '2025-07-25 17:26:32'),
(98, 1, 'Posted a new prediction on 2025-07-25 17:27:17', 'predictions', 17, '::1', '2025-07-25 17:27:38'),
(99, 1, 'Sent VIP message: \"\"Insufficient balance or invalid coverage\" to VIP ID 8', 'vip_messages', NULL, '::1', '2025-07-25 17:33:03'),
(100, 1, 'logged in as Esther Sunday on: 2025-07-25 17:39:18', 'users', 1, '::1', '2025-07-25 17:39:18'),
(101, 1, 'Posted a new prediction on 2025-07-25 17:39:43', 'predictions', 18, '::1', '2025-07-25 17:40:04'),
(102, 1, 'Posted a new prediction on 2025-07-25 17:42:35', 'predictions', 19, '::1', '2025-07-25 17:42:56'),
(103, 1, 'Posted a new prediction on 2025-07-25 17:44:37', 'predictions', 20, '::1', '2025-07-25 17:44:58'),
(104, 1, 'Posted new prediction', 'predictions', 21, '::1', '2025-07-25 17:56:07'),
(105, NULL, 'created a VIP user with subscription Ediong Moses on: 2025-07-26 05:43:56', 'users', 14, '::1', '2025-07-26 05:44:40'),
(106, NULL, 'created a VIP user with subscription moses on: 2025-07-26 07:24:16', 'users', 15, '::1', '2025-07-26 06:24:59'),
(107, NULL, 'created a VIP user with subscription moses Ud on: 2025-07-26 07:59:40', 'users', 22, '::1', '2025-07-26 07:00:23'),
(108, 1, 'logged in as Esther Sunday on: 2025-07-26 07:24:26', 'users', 1, '::1', '2025-07-26 07:24:26'),
(109, NULL, 'created a VIP user with subscription Ndueso Walter on: 2025-07-26 08:29:01', 'users', 24, '::1', '2025-07-26 07:29:06'),
(110, 1, 'Sent VIP message: \"\"Insufficient balance or invalid coverage\" to VIP ID 24', 'vip_messages', NULL, '::1', '2025-07-26 07:33:57'),
(111, NULL, 'Created a VIP user with subscription: Emeka Offor on 2025-07-26 08:35:38', 'users', 25, '::1', '2025-07-26 07:35:42'),
(112, NULL, 'Created a VIP user with subscription: Ndina Usonf on 2025-07-26 08:48:07', 'users', 26, '::1', '2025-07-26 07:48:11'),
(113, 27, 'Created a VIP user with subscription: Mmakamba Okorie on 2025-07-26 08:50:37', 'users', 27, '::1', '2025-07-26 07:50:41'),
(114, 1, 'Logged Out from vip dashboard on: 2025-07-26 08:59:40', '', 0, '::1', '2025-07-26 07:59:40'),
(115, 27, 'logged in as Mmakamba Okorie on: 2025-07-26 07:59:44', 'users', 27, '::1', '2025-07-26 07:59:44'),
(116, 27, 'Logged Out from vip dashboard on: 2025-07-26 09:01:45', '', 0, '::1', '2025-07-26 08:01:45'),
(117, 27, 'logged in as Mmakamba Okorie on: 2025-07-26 08:01:46', 'users', 27, '::1', '2025-07-26 08:01:46'),
(118, 27, 'Changed password on: 2025-07-26 15:38:09', 'users', 27, '::1', '2025-07-26 14:38:18'),
(119, 27, 'Submitted a contact message.', 'users', 27, '::1', '2025-07-26 18:03:31'),
(120, 27, 'Submitted a contact message.', 'users', 27, '::1', '2025-07-26 18:04:26'),
(121, 27, 'Posted new prediction', 'predictions', 22, '::1', '2025-07-26 20:14:50'),
(122, 27, 'Posted new prediction', 'predictions', 23, '::1', '2025-07-26 20:15:22'),
(123, 27, 'Posted new prediction', 'predictions', 24, '::1', '2025-07-26 20:16:18'),
(124, 27, 'Posted new prediction', 'predictions', 25, '::1', '2025-07-26 20:20:39'),
(125, 27, 'Posted new prediction', 'predictions', 26, '::1', '2025-07-26 21:03:12'),
(126, 27, 'Posted new prediction', 'predictions', 28, '::1', '2025-07-26 21:12:01'),
(127, 27, 'Posted new prediction', 'predictions', 29, '::1', '2025-07-26 21:19:03'),
(128, 27, 'Posted new prediction', 'predictions', 30, '::1', '2025-07-26 21:19:38'),
(129, 27, 'Posted new prediction', 'predictions', 31, '::1', '2025-07-26 22:24:21'),
(130, 27, 'Posted new prediction', 'predictions', 32, '::1', '2025-07-26 22:26:42'),
(131, 27, 'logged in as Henry Okorie on: 2025-07-27 13:02:44', 'users', 27, '::1', '2025-07-27 13:02:44'),
(132, 27, 'Logged Out from vip dashboard on: 2025-07-27 14:02:49', '', 0, '::1', '2025-07-27 13:02:49'),
(133, 1, 'logged in as Esther Sunday on: 2025-07-27 13:02:53', 'users', 1, '::1', '2025-07-27 13:02:53'),
(134, 1, 'Logged Out from vip dashboard on: 2025-07-27 14:57:55', '', 0, '::1', '2025-07-27 13:57:55'),
(135, 1, 'logged in as Esther Sunday on: 2025-07-27 13:58:16', 'users', 1, '::1', '2025-07-27 13:58:16'),
(136, 1, 'Logged Out from admin dashboard on: 2025-07-27 14:58:22', '', 0, '::1', '2025-07-27 13:58:22'),
(137, 27, 'logged in as Henry Okorie on: 2025-07-27 13:58:27', 'users', 27, '::1', '2025-07-27 13:58:28'),
(138, 27, 'logged in as Henry Okorie on: 2025-07-27 14:00:19', 'users', 27, '::1', '2025-07-27 14:00:19'),
(139, 27, 'Logged Out from vip dashboard on: 2025-07-27 18:15:03', '', 0, '::1', '2025-07-27 17:15:03'),
(140, 27, 'Added review for user: Henry Okorie on 2025-07-27 22:34:30', 'reviews', 1, '::1', '2025-07-27 21:34:30'),
(141, 27, 'Added review for user: Mmakamba Patrick on 2025-07-27 22:34:46', 'reviews', 2, '::1', '2025-07-27 21:34:46'),
(142, 27, 'Added review for user: Mmakamba Patrick on 2025-07-27 22:36:30', 'reviews', 3, '::1', '2025-07-27 21:36:30'),
(143, 27, 'Added review for user: Mmakamba Patrick on 2025-07-27 22:40:50', 'reviews', 4, '::1', '2025-07-27 21:40:50'),
(144, 27, 'Posted new prediction', 'predictions', 33, '::1', '2025-07-27 22:29:47'),
(145, 27, 'Updated Website data on: 2025-07-28 09:12:02', 'website_settings', 1, '::1', '2025-07-28 08:12:02'),
(146, 27, 'Anonymous user sent a contact us message', '', 0, '::1', '2025-07-28 11:21:58'),
(147, 27, 'Anonymous user sent a contact us message', '', 0, '::1', '2025-07-28 11:25:35'),
(148, 27, 'Updated profile  on: 2025-07-28 14:05:33', 'users', 27, '::1', '2025-07-28 13:05:33'),
(149, NULL, 'User added review on: 2025-07-28 14:20:21', 'reviews', 0, '::1', '2025-07-28 13:20:21'),
(150, NULL, 'User added review on: 2025-07-28 14:20:41', 'reviews', 0, '::1', '2025-07-28 13:20:41'),
(151, 27, 'Upload profile data on: 2025-07-28 14:35:13', 'users', 27, '::1', '2025-07-28 13:35:13'),
(152, 27, 'Upload profile image on: 2025-07-28 14:35:38', 'users', 27, '::1', '2025-07-28 13:35:38'),
(153, 27, 'Sent contact message', 'users', 27, '::1', '2025-07-28 13:53:22'),
(155, 27, 'logged in as Henry Bassey on: 2025-08-07 22:32:28', 'users', 27, '::1', '2025-08-07 21:32:29'),
(156, 27, 'Logged Out from vip dashboard on: 2025-08-07 22:32:39', '', 0, '::1', '2025-08-07 21:32:39'),
(157, 1, 'logged in as Esther Sunday on: 2025-08-07 22:32:46', 'users', 1, '::1', '2025-08-07 21:32:46'),
(158, 1, 'Added news: Re: Minister Wike Is Beautifying Abuja FCT, Doing A Great Job - Issac Fayose(vid)', 'news', 5, '::1', '2025-08-07 21:33:56'),
(159, 1, 'Added news: ADC', 'news', 6, '::1', '2025-08-07 21:34:40'),
(160, 1, 'Updated Website data on: 2025-08-07 22:56:48', 'website_settings', 1, '::1', '2025-08-07 21:56:48'),
(161, 1, 'updated News (ID: 5) on: 2025-08-08 09:45:09', 'news', 5, '::1', '2025-08-08 08:45:09'),
(162, 1, 'Posted new prediction', 'predictions', 34, '::1', '2025-08-08 09:41:42'),
(163, 1, 'Posted new prediction', 'predictions', 35, '::1', '2025-08-08 16:38:40'),
(164, NULL, 'Registered as VIP on: 2025-08-10 13:27:21', 'users', 28, '::1', '2025-08-10 12:27:21'),
(165, NULL, 'Registered as VIP on: 2025-08-10 13:27:54', 'users', 29, '::1', '2025-08-10 12:27:54'),
(166, NULL, 'Registered as VIP on: 2025-08-10 13:53:57', 'users', 30, '::1', '2025-08-10 12:53:57'),
(167, NULL, 'Registered as VIP on: 2025-08-11 11:00:03', 'users', 31, '::1', '2025-08-11 10:00:11'),
(168, NULL, 'Registered as VIP on: 2025-08-11 11:02:58', 'users', 32, '::1', '2025-08-11 10:03:05'),
(169, NULL, 'Registered as VIP on: 2025-08-11 11:04:22', 'users', 33, '::1', '2025-08-11 10:04:28'),
(170, 34, 'Registered as VIP on: 2025-08-11 11:05:46', 'users', 34, '::1', '2025-08-11 10:05:53'),
(171, 34, 'Logged in as VIP on: 2025-08-11 11:18:47', 'users', 34, '::1', '2025-08-11 10:18:47'),
(172, 34, 'Logged in as VIP on: 2025-08-11 11:19:19', 'users', 34, '::1', '2025-08-11 10:19:19'),
(173, NULL, 'Saved a review on: 2025-08-11 11:40:12', 'reviews', 8, '::1', '2025-08-11 10:40:12'),
(174, NULL, 'Saved a review on: 2025-08-11 11:41:07', 'reviews', 9, '::1', '2025-08-11 10:41:07'),
(175, 34, 'Upload profile image on: 2025-08-11 11:44:36', 'users', 34, '::1', '2025-08-11 10:44:36'),
(176, 34, 'Updated profile data on: 2025-08-11 19:01:28', 'users', 34, '::1', '2025-08-11 18:01:28'),
(177, 34, 'Updated profile data on: 2025-08-11 19:02:19', 'users', 34, '::1', '2025-08-11 18:02:19'),
(178, 34, 'Posted new prediction', 'predictions', 36, '::1', '2025-08-12 17:28:11'),
(179, 34, 'Updated Website data on: 2025-08-12 19:01:35', 'website_settings', 1, '::1', '2025-08-12 18:01:35'),
(180, 34, 'Logged in as VIP on: 2025-08-12 19:16:52', 'users', 34, '::1', '2025-08-12 18:16:52'),
(181, 34, 'Updated Website data on: 2025-08-12 19:30:50', 'website_settings', 1, '::1', '2025-08-12 18:30:50'),
(182, 34, 'Subscribed to plan \'Star\' (Plan ID: 1, Subscription ID: 30)', 'subscriptions', 30, '::1', '2025-08-12 18:37:16'),
(183, 34, 'Subscribed to plan \'Star\' (Plan ID: 1, Subscription ID: 31)', 'subscriptions', 31, '::1', '2025-08-12 18:39:22'),
(184, 34, 'Subscribed to plan \'Star\' (Plan ID: 1, Subscription ID: 32)', 'subscriptions', 32, '::1', '2025-08-12 18:39:57'),
(185, 34, 'Subscribed to plan \'Gold\' (Plan ID: 3, Subscription ID: 33)', 'subscriptions', 33, '::1', '2025-08-12 18:41:43'),
(186, 34, 'Logged Out from vip dashboard on: 2025-08-13 09:18:30', '', 0, '::1', '2025-08-13 08:18:30'),
(187, 34, 'Logged in as VIP on: 2025-08-13 09:18:37', 'users', 34, '::1', '2025-08-13 08:18:37');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(4) NOT NULL,
  `type` varchar(5) NOT NULL,
  `code` varchar(15) NOT NULL,
  `bookmaker` varchar(25) NOT NULL,
  `country` varchar(25) NOT NULL,
  `no_matches` int(3) NOT NULL,
  `match_start_date` varchar(11) NOT NULL,
  `match_end_date` varchar(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `type`, `code`, `bookmaker`, `country`, `no_matches`, `match_start_date`, `match_end_date`, `created_at`) VALUES
(6, 'free', '763010', '22bet', 'Nigeria', 12, '2025-08-08', '2025-08-10', '2025-08-08 16:01:10'),
(7, 'vip', 'x2341', 'football.com', 'Belarus', 1, '2025-08-08', '2025-08-08', '2025-08-08 16:01:38'),
(8, 'free', '219000', 'sportybet', 'Nigeria', 4, '2025-08-08', '2025-08-10', '2025-08-08 16:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `leagues`
--

CREATE TABLE `leagues` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leagues`
--

INSERT INTO `leagues` (`id`, `name`, `country`) VALUES
(64, 'Ghana Premier League', 'Ghana'),
(65, 'Nigeria Professional Football League', 'Nigeria'),
(66, 'Nigeria National League', 'Nigeria'),
(67, 'Namibia Premiership', 'Namibia'),
(68, 'Zimbabwe Premier Soccer League', 'Zimbabwe'),
(69, 'Super League of Malawi', 'Malawi'),
(70, 'Botswana Premier League', 'Botswana'),
(71, 'Zambia Super League', 'Zambia'),
(72, 'South African Premier Division', 'South Africa'),
(73, 'National First Division', 'South Africa'),
(74, 'English Premier League', 'England'),
(75, 'EFL Championship', 'England'),
(76, 'EFL League One', 'England'),
(77, 'EFL League Two', 'England'),
(78, 'National League', 'England'),
(79, 'La Liga', 'Spain'),
(80, 'Segunda División', 'Spain'),
(81, 'Serie A', 'Italy'),
(82, 'Serie B', 'Italy'),
(83, 'Bundesliga', 'Germany'),
(84, '2. Bundesliga', 'Germany'),
(85, '3. Liga', 'Germany'),
(86, 'Ligue 1', 'France'),
(87, 'Ligue 2', 'France'),
(88, 'Eredivisie', 'Netherlands'),
(89, 'Keuken Kampioen Divisie', 'Netherlands'),
(90, 'Brasileirão Série A', 'Brazil'),
(91, 'Brasileirão Série B', 'Brazil'),
(92, 'Argentine Primera División', 'Argentina'),
(93, 'Primera Nacional', 'Argentina'),
(94, 'Major League Soccer', 'USA'),
(95, 'USL Championship', 'USA'),
(96, 'USL League One', 'USA'),
(97, 'Liga MX', 'Mexico'),
(98, 'Ascenso MX', 'Mexico'),
(99, 'Canadian Premier League', 'Canada'),
(100, 'J1 League', 'Japan'),
(101, 'J2 League', 'Japan'),
(102, 'K League 1', 'South Korea'),
(103, 'K League 2', 'South Korea'),
(104, 'Chinese Super League', 'China'),
(105, 'China League One', 'China'),
(106, 'Indian Super League', 'India'),
(107, 'I-League', 'India'),
(108, 'Saudi Pro League', 'Saudi Arabia'),
(109, 'Qatar Stars League', 'Qatar'),
(110, 'UAE Pro League', 'United Arab Emirates'),
(111, 'A-League Men', 'Australia'),
(112, 'National Premier Leagues', 'Australia'),
(113, 'New Zealand Football Championship', 'New Zealand'),
(115, 'UEFA champions league', '');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `published_at`) VALUES
(4, 'dsaas', '<p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">Dis one too don cash out.</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">He was abusing Wike a few months ago over Fubara and the N39 Billions cost of ICC .</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">Now it\'s the turn of Isaac Fayose to hail Wike.</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">Oh ! NAIJA ????</span></p>', 'uploadImage/news/6882283ad1c0d_logo_3.png', '2025-07-24 12:34:02'),
(5, 'Re: Minister Wike Is Beautifying Abuja FCT, Doing A Great Job - Issac Fayose(vid)', '<p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">Dis one too don cash out.</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">He was abusing Wike a few months ago over Fubara and the N39 Billions cost of ICC .</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">Now it\'s the turn of Isaac Fayose to hail Wike.</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(246, 246, 236);\">Oh ! NAIJA ????</span></p>', 'uploadImage/news/68951bc49294e_wike_abuja.jfif', '2025-08-07 21:33:00'),
(6, 'ADC', '<p><b style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\">The opposition coalition, African Democratic Congress, ADC, has called on the ruling All Progressives Congress, APC, to remove what it called unlawful campaign infrastructure for President Bola Tinubu’s re-election.</b><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(215, 224, 201);\">This was contained in a statement by the party’s Interim National Publicity Secretary, Bolaji Abdullahi.</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(215, 224, 201);\">Abdullahi alleged that the ruling party is neglecting Nigeria’s economic and security crises in pursuit of Tinubu’s re-election.</span><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><b style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\">According to him, Nigeria’s economic indicators have deteriorated since Tinubu took office, accusing the APC of failing to address the worsening power sector crisis.</b><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><br style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\"><b style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px;\">“For months now, organs of the APC have staged rallies and unveiled billboards endorsing President Tinubu for a second term — from Abuja to Port Harcourt, Minna, Kano, and Akure,”</b><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, sans-serif; font-size: 14.6667px; background-color: rgb(215, 224, 201);\">&nbsp;he said</span></p>', 'uploadImage/news/68951bf073703_online payment2.png', '2025-08-07 21:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(4) NOT NULL,
  `reference_id` varchar(25) NOT NULL,
  `user_id` int(4) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_method` varchar(25) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `reference_id`, `user_id`, `amount`, `payment_date`, `payment_method`, `status`) VALUES
(1, 'topup_1755027761319_34', 34, 110, '2025-08-12 19:42:48', '', ''),
(2, 'topup_1755028461120_34', 34, 110, '2025-08-12 19:56:26', '', ''),
(3, 'topup_1755028592968_34', 34, 110, '2025-08-12 19:56:40', '', ''),
(5, 'topup_1755029296634_34', 34, 110, '2025-08-12 20:11:20', '', ''),
(6, 'topup_1755029499288_34', 34, 122, '2025-08-12 20:11:46', '', ''),
(7, 'topup_1755069451689_34', 34, 268, '2025-08-13 07:21:35', 'Card', 'success'),
(8, 'topup_1755069819255_34', 34, 100, '2025-08-13 07:23:50', 'Card', 'success'),
(9, 'topup_1755069936037_34', 34, 110, '2025-08-13 07:25:45', 'Card', 'success'),
(10, '689c698f66cb2', 34, 5000, '2025-08-13 10:31:43', 'wallet', 'success'),
(11, '689c69ce39164', 34, 5000, '2025-08-13 10:32:46', 'wallet', 'success'),
(12, 'topup_1755081217184_34', 34, 5000, '2025-08-13 10:33:44', 'Card', 'success'),
(13, '689c6a1692349', 34, 5000, '2025-08-13 10:33:58', 'wallet', 'success'),
(14, '689c6aa0609e1', 34, 5000, '2025-08-13 10:36:16', 'wallet', 'success'),
(15, '689c6ba389aef', 34, 5000, '2025-08-13 10:40:35', 'wallet', 'success');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(4) NOT NULL,
  `name` varchar(120) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `duration` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `amount`, `duration`) VALUES
(1, 'Star', 5000, 7),
(2, 'Silver', 14000, 31),
(3, 'Gold', 34000, 365);

-- --------------------------------------------------------

--
-- Table structure for table `predictions`
--

CREATE TABLE `predictions` (
  `id` int(11) NOT NULL,
  `user_id` int(4) NOT NULL,
  `type` enum('free','fixed') NOT NULL,
  `sport` varchar(20) NOT NULL,
  `match_date` datetime NOT NULL,
  `league_id` int(11) NOT NULL,
  `team_home_id` int(11) NOT NULL,
  `team_away_id` int(11) NOT NULL,
  `prediction_text` text DEFAULT NULL,
  `odds` decimal(5,2) DEFAULT NULL,
  `result` enum('pending','won','lose') DEFAULT 'pending',
  `score` varchar(10) DEFAULT NULL,
  `match_analysis` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predictions`
--

INSERT INTO `predictions` (`id`, `user_id`, `type`, `sport`, `match_date`, `league_id`, `team_home_id`, `team_away_id`, `prediction_text`, `odds`, `result`, `score`, `match_analysis`, `created_at`) VALUES
(29, 1, 'free', 'football', '2025-08-08 01:22:00', 84, 9, 1, 'Anytime Goalscorer - Player B', 1.09, 'won', '3-4', '', '2025-07-26 21:18:50'),
(30, 1, 'fixed', 'football', '2025-07-25 04:25:00', 76, 6, 11, '1X2 - Home Win', 1.90, 'won', '2-1', '', '2025-07-26 21:19:24'),
(31, 1, 'fixed', 'football', '2025-07-27 02:25:00', 64, 6, 2, '1X2 - Away Win', 2.99, 'won', '2-0', '', '2025-07-26 22:23:54'),
(32, 1, 'free', 'football', '2025-08-08 20:42:00', 84, 12, 1, '1X2 - Draw', 2.89, 'won', '5-1', '', '2025-07-26 22:26:22'),
(33, 1, 'free', 'football', '2025-07-27 03:34:00', 74, 11, 12, 'Anytime Goalscorer - Player B', 1.09, 'won', '2-1', '', '2025-07-27 22:29:28'),
(34, 1, 'free', 'football', '2025-08-08 11:40:00', 115, 13, 14, '1X2 - Away Win', 2.20, 'won', '3-1', '', '2025-08-08 09:41:21'),
(35, 1, 'fixed', 'football', '2025-08-08 17:37:00', 115, 1, 13, '1X2 - Home Win', 1.09, 'won', '3-1', '', '2025-08-08 16:38:19'),
(36, 34, 'fixed', 'football', '2025-08-11 18:27:00', 76, 4, 11, 'Corner Match Bet - Over 10.5', 1.23, 'lose', '5-1', '', '2025-08-12 17:28:04');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(4) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(25) DEFAULT NULL,
  `comment` text NOT NULL,
  `type` enum('testimonial','review') NOT NULL,
  `rating` int(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `full_name`, `email`, `comment`, `type`, `rating`, `created_at`) VALUES
(1, 'Henry Okorie', 'newleastpays@gmail.com', 'Others state governments have decided to keep their local governments perpetually inactive. Lagos deserves special status. It towers far above others', 'testimonial', 2, '2025-08-11 11:00:37'),
(2, 'Mmakamba Patrick', 'newleastpays@gmail.com', 'Lagos is far ahead, I envy them sha. See what a LCDA is doing, other state their governors collect their monthly allocation and then loot it.\r\nOga Otti come and see something, no be to commission 5.8km of road with noise from obedients', 'testimonial', 4, '2025-08-11 11:00:45'),
(3, 'Mmakamba Patrick', 'jew@gmail.com', 'He said, “This book comes at a very auspicious time in Nigeria’s history as we need leaders who can', 'testimonial', 2, '2025-08-11 11:00:59'),
(4, 'Mmakamba Patrick', '', '* His government established a singular school system and ensured genuine free education in Lagos State and the benefici', 'testimonial', 5, '2025-08-11 10:58:06'),
(5, 'Lilly Usoro', 'xyz@gmail.com', 'Malam Bolaji Abdullahi, National Publicity Secretary of the African Democratic Congress (ADC), says no governor is willi', 'testimonial', 4, '2025-08-11 11:00:51'),
(6, 'Henry Okoriedf', '', 'fddfdf', 'review', 3, '2025-08-11 10:58:31'),
(7, 'Henry Okorie', '', 'bbbbbb', 'review', 2, '2025-08-11 10:58:21'),
(9, 'Udy Amarachi', '', 'Testing', 'review', 2, '2025-08-11 10:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(4) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_id`, `start_date`, `end_date`, `created_at`) VALUES
(10, 8, 1, '2025-07-23', '2025-08-13', '2025-07-23 18:39:14'),
(11, 34, 2, '2025-07-23', '2025-08-08', '2025-07-23 18:40:08'),
(29, 27, 1, '2025-07-26', '2025-08-02', '2025-07-26 07:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `league_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `league_id`) VALUES
(1, 'Arsenal', 74),
(2, 'Manchester Utd', 74),
(3, 'Chelsea Fc', 74),
(4, 'Manchester City', 74),
(5, 'Liverpool', 74),
(6, 'Everton', 74),
(9, 'Aston Villa', 74),
(10, 'Bolton', 74),
(11, 'salford', 74),
(12, 'Tottenham hotspur', 84),
(13, 'Astana', 115),
(14, 'AS Roma', 115);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_proofs`
--

CREATE TABLE `ticket_proofs` (
  `id` int(4) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_proofs`
--

INSERT INTO `ticket_proofs` (`id`, `image`, `description`, `created_at`) VALUES
(3, 'uploadImage/Screenshots/6883c94558451_img_2.jpg', '', '2025-07-25 18:13:25'),
(4, 'uploadImage/Screenshots/6886b50eb0585_WIN_20250717_13_10_11_Pro.jpg', '', '2025-07-27 23:23:58'),
(5, 'uploadImage/Screenshots/6886b52c493a0_download (3).jpg', '', '2025-07-27 23:24:28'),
(6, 'uploadImage/Screenshots/6886b54069c94_cac.png', '', '2025-07-27 23:24:48'),
(8, 'uploadImage/Screenshots/6886b7f3b8f73_nepa bill.jpeg', '', '2025-07-27 23:36:19'),
(9, 'uploadImage/Screenshots/6886b7ff2d6f7_waec0001.jpg', '', '2025-07-27 23:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `id` int(4) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`id`, `name`, `description`) VALUES
(166, '1X2 - Home Win', 'Predicting that the home team will win the match.'),
(167, '1X2 - Draw', 'Predicting that the match will end in a draw.'),
(168, '1X2 - Away Win', 'Predicting that the away team will win the match.'),
(169, 'Double Chance - 1X', 'Home team win or draw.'),
(170, 'Double Chance - X2', 'Away team win or draw.'),
(171, 'Double Chance - 12', 'Either home or away team to win.'),
(172, 'Both Teams to Score - Yes', 'Predicting both teams will score.'),
(173, 'Both Teams to Score - No', 'Predicting at least one team won’t score.'),
(174, 'Over 0.5 Goals', 'At least one goal in the match.'),
(175, 'Over 1.5 Goals', 'At least 2 goals in the match.'),
(176, 'Over 2.5 Goals', 'At least 3 goals in the match.'),
(177, 'Over 3.5 Goals', 'At least 4 goals in the match.'),
(178, 'Under 2.5 Goals', 'Less than 3 goals in the match.'),
(179, 'Correct Score - 1-0', 'Prediction of exact scoreline.'),
(180, 'Correct Score - 2-1', 'Prediction of exact scoreline.'),
(181, 'Half Time/Full Time - Home/Home', 'Home team wins both halves.'),
(182, 'Half Time/Full Time - Draw/Draw', 'Draw at both halftime and fulltime.'),
(183, 'Both Teams to Score & Over 2.5', 'BTTS and over 2.5 goals.'),
(184, 'Win to Nil - Home', 'Home team to win without conceding.'),
(185, 'Win to Nil - Away', 'Away team to win without conceding.'),
(186, 'Moneyline - Home', 'Home team to win the game.'),
(187, 'Moneyline - Away', 'Away team to win the game.'),
(188, 'Run Line - Home -1.5', 'Home team to win by 2+ runs.'),
(189, 'Run Line - Away +1.5', 'Away team to not lose by 2+.'),
(190, 'Over 6.5 Runs', 'Total combined runs over 6.5.'),
(191, 'Under 6.5 Runs', 'Total combined runs under 6.5.'),
(192, 'Total Hits Over 15.5', 'More than 15.5 hits in game.'),
(193, 'First 5 Innings - Home', 'Home team leads after 5 innings.'),
(194, 'Player to Hit Home Run', 'Selected player hits a HR.'),
(195, 'Total Errors Over 1.5', 'More than 1.5 errors in game.'),
(196, 'Team Total Over 3.5', 'Team to score over 3.5 runs.'),
(197, 'Moneyline - Home', 'Home team to win the game.'),
(198, 'Moneyline - Away', 'Away team to win the game.'),
(199, 'Point Spread - Home -5.5', 'Home team to win by 6+ points.'),
(200, 'Point Spread - Away +5.5', 'Away team to lose by 5 or win.'),
(201, 'Over 180.5 Points', 'Total points over 180.5.'),
(202, 'Under 180.5 Points', 'Total points under 180.5.'),
(203, 'Winning Margin 1-10', 'Any team wins by 1 to 10 points.'),
(204, 'Team Total Over 95.5', 'Team to score over 95.5 points.'),
(205, 'Double Double - Player X', 'Player to score double-double.'),
(206, 'Triple Double - Player Y', 'Player to record triple-double.'),
(207, '1st Quarter Winner - Home', 'Home team to win 1st quarter.'),
(208, 'Half Time Winner - Away', 'Away team to lead at halftime.'),
(209, 'Race to 20 Points - Away', 'Away team to score 20 points first.'),
(210, 'Most Rebounds - Player A', 'Player A to get most rebounds.'),
(211, 'Total Assists Over 22.5', 'Combined assists over 22.5.'),
(212, 'Correct Score - 3-1', 'Prediction of exact scoreline 3-1.'),
(213, 'Correct Score - 2-2', 'Prediction of exact scoreline 2-2.'),
(214, 'First Goalscorer - Player A', 'Player A to score first goal.'),
(215, 'Anytime Goalscorer - Player B', 'Player B to score anytime.'),
(216, 'Over 4.5 Goals', 'At least 5 goals in the match.'),
(217, 'Under 1.5 Goals', 'Less than 2 goals in the match.'),
(218, 'Win Both Halves - Home', 'Home team to win both halves.'),
(219, 'Clean Sheet - Away', 'Away team keeps clean sheet.'),
(220, 'Red Card - Yes', 'At least one red card in the match.'),
(221, 'Corner Match Bet - Over 10.5', 'Total corners over 10.5.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','vip') DEFAULT 'vip',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `image` text DEFAULT 'uploadImage/Profile/default.png',
  `country` varchar(20) DEFAULT NULL,
  `wallet_balance` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password_hash`, `role`, `status`, `image`, `country`, `wallet_balance`, `created_at`) VALUES
(1, 'Esther Sunday', 'newleastpaysolution@yahoo.com', '08067361023', '$2y$10$Iq4aN23yn37q1U.oi5E8H.C59HcmQ8.IUwfqSty4uuImOuBV323Ui', 'admin', 'active', 'uploadImage/Profile/default.png', 'Nigeria', 0, '2025-07-22 16:03:47'),
(8, 'Mmakamba Patrick', 'nduesowalter@gmail.com', '08125314304', '$2y$10$Tfd.wT8E.VrHgiKlgaIL4OgRqSKkPdNgmXuylDCTMOuQFLMIakLd.', 'vip', 'active', 'uploadImage/Profile/default.png', 'Nigeria', 0, '2025-07-23 16:57:14'),
(27, 'Henry Bassey', '123@gmail.com', '+2332660590700', '$2y$10$wA0/4gJHGmnVOeWGmya3fu6YSEfIt5Pjq1lO1kveZIjkbEDtTDTni', 'vip', 'active', 'uploadImage/Profile/user_27_1753709738.jpg', 'Nigeria', 0, '2025-07-26 07:50:37'),
(34, 'Emem Walter', 'newleastpaysolution@gmail.com', '08067361023', '$2y$10$Z3bFPXrE9AHypszYfc.rmesRHhca9dTJ4hNLyKgbKLhZC4U8Z.f3S', 'vip', 'active', 'uploadImage/Profile/user_34_1754909076.jpg', 'Nigeria', 7000, '2025-08-11 10:05:47');

-- --------------------------------------------------------

--
-- Table structure for table `vip_messages`
--

CREATE TABLE `vip_messages` (
  `id` int(11) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vip_messages`
--

INSERT INTO `vip_messages` (`id`, `subject`, `message`, `user_id`, `sent_at`) VALUES
(1, '\"Insufficient balance or invalid coverage', 'In a recent sit-down, Blessing revealed how far she’s willing to go for her man. From cooking pots clanking in Enugu to waybill deliveries arriving fresh in Lagos, Blessing proves that when she says she’s “wife material,” she means it with extra yards.\r\n\r\nOf course, the internet had mixed reactions. Some hailed her for being committed and hardworking, while others questioned the logic, and the logistics — of transporting food across states. But knowing Blessing CEO, it’s all part of the show… and the statement.', 1, '2025-07-24 20:49:10'),
(2, '\"Insufficient balance or invalid coverage', 'In a recent sit-down, Blessing revealed how far she’s willing to go for her man. From cooking pots clanking in Enugu to waybill deliveries arriving fresh in Lagos, Blessing proves that when she says she’s “wife material,” she means it with extra yards.\r\n\r\nOf course, the internet had mixed reactions. Some hailed her for being committed and hardworking, while others questioned the logic, and the logistics — of transporting food across states. But knowing Blessing CEO, it’s all part of the show… and the statement.', 8, '2025-07-24 20:49:13'),
(3, '\"Insufficient balance or invalid coverage', 'In a recent sit-down, Blessing revealed how far she’s willing to go for her man. From cooking pots clanking in Enugu to waybill deliveries arriving fresh in Lagos, Blessing proves that when she says she’s “wife material,” she means it with extra yards.\r\n\r\nOf course, the internet had mixed reactions. Some hailed her for being committed and hardworking, while others questioned the logic, and the logistics — of transporting food across states. But knowing Blessing CEO, it’s all part of the show… and the statement.\r\n\r\nSource: YabaLeftOnline @IG', 1, '2025-07-25 16:11:05'),
(4, '\"Insufficient balance or invalid coverage', 'In a recent sit-down, Blessing revealed how far she’s willing to go for her man. From cooking pots clanking in Enugu to waybill deliveries arriving fresh in Lagos, Blessing proves that when she says she’s “wife material,” she means it with extra yards.\r\n\r\nOf course, the internet had mixed reactions. Some hailed her for being committed and hardworking, while others questioned the logic, and the logistics — of transporting food across states. But knowing Blessing CEO, it’s all part of the show… and the statement.\r\n\r\nSource: YabaLeftOnline @IG', 8, '2025-07-25 16:11:26'),
(5, 'bad governnor', 'In a recent sit-down, Blessing revealed how far she’s willing to go for her man. From cooking pots clanking in Enugu to waybill deliveries arriving fresh in Lagos, Blessing proves that when she says she’s “wife material,” she means it with extra yards.\r\n\r\nOf course, the internet had mixed reactions. Some hailed her for being committed and hardworking, while others questioned the logic, and the logistics — of transporting food across states. But knowing Blessing CEO, it’s all part of the show… and the statement.\r\n\r\nSource: YabaLeftOnline @IG', 1, '2025-07-25 16:12:36'),
(6, 'Testing', 'If you like cook in Space and send it to your Man on Earth. That is not what makes Home.\r\n\r\nA genuine Fear of GOD from both man and woman,\r\nA conscious act of having GOD as the Center and Life of the Home, living according to His will is what makes a Home.', 1, '2025-07-25 16:17:35'),
(7, 'Testing', 'If you like cook in Space and send it to your Man on Earth. That is not what makes Home.\r\n\r\nA genuine Fear of GOD from both man and woman,\r\nA conscious act of having GOD as the Center and Life of the Home, living according to His will is what makes a Home.', 8, '2025-07-25 16:17:56'),
(8, '\"Insufficient balance or invalid coverage', 'asdsdsd', 8, '2025-07-25 17:32:42'),
(9, '\"Insufficient balance or invalid coverage', 'sddsd', 24, '2025-07-26 07:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `vip_results`
--

CREATE TABLE `vip_results` (
  `id` int(11) NOT NULL,
  `result_date` date NOT NULL,
  `outcome` enum('won','lose') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vip_results`
--

INSERT INTO `vip_results` (`id`, `result_date`, `outcome`, `notes`, `created_at`) VALUES
(16, '2025-07-25', 'won', '', '2025-07-26 22:28:01'),
(17, '2025-07-25', 'won', '', '2025-07-26 22:28:10'),
(18, '2025-07-24', 'lose', '', '2025-07-26 22:28:21'),
(19, '2025-07-23', 'won', '', '2025-07-26 22:28:29'),
(20, '2025-07-27', 'won', '', '2025-08-12 17:26:06'),
(21, '2025-08-11', 'lose', '', '2025-08-12 17:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE `website_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) DEFAULT NULL,
  `site_email` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `site_url` varchar(255) DEFAULT NULL,
  `whatsapp_phone` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `phone1` varchar(15) NOT NULL,
  `phone2` varchar(15) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `paystack_secret_key` text NOT NULL,
  `paystack_public_key` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`id`, `site_name`, `site_email`, `logo`, `site_url`, `whatsapp_phone`, `address`, `phone1`, `phone2`, `updated_at`, `paystack_secret_key`, `paystack_public_key`) VALUES
(1, 'Confirm Predictions', 'newleastpaysolution@gmail.com', 'uploadImage/Logo/6895212053c8e_logo_1.png', 'http://surefixedwin.com/', '+2348067361023', '123 Sports Avenue, Lagos, Nigeria', '08067361023', '08077361023', '2025-08-12 18:32:56', 'sk_test_47baa9aaab29e730ccc5d25c1f00761454fc58e4', 'pk_test_9180e4cbc4f6da45138bf24d6e5a4fce84439c58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leagues`
--
ALTER TABLE `leagues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `predictions`
--
ALTER TABLE `predictions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `league_id` (`league_id`),
  ADD KEY `team_home_id` (`team_home_id`),
  ADD KEY `team_away_id` (`team_away_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_proofs`
--
ALTER TABLE `ticket_proofs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vip_messages`
--
ALTER TABLE `vip_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vip_results`
--
ALTER TABLE `vip_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leagues`
--
ALTER TABLE `leagues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ticket_proofs`
--
ALTER TABLE `ticket_proofs`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `vip_messages`
--
ALTER TABLE `vip_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vip_results`
--
ALTER TABLE `vip_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `predictions`
--
ALTER TABLE `predictions`
  ADD CONSTRAINT `predictions_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `predictions_ibfk_2` FOREIGN KEY (`team_home_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `predictions_ibfk_3` FOREIGN KEY (`team_away_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
