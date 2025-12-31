-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2021 at 05:03 PM
-- Server version: 5.6.21
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `etmsh`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_info`
--

CREATE TABLE IF NOT EXISTS `attendance_info` (
`aten_id` int(20) NOT NULL,
  `atn_user_id` int(20) NOT NULL,
  `in_time` varchar(200) DEFAULT NULL,
  `out_time` varchar(150) DEFAULT NULL,
  `total_duration` varchar(100) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendance_info`
--

TRUNCATE TABLE `attendance_info`;

INSERT INTO `attendance_info` (`aten_id`, `atn_user_id`, `in_time`, `out_time`, `total_duration`) VALUES
(1, 1, '22-03-2021 08:00:00', '22-03-2021 17:00:00', '9 hours'),
(2, 2, '22-03-2021 08:30:00', '22-03-2021 16:30:00', '8 hours'),
(3, 3, '22-03-2021 09:00:00', NULL, NULL),
(4, 4, '22-03-2021 08:15:00', '22-03-2021 16:45:00', '8.5 hours');

-- --------------------------------------------------------

--
-- Table structure for table `task_info`
--

CREATE TABLE IF NOT EXISTS `task_info` (
`task_id` int(50) NOT NULL,
  `t_title` varchar(120) NOT NULL,
  `t_description` text,
  `t_start_time` varchar(100) DEFAULT NULL,
  `t_end_time` varchar(100) DEFAULT NULL,
  `t_user_id` int(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = incomplete, 1 = In progress, 2 = complete',
  `required_skill` varchar(100) DEFAULT NULL,
  `assignment_status` varchar(20) DEFAULT 'pending' 
  COMMENT 'pending, accepted, rejected'
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `task_info`
--

INSERT INTO `task_info` (`task_id`, `t_title`, `t_description`, `t_start_time`, `t_end_time`, `t_user_id`, `status`, `required_skill`) VALUES
(20, 'Communications', 'You''re assigned to handle incoming calls and other communications within the office.', '2021-03-22 12:00', '2021-03-22 13:00', 17, 2, NULL),
(21, 'Filing', 'You''re assigned to management of filing system.', '2021-03-22 10:00', '2021-03-22 15:10', 22, 0, NULL),
(22, 'Virtual Meeting', 'Please join the virtual meeting with your senior manager regarding your works on this placement.', '2021-03-22 15:00', '2021-03-22 15:20', 24, 0, NULL),
(23, 'Data Entry', 'Go through some data!', '2021-03-22 14:00', '2021-03-22 17:00', 25, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE IF NOT EXISTS `tbl_admin` (
`user_id` int(20) NOT NULL,
  `fullname` varchar(120) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `temp_password` varchar(100) DEFAULT NULL,
  `user_role` int(10) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_admin`
--

TRUNCATE TABLE `tbl_admin`;

INSERT INTO `tbl_admin` (`user_id`, `fullname`, `username`, `email`, `password`, `temp_password`, `user_role`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', NULL, 1),
(2, 'ahmad', 'ahmad', 'ahmad@gmail.com', NULL, '123456', 2),
(3, 'Hadi', 'hadi', 'hadi@gmail.com', NULL, '123456', 2),
(4, 'hadi', 'hadi', 'hadi@gmail.com', NULL, '123456', 2);

--
-- Table structure for table `employee_skills`
--

CREATE TABLE IF NOT EXISTS `employee_skills` (
  `skill_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `proficiency_level` varchar(50) NOT NULL, -- 'Beginner', 'Intermediate', 'Expert'
  PRIMARY KEY (`skill_id`),
  FOREIGN KEY (`user_id`) REFERENCES `tbl_admin`(`user_id`) ON DELETE CASCADE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_info`
--
ALTER TABLE `attendance_info`
 ADD PRIMARY KEY (`aten_id`);

--
-- Indexes for table `task_info`
--
ALTER TABLE `task_info`
 ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_info`
--
ALTER TABLE `attendance_info`
MODIFY `aten_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `task_info`
--
ALTER TABLE `task_info`
MODIFY `task_id` int(50) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;

-- Create login function
DELIMITER //
CREATE FUNCTION check_login(username VARCHAR(100), pass VARCHAR(100)) 
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE valid INT;
    SELECT COUNT(*) INTO valid 
    FROM tbl_admin 
    WHERE username = username 
    AND password = MD5(pass);
    RETURN valid;
END //
DELIMITER ;

-- Create view for task statistics
CREATE OR REPLACE VIEW task_statistics AS
SELECT 
    t_user_id,
    COUNT(*) as total_tasks,
    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as incomplete_tasks,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as in_progress_tasks,
    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completed_tasks
FROM task_info
GROUP BY t_user_id;

-- Create indexes
CREATE INDEX idx_task_user ON task_info(t_user_id);
CREATE INDEX idx_task_status ON task_info(status);
CREATE INDEX idx_attendance_user ON attendance_info(atn_user_id);
CREATE INDEX idx_employee_skills ON employee_skills(user_id, skill_name);

-- Create trigger for task assignment
DELIMITER //
CREATE TRIGGER before_task_assignment
BEFORE INSERT ON task_info
FOR EACH ROW
BEGIN
    DECLARE skill_exists INT;
    
    IF NEW.required_skill IS NOT NULL THEN
        SELECT COUNT(*) INTO skill_exists
        FROM employee_skills
        WHERE user_id = NEW.t_user_id 
        AND skill_name = NEW.required_skill;
        
        IF skill_exists = 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Employee does not have the required skill';
        END IF;
    END IF;
END //
DELIMITER ;

-- Create stored procedure for task reassignment
DELIMITER //
CREATE PROCEDURE reassign_tasks(
    IN old_user_id INT,
    IN new_user_id INT
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE task_id INT;
    DECLARE cur CURSOR FOR 
        SELECT task_id 
        FROM task_info 
        WHERE t_user_id = old_user_id 
        AND status != 2;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    START TRANSACTION;
    
    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO task_id;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        UPDATE task_info 
        SET t_user_id = new_user_id,
            assignment_status = 'pending'
        WHERE task_id = task_id;
    END LOOP;
    
    CLOSE cur;
    COMMIT;
END //
DELIMITER ;

-- Create trigger for attendance tracking
DELIMITER //
CREATE TRIGGER after_attendance_insert
AFTER INSERT ON attendance_info
FOR EACH ROW
BEGIN
    -- Log attendance entry
    INSERT INTO attendance_log (user_id, action_type, action_time)
    VALUES (NEW.atn_user_id, 'CLOCK_IN', NOW());
END //
DELIMITER ;

-- Create attendance log table
CREATE TABLE attendance_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action_type ENUM('CLOCK_IN', 'CLOCK_OUT') NOT NULL,
    action_time DATETIME NOT NULL,
    INDEX (user_id, action_time)
);

-- Create function to calculate task completion rate
DELIMITER //
CREATE FUNCTION calculate_completion_rate(user_id INT) 
RETURNS DECIMAL(5,2)
DETERMINISTIC
BEGIN
    DECLARE total INT;
    DECLARE completed INT;
    DECLARE rate DECIMAL(5,2);
    
    SELECT COUNT(*) INTO total
    FROM task_info
    WHERE t_user_id = user_id;
    
    SELECT COUNT(*) INTO completed
    FROM task_info
    WHERE t_user_id = user_id
    AND status = 2;
    
    IF total = 0 THEN
        RETURN 0;
    END IF;
    
    SET rate = (completed / total) * 100;
    RETURN rate;
END //
DELIMITER ;

-- Create view for employee performance
CREATE OR REPLACE VIEW employee_performance AS
SELECT 
    a.user_id,
    a.fullname,
    COUNT(DISTINCT t.task_id) as total_tasks,
    COUNT(DISTINCT CASE WHEN t.status = 2 THEN t.task_id END) as completed_tasks,
    calculate_completion_rate(a.user_id) as completion_rate,
    COUNT(DISTINCT s.skill_id) as total_skills
FROM tbl_admin a
LEFT JOIN task_info t ON a.user_id = t.t_user_id
LEFT JOIN employee_skills s ON a.user_id = s.user_id
WHERE a.user_role = 2
GROUP BY a.user_id;

-- Add sample data for skills
INSERT INTO employee_skills (user_id, skill_name, proficiency_level) VALUES
(17, 'PHP', 'Expert'),
(17, 'MySQL', 'Intermediate'),
(18, 'JavaScript', 'Expert'),
(18, 'Python', 'Intermediate'),
(19, 'Java', 'Expert'),
(20, 'C++', 'Intermediate');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;