-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 03:31 PM
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
-- Database: `UniFest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `type` enum('Workshop','Seminar','Competition','Festival','Sport','Course') NOT NULL,
  `venue` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `mode` enum('Physical','Online','Hybrid') NOT NULL,
  `registration_close_date` date NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','archived') DEFAULT 'pending',
  `visibility` enum('public','university') NOT NULL DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `description`, `organizer_id`, `contact_person`, `contact_no`, `type`, `venue`, `event_date`, `event_time`, `mode`, `registration_close_date`, `max_participants`, `fee`, `remarks`, `poster`, `status`, `visibility`) VALUES
(1, 'Apex AR Exhibition: Experiencing Formula 1 Through Augmented Reality', 'step into the cockpit and go behind the scene of the world\'s fastest sport. this immersive workshop blends cutting-edge Augmented Reality (AR) with the high octane world of Formula 1. ', 1, 'EVA EVANDA', '0123456789', 'Workshop', 'STEM Lab, Library, UMS', '2026-01-25', '08:00:00', 'Physical', '2026-01-20', 100, 0.00, '', '1768225150_The future is here! 🤯 Augmented reality is transforming our daily lives in incredible ways_ What are you most excited about_ #AR #futuretech #innovation #technology.jpg', 'approved', 'public'),
(2, 'UniFest Creative Workshop ', 'a hands on creative workshop to enhance student\'s skills in design, teamwork, and communication through interactive activities and guided sessions. ', 3, 'NURSHAINANA', '0123456789', 'Workshop', 'STEM Lab, Library, UMS', '2026-02-02', '08:00:00', 'Physical', '2026-01-30', 100, 0.00, '', '', 'approved', 'university');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `event_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 3, 5, 'IT WAS REALLY FUN ', '2026-01-12 11:50:55');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 'Registration Confirmed: You have successfully joined \'UniFest Creative Workshop \'.', 1, '2026-01-12 12:49:52'),
(2, 3, 'New Participant: Sample Public User joined your event \'UniFest Creative Workshop \'.', 1, '2026-01-12 12:49:52'),
(3, 2, 'Update for \'UniFest Creative Workshop \': dont forget to come early atleast 15 minutes before the event. ', 1, '2026-01-12 13:02:39');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registration_date`, `status`) VALUES
(1, 1, 1, '2026-01-12 11:26:11', ''),
(2, 2, 1, '2026-01-12 11:44:05', 'confirmed'),
(3, 3, 1, '2026-01-12 11:48:41', 'confirmed'),
(4, 2, 2, '2026-01-12 12:49:52', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `category` enum('Staff','Student','Public','Admin') NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `organization` varchar(100) NOT NULL,
  `interested_categories` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','student','public') NOT NULL DEFAULT 'public',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `category`, `name`, `email`, `phone`, `organization`, `interested_categories`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Student', 'ADMIN00', 'admin@gmail.com', '01123456789', 'UMS', 'Festival', '$2y$10$cY8xmpbieYt6h4MzXPR4nehgVhFgkkBLKVDWyvM2hOCQIRJls5EnW', 'admin', '2026-01-12 11:16:27', 'active'),
(2, 'Public', 'Sample Public User', 'public@example.com', '0120000002', 'UMS', 'Competition', '$2y$10$cY8xmpbieYt6h4MzXPR4nehgVhFgkkBLKVDWyvM2hOCQIRJls5EnW', 'public', '2026-01-12 11:41:56', 'active'),
(3, 'Student', 'Sample Student', 'student@example.com', '0120000003', 'UMS', 'Workshop,Seminar,Competition,Festival,Sport,Course', '$2y$10$cY8xmpbieYt6h4MzXPR4nehgVhFgkkBLKVDWyvM2hOCQIRJls5EnW', 'student', '2026-01-12 11:48:14', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
