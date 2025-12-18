-- Create database and two tables for simple student management
-- Import this file via phpMyAdmin or run in MySQL CLI

CREATE DATABASE IF NOT EXISTS `student_management`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `student_management`;

-- Classes table
CREATE TABLE IF NOT EXISTS `classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Students table
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) UNIQUE,
  `class_id` INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Sample data
INSERT INTO `classes` (`name`, `description`) VALUES
('K64A', 'Lớp K64A - CNTT'),
('K64B', 'Lớp K64B - Mạng');

INSERT INTO `students` (`name`, `email`, `class_id`) VALUES
('Nguyen Van A', 'a@example.com', 1),
('Tran Thi B', 'b@example.com', 2);
