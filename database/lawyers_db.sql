-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 04:26 PM
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
-- Database: `lawyers_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `lawyer_profile_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `slot_id` int(11) NOT NULL,
  `appointment_place` varchar(255) DEFAULT NULL,
  `appointment_status` enum('pending','approved','completed','rejected') DEFAULT 'pending',
  `appointment_notes` text DEFAULT NULL,
  `appointment_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `customer_id`, `lawyer_profile_id`, `service_id`, `slot_id`, `appointment_place`, `appointment_status`, `appointment_notes`, `appointment_created`) VALUES
(3, 12, 6, 21, 69, 'Karachi Court', 'completed', 'Please bring cash and make sure to be on time.', '2026-05-09 15:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_subject` varchar(255) NOT NULL,
  `contact_message` text NOT NULL,
  `contact_status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `contact_name`, `contact_email`, `contact_phone`, `contact_subject`, `contact_message`, `contact_status`, `created_at`) VALUES
(1, 'Subhan', 'subhan@gmail.com', NULL, 'Lawyer', 'can you add good texts.', 'read', '2026-05-17 11:53:13');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_content`
--

CREATE TABLE `homepage_content` (
  `content_id` int(11) NOT NULL,
  `section` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_content`
--

INSERT INTO `homepage_content` (`content_id`, `section`, `title`, `subtitle`, `body`, `image_path`, `updated_at`) VALUES
(1, 'hero', 'Find the Right Lawyers', 'We have help thousands of people to get relief from national wide fights wrongfull denials. Now they trusted legalcare attorneys.', '', NULL, '2026-05-17 22:03:41'),
(2, 'about', 'About Our Platform', 'Connecting clients with trusted lawyers', '', NULL, '2026-05-17 21:38:20'),
(3, 'contact', 'Contact Us', 'We are here to help', '', NULL, '2026-05-17 21:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_profiles`
--

CREATE TABLE `lawyer_profiles` (
  `lawyer_profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lawyer_bar_number` varchar(50) DEFAULT NULL,
  `lawyer_city` varchar(100) DEFAULT NULL,
  `lawyer_address` text DEFAULT NULL,
  `lawyer_experience_years` int(11) DEFAULT 0,
  `lawyer_bio` text DEFAULT NULL,
  `lawyer_profile_photo` varchar(255) DEFAULT NULL,
  `lawyer_consultation_fee` decimal(10,2) DEFAULT 0.00,
  `lawyer_setup_completed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer_profiles`
--

INSERT INTO `lawyer_profiles` (`lawyer_profile_id`, `user_id`, `lawyer_bar_number`, `lawyer_city`, `lawyer_address`, `lawyer_experience_years`, `lawyer_bio`, `lawyer_profile_photo`, `lawyer_consultation_fee`, `lawyer_setup_completed`) VALUES
(5, 4, 'BAR001', 'Karachi', 'DHA Phase 6, Karachi', 10, 'Criminal law expert with strong litigation skills.', '1778782512_9601.jpeg', 10000.00, 1),
(6, 6, 'BAR002', 'Faisalabad', 'D Ground, Faisalabad', 9, 'Criminal defense lawyer.', '1778782553_4890.jpeg', 8000.00, 1),
(7, 21, 'BAR003', 'Karachi', 'Clifton Block 5, Karachi', 8, 'Experienced lawyer for civil & criminal cases.', '1778782583_9167.jpeg', 7000.00, 1),
(8, 22, 'BAR004', 'Lahore', 'Gulberg II, Lahore', 7, 'Family and divorce case specialist.', '1778782670_5492.jpeg', 6000.00, 1),
(9, 23, 'BAR005', 'Islamabad', 'F-11 Sector, Islamabad', 12, 'Civil and property law expert.', '1778782739_1657.jpeg', 12000.00, 1),
(10, 24, 'BAR006', 'Karachi', 'North Nazimabad, Karachi', 5, 'Corporate law and business legal advisor.', '1778783898_7627.jpeg', 5000.00, 1),
(11, 25, 'BAR007', 'Multan', 'Cantt Area, Multan', 6, 'Family law and custody cases expert.', '1778775696_4373.jpeg', 6500.00, 1),
(12, 26, 'BAR008', 'Peshawar', 'University Road, Peshawar', 11, 'Constitutional law specialist.', '1778784055_7860.jpeg', 9000.00, 1),
(13, 27, 'BAR009', 'Karachi', 'Malir Cantt, Karachi', 8, 'Property and real estate cases expert.', '1778784346_6980.jpeg', 7500.00, 1),
(41, 57, 'BAR010', 'Karachi', 'Bahadurabad, Karachi', 9, 'Cyber crime specialist.', '1778623898_9049.jpeg', 8500.00, 1),
(42, 58, 'BAR011', 'Lahore', 'Model Town, Lahore', 7, 'Expert in Family Law.', '1778785552_2538.jpeg', 7000.00, 1),
(43, 59, 'BAR012', 'Islamabad', 'Blue Area, Islamabad', 11, 'Corporate and taxation law.', '1778785067_6480.jpeg', 12500.00, 1),
(44, 60, 'BAR013', 'Karachi', 'PECHS Block 2, Karachi', 6, 'Property expert.', '1778624376_7226.jpeg', 6500.00, 1),
(45, 61, 'BAR014', 'Peshawar', 'Hayatabad, Peshawar', 8, 'Constitutional law.', '1778624420_2749.jpeg', 9000.00, 1),
(46, 62, 'BAR015', 'Multan', 'Gulgasht Colony, Multan', 5, 'Family lawyer.', '1778624470_8559.jpeg', 6000.00, 1),
(47, 63, 'BAR016', 'Faisalabad', 'Peoples Colony, Faisalabad', 10, 'Business contract advisor.', '1778784556_6758.jpeg', 9500.00, 1),
(48, 64, 'BAR017', 'Rawalpindi', 'Satellite Town, Rawalpindi', 7, 'Property land dispute.', '1778624743_9050.jpg', 7500.00, 1),
(49, 65, 'BAR018', 'Hyderabad', 'Latifabad, Hyderabad', 12, 'Senior advocate.', '1778624800_8273.jpeg', 13000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_requests`
--

CREATE TABLE `lawyer_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_bar_number` varchar(50) DEFAULT NULL,
  `request_consultation_fee` decimal(10,2) DEFAULT 0.00,
  `request_city` varchar(100) DEFAULT NULL,
  `request_address` text DEFAULT NULL,
  `request_experience_years` int(11) DEFAULT NULL,
  `request_bio` text DEFAULT NULL,
  `request_profile_photo` varchar(255) NOT NULL,
  `request_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer_requests`
--

INSERT INTO `lawyer_requests` (`request_id`, `user_id`, `request_bar_number`, `request_consultation_fee`, `request_city`, `request_address`, `request_experience_years`, `request_bio`, `request_profile_photo`, `request_status`, `created_at`) VALUES
(3, 4, 'BAR001', 3000.00, 'Karachi', 'DHA Phase 6, Karachi', 10, 'Criminal law expert with strong litigation skills.', '1777128374_8617.jpg', 'approved', '2026-04-25 14:46:14'),
(6, 6, 'BAR005', 10000.00, 'Faisalabad', 'D Ground, Faisalabad', 9, 'Criminal defense lawyer.', '1777132991_7911.jpg', 'approved', '2026-04-25 16:03:11');

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_services`
--

CREATE TABLE `lawyer_services` (
  `service_id` int(11) NOT NULL,
  `lawyer_profile_id` int(11) NOT NULL,
  `practice_area_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer_services`
--

INSERT INTO `lawyer_services` (`service_id`, `lawyer_profile_id`, `practice_area_id`) VALUES
(19, 5, 18),
(20, 5, 27),
(21, 6, 18),
(22, 6, 27),
(23, 7, 18),
(24, 7, 21),
(25, 8, 19),
(26, 9, 21),
(27, 9, 22),
(28, 10, 20),
(29, 11, 19),
(30, 12, 21),
(31, 13, 22),
(47, 41, 18),
(48, 41, 27),
(49, 42, 19),
(50, 43, 20),
(51, 43, 25),
(52, 44, 21),
(53, 44, 22),
(54, 45, 18),
(55, 45, 21),
(56, 46, 19),
(57, 47, 20),
(58, 47, 23),
(59, 48, 22),
(60, 49, 21);

-- --------------------------------------------------------

--
-- Table structure for table `practice_areas`
--

CREATE TABLE `practice_areas` (
  `practice_area_id` int(11) NOT NULL,
  `practice_area_name` varchar(100) NOT NULL,
  `practice_area_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `practice_areas`
--

INSERT INTO `practice_areas` (`practice_area_id`, `practice_area_name`, `practice_area_description`) VALUES
(18, 'Criminal Law', 'Vigorous defense strategies for individuals facing state or federal charges to protect your future.'),
(19, 'Family & Divorce', 'Compassionate representation for lifes most personal transitions, including divorce, custody, and support.'),
(20, 'Business Law', 'Strategic legal solutions for entrepreneurs and corporations, from formation to complex negotiations.'),
(21, 'Civil Litigation', 'Resolving non-criminal disputes between individuals or organizations through professional legal action.'),
(22, 'Property Law', 'Securing real estate investments through expert handling of transactions, zoning, and title disputes.'),
(23, 'Employment Law', 'Navigating workplace dynamics, specializing in wrongful termination, discrimination, and wage claims.'),
(24, 'Insurance Law', 'Protecting policyholders’ rights in claim disputes, denials, and bad-faith insurance practices.'),
(25, 'Financial Law', 'Guidance through the complexities of banking regulations, securities litigation, and investment fraud.'),
(26, 'Fire Accident', 'Expert advocacy for victims of fire-related negligence, focusing on liability and full damage recovery.'),
(27, 'Drug Offenses', 'Focused criminal defense for possession and distribution charges, prioritizing rehabilitation and due process.');

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `slot_id` int(11) NOT NULL,
  `lawyer_profile_id` int(11) NOT NULL,
  `slot_date` date NOT NULL,
  `slot_time` time NOT NULL,
  `slot_status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`slot_id`, `lawyer_profile_id`, `slot_date`, `slot_time`, `slot_status`) VALUES
(11, 5, '2026-05-04', '11:00:00', 'available'),
(12, 5, '2026-05-04', '14:00:00', 'available'),
(13, 5, '2026-05-05', '12:00:00', 'available'),
(15, 6, '2026-05-05', '13:00:00', 'available'),
(16, 6, '2026-05-06', '15:00:00', 'available'),
(17, 7, '2026-05-04', '11:30:00', 'available'),
(18, 7, '2026-05-05', '16:00:00', 'available'),
(19, 7, '2026-05-06', '12:30:00', 'available'),
(20, 8, '2026-05-04', '09:30:00', 'available'),
(21, 8, '2026-05-05', '11:00:00', 'available'),
(22, 8, '2026-05-06', '14:00:00', 'available'),
(23, 9, '2026-05-04', '10:30:00', 'available'),
(24, 9, '2026-05-05', '13:30:00', 'available'),
(25, 9, '2026-05-06', '15:30:00', 'available'),
(26, 10, '2026-05-04', '12:00:00', 'available'),
(27, 10, '2026-05-05', '14:30:00', 'available'),
(28, 10, '2026-05-06', '16:30:00', 'available'),
(29, 11, '2026-05-04', '10:15:00', 'available'),
(30, 11, '2026-05-05', '12:45:00', 'available'),
(31, 11, '2026-05-06', '15:15:00', 'available'),
(32, 12, '2026-05-04', '11:45:00', 'available'),
(33, 12, '2026-05-05', '13:15:00', 'available'),
(34, 12, '2026-05-06', '17:00:00', 'available'),
(35, 13, '2026-05-04', '09:45:00', 'available'),
(36, 13, '2026-05-05', '12:15:00', 'available'),
(37, 13, '2026-05-06', '14:45:00', 'available'),
(38, 5, '2026-05-07', '09:00:00', 'available'),
(39, 5, '2026-05-07', '11:00:00', 'available'),
(40, 5, '2026-05-08', '14:00:00', 'available'),
(41, 6, '2026-05-07', '10:00:00', 'available'),
(42, 6, '2026-05-08', '12:00:00', 'available'),
(43, 6, '2026-05-09', '15:00:00', 'booked'),
(44, 7, '2026-05-07', '11:30:00', 'available'),
(45, 7, '2026-05-08', '13:00:00', 'available'),
(46, 7, '2026-05-09', '16:30:00', 'available'),
(47, 8, '2026-05-07', '09:30:00', 'available'),
(48, 8, '2026-05-08', '14:00:00', 'available'),
(49, 8, '2026-05-09', '11:00:00', 'available'),
(50, 9, '2026-05-07', '10:30:00', 'available'),
(51, 9, '2026-05-08', '15:30:00', 'available'),
(52, 9, '2026-05-09', '13:30:00', 'available'),
(53, 10, '2026-05-07', '12:00:00', 'available'),
(54, 10, '2026-05-08', '14:30:00', 'available'),
(55, 10, '2026-05-09', '16:00:00', 'available'),
(56, 11, '2026-05-07', '09:15:00', 'available'),
(57, 11, '2026-05-08', '12:45:00', 'available'),
(58, 11, '2026-05-09', '15:15:00', 'available'),
(59, 12, '2026-05-07', '11:45:00', 'available'),
(60, 12, '2026-05-08', '13:15:00', 'available'),
(61, 12, '2026-05-09', '17:00:00', 'available'),
(62, 13, '2026-05-07', '10:00:00', 'available'),
(63, 13, '2026-05-08', '12:00:00', 'available'),
(64, 13, '2026-05-09', '14:00:00', 'available'),
(66, 5, '2026-05-11', '09:00:00', 'available'),
(67, 5, '2026-05-11', '11:00:00', 'available'),
(68, 5, '2026-05-12', '15:00:00', 'available'),
(69, 6, '2026-05-11', '10:00:00', 'available'),
(70, 6, '2026-05-12', '12:00:00', 'available'),
(72, 7, '2026-05-11', '11:30:00', 'available'),
(73, 7, '2026-05-12', '13:00:00', 'available'),
(74, 7, '2026-05-13', '16:00:00', 'available'),
(75, 8, '2026-05-11', '09:30:00', 'available'),
(76, 8, '2026-05-12', '14:30:00', 'available'),
(77, 8, '2026-05-13', '11:00:00', 'available'),
(78, 9, '2026-05-11', '10:30:00', 'available'),
(79, 9, '2026-05-12', '12:30:00', 'available'),
(80, 9, '2026-05-13', '15:30:00', 'available'),
(81, 10, '2026-05-11', '12:00:00', 'available'),
(82, 10, '2026-05-12', '14:00:00', 'available'),
(83, 10, '2026-05-13', '10:00:00', 'available'),
(84, 11, '2026-05-11', '09:15:00', 'available'),
(85, 11, '2026-05-12', '11:45:00', 'available'),
(86, 11, '2026-05-13', '14:15:00', 'available'),
(87, 12, '2026-05-11', '10:45:00', 'available'),
(88, 12, '2026-05-12', '13:15:00', 'available'),
(89, 12, '2026-05-13', '15:45:00', 'available'),
(90, 13, '2026-05-11', '11:00:00', 'available'),
(91, 13, '2026-05-12', '14:00:00', 'available'),
(92, 13, '2026-05-13', '09:00:00', 'available'),
(120, 41, '2026-05-10', '10:00:00', 'available'),
(121, 42, '2026-05-10', '11:00:00', 'available'),
(122, 43, '2026-05-10', '12:00:00', 'available'),
(123, 44, '2026-05-10', '09:30:00', 'available'),
(124, 45, '2026-05-10', '10:30:00', 'available'),
(125, 46, '2026-05-10', '11:30:00', 'available'),
(126, 47, '2026-05-10', '12:30:00', 'available'),
(127, 48, '2026-05-10', '09:45:00', 'available'),
(128, 49, '2026-05-10', '10:15:00', 'available'),
(147, 41, '2026-05-12', '10:00:00', 'available'),
(148, 41, '2026-05-13', '14:00:00', 'available'),
(149, 42, '2026-05-12', '11:00:00', 'available'),
(150, 42, '2026-05-13', '15:00:00', 'available'),
(151, 43, '2026-05-12', '12:00:00', 'available'),
(152, 43, '2026-05-13', '16:00:00', 'available'),
(153, 44, '2026-05-12', '09:30:00', 'available'),
(154, 44, '2026-05-13', '13:30:00', 'available'),
(155, 45, '2026-05-12', '10:30:00', 'available'),
(156, 45, '2026-05-13', '17:00:00', 'booked'),
(157, 46, '2026-05-12', '11:30:00', 'available'),
(158, 46, '2026-05-13', '14:30:00', 'available'),
(159, 47, '2026-05-12', '12:30:00', 'available'),
(160, 47, '2026-05-13', '15:30:00', 'available'),
(161, 48, '2026-05-12', '09:45:00', 'available'),
(162, 48, '2026-05-13', '13:45:00', 'available'),
(163, 49, '2026-05-12', '10:15:00', 'available'),
(164, 49, '2026-05-13', '16:15:00', 'available'),
(167, 6, '2026-05-16', '16:50:00', 'booked'),
(168, 5, '2026-05-20', '09:00:00', 'available'),
(169, 5, '2026-05-20', '11:30:00', 'available'),
(170, 5, '2026-05-21', '14:00:00', 'available'),
(171, 6, '2026-05-20', '10:00:00', 'available'),
(172, 6, '2026-05-21', '12:00:00', 'available'),
(173, 6, '2026-05-21', '15:30:00', 'available'),
(174, 7, '2026-05-20', '11:00:00', 'available'),
(175, 7, '2026-05-22', '13:00:00', 'available'),
(176, 7, '2026-05-22', '16:00:00', 'available'),
(177, 9, '2026-05-20', '09:30:00', 'available'),
(178, 9, '2026-05-20', '14:30:00', 'available'),
(179, 9, '2026-05-21', '11:00:00', 'available'),
(180, 11, '2026-05-21', '10:15:00', 'available'),
(181, 11, '2026-05-21', '12:45:00', 'available'),
(182, 11, '2026-05-22', '15:00:00', 'available'),
(183, 13, '2026-05-20', '11:15:00', 'available'),
(184, 13, '2026-05-21', '13:45:00', 'available'),
(185, 13, '2026-05-23', '16:30:00', 'available'),
(186, 42, '2026-05-22', '09:00:00', 'available'),
(187, 42, '2026-05-22', '11:30:00', 'available'),
(188, 42, '2026-05-23', '14:00:00', 'available'),
(189, 45, '2026-05-21', '10:00:00', 'available'),
(190, 45, '2026-05-23', '12:30:00', 'available'),
(191, 45, '2026-05-23', '15:00:00', 'available'),
(192, 47, '2026-05-22', '11:00:00', 'available'),
(193, 47, '2026-05-22', '14:00:00', 'available'),
(194, 47, '2026-05-25', '16:15:00', 'available'),
(195, 8, '2026-05-20', '12:30:00', 'available'),
(196, 8, '2026-05-21', '15:00:00', 'available'),
(197, 10, '2026-05-21', '09:45:00', 'available'),
(198, 10, '2026-05-22', '13:15:00', 'available'),
(199, 12, '2026-05-21', '10:30:00', 'available'),
(200, 12, '2026-05-23', '14:45:00', 'available'),
(201, 41, '2026-05-22', '11:00:00', 'available'),
(202, 41, '2026-05-24', '15:30:00', 'available'),
(203, 44, '2026-05-22', '10:15:00', 'available'),
(204, 44, '2026-05-23', '12:45:00', 'available'),
(205, 46, '2026-05-23', '09:15:00', 'available'),
(206, 46, '2026-05-24', '13:30:00', 'available'),
(207, 49, '2026-05-22', '11:45:00', 'available'),
(208, 49, '2026-05-25', '14:15:00', 'available'),
(209, 43, '2026-05-23', '10:00:00', 'available'),
(210, 48, '2026-05-24', '12:00:00', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(15) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_role` enum('customer','lawyer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_phone`, `user_password`, `user_role`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXutHOS', 'admin', '2026-04-23 15:38:58'),
(2, 'Ahad', 'ahad@gmail.com', '1234567890', '$2y$10$FbuqewKH2VTKzCjOixGFtOpQUrfOaGwZtEX.xmm51a5HS1TG.0C8a', 'admin', '2026-04-23 15:40:46'),
(4, 'Asher Nadeem', 'asher@gmail.com', '54565667', '$2y$10$IfjtpbtMIKUTbqpZYwOrv./RzQ6MBCaxFvHrvgTm77iY57tK3O0YG', 'lawyer', '2026-04-24 18:33:18'),
(6, 'Ahmed Raza', 'ahmed@gmail.com', '43546564', '$2y$10$2n2fV/f8wvCbRD95nGQecuo.FFk2gQK8nlx1Gy89L8xrRDWJ3C7JC', 'lawyer', '2026-04-25 16:01:31'),
(12, 'Subhan', 'subhan@gmail.com', '45435433', '$2y$10$eayrMyS2eginAImt1W5bm.Dk6ZLhO.Ea14EmXtOK/u50GsR3F48mC', 'customer', '2026-04-26 10:00:40'),
(17, 'Mannan', 'mannan@gmail.com', '', '$2y$10$FnAoMqRvGeszm36u.AOM3uX/K8W0ulN1O9vR0zLUXeYF4I3ZMakH.', 'customer', '2026-04-26 10:16:53'),
(21, 'Ali Khan', 'ali@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-04-29 19:22:43'),
(22, 'Sara Ahmed', 'sara@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-04-29 19:22:43'),
(23, 'Usman Tariq', 'usman@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-04-29 19:22:43'),
(24, 'Hina Malik', 'hina@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-04-29 19:22:43'),
(25, 'Fatima Noor', 'fatima@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-04-29 19:22:43'),
(26, 'Bilal Sheikh', 'bilal@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-04-29 19:22:43'),
(27, 'Zain Ali', 'zain@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-04-29 19:22:43'),
(57, 'Hamza Qureshi', 'hamza@gmail.com', '2147483647', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(58, 'Ayesha Siddiqui', 'ayesha@gmail.com', '92344345', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(59, 'Danish Malik', 'danish@gmail.com', '3234322', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(60, 'Noor Fatima', 'noor@gmail.com', '2147483647', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(61, 'Taha Khan', 'taha@gmail.com', '2147483647', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(62, 'Maryam Ali', 'maryam@gmail.com', '2147483647', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(63, 'Saad Hussain', 'saad@gmail.com', '0', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(64, 'Iqra Naveed', 'iqra@gmail.com', '2147483647', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(65, 'Shahzaib Ahmed', 'shahzaib@gmail.com', '2147483647', '$2y$10$ngmKpv348h89tF5SSHReie1zbY0GbumHIo4.9DxFHAKYSk6C3tSmG', 'lawyer', '2026-05-10 17:31:28'),
(69, 'Hussain', 'hussain@gmail.com', '233', '$2y$10$Mc07.gEeqYbRp/myMX9ReuOmVu6a5I.y8rvXfrq/NDg7/TQRnYz7G', 'customer', '2026-05-13 13:10:11'),
(71, 'Junaid', 'junaid@gmail.com', '123123121', '$2y$10$1ihNvS5oT7JX3I8gvDDpTumC2PC/0/ywnV70w8L2rUVrGeo8VqBwm', 'customer', '2026-05-13 15:32:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `lawyer_profile_id` (`lawyer_profile_id`),
  ADD KEY `slot_id` (`slot_id`),
  ADD KEY `fk_service` (`service_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `homepage_content`
--
ALTER TABLE `homepage_content`
  ADD PRIMARY KEY (`content_id`),
  ADD UNIQUE KEY `section` (`section`);

--
-- Indexes for table `lawyer_profiles`
--
ALTER TABLE `lawyer_profiles`
  ADD PRIMARY KEY (`lawyer_profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lawyer_requests`
--
ALTER TABLE `lawyer_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lawyer_services`
--
ALTER TABLE `lawyer_services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `lawyer_profile_id` (`lawyer_profile_id`),
  ADD KEY `practice_area_id` (`practice_area_id`);

--
-- Indexes for table `practice_areas`
--
ALTER TABLE `practice_areas`
  ADD PRIMARY KEY (`practice_area_id`),
  ADD UNIQUE KEY `practice_area_name` (`practice_area_name`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`slot_id`),
  ADD UNIQUE KEY `unique_slot` (`lawyer_profile_id`,`slot_date`,`slot_time`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `homepage_content`
--
ALTER TABLE `homepage_content`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lawyer_profiles`
--
ALTER TABLE `lawyer_profiles`
  MODIFY `lawyer_profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `lawyer_requests`
--
ALTER TABLE `lawyer_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `lawyer_services`
--
ALTER TABLE `lawyer_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `practice_areas`
--
ALTER TABLE `practice_areas`
  MODIFY `practice_area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`lawyer_profile_id`) REFERENCES `lawyer_profiles` (`lawyer_profile_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`slot_id`) REFERENCES `time_slots` (`slot_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`service_id`) REFERENCES `lawyer_services` (`service_id`) ON DELETE CASCADE;

--
-- Constraints for table `lawyer_profiles`
--
ALTER TABLE `lawyer_profiles`
  ADD CONSTRAINT `lawyer_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lawyer_requests`
--
ALTER TABLE `lawyer_requests`
  ADD CONSTRAINT `lawyer_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lawyer_services`
--
ALTER TABLE `lawyer_services`
  ADD CONSTRAINT `lawyer_services_ibfk_1` FOREIGN KEY (`lawyer_profile_id`) REFERENCES `lawyer_profiles` (`lawyer_profile_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lawyer_services_ibfk_2` FOREIGN KEY (`practice_area_id`) REFERENCES `practice_areas` (`practice_area_id`) ON DELETE CASCADE;

--
-- Constraints for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD CONSTRAINT `time_slots_ibfk_1` FOREIGN KEY (`lawyer_profile_id`) REFERENCES `lawyer_profiles` (`lawyer_profile_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
