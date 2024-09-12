-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 22, 2024 at 11:54 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `firstname`, `lastname`) VALUES
(1, 'Brian', 'Lupasa');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `status` enum('present','absent','sick') NOT NULL,
  `pupil_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pupil_id` (`pupil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `date`, `status`, `pupil_id`) VALUES
(1, '2024-07-22', 'present', '2410010001'),
(2, '2024-07-22', 'absent', '2410010002'),
(3, '2024-07-22', 'sick', '2410010003'),
(4, '2024-07-22', 'present', '2410010004'),
(5, '2024-07-22', 'present', '2410010001'),
(6, '2024-07-22', 'absent', '2410010002'),
(7, '2024-07-22', 'sick', '2410010003'),
(8, '2024-07-22', 'absent', '2410010004'),
(9, '2024-07-22', 'present', '2410010001'),
(10, '2024-07-22', 'absent', '2410010002'),
(11, '2024-07-22', 'sick', '2410010003'),
(12, '2024-07-22', 'present', '2410010004'),
(13, '2024-07-22', 'present', '2410010001'),
(14, '2024-07-22', 'present', '2410010002'),
(15, '2024-07-22', 'absent', '2410010003'),
(16, '2024-07-22', 'present', '2410010004');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`) VALUES
(1, 'Grade 8'),
(2, 'Grade 9'),
(3, 'Grade 10'),
(4, 'Grade 11'),
(5, 'Grade 12');

-- --------------------------------------------------------

--
-- Table structure for table `pupils`
--

DROP TABLE IF EXISTS `pupils`;
CREATE TABLE IF NOT EXISTS `pupils` (
  `id` varchar(10) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `added_by` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `added_by` (`added_by`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pupils`
--

INSERT INTO `pupils` (`id`, `firstname`, `lastname`, `sex`, `birthdate`, `added_by`, `class_id`) VALUES
('2408010001', 'mercy ', 'mulenga', 'Female', '2004-02-10', 1, 1),
('2408010002', 'alice', 'banda', 'Female', '2006-03-02', 1, 1),
('2409010001', 'mergan', 'namumba', 'Female', '0002-02-12', 1, 2),
('2409010002', 'flaviour', 'chipamba', 'Female', '2002-09-25', 1, 2),
('2409010003', 'emmanuel', 'sakala', 'Male', '2007-05-10', 1, 2),
('2410010001', 'kaziweni', 'innocent', 'Male', '2002-09-12', 1, 3),
('2410010002', 'muti', 'nalikena', 'Male', '2003-06-06', 1, 3),
('2410010003', 'Violet', 'Tembo', 'Female', '2004-02-02', 1, 3),
('2410010004', 'Tamara', 'Masamba', 'Female', '2005-03-03', 1, 3),
('2411010001', 'catherine', 'lupasa', 'Female', '2006-08-12', 1, 4),
('2411010002', 'phiri', 'shardrick', 'Male', '2006-03-11', 1, 4),
('2411010003', 'anita', 'zulu', 'Female', '2002-02-14', 1, 4),
('2412010001', 'annet', 'namuchimba', 'Female', '2002-01-12', 1, 5),
('2412010002', 'joyce', 'chibuye', 'Female', '2004-05-09', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pupil_id` varchar(10) NOT NULL,
  `subject_id` int NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `term` varchar(20) NOT NULL,
  `year` int NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pupil_id` (`pupil_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `pupil_id`, `subject_id`, `score`, `term`, `year`, `comment`) VALUES
(1, '2410010001', 3, 75.00, '1', 2024, ''),
(2, '2410010001', 4, 75.00, '1', 2024, ''),
(3, '2410010002', 4, 78.00, '1', 2024, ''),
(4, '2410010003', 4, 87.00, '1', 2024, 'Excellent results'),
(5, '2410010004', 4, 90.00, '1', 2024, 'Excellent results'),
(6, '2410010001', 5, 75.00, '1', 2024, ''),
(7, '2410010002', 5, 78.00, '1', 2024, ''),
(8, '2410010003', 5, 87.00, '1', 2024, 'Excellent results'),
(9, '2410010004', 5, 90.00, '1', 2024, 'Excellent results'),
(10, '2410010001', 6, 67.00, '1', 2024, ''),
(11, '2410010002', 6, 68.00, '1', 2024, ''),
(12, '2410010003', 6, 90.00, '1', 2024, 'Excellent results'),
(13, '2410010004', 6, 68.00, '1', 2024, 'Excellent results'),
(14, '2410010001', 2, 67.00, '1', 2024, ''),
(15, '2410010002', 2, 68.00, '1', 2024, ''),
(16, '2410010003', 2, 90.00, '1', 2024, 'Excellent results'),
(17, '2410010004', 2, 68.00, '1', 2024, 'Excellent results'),
(18, '2410010001', 1, 67.00, '1', 2024, ''),
(19, '2410010002', 1, 68.00, '1', 2024, ''),
(20, '2410010003', 1, 90.00, '1', 2024, 'Excellent results'),
(21, '2410010004', 1, 68.00, '1', 2024, 'Excellent results'),
(22, '2409010001', 1, 67.00, '1', 2024, ''),
(23, '2409010002', 2, 88.00, '1', 2024, ''),
(24, '2411010001', 2, 80.00, '1', 2024, ''),
(25, '2411010002', 2, 78.00, '1', 2024, ''),
(26, '2411010003', 2, 90.00, '1', 2024, ''),
(27, '2411010001', 2, 80.00, '1', 2024, ''),
(28, '2411010002', 2, 78.00, '1', 2024, ''),
(29, '2411010003', 2, 90.00, '1', 2024, '');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'Mathematics'),
(2, 'Biology'),
(3, 'Science'),
(4, 'English'),
(5, 'Civics'),
(6, 'Geography');

-- --------------------------------------------------------

--
-- Table structure for table `subject_teacher`
--

DROP TABLE IF EXISTS `subject_teacher`;
CREATE TABLE IF NOT EXISTS `subject_teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `subject_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nrc_number` varchar(50) NOT NULL,
  `tcz_number` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `class_id` int DEFAULT NULL,
  `class_ids` varchar(255) DEFAULT NULL,
  `active` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `firstname`, `lastname`, `username`, `password`, `nrc_number`, `tcz_number`, `contact`, `address`, `class_id`, `class_ids`, `active`) VALUES
(1, 'John', 'Doe', 'john_doe', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'NRC123', 'TCZ123', '1234567890', '123 Street Name', 1, NULL, 0),
(2, 'lupasa', 'mwansa', 'lupasa', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '4674774347', '0099865656', '098957747774', 'choma', 3, '1,2,3,4', 0),
(3, 'Malumbo', 'Kanjela', 'malumbo', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '38463', '19373', '92739', 'kAMAILA', 1, '1,2,3,4,5', 0),
(4, 'lingoshi', 'rubben', 'lingoshi', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '0968686855', '0958845874', '059568968959', 'chavuma', 1, '1,3,5', 0),
(5, 'shadreck', 'phiri', 'shadreck', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '515300/91/1', '783', '64835', 'lilayi', 4, '1,3,4', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','subject_teacher') NOT NULL,
  `active` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `active`) VALUES
('Brian', SHA2('2000', 256), 'admin', 1),

(2, 'lupasa', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'teacher', 1),
(3, 'malumbo', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'subject_teacher', 1),
(4, 'lingoshi', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'teacher', 1),
(5, 'shadreck', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'subject_teacher', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`pupil_id`) REFERENCES `pupils` (`id`);

--
-- Constraints for table `pupils`
--
ALTER TABLE `pupils`
  ADD CONSTRAINT `pupils_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pupils_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`pupil_id`) REFERENCES `pupils` (`id`),
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  ADD CONSTRAINT `subject_teacher_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_teacher_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
