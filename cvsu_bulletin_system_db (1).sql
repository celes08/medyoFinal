-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 07:09 PM
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
-- Database: `cvsu_bulletin_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` varchar(100) NOT NULL,
  `target_entity_type` varchar(50) DEFAULT NULL,
  `target_entity_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `event_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `event_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by_user_id` int(11) NOT NULL,
  `visibility` enum('All','Department') NOT NULL DEFAULT 'All',
  `target_department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_code` varchar(10) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_code`, `department_name`) VALUES
(1, 'DIT', 'Department of Information Technology'),
(2, 'DOM', 'Department of Management'),
(3, 'DAS', 'Department of Arts and Sciences'),
(4, 'TED', 'Teacher Education Department');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `related_post_id` int(11) DEFAULT NULL,
  `related_user_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `notification_type`, `message`, `related_post_id`, `related_user_id`, `is_read`, `read_at`, `created_at`) VALUES
(10, 2, 'new_post', 'A new announcement has been posted!', 10, NULL, 1, '2025-06-24 11:10:10', '2025-06-24 00:51:11'),
(11, 2, 'like', 'Someone liked your post!', 7, NULL, 1, '2025-06-24 11:10:10', '2025-06-24 06:46:22'),
(12, 2, 'like', 'Someone liked your post!', 8, NULL, 1, '2025-06-24 11:10:10', '2025-06-24 06:46:25'),
(13, 2, 'comment', 'Someone commented on your post!', 8, NULL, 1, '2025-06-24 11:10:10', '2025-06-24 07:01:20'),
(14, 2, 'new_post', 'A new announcement has been posted!', 11, NULL, 0, NULL, '2025-06-24 11:24:24'),
(17, 3, 'like', 'Someone liked your post!', 11, NULL, 1, '2025-06-24 15:45:20', '2025-06-24 15:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `organizational_charts`
--

CREATE TABLE `organizational_charts` (
  `chart_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `uploaded_by_user_id` int(11) NOT NULL,
  `version_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0 COMMENT '0=unused, 1=used',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `post_type` enum('Campus-Wide','Department','Student') NOT NULL,
  `target_department_id` int(11) DEFAULT NULL,
  `is_super_important` tinyint(1) NOT NULL DEFAULT 0,
  `is_scheduled` tinyint(1) NOT NULL DEFAULT 0,
  `scheduled_publish_at` timestamp NULL DEFAULT NULL,
  `status` enum('Draft','Scheduled','Published','Archived','Deleted') NOT NULL DEFAULT 'Published',
  `view_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `published_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_edited_at` timestamp NULL DEFAULT NULL,
  `last_edited_by_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `content`, `post_type`, `target_department_id`, `is_super_important`, `is_scheduled`, `scheduled_publish_at`, `status`, `view_count`, `created_at`, `published_at`, `updated_at`, `last_edited_at`, `last_edited_by_user_id`) VALUES
(1, 1, 'Deadlin of Talambuhay', 'monday bawal late', 'Department', 1, 0, 0, NULL, 'Published', 0, '2025-06-21 10:43:28', '2025-06-21 10:43:28', '2025-06-21 10:43:28', '2025-06-21 10:43:28', 1),
(2, 2, 'Defense', 'Wednesday', 'Department', NULL, 1, 0, NULL, 'Published', 0, '2025-06-23 09:16:48', '2025-06-23 09:16:48', '2025-06-23 09:16:48', '2025-06-23 09:16:48', 2),
(3, 2, 'Final Defense', 'System Defense', 'Department', 1, 1, 1, '2025-06-25 01:00:00', 'Published', 0, '2025-06-23 12:10:08', '2025-06-23 12:10:08', '2025-06-23 12:10:08', '2025-06-23 12:10:08', 2),
(4, 2, 'Testinggg', 'testing wawawaww', 'Department', 2, 1, 0, NULL, 'Published', 0, '2025-06-23 12:12:14', '2025-06-23 12:12:14', '2025-06-23 12:47:55', '2025-06-23 12:47:55', 2),
(5, 1, 'testing 14', 'testing uliii', 'Department', 1, 1, 0, NULL, 'Published', 0, '2025-06-23 12:14:43', '2025-06-23 12:14:43', '2025-06-23 13:03:40', '2025-06-23 13:03:40', 1),
(6, 1, 'ANDAMI', 'PAKIGAWA PLS', 'Department', 1, 1, 1, '2025-06-24 01:21:00', 'Published', 0, '2025-06-23 13:21:38', '2025-06-23 13:21:38', '2025-06-23 13:21:38', '2025-06-23 13:21:38', 1),
(7, 2, 'NOTIF TEST', 'WHAT HAFFEN VELLA', 'Department', NULL, 0, 1, '2025-06-23 13:27:00', 'Published', 0, '2025-06-23 13:26:31', '2025-06-23 13:26:31', '2025-06-23 13:26:31', '2025-06-23 13:26:31', 2),
(8, 2, 'NOTIF TEST 2', 'BAKIT WALA?', 'Department', 1, 0, 1, '2025-06-24 01:33:00', 'Published', 0, '2025-06-23 13:33:18', '2025-06-23 13:33:18', '2025-06-23 13:33:18', '2025-06-23 13:33:18', 2),
(9, 1, 'AYAW PA RIN BA?', 'HUHUHU', 'Department', 1, 1, 0, NULL, 'Published', 0, '2025-06-23 13:41:53', '2025-06-23 13:41:53', '2025-06-24 07:14:48', '2025-06-24 07:14:48', 1),
(10, 1, 'DEADLINE OF PAPERSS', 'bukas na defense', 'Department', NULL, 1, 0, NULL, 'Published', 0, '2025-06-23 18:51:11', '2025-06-23 18:51:11', '2025-06-24 07:14:31', '2025-06-24 07:14:31', 1),
(11, 3, 'YEY TAPOS NA KAMII', 'NYENYE :P', 'Department', NULL, 0, 0, NULL, 'Published', 0, '2025-06-24 05:24:24', '2025-06-24 05:24:24', '2025-06-24 11:30:07', '2025-06-24 11:30:07', 3);

-- --------------------------------------------------------

--
-- Table structure for table `post_bookmarks`
--

CREATE TABLE `post_bookmarks` (
  `bookmark_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_bookmarks`
--

INSERT INTO `post_bookmarks` (`bookmark_id`, `post_id`, `user_id`, `created_at`) VALUES
(4, 1, 1, '2025-06-21 11:19:24'),
(9, 5, 2, '2025-06-23 12:49:07'),
(10, 7, 1, '2025-06-24 06:46:32'),
(11, 10, 1, '2025-06-24 06:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`comment_id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 1, 1, 'okay', '2025-06-21 11:08:03'),
(2, 1, 2, 'wowowowow', '2025-06-23 09:22:02'),
(3, 9, 2, 'wala pa rin bes @celestinebenitez', '2025-06-23 13:47:40'),
(4, 9, 2, '@celestinebenitez woi', '2025-06-23 14:03:11'),
(5, 9, 2, 'gumagana na ba teh? \r\n\r\n@celestinebenitez08', '2025-06-23 14:09:09'),
(6, 9, 2, 'gumagana na ba teh? \r\n\r\n@celestinebenitez08', '2025-06-23 14:11:26'),
(7, 9, 2, 'gumagana na ba teh? \r\n\r\n@celestinebenitez08', '2025-06-23 14:12:48'),
(8, 9, 2, 'feel q bes ayaw pa rin? @celestinebenitez', '2025-06-23 14:13:20'),
(9, 8, 2, 'teh @celestinebenitez08', '2025-06-23 14:14:22'),
(10, 10, 1, 'what..', '2025-06-24 06:55:31'),
(11, 8, 1, 'wala', '2025-06-24 07:01:20'),
(12, 10, 1, '??', '2025-06-24 07:08:37'),
(13, 10, 1, 'EWWAN', '2025-06-24 07:15:38');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`like_id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 1, 1, '2025-06-21 11:06:30'),
(2, 1, 2, '2025-06-23 09:17:03'),
(3, 3, 2, '2025-06-23 12:11:01'),
(7, 9, 1, '2025-06-24 01:43:55'),
(10, 7, 1, '2025-06-24 06:46:22'),
(11, 8, 1, '2025-06-24 06:46:25'),
(12, 10, 1, '2025-06-24 06:59:18'),
(13, 11, 3, '2025-06-24 11:24:27'),
(14, 10, 3, '2025-06-24 12:37:40'),
(15, 11, 1, '2025-06-24 15:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `post_replies`
--

CREATE TABLE `post_replies` (
  `reply_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_reply_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_views`
--

CREATE TABLE `post_views` (
  `view_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_views`
--

INSERT INTO `post_views` (`view_id`, `post_id`, `user_id`, `viewed_at`) VALUES
(1, 1, 1, '2025-06-21 10:43:28'),
(5, 1, 2, '2025-06-23 09:15:48'),
(6, 2, 2, '2025-06-23 09:16:48'),
(7, 3, 2, '2025-06-23 12:10:08'),
(8, 4, 2, '2025-06-23 12:12:14'),
(9, 5, 1, '2025-06-23 12:14:43'),
(10, 6, 1, '2025-06-23 13:21:38'),
(11, 7, 2, '2025-06-23 13:26:31'),
(12, 8, 2, '2025-06-23 13:33:18'),
(13, 9, 1, '2025-06-23 13:41:53'),
(14, 8, 1, '2025-06-24 00:31:46'),
(15, 7, 1, '2025-06-24 00:31:47'),
(16, 4, 1, '2025-06-24 00:31:47'),
(17, 3, 1, '2025-06-24 00:31:47'),
(18, 2, 1, '2025-06-24 00:31:47'),
(19, 10, 1, '2025-06-24 00:51:11'),
(20, 11, 3, '2025-06-24 11:24:24'),
(21, 11, 1, '2025-06-24 16:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(100) NOT NULL,
  `program_code` varchar(20) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `signuptbl`
--

CREATE TABLE `signuptbl` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=inactive/unverified, 1=active/verified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signuptbl`
--

INSERT INTO `signuptbl` (`user_id`, `first_name`, `middle_name`, `last_name`, `username`, `email`, `date_of_birth`, `student_number`, `department`, `password`, `reg_date`, `profile_picture`, `status`) VALUES
(1, 'Celestine', 'Jadion', 'Benitez', 'celestinebenitez08', 'celestinebenitez04@cvsu.edu.ph', '2025-06-18', '202310376', 'DIT', '$2y$10$yI9s9juSUl8cUb/8WyDWeuXS6ABzuxmr5tYIFMgl1AvMhZx9BiF3a', '2025-06-21 00:24:30', 'uploads/profile_1.jpg', 1),
(2, 'Angel ', 'Jadion', 'Benitez', 'angelbenitez09', 'angelbenitez@cvsu.edu.ph', '0000-00-00', '202310377', 'TED', '$2y$10$khE0OT0b48Tzg9ObHkhfkOOZV73itQUuJd61/8GduqqTS2hQAj4vu', '2025-06-23 17:15:12', 'uploads/profile_2.jpeg', 1),
(3, 'Donna', 'Ferolin', 'Ocampo', 'dawn', 'donnaocampo@cvsu.edu.ph', '2005-03-15', '202310387', 'DIT', '$2y$10$udC6b8C5Ommb6OzbSFpRpeWQ4VwUE7zvztutHz5P8Qf4/ZEw9g.5u', '2025-06-24 19:21:17', 'uploads/profile_3.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `status` enum('Open','In Progress','Resolved','Closed') NOT NULL DEFAULT 'Open',
  `priority` enum('Low','Medium','High','Urgent') DEFAULT 'Medium',
  `assigned_to_admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`ticket_id`, `user_id`, `subject`, `description`, `category`, `status`, `priority`, `assigned_to_admin_id`, `created_at`, `updated_at`, `resolved_at`) VALUES
(1, 1, 'not working', 'hayyy', 'technical', 'Open', 'Urgent', NULL, '2025-06-20 20:35:39', '2025-06-21 02:35:39', NULL),
(2, 3, 'not working uli', '???', 'technical', 'Open', 'High', NULL, '2025-06-24 06:06:19', '2025-06-24 12:06:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `student_number` varchar(20) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_picture_url` varchar(255) DEFAULT 'default_avatar.png',
  `role_id` int(11) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `account_status` enum('Pending','Active','Inactive','Deactivated','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approved_by_admin_id` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `theme_preference` enum('Light','Dark','System') NOT NULL DEFAULT 'System',
  `email_on_campus_announcements` tinyint(1) NOT NULL DEFAULT 1,
  `email_on_department_announcements` tinyint(1) NOT NULL DEFAULT 1,
  `email_on_student_announcements` tinyint(1) NOT NULL DEFAULT 1,
  `email_on_mentions` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `organizational_charts`
--
ALTER TABLE `organizational_charts`
  ADD PRIMARY KEY (`chart_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `post_bookmarks`
--
ALTER TABLE `post_bookmarks`
  ADD PRIMARY KEY (`bookmark_id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`user_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`user_id`);

--
-- Indexes for table `post_replies`
--
ALTER TABLE `post_replies`
  ADD PRIMARY KEY (`reply_id`);

--
-- Indexes for table `post_views`
--
ALTER TABLE `post_views`
  ADD PRIMARY KEY (`view_id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`user_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`),
  ADD UNIQUE KEY `program_code` (`program_code`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `signuptbl`
--
ALTER TABLE `signuptbl`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `organizational_charts`
--
ALTER TABLE `organizational_charts`
  MODIFY `chart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `post_bookmarks`
--
ALTER TABLE `post_bookmarks`
  MODIFY `bookmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `post_replies`
--
ALTER TABLE `post_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_views`
--
ALTER TABLE `post_views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signuptbl`
--
ALTER TABLE `signuptbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
