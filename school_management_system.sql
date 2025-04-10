-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2025 at 04:44 PM
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
-- Database: `school_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `book_loans`
--

CREATE TABLE `book_loans` (
  `loan_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `pupil_id` int(11) NOT NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_loans`
--

INSERT INTO `book_loans` (`loan_id`, `book_id`, `pupil_id`, `loan_date`, `due_date`, `return_date`) VALUES
(1, 1, 1, '2025-03-27', '2025-04-10', NULL),
(2, 2, 3, '2025-03-22', '2025-04-05', NULL),
(3, 3, 5, '2025-03-25', '2025-04-08', NULL),
(4, 4, 7, '2025-03-20', '2025-04-03', NULL),
(5, 5, 9, '2025-03-29', '2025-04-12', NULL),
(6, 6, 11, '2025-03-24', '2025-04-07', NULL),
(7, 7, 13, '2025-03-31', '2025-04-14', NULL),
(8, 8, 15, '2025-03-18', '2025-04-01', NULL),
(9, 9, 17, '2025-03-23', '2025-04-06', NULL),
(10, 10, 19, '2025-03-26', '2025-04-09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `capacity`, `teacher_id`) VALUES
(1, 'Reception Year', 25, NULL),
(2, 'Year One', 25, 2),
(3, 'Year Two', 25, 3),
(4, 'Year Three', 27, 4),
(5, 'Year Four', 30, 5),
(6, 'Year Five', 25, 6),
(7, 'Year Six', 40, 9);

-- --------------------------------------------------------

--
-- Table structure for table `class_history`
--

CREATE TABLE `class_history` (
  `history_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_books`
--

CREATE TABLE `library_books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `published_year` int(11) NOT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `library_books`
--

INSERT INTO `library_books` (`book_id`, `title`, `author`, `isbn`, `published_year`, `available`) VALUES
(1, 'The Gruffalo', 'Julia Donaldson', '9780333710937', 1999, 0),
(2, 'The Very Hungry Caterpillar', 'Eric Carle', '9780241003008', 1969, 0),
(3, 'Where the Wild Things Are', 'Maurice Sendak', '9780060254926', 1963, 0),
(4, 'Goodnight Moon', 'Margaret Wise Brown', '9780060775858', 1947, 0),
(5, 'Brown Bear, Brown Bear, What Do You See?', 'Bill Martin Jr.', '9780805047905', 1967, 0),
(6, 'The Cat in the Hat', 'Dr. Seuss', '9780394800011', 1957, 0),
(7, 'Green Eggs and Ham', 'Dr. Seuss', '9780394800165', 1960, 0),
(8, 'Charlotte\'s Web', 'E.B. White', '9780061124952', 1952, 0),
(9, 'The Lion, the Witch and the Wardrobe', 'C.S. Lewis', '9780060234812', 1950, 0),
(10, 'Matilda', 'Roald Dahl', '9780142410370', 1988, 0),
(11, 'Charlie and the Chocolate Factory', 'Roald Dahl', '9780142410318', 1964, 1),
(12, 'The BFG', 'Roald Dahl', '9780142410387', 1982, 1),
(13, 'The Hobbit', 'J.R.R. Tolkien', '9780261102217', 1937, 1),
(14, 'Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', '9780747532743', 1997, 1),
(15, 'Harry Potter and the Chamber of Secrets', 'J.K. Rowling', '9780747538486', 1998, 1),
(16, 'Harry Potter and the Prisoner of Azkaban', 'J.K. Rowling', '9780747542155', 1999, 1),
(17, 'Harry Potter and the Goblet of Fire', 'J.K. Rowling', '9780747546245', 2000, 1),
(18, 'Harry Potter and the Order of the Phoenix', 'J.K. Rowling', '9780747551003', 2003, 1),
(19, 'Harry Potter and the Half-Blood Prince', 'J.K. Rowling', '9780747581086', 2005, 1),
(20, 'Harry Potter and the Deathly Hallows', 'J.K. Rowling', '9780545010221', 2007, 1),
(21, 'The Secret Garden', 'Frances Hodgson Burnett', '9780064401883', 1911, 1),
(23, 'Bridge to Terabithia', 'Katherine Paterson', '9780060734015', 1977, 1),
(24, 'Holes', 'Louis Sachar', '9780440414803', 1998, 1),
(25, 'The Giver', 'Lois Lowry', '9780544336261', 1993, 1),
(26, 'Number the Stars', 'Lois Lowry', '9780547577098', 1989, 1),
(27, 'Wonder', 'R.J. Palacio', '9780375869020', 2012, 1),
(28, 'The One and Only Ivan', 'Katherine Applegate', '9780061992254', 2012, 1),
(29, 'Percy Jackson and the Lightning Thief', 'Rick Riordan', '9780786838653', 2005, 1),
(30, 'Diary of a Wimpy Kid', 'Jeff Kinney', '9780810993136', 2007, 1);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `relationship` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parent_id`, `first_name`, `last_name`, `address`, `phone`, `email`, `relationship`) VALUES
(1, 'James', 'Smith', '123 Oak Street, Manchester, M1 1AB', '07700900123', 'james.smith@email.com', 'Father'),
(2, 'Sarah', 'Smith', '123 Oak Street, Manchester, M1 1AB', '07700900124', 'sarah.smith@email.com', 'Mother'),
(3, 'Michael', 'Johnson', '45 Maple Road, Salford, M3 2CD', '07700900234', 'michael.j@email.com', 'Father'),
(4, 'Emma', 'Johnson', '45 Maple Road, Salford, M3 2CD', '07700900235', 'emma.j@email.com', 'Mother'),
(5, 'David', 'Williams', '78 Pine Avenue, Bolton, BL1 3EF', '07700900345', 'david.w@email.com', 'Father'),
(6, 'Sophia', 'Williams', '78 Pine Avenue, Bolton, BL1 3EF', '07700900346', 'sophia.w@email.com', 'Mother'),
(7, 'Robert', 'Brown', '12 Elm Lane, Bury, BL9 4GH', '07700900456', 'robert.b@email.com', 'Father'),
(8, 'Olivia', 'Brown', '12 Elm Lane, Bury, BL9 4GH', '07700900457', 'olivia.b@email.com', 'Mother'),
(9, 'William', 'Jones', '34 Cedar Street, Rochdale, OL11 5IJ', '07700900567', 'william.j@email.com', 'Father'),
(10, 'Ava', 'Jones', '34 Cedar Street, Rochdale, OL11 5IJ', '07700900568', 'ava.j@email.com', 'Mother'),
(11, 'Thomas', 'Garcia', '56 Birch Road, Oldham, OL4 6KL', '07700900678', 'thomas.g@email.com', 'Father'),
(12, 'Mia', 'Garcia', '56 Birch Road, Oldham, OL4 6KL', '07700900679', 'mia.g@email.com', 'Mother'),
(13, 'Christopher', 'Miller', '89 Willow Way, Stockport, SK1 7MN', '07700900789', 'chris.m@email.com', 'Father'),
(14, 'Charlotte', 'Miller', '89 Willow Way, Stockport, SK1 7MN', '07700900790', 'charlotte.m@email.com', 'Mother'),
(15, 'Daniel', 'Davis', '23 Ash Grove, Trafford, M32 8OP', '07700900890', 'daniel.d@email.com', 'Father'),
(16, 'Amelia', 'Davis', '23 Ash Grove, Trafford, M32 8OP', '07700900891', 'amelia.d@email.com', 'Mother'),
(17, 'Matthew', 'Rodriguez', '67 Redwood Close, Wigan, WN1 9QR', '07700900901', 'matt.r@email.com', 'Father'),
(18, 'Harper', 'Rodriguez', '67 Redwood Close, Wigan, WN1 9QR', '07700900902', 'harper.r@email.com', 'Mother'),
(19, 'Andrew', 'Martinez', '90 Sequoia Drive, Tameside, SK14 0ST', '07700901012', 'andrew.m@email.com', 'Father'),
(20, 'Evelyn', 'Martinez', '90 Sequoia Drive, Tameside, SK14 0ST', '07700901013', 'evelyn.m@email.com', 'Mother');

-- --------------------------------------------------------

--
-- Table structure for table `pupils`
--

CREATE TABLE `pupils` (
  `pupil_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text NOT NULL,
  `medical_info` text DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `parent1_id` int(11) DEFAULT NULL,
  `parent2_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pupils`
--

INSERT INTO `pupils` (`pupil_id`, `first_name`, `last_name`, `date_of_birth`, `address`, `medical_info`, `class_id`, `parent1_id`, `parent2_id`) VALUES
(1, 'Liam', 'Smith', '2018-05-12', '123 Oak Street, Manchester, M1 1AB', 'Peanut allergy', 1, 1, 2),
(2, 'Noah', 'Johnson', '2018-07-23', '45 Maple Road, Salford, M3 2CD', NULL, 1, 3, 4),
(3, 'Oliver', 'Williams', '2018-03-15', '78 Pine Avenue, Bolton, BL1 3EF', 'Asthma', 1, 5, 6),
(4, 'Elijah', 'Brown', '2018-09-30', '12 Elm Lane, Bury, BL9 4GH', NULL, 1, 7, 8),
(5, 'William', 'Jones', '2018-11-05', '34 Cedar Street, Rochdale, OL11 5IJ', NULL, 1, 9, 10),
(6, 'Benjamin', 'Garcia', '2018-01-18', '56 Birch Road, Oldham, OL4 6KL', NULL, 1, 11, 12),
(7, 'Lucas', 'Miller', '2018-08-22', '89 Willow Way, Stockport, SK1 7MN', NULL, 1, 13, 14),
(8, 'Henry', 'Davis', '2018-04-07', '23 Ash Grove, Trafford, M32 8OP', 'Dairy intolerance', 1, 15, 16),
(9, 'Alexander', 'Rodriguez', '2018-12-14', '67 Redwood Close, Wigan, WN1 9QR', NULL, 1, 17, 18),
(10, 'Mason', 'Martinez', '2018-06-29', '90 Sequoia Drive, Tameside, SK14 0ST', NULL, 1, 19, 20),
(11, 'Ethan', 'Smith', '2017-02-11', '123 Oak Street, Manchester, M1 1AB', NULL, 2, 1, 2),
(12, 'Daniel', 'Johnson', '2017-05-24', '45 Maple Road, Salford, M3 2CD', NULL, 2, 3, 4),
(13, 'Matthew', 'Williams', '2017-09-16', '78 Pine Avenue, Bolton, BL1 3EF', NULL, 2, 5, 6),
(14, 'Aiden', 'Brown', '2017-11-01', '12 Elm Lane, Bury, BL9 4GH', 'Epilepsy', 2, 7, 8),
(15, 'Joseph', 'Jones', '2017-04-19', '34 Cedar Street, Rochdale, OL11 5IJ', NULL, 2, 9, 10),
(16, 'Jackson', 'Garcia', '2017-07-25', '56 Birch Road, Oldham, OL4 6KL', NULL, 2, 11, 12),
(17, 'Samuel', 'Miller', '2017-01-08', '89 Willow Way, Stockport, SK1 7MN', NULL, 2, 13, 14),
(18, 'Sebastian', 'Davis', '2017-10-13', '23 Ash Grove, Trafford, M32 8OP', NULL, 2, 15, 16),
(19, 'David', 'Rodriguez', '2017-03-27', '67 Redwood Close, Wigan, WN1 9QR', NULL, 2, 17, 18),
(20, 'Carter', 'Martinez', '2017-08-31', '90 Sequoia Drive, Tameside, SK14 0ST', NULL, 2, 19, 20),
(21, 'Wyatt', 'Smith', '2016-06-10', '123 Oak Street, Manchester, M1 1AB', NULL, 3, 1, 2),
(22, 'Jayden', 'Johnson', '2016-09-21', '45 Maple Road, Salford, M3 2CD', 'Asthma', 3, 3, 4),
(23, 'John', 'Williams', '2016-02-14', '78 Pine Avenue, Bolton, BL1 3EF', NULL, 3, 5, 6),
(24, 'Owen', 'Brown', '2016-12-03', '12 Elm Lane, Bury, BL9 4GH', NULL, 3, 7, 8),
(25, 'Dylan', 'Jones', '2016-05-17', '34 Cedar Street, Rochdale, OL11 5IJ', NULL, 3, 9, 10),
(26, 'Luke', 'Garcia', '2016-08-26', '56 Birch Road, Oldham, OL4 6KL', NULL, 3, 11, 12),
(27, 'Gabriel', 'Miller', '2016-01-09', '89 Willow Way, Stockport, SK1 7MN', NULL, 3, 13, 14),
(28, 'Anthony', 'Davis', '2016-10-15', '23 Ash Grove, Trafford, M32 8OP', NULL, 3, 15, 16),
(29, 'Isaac', 'Rodriguez', '2016-04-28', '67 Redwood Close, Wigan, WN1 9QR', NULL, 3, 17, 18),
(30, 'Grayson', 'Martinez', '2016-07-30', '90 Sequoia Drive, Tameside, SK14 0ST', NULL, 3, 19, 20),
(31, 'Jack', 'Smith', '2015-03-12', '123 Oak Street, Manchester, M1 1AB', NULL, 4, 1, 2),
(32, 'Julian', 'Johnson', '2015-06-23', '45 Maple Road, Salford, M3 2CD', NULL, 4, 3, 4),
(33, 'Levi', 'Williams', '2015-09-15', '78 Pine Avenue, Bolton, BL1 3EF', NULL, 4, 5, 6),
(34, 'Christopher', 'Brown', '2015-11-30', '12 Elm Lane, Bury, BL9 4AA', '', 4, 7, 8),
(35, 'Joshua', 'Jones', '2015-04-05', '34 Cedar Street, Rochdale, OL11 5IJ', NULL, 4, 9, 10),
(36, 'Andrew', 'Garcia', '2015-01-18', '56 Birch Road, Oldham, OL4 6KL', NULL, 4, 11, 12),
(37, 'Lincoln', 'Miller', '2015-08-22', '89 Willow Way, Stockport, SK1 7MN', NULL, 4, 13, 14),
(38, 'Mateo', 'Davis', '2015-12-07', '23 Ash Grove, Trafford, M32 8OP', NULL, 4, 15, 16),
(39, 'Ryan', 'Rodriguez', '2015-05-14', '67 Redwood Close, Wigan, WN1 9QR', NULL, 4, 17, 18),
(40, 'Jaxon', 'Martinez', '2015-07-29', '90 Sequoia Drive, Tameside, SK14 0ST', NULL, 4, 19, 20),
(41, 'Nathan', 'Smith', '2014-02-11', '123 Oak Street, Manchester, M1 1AB', NULL, 5, 1, 2),
(42, 'Aaron', 'Johnson', '2014-05-24', '45 Maple Road, Salford, M3 2CD', NULL, 5, 3, 4),
(43, 'Isaiah', 'Williams', '2014-09-16', '78 Pine Avenue, Bolton, BL1 3EF', NULL, 5, 5, 6),
(44, 'Thomas', 'Brown', '2014-11-01', '12 Elm Lane, Bury, BL9 4GH', NULL, 5, 7, 8),
(45, 'Charles', 'Jones', '2014-04-19', '34 Cedar Street, Rochdale, OL11 5IJ', NULL, 5, 9, 10),
(46, 'Caleb', 'Garcia', '2014-07-25', '56 Birch Road, Oldham, OL4 6KL', NULL, 5, 11, 12),
(47, 'Josiah', 'Miller', '2014-01-08', '89 Willow Way, Stockport, SK1 7MN', NULL, 5, 13, 14),
(49, 'Hunter', 'Rodriguez', '2014-03-27', '67 Redwood Close, Wigan, WN1 9QR', NULL, 5, 17, 18),
(50, 'Eli', 'Martinez', '2014-08-31', '90 Sequoia Drive, Tameside, SK14 0ST', NULL, 5, 19, 20),
(51, 'Jonathan', 'Smith', '2013-06-10', '123 Oak Street, Manchester, M1 1AB', NULL, 6, 1, 2),
(52, 'Connor', 'Johnson', '2013-09-21', '45 Maple Road, Salford, M3 2CD', NULL, 6, 3, 4),
(53, 'Landon', 'Williams', '2013-02-14', '78 Pine Avenue, Bolton, BL1 3EF', NULL, 6, 5, 6),
(54, 'Adrian', 'Brown', '2013-12-03', '12 Elm Lane, Bury, BL9 4GH', NULL, 6, 7, 8),
(55, 'Asher', 'Jones', '2013-05-17', '34 Cedar Street, Rochdale, OL11 5IJ', NULL, 6, 9, 10),
(56, 'Cameron', 'Garcia', '2013-08-26', '56 Birch Road, Oldham, OL4 6KL', NULL, 6, 11, 12),
(57, 'Leo', 'Miller', '2013-01-09', '89 Willow Way, Stockport, SK1 7MN', NULL, 6, 13, 14),
(58, 'Theodore', 'Davis', '2013-10-15', '23 Ash Grove, Trafford, M32 8OP', NULL, 6, 15, 16),
(59, 'Jeremiah', 'Rodriguez', '2013-04-28', '67 Redwood Close, Wigan, WN1 9QR', NULL, 6, 17, 18),
(60, 'Hudson', 'Martinez', '2013-07-30', '90 Sequoia Drive, Tameside, SK14 0ST', NULL, 6, 19, 20),
(61, 'Robert', 'Smith', '2012-03-12', '123 Oak Street, Manchester, M1 1AB', NULL, 7, 1, 2),
(62, 'Evan', 'Johnson', '2012-06-23', '45 Maple Road, Salford, M3 2CD', NULL, 7, 3, 4),
(63, 'Nicholas', 'Williams', '2012-09-15', '78 Pine Avenue, Bolton, BL1 3EF', NULL, 7, 5, 6),
(64, 'Ezra', 'Brown', '2012-11-30', '12 Elm Lane, Bury, BL9 4GH', NULL, 7, 7, 8),
(65, 'Colton', 'Jones', '2012-04-05', '34 Cedar Street, Rochdale, OL11 5IJ', NULL, 7, 9, 10),
(66, 'Angel', 'Garcia', '2012-01-18', '56 Birch Road, Oldham, OL4 6KL', NULL, 7, 11, 12),
(67, 'Brayden', 'Miller', '2012-08-22', '89 Willow Way, Stockport, SK1 7MN', NULL, 7, 13, 14),
(69, 'Dominic', 'Rodriguez', '2012-05-14', '67 Redwood Close, Wigan, WN1 9QR', NULL, 7, 17, 18),
(70, 'Austin', 'Martinez', '2012-07-29', '90 Sequoia Drive, Tameside, SK14 0ST', NULL, 7, 19, 20);

-- --------------------------------------------------------

--
-- Table structure for table `ta_class_history`
--

CREATE TABLE `ta_class_history` (
  `history_id` int(11) NOT NULL,
  `ta_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `background_check` tinyint(1) DEFAULT 0,
  `hire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `first_name`, `last_name`, `address`, `phone`, `email`, `salary`, `background_check`, `hire_date`) VALUES
(1, 'Jennifer', 'Wilson', '101 Beech Street, Manchester, M1 2AB', '07700901123', 'jennifer.w@school.edu', 32000.00, 1, '2018-09-01'),
(2, 'Richard', 'Anderson', '202 Spruce Avenue, Salford, M3 3CD', '07700901234', 'richard.a@school.edu', 34000.00, 1, '2017-09-01'),
(3, 'Patricia', 'Thomas', '303 Fir Lane, Bolton, BL1 4EF', '07700901345', 'patricia.t@school.edu', 36000.00, 1, '2016-09-01'),
(4, 'Joseph', 'Jackson', '404 Hemlock Road, Bury, BL9 5GH', '07700901456', 'joseph.j@school.edu', 38000.00, 1, '2015-09-01'),
(5, 'Elizabeth', 'White', '505 Cypress Street, Rochdale, OL11 6IJ', '07700901567', 'elizabeth.w@school.edu', 40000.00, 1, '2014-09-01'),
(6, 'Charles', 'Harris', '606 Juniper Way, Oldham, OL4 7KL', '07700901678', 'charles.h@school.edu', 42000.00, 1, '2013-09-01'),
(7, 'Margaret', 'Martin', '707 Magnolia Drive, Stockport, SK1 8MN', '07700901789', 'margaret.m@school.edu', 44000.00, 1, '2012-09-01'),
(9, 'Saqib', 'Imran', '123 Manchester Street', '07825632137', 'saqimimran@icloud.com', 32500.00, 1, '2025-04-04'),
(11, 'Shahreyar', 'Ahmed', '4 Oakfield Terrace', '07305193508', 'shahreyara07@gmail.com', 20000.00, 1, '2025-04-08');

-- --------------------------------------------------------

--
-- Table structure for table `teaching_assistants`
--

CREATE TABLE `teaching_assistants` (
  `ta_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `background_check` tinyint(1) DEFAULT 0,
  `hire_date` date NOT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teaching_assistants`
--

INSERT INTO `teaching_assistants` (`ta_id`, `first_name`, `last_name`, `address`, `phone`, `email`, `salary`, `background_check`, `hire_date`, `class_id`) VALUES
(1, 'Emily', 'Taylor', '111 Aspen Street, Manchester, M1 3AB', '07700902123', 'emily.t@school.edu', 22000.00, 1, '2019-09-01', 1),
(2, 'Daniel', 'Moore', '222 Redwood Avenue, Salford, M3 4CD', '07700902234', 'daniel.m@school.edu', 22000.00, 1, '2019-09-01', 1),
(3, 'Madison', 'Lee', '333 Sequoia Lane, Bolton, BL1 5EF', '07700902345', 'madison.l@school.edu', 23000.00, 1, '2018-09-01', 2),
(4, 'Alexander', 'Perez', '444 Cedar Road, Bury, BL9 6GH', '07700902456', 'alexander.p@school.edu', 23000.00, 1, '2018-09-01', 2),
(5, 'Abigail', 'Thompson', '555 Birch Street, Rochdale, OL11 7IJ', '07700902567', 'abigail.t@school.edu', 24000.00, 1, '2017-09-01', 3),
(6, 'Ryan', 'Clark', '666 Pine Way, Oldham, OL4 8KL', '07700902678', 'ryan.c@school.edu', 24000.00, 1, '2017-09-01', 3),
(7, 'Olivia', 'Lewis', '777 Elm Drive, Stockport, SK1 9MN', '07700902789', 'olivia.l@school.edu', 25000.00, 1, '2016-09-01', 4),
(8, 'Noah', 'Walker', '888 Maple Close, Trafford, M32 0OP', '07700902890', 'noah.w@school.edu', 25000.00, 1, '2016-09-01', 4),
(9, 'Sophia', 'Hall', '999 Oak Grove, Wigan, WN1 1QR', '07700902901', 'sophia.h@school.edu', 26000.00, 1, '2015-09-01', 5),
(10, 'Jacob', 'Young', '1010 Beech Road, Tameside, SK14 2ST', '07700903012', 'jacob.y@school.edu', 26000.00, 1, '2015-09-01', 5),
(11, 'Isabella', 'Allen', '1111 Willow Lane, Manchester, M1 3AB', '07700903123', 'isabella.a@school.edu', 27000.00, 1, '2014-09-01', 6),
(12, 'Michael', 'King', '1212 Ash Avenue, Salford, M3 4CD', '07700903234', 'michael.k@school.edu', 27000.00, 1, '2014-09-01', 6),
(13, 'Charlotte', 'Wright', '1313 Fir Street, Bolton, BL1 5EF', '07700903345', 'charlotte.w@school.edu', 28000.00, 1, '2013-09-01', 7),
(14, 'Ethan', 'Scott', '1414 Hemlock Way, Bury, BL9 6GH', '07700903456', 'ethan.s@school.edu', 28000.00, 1, '2013-09-01', 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_loans`
--
ALTER TABLE `book_loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `pupil_id` (`pupil_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `unique_class_name` (`class_name`),
  ADD KEY `fk_teacher` (`teacher_id`);

--
-- Indexes for table `class_history`
--
ALTER TABLE `class_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `library_books`
--
ALTER TABLE `library_books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`);

--
-- Indexes for table `pupils`
--
ALTER TABLE `pupils`
  ADD PRIMARY KEY (`pupil_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `parent1_id` (`parent1_id`),
  ADD KEY `parent2_id` (`parent2_id`);

--
-- Indexes for table `ta_class_history`
--
ALTER TABLE `ta_class_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `ta_id` (`ta_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `teaching_assistants`
--
ALTER TABLE `teaching_assistants`
  ADD PRIMARY KEY (`ta_id`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_loans`
--
ALTER TABLE `book_loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `class_history`
--
ALTER TABLE `class_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_books`
--
ALTER TABLE `library_books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pupils`
--
ALTER TABLE `pupils`
  MODIFY `pupil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `ta_class_history`
--
ALTER TABLE `ta_class_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teaching_assistants`
--
ALTER TABLE `teaching_assistants`
  MODIFY `ta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_loans`
--
ALTER TABLE `book_loans`
  ADD CONSTRAINT `book_loans_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`book_id`),
  ADD CONSTRAINT `book_loans_ibfk_2` FOREIGN KEY (`pupil_id`) REFERENCES `pupils` (`pupil_id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `fk_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE SET NULL;

--
-- Constraints for table `class_history`
--
ALTER TABLE `class_history`
  ADD CONSTRAINT `class_history_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  ADD CONSTRAINT `class_history_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `pupils`
--
ALTER TABLE `pupils`
  ADD CONSTRAINT `pupils_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pupils_ibfk_2` FOREIGN KEY (`parent1_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pupils_ibfk_3` FOREIGN KEY (`parent2_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL;

--
-- Constraints for table `ta_class_history`
--
ALTER TABLE `ta_class_history`
  ADD CONSTRAINT `ta_class_history_ibfk_1` FOREIGN KEY (`ta_id`) REFERENCES `teaching_assistants` (`ta_id`),
  ADD CONSTRAINT `ta_class_history_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `teaching_assistants`
--
ALTER TABLE `teaching_assistants`
  ADD CONSTRAINT `teaching_assistants_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
