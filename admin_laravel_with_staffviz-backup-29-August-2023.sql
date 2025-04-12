-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2023 at 10:16 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_laravel_with_staffviz`
--

-- --------------------------------------------------------

--
-- Table structure for table `typicms_app_fd_api_service`
--

CREATE TABLE `typicms_app_fd_api_service` (
  `id` varchar(255) NOT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datemodified` datetime DEFAULT NULL,
  `app` longtext DEFAULT NULL,
  `serviceKey` longtext DEFAULT NULL,
  `type` longtext DEFAULT NULL,
  `params` longtext DEFAULT NULL,
  `query` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typicms_app_fd_api_service`
--

INSERT INTO `typicms_app_fd_api_service` (`id`, `datecreated`, `datemodified`, `app`, `serviceKey`, `type`, `params`, `query`) VALUES
('06228c96-9068-441a-8bfa-6e3c077587f3', '2022-06-21 12:17:31', '2022-07-17 03:41:46', 'desktop', 'projects.assigned.summary', '', '', 'SELECT \n    COUNT(DISTINCT (p.project_id)) assigned_projects,\n    COUNT(DISTINCT (ua.id)) assigned_tasks,\n    COUNT(DISTINCT (uc.id)) assigned_courses\nFROM\n    user_projects p\n     LEFT   JOIN\n    user_activities ua ON ua.user_id = p.user_id\n    LEFT    JOIN\n    user_courses uc ON uc.user_id = p.user_id\nWHERE\n    p.user_id = ?\n	AND ua.activity_status != 2; '),
('069a591b-7259-415d-9fb5-0cea7e5f8c1e', '2022-07-16 13:39:09', '2022-07-17 04:47:55', 'desktop', 'user.courses', '', '', 'select 0 as project_id, uc.course_id as activity_id, uc.id as user_activity_id, c.title, \r\n date_format(uc.start_date,\'%Y-%m-%d %H:%m:%s\') start_date, date_format(uc.due_date,\'%Y-%m-%d %H:%m:%s\') due_date, \r\n c.description as description, \'\' allocated_hours, uc.status,\r\n ifnull(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(l.end_time, l.start_time)))),\'00:00:00\') AS total_time\r\n from courses c\r\n      join user_courses uc on c.id=uc.course_id\r\n      join users u on uc.user_id=u.id\r\n      LEFT JOIN timelogs l ON l.user_id=u.id and l.user_courses_id=uc.id\r\n where u.id=? and c.status=\'active\' and c.is_deleted=0 and uc.is_removed = 1\r\n group by u.id, uc.id '),
('069a591b-7259-415d-9fb5-0cea7e5f8c1f', '2022-07-16 13:39:09', '2022-07-16 13:39:09', 'desktop:1', 'server.utc.time', NULL, NULL, 'select date_format(utc_timestamp(), \'%Y-%m-%d %H:%i:%s\') as server_utc_time'),
('0c00d21d-fb96-43a5-ba7e-a92aebbeea12', '2022-06-15 10:12:56', '2022-06-17 08:00:26', 'desktop:1', 'company.user', '', '', 'select companies.* from  users  inner join  companies_users  on  companies_users . user_id  =  users . id \n inner join  companies  on  companies_users . company_id  =  companies . id  \n where  users . id  = ? and ( companies_users . user_id  = ? and  companies_users . is_terminated  = ? \n and  companies_users . is_deleted  = ? and  companies_users . status  = ?)\n order by  companies . id  asc;'),
('19ff1327-ca9c-475f-96a7-98fa159c3433', '2022-06-15 10:11:36', '2022-06-17 07:58:45', 'desktop:1', 'app.version', '', '', 'select * from app_versions order by id desc limit 1'),
('21e14948-90eb-11ed-a1eb-0242ac120002', '2023-01-10 13:44:57', '2023-01-10 13:44:57', 'desktop', 'user.breaks', 'desktop', NULL, ' select ub.id as break_id, ub.break_start as start_time,\r\nub.break_end as end_time,\r\nub.scheduled_by as shift_id,\r\nub.break_type as type,\r\nsh.start_time as shift_start_time,\r\nsh.end_time as shift_end_time\r\nfrom user_breaks ub\r\ninner join  users u on u.id = ub.user_id\r\ninner join  shifts sh on sh.id = ub.scheduled_by\r\nwhere u.email =\'#currentUser.username#\'\r\nand ub.status=1\r\n '),
('24edafca-37f4-48fe-b1dd-a789f1dbdef0', '2022-06-15 10:12:39', '2022-06-17 14:21:25', 'desktop:1', 'user.companies', '', '', 'select c.*, cu.profile_type as user_role ,\r\ncu.is_terminated as is_terminated ,\r\ncu.status as status\r\nfrom companies c\r\njoin users u\r\njoin companies_users cu on cu.user_id=u.id and c.id=cu.company_id\r\nwhere u.email=\'#currentUser.username#\' and cu.is_deleted=0 \r\nand cu.is_terminated= 0\r\nand  cu.status =\'active\''),
('36261e41-d407-4ff0-b980-e2bce4f93ca5', '2022-06-21 12:16:22', '2022-07-17 03:41:18', 'desktop', 'projects.today.summary', '', '', 'SELECT l.user_id, COUNT(DISTINCT(l.project_id)) active_projects_today, COUNT(DISTINCT(l.activity_id)) active_tasks_today, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time)))) AS total_working_time_today\nFROM timelogs l\nWHERE l.user_id=? AND DATE(l.end_time)=DATE(NOW())AND DATE(l.start_time)=DATE(NOW())AND l.user_courses_id IS NULL\nGROUP BY l.user_id;'),
('4049f520-6803-11ed-89df-0242ac140002', NULL, NULL, 'desktop', 'update.timelog.cost', NULL, NULL, 'update timelogs as t\r\ninner join companies_users as u on u.user_id=t.user_id\r\nset t.rate=ifnull(u.rate,0), t.bill= ifnull(u.bill,0 ), \r\nt.cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.rate,0),\r\nt.bill_cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.bill,0)\r\nwhere t.id=#FORM_ID#'),
('685c717c-7356-4d0c-a1a4-514d1154fc89', '2022-07-16 13:50:47', '2022-07-16 13:50:47', 'desktop', 'user.projects', NULL, NULL, 'select distinct(p.id), p.title from \r\n projects p join user_projects up on p.id=up.project_id\r\n where up.user_id=? and p.active=1 and p.is_deleted=0'),
('69396911-868a-458e-9c50-95e8624e0996', '2022-07-17 06:41:11', '2022-07-17 06:41:11', 'desktop', 'user.today.log', '', '', 'select l.id log_id, ifnull(l.project_id,0) project_id, ifnull(l.activity_id, l.user_courses_id) activity_id, \nCAST(l.start_time AS char) start_time,  CAST(l.end_time AS char) as end_time, SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(l.end_time, l.start_time))) as time_spent\nfrom timelogs l where l.user_id=?\nand date(start_time) between date(DATE_ADD(UTC_TIMESTAMP(), INTERVAL -27 HOUR)) and date(utc_timestamp)'),
('6bd9f141-b8e6-11ed-9064-0242ac120004', '2023-03-02 10:38:50', '2023-03-02 10:38:50', 'desktop', 'insert.idle_time.history', NULL, NULL, 'insert into timelogs_idle_time_history (timelogs_id, company_setting_idle_time, created_at)\r\nvalues (\'#FORM_ID#\', ?, now())'),
('a8ce22ae-6e5a-4a20-83bb-f1c8e78ebb2f', '2022-06-15 09:55:35', '2022-07-17 03:41:09', 'desktop', 'user.details', '', '', 'SELECT id AS userId, first_name, last_name, image, macAddress, client_app_version, date_format(utc_timestamp, \'%Y-%m-%d %H:%m\') server_utc_time\r\nFROM users\r\nWHERE email=\'#currentUser.username#\';'),
('aae805ff-67e4-11ed-89df-0242ac140002', NULL, NULL, 'desktop', 'update.task.status', NULL, NULL, 'update activities\r\ninner join \r\n(select s.id, case when pending=total then \'pending\' \r\nwhen completed<total then \'in progress\'\r\nwhen completed=total then \'completed\' end as status from \r\n\r\n(select a.id, a.title activity_title, a.task_status as activity_status, \r\n\r\nifnull((select count(user_id) from user_activities \r\nwhere activity_status=0 and activity_id=a.id \r\ngroup by activity_id),0) as pending,\r\n\r\nifnull((select count(user_id) from user_activities \r\nwhere activity_status=1 and activity_id=a.id \r\ngroup by activity_id),0) as inprogress,\r\n\r\nifnull((select count(user_id) from user_activities \r\nwhere activity_status=2 and activity_id=a.id \r\ngroup by activity_id),0) as completed,\r\n\r\nifnull(count(ua.user_id),0) as total\r\n\r\nfrom activities a \r\njoin user_activities ua on ua.activity_id=a.id\r\njoin user_activities ua1 on ua1.activity_id=a.id\r\nwhere ua1.id=?\r\ngroup by a.id) as s) as b on activities.id=b.id\r\nset activities.task_status=b.status\r\n'),
('ad7f73cb-6655-4de2-87fa-609b4f978765', '2022-06-20 14:14:18', '2022-07-17 07:45:25', 'desktop', 'user.activities', '', '', 'SELECT ua.id as user_activity_id, a.project_id, a.id as activity_id, a.title, date_format(a.start_date,\'%Y-%m-%d %H:%m:%s\') start_date\r\n , date_format(a.due_date,\'%Y-%m-%d %H:%m:%s\') due_date, a.allocated_hours, \r\n ifnull(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(l.end_time, l.start_time)))), \'00:00:00\') AS total_time,\r\n ifnull(a.description,\'\') description, \r\n case when ua.activity_status=0 then \'pending\' when ua.activity_status=1 then \'in progress\' end  as status\r\n FROM activities a\r\n      JOIN projects p ON a.project_id=p.id \r\n      JOIN user_projects up ON p.id=up.project_id\r\n      JOIN users u ON up.user_id=u.id\r\n      JOIN user_activities ua ON ua.user_id=u.id AND a.id=ua.activity_id AND ua.activity_status <>2\r\n      LEFT JOIN timelogs l ON l.user_id=u.id and l.activity_id=a.id\r\n WHERE u.id=? and p.active=1 and p.is_deleted=0  and a.deleted_at is null\r\n group by u.id, ua.id'),
('c9202fb6-ee73-4aa1-a6ff-608bf496373a', '2022-06-22 06:10:00', '2022-06-22 06:10:00', 'desktop:', 'company.user.details', NULL, NULL, 'SELECT u.id AS userId, u.first_name, u.last_name,\r\nu.email, u.image, u.macAddress, ifnull(u.client_app_version,\'\'),\r\ndate_format(utc_timestamp, \'%Y-%m-%d %H:%m\') server_utc_time,\r\nuc.allow_tracking as time_tracking, ifnull(uc.capture_screen, \'1\') as capture_screen, uc.web_tracking as web_app_tracking, uc.idle_time_tracking as idle_time,\r\nc.screen_capture_limit,c.screen_capture_duration,c.screen_capture_image_size, c.idle_time_threshold, c.theme_json,\r\nuc.default_approval as idle_time_approval,\r\nc.timezone_id as timezone_id,\r\nc.date_format as date_format,\r\nc.time_format as time_format,\r\nc.weekdays as  weekdays\r\nFROM users u\r\njoin companies_users uc on uc.user_id=u.id\r\njoin companies c on c.id=uc.company_id\r\nwhere email=\'#currentUser.username#\''),
('d2e12479-c6f9-11ed-aec8-0242ac160003', '2023-03-20 08:33:00', '2023-03-20 08:33:00', 'desktop', 'notifications.not.delivered', NULL, NULL, 'select message from push_notifications where is_delivered = false and (target_app=3 or target_app=1 )  and user_id=?'),
('d6994daf-59b4-4ce6-97ad-b37e749dd9e1', '2022-06-21 07:59:32', '2022-07-17 03:41:27', 'desktop', 'projects.timelogs', '', '', 'SELECT   *,SUM(TIME_TO_SEC(TIMEDIFF(timelogs.end_time, timelogs.start_time))) AS sum\nFROM\n    timelogs\nWHERE\n    timelogs.activity_id AND user_id = ?\nGROUP BY id'),
('ecdddb8a-e604-4888-a7ec-ec56774ed5e4', '2022-06-20 14:02:37', '2022-07-17 03:41:35', 'desktop', 'projects.summary', '', '', 'SELECT \n    p.*, u.email\nFROM\n    user_projects up\n        INNER JOIN\n    users u ON u.id = up.user_id\n        JOIN\n    projects p ON p.id = up.project_id\nWHERE\n    u.email = \'#currentUser.username#\''),
('f8eb01c4-d556-4f71-b9b0-d1144e54a50d', NULL, '2022-06-16 08:00:35', 'desktop', 'api.data', '', '', 'SELECT * FROM app_fd_api_service');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_blocks`
--

CREATE TABLE `typicms_blocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`body`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_companies`
--

CREATE TABLE `typicms_companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `emp_code_format` varchar(10) NOT NULL,
  `company_initial` char(25) DEFAULT NULL,
  `no_of_employee` int(11) DEFAULT 0,
  `super_admin_id` int(11) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `screen_capture_duration` int(11) NOT NULL DEFAULT 0,
  `screen_capture_image_size` varchar(50) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `instance_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_companies`
--

INSERT INTO `typicms_companies` (`id`, `title`, `emp_code_format`, `company_initial`, `no_of_employee`, `super_admin_id`, `logo`, `screen_capture_duration`, `screen_capture_image_size`, `timezone`, `status`, `instance_id`, `created_at`, `updated_at`) VALUES
(40, 'CRECENTECH', 'CT-0001', 'crt_23', 100, 42, 'tth/crt_40/gallery/all/RYsdkHo8XBHDtoQJ08qmcxnWEn3VGCesPLBtqXID.png', 0, NULL, 'US/Eastern', 0, 1, '2022-08-30 09:49:51', '2023-08-03 13:26:21'),
(41, 'Dejavu', 'DJ-001', 'djv_41', 402, 119, 'tth/djv_41/logo/HefysaldyxorZwK7_1662447798.png', 0, NULL, NULL, 1, 1, '2022-09-06 06:03:18', '2022-09-06 06:03:18'),
(42, 'Woo', 'Bank', 'hatf_42', 56, 120, 'tth/hatf_42/logo/vXDAKdOiTuKcUKDM_1662454384.png', 0, NULL, 'Etc/GMT+12', 1, 1, '2022-09-06 06:20:06', '2022-09-06 06:20:06'),
(43, 'Step2Agility', 'SA-001', 'sa_43', 50, 124, NULL, 0, NULL, NULL, 1, 1, '2022-09-06 10:02:08', '2022-09-06 10:02:08'),
(44, 'Step2Agility', 'SA-001', 'sa_44', 50, 125, NULL, 0, NULL, NULL, 1, 1, '2022-09-06 10:05:59', '2022-09-06 10:05:59'),
(45, 'LID', 'LID', 'lid_45', 50, 177, NULL, 0, NULL, NULL, 1, 1, '2022-09-21 07:07:40', '2022-09-21 07:07:40'),
(46, 'TMA', 'TMA', 'tma_46', 50, 178, NULL, 0, NULL, NULL, 1, 1, '2022-09-21 07:14:06', '2022-09-21 07:14:06'),
(47, 'Bush', 'Bush', 'bush_47', 77, 186, NULL, 0, NULL, NULL, 1, 1, '2022-10-14 03:24:37', '2022-10-14 03:24:37'),
(48, 'Houston', 'Houston', 'houston_48', 14, 187, NULL, 0, NULL, NULL, 1, 1, '2022-10-14 03:28:08', '2022-10-14 03:28:08'),
(49, 'NewCompany', 'CT-002', 'ggh_49', 42, 203, NULL, 0, NULL, NULL, 1, 1, '2022-10-18 06:54:50', '2022-10-18 06:54:50'),
(50, 'Alpha Company Azan', 'CT-001', 'alc_50', 100, 207, 'tth/alc_50/gallery/all/R7iumD5E3hWVoblso6aPmshR6sxppsfJCY8B7G3r.png', 0, NULL, NULL, 1, 1, '2022-11-19 05:53:29', '2022-11-19 05:53:29'),
(51, 'Faizan Solutions', 'FS-0022', 'fs_51', 100, 208, NULL, 0, NULL, NULL, 1, 1, '2022-11-19 05:59:48', '2022-11-19 05:59:48'),
(52, 'Donald', 'Har', 'har_52', 54, 209, 'tth/har_52/logo/ZVG7NSMdCeHM4ugTLZX8FyUbpTL4TU3cDYLbMoKY.png', 0, NULL, NULL, 1, 1, '2022-11-19 06:08:37', '2022-11-19 06:08:37');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_companies_currency`
--

CREATE TABLE `typicms_companies_currency` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  `currency_name` varchar(5) DEFAULT NULL,
  `currency_sign` varchar(2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 for disable , 2 for enable',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_companies_currency`
--

INSERT INTO `typicms_companies_currency` (`id`, `name`, `flag`, `currency_name`, `currency_sign`, `status`, `created_at`) VALUES
(1, 'American Samoa', 'American_Samoa.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(2, 'Andorra', 'Andorra.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(3, 'Anguilla', 'Anguilla.png', 'XCD', '$', 1, '2022-11-19 05:40:53'),
(4, 'Antarctica', 'Antarctica.png', 'GBP', ' £', 1, '2022-11-19 05:40:53'),
(5, 'Antigua and Barbuda', 'Antigua_and_Barbuda.', 'XCD', '$', 1, '2022-11-19 05:40:53'),
(6, 'Argentina', 'Argentina.png', 'ARS', '$', 1, '2022-11-19 05:40:53'),
(7, 'Australia', 'Australia.png', 'AUD', '$', 1, '2022-11-19 05:40:53'),
(8, 'Austria', 'Austria.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(9, 'Bahamas', 'Bahamas.png', 'BSD', '$', 1, '2022-11-19 05:40:53'),
(10, 'Barbados', 'Barbados.png', 'BBD', '$', 1, '2022-11-19 05:40:53'),
(11, 'Belgium', 'Belgium.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(12, 'Belize', 'Belize.png', 'BZD', '$', 1, '2022-11-19 05:40:53'),
(13, 'Bermuda', 'Bermuda.png', 'BMD', '$', 1, '2022-11-19 05:40:53'),
(14, 'British Indian Ocean Territory', 'British_Indian_Ocean', 'USD', '$', 1, '2022-11-19 05:40:53'),
(15, 'Brunei Darussalam', 'Brunei_Darussalam.pn', 'BND', '$', 1, '2022-11-19 05:40:53'),
(16, 'Canada', 'Canada.png', 'CAD', '$', 1, '2022-11-19 05:40:53'),
(17, 'Cape Verde', 'Cape_Verde.png', 'CVE', '$', 1, '2022-11-19 05:40:53'),
(18, 'Cayman Islands', 'Cayman_Islands.png', 'KYD', '$', 1, '2022-11-19 05:40:53'),
(19, 'Chile', 'Chile.png', 'CLP', '$', 1, '2022-11-19 05:40:53'),
(20, 'China', 'China.png', 'CNY', '¥', 1, '2022-11-19 05:40:53'),
(21, 'Christmas Island', 'Christmas_Island.png', 'AUD', '$', 1, '2022-11-19 05:40:53'),
(22, 'Cocos Islands', 'Cocos_Islands.png', 'AUD', '$', 1, '2022-11-19 05:40:53'),
(23, 'Colombia', 'Colombia.png', 'COP', '$', 1, '2022-11-19 05:40:53'),
(24, 'Cook Islands', 'Cook_Islands.png', 'NZD', '$', 1, '2022-11-19 05:40:53'),
(25, 'Cyprus', 'Cyprus.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(26, 'Dominica', 'Dominica.png', 'DOP', '$', 1, '2022-11-19 05:40:53'),
(27, 'East Timor', 'East_Timor.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(28, 'Ecuador', 'Ecuador.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(29, 'Egypt', 'Egypt.png', 'EGP', '£', 1, '2022-11-19 05:40:53'),
(30, 'El Salvador', 'El_Salvador.png', 'SVC', '$', 1, '2022-11-19 05:40:53'),
(31, 'Estonia', 'Estonia.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(32, 'Falkland Islands', 'Falkland_Islands.png', 'FKP', '£', 1, '2022-11-19 05:40:53'),
(33, 'Fiji', 'Fiji.png', 'FJD', '$', 1, '2022-11-19 05:40:53'),
(34, 'Finland', 'Finland.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(35, 'France', 'France.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(36, 'French Guiana', 'French_Guiana.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(37, 'French Southern Territories', 'French_Southern_Terr', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(38, 'Germany', 'Germany.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(39, 'Ghana', 'Ghana.png', 'GHS', '¢', 1, '2022-11-19 05:40:53'),
(40, 'Gibraltar', 'Gibraltar.png', 'GIP', '£', 1, '2022-11-19 05:40:53'),
(41, 'Greece', 'Greece.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(42, 'Grenada', 'Grenada.png', 'XCD', '$', 1, '2022-11-19 05:40:53'),
(43, 'Guadeloupe', 'Guadeloupe.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(44, 'Guam', 'Guam.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(45, 'Guyana', 'Guyana.png', 'GYD', '$', 1, '2022-11-19 05:40:53'),
(46, 'Heard and Mc Donald Islands', 'Heard_and_Mc_Donald_', 'AUD ', '$', 1, '2022-11-19 05:40:53'),
(47, 'Holy See', 'Holy_See.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(48, 'Hong Kong', 'Hong_Kong.png', 'HKD', '$', 1, '2022-11-19 05:40:53'),
(49, 'India', 'India.png', 'INR', 'Rs', 1, '2022-11-19 05:40:53'),
(50, 'Indonesia', 'Indonesia.png', 'IDR', 'Rp', 1, '2022-11-19 05:40:53'),
(51, 'Ireland', 'Ireland.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(52, 'Italy', 'Italy.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(53, 'Jamaica', 'Jamaica.png', 'JMD', '$', 1, '2022-11-19 05:40:53'),
(54, 'Japan', 'Japan.png', 'JPY', '¥', 1, '2022-11-19 05:40:53'),
(55, 'Kiribati', 'Kiribati.png', 'AUD', '$', 1, '2022-11-19 05:40:53'),
(56, 'Kuwait', 'Kuwait.png', 'KWD', 'KD', 1, '2022-11-19 05:40:53'),
(57, 'Latvia', 'Latvia.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(58, 'Lebanon', 'Lebanon.png', 'LBP', '£', 1, '2022-11-19 05:40:53'),
(59, 'Liberia', 'Liberia.png', 'LRD', '$', 1, '2022-11-19 05:40:53'),
(60, 'Lithuania', 'Lithuania.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(61, 'Luxembourg', 'Luxembourg.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(62, 'Macau', 'Macau.png', 'MOP', '$', 1, '2022-11-19 05:40:53'),
(63, 'Malta', 'Malta.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(64, 'Marshall Islands', 'Marshall_Islands.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(65, 'Martinique', 'Martinique.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(66, 'Mayotte', 'Mayotte.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(67, 'Mexico', 'Mexico.png', 'MXN', '$', 1, '2022-11-19 05:40:53'),
(68, 'Micronesia', 'Micronesia.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(69, 'Monaco', 'Monaco.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(70, 'Montserrat', 'Montserrat.png', 'XCD', '$', 1, '2022-11-19 05:40:53'),
(71, 'Namibia', 'Namibia.png', 'NAD', '$', 1, '2022-11-19 05:40:53'),
(72, 'Nauru', 'Nauru.png', 'AUD', '$', 1, '2022-11-19 05:40:53'),
(73, 'Nepal', 'Nepal.png', 'NPR', 'Rs', 1, '2022-11-19 05:40:53'),
(74, 'Netherlands', 'Netherlands.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(75, 'New Zealand', 'New_Zealand.png', 'NZD', '$', 1, '2022-11-19 05:40:53'),
(76, 'Nicaragua', 'Nicaragua.png', 'NIO', '$', 1, '2022-11-19 05:40:53'),
(77, 'Niue', 'Niue.png', 'NZD', '$', 1, '2022-11-19 05:40:53'),
(78, 'Norfolk Island', 'Norfolk_Island.png', 'AUD', '$', 1, '2022-11-19 05:40:53'),
(79, 'Northern Mariana Islands', 'Northern_Mariana_Isl', 'USD', '$', 1, '2022-11-19 05:40:53'),
(80, 'Pakistan', 'Pakistan.png', 'PKR', 'Rs', 1, '2022-11-19 05:40:53'),
(81, 'Palau', 'Palau.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(82, 'Pitcairn', 'Pitcairn.png', 'NZD', '$', 1, '2022-11-19 05:40:53'),
(83, 'Portugal', 'Portugal.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(84, 'Puerto Rico', 'Puerto_Rico.png', 'USD', '$', 1, '2022-11-19 05:40:53'),
(85, 'Reunion', 'Reunion.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(86, 'Saint Kitts and Nevis', 'Saint_Kitts_and_Nevi', 'XCD', '$', 1, '2022-11-19 05:40:53'),
(87, 'Saint Lucia', 'Saint_Lucia.png', 'XCD', '$', 1, '2022-11-19 05:40:53'),
(88, 'Saint Vincent and the Grenadines', 'Saint_Vincent_and_th', 'XCD', '$', 1, '2022-11-19 05:40:53'),
(89, 'Samoa', 'Samoa.png', 'WST', '$', 1, '2022-11-19 05:40:53'),
(90, 'San Marino', 'San_Marino.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(91, 'Singapore', 'Singapore.png', 'SGD', '$', 1, '2022-11-19 05:40:53'),
(92, 'Slovakia', 'Slovakia.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(93, 'Slovenia', 'Slovenia.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(94, 'Solomon Islands', 'Solomon_Islands.png', 'SBD', '$', 1, '2022-11-19 05:40:53'),
(95, 'Somalia', 'Somalia.png', 'SOS', 'S', 1, '2022-11-19 05:40:53'),
(96, 'South Africa', 'South_Africa.png', 'ZAR', 'R', 1, '2022-11-19 05:40:53'),
(97, 'South Georgia', 'South_Georgia.png', 'GBP', '£', 1, '2022-11-19 05:40:53'),
(98, 'Spain', 'Spain.png', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(99, 'Saint Helena', 'Saint_Helena.png', 'SHP', '£', 1, '2022-11-19 05:40:53'),
(100, 'Saint Pierre and Miquelon', 'Saint_Pierre_and_Miq', 'EURO', '€', 1, '2022-11-19 05:40:53'),
(101, 'Suriname', 'Suriname.png', 'SRD', '$', 1, '2022-11-19 05:40:53'),
(102, 'Syrian Arab Republic', 'Syrian_Arab_Republic', 'SYP', '£', 1, '2022-11-19 05:40:53'),
(103, 'Taiwan', 'Taiwan.png', 'TWD', '$', 1, '2022-11-19 05:40:53'),
(104, 'Tokelau', 'Tokelau.png', 'NZD', '$', 1, '2022-11-19 05:40:53'),
(105, 'Tonga', 'Tonga.png', 'TOP', '$', 1, '2022-11-19 05:40:53'),
(106, 'Trinidad and Tobago', 'Trinidad_and_Tobago.', 'TTD', '$', 1, '2022-11-19 05:40:53'),
(107, 'Turks and Caicos Islands', 'Turks_and_Caicos_Isl', 'USD', '$', 1, '2022-11-19 05:40:53'),
(108, 'Tuvalu', 'Tuvalu.png', 'AUD', '$', 1, '2022-11-19 05:40:53'),
(109, 'United Kingdom', 'United_Kingdom.png', 'GBP', '£', 2, '2022-11-19 05:40:53'),
(110, 'United States', 'United_States.png', 'USD', '$', 2, '2022-11-19 05:40:53'),
(111, 'Uruguay', 'Uruguay.png', 'UYU', '$', 1, '2022-11-19 05:40:53'),
(112, 'Zimbabwe', 'Zimbabwe.png', 'ZWD', '$', 1, '2022-11-19 05:40:53');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_companies_industry`
--

CREATE TABLE `typicms_companies_industry` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_companies_industry`
--

INSERT INTO `typicms_companies_industry` (`id`, `name`, `created_at`) VALUES
(1, 'Manufacturing', '2022-11-19 05:40:14'),
(2, 'Technology', '2022-11-19 05:40:14'),
(3, 'Trade', '2022-11-19 05:40:14'),
(4, 'Production', '2022-11-19 05:40:14'),
(5, 'Finance', '2022-11-19 05:40:14'),
(6, 'Industry', '2022-11-19 05:40:14'),
(7, 'Market research', '2022-11-19 05:40:14'),
(8, 'Mining', '2022-11-19 05:40:14'),
(9, 'Construction', '2022-11-19 05:40:14'),
(10, 'Retail', '2022-11-19 05:40:14'),
(11, 'Robotics', '2022-11-19 05:40:14'),
(12, 'Agriculture', '2022-11-19 05:40:14'),
(13, 'Conglomerate', '2022-11-19 05:40:14'),
(14, 'Transport', '2022-11-19 05:40:14'),
(15, 'Investment', '2022-11-19 05:40:14'),
(16, 'Computers and information technology', '2022-11-19 05:40:14'),
(17, 'Foodservice', '2022-11-19 05:40:14'),
(18, 'Food industry', '2022-11-19 05:40:14'),
(19, 'Economics', '2022-11-19 05:40:14'),
(20, 'Education', '2022-11-19 05:40:14'),
(21, 'Software', '2022-11-19 05:40:14'),
(22, 'Real Estate', '2022-11-19 05:40:14'),
(23, 'Goods', '2022-11-19 05:40:14'),
(24, 'Research', '2022-11-19 05:40:14'),
(25, 'Heavy industry', '2022-11-19 05:40:14'),
(26, 'Factory', '2022-11-19 05:40:14'),
(27, 'Health care', '2022-11-19 05:40:14'),
(28, 'Engineering', '2022-11-19 05:40:14'),
(29, 'Insurance', '2022-11-19 05:40:14'),
(30, 'Marketing', '2022-11-19 05:40:14'),
(31, 'Machine industry', '2022-11-19 05:40:14'),
(32, 'Telecommunications', '2022-11-19 05:40:14'),
(33, 'Holding company', '2022-11-19 05:40:14'),
(34, 'Tertiary sector of the economy', '2022-11-19 05:40:14'),
(35, 'Cryptocurrency', '2022-11-19 05:40:14'),
(36, 'Computer hardware', '2022-11-19 05:40:14'),
(37, 'Electronics', '2022-11-19 05:40:14'),
(38, 'Financial services', '2022-11-19 05:40:14'),
(39, 'Public administration', '2022-11-19 05:40:14'),
(40, 'Aerospace', '2022-11-19 05:40:14'),
(41, 'Lobbying', '2022-11-19 05:40:14'),
(42, 'Digital transformation', '2022-11-19 05:40:14'),
(43, 'Forestry', '2022-11-19 05:40:14'),
(44, 'Digital media', '2022-11-19 05:40:14'),
(45, 'Shipbuilding', '2022-11-19 05:40:14'),
(46, 'Publishing', '2022-11-19 05:40:14'),
(47, 'Entertainment', '2022-11-19 05:40:14'),
(48, 'Website', '2022-11-19 05:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_companies_timezone`
--

CREATE TABLE `typicms_companies_timezone` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `zone_value` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_companies_timezone`
--

INSERT INTO `typicms_companies_timezone` (`id`, `name`, `zone_value`, `created_at`) VALUES
(1, 'Burkina Faso,BF', 'Africa/Abidjan', '2022-12-30 02:06:15'),
(2, 'Ghana ,GH', 'Africa/Accra', '2022-12-30 02:06:15'),
(3, 'Ethiopia ,ET', 'Africa/Addis_Ababa', '2022-12-30 02:06:15'),
(4, 'Algeria,DZ', 'Africa/Algiers', '2022-12-30 02:06:15'),
(5, 'Eritrea ,ER', 'Africa/Asmera', '2022-12-30 02:06:15'),
(6, 'Mali ,ML', 'Africa/Bamako', '2022-12-30 02:06:15'),
(7, 'Central African Republic,CF', 'Africa/Bangui', '2022-12-30 02:06:15'),
(8, 'Gambia,GM', 'Africa/Banjul', '2022-12-30 02:06:15'),
(9, 'Bissau,GW', 'Africa/Bissau', '2022-12-30 02:06:15'),
(10, 'Malawi ,MW', 'Africa/Blantyre', '2022-12-30 02:06:15'),
(11, 'Congo ,CG', 'Africa/Brazzaville', '2022-12-30 02:06:15'),
(12, 'Burundi ,BI', 'Africa/Bujumbura', '2022-12-30 02:06:15'),
(13, 'Egypt,EG', 'Africa/Cairo', '2022-12-30 02:06:15'),
(14, 'Morocco,MA', 'Africa/Casablanca', '2022-12-30 02:06:15'),
(15, 'Spain ,ES', 'Africa/Ceuta', '2022-12-30 02:06:15'),
(16, 'Guinea ,GN', 'Africa/Conakry', '2022-12-30 02:06:15'),
(17, 'Senegal ,SN', 'Africa/Dakar', '2022-12-30 02:06:15'),
(18, 'Tanzania ,TZ', 'Africa/Dar_es_Salaam', '2022-12-30 02:06:15'),
(19, 'Djibouti ,DJ', 'Africa/Djibouti', '2022-12-30 02:06:15'),
(20, 'Cameroon ,CM', 'Africa/Douala', '2022-12-30 02:06:15'),
(21, 'Laayoune', 'Africa/El_Aaiun', '2022-12-30 02:06:15'),
(22, 'Sierra Leone,SL', 'Africa/Freetown', '2022-12-30 02:06:15'),
(23, 'Botswana ,BW', 'Africa/Gaborone', '2022-12-30 02:06:15'),
(24, 'Zimbabwe ,ZW', 'Africa/Harare', '2022-12-30 02:06:15'),
(25, 'South Africa,ZA', 'Africa/Johannesburg', '2022-12-30 02:06:15'),
(26, 'South Sudan,SS', 'Africa/Juba', '2022-12-30 02:06:15'),
(27, 'Uganda ,UG', 'Africa/Kampala', '2022-12-30 02:06:15'),
(28, 'Sudan,SD', 'Africa/Khartoum', '2022-12-30 02:06:15'),
(29, 'Rwanda,RW', 'Africa/Kigali', '2022-12-30 02:06:15'),
(30, 'Kinshasa', 'Africa/Kinshasa', '2022-12-30 02:06:15'),
(31, 'Nigeria ,NG', 'Africa/Lagos', '2022-12-30 02:06:15'),
(32, 'Gabon ,GA', 'Africa/Libreville', '2022-12-30 02:06:15'),
(33, 'Togo ,TG', 'Africa/Lome', '2022-12-30 02:06:15'),
(34, 'Angola ,AO', 'Africa/Luanda', '2022-12-30 02:06:15'),
(35, 'Lubumbashi', 'Africa/Lubumbashi', '2022-12-30 02:06:15'),
(36, 'Zambia,ZM', 'Africa/Lusaka', '2022-12-30 02:06:15'),
(37, 'Equatorial Guinea,GQ', 'Africa/Malabo', '2022-12-30 02:06:15'),
(38, 'Mozambique ,MZ', 'Africa/Maputo', '2022-12-30 02:06:15'),
(39, 'Lesotho,LS', 'Africa/Maseru', '2022-12-30 02:06:15'),
(40, 'Eswatini ', 'Africa/Mbabane', '2022-12-30 02:06:15'),
(41, 'Somalia ,SO', 'Africa/Mogadishu', '2022-12-30 02:06:15'),
(42, 'Liberia,LR', 'Africa/Monrovia', '2022-12-30 02:06:15'),
(43, 'Kenya ,KE', 'Africa/Nairobi', '2022-12-30 02:06:15'),
(44, 'Chad ,TD', 'Africa/Ndjamena', '2022-12-30 02:06:15'),
(45, 'Niger ,NE', 'Africa/Niamey', '2022-12-30 02:06:15'),
(46, 'Mauritania ,MR', 'Africa/Nouakchott', '2022-12-30 02:06:15'),
(47, 'Burkina Faso,BF', 'Africa/Ouagadougou', '2022-12-30 02:06:15'),
(48, 'Benin,BG', 'Africa/Porto-Novo', '2022-12-30 02:06:15'),
(49, 'São Tomé & Príncipe,ST', 'Africa/Sao_Tome', '2022-12-30 02:06:15'),
(50, 'Timbuktu', 'Africa/Timbuktu', '2022-12-30 02:06:15'),
(51, 'Libya ,LY', 'Africa/Tripoli', '2022-12-30 02:06:15'),
(52, 'Tunisia ,TN', 'Africa/Tunis', '2022-12-30 02:06:15'),
(53, 'Namibia ,NA', 'Africa/Windhoek', '2022-12-30 02:06:15'),
(54, 'Adak', 'America/Adak', '2022-12-30 02:06:15'),
(55, 'Anchorage', 'America/Anchorage', '2022-12-30 02:06:15'),
(56, 'Anguilla ', 'America/Anguilla', '2022-12-30 02:06:15'),
(57, 'Antigua and Barbuda,AG', 'America/Antigua', '2022-12-30 02:06:15'),
(58, 'Brazil,BR', 'America/Araguaina', '2022-12-30 02:06:15'),
(59, 'Buenos Aires ,AR', 'America/Argentina/Buenos_Aires', '2022-12-30 02:06:15'),
(60, 'Catamarca Province', 'America/Argentina/Catamarca', '2022-12-30 02:06:15'),
(61, 'Comodoro Rivadavia', 'America/Argentina/ComodRivadavia', '2022-12-30 02:06:15'),
(62, 'Cordoba', 'America/Argentina/Cordoba', '2022-12-30 02:06:15'),
(63, 'Jujuy', 'America/Argentina/Jujuy', '2022-12-30 02:06:15'),
(64, 'La Rioja Province', 'America/Argentina/La_Rioja', '2022-12-30 02:06:15'),
(65, 'Mendoza', 'America/Argentina/Mendoza', '2022-12-30 02:06:15'),
(66, 'Rio Gallegos', 'America/Argentina/Rio_Gallegos', '2022-12-30 02:06:15'),
(67, 'Salta', 'America/Argentina/Salta', '2022-12-30 02:06:15'),
(68, 'San Juan', 'America/Argentina/San_Juan', '2022-12-30 02:06:15'),
(69, 'San Luis ', 'America/Argentina/San_Luis', '2022-12-30 02:06:15'),
(70, 'Tucumán', 'America/Argentina/Tucuman', '2022-12-30 02:06:15'),
(71, 'Ushuaia', 'America/Argentina/Ushuaia', '2022-12-30 02:06:15'),
(72, 'Aruba ', 'America/Aruba', '2022-12-30 02:06:15'),
(73, 'Asunción', 'America/Asuncion', '2022-12-30 02:06:15'),
(74, 'Atikokan', 'America/Atikokan', '2022-12-30 02:06:15'),
(75, 'Atka', 'America/Atka', '2022-12-30 02:06:15'),
(76, 'Bahia', 'America/Bahia', '2022-12-30 02:06:15'),
(77, 'Bahía de Banderas', 'America/Bahia_Banderas', '2022-12-30 02:06:15'),
(78, 'Barbados,BB', 'America/Barbados', '2022-12-30 02:06:15'),
(79, 'Belém', 'America/Belem', '2022-12-30 02:06:15'),
(80, 'Belize ,BZ', 'America/Belize', '2022-12-30 02:06:15'),
(81, 'Blanc-Sablon', 'America/Blanc-Sablon', '2022-12-30 02:06:15'),
(82, ' Boa Vista', 'America/Boa_Vista', '2022-12-30 02:06:15'),
(83, 'Colombia,CO', 'America/Bogota', '2022-12-30 02:06:15'),
(84, 'Boise', 'America/Boise', '2022-12-30 02:06:15'),
(85, 'Buenos Aires', 'America/Buenos_Aires', '2022-12-30 02:06:15'),
(86, 'Cambridge Bay', 'America/Cambridge_Bay', '2022-12-30 02:06:15'),
(87, 'Campo Grande', 'America/Campo_Grande', '2022-12-30 02:06:15'),
(88, 'Mexico,MX', 'America/Cancun', '2022-12-30 02:06:15'),
(89, 'Venezuela,VE', 'America/Caracas', '2022-12-30 02:06:15'),
(90, 'Catamarca ', 'America/Catamarca', '2022-12-30 02:06:15'),
(91, 'French Guiana,GF', 'America/Cayenne', '2022-12-30 02:06:15'),
(92, 'Cayman Islands,KY', 'America/Cayman', '2022-12-30 02:06:15'),
(93, 'Chicago,US', 'America/Chicago', '2022-12-30 02:06:15'),
(94, 'Chihuahua', 'America/Chihuahua', '2022-12-30 02:06:15'),
(95, 'Coral Harbour', 'America/Coral_Harbour', '2022-12-30 02:06:15'),
(96, 'Cordoba', 'America/Cordoba', '2022-12-30 02:06:15'),
(97, 'Costa Rica,CR', 'America/Costa_Rica', '2022-12-30 02:06:15'),
(98, 'Creston', 'America/Creston', '2022-12-30 02:06:15'),
(99, 'Cuiabá ,BR', 'America/Cuiaba', '2022-12-30 02:06:15'),
(100, 'Curaçao ,CW', 'America/Curacao', '2022-12-30 02:06:15'),
(101, 'Danmarkshavn ,GL', 'America/Danmarkshavn', '2022-12-30 02:06:15'),
(102, 'Dawson ,CA', 'America/Dawson', '2022-12-30 02:06:15'),
(103, 'Dawson Creek,CA', 'America/Dawson_Creek', '2022-12-30 02:06:15'),
(104, 'Denver,US', 'America/Denver', '2022-12-30 02:06:15'),
(105, 'Detroit', 'America/Detroit', '2022-12-30 02:06:15'),
(106, 'Dominica ,DM', 'America/Dominica', '2022-12-30 02:06:15'),
(107, 'Edmonton,CA', 'America/Edmonton', '2022-12-30 02:06:15'),
(108, 'Eirunepé ', 'America/Eirunepe', '2022-12-30 02:06:15'),
(109, 'El Salvador,SV', 'America/El_Salvador', '2022-12-30 02:06:15'),
(110, 'Ensenada', 'America/Ensenada', '2022-12-30 02:06:15'),
(111, 'Fortaleza ,BR', 'America/Fortaleza', '2022-12-30 02:06:15'),
(112, 'Fort Nelson,CA', 'America/Fort_Nelson', '2022-12-30 02:06:15'),
(113, ' Fort Wayne', 'America/Fort_Wayne', '2022-12-30 02:06:15'),
(114, 'Glace Bay,CA', 'America/Glace_Bay', '2022-12-30 02:06:15'),
(115, 'Godthab ,GL', 'America/Godthab', '2022-12-30 02:06:15'),
(116, 'Goose Bay', 'America/Goose_Bay', '2022-12-30 02:06:15'),
(117, 'Grand Turk', 'America/Grand_Turk', '2022-12-30 02:06:15'),
(118, 'Grenada ,GD', 'America/Grenada', '2022-12-30 02:06:15'),
(119, 'Guadeloupe ,GP', 'America/Guadeloupe', '2022-12-30 02:06:15'),
(120, 'Guatemala ,GT', 'America/Guatemala', '2022-12-30 02:06:15'),
(121, 'Ecuador,EC', 'America/Guayaquil', '2022-12-30 02:06:15'),
(122, 'Guyana ,GY', 'America/Guyana', '2022-12-30 02:06:15'),
(123, 'Halifax,CA', 'America/Halifax', '2022-12-30 02:06:15'),
(124, 'Cuba,CU', 'America/Havana', '2022-12-30 02:06:15'),
(125, 'Hermosillo,MX', 'America/Hermosillo', '2022-12-30 02:06:15'),
(126, 'Indianapolis', 'America/Indiana/Indianapolis', '2022-12-30 02:06:15'),
(127, 'Knox', 'America/Indiana/Knox', '2022-12-30 02:06:15'),
(128, 'Marengo,USA', 'America/Indiana/Marengo', '2022-12-30 02:06:15'),
(129, 'Petersburg,USA', 'America/Indiana/Petersburg', '2022-12-30 02:06:15'),
(130, 'Tell ,USA', 'America/Indiana/Tell_City', '2022-12-30 02:06:15'),
(131, 'Vevay,USA', 'America/Indiana/Vevay', '2022-12-30 02:06:15'),
(132, 'Vincennes,USA', 'America/Indiana/Vincennes', '2022-12-30 02:06:15'),
(133, 'Winamac,USA', 'America/Indiana/Winamac', '2022-12-30 02:06:15'),
(134, 'Indianapolis,USA', 'America/Indianapolis', '2022-12-30 02:06:15'),
(135, 'Inuvik,CA', 'America/Inuvik', '2022-12-30 02:06:15'),
(136, 'Iqaluit,CA', 'America/Iqaluit', '2022-12-30 02:06:15'),
(137, 'Jamaica ,JM', 'America/Jamaica', '2022-12-30 02:06:15'),
(138, 'Jujuy', 'America/Jujuy', '2022-12-30 02:06:15'),
(139, 'Juneau,USA', 'America/Juneau', '2022-12-30 02:06:15'),
(140, 'Louisville,USA', 'America/Kentucky/Louisville', '2022-12-30 02:06:15'),
(141, 'Monticello', 'America/Kentucky/Monticello', '2022-12-30 02:06:15'),
(142, 'Knox,USA', 'America/Knox_IN', '2022-12-30 02:06:15'),
(143, 'Kralendijk', 'America/Kralendijk', '2022-12-30 02:06:15'),
(144, 'Bolivia,BQ', 'America/La_Paz', '2022-12-30 02:06:15'),
(145, 'Lima,PE', 'America/Lima', '2022-12-30 02:06:15'),
(146, 'Los Angeles,USA', 'America/Los_Angeles', '2022-12-30 02:06:15'),
(147, 'Louisville,USA', 'America/Louisville', '2022-12-30 02:06:15'),
(148, ' Sint Maarten,SX', 'America/Lower_Princes', '2022-12-30 02:06:15'),
(149, 'Maceió,BR', 'America/Maceio', '2022-12-30 02:06:15'),
(150, 'Nicaragua,NI', 'America/Managua', '2022-12-30 02:06:15'),
(151, 'Manaus,BR', 'America/Manaus', '2022-12-30 02:06:15'),
(152, 'Marigot ', 'America/Marigot', '2022-12-30 02:06:15'),
(153, 'Martinique ,MQ', 'America/Martinique', '2022-12-30 02:06:15'),
(154, 'Matamoros', 'America/Matamoros', '2022-12-30 02:06:15'),
(155, 'Mazatlán,MX', 'America/Mazatlan', '2022-12-30 02:06:15'),
(156, 'Mendoza ', 'America/Mendoza', '2022-12-30 02:06:15'),
(157, 'Menominee,USA', 'America/Menominee', '2022-12-30 02:06:15'),
(158, 'Mérida', 'America/Merida', '2022-12-30 02:06:15'),
(159, 'Metlakatla,USA', 'America/Metlakatla', '2022-12-30 02:06:15'),
(160, 'Mexico City', 'America/Mexico_City', '2022-12-30 02:06:15'),
(161, 'Miquelon ,PM', 'America/Miquelon', '2022-12-30 02:06:15'),
(162, 'Moncton,CA', 'America/Moncton', '2022-12-30 02:06:15'),
(163, 'Monterrey ', 'America/Monterrey', '2022-12-30 02:06:15'),
(164, 'Uruguay,UY', 'America/Montevideo', '2022-12-30 02:06:15'),
(165, 'Montreal,CA', 'America/Montreal', '2022-12-30 02:06:15'),
(166, 'Montserrat ,MS', 'America/Montserrat', '2022-12-30 02:06:15'),
(167, 'Bahamas,BS', 'America/Nassau', '2022-12-30 02:06:15'),
(168, 'New York,USA', 'America/New_York', '2022-12-30 02:06:15'),
(169, 'Nipigon,CA', 'America/Nipigon', '2022-12-30 02:06:15'),
(170, 'Nome,USA', 'America/Nome', '2022-12-30 02:06:15'),
(171, 'Noronha ,BR', 'America/Noronha', '2022-12-30 02:06:15'),
(172, 'Beulah,USA', 'America/North_Dakota/Beulah', '2022-12-30 02:06:15'),
(173, 'Center,USA', 'America/North_Dakota/Center', '2022-12-30 02:06:15'),
(174, 'New Salem,USA', 'America/North_Dakota/New_Salem', '2022-12-30 02:06:15'),
(175, 'Nuuk,GL', 'America/Nuuk', '2022-12-30 02:06:15'),
(176, 'Ojinaga', 'America/Ojinaga', '2022-12-30 02:06:15'),
(177, 'Panama ,KY', 'America/Panama', '2022-12-30 02:06:15'),
(178, 'Pangnirtung', 'America/Pangnirtung', '2022-12-30 02:06:15'),
(179, 'Suriname,SR', 'America/Paramaribo', '2022-12-30 02:06:15'),
(180, 'Phoenix,USA', 'America/Phoenix', '2022-12-30 02:06:15'),
(181, 'Haiti ,HT', 'America/Port-au-Prince', '2022-12-30 02:06:15'),
(182, 'Puerto Rico,PR', 'America/Porto_Acre', '2022-12-30 02:06:15'),
(183, 'Porto Velho,BR', 'America/Porto_Velho', '2022-12-30 02:06:15'),
(184, 'Trinidad and Tobago,TT', 'America/Port_of_Spain', '2022-12-30 02:06:15'),
(185, 'Puerto Rico,PR', 'America/Puerto_Rico', '2022-12-30 02:06:15'),
(186, 'Punta Arenas', 'America/Punta_Arenas', '2022-12-30 02:06:15'),
(187, 'Rainy Rive,CA', 'America/Rainy_River', '2022-12-30 02:06:15'),
(188, 'Rankin Inlet,CA', 'America/Rankin_Inlet', '2022-12-30 02:06:15'),
(189, 'Recife,BR', 'America/Recife', '2022-12-30 02:06:15'),
(190, 'Regina,CA', 'America/Regina', '2022-12-30 02:06:15'),
(191, 'Resolute,CA', 'America/Resolute', '2022-12-30 02:06:15'),
(192, 'Rio Branco,BR', 'America/Rio_Branco', '2022-12-30 02:06:15'),
(193, 'Rosario', 'America/Rosario', '2022-12-30 02:06:15'),
(194, 'Santarém,BR', 'America/Santarem', '2022-12-30 02:06:15'),
(195, ' Santa Isabel', 'America/Santa_Isabel', '2022-12-30 02:06:15'),
(196, ' Santa Isabel', 'America/Santiago', '2022-12-30 02:06:15'),
(197, 'Dominican Republic,DO', 'America/Santo_Domingo', '2022-12-30 02:06:15'),
(198, 'São Paulo', 'America/Sao_Paulo', '2022-12-30 02:06:15'),
(199, 'Ittoqqortoormiit,GR', 'America/Scoresbysund', '2022-12-30 02:06:15'),
(200, 'Shiprock,USA', 'America/Shiprock', '2022-12-30 02:06:15'),
(201, 'Sitka,USA', 'America/Sitka', '2022-12-30 02:06:15'),
(202, 'St. Barts,AST', 'America/St_Barthelemy', '2022-12-30 02:06:15'),
(203, ' St. John\'s,CA', 'America/St_Johns', '2022-12-30 02:06:15'),
(204, 'Saint Kitts and Nevis', 'America/St_Kitts', '2022-12-30 02:06:15'),
(205, 'Saint Lucia', 'America/St_Lucia', '2022-12-30 02:06:15'),
(206, 'Saint Thomas', 'America/St_Thomas', '2022-12-30 02:06:15'),
(207, 'St. Vincent & Grenadines,VC', 'America/St_Vincent', '2022-12-30 02:06:15'),
(208, 'Swift Current,CA', 'America/Swift_Current', '2022-12-30 02:06:15'),
(209, 'Honduras,HN', 'America/Tegucigalpa', '2022-12-30 02:06:15'),
(210, 'Thule ,GL', 'America/Thule', '2022-12-30 02:06:15'),
(211, 'Thunder Bay,CA', 'America/Thunder_Bay', '2022-12-30 02:06:15'),
(212, 'Tijuana,MX', 'America/Tijuana', '2022-12-30 02:06:15'),
(213, 'Toronto,CA', 'America/Toronto', '2022-12-30 02:06:15'),
(214, 'Tortola ', 'America/Tortola', '2022-12-30 02:06:15'),
(215, 'Vancouver,CA', 'America/Vancouver', '2022-12-30 02:06:15'),
(216, 'Virgin,USA', 'America/Virgin', '2022-12-30 02:06:15'),
(217, 'Whitehorse,CA', 'America/Whitehorse', '2022-12-30 02:06:15'),
(218, 'Winnipeg,CA', 'America/Winnipeg', '2022-12-30 02:06:15'),
(219, 'Yakutat,USA', 'America/Yakutat', '2022-12-30 02:06:15'),
(220, 'Yellowknife,CA', 'America/Yellowknife', '2022-12-30 02:06:15'),
(221, 'Casey,AQ', 'Antarctica/Casey', '2022-12-30 02:06:15'),
(222, 'Davis,AQ', 'Antarctica/Davis', '2022-12-30 02:06:15'),
(223, 'DumontDUrville,AQ', 'Antarctica/DumontDUrville', '2022-12-30 02:06:15'),
(224, 'Macquarie ', 'Antarctica/Macquarie', '2022-12-30 02:06:15'),
(225, 'Mawson ,AQ', 'Antarctica/Mawson', '2022-12-30 02:06:15'),
(226, 'McMurdo,NZST', 'Antarctica/McMurdo', '2022-12-30 02:06:15'),
(227, 'Palmer ,AQ', 'Antarctica/Palmer', '2022-12-30 02:06:15'),
(228, 'Rothera ,AQ', 'Antarctica/Rothera', '2022-12-30 02:06:15'),
(229, 'South Pole,NZST', 'Antarctica/South_Pole', '2022-12-30 02:06:15'),
(230, 'Syowa ,AQ', 'Antarctica/Syowa', '2022-12-30 02:06:15'),
(231, 'Troll ', 'Antarctica/Troll', '2022-12-30 02:06:15'),
(232, 'Lake Vostok', 'Antarctica/Vostok', '2022-12-30 02:06:15'),
(233, 'Svalbard & Jan Mayen ,SJ', 'Arctic/Longyearbyen', '2022-12-30 02:06:15'),
(234, 'Yemen', 'Asia/Aden', '2022-12-30 02:06:15'),
(235, 'Almaty ,KZ', 'Asia/Almaty', '2022-12-30 02:06:15'),
(236, 'Amman,JO', 'Asia/Amman', '2022-12-30 02:06:15'),
(237, 'Anadyr,RU', 'Asia/Anadyr', '2022-12-30 02:06:15'),
(238, 'Aktau,KZ', 'Asia/Aqtau', '2022-12-30 02:06:15'),
(239, 'Aqtobe,KZ', 'Asia/Aqtobe', '2022-12-30 02:06:15'),
(240, 'Ashgabat,TM', 'Asia/Ashgabat', '2022-12-30 02:06:15'),
(241, 'Ashkhabad ,TM', 'Asia/Ashkhabad', '2022-12-30 02:06:15'),
(242, 'Atyrau,KZ', 'Asia/Atyrau', '2022-12-30 02:06:15'),
(243, 'Iraq ,IQ', 'Asia/Baghdad', '2022-12-30 02:06:15'),
(244, 'Bahrain ,BH', 'Asia/Bahrain', '2022-12-30 02:06:15'),
(245, 'Azerbaijan ,AZ', 'Asia/Baku', '2022-12-30 02:06:15'),
(246, 'Thailand ,TH', 'Asia/Bangkok', '2022-12-30 02:06:15'),
(247, 'Barnaul,RU', 'Asia/Barnaul', '2022-12-30 02:06:15'),
(248, 'Lebanon ,LB', 'Asia/Beirut', '2022-12-30 02:06:15'),
(249, 'Kyrgyzstan ,KG', 'Asia/Bishkek', '2022-12-30 02:06:15'),
(250, 'Brunei,BN', 'Asia/Brunei', '2022-12-30 02:06:15'),
(251, 'India ,IN', 'Asia/Calcutta', '2022-12-30 02:06:15'),
(252, 'Chita,RU', 'Asia/Chita', '2022-12-30 02:06:15'),
(253, 'Choibalsan ,MN', 'Asia/Choibalsan', '2022-12-30 02:06:15'),
(254, 'Chongqing,CN', 'Asia/Chongqing', '2022-12-30 02:06:15'),
(255, 'Chungking', 'Asia/Chungking', '2022-12-30 02:06:15'),
(256, 'Sri Lanka,LK', 'Asia/Colombo', '2022-12-30 02:06:15'),
(257, 'Dacca', 'Asia/Dacca', '2022-12-30 02:06:15'),
(258, 'Syria ,SY', 'Asia/Damascus', '2022-12-30 02:06:15'),
(259, 'Dhaka,BD', 'Asia/Dhaka', '2022-12-30 02:06:15'),
(260, 'Timor Leste,TL', 'Asia/Dili', '2022-12-30 02:06:15'),
(261, 'Dubai ,UAE', 'Asia/Dubai', '2022-12-30 02:06:15'),
(262, 'Tajikistan,TJ', 'Asia/Dushanbe', '2022-12-30 02:06:15'),
(263, 'Famagusta', 'Asia/Famagusta', '2022-12-30 02:06:15'),
(264, 'Palestine ,PS', 'Asia/Gaza', '2022-12-30 02:06:15'),
(265, 'Harbin,CN', 'Asia/Harbin', '2022-12-30 02:06:15'),
(266, 'Hebron ', 'Asia/Hebron', '2022-12-30 02:06:15'),
(267, 'Hong Kong,HK', 'Asia/Hong_Kong', '2022-12-30 02:06:15'),
(268, 'Hovd,MN', 'Asia/Hovd', '2022-12-30 02:06:15'),
(269, 'Vietnam ,VN', 'Asia/Ho_Chi_Minh', '2022-12-30 02:06:15'),
(270, 'Irkutsk,RU', 'Asia/Irkutsk', '2022-12-30 02:06:15'),
(271, 'Turkey ,TR', 'Asia/Istanbul', '2022-12-30 02:06:15'),
(272, 'Jakarta,ID', 'Asia/Jakarta', '2022-12-30 02:06:15'),
(273, 'Jayapura,ID', 'Asia/Jayapura', '2022-12-30 02:06:15'),
(274, 'Jerusalem,IL', 'Asia/Jerusalem', '2022-12-30 02:06:15'),
(275, 'Afghanistan ,AF', 'Asia/Kabul', '2022-12-30 02:06:15'),
(276, 'Kamchatka,RU', 'Asia/Kamchatka', '2022-12-30 02:06:15'),
(277, 'Pakistan ,PK', 'Asia/Karachi', '2022-12-30 02:06:15'),
(278, 'Kashgar,CN', 'Asia/Kashgar', '2022-12-30 02:06:15'),
(279, 'Nepal ,NP', 'Asia/Kathmandu', '2022-12-30 02:06:15'),
(280, 'Katmandu ', 'Asia/Katmandu', '2022-12-30 02:06:15'),
(281, 'Khandyga ', 'Asia/Khandyga', '2022-12-30 02:06:15'),
(282, 'Kolkata,IN', 'Asia/Kolkata', '2022-12-30 02:06:15'),
(283, 'Krasnoyarsk,RU', 'Asia/Krasnoyarsk', '2022-12-30 02:06:15'),
(284, ' Kuala Lumpu ,MY', 'Asia/Kuala_Lumpur', '2022-12-30 02:06:15'),
(285, 'Kuching,MY', 'Asia/Kuching', '2022-12-30 02:06:15'),
(286, 'Kuwait ,KW', 'Asia/Kuwait', '2022-12-30 02:06:15'),
(287, 'Macao', 'Asia/Macao', '2022-12-30 02:06:15'),
(288, 'Macau ,MO', 'Asia/Macau', '2022-12-30 02:06:15'),
(289, 'Magadan,RU', 'Asia/Magadan', '2022-12-30 02:06:15'),
(290, 'Makassar,ID', 'Asia/Makassar', '2022-12-30 02:06:15'),
(291, 'Philippines,PH', 'Asia/Manila', '2022-12-30 02:06:15'),
(292, 'Muscat,OM', 'Asia/Muscat', '2022-12-30 02:06:15'),
(293, 'Cyprus ,CY', 'Asia/Nicosia', '2022-12-30 02:06:15'),
(294, 'Novokuznetsk ,RU', 'Asia/Novokuznetsk', '2022-12-30 02:06:15'),
(295, 'Novosibirsk,RU', 'Asia/Novosibirsk', '2022-12-30 02:06:15'),
(296, 'Omsk,RU', 'Asia/Omsk', '2022-12-30 02:06:15'),
(297, 'Oral ', 'Asia/Oral', '2022-12-30 02:06:15'),
(298, 'Phnom Penh,KH', 'Asia/Phnom_Penh', '2022-12-30 02:06:15'),
(299, 'Pontianak ,ID', 'Asia/Pontianak', '2022-12-30 02:06:15'),
(300, 'North Korea,KP', 'Asia/Pyongyang', '2022-12-30 02:06:15'),
(301, 'Qatar ,QA', 'Asia/Qatar', '2022-12-30 02:06:15'),
(302, 'Qostanay ', 'Asia/Qostanay', '2022-12-30 02:06:15'),
(303, 'Qyzylorda ', 'Asia/Qyzylorda', '2022-12-30 02:06:15'),
(304, 'Rangoon ', 'Asia/Rangoon', '2022-12-30 02:06:15'),
(305, 'Saudi Arabia,SA', 'Asia/Riyadh', '2022-12-30 02:06:15'),
(306, 'Vietnam ,VN', 'Asia/Saigon', '2022-12-30 02:06:15'),
(307, 'Sakhalin ', 'Asia/Sakhalin', '2022-12-30 02:06:15'),
(308, 'Uzbekistan ', 'Asia/Samarkand', '2022-12-30 02:06:15'),
(309, 'South Korea,KR', 'Asia/Seoul', '2022-12-30 02:06:15'),
(310, 'Shanghai,CN', 'Asia/Shanghai', '2022-12-30 02:06:15'),
(311, 'Singapore ,SG', 'Asia/Singapore', '2022-12-30 02:06:15'),
(312, 'Srednekolymsk,RU', 'Asia/Srednekolymsk', '2022-12-30 02:06:15'),
(313, 'Taiwan ,TW', 'Asia/Taipei', '2022-12-30 02:06:15'),
(314, 'Uzbekistan ,UZ', 'Asia/Tashkent', '2022-12-30 02:06:15'),
(315, 'Georgia ,GE', 'Asia/Tbilisi', '2022-12-30 02:06:15'),
(316, 'Iran ,IR', 'Asia/Tehran', '2022-12-30 02:06:15'),
(317, 'Tel Aviv Yafo', 'Asia/Tel_Aviv', '2022-12-30 02:06:15'),
(318, 'Thimbu ', 'Asia/Thimbu', '2022-12-30 02:06:15'),
(319, 'Bhutan ,BT', 'Asia/Thimphu', '2022-12-30 02:06:15'),
(320, 'Japan ,JP', 'Asia/Tokyo', '2022-12-30 02:06:15'),
(321, 'Tomsk,RU', 'Asia/Tomsk', '2022-12-30 02:06:15'),
(322, 'Makassar,ID', 'Asia/Ujung_Pandang', '2022-12-30 02:06:15'),
(323, 'Ulaanbaatar,MN', 'Asia/Ulaanbaatar', '2022-12-30 02:06:15'),
(324, 'Ürümqi,CN', 'Asia/Urumqi', '2022-12-30 02:06:15'),
(325, 'Sakha Republic,RU', 'Asia/Ust-Nera', '2022-12-30 02:06:15'),
(326, 'Laos,LA', 'Asia/Vientiane', '2022-12-30 02:06:15'),
(327, 'Vladivostok ,RU', 'Asia/Vladivostok', '2022-12-30 02:06:15'),
(328, 'Yakutsk,RU', 'Asia/Yakutsk', '2022-12-30 02:06:15'),
(329, 'Yangon', 'Asia/Yangon', '2022-12-30 02:06:15'),
(330, 'Yekaterinburg,RU', 'Asia/Yekaterinburg', '2022-12-30 02:06:15'),
(331, 'Armenia ,AM', 'Asia/Yerevan', '2022-12-30 02:06:15'),
(332, 'Azores,PT', 'Atlantic/Azores', '2022-12-30 02:06:15'),
(333, 'Bermuda,BM', 'Atlantic/Bermuda', '2022-12-30 02:06:15'),
(334, 'Canary,ES', 'Atlantic/Canary', '2022-12-30 02:06:15'),
(335, 'Cape Verde,CV', 'Atlantic/Cape_Verde', '2022-12-30 02:06:15'),
(336, 'Faroe Islands,FO', 'Atlantic/Faeroe', '2022-12-30 02:06:15'),
(337, 'Faroe', 'Atlantic/Faroe', '2022-12-30 02:06:15'),
(338, 'Jan Mayen', 'Atlantic/Jan_Mayen', '2022-12-30 02:06:15'),
(339, 'Madeira', 'Atlantic/Madeira', '2022-12-30 02:06:15'),
(340, 'Iceland ,IS', 'Atlantic/Reykjavik', '2022-12-30 02:06:15'),
(341, 'South Georgia And South Sandwich Islands,GS', 'Atlantic/South_Georgia', '2022-12-30 02:06:15'),
(342, 'Falkland Islands,FK', 'Atlantic/Stanley', '2022-12-30 02:06:15'),
(343, 'St. Helena,SH', 'Atlantic/St_Helena', '2022-12-30 02:06:15'),
(344, 'Australian Capital Territory,AU', 'Australia/ACT', '2022-12-30 02:06:15'),
(345, 'Adelaide,AU', 'Australia/Adelaide', '2022-12-30 02:06:15'),
(346, 'Brisbane,AU', 'Australia/Brisbane', '2022-12-30 02:06:15'),
(347, ' Broken Hill NSW,AU', 'Australia/Broken_Hill', '2022-12-30 02:06:15'),
(348, 'Canberra ,AU', 'Australia/Canberra', '2022-12-30 02:06:15'),
(349, 'Currie TAS,AU', 'Australia/Currie', '2022-12-30 02:06:15'),
(350, 'Darwin,AU', 'Australia/Darwin', '2022-12-30 02:06:15'),
(351, 'Eucla WA,AU', 'Australia/Eucla', '2022-12-30 02:06:15'),
(352, 'Hobart,AU', 'Australia/Hobart', '2022-12-30 02:06:15'),
(353, 'LHI,AU', 'Australia/LHI', '2022-12-30 02:06:15'),
(354, 'Lindeman Island,AU', 'Australia/Lindeman', '2022-12-30 02:06:15'),
(355, 'Lord Howe,AU', 'Australia/Lord_Howe', '2022-12-30 02:06:15'),
(356, 'Melbourne ,AU', 'Australia/Melbourne', '2022-12-30 02:06:15'),
(357, 'Australia/North ,AU', 'Australia/North', '2022-12-30 02:06:15'),
(358, 'Victoria and Tasmania,AU', 'Australia/NSW', '2022-12-30 02:06:15'),
(359, 'Perth WA,AU', 'Australia/Perth', '2022-12-30 02:06:15'),
(360, 'Queensland,AU', 'Australia/Queensland', '2022-12-30 02:06:15'),
(361, 'South Australia,AU', 'Australia/South', '2022-12-30 02:06:15'),
(362, 'Sydney NSW,AU', 'Australia/Sydney', '2022-12-30 02:06:15'),
(363, 'Tasmania,AU', 'Australia/Tasmania', '2022-12-30 02:06:15'),
(364, 'Victoria,AU', 'Australia/Victoria', '2022-12-30 02:06:15'),
(365, 'Western Australia,AWST', 'Australia/West', '2022-12-30 02:06:15'),
(366, 'Yancowinna ,AU', 'Australia/Yancowinna', '2022-12-30 02:06:15'),
(367, 'Acre,BR', 'Brazil/Acre', '2022-12-30 02:06:15'),
(368, 'De Noronha,BR', 'Brazil/DeNoronha', '2022-12-30 02:06:15'),
(369, 'Brazil East,BR', 'Brazil/East', '2022-12-30 02:06:15'),
(370, 'Brazil West,BR', 'Brazil/West', '2022-12-30 02:06:15'),
(371, 'Atlantic ,CA', 'Canada/Atlantic', '2022-12-30 02:06:15'),
(372, 'Canada Central ,CA', 'Canada/Central', '2022-12-30 02:06:15'),
(373, 'Canada Eastern,CA', 'Canada/Eastern', '2022-12-30 02:06:15'),
(374, 'Canada Mountain,CA', 'Canada/Mountain', '2022-12-30 02:06:15'),
(375, 'Canada Newfoundland,CA', 'Canada/Newfoundland', '2022-12-30 02:06:15'),
(376, 'Canada Pacific,CA', 'Canada/Pacific', '2022-12-30 02:06:15'),
(377, 'Canada Saskatchewan,CA', 'Canada/Saskatchewan', '2022-12-30 02:06:15'),
(378, 'Canada Yukon,CA', 'Canada/Yukon', '2022-12-30 02:06:15'),
(379, 'Central European Time,CET', 'CET', '2022-12-30 02:06:15'),
(380, 'Magallanes and Chilean Antarctica', 'Chile/Continental', '2022-12-30 02:06:15'),
(381, 'Easter Island', 'Chile/EasterIsland', '2022-12-30 02:06:15'),
(382, 'US Central Time', 'CST6CDT', '2022-12-30 02:06:15'),
(383, 'Cuba,CU', 'Cuba', '2022-12-30 02:06:15'),
(384, 'Eastern European Time,EET', 'EET', '2022-12-30 02:06:15'),
(385, 'Egypt,EG', 'Egypt', '2022-12-30 02:06:15'),
(386, 'Eire', 'Eire', '2022-12-30 02:06:15'),
(387, 'Eastern Time,ET', 'EST', '2022-12-30 02:06:15'),
(388, 'US Eastern Time', 'EST5EDT', '2022-12-30 02:06:15'),
(389, 'Etc/GMT', 'Etc/GMT', '2022-12-30 02:06:15'),
(390, 'Etc/GMT+0', 'Etc/GMT+0', '2022-12-30 02:06:15'),
(391, 'Etc/GMT+1', 'Etc/GMT+1', '2022-12-30 02:06:15'),
(392, 'Etc/GMT+10', 'Etc/GMT+10', '2022-12-30 02:06:15'),
(393, 'Etc/GMT+11', 'Etc/GMT+11', '2022-12-30 02:06:15'),
(394, 'Etc/GMT+12', 'Etc/GMT+12', '2022-12-30 02:06:15'),
(395, 'Etc/GMT+2', 'Etc/GMT+2', '2022-12-30 02:06:15'),
(396, 'Etc/GMT+3', 'Etc/GMT+3', '2022-12-30 02:06:15'),
(397, 'Etc/GMT+4', 'Etc/GMT+4', '2022-12-30 02:06:15'),
(398, 'Etc/GMT+5', 'Etc/GMT+5', '2022-12-30 02:06:15'),
(399, 'Etc/GMT+6', 'Etc/GMT+6', '2022-12-30 02:06:15'),
(400, 'Etc/GMT+7', 'Etc/GMT+7', '2022-12-30 02:06:15'),
(401, 'Etc/GMT+8', 'Etc/GMT+8', '2022-12-30 02:06:15'),
(402, 'Etc/GMT+9', 'Etc/GMT+9', '2022-12-30 02:06:15'),
(403, 'Etc/GMT-0', 'Etc/GMT-0', '2022-12-30 02:06:15'),
(404, 'Etc/GMT-1', 'Etc/GMT-1', '2022-12-30 02:06:15'),
(405, 'Etc/GMT-10', 'Etc/GMT-10', '2022-12-30 02:06:15'),
(406, 'Etc/GMT-11', 'Etc/GMT-11', '2022-12-30 02:06:15'),
(407, 'Etc/GMT-12', 'Etc/GMT-12', '2022-12-30 02:06:15'),
(408, 'Etc/GMT-13', 'Etc/GMT-13', '2022-12-30 02:06:15'),
(409, 'Etc/GMT-14', 'Etc/GMT-14', '2022-12-30 02:06:15'),
(410, 'Etc/GMT-2', 'Etc/GMT-2', '2022-12-30 02:06:15'),
(411, 'Etc/GMT-3', 'Etc/GMT-3', '2022-12-30 02:06:15'),
(412, 'Etc/GMT-4', 'Etc/GMT-4', '2022-12-30 02:06:15'),
(413, 'Etc/GMT-5', 'Etc/GMT-5', '2022-12-30 02:06:15'),
(414, 'Etc/GMT-6', 'Etc/GMT-6', '2022-12-30 02:06:15'),
(415, 'Etc/GMT-7', 'Etc/GMT-7', '2022-12-30 02:06:15'),
(416, 'Etc/GMT-8', 'Etc/GMT-8', '2022-12-30 02:06:15'),
(417, 'Etc/GMT-9', 'Etc/GMT-9', '2022-12-30 02:06:15'),
(418, 'Etc/GMT0', 'Etc/GMT0', '2022-12-30 02:06:15'),
(419, 'Etc/Greenwich', 'Etc/Greenwich', '2022-12-30 02:06:15'),
(420, 'Etc/UCT', 'Etc/UCT', '2022-12-30 02:06:15'),
(421, 'Etc/Universal', 'Etc/Universal', '2022-12-30 02:06:15'),
(422, 'Etc/UTC', 'Etc/UTC', '2022-12-30 02:06:15'),
(423, 'Etc/Zulu', 'Etc/Zulu', '2022-12-30 02:06:15'),
(424, 'Netherlands ,NL', 'Europe/Amsterdam', '2022-12-30 02:06:15'),
(425, 'Andorra,AD', 'Europe/Andorra', '2022-12-30 02:06:15'),
(426, 'Russian Federation,RU', 'Europe/Astrakhan', '2022-12-30 02:06:15'),
(427, 'Greece ,GR', 'Europe/Athens', '2022-12-30 02:06:15'),
(428, 'Belfast,UK', 'Europe/Belfast', '2022-12-30 02:06:15'),
(429, 'Serbia,RS', 'Europe/Belgrade', '2022-12-30 02:06:15'),
(430, 'Berlin ,DE', 'Europe/Berlin', '2022-12-30 02:06:15'),
(431, 'Bratislava', 'Europe/Bratislava', '2022-12-30 02:06:15'),
(432, 'Belgium,BE', 'Europe/Brussels', '2022-12-30 02:06:15'),
(433, 'Romania ,RO', 'Europe/Bucharest', '2022-12-30 02:06:15'),
(434, 'Hungary ,HU', 'Europe/Budapest', '2022-12-30 02:06:15'),
(435, 'Busingen ,DE', 'Europe/Busingen', '2022-12-30 02:06:15'),
(436, 'Moldova ,MD', 'Europe/Chisinau', '2022-12-30 02:06:15'),
(437, 'Denmark ,DK', 'Europe/Copenhagen', '2022-12-30 02:06:15'),
(438, 'Ireland,IE', 'Europe/Dublin', '2022-12-30 02:06:15'),
(439, 'Gibraltar,GI', 'Europe/Gibraltar', '2022-12-30 02:06:15'),
(440, 'Guernsey ,GG', 'Europe/Guernsey', '2022-12-30 02:06:15'),
(441, 'Helsinki,AX', 'Europe/Helsinki', '2022-12-30 02:06:15'),
(442, 'Isle of Man', 'Europe/Isle_of_Man', '2022-12-30 02:06:15'),
(443, 'Turkey ,TR', 'Europe/Istanbul', '2022-12-30 02:06:15'),
(444, 'Jersey ', 'Europe/Jersey', '2022-12-30 02:06:15'),
(445, 'Kaliningrad,RU', 'Europe/Kaliningrad', '2022-12-30 02:06:15'),
(446, 'Ukraine ,UA', 'Europe/Kiev', '2022-12-30 02:06:15'),
(447, 'Kirov,RU', 'Europe/Kirov', '2022-12-30 02:06:15'),
(448, 'Lisbon,PT', 'Europe/Lisbon', '2022-12-30 02:06:15'),
(449, 'Ljubljana', 'Europe/Ljubljana', '2022-12-30 02:06:15'),
(450, 'London,UK', 'Europe/London', '2022-12-30 02:06:15'),
(451, 'Luxembourg,LU', 'Europe/Luxembourg', '2022-12-30 02:06:15'),
(452, 'Madrid,ES', 'Europe/Madrid', '2022-12-30 02:06:15'),
(453, 'Malta,MT', 'Europe/Malta', '2022-12-30 02:06:15'),
(454, 'Mariehamn', 'Europe/Mariehamn', '2022-12-30 02:06:15'),
(455, 'Minsk,BY', 'Europe/Minsk', '2022-12-30 02:06:15'),
(456, 'Monaco,MC', 'Europe/Monaco', '2022-12-30 02:06:15'),
(457, 'Moscow,RU', 'Europe/Moscow', '2022-12-30 02:06:15'),
(458, 'Nicosia ', 'Europe/Nicosia', '2022-12-30 02:06:15'),
(459, 'Norway ,NO', 'Europe/Oslo', '2022-12-30 02:06:15'),
(460, 'France,FR', 'Europe/Paris', '2022-12-30 02:06:15'),
(461, 'Podgorica', 'Europe/Podgorica', '2022-12-30 02:06:15'),
(462, 'Czech ,CZ', 'Europe/Prague', '2022-12-30 02:06:15'),
(463, 'Latvia ,LV', 'Europe/Riga', '2022-12-30 02:06:15'),
(464, 'Italy ,IT', 'Europe/Rome', '2022-12-30 02:06:15'),
(465, 'Samara,RU', 'Europe/Samara', '2022-12-30 02:06:15'),
(466, 'San Marino,SM', 'Europe/San_Marino', '2022-12-30 02:06:15'),
(467, 'Sarajevo', 'Europe/Sarajevo', '2022-12-30 02:06:15'),
(468, 'Saratov Oblast,RU', 'Europe/Saratov', '2022-12-30 02:06:15'),
(469, 'Simferopol ', 'Europe/Simferopol', '2022-12-30 02:06:15'),
(470, 'Skopje', 'Europe/Skopje', '2022-12-30 02:06:15'),
(471, 'Bulgaria ,BG', 'Europe/Sofia', '2022-12-30 02:06:15'),
(472, 'Sweden ,SE', 'Europe/Stockholm', '2022-12-30 02:06:15'),
(473, 'Estonia ,EE', 'Europe/Tallinn', '2022-12-30 02:06:15'),
(474, 'Albania ,AL', 'Europe/Tirane', '2022-12-30 02:06:15'),
(475, 'Tiraspol', 'Europe/Tiraspol', '2022-12-30 02:06:15'),
(476, 'Ulyanovsk,RU', 'Europe/Ulyanovsk', '2022-12-30 02:06:15'),
(477, 'Uzhhorod,UA', 'Europe/Uzhgorod', '2022-12-30 02:06:15'),
(478, 'Vaduz', 'Europe/Vaduz', '2022-12-30 02:06:15'),
(479, 'Vatican City', 'Europe/Vatican', '2022-12-30 02:06:15'),
(480, 'Austria ,AT', 'Europe/Vienna', '2022-12-30 02:06:15'),
(481, 'Lithuania ,LT', 'Europe/Vilnius', '2022-12-30 02:06:15'),
(482, 'Volgograd ', 'Europe/Volgograd', '2022-12-30 02:06:15'),
(483, 'Poland ,PL', 'Europe/Warsaw', '2022-12-30 02:06:15'),
(484, 'Zagreb', 'Europe/Zagreb', '2022-12-30 02:06:15'),
(485, 'Zaporizhzhia', 'Europe/Zaporozhye', '2022-12-30 02:06:15'),
(486, 'Switzerland,CH', 'Europe/Zurich', '2022-12-30 02:06:15'),
(487, 'United Kingdom,GB', 'GB', '2022-12-30 02:06:15'),
(488, 'Caighdeánach ', 'GB-Eire', '2022-12-30 02:06:15'),
(489, 'GMT', 'GMT', '2022-12-30 02:06:15'),
(490, 'GMT+0', 'GMT+0', '2022-12-30 02:06:15'),
(491, 'GMT-0', 'GMT-0', '2022-12-30 02:06:15'),
(492, 'GMT0', 'GMT0', '2022-12-30 02:06:15'),
(493, 'London,UK', 'Greenwich', '2022-12-30 02:06:15'),
(494, 'Hongkong', 'Hongkong', '2022-12-30 02:06:15'),
(495, 'Hawaii Standard Time,HST', 'HST', '2022-12-30 02:06:15'),
(496, 'Iceland,IS', 'Iceland', '2022-12-30 02:06:15'),
(497, 'Antananarivo ', 'Indian/Antananarivo', '2022-12-30 02:06:15'),
(498, 'British Indian Ocean Territory,IO', 'Indian/Chagos', '2022-12-30 02:06:15'),
(499, 'Christmas Island,CX', 'Indian/Christmas', '2022-12-30 02:06:15'),
(500, 'Cocos ,CC', 'Indian/Cocos', '2022-12-30 02:06:15'),
(501, 'Comoro', 'Indian/Comoro', '2022-12-30 02:06:15'),
(502, 'French Southern Territories,TF', 'Indian/Kerguelen', '2022-12-30 02:06:15'),
(503, 'Seychelles,SC', 'Indian/Mahe', '2022-12-30 02:06:15'),
(504, 'Maldives,MV', 'Indian/Maldives', '2022-12-30 02:06:15'),
(505, 'Mauritius ,MU', 'Indian/Mauritius', '2022-12-30 02:06:15'),
(506, 'Mayotte ', 'Indian/Mayotte', '2022-12-30 02:06:15'),
(507, 'Réunion ,RE', 'Indian/Reunion', '2022-12-30 02:06:15'),
(508, 'Iran,IR', 'Iran', '2022-12-30 02:06:15'),
(509, 'Israel,IL', 'Israel', '2022-12-30 02:06:15'),
(510, 'Jamaica,JM', 'Jamaica', '2022-12-30 02:06:15'),
(511, 'Japan,JP', 'Japan', '2022-12-30 02:06:15'),
(512, 'Kwajalein,MH', 'Kwajalein', '2022-12-30 02:06:15'),
(513, 'Libya,LY', 'Libya', '2022-12-30 02:06:15'),
(514, 'Middle European Time,MET', 'MET', '2022-12-30 02:06:15'),
(515, ' Baja California,MX', 'Mexico/BajaNorte', '2022-12-30 02:06:15'),
(516, ' Baja California Sur,MX', 'Mexico/BajaSur', '2022-12-30 02:06:15'),
(517, 'capital Mexico City,MX', 'Mexico/General', '2022-12-30 02:06:15'),
(518, 'Mountain Time,MST', 'MST', '2022-12-30 02:06:15'),
(519, 'US Mountain Time', 'MST7MDT', '2022-12-30 02:06:15'),
(520, 'Navajo', 'Navajo', '2022-12-30 02:06:15'),
(521, 'New Zealand,NZ', 'NZ', '2022-12-30 02:06:15'),
(522, 'Chatham Island Standard Time,CHAST', 'NZ-CHAT', '2022-12-30 02:06:15'),
(523, 'Samoa,WS', 'Pacific/Apia', '2022-12-30 02:06:15'),
(524, 'Auckland,NZ', 'Pacific/Auckland', '2022-12-30 02:06:15'),
(525, 'Bougainville Island', 'Pacific/Bougainville', '2022-12-30 02:06:15'),
(526, 'Chatham ,NZ', 'Pacific/Chatham', '2022-12-30 02:06:15'),
(527, 'Chuuk,FM', 'Pacific/Chuuk', '2022-12-30 02:06:15'),
(528, 'Easter,CL', 'Pacific/Easter', '2022-12-30 02:06:15'),
(529, 'Vanuatu,VU', 'Pacific/Efate', '2022-12-30 02:06:15'),
(530, 'Enderbury,UM', 'Pacific/Enderbury', '2022-12-30 02:06:15'),
(531, 'Fakaofo,TK', 'Pacific/Fakaofo', '2022-12-30 02:06:15'),
(532, 'Fiji,FJ', 'Pacific/Fiji', '2022-12-30 02:06:15'),
(533, 'Tuvalu,TV', 'Pacific/Funafuti', '2022-12-30 02:06:15'),
(534, 'Galapagos,EC', 'Pacific/Galapagos', '2022-12-30 02:06:15'),
(535, 'Gambier,PF', 'Pacific/Gambier', '2022-12-30 02:06:15'),
(536, 'Solomon Islands,SB', 'Pacific/Guadalcanal', '2022-12-30 02:06:15'),
(537, 'Guam,GU', 'Pacific/Guam', '2022-12-30 02:06:15'),
(538, 'Honolulu,US', 'Pacific/Honolulu', '2022-12-30 02:06:15'),
(539, 'United States Minor Outlying Islands,US', 'Pacific/Johnston', '2022-12-30 02:06:15'),
(540, 'Kanton ', 'Pacific/Kanton', '2022-12-30 02:06:15'),
(541, 'Kiritimati ', 'Pacific/Kiritimati', '2022-12-30 02:06:15'),
(542, 'Kosrae,FM', 'Pacific/Kosrae', '2022-12-30 02:06:15'),
(543, 'Kwajalein,MH', 'Pacific/Kwajalein', '2022-12-30 02:06:15'),
(544, 'Majuro,MH', 'Pacific/Majuro', '2022-12-30 02:06:15'),
(545, 'Marquesas,PF', 'Pacific/Marquesas', '2022-12-30 02:06:15'),
(546, 'Midway ', 'Pacific/Midway', '2022-12-30 02:06:15'),
(547, 'Nauru,NR', 'Pacific/Nauru', '2022-12-30 02:06:15'),
(548, 'Niue,NU', 'Pacific/Niue', '2022-12-30 02:06:15'),
(549, 'Norfolk Island,NF', 'Pacific/Norfolk', '2022-12-30 02:06:15'),
(550, 'New Caledonia,NC', 'Pacific/Noumea', '2022-12-30 02:06:15'),
(551, 'Pago Pago,NS', 'Pacific/Pago_Pago', '2022-12-30 02:06:15'),
(552, 'Palau,PW', 'Pacific/Palau', '2022-12-30 02:06:15'),
(553, 'Pitcairn Islands,PN', 'Pacific/Pitcairn', '2022-12-30 02:06:15'),
(554, 'Micronesia,FM', 'Pacific/Pohnpei', '2022-12-30 02:06:15'),
(555, 'Ponape ', 'Pacific/Ponape', '2022-12-30 02:06:15'),
(556, 'Papua New Guinea,PG', 'Pacific/Port_Moresby', '2022-12-30 02:06:15'),
(557, 'Cook Islands,CK', 'Pacific/Rarotonga', '2022-12-30 02:06:15'),
(558, 'Saipan ', 'Pacific/Saipan', '2022-12-30 02:06:15'),
(559, 'Samoa ', 'Pacific/Samoa', '2022-12-30 02:06:15'),
(560, 'Tahiti,PF', 'Pacific/Tahiti', '2022-12-30 02:06:15'),
(561, 'Tarawa,KI', 'Pacific/Tarawa', '2022-12-30 02:06:15'),
(562, 'Tonga,TO', 'Pacific/Tongatapu', '2022-12-30 02:06:15'),
(563, 'Micronesia,FM', 'Pacific/Truk', '2022-12-30 02:06:15'),
(564, 'Wake,UM', 'Pacific/Wake', '2022-12-30 02:06:15'),
(565, 'Wallis & Futuna,WF', 'Pacific/Wallis', '2022-12-30 02:06:15'),
(566, 'Micronesia ', 'Pacific/Yap', '2022-12-30 02:06:15'),
(567, 'Poland,PL', 'Poland', '2022-12-30 02:06:15'),
(568, 'Portugal,PT', 'Portugal', '2022-12-30 02:06:15'),
(569, 'PRC,CA', 'PRC', '2022-12-30 02:06:15'),
(570, 'PST8PDT', 'PST8PDT', '2022-12-30 02:06:15'),
(571, 'ROC', 'ROC', '2022-12-30 02:06:15'),
(572, 'Asia/Seoul', 'ROK', '2022-12-30 02:06:15'),
(573, 'Singapore', 'Singapore', '2022-12-30 02:06:15'),
(574, 'Turkey,TR', 'Turkey', '2022-12-30 02:06:15'),
(575, 'Coordinated Universal Time,UTC', 'UCT', '2022-12-30 02:06:15'),
(576, 'Universal', 'Universal', '2022-12-30 02:06:15'),
(577, 'Juneau,US', 'US/Alaska', '2022-12-30 02:06:15'),
(578, 'Hawaii Aleutian,HST', 'US/Aleutian', '2022-12-30 02:06:15'),
(579, 'Phoenix,USA', 'US/Arizona', '2022-12-30 02:06:15'),
(580, 'Central Time ,CT', 'US/Central', '2022-12-30 02:06:15'),
(581, ' East Central Indiana', 'US/East-Indiana', '2022-12-30 02:06:15'),
(582, 'Eastern Standard Time,EST', 'US/Eastern', '2022-12-30 02:06:15'),
(583, 'Hawaii,USA', 'US/Hawaii', '2022-12-30 02:06:15'),
(584, ' Starke,USA', 'US/Indiana-Starke', '2022-12-30 02:06:15'),
(585, 'Lansing,USA', 'US/Michigan', '2022-12-30 02:06:15'),
(586, 'Mountain ,USA', 'US/Mountain', '2022-12-30 02:06:15'),
(587, 'Pacific ,USA', 'US/Pacific', '2022-12-30 02:06:15'),
(588, 'Samoa ,USA', 'US/Samoa', '2022-12-30 02:06:15'),
(589, 'Coordinated Universal Time,UTC', 'UTC', '2022-12-30 02:06:15'),
(590, 'Europe/Moscow', 'W-SU', '2022-12-30 02:06:15'),
(591, 'Western European Time,WET', 'WET', '2022-12-30 02:06:15'),
(592, 'Zulu', 'Zulu', '2022-12-30 02:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_companies_users`
--

CREATE TABLE `typicms_companies_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `profile_id` int(10) UNSIGNED NOT NULL,
  `profile_type` varchar(50) DEFAULT NULL,
  `profile_name` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `web_tracking` tinyint(4) NOT NULL DEFAULT 1,
  `idle_time_tracking` tinyint(4) NOT NULL DEFAULT 1,
  `default_approval` varchar(40) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_terminated` tinyint(4) NOT NULL DEFAULT 0,
  `is_employee` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_companies_users`
--

INSERT INTO `typicms_companies_users` (`id`, `company_id`, `user_id`, `profile_id`, `profile_type`, `profile_name`, `status`, `web_tracking`, `idle_time_tracking`, `default_approval`, `is_deleted`, `created_at`, `updated_at`, `is_terminated`, `is_employee`) VALUES
(40, 40, 42, 1, 'Owner', 'Owner', 'active', 1, 1, NULL, 0, '2022-08-30 09:49:51', '2022-08-30 09:51:07', 0, 1),
(41, 40, 43, 4, NULL, NULL, 'active', 1, 1, NULL, 1, NULL, '2023-08-07 10:22:02', 0, 0),
(42, 40, 44, 1, NULL, NULL, 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(43, 40, 45, 2, NULL, NULL, 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(44, 40, 46, 4, NULL, NULL, 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(45, 40, 47, 1, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(46, 40, 48, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(47, 40, 49, 2, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(48, 40, 50, 30, 'User', 'QA', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(49, 40, 51, 6, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(50, 40, 52, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(51, 40, 53, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(52, 40, 54, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(53, 40, 55, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(54, 40, 56, 11, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(55, 40, 57, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(56, 40, 58, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(57, 40, 59, 1, 'Admin', 'Admin', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(58, 40, 60, 40, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(59, 40, 61, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(60, 40, 62, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(61, 40, 63, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(62, 40, 64, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(63, 40, 65, 38, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(64, 40, 66, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(65, 40, 67, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(66, 40, 68, 27, 'User', 'Developer', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(67, 40, 69, 45, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(68, 40, 70, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(69, 40, 71, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(70, 40, 72, 9, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(71, 40, 73, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(72, 40, 74, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(73, 40, 75, 9, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(74, 40, 76, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(75, 40, 77, 10, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(76, 40, 78, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(77, 40, 79, 10, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(78, 40, 80, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(79, 40, 81, 21, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(80, 40, 82, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(81, 40, 83, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(82, 40, 84, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(83, 40, 85, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(84, 40, 86, 19, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(85, 40, 87, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(86, 40, 88, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(87, 40, 89, 18, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(88, 40, 90, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(89, 40, 91, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(90, 40, 92, 57, 'User', 'User', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(91, 40, 93, 37, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(92, 40, 94, 18, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(93, 40, 95, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(94, 40, 96, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(95, 40, 97, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(96, 40, 98, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(97, 40, 99, 53, 'Manager', 'Manager', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(98, 40, 100, 55, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(99, 40, 101, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(100, 40, 102, 7, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(101, 40, 103, 6, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(102, 40, 104, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(103, 40, 105, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(104, 40, 106, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(105, 40, 107, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(106, 40, 108, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(107, 40, 109, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(108, 40, 110, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(109, 40, 111, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(110, 40, 112, 39, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(111, 40, 113, 14, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(112, 40, 114, 55, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(113, 40, 115, 55, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(114, 40, 116, 55, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(115, 40, 117, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(116, 40, 118, 14, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(117, 41, 119, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-09-06 06:03:18', '2022-09-06 06:03:52', 0, 1),
(118, 42, 120, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-09-06 06:20:06', '2022-09-06 06:21:12', 0, 1),
(119, 40, 121, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(120, 40, 122, 15, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(121, 42, 123, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-09-06 07:31:04', '2022-09-06 07:31:04', 0, 1),
(122, 43, 124, 1, NULL, NULL, 'deactive', 1, 1, NULL, 0, '2022-09-06 10:02:08', '2022-09-06 10:02:08', 0, 1),
(123, 44, 125, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-09-06 10:05:59', '2022-09-06 10:06:20', 0, 1),
(124, 44, 126, 2, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(125, 44, 127, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(126, 40, 128, 55, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(128, 40, 130, 19, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(129, 40, 131, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(130, 40, 132, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(131, 40, 133, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(132, 40, 134, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(133, 40, 135, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(134, 40, 136, 14, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(135, 40, 137, 14, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(136, 40, 138, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(137, 40, 139, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(138, 40, 140, 14, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(139, 40, 141, 53, 'Manager', 'Manager', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(140, 40, 142, 55, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(141, 40, 143, 22, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(144, 40, 146, 11, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(145, 40, 147, 52, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(146, 40, 148, 19, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(147, 40, 149, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(148, 40, 150, 7, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(149, 40, 151, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(151, 40, 153, 19, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(152, 40, 154, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(153, 40, 155, 18, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(154, 40, 156, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(156, 40, 158, 19, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(158, 40, 160, 19, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(159, 40, 161, 19, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(160, 40, 162, 4, 'User', 'User', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(161, 40, 163, 63, 'Manager', 'Manager', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(162, 40, 164, 55, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(163, 40, 165, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(164, 40, 166, 21, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(165, 40, 167, 21, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(166, 40, 168, 21, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(167, 40, 169, 18, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(168, 40, 170, 41, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(169, 40, 171, 18, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(170, 40, 172, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(171, 40, 173, 18, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(172, 40, 174, 18, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(174, 44, 176, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(175, 45, 177, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-09-21 07:07:40', '2022-09-21 07:08:46', 0, 1),
(176, 46, 178, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-09-21 07:14:06', '2022-09-21 07:14:31', 0, 1),
(177, 44, 179, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(178, 45, 179, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(179, 46, 179, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(180, 45, 180, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(181, 45, 181, 2, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(182, 44, 181, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(183, 44, 180, 4, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(184, 40, 182, 1, NULL, NULL, 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(185, 47, 186, 1, NULL, NULL, 'deactive', 1, 1, NULL, 0, '2022-10-14 03:24:37', '2022-10-14 03:24:37', 0, 1),
(186, 48, 187, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-10-14 03:28:09', '2022-10-14 03:28:51', 0, 1),
(187, 40, 187, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(188, 40, 188, 1, NULL, NULL, 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(189, 40, 189, 25, NULL, NULL, 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(190, 40, 190, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(191, 40, 191, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(192, 40, 192, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(193, 40, 193, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(194, 40, 194, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(195, 40, 195, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(196, 40, 196, 4, NULL, NULL, 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(197, 40, 197, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(198, 40, 198, 59, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(199, 40, 199, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(200, 40, 200, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(201, 40, 201, 57, 'User', 'User', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(203, 49, 203, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-10-18 06:54:50', '2022-10-18 06:56:08', 0, 1),
(204, 40, 204, 2, 'Manager', 'Manager', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(206, 40, 206, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(207, 50, 207, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-11-19 05:53:29', '2022-11-19 05:54:11', 0, 1),
(208, 51, 208, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-11-19 05:59:48', '2022-11-19 06:03:09', 0, 1),
(209, 52, 209, 1, NULL, NULL, 'active', 1, 1, NULL, 0, '2022-11-19 06:08:37', '2022-11-19 06:09:00', 0, 1),
(210, 50, 208, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(211, 52, 48, 1, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(212, 50, 210, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(213, 52, 187, 4, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(214, 51, 210, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(215, 52, 50, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(216, 50, 211, 4, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(217, 50, 212, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(218, 50, 213, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(219, 40, 214, 34, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(220, 40, 215, 48, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(221, 40, 216, 48, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(222, 40, 217, 49, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(223, 40, 218, 48, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(224, 40, 219, 49, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(225, 40, 220, 33, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(226, 40, 221, 25, 'Manager', 'Tech Support Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(227, 50, 222, 1, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(228, 40, 223, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(229, 40, 224, 1, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(230, 40, 225, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(231, 40, 226, 63, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(232, 40, 227, 43, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(233, 40, 228, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(234, 50, 229, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(235, 40, 230, 25, 'Manager', 'Tech Support Manager', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(236, 40, 231, 25, 'Manager', 'Tech Support Manager', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(237, 40, 232, 44, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(239, 52, 234, 4, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(240, 52, 235, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(241, 52, 236, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(242, 40, 237, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(243, 40, 238, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(244, 40, 239, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(245, 40, 240, 53, 'Manager', 'Manager', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(246, 40, 241, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(247, 40, 242, 35, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(249, 40, 244, 46, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(250, 40, 245, 56, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(251, 40, 246, 13, 'User', 'campaign management', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(254, 52, 249, 1, 'Admin', 'Admin', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(256, 52, 251, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(257, 50, 226, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(258, 40, 252, 64, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(259, 40, 253, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(260, 40, 254, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(261, 40, 255, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(262, 40, 256, 47, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(263, 40, 257, 2, 'Manager', 'Manager', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(264, 40, 258, 46, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(265, 40, 259, 46, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(266, 40, 260, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(267, 40, 261, 51, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(268, 40, 262, 51, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(269, 40, 263, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(270, 40, 264, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(271, 40, 265, 50, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(272, 40, 266, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(273, 40, 267, 42, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(274, 40, 268, 42, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(275, 40, 269, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(276, 40, 270, 64, 'User', 'User', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(277, 40, 271, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(278, 40, 272, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(279, 50, 273, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(280, 50, 274, 4, 'User', 'User', 'active', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(281, 40, 275, 2, 'Manager', 'Manager', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(282, 40, 276, 1, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(283, 40, 277, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(284, 40, 278, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(286, 40, 280, 59, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(287, 40, 281, 64, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(288, 40, 282, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(290, 40, 284, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(291, 52, 285, 4, 'User', 'User', 'active', 1, 1, NULL, 0, '2023-02-18 06:53:37', '2023-02-18 06:53:37', 0, 1),
(292, 40, 286, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(293, 40, 287, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(294, 52, 290, 1, 'Admin', 'Admin', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(295, 52, 291, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(296, 50, 292, 4, 'User', 'User', 'invited', 1, 1, NULL, 1, '2023-03-18 03:23:45', '2023-03-18 03:23:45', 0, 1),
(297, 50, 293, 4, 'User', 'User', 'invited', 1, 1, NULL, 1, '2023-03-18 03:30:38', '2023-03-18 03:30:38', 0, 1),
(298, 50, 294, 1, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, '2023-03-18 13:01:36', '2023-03-18 13:01:36', 0, 1),
(299, 40, 295, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(300, 40, 296, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(301, 40, 297, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(302, 40, 298, 36, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(303, 40, 299, 3, 'Viewer', 'Viewer', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(304, 40, 300, 4, 'User', 'User', 'deactive', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(305, 40, 301, 42, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(306, 40, 302, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(307, 40, 303, 64, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(309, 40, 305, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(310, 40, 306, 36, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(311, 40, 307, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(312, 40, 308, 41, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(315, 40, 311, 1, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(316, 40, 312, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(317, 40, 313, 57, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(318, 40, 314, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(319, 40, 315, 51, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(320, 40, 316, 64, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(321, 50, 317, 4, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(323, 40, 319, 54, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(333, 40, 329, 39, 'User', 'Call QA Executive', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(334, 40, 330, 54, 'User', 'Operations Executive', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(335, 40, 331, 54, 'User', 'Operations Executive', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(336, 40, 332, 54, 'User', 'Operations Executive', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(337, 40, 333, 46, 'User', 'Graphics Designer', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(338, 40, 334, 53, 'Manager', 'Operations Coordinator', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(339, 40, 335, 53, 'Manager', 'Operations Coordinator', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(340, 40, 336, 46, 'User', 'Graphics Designer', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(341, 40, 337, 53, 'Manager', 'Operations Coordinator', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(342, 40, 338, 53, 'Manager', 'Operations Coordinator', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(343, 40, 339, 44, 'Manager', 'DM Manager', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(344, 40, 340, 54, 'User', 'Operations Executive', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(345, 40, 341, 53, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(346, 40, 342, 54, 'User', 'Operations Executive', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(347, 40, 343, 63, 'Manager', 'Manager', 'deactive', 1, 1, NULL, 1, NULL, NULL, 0, 0),
(348, 40, 317, 1, 'Admin', 'Admin', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(349, 40, 344, 2, 'Manager', 'Manager', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(350, 40, 348, 3, 'Viewer', 'Viewer', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(351, 50, 350, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(352, 40, 350, 4, 'User', 'User', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(353, 40, 351, 1, 'Admin', 'Admin', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(354, 40, 352, 4, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(355, 40, 353, 4, 'User', 'User', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(356, 50, 354, 1, 'Admin', 'Admin', 'active', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(357, 50, 355, 1, 'Admin', 'Admin', 'invited', 1, 1, NULL, 0, NULL, NULL, 0, 0),
(359, 50, 356, 3, 'Viewer', 'Viewer', 'active', 1, 1, NULL, 0, NULL, '2023-08-21 06:13:21', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `typicms_contacts`
--

CREATE TABLE `typicms_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `privacy_policy_accepted` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_contacts`
--

INSERT INTO `typicms_contacts` (`id`, `locale`, `name`, `email`, `message`, `privacy_policy_accepted`, `created_at`, `updated_at`) VALUES
(1, 'en', 'adnan', 'ahmad@crecentech.com', 'Hello Wor', 1, '2023-05-31 08:21:42', '2023-06-08 09:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_events`
--

CREATE TABLE `typicms_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `registration_form` tinyint(1) NOT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `venue` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`venue`)),
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`address`)),
  `summary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`summary`)),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`body`)),
  `website` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`website`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_failed_jobs`
--

CREATE TABLE `typicms_failed_jobs` (
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
-- Table structure for table `typicms_files`
--

CREATE TABLE `typicms_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `folder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('a','v','d','i','o','f') DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `extension` varchar(8) DEFAULT NULL,
  `mimetype` varchar(100) DEFAULT NULL,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `filesize` int(10) UNSIGNED DEFAULT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`description`)),
  `alt_attribute` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`alt_attribute`)),
  `focal_x` tinyint(3) UNSIGNED DEFAULT NULL,
  `focal_y` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_files`
--

INSERT INTO `typicms_files` (`id`, `folder_id`, `type`, `name`, `path`, `extension`, `mimetype`, `width`, `height`, `filesize`, `title`, `description`, `alt_attribute`, `focal_x`, `focal_y`, `created_at`, `updated_at`) VALUES
(1, NULL, 'f', 'test', NULL, NULL, NULL, NULL, NULL, NULL, '{\"en\":null}', '{\"en\":null}', '{\"en\":null}', NULL, NULL, '2023-03-14 18:11:03', '2023-03-14 18:11:03'),
(2, 1, 'f', 'Test', NULL, NULL, NULL, NULL, NULL, NULL, '{\"en\":null}', '{\"en\":null}', '{\"en\":null}', NULL, NULL, '2023-03-21 17:41:00', '2023-03-21 17:41:00'),
(3, 2, 'i', 'download.png', 'files/download.png', 'png', 'image/png', 1895, 1050, 153039, '{\"en\":null}', '{\"en\":null}', '{\"en\":null}', NULL, NULL, '2023-03-21 17:41:13', '2023-03-21 17:41:13'),
(4, NULL, 'f', 'gakkery', NULL, NULL, NULL, NULL, NULL, NULL, '{\"en\":null}', '{\"en\":null}', '{\"en\":null}', NULL, NULL, '2023-03-21 18:05:24', '2023-03-21 18:05:24'),
(5, 4, 'a', 'untitled-2.mp4', 'files/untitled-2.mp4', 'mp4', 'video/mp4', NULL, NULL, 687608, '{\"en\":null}', '{\"en\":null}', '{\"en\":null}', NULL, NULL, '2023-03-21 18:05:46', '2023-03-21 18:05:46'),
(6, NULL, 'i', 'gj2ggjoxrliijmsofmtqwiz7ze3kdkqgtavd3ite.png', 'files/gj2ggjoxrliijmsofmtqwiz7ze3kdkqgtavd3ite.png', 'png', 'image/png', 754, 400, 4391, '{\"en\":null}', '{\"en\":null}', '{\"en\":null}', NULL, NULL, '2023-03-21 18:07:52', '2023-03-21 18:07:52'),
(59, NULL, 'i', 'slide-1_1.png', 'files/slide-1_1.png', 'png', 'image/png', 500, 350, 137760, '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', NULL, NULL, '2023-06-14 06:37:22', '2023-06-14 06:37:22'),
(60, NULL, 'i', 'slide-1_2.png', 'files/slide-1_2.png', 'png', 'image/png', 500, 350, 137760, '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', NULL, NULL, '2023-06-14 06:37:25', '2023-06-14 06:37:25'),
(61, NULL, 'f', 'asdf', NULL, NULL, NULL, NULL, NULL, NULL, '{\"en\":null}', '{\"en\":null}', '{\"en\":null}', NULL, NULL, '2023-06-14 06:46:31', '2023-06-14 06:46:31'),
(62, NULL, 'i', 'slide-1_3.png', 'files/slide-1_3.png', 'png', 'image/png', 500, 350, 137760, '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', NULL, NULL, '2023-06-14 06:51:00', '2023-06-14 06:51:00'),
(63, NULL, 'i', 'Hello.png', 'files/slide-1.png', 'png', 'image/png', 500, 350, 137760, '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', '{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"}', NULL, NULL, '2023-06-14 07:02:47', '2023-06-14 07:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_history`
--

CREATE TABLE `typicms_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `historable_id` int(10) UNSIGNED NOT NULL,
  `historable_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `historable_table` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `old` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`old`)),
  `new` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`new`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_history`
--

INSERT INTO `typicms_history` (`id`, `historable_id`, `historable_type`, `user_id`, `title`, `locale`, `historable_table`, `action`, `old`, `new`, `created_at`, `updated_at`) VALUES
(1, 1, 'TypiCMS\\Modules\\Core\\Models\\User', NULL, 'admin admin', NULL, 'users', 'created', '[]', '{\"first_name\":\"admin\",\"last_name\":\"admin\",\"email\":\"farzand.ali@crecentech.com\",\"superuser\":1,\"activated\":1,\"email_verified_at\":\"2023-03-14T12:09:05.057598Z\",\"api_token\":\"68f64c2c-29f0-422a-b827-c6c37982a3a5\",\"updated_at\":\"2023-03-14T12:09:05.000000Z\",\"created_at\":\"2023-03-14T12:09:05.000000Z\",\"id\":1}', '2023-03-14 17:09:05', '2023-03-14 17:09:05'),
(2, 7, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'created', '[]', '{\"image_id\":null,\"module\":null,\"template\":null,\"is_home\":\"0\",\"private\":\"0\",\"redirect\":\"0\",\"css\":null,\"js\":null,\"title\":{\"en\":\"Home\",\"nl\":null},\"uri\":{\"en\":\"home\"},\"status\":{\"en\":\"0\",\"fr\":\"0\",\"nl\":\"0\"},\"body\":{\"nl\":null},\"meta_keywords\":{\"nl\":null},\"meta_description\":{\"nl\":null},\"slug\":{\"en\":\"home\",\"nl\":null},\"updated_at\":\"2023-03-14T12:28:19.000000Z\",\"created_at\":\"2023-03-14T12:28:19.000000Z\",\"id\":7}', '2023-03-14 17:28:19', '2023-03-14 17:28:19'),
(3, 1, 'TypiCMS\\Modules\\Core\\Models\\File', 1, 'test', NULL, 'files', 'created', '[]', '{\"folder_id\":null,\"type\":\"f\",\"name\":\"test\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-03-14T13:11:03.000000Z\",\"created_at\":\"2023-03-14T13:11:03.000000Z\",\"id\":1,\"thumb_sm\":\"http:\\/\\/127.0.0.1:2000\\/.\\/storage-240x240-resize?token=5ae982f9465c923383dfdfc39dbdc3f3\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-03-14 18:11:03', '2023-03-14 18:11:03'),
(4, 2, 'TypiCMS\\Modules\\Core\\Models\\File', 1, 'Test', NULL, 'files', 'created', '[]', '{\"folder_id\":1,\"type\":\"f\",\"name\":\"Test\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-03-21T12:41:00.000000Z\",\"created_at\":\"2023-03-21T12:41:00.000000Z\",\"id\":2,\"thumb_sm\":\"http:\\/\\/127.0.0.1:2030\\/.\\/storage-240x240-resize?token=5ae982f9465c923383dfdfc39dbdc3f3\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-03-21 17:41:00', '2023-03-21 17:41:00'),
(5, 3, 'TypiCMS\\Modules\\Core\\Models\\File', 1, 'download.png', NULL, 'files', 'created', '[]', '{\"folder_id\":\"2\",\"name\":\"download.png\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"filesize\":153039,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":1895,\"height\":1050,\"path\":\"files\\/download.png\",\"type\":\"i\",\"updated_at\":\"2023-03-21T12:41:13.000000Z\",\"created_at\":\"2023-03-21T12:41:13.000000Z\",\"id\":3,\"thumb_sm\":\"http:\\/\\/127.0.0.1:2030\\/storage\\/files\\/download-240x240-resize.png?token=9e5ff4fdabd97055b88d8474feeee3ec\",\"url\":\"http:\\/\\/localhost\\/storage\\/files\\/download.png\"}', '2023-03-21 17:41:13', '2023-03-21 17:41:13'),
(6, 1, 'TypiCMS\\Modules\\Projects\\Models\\ProjectCategory', 1, '', NULL, 'project_categories', 'created', '[]', '{\"image_id\":null,\"title\":{\"nl\":null},\"slug\":{\"nl\":null},\"status\":{\"en\":\"0\",\"fr\":\"0\",\"nl\":\"0\"},\"position\":1,\"updated_at\":\"2023-03-21T12:46:59.000000Z\",\"created_at\":\"2023-03-21T12:46:59.000000Z\",\"id\":1,\"thumb\":\"http:\\/\\/127.0.0.1:2030\\/storage\\/img-not-found-_x54.png?token=c64942488fa5d5b0412d832d80d27477\",\"image\":null}', '2023-03-21 17:46:59', '2023-03-21 17:46:59'),
(7, 4, 'TypiCMS\\Modules\\Core\\Models\\File', 1, 'gakkery', NULL, 'files', 'created', '[]', '{\"folder_id\":null,\"type\":\"f\",\"name\":\"gakkery\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-03-21T13:05:24.000000Z\",\"created_at\":\"2023-03-21T13:05:24.000000Z\",\"id\":4,\"thumb_sm\":\"http:\\/\\/127.0.0.1:2030\\/.\\/storage-240x240-resize?token=5ae982f9465c923383dfdfc39dbdc3f3\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-03-21 18:05:24', '2023-03-21 18:05:24'),
(8, 5, 'TypiCMS\\Modules\\Core\\Models\\File', 1, 'untitled-2.mp4', NULL, 'files', 'created', '[]', '{\"folder_id\":\"4\",\"name\":\"untitled-2.mp4\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"filesize\":687608,\"mimetype\":\"video\\/mp4\",\"extension\":\"mp4\",\"width\":null,\"height\":null,\"path\":\"files\\/untitled-2.mp4\",\"type\":\"a\",\"updated_at\":\"2023-03-21T13:05:46.000000Z\",\"created_at\":\"2023-03-21T13:05:46.000000Z\",\"id\":5,\"thumb_sm\":\"http:\\/\\/127.0.0.1:2030\\/storage\\/files\\/untitled-2-240x240-resize.mp4?token=542088c1e290685cf936eb77e834fc99\",\"url\":\"http:\\/\\/localhost\\/storage\\/files\\/untitled-2.mp4\"}', '2023-03-21 18:05:46', '2023-03-21 18:05:46'),
(9, 6, 'TypiCMS\\Modules\\Core\\Models\\File', 1, 'gj2ggjoxrliijmsofmtqwiz7ze3kdkqgtavd3ite.png', NULL, 'files', 'created', '[]', '{\"folder_id\":null,\"name\":\"gj2ggjoxrliijmsofmtqwiz7ze3kdkqgtavd3ite.png\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"filesize\":4391,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":754,\"height\":400,\"path\":\"files\\/gj2ggjoxrliijmsofmtqwiz7ze3kdkqgtavd3ite.png\",\"type\":\"i\",\"updated_at\":\"2023-03-21T13:07:52.000000Z\",\"created_at\":\"2023-03-21T13:07:52.000000Z\",\"id\":6,\"thumb_sm\":\"http:\\/\\/127.0.0.1:2030\\/storage\\/files\\/gj2ggjoxrliijmsofmtqwiz7ze3kdkqgtavd3ite-240x240-resize.png?token=2447aa1c5517bd48a731713602fd9164\",\"url\":\"http:\\/\\/localhost\\/storage\\/files\\/gj2ggjoxrliijmsofmtqwiz7ze3kdkqgtavd3ite.png\"}', '2023-03-21 18:07:52', '2023-03-21 18:07:52'),
(10, 1, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'admin admin', NULL, 'users', 'updated', '{\"remember_token\":null}', '{\"remember_token\":\"v2EUbKAnpL6GOFf2Lx1VZtdlHee6LIXFNkDNfl3712caoE3cH0RXCH2073SN\"}', '2023-05-31 04:56:10', '2023-05-31 04:56:10'),
(11, 8, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Hello World', NULL, 'pages', 'created', '[]', '{\"image_id\":\"3\",\"module\":null,\"template\":null,\"is_home\":\"1\",\"private\":\"0\",\"redirect\":\"0\",\"css\":null,\"js\":null,\"title\":{\"en\":\"Hello World\",\"fr\":\"Hello World\",\"nl\":\"Hello World\"},\"uri\":{\"en\":\"hello-world\",\"fr\":\"hello-world\",\"nl\":\"hello-world\"},\"status\":{\"en\":\"1\",\"fr\":\"1\",\"nl\":\"1\"},\"body\":{\"en\":\"<p>Hello World Content.&nbsp;<\\/p>\",\"fr\":\"<p>Hello World Content<\\/p>\",\"nl\":\"<p>Hello World Content<\\/p>\"},\"meta_keywords\":{\"nl\":null},\"meta_description\":{\"nl\":null},\"slug\":{\"en\":\"hello-world\",\"fr\":\"hello-world\",\"nl\":\"hello-world\"},\"updated_at\":\"2023-05-31T09:08:19.000000Z\",\"created_at\":\"2023-05-31T09:08:19.000000Z\",\"id\":8}', '2023-05-31 08:08:19', '2023-05-31 08:08:19'),
(12, 8, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Hello World', NULL, 'pages', 'created', '[]', '{\"image_id\":null,\"module\":null,\"template\":null,\"is_home\":\"0\",\"private\":\"0\",\"redirect\":\"0\",\"css\":null,\"js\":null,\"title\":{\"en\":\"Hello World\",\"fr\":\"Hello World\",\"nl\":\"Hello World\"},\"uri\":{\"en\":\"hello-world\",\"fr\":\"hello-world\",\"nl\":\"hello-world\"},\"status\":{\"en\":\"1\",\"fr\":\"1\",\"nl\":\"1\"},\"body\":{\"en\":\"<p>Hello World Content<\\/p>\",\"fr\":\"<p>Hello World Content<\\/p>\",\"nl\":\"<p>Hello World Content<\\/p>\"},\"meta_keywords\":{\"nl\":null},\"meta_description\":{\"nl\":null},\"slug\":{\"en\":\"hello-world\",\"fr\":\"hello-world\",\"nl\":\"hello-world\"},\"updated_at\":\"2023-05-31T09:10:52.000000Z\",\"created_at\":\"2023-05-31T09:10:52.000000Z\",\"id\":8}', '2023-05-31 08:10:52', '2023-05-31 08:10:52'),
(13, 1, 'TypiCMS\\Modules\\Contacts\\Models\\Contact', 1, 'Adnan', NULL, 'contacts', 'created', '[]', '{\"email\":\"a.ahmad@crecentech.com\",\"locale\":\"en\",\"name\":\"Adnan\",\"message\":\"Hi.\",\"privacy_policy_accepted\":\"1\",\"updated_at\":\"2023-05-31T09:21:42.000000Z\",\"created_at\":\"2023-05-31T09:21:42.000000Z\",\"id\":1}', '2023-05-31 08:21:42', '2023-05-31 08:21:42'),
(14, 1, 'TypiCMS\\Modules\\Core\\Models\\Taxonomy', 1, 'Any_Body', NULL, 'taxonomies', 'created', '[]', '{\"name\":\"Any_Body\",\"validation_rule\":\"required\",\"modules\":[null],\"title\":{\"nl\":null},\"slug\":{\"nl\":null},\"result_string\":{\"nl\":null},\"position\":1,\"updated_at\":\"2023-05-31T09:33:02.000000Z\",\"created_at\":\"2023-05-31T09:33:02.000000Z\",\"id\":1}', '2023-05-31 08:33:03', '2023-05-31 08:33:03'),
(15, 7, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"position\":0,\"updated_at\":\"2023-03-14 18:28:19\"}', '{\"position\":2,\"updated_at\":\"2023-06-01 08:49:17\"}', '2023-06-01 07:49:17', '2023-06-01 07:49:17'),
(16, 2, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"position\":2,\"updated_at\":\"2023-03-14 18:08:44\"}', '{\"position\":3,\"updated_at\":\"2023-06-01 08:49:17\"}', '2023-06-01 07:49:17', '2023-06-01 07:49:17'),
(17, 3, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Search results', NULL, 'pages', 'updated', '{\"position\":3,\"updated_at\":\"2023-03-14 18:08:44\"}', '{\"position\":4,\"updated_at\":\"2023-06-01 08:49:18\"}', '2023-06-01 07:49:18', '2023-06-01 07:49:18'),
(18, 4, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Terms and Conditions', NULL, 'pages', 'updated', '{\"position\":4,\"updated_at\":\"2023-03-14 18:08:44\"}', '{\"position\":5,\"updated_at\":\"2023-06-01 08:49:18\"}', '2023-06-01 07:49:18', '2023-06-01 07:49:18'),
(19, 5, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Privacy policy', NULL, 'pages', 'updated', '{\"position\":5,\"updated_at\":\"2023-03-14 18:08:44\"}', '{\"position\":6,\"updated_at\":\"2023-06-01 08:49:18\"}', '2023-06-01 07:49:18', '2023-06-01 07:49:18'),
(20, 6, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Cookie policy', NULL, 'pages', 'updated', '{\"position\":6,\"updated_at\":\"2023-03-14 18:08:44\"}', '{\"position\":7,\"updated_at\":\"2023-06-01 08:49:18\"}', '2023-06-01 07:49:18', '2023-06-01 07:49:18'),
(21, 8, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Hello World', NULL, 'pages', 'updated', '{\"parent_id\":null,\"position\":8,\"uri\":{\"en\":\"hello-world\",\"fr\":\"hello-world\",\"nl\":\"hello-world\"},\"updated_at\":\"2023-05-31 09:10:52\"}', '{\"parent_id\":2,\"position\":1,\"uri\":{\"en\":\"contact\\/hello-world\",\"fr\":\"contact\\/hello-world\",\"nl\":\"contact\\/hello-world\"},\"updated_at\":\"2023-06-01 08:49:26\"}', '2023-06-01 07:49:26', '2023-06-01 07:49:26'),
(22, 8, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Hello World', NULL, 'pages', 'updated', '{\"parent_id\":2,\"position\":1,\"uri\":{\"en\":\"contact\\/hello-world\",\"fr\":\"contact\\/hello-world\",\"nl\":\"contact\\/hello-world\"},\"updated_at\":\"2023-06-01 08:49:26\"}', '{\"parent_id\":null,\"position\":3,\"uri\":{\"en\":\"hello-world\",\"fr\":\"hello-world\",\"nl\":\"hello-world\"},\"updated_at\":\"2023-06-01 08:49:29\"}', '2023-06-01 07:49:29', '2023-06-01 07:49:29'),
(23, 2, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"position\":3,\"updated_at\":\"2023-06-01 08:49:17\"}', '{\"position\":4,\"updated_at\":\"2023-06-01 08:49:29\"}', '2023-06-01 07:49:30', '2023-06-01 07:49:30'),
(24, 3, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Search results', NULL, 'pages', 'updated', '{\"position\":4,\"updated_at\":\"2023-06-01 08:49:18\"}', '{\"position\":5,\"updated_at\":\"2023-06-01 08:49:30\"}', '2023-06-01 07:49:30', '2023-06-01 07:49:30'),
(25, 4, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Terms and Conditions', NULL, 'pages', 'updated', '{\"position\":5,\"updated_at\":\"2023-06-01 08:49:18\"}', '{\"position\":6,\"updated_at\":\"2023-06-01 08:49:30\"}', '2023-06-01 07:49:30', '2023-06-01 07:49:30'),
(26, 5, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Privacy policy', NULL, 'pages', 'updated', '{\"position\":6,\"updated_at\":\"2023-06-01 08:49:18\"}', '{\"position\":7,\"updated_at\":\"2023-06-01 08:49:30\"}', '2023-06-01 07:49:30', '2023-06-01 07:49:30'),
(27, 6, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Cookie policy', NULL, 'pages', 'updated', '{\"position\":7,\"updated_at\":\"2023-06-01 08:49:18\"}', '{\"position\":8,\"updated_at\":\"2023-06-01 08:49:30\"}', '2023-06-01 07:49:30', '2023-06-01 07:49:30'),
(28, 1, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"parent_id\":null,\"updated_at\":\"2023-03-14 18:08:44\"}', '{\"parent_id\":7,\"updated_at\":\"2023-06-01 08:49:32\"}', '2023-06-01 07:49:32', '2023-06-01 07:49:32'),
(29, 1, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"parent_id\":7,\"uri\":{\"en\":\"home-1\",\"fr\":\"home-1\",\"nl\":\"home-1\"},\"updated_at\":\"2023-06-01 08:49:32\"}', '{\"parent_id\":8,\"uri\":{\"en\":\"hello-world-1\",\"fr\":\"hello-world-1\",\"nl\":\"hello-world-1\"},\"updated_at\":\"2023-06-01 08:49:45\"}', '2023-06-01 07:49:46', '2023-06-01 07:49:46'),
(30, 7, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"position\":2,\"updated_at\":\"2023-06-01 08:49:17\"}', '{\"position\":1,\"updated_at\":\"2023-06-01 08:49:52\"}', '2023-06-01 07:49:52', '2023-06-01 07:49:52'),
(31, 1, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"parent_id\":8,\"position\":1,\"uri\":{\"nl\":\"hello-world-1\"},\"updated_at\":\"2023-06-01 08:49:45\"}', '{\"parent_id\":null,\"position\":2,\"uri\":{\"nl\":\"\"},\"updated_at\":\"2023-06-01 08:49:52\"}', '2023-06-01 07:49:52', '2023-06-01 07:49:52'),
(32, 8, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Hello World', NULL, 'pages', 'deleted', '[]', '[]', '2023-06-01 08:17:12', '2023-06-01 08:17:12'),
(33, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'created', '[]', '{\"image_id\":null,\"module\":null,\"template\":null,\"is_home\":\"0\",\"private\":\"0\",\"redirect\":\"0\",\"css\":null,\"js\":null,\"title\":{\"en\":\"test\",\"nl\":null},\"uri\":{\"en\":\"test\"},\"status\":{\"en\":\"0\",\"fr\":\"0\",\"nl\":\"0\"},\"body\":{\"en\":\"<p>steastaeta<\\/p>\",\"nl\":null},\"meta_keywords\":{\"nl\":null},\"meta_description\":{\"nl\":null},\"slug\":{\"en\":\"test\",\"nl\":null},\"updated_at\":\"2023-06-01T09:17:56.000000Z\",\"created_at\":\"2023-06-01T09:17:56.000000Z\",\"id\":9}', '2023-06-01 08:17:56', '2023-06-01 08:17:56'),
(34, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'updated', '{\"status\":{\"en\":\"0\"},\"updated_at\":\"2023-06-01 09:17:56\"}', '{\"status\":{\"en\":1},\"updated_at\":\"2023-06-01 09:18:04\"}', '2023-06-01 08:18:04', '2023-06-01 08:18:04'),
(35, 2, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"position\":4,\"updated_at\":\"2023-06-01 08:49:29\"}', '{\"position\":3,\"updated_at\":\"2023-06-01 09:18:11\"}', '2023-06-01 08:18:11', '2023-06-01 08:18:11'),
(36, 3, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Search results', NULL, 'pages', 'updated', '{\"position\":5,\"updated_at\":\"2023-06-01 08:49:30\"}', '{\"position\":4,\"updated_at\":\"2023-06-01 09:18:11\"}', '2023-06-01 08:18:11', '2023-06-01 08:18:11'),
(37, 4, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Terms and Conditions', NULL, 'pages', 'updated', '{\"position\":6,\"updated_at\":\"2023-06-01 08:49:30\"}', '{\"position\":5,\"updated_at\":\"2023-06-01 09:18:11\"}', '2023-06-01 08:18:12', '2023-06-01 08:18:12'),
(38, 5, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Privacy policy', NULL, 'pages', 'updated', '{\"position\":7,\"updated_at\":\"2023-06-01 08:49:30\"}', '{\"position\":6,\"updated_at\":\"2023-06-01 09:18:12\"}', '2023-06-01 08:18:12', '2023-06-01 08:18:12'),
(39, 6, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Cookie policy', NULL, 'pages', 'updated', '{\"position\":8,\"updated_at\":\"2023-06-01 08:49:30\"}', '{\"position\":7,\"updated_at\":\"2023-06-01 09:18:12\"}', '2023-06-01 08:18:12', '2023-06-01 08:18:12'),
(40, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'updated', '{\"position\":0,\"updated_at\":\"2023-06-01 09:18:04\"}', '{\"position\":8,\"updated_at\":\"2023-06-01 09:18:12\"}', '2023-06-01 08:18:12', '2023-06-01 08:18:12'),
(41, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'updated', '{\"position\":8,\"updated_at\":\"2023-06-01 09:18:12\"}', '{\"position\":3,\"updated_at\":\"2023-06-01 09:19:08\"}', '2023-06-01 08:19:09', '2023-06-01 08:19:09'),
(42, 2, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"position\":3,\"updated_at\":\"2023-06-01 09:18:11\"}', '{\"position\":4,\"updated_at\":\"2023-06-01 09:19:09\"}', '2023-06-01 08:19:09', '2023-06-01 08:19:09'),
(43, 3, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Search results', NULL, 'pages', 'updated', '{\"position\":4,\"updated_at\":\"2023-06-01 09:18:11\"}', '{\"position\":5,\"updated_at\":\"2023-06-01 09:19:09\"}', '2023-06-01 08:19:09', '2023-06-01 08:19:09'),
(44, 4, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Terms and Conditions', NULL, 'pages', 'updated', '{\"position\":5,\"updated_at\":\"2023-06-01 09:18:11\"}', '{\"position\":6,\"updated_at\":\"2023-06-01 09:19:09\"}', '2023-06-01 08:19:09', '2023-06-01 08:19:09'),
(45, 5, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Privacy policy', NULL, 'pages', 'updated', '{\"position\":6,\"updated_at\":\"2023-06-01 09:18:12\"}', '{\"position\":7,\"updated_at\":\"2023-06-01 09:19:09\"}', '2023-06-01 08:19:09', '2023-06-01 08:19:09'),
(46, 6, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Cookie policy', NULL, 'pages', 'updated', '{\"position\":7,\"updated_at\":\"2023-06-01 09:18:12\"}', '{\"position\":8,\"updated_at\":\"2023-06-01 09:19:09\"}', '2023-06-01 08:19:09', '2023-06-01 08:19:09'),
(47, 2, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"position\":4,\"updated_at\":\"2023-06-01 09:19:09\"}', '{\"position\":3,\"updated_at\":\"2023-06-01 09:19:26\"}', '2023-06-01 08:19:27', '2023-06-01 08:19:27'),
(48, 3, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Search results', NULL, 'pages', 'updated', '{\"position\":5,\"updated_at\":\"2023-06-01 09:19:09\"}', '{\"position\":4,\"updated_at\":\"2023-06-01 09:19:27\"}', '2023-06-01 08:19:27', '2023-06-01 08:19:27'),
(49, 4, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Terms and Conditions', NULL, 'pages', 'updated', '{\"position\":6,\"updated_at\":\"2023-06-01 09:19:09\"}', '{\"position\":5,\"updated_at\":\"2023-06-01 09:19:27\"}', '2023-06-01 08:19:27', '2023-06-01 08:19:27'),
(50, 5, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Privacy policy', NULL, 'pages', 'updated', '{\"position\":7,\"updated_at\":\"2023-06-01 09:19:09\"}', '{\"position\":6,\"updated_at\":\"2023-06-01 09:19:27\"}', '2023-06-01 08:19:27', '2023-06-01 08:19:27'),
(51, 6, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Cookie policy', NULL, 'pages', 'updated', '{\"position\":8,\"updated_at\":\"2023-06-01 09:19:09\"}', '{\"position\":7,\"updated_at\":\"2023-06-01 09:19:27\"}', '2023-06-01 08:19:27', '2023-06-01 08:19:27'),
(52, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'updated', '{\"position\":3,\"updated_at\":\"2023-06-01 09:19:08\"}', '{\"position\":8,\"updated_at\":\"2023-06-01 09:19:28\"}', '2023-06-01 08:19:28', '2023-06-01 08:19:28'),
(53, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'updated', '{\"position\":8,\"updated_at\":\"2023-06-01 09:19:28\"}', '{\"position\":5,\"updated_at\":\"2023-06-01 09:19:33\"}', '2023-06-01 08:19:33', '2023-06-01 08:19:33'),
(54, 4, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Terms and Conditions', NULL, 'pages', 'updated', '{\"position\":5,\"updated_at\":\"2023-06-01 09:19:27\"}', '{\"position\":6,\"updated_at\":\"2023-06-01 09:19:33\"}', '2023-06-01 08:19:33', '2023-06-01 08:19:33'),
(55, 5, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Privacy policy', NULL, 'pages', 'updated', '{\"position\":6,\"updated_at\":\"2023-06-01 09:19:27\"}', '{\"position\":7,\"updated_at\":\"2023-06-01 09:19:34\"}', '2023-06-01 08:19:34', '2023-06-01 08:19:34'),
(56, 6, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Cookie policy', NULL, 'pages', 'updated', '{\"position\":7,\"updated_at\":\"2023-06-01 09:19:27\"}', '{\"position\":8,\"updated_at\":\"2023-06-01 09:19:34\"}', '2023-06-01 08:19:34', '2023-06-01 08:19:34'),
(57, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'updated', '{\"parent_id\":null,\"position\":5,\"uri\":{\"en\":\"test\",\"fr\":\"test\",\"nl\":\"test\"},\"updated_at\":\"2023-06-01 09:19:33\"}', '{\"parent_id\":6,\"position\":1,\"uri\":{\"en\":\"cookie-policy\\/test\",\"fr\":\"politique-des-cookies\\/test\",\"nl\":\"cookieverklaring\\/test\"},\"updated_at\":\"2023-06-01 09:19:36\"}', '2023-06-01 08:19:36', '2023-06-01 08:19:36'),
(58, 1, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'admin admin', NULL, 'users', 'updated', '{\"remember_token\":\"v2EUbKAnpL6GOFf2Lx1VZtdlHee6LIXFNkDNfl3712caoE3cH0RXCH2073SN\"}', '{\"remember_token\":\"UArBYejyf4YhvNodDAsy3L94GOp2LQDwqlSFcyPy2pijw6Oi7vJ6lenpF6Gi\"}', '2023-06-05 05:17:30', '2023-06-05 05:17:30'),
(59, 2, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'visitor visitor', NULL, 'users', 'created', '[]', '{\"email\":\"visitor@crecentech.com\",\"first_name\":\"visitor\",\"last_name\":\"visitor\",\"street\":null,\"number\":null,\"box\":null,\"postal_code\":null,\"city\":null,\"country\":null,\"phone\":null,\"locale\":null,\"activated\":\"1\",\"superuser\":\"0\",\"email_verified_at\":\"2023-06-05T10:13:09.489880Z\",\"api_token\":\"e28558a6-c250-4ae5-90ab-0532abb9d848\",\"updated_at\":\"2023-06-05T10:13:09.000000Z\",\"created_at\":\"2023-06-05T10:13:09.000000Z\",\"id\":2}', '2023-06-05 09:13:09', '2023-06-05 09:13:09'),
(60, 1, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'admin admin', NULL, 'users', 'updated', '{\"remember_token\":\"UArBYejyf4YhvNodDAsy3L94GOp2LQDwqlSFcyPy2pijw6Oi7vJ6lenpF6Gi\"}', '{\"remember_token\":\"XWvf5RJvBMhrL2MhEol3nk9xKiSRXzZb4K9LWTY0ApPPOmbz0ynw6w2Mx4Eu\"}', '2023-06-05 09:13:20', '2023-06-05 09:13:20'),
(61, 3, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'administrator administrator', NULL, 'users', 'created', '[]', '{\"email\":\"administrator@crecentech.com\",\"first_name\":\"administrator\",\"last_name\":\"administrator\",\"street\":null,\"number\":null,\"box\":null,\"postal_code\":null,\"city\":null,\"country\":null,\"phone\":null,\"locale\":null,\"activated\":\"1\",\"superuser\":\"0\",\"email_verified_at\":\"2023-06-05T10:17:33.584856Z\",\"api_token\":\"3cb0091f-66cf-4ac1-ab34-94eb4dd0c2ef\",\"updated_at\":\"2023-06-05T10:17:33.000000Z\",\"created_at\":\"2023-06-05T10:17:33.000000Z\",\"id\":3}', '2023-06-05 09:17:33', '2023-06-05 09:17:33'),
(62, 1, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'admin admin', NULL, 'users', 'updated', '{\"remember_token\":\"XWvf5RJvBMhrL2MhEol3nk9xKiSRXzZb4K9LWTY0ApPPOmbz0ynw6w2Mx4Eu\"}', '{\"remember_token\":\"wgN74RSTovuHZJz520lbmCVKbkolGd3T2CxP9syZRHpcObTtYBczTR8TbN9z\"}', '2023-06-05 09:17:57', '2023-06-05 09:17:57'),
(63, 7, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"parent_id\":null,\"uri\":{\"en\":\"home\",\"fr\":\"home\",\"nl\":\"home\"},\"updated_at\":\"2023-06-01 08:49:52\"}', '{\"parent_id\":9,\"uri\":{\"en\":\"cookie-policy\\/test\\/home\",\"fr\":\"politique-des-cookies\\/test\\/home\",\"nl\":\"cookieverklaring\\/test\\/home\"},\"updated_at\":\"2023-06-05 13:17:40\"}', '2023-06-05 12:17:40', '2023-06-05 12:17:40'),
(64, 1, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'admin admin', NULL, 'users', 'updated', '{\"preferences\":null,\"updated_at\":\"2023-03-14 18:09:05\"}', '{\"preferences\":\"{\\\"Pages_9_collapsed\\\":true}\",\"updated_at\":\"2023-06-05 13:17:44\"}', '2023-06-05 12:17:44', '2023-06-05 12:17:44'),
(65, 1, 'TypiCMS\\Modules\\Core\\Models\\User', 1, 'admin admin', NULL, 'users', 'updated', '{\"preferences\":\"{\\\"Pages_9_collapsed\\\":true}\"}', '{\"preferences\":\"{\\\"Pages_9_collapsed\\\":false}\"}', '2023-06-05 12:17:45', '2023-06-05 12:17:45'),
(66, 7, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"parent_id\":9,\"uri\":{\"en\":\"cookie-policy\\/test\\/home\",\"fr\":\"politique-des-cookies\\/test\\/home\",\"nl\":\"cookieverklaring\\/test\\/home\"},\"updated_at\":\"2023-06-05 13:17:40\"}', '{\"parent_id\":null,\"uri\":{\"en\":\"home\",\"fr\":\"home\",\"nl\":\"home\"},\"updated_at\":\"2023-06-05 13:18:04\"}', '2023-06-05 12:18:04', '2023-06-05 12:18:04'),
(67, 4, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Terms and Conditions', NULL, 'pages', 'updated', '{\"position\":6,\"updated_at\":\"2023-06-01 09:19:33\"}', '{\"position\":5,\"updated_at\":\"2023-06-05 13:18:04\"}', '2023-06-05 12:18:04', '2023-06-05 12:18:04'),
(68, 5, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Privacy policy', NULL, 'pages', 'updated', '{\"position\":7,\"updated_at\":\"2023-06-01 09:19:34\"}', '{\"position\":6,\"updated_at\":\"2023-06-05 13:18:04\"}', '2023-06-05 12:18:04', '2023-06-05 12:18:04'),
(69, 6, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'Cookie policy', NULL, 'pages', 'updated', '{\"position\":8,\"updated_at\":\"2023-06-01 09:19:34\"}', '{\"position\":7,\"updated_at\":\"2023-06-05 13:18:04\"}', '2023-06-05 12:18:04', '2023-06-05 12:18:04'),
(70, 9, 'TypiCMS\\Modules\\Core\\Models\\Page', 1, 'test', NULL, 'pages', 'updated', '{\"updated_at\":\"2023-06-01 09:19:36\"}', '{\"updated_at\":\"2023-06-05 13:18:05\"}', '2023-06-05 12:18:05', '2023-06-05 12:18:05'),
(71, 1, 'TypiCMS\\Modules\\Contacts\\Models\\Contact', 1, 'Adnan', NULL, 'contacts', 'updated', '{\"message\":\"Hi.\",\"updated_at\":\"2023-05-31 09:21:42\"}', '{\"message\":\"Hi.s\",\"updated_at\":\"2023-06-06 07:20:31\"}', '2023-06-06 06:20:31', '2023-06-06 06:20:31'),
(72, 2, 'App\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"status\":{\"en\":1},\"updated_at\":\"2023-06-01 09:19:26\"}', '{\"status\":{\"en\":0},\"updated_at\":\"2023-06-07 10:07:09\"}', '2023-06-07 09:07:09', '2023-06-07 09:07:09'),
(73, 2, 'App\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"status\":{\"en\":0},\"updated_at\":\"2023-06-07 10:07:09\"}', '{\"status\":{\"en\":1},\"updated_at\":\"2023-06-07 10:08:01\"}', '2023-06-07 09:08:01', '2023-06-07 09:08:01'),
(74, 1, 'App\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"position\":2,\"updated_at\":\"2023-06-01 08:49:52\"}', '{\"position\":1,\"updated_at\":\"2023-06-07 10:12:15\"}', '2023-06-07 09:12:15', '2023-06-07 09:12:15'),
(75, 2, 'App\\Models\\Page', 1, 'Contact', NULL, 'pages', 'updated', '{\"position\":3,\"updated_at\":\"2023-06-07 10:08:01\"}', '{\"position\":2,\"updated_at\":\"2023-06-07 10:12:15\"}', '2023-06-07 09:12:15', '2023-06-07 09:12:15'),
(76, 7, 'App\\Models\\Page', 1, 'Home', NULL, 'pages', 'updated', '{\"position\":1,\"updated_at\":\"2023-06-05 13:18:04\"}', '{\"position\":3,\"updated_at\":\"2023-06-07 10:12:15\"}', '2023-06-07 09:12:15', '2023-06-07 09:12:15'),
(77, 9, 'App\\Models\\Page', 1, 'test2eng', NULL, 'pages', 'updated', '{\"title\":{\"en\":\"test\"},\"body\":{\"en\":\"<p>steastaeta<\\/p>\"},\"status\":{\"en\":1,\"fr\":\"0\",\"nl\":\"0\"},\"updated_at\":\"2023-06-05 13:18:05\"}', '{\"title\":{\"en\":\"test2eng\"},\"body\":{\"en\":\"<p>ENsteastaeta<\\/p>\"},\"status\":{\"en\":\"1\",\"fr\":\"1\",\"nl\":\"1\"},\"updated_at\":\"2023-06-07 11:16:30\"}', '2023-06-07 10:16:30', '2023-06-07 10:16:30'),
(78, 1, 'App\\Models\\Role', 1, 'Administrator update', NULL, 'roles', 'updated', '{\"name\":\"administrator\",\"updated_at\":\"2023-03-14 18:08:44\"}', '{\"name\":\"Administrator update\",\"updated_at\":\"2023-06-08 08:57:53\"}', '2023-06-08 07:57:53', '2023-06-08 07:57:53'),
(79, 3, 'App\\Models\\Role', 1, 'Roles From API', NULL, 'roles', 'created', '[]', '{\"guard_name\":\"sanctum\",\"name\":\"Roles From API\",\"updated_at\":\"2023-06-08T09:07:58.000000Z\",\"created_at\":\"2023-06-08T09:07:58.000000Z\",\"id\":3}', '2023-06-08 08:07:58', '2023-06-08 08:07:58'),
(80, 3, 'App\\Models\\Role', 1, 'Administrator update2', NULL, 'roles', 'updated', '{\"name\":\"Roles From API\",\"updated_at\":\"2023-06-08 09:07:58\"}', '{\"name\":\"Administrator update2\",\"updated_at\":\"2023-06-08 09:52:54\"}', '2023-06-08 08:52:54', '2023-06-08 08:52:54'),
(81, 3, 'App\\Models\\Role', 1, 'Administrator update2', NULL, 'roles', 'deleted', '[]', '[]', '2023-06-08 08:55:34', '2023-06-08 08:55:34'),
(82, 4, 'App\\Models\\Role', 1, 'Administrator update2', NULL, 'roles', 'created', '[]', '{\"guard_name\":\"sanctum\",\"name\":\"Administrator update2\",\"updated_at\":\"2023-06-08T09:56:31.000000Z\",\"created_at\":\"2023-06-08T09:56:31.000000Z\",\"id\":4}', '2023-06-08 08:56:31', '2023-06-08 08:56:31'),
(83, 4, 'App\\Models\\Role', 1, 'Test Permission', NULL, 'roles', 'updated', '{\"name\":\"Administrator update2\",\"updated_at\":\"2023-06-08 09:56:31\"}', '{\"name\":\"Test Permission\",\"updated_at\":\"2023-06-08 09:58:08\"}', '2023-06-08 08:58:09', '2023-06-08 08:58:09'),
(84, 7, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php2047.tmp', NULL, 'files', 'created', '[]', '{\"folder_id\":\"2\",\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-12T07:38:38.000000Z\",\"created_at\":\"2023-06-12T07:38:38.000000Z\",\"id\":7,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-12 06:38:38', '2023-06-12 06:38:38'),
(85, 8, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php37EA.tmp', NULL, 'files', 'created', '[]', '{\"folder_id\":\"2\",\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-12T07:45:17.000000Z\",\"created_at\":\"2023-06-12T07:45:17.000000Z\",\"id\":8,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-12 06:45:17', '2023-06-12 06:45:17'),
(86, 9, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php976B.tmp', NULL, 'files', 'created', '[]', '{\"folder_id\":\"2\",\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-12T07:46:47.000000Z\",\"created_at\":\"2023-06-12T07:46:47.000000Z\",\"id\":9,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-12 06:46:47', '2023-06-12 06:46:47'),
(87, 10, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php8EBD.tmp', NULL, 'files', 'created', '[]', '{\"folder_id\":\"2\",\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-12T07:47:50.000000Z\",\"created_at\":\"2023-06-12T07:47:50.000000Z\",\"id\":10,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-12 06:47:50', '2023-06-12 06:47:50'),
(88, 11, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpD71C.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:03:59.000000Z\",\"created_at\":\"2023-06-13T06:03:59.000000Z\",\"id\":11,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:03:59', '2023-06-13 05:03:59'),
(89, 12, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php72E0.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:04:39.000000Z\",\"created_at\":\"2023-06-13T06:04:39.000000Z\",\"id\":12,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:04:39', '2023-06-13 05:04:39'),
(90, 13, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpDC6B.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:12:45.000000Z\",\"created_at\":\"2023-06-13T06:12:45.000000Z\",\"id\":13,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:12:45', '2023-06-13 05:12:45'),
(91, 14, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php15F5.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:18:27.000000Z\",\"created_at\":\"2023-06-13T06:18:27.000000Z\",\"id\":14,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:18:27', '2023-06-13 05:18:27'),
(92, 15, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php4EBD.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:36:10.000000Z\",\"created_at\":\"2023-06-13T06:36:10.000000Z\",\"id\":15,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:36:10', '2023-06-13 05:36:10'),
(93, 16, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php6285.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:36:15.000000Z\",\"created_at\":\"2023-06-13T06:36:15.000000Z\",\"id\":16,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:36:15', '2023-06-13 05:36:15'),
(94, 17, 'App\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php9E8.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:39:09.000000Z\",\"created_at\":\"2023-06-13T06:39:09.000000Z\",\"id\":17,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:39:09', '2023-06-13 05:39:09'),
(95, 18, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php5BF9.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:44:58.000000Z\",\"created_at\":\"2023-06-13T06:44:58.000000Z\",\"id\":18,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:44:58', '2023-06-13 05:44:58'),
(96, 19, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php4404.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:47:03.000000Z\",\"created_at\":\"2023-06-13T06:47:03.000000Z\",\"id\":19,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:47:04', '2023-06-13 05:47:04'),
(97, 20, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpBFA8.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:48:40.000000Z\",\"created_at\":\"2023-06-13T06:48:40.000000Z\",\"id\":20,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:48:40', '2023-06-13 05:48:40'),
(98, 21, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpF452.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:53:16.000000Z\",\"created_at\":\"2023-06-13T06:53:16.000000Z\",\"id\":21,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:53:16', '2023-06-13 05:53:16'),
(99, 22, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php2ED8.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:57:53.000000Z\",\"created_at\":\"2023-06-13T06:57:53.000000Z\",\"id\":22,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:57:53', '2023-06-13 05:57:53'),
(100, 23, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpDAB4.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T06:59:42.000000Z\",\"created_at\":\"2023-06-13T06:59:42.000000Z\",\"id\":23,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 05:59:42', '2023-06-13 05:59:42'),
(101, 24, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php59EB.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"en\":null},\"title\":{\"fr\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T07:06:48.000000Z\",\"created_at\":\"2023-06-13T07:06:48.000000Z\",\"id\":24,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-06-13 06:06:48', '2023-06-13 06:06:48'),
(102, 25, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpD0F0.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T07:07:19.000000Z\",\"created_at\":\"2023-06-13T07:07:19.000000Z\",\"id\":25,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-06-13 06:07:19', '2023-06-13 06:07:19'),
(103, 26, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpE351.tmp', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T07:07:23.000000Z\",\"created_at\":\"2023-06-13T07:07:23.000000Z\",\"id\":26,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-06-13 06:07:23', '2023-06-13 06:07:23'),
(104, 27, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T07:08:10.000000Z\",\"created_at\":\"2023-06-13T07:08:10.000000Z\",\"id\":27,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-06-13 06:08:10', '2023-06-13 06:08:10'),
(105, 27, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:02', '2023-06-13 06:11:02'),
(106, 26, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpE351.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:16', '2023-06-13 06:11:16'),
(107, 25, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpD0F0.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:30', '2023-06-13 06:11:30'),
(108, 24, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php59EB.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:36', '2023-06-13 06:11:36'),
(109, 23, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpDAB4.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:41', '2023-06-13 06:11:41'),
(110, 22, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php2ED8.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:44', '2023-06-13 06:11:44'),
(111, 21, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpF452.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:47', '2023-06-13 06:11:47'),
(112, 20, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpBFA8.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:11:56', '2023-06-13 06:11:56'),
(113, 19, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php4404.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:02', '2023-06-13 06:12:02'),
(114, 18, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php5BF9.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:08', '2023-06-13 06:12:08'),
(115, 17, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php9E8.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:13', '2023-06-13 06:12:13'),
(116, 16, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php6285.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:22', '2023-06-13 06:12:22'),
(117, 15, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php4EBD.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:26', '2023-06-13 06:12:26'),
(118, 14, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php15F5.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:32', '2023-06-13 06:12:32'),
(119, 13, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpDC6B.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:48', '2023-06-13 06:12:48'),
(120, 12, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php72E0.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:54', '2023-06-13 06:12:54'),
(121, 11, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\phpD71C.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:12:58', '2023-06-13 06:12:58'),
(122, 10, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php8EBD.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:13:02', '2023-06-13 06:13:02'),
(123, 9, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php976B.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:13:12', '2023-06-13 06:13:12'),
(124, 8, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php37EA.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:13:22', '2023-06-13 06:13:22'),
(125, 7, 'Modules\\Files\\Models\\File', 1, 'D:\\xampp-8.2.0\\tmp\\php2047.tmp', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:13:45', '2023-06-13 06:13:45'),
(126, 28, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T07:16:13.000000Z\",\"created_at\":\"2023-06-13T07:16:13.000000Z\",\"id\":28,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-06-13 06:16:13', '2023-06-13 06:16:13'),
(127, 28, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 06:16:38', '2023-06-13 06:16:38'),
(128, 30, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T07:33:07.000000Z\",\"created_at\":\"2023-06-13T07:33:07.000000Z\",\"id\":30,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-06-13 06:33:07', '2023-06-13 06:33:07'),
(129, 31, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T07:33:44.000000Z\",\"created_at\":\"2023-06-13T07:33:44.000000Z\",\"id\":31,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"http:\\/\\/localhost\\/storage\\/\"}', '2023-06-13 06:33:44', '2023-06-13 06:33:44'),
(130, 32, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T08:08:48.000000Z\",\"created_at\":\"2023-06-13T08:08:48.000000Z\",\"id\":32,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 07:08:48', '2023-06-13 07:08:48'),
(131, 33, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"name\":\"slide-1_7.png\",\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1_7.png\",\"type\":\"i\",\"updated_at\":\"2023-06-13T08:24:03.000000Z\",\"created_at\":\"2023-06-13T08:24:03.000000Z\",\"id\":33,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1_7-240x240-resize.png?token=cbd0c64677eeca43b42e0ac9904e310d\",\"url\":\"\\/storage\\/files\\/slide-1_7.png\"}', '2023-06-13 07:24:03', '2023-06-13 07:24:03'),
(132, 34, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"name\":\"slide-1.png\",\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1.png\",\"type\":\"i\",\"updated_at\":\"2023-06-13T08:24:27.000000Z\",\"created_at\":\"2023-06-13T08:24:27.000000Z\",\"id\":34,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1-240x240-resize.png?token=aa440aba748c5c99b5413ec4d8a12ce8\",\"url\":\"\\/storage\\/files\\/slide-1.png\"}', '2023-06-13 07:24:27', '2023-06-13 07:24:27'),
(133, 35, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"name\":\"office-365-case-studies-1-5.pdf\",\"filesize\":132400,\"mimetype\":\"application\\/pdf\",\"extension\":\"pdf\",\"width\":null,\"height\":null,\"path\":\"files\\/office-365-case-studies-1-5.pdf\",\"type\":\"d\",\"updated_at\":\"2023-06-13T08:26:24.000000Z\",\"created_at\":\"2023-06-13T08:26:24.000000Z\",\"id\":35,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/office-365-case-studies-1-5-240x240-resize.pdf?token=ecc3aa99128de6af7a7a5edf48bf2f63\",\"url\":\"\\/storage\\/files\\/office-365-case-studies-1-5.pdf\"}', '2023-06-13 07:26:24', '2023-06-13 07:26:24'),
(134, 29, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 07:27:35', '2023-06-13 07:27:35'),
(135, 30, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 07:27:38', '2023-06-13 07:27:38'),
(136, 31, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 07:27:45', '2023-06-13 07:27:45'),
(137, 32, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 07:27:50', '2023-06-13 07:27:50'),
(138, 33, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-13 07:27:55', '2023-06-13 07:27:55');
INSERT INTO `typicms_history` (`id`, `historable_id`, `historable_type`, `user_id`, `title`, `locale`, `historable_table`, `action`, `old`, `new`, `created_at`, `updated_at`) VALUES
(139, 36, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T08:44:40.000000Z\",\"created_at\":\"2023-06-13T08:44:40.000000Z\",\"id\":36,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 07:44:41', '2023-06-13 07:44:41'),
(140, 37, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T08:52:52.000000Z\",\"created_at\":\"2023-06-13T08:52:52.000000Z\",\"id\":37,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 07:52:52', '2023-06-13 07:52:52'),
(141, 38, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"name\":\"office-365-case-studies-1-5_4.pdf\",\"filesize\":132400,\"mimetype\":\"application\\/pdf\",\"extension\":\"pdf\",\"width\":null,\"height\":null,\"path\":\"files\\/office-365-case-studies-1-5_4.pdf\",\"type\":\"d\",\"updated_at\":\"2023-06-13T09:01:59.000000Z\",\"created_at\":\"2023-06-13T09:01:59.000000Z\",\"id\":38,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/office-365-case-studies-1-5_4-240x240-resize.pdf?token=a3a53f1c9b78ee29cfb907ff45e00495\",\"url\":\"\\/storage\\/files\\/office-365-case-studies-1-5_4.pdf\"}', '2023-06-13 08:01:59', '2023-06-13 08:01:59'),
(142, 40, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"name\":\"slide-1_1.png\",\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1_1.png\",\"type\":\"i\",\"updated_at\":\"2023-06-13T09:04:34.000000Z\",\"created_at\":\"2023-06-13T09:04:34.000000Z\",\"id\":40,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1_1-240x240-resize.png?token=937f0ef7f277e6d2d4a0c49d6dd801ac\",\"url\":\"\\/storage\\/files\\/slide-1_1.png\"}', '2023-06-13 08:04:35', '2023-06-13 08:04:35'),
(143, 41, 'Modules\\Files\\Models\\File', 1, 'asdf', NULL, 'files', 'created', '[]', '{\"folder_id\":null,\"type\":\"f\",\"name\":\"asdf\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-13T09:11:34.000000Z\",\"created_at\":\"2023-06-13T09:11:34.000000Z\",\"id\":41,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 08:11:35', '2023-06-13 08:11:35'),
(144, 42, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T09:34:22.000000Z\",\"created_at\":\"2023-06-13T09:34:22.000000Z\",\"id\":42,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 08:34:23', '2023-06-13 08:34:23'),
(145, 10, 'App\\Models\\Page', 1, 'test page', NULL, 'pages', 'created', '[]', '{\"image_id\":null,\"module\":null,\"template\":null,\"is_home\":\"0\",\"private\":\"0\",\"redirect\":\"0\",\"css\":null,\"js\":null,\"title\":{\"en\":\"test page\",\"fr\":\"test page\",\"nl\":null},\"uri\":{\"nl\":null},\"status\":{\"nl\":null},\"body\":{\"nl\":null},\"meta_keywords\":{\"nl\":null},\"meta_description\":{\"nl\":null},\"slug\":{\"en\":\"test-page\",\"fr\":\"test-page\",\"nl\":null},\"updated_at\":\"2023-06-13T12:08:41.000000Z\",\"created_at\":\"2023-06-13T12:08:41.000000Z\",\"id\":10}', '2023-06-13 11:08:41', '2023-06-13 11:08:41'),
(146, 43, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T13:48:12.000000Z\",\"created_at\":\"2023-06-13T13:48:12.000000Z\",\"id\":43,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 12:48:12', '2023-06-13 12:48:12'),
(147, 44, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T13:51:28.000000Z\",\"created_at\":\"2023-06-13T13:51:28.000000Z\",\"id\":44,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 12:51:28', '2023-06-13 12:51:28'),
(148, 45, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T13:51:31.000000Z\",\"created_at\":\"2023-06-13T13:51:31.000000Z\",\"id\":45,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 12:51:31', '2023-06-13 12:51:31'),
(149, 46, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T13:57:41.000000Z\",\"created_at\":\"2023-06-13T13:57:41.000000Z\",\"id\":46,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 12:57:41', '2023-06-13 12:57:41'),
(150, 47, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T14:05:56.000000Z\",\"created_at\":\"2023-06-13T14:05:56.000000Z\",\"id\":47,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 13:05:56', '2023-06-13 13:05:56'),
(151, 48, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T14:06:00.000000Z\",\"created_at\":\"2023-06-13T14:06:00.000000Z\",\"id\":48,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 13:06:00', '2023-06-13 13:06:00'),
(152, 49, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-13T14:10:00.000000Z\",\"created_at\":\"2023-06-13T14:10:00.000000Z\",\"id\":49,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-13 13:10:00', '2023-06-13 13:10:00'),
(153, 51, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-14T06:44:37.000000Z\",\"created_at\":\"2023-06-14T06:44:37.000000Z\",\"id\":51,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-14 05:44:38', '2023-06-14 05:44:38'),
(154, 52, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-14T06:53:48.000000Z\",\"created_at\":\"2023-06-14T06:53:48.000000Z\",\"id\":52,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-14 05:53:48', '2023-06-14 05:53:48'),
(155, 53, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-14T06:57:14.000000Z\",\"created_at\":\"2023-06-14T06:57:14.000000Z\",\"id\":53,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-14 05:57:14', '2023-06-14 05:57:14'),
(156, 54, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-14T07:09:53.000000Z\",\"created_at\":\"2023-06-14T07:09:53.000000Z\",\"id\":54,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-14 06:09:53', '2023-06-14 06:09:53'),
(157, 55, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-14T07:14:35.000000Z\",\"created_at\":\"2023-06-14T07:14:35.000000Z\",\"id\":55,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-14 06:14:35', '2023-06-14 06:14:35'),
(158, 56, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":{},\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"updated_at\":\"2023-06-14T07:18:32.000000Z\",\"created_at\":\"2023-06-14T07:18:32.000000Z\",\"id\":56,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-14 06:18:32', '2023-06-14 06:18:32'),
(159, 57, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":\"slide-1_2.png\",\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1_2.png\",\"type\":\"i\",\"updated_at\":\"2023-06-14T07:22:47.000000Z\",\"created_at\":\"2023-06-14T07:22:47.000000Z\",\"id\":57,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1_2-240x240-resize.png?token=a0d35ced104ea50735edbc579f72d31e\",\"url\":\"\\/storage\\/files\\/slide-1_2.png\"}', '2023-06-14 06:22:47', '2023-06-14 06:22:47'),
(160, 58, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":\"slide-1.png\",\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1.png\",\"type\":\"i\",\"updated_at\":\"2023-06-14T07:26:19.000000Z\",\"created_at\":\"2023-06-14T07:26:19.000000Z\",\"id\":58,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1-240x240-resize.png?token=aa440aba748c5c99b5413ec4d8a12ce8\",\"url\":\"\\/storage\\/files\\/slide-1.png\"}', '2023-06-14 06:26:19', '2023-06-14 06:26:19'),
(161, 58, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:28:15', '2023-06-14 06:28:15'),
(162, 57, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:28:25', '2023-06-14 06:28:25'),
(163, 56, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:28:33', '2023-06-14 06:28:33'),
(164, 55, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:28:41', '2023-06-14 06:28:41'),
(165, 54, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:28:53', '2023-06-14 06:28:53'),
(166, 53, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:28:57', '2023-06-14 06:28:57'),
(167, 52, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:01', '2023-06-14 06:29:01'),
(168, 51, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:07', '2023-06-14 06:29:07'),
(169, 50, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:13', '2023-06-14 06:29:13'),
(170, 42, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:28', '2023-06-14 06:29:28'),
(171, 43, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:32', '2023-06-14 06:29:32'),
(172, 44, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:35', '2023-06-14 06:29:35'),
(173, 45, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:39', '2023-06-14 06:29:39'),
(174, 46, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:43', '2023-06-14 06:29:43'),
(175, 47, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:46', '2023-06-14 06:29:46'),
(176, 48, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:50', '2023-06-14 06:29:50'),
(177, 49, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:29:54', '2023-06-14 06:29:54'),
(178, 34, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:30:37', '2023-06-14 06:30:37'),
(179, 35, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:30:42', '2023-06-14 06:30:42'),
(180, 36, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:30:47', '2023-06-14 06:30:47'),
(181, 37, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:30:52', '2023-06-14 06:30:52'),
(182, 38, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:31:00', '2023-06-14 06:31:00'),
(183, 39, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:31:04', '2023-06-14 06:31:04'),
(184, 40, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:31:10', '2023-06-14 06:31:10'),
(185, 41, 'Modules\\Files\\Models\\File', 1, 'asdf', NULL, 'files', 'deleted', '[]', '[]', '2023-06-14 06:31:16', '2023-06-14 06:31:16'),
(186, 59, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":\"slide-1_1.png\",\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1_1.png\",\"type\":\"i\",\"updated_at\":\"2023-06-14T07:37:22.000000Z\",\"created_at\":\"2023-06-14T07:37:22.000000Z\",\"id\":59,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1_1-240x240-resize.png?token=937f0ef7f277e6d2d4a0c49d6dd801ac\",\"url\":\"\\/storage\\/files\\/slide-1_1.png\"}', '2023-06-14 06:37:22', '2023-06-14 06:37:22'),
(187, 60, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":\"slide-1_2.png\",\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1_2.png\",\"type\":\"i\",\"updated_at\":\"2023-06-14T07:37:25.000000Z\",\"created_at\":\"2023-06-14T07:37:25.000000Z\",\"id\":60,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1_2-240x240-resize.png?token=a0d35ced104ea50735edbc579f72d31e\",\"url\":\"\\/storage\\/files\\/slide-1_2.png\"}', '2023-06-14 06:37:25', '2023-06-14 06:37:25'),
(188, 61, 'Modules\\Files\\Models\\File', 1, 'asdf', NULL, 'files', 'created', '[]', '{\"folder_id\":null,\"type\":\"f\",\"name\":\"asdf\",\"alt_attribute\":{\"en\":null},\"title\":{\"en\":null},\"description\":{\"en\":null},\"updated_at\":\"2023-06-14T07:46:31.000000Z\",\"created_at\":\"2023-06-14T07:46:31.000000Z\",\"id\":61,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/.\\/storage-240x240-resize?token=3e6f15cec36a627c1efed8ca2f635445\",\"url\":\"\\/storage\\/\"}', '2023-06-14 06:46:31', '2023-06-14 06:46:31'),
(189, 62, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":\"slide-1_3.png\",\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1_3.png\",\"type\":\"i\",\"updated_at\":\"2023-06-14T07:51:00.000000Z\",\"created_at\":\"2023-06-14T07:51:00.000000Z\",\"id\":62,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1_3-240x240-resize.png?token=4b0d91d003a409542e1633732255fcf4\",\"url\":\"\\/storage\\/files\\/slide-1_3.png\"}', '2023-06-14 06:51:00', '2023-06-14 06:51:00'),
(190, 63, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'created', '[]', '{\"name\":\"slide-1.png\",\"alt_attribute\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"title\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"description\":{\"nl\":\"hello\",\"fr\":\"hello\",\"en\":\"hello\"},\"filesize\":137760,\"mimetype\":\"image\\/png\",\"extension\":\"png\",\"width\":500,\"height\":350,\"path\":\"files\\/slide-1.png\",\"type\":\"i\",\"updated_at\":\"2023-06-14T08:02:47.000000Z\",\"created_at\":\"2023-06-14T08:02:47.000000Z\",\"id\":63,\"thumb_sm\":\"http:\\/\\/localhost\\/admin_laravel\\/public\\/storage\\/files\\/slide-1-240x240-resize.png?token=aa440aba748c5c99b5413ec4d8a12ce8\",\"url\":\"\\/storage\\/files\\/slide-1.png\"}', '2023-06-14 07:02:47', '2023-06-14 07:02:47'),
(191, 63, 'Modules\\Files\\Models\\File', 1, 'hello', NULL, 'files', 'updated', '{\"name\":\"slide-1.png\",\"updated_at\":\"2023-06-14 08:02:47\"}', '{\"name\":\"Hello.png\",\"updated_at\":\"2023-06-14 08:36:13\"}', '2023-06-14 07:36:13', '2023-06-14 07:36:13'),
(192, 364, 'App\\Models\\User', NULL, '12.Adnan ', NULL, 'users', 'created', '[]', '{\"first_name\":\"12.Adnan\",\"email\":\"12.ahmad@crecentech.com\",\"last_name\":null,\"phone\":null,\"street\":null,\"number\":null,\"box\":null,\"postal_code\":null,\"city\":null,\"country\":null,\"locale\":null,\"activated\":\"1\",\"superuser\":\"0\",\"updated_at\":\"2023-07-26T13:12:19.000000Z\",\"created_at\":\"2023-07-26T13:12:19.000000Z\",\"id\":364}', '2023-07-26 12:12:19', '2023-07-26 12:12:19'),
(193, 365, 'App\\Models\\User', NULL, '13.Adnan ', NULL, 'users', 'created', '[]', '{\"email\":\"13.ahmad@crecentech.com\",\"first_name\":\"13.Adnan\",\"updated_at\":\"2023-07-26T13:44:12.000000Z\",\"created_at\":\"2023-07-26T13:44:12.000000Z\",\"id\":365}', '2023-07-26 12:44:12', '2023-07-26 12:44:12'),
(194, 366, 'App\\Models\\User', NULL, '14.Adnan ', NULL, 'users', 'created', '[]', '{\"email\":\"14.ahmad@crecentech.com\",\"first_name\":\"14.Adnan\",\"updated_at\":\"2023-07-26T13:46:57.000000Z\",\"created_at\":\"2023-07-26T13:46:57.000000Z\",\"id\":366}', '2023-07-26 12:46:57', '2023-07-26 12:46:57'),
(195, 5, 'App\\Models\\User', 1, '5.Adnan-update Ahmad', NULL, 'users', 'updated', '{\"first_name\":\"5.Adnan\",\"updated_at\":\"2023-06-08 12:46:04\"}', '{\"first_name\":\"5.Adnan-update\",\"updated_at\":\"2023-07-31 09:09:26\"}', '2023-07-31 08:09:27', '2023-07-31 08:09:27'),
(196, 4, 'App\\Models\\User', 1, 'c.Adnan ', NULL, 'users', 'deleted', '[]', '[]', '2023-07-31 08:22:11', '2023-07-31 08:22:11'),
(197, 1, 'App\\Models\\Role', 1, 'Test Permission', NULL, 'roles', 'updated', '{\"name\":\"Administrator update\",\"updated_at\":\"2023-06-08 08:57:53\"}', '{\"name\":\"Test Permission\",\"updated_at\":\"2023-07-31 13:46:11\"}', '2023-07-31 12:46:11', '2023-07-31 12:46:11'),
(198, 4, 'App\\Models\\Role', 1, 'Test Permissions', NULL, 'roles', 'updated', '{\"name\":\"Test Permission\",\"updated_at\":\"2023-06-08 09:58:08\"}', '{\"name\":\"Test Permissions\",\"updated_at\":\"2023-07-31 13:49:48\"}', '2023-07-31 12:49:48', '2023-07-31 12:49:48'),
(199, 1, 'App\\Models\\Role', 1, 'Top Administraotor', NULL, 'roles', 'updated', '{\"name\":\"Test Permission\",\"guard_name\":\"web\",\"updated_at\":\"2023-07-31 13:46:11\"}', '{\"name\":\"Top Administraotor\",\"guard_name\":\"sanctum\",\"updated_at\":\"2023-07-31 13:51:28\"}', '2023-07-31 12:51:29', '2023-07-31 12:51:29'),
(200, 1, 'App\\Models\\Role', 1, 'Top Administraotors', NULL, 'roles', 'updated', '{\"name\":\"Top Administraotor\",\"updated_at\":\"2023-07-31 13:51:28\"}', '{\"name\":\"Top Administraotors\",\"updated_at\":\"2023-07-31 13:53:16\"}', '2023-07-31 12:53:16', '2023-07-31 12:53:16');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_instances`
--

CREATE TABLE `typicms_instances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instance` varchar(255) NOT NULL,
  `db_host` blob NOT NULL,
  `db_port` blob NOT NULL,
  `db_username` blob NOT NULL,
  `db_password` blob NOT NULL,
  `db_driver` blob NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_instances`
--

INSERT INTO `typicms_instances` (`id`, `instance`, `db_host`, `db_port`, `db_username`, `db_password`, `db_driver`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'instance 1', 0x28523a9a8163c6b35f5f6c69afb6fe7a, 0xa587a8ed46be17dd5283c666383afed5, 0x0bc18b9a1a9e848dbb116fa7b3b9220b, 0xa2016f8950fe1e434d8521a281e7e241, 0x9378555fab314cdb93411b9dc45795a2, NULL, '2023-03-15 05:19:02', '2023-03-15 05:19:02');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_menulinks`
--

CREATE TABLE `typicms_menulinks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `page_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `target` varchar(10) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`url`)),
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`description`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_menulinks`
--

INSERT INTO `typicms_menulinks` (`id`, `menu_id`, `page_id`, `section_id`, `parent_id`, `image_id`, `position`, `target`, `class`, `status`, `title`, `url`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, 1, NULL, NULL, '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Home\", \"fr\": \"Accueil\", \"nl\": \"Home\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(2, 1, 2, NULL, NULL, NULL, 2, NULL, NULL, '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Contact\", \"fr\": \"Contact\", \"nl\": \"Contact\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(3, 2, 2, NULL, NULL, NULL, 1, NULL, NULL, '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Contact\", \"fr\": \"Contact\", \"nl\": \"Contact\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(4, 3, NULL, NULL, NULL, NULL, 1, '_blank', 'btn-facebook', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Facebook\", \"fr\": \"Facebook\", \"nl\": \"Facebook\"}', '{\"en\": \"https://www.facebook.com\", \"fr\": \"https://www.facebook.com\", \"nl\": \"https://www.facebook.com\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(5, 3, NULL, NULL, NULL, NULL, 2, '_blank', 'btn-linkedin', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"LinkedIn\", \"fr\": \"LinkedIn\", \"nl\": \"LinkedIn\"}', '{\"en\": \"https://www.linkedin.com\", \"fr\": \"https://www.linkedin.com\", \"nl\": \"https://www.linkedin.com\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(6, 3, NULL, NULL, NULL, NULL, 3, '_blank', 'btn-twitter', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Twitter\", \"fr\": \"Twitter\", \"nl\": \"Twitter\"}', '{\"en\": \"https://twitter.com\", \"fr\": \"https://twitter.com\", \"nl\": \"https://twitter.com\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(7, 3, NULL, NULL, NULL, NULL, 4, '_blank', 'btn-instagram', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Instagram\", \"fr\": \"Instagram\", \"nl\": \"Instagram\"}', '{\"en\": \"https://www.instagram.com\", \"fr\": \"https://www.instagram.com\", \"nl\": \"https://www.instagram.com\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(8, 3, NULL, NULL, NULL, NULL, 5, '_blank', 'btn-youtube', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"YouTube\", \"fr\": \"YouTube\", \"nl\": \"YouTube\"}', '{\"en\": \"https://www.youtube.com\", \"fr\": \"https://www.youtube.com\", \"nl\": \"https://www.youtube.com\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(9, 4, 3, NULL, NULL, NULL, 0, NULL, NULL, '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Terms and conditions\", \"fr\": \"Conditions générales\", \"nl\": \"Algemene Voorwaarden\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(10, 4, 4, NULL, NULL, NULL, 0, NULL, NULL, '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Privacy policy\", \"fr\": \"Charte vie privée\", \"nl\": \"Privacyverklaring\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(11, 4, 5, NULL, NULL, NULL, 0, NULL, NULL, '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": \"Cookie policy\", \"fr\": \"Politique des cookies\", \"nl\": \"Cookieverklaring\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', '2023-03-14 17:08:44', '2023-03-14 17:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_menus`
--

CREATE TABLE `typicms_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_menus`
--

INSERT INTO `typicms_menus` (`id`, `image_id`, `name`, `class`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'primary', NULL, '{\"fr\":\"1\",\"en\":\"1\",\"nl\":\"1\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(2, NULL, 'footer', NULL, '{\"fr\":\"1\",\"en\":\"1\",\"nl\":\"1\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(3, NULL, 'social', NULL, '{\"fr\":\"1\",\"en\":\"1\",\"nl\":\"1\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(4, NULL, 'legal', NULL, '{\"fr\": 1, \"en\": 1, \"nl\": 1}', '2023-03-14 17:08:44', '2023-03-14 17:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_migrations`
--

CREATE TABLE `typicms_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_migrations`
--

INSERT INTO `typicms_migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2023_03_14_113037_create_permission_tables', 1),
(3, '2023_03_14_113038_create_files_table', 1),
(4, '2023_03_14_113039_create_pages_tables', 1),
(5, '2023_03_14_113040_create_blocks_table', 1),
(6, '2023_03_14_113041_create_settings_table', 1),
(7, '2023_03_14_113042_create_history_table', 1),
(8, '2023_03_14_113043_create_users_table', 1),
(9, '2023_03_14_113044_create_password_resets_table', 1),
(10, '2023_03_14_113045_create_model_has_files_table', 1),
(11, '2023_03_14_113046_create_menus_tables', 1),
(12, '2023_03_14_113047_create_tags_table', 1),
(13, '2023_03_14_113048_create_taxonomies_tables', 1),
(14, '2023_03_14_113049_create_translations_table', 1),
(15, '2023_03_14_115153_create_news_table', 1),
(16, '2023_03_14_122410_create_project_categories_table', 2),
(17, '2023_03_14_122410_create_projects_table', 2),
(18, '2023_03_15_060552_create_events_table', 3),
(19, '2023_03_15_060552_create_registrations_table', 3),
(20, '2023_03_15_061038_create_contacts_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `typicms_model_has_files`
--

CREATE TABLE `typicms_model_has_files` (
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `position` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_model_has_permissions`
--

CREATE TABLE `typicms_model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_model_has_roles`
--

CREATE TABLE `typicms_model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_model_has_roles`
--

INSERT INTO `typicms_model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'TypiCMS\\Modules\\Core\\Models\\User', 3),
(2, 'TypiCMS\\Modules\\Core\\Models\\User', 2),
(4, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `typicms_model_has_terms`
--

CREATE TABLE `typicms_model_has_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_news`
--

CREATE TABLE `typicms_news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `summary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`summary`)),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`body`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_notification_companies`
--

CREATE TABLE `typicms_notification_companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL COMMENT 'Set company id',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_pages`
--

CREATE TABLE `typicms_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `private` tinyint(1) NOT NULL DEFAULT 0,
  `is_home` tinyint(1) NOT NULL DEFAULT 0,
  `redirect` tinyint(1) NOT NULL DEFAULT 0,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `uri` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`uri`)),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`body`)),
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`meta_keywords`)),
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`meta_description`)),
  `css` text DEFAULT NULL,
  `js` text DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_pages`
--

INSERT INTO `typicms_pages` (`id`, `image_id`, `parent_id`, `position`, `private`, `is_home`, `redirect`, `title`, `slug`, `uri`, `body`, `status`, `meta_keywords`, `meta_description`, `css`, `js`, `module`, `template`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 1, 0, 1, 0, '{\"en\": \"Home\", \"fr\": \"Accueil\", \"nl\": \"Home\"}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"nl\":\"\"}', '{\"en\": \"\", \"fr\": \"\", \"nl\": \"\"}', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', NULL, NULL, NULL, 'home', '2023-03-14 17:08:44', '2023-06-07 09:12:15'),
(2, NULL, NULL, 2, 0, 0, 0, '{\"en\": \"Contact\", \"fr\": \"Contact\", \"nl\": \"Contact\"}', '{\"en\": \"contact\", \"fr\": \"contact\", \"nl\": \"contact\"}', '{\"en\": \"contact\", \"fr\": \"contact\", \"nl\": \"contact\"}', '{\"en\": \"\", \"fr\": \"\", \"nl\": \"\"}', '{\"en\":1,\"fr\":1,\"nl\":1}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', NULL, NULL, NULL, NULL, '2023-03-14 17:08:44', '2023-06-07 09:12:15'),
(3, NULL, NULL, 4, 0, 0, 0, '{\"en\": \"Search results\", \"fr\": \"Résultats de recherche\", \"nl\": \"Zoekresultaten\"}', '{\"en\": \"search-results\", \"fr\": \"resultats-de-recherche\", \"nl\": \"zoekresultaten\"}', '{\"en\": \"search-results\", \"fr\": \"resultats-de-recherche\", \"nl\": \"zoekresultaten\"}', '{\"en\": \"\", \"fr\": \"\", \"nl\": \"\"}', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', NULL, NULL, 'search', NULL, '2023-03-14 17:08:44', '2023-06-01 08:19:27'),
(4, NULL, NULL, 5, 0, 0, 0, '{\"en\": \"Terms and Conditions\", \"fr\": \"Conditions générales\", \"nl\": \"Algemene Voorwaarden\"}', '{\"en\": \"terms-and-conditions\", \"fr\": \"conditions-generales\", \"nl\": \"algemene-voorwaarden\"}', '{\"en\": \"terms-and-conditions\", \"fr\": \"conditions-generales\", \"nl\": \"algemene-voorwaarden\"}', '{\"en\": \"<p>The use of our website always goes hand in hand with some rights and obligations. These are defined in our Terms of Sale, in our Privacy Policy, in our Cookie Statement and in the present Disclaimer (together, the “Terms and Conditions”).</p><p>The Terms and Conditions apply both to us, [X], as to you, the user. By using our website, you expressly acknowledge and accept the exclusive application of our Terms and Conditions and you expressly renounce your right to invoke your own terms and conditions.</p><p>We may deviate from these Terms and Conditions in some exceptional situations, but only to the extent that the deviations are accepted by each party and stated explicitly in writing. These deviations only replace or supplement the clauses to which they relate. They shall not affect the application of any other provisions of these Terms and Conditions.</p><h2>1. Who we are</h2><p>The website URL (hereafter: the “Website”) is an initiative of:</p><p>(naam en rechtsvorm onderneming) (hereafter: “[X]” and “We”)<br />(Adres maatschappelijke zetel)<br />(Postcode + gemeente/stad)<br />(Land)<br />VAT BE (ondernemingsnummer)<br />Email:<br />Phone:</p><p>Feel free to contact us should you have further questions or remarks. We promise to reply soon!</p><h2>2. Our Website</h2><h3>2.1 Proper functioning, safety and accessibility</h3><p>You can rest assured; we offer a user-friendly Website that is safe for every user. We take all reasonable and necessary measures to ensure the proper functioning, safety and accessibility of our Website. Yet we cannot give you an absolute guarantee on this matter. We are bound by an obligation of means only.</p><p>Any use of the Website is entirely at your own risk. We are not liable for damages resulting from malfunctions, interruptions, defects, harmful elements or other problems on or within our website, regardless of the existence of force majeure or other extraneous events.</p><p>We have the right to restrict and/or interrupt fully or partially the access to our Website, at any time and without prior warning. We will only take such measures if this is justified by the circumstances, without this being in any way a condition to be covered.</p><h3>2.2 Content on our Website</h3><p>We largely determine which content is available on our Website. We apply great care in this respect and make every effort to provide high quality information. We therefore take all necessary steps to keep our Website as complete, accurate and current as possible, even when the information is provided by third parties. We are always permitted to change, add or delete the content on our Website.</p><p>Despite our considerable attention, we are not able to guarantee the quality of the information available on our Website. It is possible that the information is not correct, not sufficiently accurate and/or not useful. We are not liable for (direct and indirect) damages which the user may suffer as a result of the information on our Website.</p><p>We ask you to notify us as soon as possible if you notice the content on our Website violates applicable laws and/or third party rights or is simply not acceptable. We will then take all the appropriate measures, which can include the partial or total removal of the information.</p><p>Our Website contains content that can be downloaded. You understand and agree that every download from our Website is at your own risk and that damages resulting from loss of data or damage to the computer system are your entire and sole responsibility.</p><h3>2.3 What we expect from you as a User</h3><p>The user bears some responsibility for the way we offer our Website. This means that you should refrain from acts that have a deleterious impact on the proper operation and security of the Website or on its use. For example, the Website cannot be used to circumvent our business model and/or to gather information from other users.</p><p>It is therefore forbidden to distribute content via our Website that (may) damage(s) other users of the Website. We may think at the spread of malicious software, computer viruses, malware, worms, trojans and cancelbots. The proliferation of unsolicited and/or commercial messages via the Website, such as junk mail, spam and chain letters, is also targeted.</p><p>We reserve the right to take all necessary (judicial and extrajudicial) actions that may offer appropriate remedies to the affected parties. The user is solely responsible for all actions exerted on the Website that cause damages to the Website and/or to other users. If this occurs, the user has the obligation to keep [X] harmless and indemnified from all claims that may arise.</p><h2>3. Links to other websites</h2><p>Our Website may contain or provide hyperlinks or pointed to other websites and/or electronic communication portals maintained by third parties or may provide third party content on our Website by framing or other methods. Such a reference being made on our Website does not mean that there is any connection between our Website and these third-party websites nor that we (implicitly) agree with the content of those sites.</p><p>We do not guarantee or assume any liability for the accuracy, legality, completeness or quality of the content of external websites linked to on our Website or of other electronic communications portals that are not under our actual control. These references are therefore to click at your own risk and responsibility. We are not liable for any damage resulting therefrom.</p><p>These external websites do not offer the same guarantees as we do. We therefor recommend you to carefully read the terms and conditions and privacy statement of these other websites.</p><h2>4. Intellectual Property Rights</h2><p>Creativity deserves protection; so does our Website and its content. Such protection is provided by intellectual property rights and is entitled to all parties, i.e. [X] and third parties. Content means the very broad category of photos, video, audio, text, ideas, notes, drawings, articles, et cetera. This content is protected by copyright, software rights, database rights, designs and models rights, and other applicable (intellectual property) rights. The technical nature of our Website is protected by copyright and by the rights on software and databases. Each trade name that we use on our Website is protected by trademark law.</p><p>Each user receives a limited right to access, use and display our Website and its content. This right is granted in a non-exclusive and non-transferable manner and can only be used within a personal, non-commercial context. We ask our users not to create nor to bring changes to the intellectual property rights as described in this article without the consent of the owner. [X] attaches great importance to its intellectual property rights and has taken all possible measures to ensure its protection. We will take legal actions against any infringement of existing intellectual property rights.</p><h2>5. Processing of personal data</h2><p>The personal information you provide is necessary for a good service. Entering incorrect or false personal data is considered a violation of our Terms and Conditions. User’s personal data are exclusively processed in accordance with the applicable Privacy Policy which can be consulted via our Website.</p><h2>6. General provisions</h2><p>We reserve the right to change, limit or discontinue our Website and related services at any time and to any extent. We may do so without noticing the user. This does not give rise to any form of compensation.</p><p>These Terms and Conditions shall be exclusively governed by and interpreted in accordance with Belgian law. Any dispute arising under or relating to the services of [X] shall come under the jurisdiction of the competent court of the judicial district [XXX].</p><p>If a provision of these Terms and Conditions is deemed invalid, the invalidity of such provision shall not affect the validity and enforceability of the remaining provisions of these Terms and Conditions, which shall remain in full force and effect. We retain the right to propose a valid modification of the disputed clause(s).</p>\", \"fr\": \"<p>L’utilisation de notre site internet est assortie des droits et obligations mentionnées explicitement sur notre le site mais aussi des droits et obligations définis dans le présent disclaimer ainsi que dans nos conditions générales de vente et dans notre charte vie privée. L’ensemble de ces textes constitue nos «&nbsp;Conditions Générales».</p><p>Ces Conditions Générales s’appliquent aussi bien à nous qu’à vous. En utilisant notre site internet, vous reconnaissez et acceptez explicitement l’application de nos Conditions Générales, à l’exclusion de Conditions Générales propres.</p><p>Il peut exceptionnellement être dérogé aux dispositions des Conditions Générales dans la mesure où ces dérogations ont fait l’objet d’un accord écrit entre les parties. Ces dérogations peuvent consister en la modification, l’ajout ou la suppression des clauses auxquelles elles se rapportent et n’ont aucune incidence sur l’application des autres dispositions des Conditions Générales.</p><h2>1.&nbsp;Qui sommes-nous&nbsp;?</h2><p>Le site internet [URL] est une initiative de&nbsp;:</p><p>    NOM DE L’ENTREPRISE + FORME JURIDIQUE<br />    ADRESSE<br />    Numéro d’entreprise (TVA-BE)&nbsp;:<br />    E-mail&nbsp;:<br />    Téléphone&nbsp;:</p><p>N’hésitez pas à nous contacter si vous avez des questions ou des remarques, nous promettons une réponse rapide.</p><h2>2.&nbsp;Notre site</h2><h3>2.1 Bon fonctionnement, sécurité et accessibilité</h3><p>Nous prenons toutes les mesures raisonnables et nécessaires pour assurer le bon fonctionnement, la sécurité et l’accessibilité de notre site. Toutefois, nous ne pouvons pas offrir de garantie d’opérabilité absolue et il faut dès lors considérer nos actions comme étant couvertes par une obligation de moyen.</p><p>Toute utilisation du site se fait toujours aux propres risques de l’utilisateur. Ainsi, nous ne sommes pas responsables des dommages pouvant résulter de possibles dysfonctionnements, interruptions, défauts ou encore d’éléments nuisibles présents sur le site, indépendamment de l’existence d’un cas de force majeure ou d’une cause étrangère.</p><p>Nous nous réservons le droit de restreindre l’accès à notre site ou d’interrompre son fonctionnement à tout moment, sans obligation de notification préalable. Ceci, en principe, seulement si les circonstances de l’espèce le justifient, mais il ne s’agit pas là d’une condition absolue.</p><h3>2.2. Contenu du site</h3><p>[X] détermine en grande partie le contenu de son site et prend grand soin des informations présentes sur celui-ci. [X] prend toutes les mesures possibles pour maintenir son site aussi complet, précis et à jour que possible, même lorsque les informations présentes sur celui-ci sont fournies par des tiers. [X] se réserve le droit de modifier, compléter ou supprimer à tout moment le site et son contenu.</p><p>[X] ne peut pas offrir de garantie absolue concernant la qualité de l’information présente sur son site. Il est donc possible que cette information ne soit pas toujours complète, exacte, suffisamment précise et/ou à jour. Par conséquent, [X] ne pourra pas être tenue responsable des dommages, directs et indirects, que l’utilisateur subirait de par l’information présente sur le site.</p><p>Si certains contenus sur le site sont en violation avec les lois applicables et/ou avec les droits des tiers et/ou tout simplement seraient contraires à la morale, nous vous demandons de nous en informer le plus rapidement possible afin que nous puissions prendre des mesures appropriées. [X] pourra notamment procéder à la suppression partielle ou totale desdites informations.</p><p>Le site contient du contenu téléchargeable. Tout téléchargement à partir du site a toujours lieu aux propres risques de l’utilisateur. [X] ne pourra en aucun cas être tenue responsable des éventuels dommages, directs et indirects, découlant de ces téléchargements, tels qu’une perte de données ou un endommagement du système informatique de l’utilisateur, qui relèvent entièrement et exclusivement de la responsabilité de l’utilisateur.</p><p>Concernant plus spécifiquement les prix et les informations sur nos produits figurant sur le site, une réserve s’applique pour les fautes formelles manifestes, comme des erreurs de frappe. L’utilisateur ne peut en aucun cas se prévaloir de l’existence d’un contrat avec [X] basé sur de telles foutes.</p><h3>2.3 Ce que nous attendons de l’utilisateur</h3><p>En tant qu’utilisateur, vous avez une certaine responsabilité quant au bon fonctionnement du site. Vous devez en tout temps vous abstenir de poser des actes susceptibles de nuire au bon fonctionnement et à la sécurité du site et de son utilisation. Par exemple, le site ne peut être utilisé dans le but de contourner notre Business Model et/ou pour récolter des informations sur d’autres utilisateurs.</p><p>Il est notamment interdit d’utiliser le Site pour diffuser du contenu qui résulterait en un dommage envers d’autres utilisateurs, comme la propagation de logiciels malveillants tels que des virus informatiques. Sont également interdits la prolifération de messages non sollicités et/ou commerciaux au moyen du site, mais aussi les courriels indésirables, le spamming ou encore les chaînes de lettres.</p><p>[X] se réserve le droit de prendre toutes les mesures nécessaires pour remédier à la situation pour elle et pour ses utilisateurs, à la fois sur le plan judiciaire et extrajudiciaire. L’utilisateur est seul responsable, personnellement et exclusivement, dans le cas où ses actions et son comportement ont effectivement entraîné des dommages au site ou à d’autres utilisateurs. Dans ce cas, l’utilisateur en cause devra s’assurer d’exonérer [X] de toute plainte pour dommage qui s’en suivrait.</p><h2>3. Liens vers d’autres sites internet</h2><p>Le site pourrait contenir des liens ou hyperliens renvoyant vers des sites internet externes ou d’autres formes de portails en ligne. De tels liens ne signifient pas de manière automatique qu’il existe une relation entre nous et le site internet externe ou même que nous sommes implicitement d’accords avec le contenu de ces sites externes.</p><p>[X] n’exerce aucun contrôle sur les sites internet externes. Nous ne sommes donc pas responsables du fonctionnement sûr et correct des hyperliens et de leur destination finale. Dès l’instant où l’utilisateur clique sur l’hyperlien, il quitte notre site. Nous ne pouvons dès lors plus être tenus responsables en cas de dommage ultérieur.</p><p>Il est probable que ces sites internet externes n’offrent pas les mêmes garanties que nous. Ainsi, nous vous suggérons de lire attentivement leurs conditions générales de vente mais aussi leur disclaimer et leur charte vie privée.&nbsp;</p><h2>4. Propriété intellectuelle</h2><p>Le contenu publié sur le site est protégé par les droits de propriété intellectuelle de [X]. Par «&nbsp;contenu&nbsp;», il convient d’entendre les informations, logos, photos, marques, dessins, modèles, slogans, bases de données, etc. accessibles sur le site. Le caractère technique du site en lui-même, à savoir le code informatique, est également protégé par la propriété intellectuelle.</p><p>En visitant notre site, l’utilisateur se voit octroyer un droit limité d’accès, d’utilisation, et d’affichage de notre site et de son contenu. Ce droit est accordé à titre non-exclusif, non-transférable et ne peut être utilisé que moyennant un usage personnel et non commercial. Sauf accord préalable et écrit de [X], les utilisateurs ne sont pas autorisés à modifier, reproduire, traduire, distribuer, vendre, emprunter, louer, communiquer au public, en tout ou en partie, les éléments protégés.</p><h2>5.&nbsp;Protection des données personnelles</h2><p>Les données personnelles fournies par l’utilisateur lors de sa visite et/ou de l’utilisation du site sont collectées et traitées par [X] exclusivement à des fins internes. [X] assure à ses utilisateurs qu’elle attache la plus grande importance à la protection de leur vie privée et de leurs données personnelles, et qu’elle s’engage toujours à communiquer de manière claire et transparente sur ce point.</p><p>[X] s’engage à respecter la législation applicable en la matière, à savoir la Loi du 8 décembre 1992 relative à la protection de la vie privée à l’égard des traitements de données à caractère personnel («&nbsp;Loi vie privée rivée&nbsp;») ainsi que le Règlement européen du 27 avril 2016 relatif à la protection des personnes physiques à l’égard du traitement des données à caractère personnel et à la libre circulation de ces données («&nbsp;Règlement&nbsp;»).</p><p>Les données personnelles de l’utilisateur sont traitées conformément à notre Charte Vie Privée.</p><h2>6.&nbsp;Dispositions générales applicables à nos Conditions Générales</h2><p>[X] se réserve la possibilité de modifier, étendre, supprimer, limiter ou interrompre le site et les services qui y sont associés à tout moment, sans notification préalable, et sans que cela ne donne lieu à une quelconque forme d’indemnisation.</p><p>Nos Conditions Générales (notamment nos conditions générales de vente) sont exécutées et interprétées conformément au droit belge. Tout litige relatif à la validité, l’interprétation ou l’exécution de nos Conditions Générales sera soumis à la compétence exclusive des tribunaux de l’arrondissement judiciaire de [XXX].</p><p>L’illégalité ou la nullité d’une disposition de nos Conditions Générales, en tout ou en partie, n’aura aucun impact sur la validité et l’application des autres dispositions de nos Conditions Générales. Nous disposons, dans un tel cas, du droit de remplacer la clause inapplicable par une autre disposition valable en droit et de portée similaire.</p>\", \"nl\": \"<p>Het gebruik van onze website moet steeds gebeuren conform de rechten en plichten die duidelijk op de website vermeld staan en de rechten en plichten die bepaald zijn in de Disclaimer, de Verkoopsvoorwaarden en de Privacy Verklaring. Het geheel van deze teksten zijn onze Algemene Voorwaarden.</p><p>Deze Algemene Voorwaarden gelden zowel voor ons, [X], als voor jou, de Gebruiker. Zodra je gebruik maakt van onze website erken en aanvaard je uitdrukkelijk dat onze Algemene Voorwaarden van toepassing zijn en dat volledig verzaakt wordt aan de toepassing van eigen Algemene Voorwaarden.</p><p>Wij kunnen in uitzonderlijke gevallen afwijken van de Algemene Voorwaarden, voor zover deze afwijkingen schriftelijk worden vastgelegd en aanvaard worden door alle partijen. Deze afwijkingen gelden slechts ter vervanging of aanvulling van de clausules waar ze betrekking op hebben en hebben geen effect op de toepassing van overige bepalingen uit de Algemene Voorwaarden.</p><h2>1. Wie zijn wij?</h2><p>De website www.URL is een initiatief van:</p><p>BEDRIJFSNAAM + RECHTSVORM<br />ADRES<br />E-mail:<br />Telefoon:</p><p>Contacteer ons gerust indien u verdere vragen of opmerkingen heeft; wij beloven u een spoedig antwoord!</p><h2>2. Onze website</h2><h3>2.1 Goede werking, veiligheid en toegankelijkheid</h3><p>Je kan ervan op aan, wij bieden een gebruiksvriendelijke website aan die veilig is voor iedere Gebruiker. We nemen dan ook alle redelijke maatregelen die nodig zijn om de goede werking, veiligheid en toegankelijkheid van onze website te garanderen. Toch kunnen we jou geen absolute garanties geven en moet men onze maatregelen beschouwen als een middelenverbintenis.</p><p>Ieder gebruik van de website gebeurt steeds op eigen risico. Dit betekent dat wij geen aansprakelijkheid dragen voor schade die voortvloeit uit storingen, onderbrekingen, schadelijke elementen of defecten aan de website, ongeacht het bestaan van een vreemde oorzaak of overmacht.</p><p>We hebben het recht om de toegang tot onze website te allen tijde te beperken en/of geheel of gedeeltelijk te onderbreken, zonder voorafgaande waarschuwing. We doen dit in principe uitsluitend indien de omstandigheden dit verantwoorden, maar dit is geen absolute voorwaarde.</p><h3>2.2 Inhoud op onze website</h3><p>De inhoud van de website wordt grotendeels door ons bepaald en wij besteden de grootste zorg aan deze inhoud. Dit wil zeggen dat we de nodige maatregelen nemen om onze website zo volledig, nauwkeurig en actueel mogelijk te houden, ook wanneer content wordt aangeleverd door derde partijen. De inhoud op onze website kan steeds gewijzigd, aangevuld of verwijderd worden.</p><p>Toch kunnen we geen garanties geven omtrent de kwaliteit van de informatie op onze website. Het is mogelijk dat informatie niet volledig, voldoende accuraat en/of nuttig is. We zijn bijgevolg niet aansprakelijk voor (directe en indirecte) schade die de Gebruiker lijdt ten gevolge van de informatie op onze website.</p><p>In het geval bepaalde inhoud op onze website een schending van de geldende wetgeving en/of een schending van de rechten van derde partijen betekent en/of eenvoudigweg niet door de beugel kan, vragen wij aan jou om ons dit zo spoedig mogelijk te melden zodat we de gepaste maatregelen kunnen nemen. Zo kunnen we overgaan tot een gedeeltelijke of gehele verwijdering en/of aanpassing van de inhoud.</p><p>Onze website bevat inhoud die gedownload kan worden. Iedere download van onze website gebeurt steeds op eigen risico. Hiervoor zijn wij niet aansprakelijk en schade ten gevolge van een verlies van data of schade aan het computersysteem valt volledig en uitsluitend onder de verantwoordelijkheid van Gebruiker.</p><p>Specifiek voor prijzen en andere informatie over producten op de website geldt een voorbehoud van kennelijke programmeer- en typefouten. De Gebruiker kan op basis van dergelijke fouten geen overeenkomst claimen met [X].</p><h3>2.3 Wat wij van jou als Gebruiker verwachten</h3><p>Ook de Gebruiker draagt een zekere verantwoordelijkheid bij het gebruiken van onze website. De Gebruiker moet zich steeds onthouden van handelingen die een schadelijke impact kunnen hebben op de goede werking en veiligheid van de website. Zo mag de website niet gebruikt worden om ons business model te omzeilen en/of om informatie van andere Gebruikers op grote schaal te verzamelen.</p><p>Het is bijgevolg niet toegelaten om onze website te gebruiken voor de verspreiding van inhoud die schade aan andere Gebruikers van de website kan toebrengen, zoals de verspreiding van schadelijke programmatuur zoals computervirussen, malware, worms, trojans en cancelbots. De verspreiding van ongevraagde en/of commerciële berichten via de website, zoals junk mail, spamming en kettingbrieven, valt hier ook onder.</p><p>Wij behouden ons het recht voor om alle noodzakelijke handelingen te treffen die herstel kunnen opleveren voor ons en voor onze Gebruikers, zowel op gerechtelijk als buitengerechtelijk vlak. De Gebruiker is als enige persoonlijk en integraal verantwoordelijk indien zijn handelingen en gedragingen effectief schade veroorzaken aan de website en de andere Gebruikers. In dat geval moet hij [X] vrijwaren van iedere schadeclaim die volgt.</p><h2>3. Links naar andere websites</h2><p>De inhoud van onze website kan een link, hyperlink of framed link naar vreemde websites of andere vormen van elektronische portalen bevatten. Een link betekent niet automatisch dat er een band tussen ons en de vreemde website bestaat, noch dat wij (impliciet) akkoord gaan met de inhoud van deze websites.</p><p>Wij houden geen controle op deze vreemde websites en zijn niet verantwoordelijk voor de veilige en correcte werking van de link en de uiteindelijke bestemming. Zodra men op de link klikt verlaat men onze website en kan men ons niet meer aansprakelijk stellen voor enige schade.</p><p>Het is mogelijk dat vreemde websites niet dezelfde garanties bieden als wij. Daarom raden wij aan om aandachtig de Algemene Voorwaarden en de Privacy Statement van deze websites door te nemen.</p><h2>4. Intellectuele eigendom</h2><p>Creativiteit verdient bescherming, zo ook onze website en haar inhoud. De bescherming wordt voorzien door intellectuele eigendomsrechten en komt toe aan alle rechthebbende partijen, zijnde [X] en derde partijen. Onder inhoud verstaat men de heel ruime categorie van foto’s, video, audio, tekst, ideeën, notities, tekeningen, artikels, et cetera. Al deze inhoud wordt beschermd door het auteursrecht, softwarerecht, databankrecht, tekeningen- en modellenrecht en andere toepasselijke (intellectuele) eigendomsrechten. Het technische karakter van onze website zelf is beschermd door het auteursrecht, het softwarerecht en databankenrecht.&nbsp; Iedere handelsnaam die wij gebruiken op onze websites wordt eveneens beschermd door het toepasselijke handelsnamenrecht of merkenrecht.</p><p>Iedere Gebruiker krijgt een beperkt recht van toegang, gebruik en weergave van onze websites en haar inhoud. Dit toegekende recht is niet-exclusief, niet-overdraagbaar en kan enkel binnen een persoonlijk, niet-commercieel kader worden gebruikt. Wij vragen onze Gebruikers dan ook om geen gebruik te maken van en/of wijzigingen aan te brengen in de door deze rechten beschermde zaken, zonder de toestemming van de rechthebbende. [X] hecht veel belang aan haar intellectuele eigendomsrechten en heeft hiervoor alle mogelijke maatregelen getroffen om de bescherming te garanderen. Iedere inbreuk op de bestaande intellectuele eigendomsrechten wordt vervolgd.</p><h2>5. Verwerking persoonsgegevens</h2><p>De door jou opgegeven informatie is noodzakelijk voor het verwerken en voltooien van de bestellingen, en het opstellen van de rekeningen en garantiecontracten. Voor elke bestelling is een minimum aan gegevens vereist. Verdere gegevens kunnen gevraagd worden in functie van het personaliseren van de bestelling. Als de minimumgegevens ontbreken wordt de bestelling onvermijdelijk geannuleerd. De persoonsgegevens van de Koper zullen uitsluitend verwerkt worden in overeenstemming met de Privacy Statement te consulteren via onze website.&nbsp;</p><h2>6. Algemene bepalingen omtrent de Algemene Voorwaarden.</h2><p>Wij behouden de vrijheid om onze website en de daarbij horende diensten te allen tijde te wijzigen, uit te breiden, te beperken of stop te zetten. Dit kan zonder voorafgaande kennisgeving van de Gebruiker en geeft evenmin aanleiding tot enige vorm van schadevergoeding.</p><p>Deze Algemene Voorwaarden (inclusief de Verkoopsvoorwaarden) worden exclusief beheerst en geïnterpreteerd in overeenstemming met de Belgische Wetgeving. Alle geschillen die verband houden met of voortvloeien uit aanbiedingen van [X], of overeenkomsten die met haar gesloten zijn, worden voorgelegd aan de bevoegde rechtbank uit het gerechtelijk arrondissement [XXX].</p><p>Indien de werking of geldigheid van één of meerdere van bovenstaande bepalingen van deze Algemene Voorwaarden in het gedrang komen, zal dit geen gevolg hebben op de geldigheid van de overige bepalingen van deze overeenkomst. In dergelijk geval hebben wij het recht om de betrokken bepaling te wijzigen in een geldige bepaling van gelijkaardige strekking.</p>\"}', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', NULL, NULL, NULL, NULL, '2023-03-14 17:08:44', '2023-06-05 12:18:04');
INSERT INTO `typicms_pages` (`id`, `image_id`, `parent_id`, `position`, `private`, `is_home`, `redirect`, `title`, `slug`, `uri`, `body`, `status`, `meta_keywords`, `meta_description`, `css`, `js`, `module`, `template`, `created_at`, `updated_at`) VALUES
(5, NULL, NULL, 6, 0, 0, 0, '{\"en\": \"Privacy policy\", \"fr\": \"Charte vie privée\", \"nl\": \"Privacyverklaring\"}', '{\"en\": \"privacy-policy\", \"fr\": \"charte-vie-privee\", \"nl\": \"privacyverklaring\"}', '{\"en\": \"privacy-policy\", \"fr\": \"charte-vie-privee\", \"nl\": \"privacyverklaring\"}', '{\"en\": \"<h2>1. Why this Privacy Statement?</h2><p>Every person (hereafter the “User”) who visits or uses the Website discloses a certain amount of personal data. The personal data is information which allows [X] to identify you as a natural person, regardless of whether we actually do this. You are identifiable as soon as it is possible to create a direct or indirect link between one or more data and you as a natural person.&nbsp;</p><p>We only use and process your personal data in accordance with the GDPR and any replacement legislation, or any similar regulation under any applicable law, and any regulatory requirements or codes of practice governing the use, storage or transmission of personal data. Every reference in this Privacy Statement to the ‘GDPR’ is a reference to the Regulation of 27 April 2016 on the Protection of Natural Persons with regard to the Processing of Personal Data and on the Free Movement of such Data (General Data Protection Regulation).</p><p>Through this Privacy Policy, every User of the Website is informed of the processing activities [X] may carry out with his or her personal data. [X] reserves the right to modify this Privacy Policy at all times. Every substantial change will be clearly communicated towards the User. We advise you to consult this document regularly.</p><h2>2. Who is responsible for the processing of personal data?</h2><h3>2.1. Controller</h3><p>[X] is responsible for the processing and decides alone or in cooperation with others which personal data are being collected as well as the purposes and the technical and organisational means with regard to the processing of those personal data.</p><h3>2.2. Processor(s)</h3><p>[X] is free to rely on data processors. A processor is the natural or legal person who processes your personal data upon request and on behalf of the data controller. The processor is required to ensure the security and confidentiality of the data. The processor shall always act on the instructions of the data controller.</p><p>[X] relies on the following categories of “processors”:</p><ul><li>Companies we have engaged for marketing purposes;</li><li>Companies we have engaged for hosting purposes;</li><li>Companies we have engaged for administrative purposes;</li><li>Companies we have engaged for communication purposes;</li><li>Companies we have engaged for statistical purposes;</li><li>Companies we have engaged for payment purposes.</li></ul><h2>3. On what legal grounds are my data processed?</h2><p>In accordance with the GDPR we process personal data on the following legal grounds:</p><ul><li>On the basis of the execution of the contract agreed upon with you or the execution of pre-contractual steps taken at your request; or</li><li>On the basis of compliance with legal or regulatory provisions with regard to the management of the contractual relationship, invoicing in particular;</li><li>On the basis of our legitimate interest in sending information and newsletters to our customers;</li><li>On the basis of your consent to send promotional offers (direct marketing).</li></ul><h2>4. Which personal data are being processed?</h2><p>[X] commits to only collect and process adequate, relevant and limited to what is necessary for the purposes for which they are processed. The following categories of personal data are processed by [X]:</p><ul><li>Personal identification data (name, first name, address, login details);</li><li>Contact details (telephone number and e-mail address);</li><li>Financial identification data (bank details);</li><li>Electronic identification data (IP address, location, cookies);</li><li>Personal data (gender, age, date and place of birth, nationality).</li><li>…</li></ul><p>This data is collected at the time of your registration on the Website and when you use our services. Other personal data may be collected later, e.g. in the context of our after-sales. These data are necessary for the provision of [X] services. The amount of personal data collected depends on your use of the Website and the functionalities of the Website.</p><p>We also use cookies in order to recognise the User and to offer the User a personalised user experience, to remember technical choices (for example, language choices or a shopping cart), and to detect and correct any errors which might be present on the Website.</p><p>For more information concerning the use of cookies, we kindly refer you to our Cookie Statement.</p><h2>5. For which purposes are my personal data being used?</h2><p>[X] collects your personal data for the sole purpose of offering every User of our a safe, optimised and personal user experience of our Website and the offered services. The collection of personal data becomes more extensive as the User makes more intensive use of our Website and our online services. [X] reserves the right to suspend or cancel certain operations if personal data is missing, incorrect or incomplete.</p><p>The processing of your personal data is essential for the proper functioning of the Website and the provision of associated services. [X] commits to solely process your personal data for the following purposes:</p><ul><li>Customer management: customer administration, order management, deliveries, invoicing, checking creditworthiness, support, complaint monitoring and sending newsletters.</li><li>Dispute management.</li><li>Protection against fraud and infringements.</li><li>Personalized marketing and advertising if you have expressly agreed to it. In that case, you are free to withdraw your consent at any time.</li></ul><p>When visiting the website of [X], some data are being collected for statistical purposes. Such data is necessary to optimise your user experience. These data are: IP-address, probable location of consultation, hour and day of the consultation and the pages which are being consulted. When you visit the Website, you explicitly agree to this collection of data for statistical purposes.</p><p>The User provides the personal data to [X] himself and can therefore exercise some kind of control. When certain data is incomplete or apparently incorrect, the User has the right to postpone some expected actions temporarily or permanently.</p><h2>6. Who receives your personal data?</h2><p>Your personal data are processed for internal use within [X] only. Your personal data will not be sold, passed on or communicated to any third parties, except in case you have given us your explicit prior consent.</p><h2>7. How long do we store your personal data?</h2><p>Your data is stored as long as necessary to achieve the ends pursued. They will be erased from our database as soon as they are no longer necessary for the ends pursued or if you validly exercise your right to erasure.</p><h2>8. What are my rights?</h2><h3>8.1. Guarantee of a legitimate and secure process of your personal data</h3><p>Your personal data are always processed for the legitimate purposes explained in point 5. They are collected and processed in an appropriate, relevant and non-excessive manner, and are not kept longer than necessary to achieve the intended purposes.</p><h3>8.2. Right to access</h3><p>If you can prove your identity, you have the right to obtain information about the processing of your data. Thus, you have the right to know the purposes of the processing, the categories of data concerned, the categories of recipients to whom the data are transmitted, the criteria used to determine the data retention period, and the rights that you can exercise on your data.</p><h3>8.3. Right to rectification of your personal data</h3><p>Inaccurate or incomplete personal data may be corrected. It is primarily the responsibility of the User to make the necessary changes in his “user area” himself, but you can also request us in writing.</p><h3>8.4. Right to erasure (or “right to be forgotten”)</h3><p>You also have the right to obtain the erasure of your personal data under the following assumptions:</p><ul><li>Your personal data are no longer necessary for the intended purposes;</li><li>You withdraw your consent to the processing and there is no other legal ground for processing;</li><li>You have validly exercised your right of opposition;</li><li>Your data has been illegally processed;</li><li>Your data must be deleted to comply with a legal obligation.</li></ul><p>The deletion of data is mainly related to visibility; it is possible that the deleted data are still temporarily stored.</p><h3>8.5. Right to limitation of processing</h3><p>In certain cases, you have the right to request the limitation of the processing of your personal data, especially in case of dispute as to the accuracy of the data, if the data are necessary in the context of legal proceedings or the time required to [X] to verify that you can validly exercise your right to erasure.</p><h3>8.6. Right to object</h3><p>You have the right to object at any time to the processing of your personal data for direct marketing purposes. [X] will stop processing your personal data unless it can demonstrate that there are compelling legitimate reasons for the processing which prevail over your right to object.</p><h3>8.7. Right to data portability</h3><p>You have the right to obtain any personal data which you have provided us in a structured, commonly used and machine readable format. At your request, this data may be transferred to another provider unless it is technically impossible.</p><h3>8.8. Right to withdraw your consent</h3><p>You may withdraw your consent to the processing of your personal data at any time, for example for direct marketing purposes.</p><h2>9. How to exercise my rights?</h2><p>If you wish to exercise your rights, you must send a written request and proof of identity by registered mail to [X] or by email to [EMAIL]. We will respond as soon as possible, and no later than one (1) month after receipt of the request.</p><h2>10. Possibility to lodge a complaint</h2><p>If you are not satisfied with the processing of your personal data by [X], you have the right to lodge a complaint with the competent Data Protection Authority (for Belgium: <a href=\\\"https://www.privacycommission.be/)\\\">https://www.privacycommission.be/</a>).</p>\", \"fr\": \"<h2>1. Pourquoi cette charte vie privée&nbsp;?</h2><p>[X] accorde beaucoup d’importance à la protection de votre vie privée et de vos données personnelles. Nous collectons et traitons vos données personnelles conformément aux dispositions légales applicables en la matière, à savoir la Loi du 8 décembre 1992 relative à la protection de la vie privée à l’égard des traitements de données à caractère personnel («&nbsp;Loi vie privée&nbsp;») et le Règlement européen du 27 avril 2016 relatif à la protection des personnes physiques à l’égard du traitement des données à caractère personnel et à la libre circulation de ces données («&nbsp;Règlement&nbsp;»).</p><p>Nous souhaitons vous informer des éventuelles opérations de traitement de vos données personnelles et de vos droits à cet égard. En utilisant notre [site/plateforme/application], vous acceptez expressément la collecte et le traitement de vos données personnelles par [X] de la manière décrite dans ce document.</p><p>Nous nous réservons le droit d’apporter à tout moment des modifications à notre Charte vie privée. Toute modification substantielle sera toujours communiquée clairement sur notre [site/plateforme/application]. Nous vous conseillons néanmoins de consulter régulièrement ce document.</p><h2>2. Qui traite vos données personnelles&nbsp;?</h2><p>[X] est responsable [du site/de la plateforme/l’application].</p><p><strong>Informations de contact&nbsp;:</strong></p><p>NOM + FORME JURIDIQUE<br />RUE ET NUMERO<br />CODE POSTAL ET COMMUNE<br />Belgique</p><p><strong>Numéro d’entreprise (TVA-BE)&nbsp;:</strong></p><p><strong>E-mail&nbsp;:</strong></p><p><strong>Téléphone&nbsp;:</strong></p><h2>3. Quelles données personnelles sont collectées?</h2><p>Le traitement de vos données personnelles est limité au strict nécessaire pour atteindre les finalités poursuivies par [X].&nbsp; Ci-dessous, sont énumérées les catégories de données concernées par le traitement.</p><ul><li>Données d’identification</li><li>Données financières</li><li>Données de localicalisation</li><li>Données relatives à la connexion (adresse IP, etc.)</li><li>Caractéristiques personnelles</li><li>Données physiques</li><li>Habitudes de vie</li><li>Composition du ménage et de la famille</li><li>Loisirs et intérêts</li><li>Affiliations et appartenances</li><li>Données judiciaires</li><li>Habitudes de consommation</li><li>Données relatives aux études et à la formation</li><li>Données relatives à l’emploi et à la fonction</li><li>Numéro de registre national et numéro d’identification de la sécurité sociale</li><li>Données raciales ou ethniques</li><li>Données sur la vie sexuelle ou l’orientation sexuelle</li><li>Opinions politiques</li><li>Convictions religieuses ou philosophiques</li><li>Photos / Prises de vue</li><li>Enregistrements / Prises de son</li></ul><h2>4. Quelles sont les finalités poursuivies?</h2><p>[X] recueille vos données personnelles dans le seul objectif de vous fournir une expérience d’utilisation optimale, personnalisée et en toute sécurité [du site/de la plateforme/l’application]. La collecte de vos données personnelles peut s’intensifier de par une utilisation plus intensive de notre [site/plateforme/application].</p><p>Le traitement de vos données personnelles est donc essentiel au bon fonctionnement [du site/de la plateforme/l’application] et à la fourniture de nos services. [X] s’engage à traiter vos données personnelles exclusivement aux fins suivantes&nbsp;:</p><ul><li>Vous permettre d’avoir accès à votre «&nbsp;compte utilisateur&nbsp;»</li><li>Assurer le traitement des commandes et la livraison des produits&nbsp;</li><li>Assurer le service après-vente&nbsp;</li><li>Gérer la relation contractuelle&nbsp;</li><li>Permettre la facturation et la gestion des comptes&nbsp;</li><li>Offrir à l’Utilisateur un service général mais aussi personnalisé, incluant l’envoi d’information utile, de newsletters, la fourniture de soutien et le suivi des plaintes.</li><li>Améliorer et personnaliser nos services et nos produits</li><li>Détecter les fraudes, les erreurs et les comportements criminels.</li><li>Envoyer des offres promotionnelles si vous y avez expressément consenti (marketing direct). Dans ce cas, vous restez libre de retirer votre consentement à tout moment</li></ul><p>En visitant notre [site/plateforme/application], certaines données sont collectées à des fins statistiques. Ces données nous permettent d’optimiser votre expérience d’utilisation. Il s’agit de votre adresse IP, de la zone géographique de consultation, du jour et de l’heure de consultation, et des pages consultées. En visitant notre [site/plateforme/application], vous acceptez expressément la collecte de ces données à des fins statistiques.</p><p>Vous exercez un contrôle sur les données que vous nous communiquez. Lorsque des données sont incomplètes ou erronées, [X] se réserve le droit d’interrompre ou de suspendre, de manière temporaire ou permanente, certaines opérations.</p><p>Vos données personnelles sont destinées à être utilisées exclusivement à des fins internes. Vos données personnelles ne seront donc pas vendues, données ou transmises à des tiers, sauf consentement préalable de votre part. [X] a pris toutes les mesures techniques et organisationnelles possibles pour éviter une violation des données.</p><h2>5. Nous utilisons aussi des cookies&nbsp;!</h2><p>Lors de votre visite sur notre [site/plateforme/application], des cookies peuvent être placés sur le disque dur de votre ordinateur. Nous utilisons des cookies pour offrir une expérience d’utilisation optimale à nos visiteurs réguliers.</p><p>Pour plus d’informations sur l’utilisation que nous faisons des cookies, nous vous invitons à consulter notre Politique des cookies.</p><p>Si vous visitez notre [site/plateforme/application], nous vous conseillons d’activer vos cookies. Toutefois, vous restez libre de les désactiver via les paramètres de votre navigateur si vous souhaitez. Pour plus de détails, nous vous renvoyons vers notre Politique des cookies.</p><h2>6. Quels sont mes droits&nbsp;?</h2><h3>6.1. Garantie d’un traitement loyal et licite</h3><p>Vos données personnelles sont toujours traitées conformément aux fins légitimes explicitées au point 4. Elles sont collectées et traitées de manière adéquate, pertinente et non-excessive, et ne sont pas conservées plus longtemps que nécessaire pour atteindre les finalités poursuivies.</p><p>Toutes les mesures techniques et sécuritaires ont été prises pour limiter au maximum les risques d’accès ou de traitement illégal ou non-autorisé de vos données personnelles. En cas d’intrusion dans ses systèmes informatiques, [X] prendra immédiatement toutes les mesures nécessaires pour réduire les dommages à leur minimum.</p><h3>6.2. Droit d’accès</h3><p>Si vous êtes en mesure de prouver votre identité, vous avez le droit d’obtenir des informations sur le traitement de vos données. Ainsi vous avez le droit de connaître les finalités du traitement, les catégories de données concernées, les catégories de destinataires auxquels les données sont transmises, les critères utilisés pour déterminer la durée de conservation des données, et les droits que vous pouvez exercer sur vos données.</p><h3>6.3. Droit de rectification</h3><p>Les données personnelles inexactes ou incomplètes peuvent être corrigées. Il incombe en premier lieu à l’Utilisateur d’effectuer lui-même les changements nécessaires dans son «&nbsp;compte utilisateur&nbsp;» mais vous pouvez également nous en faire la demande écrite.</p><h3>6.4. Droit à l’effacement (ou «&nbsp;droit à l’oubli&nbsp;»)</h3><p>Vous avez en outre le droit d’obtenir l’effacement de vos données personnelles dans les hypothèses suivantes&nbsp;:</p><ul><li>Vos données personnelles ne sont plus nécessaires au regard des finalités&nbsp;;</li><li>Vous retirez votre consentement au traitement et il n’existe pas d’autre fondement légal au traitement&nbsp;;</li><li>Vous avez valablement exercé votre droit d’opposition&nbsp;;</li><li>Vos données ont fait l’objet d’un traitement illégal&nbsp;;</li><li>Vos données doivent être supprimées pour respecter une obligation légale.</li></ul><p>[X] décide de son propre chef de la présence des critères repris ci-dessus.</p><h3>6.5. Droit à la limitation du traitement</h3><p>Dans certaines hypothèses, vous avez le droit de demander la limitation du traitement de vos données personnelles, notamment en cas de contestation quant à l’exactitude des données, si vos données ont été traitées illégalement, si les données sont nécessaires dans le cadre d’une procédure judiciaire ou le temps nécessaire à [X] pour vérifier que vous pouvez valablement exercer votre droit à l’effacement.</p><h3>6.6. Droit d’opposition</h3><p>Vous avez en outre le droit de vous opposer à tout moment au traitement de vos données personnelles. [X] cessera le traitement de vos données personnelles, à moins qu’elle ne parvienne à démontrer qu’il existe des motifs légitimes impérieux en faveur du traitement qui prévalent sur votre droit d’opposition.</p><h3>6.7. Droit à la portabilité des données</h3><p>Vous avez le droit d’obtenir toutes les données personnelles que vous nous avez fournies dans un format structuré, couramment utilisé et lisible par machine. À votre demande, ces données peuvent être transférées à un autre prestataire, à moins que cela soit techniquement impossible.</p><h3>6.8. Droit du retrait de votre consentement</h3><p>Vous pouvez retirer à tout moment votre consentement au traitement de vos données personnelles. Le retrait de votre consentement n’entache pas la validité des opérations de traitement antérieures au retrait.</p><h2>7. Comment exercer mes droits&nbsp;?</h2><p>Pour exercer vos droits, vous devez nous envoyer une demande par courrier recommandé à [X] ou par mail à [EMAIL]. Nous y répondrons dans les meilleurs délais, et au plus tard un (1) mois après réception de la demande.</p><h2>8. Possibilité d’introduire une plainte</h2><p>Si vous n’êtes pas satisfait du traitement de vos données personnelles par [X], vous avez le droit d’introduire une plainte auprès de la Commission pour la Protection de la Vie Privée, qui devient l’Autorité de Protection des Données à partir du 25 mai 2018 (<a href=\\\"https://www.autoriteprotectiondonnees.be/\\\">www.autoriteprotectiondonnees.be</a>).</p>\", \"nl\": \"<h2>1. Waarom deze privacyverklaring?</h2><p>[X] hecht grote waarde aan de bescherming van uw privacy en persoonsgegevens. Wij gebruiken uw persoonsgegevens uitsluitend in overeenstemming met de Privacywet en andere relevante vigerende wettelijke voorschriften. Iedere verwijzing in deze Privacyverklaring naar de Privacywet betekent een verwijzing naar de Wet van 8 december 1992 tot bescherming van de persoonlijke levenssfeer ten opzichte van de verwerking van persoonsgegevens. Iedere verwijzing naar de Verordening is een verwijzing naar de Verordening van 27 april 2016 betreffende de bescherming van natuurlijke personen in verband met de verwerking van persoonsgegevens en betreffende het vrije verkeer van die gegevens.</p><p>Met deze Privacyverklaring wil [X] u wijzen op eventuele verwerkingshandelingen ten aanzien van deze gegevens en op uw rechten. Door gebruik te maken van [onze website/ons platform/onze applicatie] verleent u expliciet uw toestemming met mogelijke verwerkingshandelingen door [X].</p><p>Het is mogelijk dat deze Privacyverklaring in de toekomst onderhevig is aan aanpassingen en wijzigingen. Het is aan u om op regelmatige basis dit document te raadplegen. Iedere substantiële wijziging zal steeds duidelijk gecommuniceerd worden op [de website/het platform/de applicatie] van [X].</p><h2>2.Wie verwerkt de persoonsgegevens?</h2><p>[X] is verantwoordelijk voor de [de website/het platform/de applicatie].</p><p>    LID ONDERNEMINGSVORM<br />    STRAAT HUISNUMMER<br />    POSTCODE GEMEENTE<br />    België</p><p><strong>Btw-nummer:</strong></p><p><strong>E-mail:</strong></p><p><strong>Telefoon:</strong></p><h2>3. Welke persoonsgegevens worden verwerkt?</h2><p>[X] verbindt zich ertoe enkel de gegevens te verwerken die ter zake dienend zijn en noodzakelijk zijn voor de doeleinden waarvoor zij verzameld werden. Volgende categorieën van persoonsgegevens kunnen verwerkt worden door [X]:</p><ul>    <li>Identificatiegegevens</li>    <li>Financiële bijzonderheden</li>    <li>Persoonlijke kenmerken</li>    <li>Fysieke gegevens</li>    <li>Leefgewoonten</li>    <li>Psychische gegevens</li>    <li>Samenstelling van het gezin</li>    <li>Vrijetijdsbesteding en interessen</li>    <li>Lidmaatschappen</li>    <li>Gerechtelijke gegevens</li>    <li>Consumptiegewoonten</li>    <li>Woningkenmerken</li>    <li>Gegevens omtrent opleiding en vorming</li>    <li>Gegevens omtrent beroep en betrekking</li>    <li>Rijksregisternummer en identificatienummer van de sociale zekerheid</li>    <li>Raciale of etnische gegevens</li>    <li>Gegevens over het seksuele leven</li>    <li>Politieke opvattingen</li>    <li>Filosofische of religieuze overtuigingen</li>    <li>Beeldopnamen</li>    <li>Geluidsopnamen</li></ul><h2>4. Voor welke doeleinden worden mijn persoonsgegevens gebruikt?</h2><p>[X] verzamelt persoonsgegevens om u een veilige, optimale en persoonlijke gebruikerservaring te bieden. De verzameling van persoonsgegevens wordt uitgebreider naarmate u intensiever gebruik maakt van [de website/het platform/de applicatie] en onze online dienstverlening.</p><p>Gegevensverwerking is essentieel voor de werking van [de website/het platform/de applicatie] en de daarbij horende diensten. De verwerking gebeurt uitsluitend voor volgende welbepaalde doeleinden:</p><ul>    <li>U toegang verschaffen tot uw gebruikersprofiel.</li>    <li>Het aanbieden en verbeteren van een algemene en gepersonaliseerde dienstverlening; inclusief facturatiedoeleinden, aanbod van informatie, nieuwsbrieven en aanbiedingen die nuttig en/of noodzakelijk zijn voor u, de verkrijging en verwerking van gebruikersbeoordelingen en het verlenen van ondersteuning.</li>    <li>Het aanbieden en verbeteren van de aangeleverde producten; persoonsgerichte en specifieke producten aan de hand van geleverde informatie en gegevens.</li>    <li>De detectie van en bescherming tegen fraude, fouten en/of criminele gedragingen.</li>    <li>Marketing doeleinden</li></ul><p>Bij een bezoek aan [de website/het platform/de applicatie] van [X] worden er enkele gegevens ingezameld voor statistische doeleinden. Dergelijke gegevens zijn noodzakelijk om het gebruik van [onze website/ons platform/onze applicatie] te optimaliseren. Deze gegevens zijn: IP-adres, vermoedelijke plaats van consultatie, uur en dag van consultatie, welke pagina’s er werden bezocht. Wanneer u [de website/het platform/de applicatie] van [X] bezoekt verklaart u zich akkoord met deze gegevensinzameling bestemd voor statische doeleinden zoals hierboven omschreven.</p><p>De Gebruiker verschaft steeds zelf de persoonsgegevens aan [X] en kan op die manier een zekere controle uitoefenen. Indien bepaalde gegevens onvolledig of ogenschijnlijk incorrect zijn, behoudt LID zich het recht voor bepaalde verwachte handelingen tijdelijk of permanent uit te stellen.</p><p>    De persoonsgegevens worden enkel verwerkt voor intern gebruik binnen [X].<br />    We kunnen u dan ook geruststellen dat persoonsgegevens niet verkocht, doorgegeven of meegedeeld worden aan derde partijen die aan ons verbonden zijn. [X] heeft alle mogelijke juridische en technische voorzorgen genomen om ongeoorloofde toegang en gebruik te vermijden.</p><h2>5. Wij gebruiken ook cookies!</h2><p>Tijdens een bezoek aan [onze website/ons platform/onze applicatie] kunnen ‘cookies’ op uw harde schijf geplaatst worden om [de website/het platform/de applicatie] beter af te stemmen op de behoeften van de terugkerende bezoeker. Niet-functionele cookies helpen ons om uw bezoek aan [de website/het platform/de applicatie] te optimaliseren en om technische keuzes te herinneren.</p><p>Voor een verder begrip van de wijze waarop wij cookies gebruiken om uw persoonsgegevens te verzamelen en te verwerken, verwijzen wij u graag door naar onze Cookieverklaring.</p><p>Als u [de website/het platform/de applicatie] van [X] wil consulteren, is het aan te raden dat u cookies ingeschakeld hebt. Hoe u cookies daarentegen kan uitschakelen, staat eveneens te lezen in onze Cookieverklaring.</p><h2>6. Wat zijn mijn rechten?</h2><h3>6.1. Garantie van een rechtmatige en veilige verwerking van de persoonsgegevens</h3><p>[X] verwerkt uw persoonsgegevens steeds eerlijk en rechtmatig. Dit houdt volgende garanties in:</p><ul>    <li>Persoonsgegevens worden enkel conform de omschreven en gerechtvaardigde doeleinden van deze Privacyverklaring verwerkt.</li>    <li>Persoonsgegevens worden enkel verwerkt voor zover dit toereikend, ter zake dienend en niet overmatig is.</li>    <li>Persoonsgegevens worden maar bewaard zolang dit noodzakelijk is voor het verwezelijken van de omschreven en gerechtvaardigde doeleinden in deze Privacyverklaring.</li></ul><p>De nodige technische en beveiligingsmaatregelen werden genomen om de risico’s op onrechtmatige toegang tot of verwerking van de persoonsgegevens tot een minimum te reduceren. Bij inbraak in haar informaticasystemen zal [X] onmiddellijk alle mogelijke maatregelen nemen om de schade tot een minimum te beperken.</p><h3>6.2. Recht op inzage/rectificatie/wissen van uw persoonsgegevens</h3><p>Bij bewijs van uw identiteit als Gebruiker, beschikt u over een recht om van [X] uitsluitsel te krijgen over het al dan niet verwerken van uw persoonsgegevens. Wanneer [X] uw gegevens verwerkt, heeft u bovendien het recht om inzage te verkrijgen in de verzamelde persoonsgegevens. Indien u wenst uw recht op inzage te gebruiken, zal [X] hieraan gevolg geven binnen één (1) maand na het ontvangen van de aanvraag. De aanvraag gebeurt via aangetekende zending of via het versturen van een e-mail naar [X]</p><p>Onnauwkeurige of onvolledige persoonsgegevens kunnen steeds verbeterd worden. Het is aan de Gebruiker om in de eerste plaats zelf onnauwkeurigheden en onvolledigheden aan te passen. U kan uw recht op verbetering uitoefenen door een aanvullende verklaring te verstrekken aan [X]. [X] zal hieraan gevolg geven binnen één (1) maand na het ontvangen van de aanvullende verklaring.</p><p>U heeft bovendien het recht om zonder onredelijke vertraging uw persoonsgegevens door ons te laten wissen. U kan slechts beroep doen op dit recht om vergeten te worden in de hiernavolgende gevallen:</p><ul>    <li>Wanneer de persoonsgegevens niet langer nodig zijn voor de doeleinden waarvoor zij oorspronkelijk werden verzameld;</li>    <li>Wanneer de persoonsgegevens verzameld werden op basis van verkregen toestemming en geen andere rechtsgrond bestaat voor de verwerking;</li>    <li>Wanneer bezwaar wordt gemaakt tegen de verwerking en geen prevalerende dwingende gerechtvaardigde gronden voor de verwerking bestaan;</li>    <li>Wanneer de persoonsgegevens onrechtmatig werden verwerkt;</li>    <li>Wanneer de persoonsgegevens gewist moeten worden overeenkomstig een wettelijke verplichting.</li></ul><p>[X] beoordeelt de aanwezigheid van een van de voornoemde gevallen.</p><h3>6.3. Recht op beperking van/bezwaar tegen de verwerking van uw persoonsgegevens</h3><p>Gebruiker heeft het recht om een beperking van de verwerking van uw persoonsgegevens te verkrijgen:</p><ul>    <li>Gedurende de periode die [X] nodig heeft om de juistheid van de persoonsgegevens te controleren, in geval van betwisting;</li>    <li>Wanneer de gegevensverwerking onrechtmatig is en Gebruiker verzoekt tot een beperking van de verwerking in plaats van het wissen van de persoonsgegevens;</li>    <li>Wanneer [X] de persoonsgegevens van de Gebruiker niet meer nodig heeft voor de verwerkingsdoeleinden en Gebruiker de persoonsgegevens nodig heeft inzake een rechtsvordering;</li>    <li>Gedurende de periode die [X] nodig heeft om de aanwezigheid van de gronden voor het wissen van persoonsgegevens te beoordelen.</li></ul><p>Gebruiker heeft bovendien te allen tijde het recht om bezwaar te maken tegen de verwerking van zijn persoonsgegevens. [X] staakt hierna de verwerking van uw persoonsgegevens, tenzij [X] dwingende gerechtvaardigde gronden voor de verwerking van uw persoonsgegevens kan aanvoeren die zwaarder wegen dan het recht van de Gebruiker op bezwaar.</p><p>Indien Gebruiker wenst om deze rechten uit te oefenen, zal [X] hieraan gevolg geven binnen één&nbsp; (1) maand na het ontvangen van de aanvraag. De aanvraag gebeurt via aangetekende zending of via een e-mail naar&nbsp;[EMAIL].</p><h3>6.4. Recht op gegevensoverdraagbaarheid</h3><p>Gebruiker heeft het recht om de aan [X] verstrekte persoonsgegevens in een gestructureerde, gangbare en machine leesbare vorm te verkrijgen. Daarnaast heeft Gebruiker het recht om deze persoonsgegevens over te dragen aan een andere verwerkingsverantwoordelijke wanneer de verwerking van de persoonsgegevens uitsluitend rust op de verkregen toestemming van de Gebruiker.</p><p>Indien Gebruiker wenst om dit recht uit te oefenen, zal [X] hieraan gevolg geven binnen één (1) maand na het ontvangen van de aanvraag. De aanvraag gebeurt via aangetekende zending of via een e-mail naar [EMAIL].</p><h3>6.5. Recht op het intrekken van mijn toestemming/recht om klacht in te dienen</h3><p>Gebruiker heeft te allen tijde het recht om zijn toestemming in te trekken. Het intrekken van de toestemming laat de rechtmatigheid van de verwerking op basis van de toestemming vóór de intrekking daarvan, onverlet. Daarnaast heeft Gebruiker het recht om klacht in te dienen betreffende de verwerking van zijn persoonsgegevens door [X] bij de Belgische Commissie voor de Bescherming van de Persoonlijke Levenssfeer.</p><p>Indien Gebruiker wenst dit recht uit te oefenen, zal [X] hieraan gevolg geven binnen één (1) maand na het ontvangen van de aanvraag. De aanvraag gebeurt via aangetekende zending of via e-mail naar [EMAIL].</p><h3>6.6. Recht op bezwaar</h3><p>Gebruiker heeft bovendien te allen tijde het recht om bezwaar te maken tegen de verwerking van zijn persoonsgegevens. [X] staakt hierna de verwerking van uw 5 / 5 persoonsgegevens, tenzij de vzw dwingende gerechtvaardigde gronden voor de verwerking van uw persoonsgegevens kan aanvoeren die zwaarder wegen dan het recht van de Gebruiker op bezwaar.</p><h3>6.7. Recht op gegevensoverdraagbaarheid</h3><p>Gebruiker heeft het recht om de aan de [X] verstrekte persoonsgegevens in een gestructureerde, gangbare en machine leesbare vorm te verkrijgen. Daarnaast heeft Gebruiker het recht om deze persoonsgegevens over te dragen aan een andere verwerkingsverantwoordelijke wanneer de verwerking van de persoonsgegevens uitsluitend rust op de verkregen toestemming van de Gebruiker.</p><h3>6.8. Recht op het intrekken van mijn toestemming</h3><p>Gebruiker heeft te allen tijde het recht om zijn toestemming in te trekken. Het intrekken van de toestemming laat de rechtmatigheid van de verwerking op basis van de toestemming vóór de intrekking daarvan, onverlet.</p><h2>7. Hoe kan ik mijn rechten uitoefenen?</h2><p>Indien Gebruiker wenst om deze rechten uit te oefenen, zal de [X] hieraan gevolg geven binnen één (1) maand na het ontvangen van de aanvraag. De aanvraag gebeurt via aangetekende zending aan [X], of via een e-mail naar [EMAIL].</p><h2>8. Recht om klacht in te dienen</h2><p>Daarnaast heeft Gebruiker het recht om klacht in te dienen betreffende de verwerking van zijn persoonsgegevens door de [X] bij de Belgische Commissie voor de Bescherming van de Persoonlijke Levenssfeer (<a href=\\\"https://www.gegevensbeschermingsautoriteit.be\\\">www.gegevensbeschermingsautoriteit.be</a>).</p>\"}', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', NULL, NULL, NULL, NULL, '2023-03-14 17:08:44', '2023-06-05 12:18:04');
INSERT INTO `typicms_pages` (`id`, `image_id`, `parent_id`, `position`, `private`, `is_home`, `redirect`, `title`, `slug`, `uri`, `body`, `status`, `meta_keywords`, `meta_description`, `css`, `js`, `module`, `template`, `created_at`, `updated_at`) VALUES
(6, NULL, NULL, 7, 0, 0, 0, '{\"en\": \"Cookie policy\", \"fr\": \"Politique des cookies\", \"nl\": \"Cookieverklaring\"}', '{\"en\": \"cookie-policy\", \"fr\": \"politique-des-cookies\", \"nl\": \"cookieverklaring\"}', '{\"en\": \"cookie-policy\", \"fr\": \"politique-des-cookies\", \"nl\": \"cookieverklaring\"}', '{\"en\": \"<h2>1. Cookies</h2><p>A cookie is a small text file that is placed on the hard disk of your computer or mobile device when you visit a website. The cookie is placed on your device by the website itself (“first party cookies”) or by partners of the website (“third party cookies”). The cookie identifies your device by a unique identification number when you return to the website and collects information about your browsing behaviour.</p><p>There are different types of cookies. We distinguish the following cookies according to their purposes: there are essential or strictly necessary cookies and non-essential cookies (functional, analytical and targeting cookies)</p><p>The Belgian Act concerning the Electronic Communication of 13 June 2005 contains some provisions about cookies and the use thereof on websites. The Belgian implementation is deduced of the European e-Privacy Directive, which implies that the cookie usage and the cookie legislation is regulated differently in every European country. [X] is a Belgium-based webshop and therefore follows the Belgian and European legislation on cookies.</p><h2>2. Goals and utilities of cookies</h2><p>By using the website, the visitor may agree to the use of cookies. Cookies help [X] to optimize your visit to the website and to provide you with an optimal user experience. However, you are free to delete or restrict cookies at any time by changing your browser settings (see “Management of cookies”).</p><p>Disabling cookies can have an impact on the functioning of the website. Some of the site’s features may be restricted or inaccessible. If you decide to disable cookies, we cannot guarantee you a smooth and optimal visit to our website.</p><h2>3. Types of cookies used by [X]</h2><p>We distinguish the following types of cookies, according to their purposes:</p><h3>Essential / Strictly necessary cookies:</h3><p>These cookies are necessary for the website to function and cannot be disabled in our systems. They are usually only set up in response to your actions, such as setting up your privacy preferences, logging in or filling in forms. They are necessary for a good communication and they facilitate navigating (for example, returning to a previous page, etc.).</p><h3>Non-essential cookies:</h3><p>These cookies are not necessary for the website to function, but they do help us to offer an improved and personalized website.</p><ul><li><strong>Functional cookies:</strong><br />These cookies enable the website to offer improved functionality and personalization. They can be set up by us or by external providers whose services we have added to our pages.</li><li><strong>Analytical cookies:</strong><br />With these cookies, we can track visits and traffic so that we can measure and improve the performance of our site. They help us to determine which pages are the most and the least popular and how visitors move through the site.</li><li><strong>Targeting / advertising cookies:</strong><br />These cookies can be set by our advertising partners via our site. They can be used by those companies to create a profile of your interests and show you relevant ads on other sites.</li></ul><p>We use two types of cookies, namely our own functional cookies and cookies of carefully selected partners with whom we cooperate and who advertise our services on their website.</p><h3>First party cookies:</h3><p>Domain name: [URL]</p><div class=\\\"table-responsive\\\"><table><tbody><tr><td>Name of the cookie</td><td>Type of cookie</td><td>Retention period</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><h3>Third party cookies:</h3><p>Domain name: [URL]</p><div class=\\\"table-responsive\\\"><table><tbody><tr><td>Name of the cookie</td><td>Type of cookie</td><td>Retention period</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><p>Domain name: [URL]&nbsp;</p><div class=\\\"table-responsive\\\"><table><tbody><tr><td>Name of the cookie</td><td>Type of cookie</td><td>Retention period</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><p>Carefully read our <a href=\\\"{!! page:4 !!}\\\">Privacy policy</a> for more information concerning the processing of personal data by [X].</p><h2>4. Management of cookies</h2><p>Make sure that cookies are enabled in your web browser. If you want to consult the website of BEGUMMY, it is recommended you enable cookies. However, you are free to disable cookies in your browser settings if you wish to do so.</p><p>To enable or disable cookies, you must change your browser settings (via the “preferences” or “options” tab). The following links will give you more information on how to manage your cookies. You can also consult the “help” tab of your browser.</p><ul><li><a href=\\\"https://support.microsoft.com/en-gb/help/17442/windows-internet-explorer-delete-manage-cookies\\\">How to delete and manage cookies in Internet Explorer</a></li><li><a href=\\\"https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences\\\">How to delete and manage cookies in Mozilla Firefox</a></li><li><a href=\\\"https://support.google.com/chrome/answer/95647?co=GENIE.Platform%3DDesktop&amp;hl=en-GB\\\">How to delete and manage cookies in Chrome</a></li><li><a href=\\\"https://support.apple.com/en-gb/guide/safari/manage-cookies-and-website-data-sfri11471/mac\\\">How to delete and manage cookies in Safari </a></li></ul><h2>5. Rights of the visitors</h2><p>Since cookies may constitute a processing of personal data, you as a data subject have the right to the lawful and secure processing of personal data. More information about the way in which we collect and process your personal data, as well as about your rights, can be found in our <a href=\\\"{!! page:4 !!}\\\">Privacy policy</a>.</p>\", \"fr\": \"<h2>1. Cookies</h2><p>Les cookies sont de petits fichiers de données ou de texte placés sur votre ordinateur local par des sites internet ou des applications. De tels cookies peuvent poursuivre différentes finalités. On distingue ainsi les cookies fonctionnels (par exemple, lorsque vous faites un choix de langue ou lorsque vous ajoutez des achats à votre «&nbsp;caddy&nbsp;»), les cookies de session (temporaires)&nbsp;et les cookies de tracking (qui analysent et enregistrent le comportement que vous adoptez sur le site internet en question afin de vous offrir une expérience d’utilisation optimale).</p><p>La loi belge du 13 juin 2005 relative aux communications électroniques contient quelques dispositions relatives aux cookies et à leur utilisation sur les sites internet. L’application de la loi belge découle de la directive européenne e-Privacy, ce qui implique que l’utilisation des cookies et leur réglementation au niveau national diffèrent selon les pays européens.</p><p>[X] est une entreprise active sur le marché belge, elle suit par conséquent la législation belge en la matière.</p><h2>2. Quelle est l’utilité des cookies&nbsp;?</h2><p>[X] souhaite vous informer le plus précisément possible des règles entourant l’utilisation des cookies et des types de cookies qu’elle utilise sur son site. En utilisant notre site, vous marquez votre accord avec l’utilisation de cookies. Les cookies aident [X] à optimiser votre visite de son site internet, à se souvenir de vos choix techniques (comme un choix de langue, une newsletter, etc.), et à vous proposer des offres et des services toujours plus adaptés à vos besoins.</p><p>Pour pouvoir utiliser le site internet de [X], il est recommandé d’activer les cookies. Si les cookies sont désactivés, il est possible que nous ne soyons pas en mesure de vous garantir une visite de notre site qui soit dénuée de tout problème technique. Cependant, vous restez tout à fait libre de désactiver les cookies si vous le souhaitez.</p><p>Nous utilisons des cookies pour améliorer votre visite et votre utilisation de notre site internet. Les cookies que nous utilisons sont sûrs. Les informations que nous collectons à l’aide des cookies nous aident à identifier les éventuelles erreurs qui se trouvent sur notre site internet et à vous proposer des services spécifiques et personnalisés dont nous pensons qu’ils peuvent vous intéresser.</p><h2>3. Quels types de cookies utilise [X]?</h2><p>Nous distinguons les types de cookies suivants, en fonction de leurs finalités :</p><h3>Cookies essentiels / strictement nécessaires:</h3><p>Ces cookies sont nécessaires pour le bon fonctionnement de notre site internet et ne peuvent pas être désactivés. Ils sont configurés en réponse aux actions que vous faites sur notre site internet, comme définir vos paramètres de confidentialité, vos identifiants de connexion ou remplir nos formulaires. Ces cookies permettent de faciliter votre navigation sur notre site internet (par exemple, revenir à une page précédente, etc.).</p><h3>Cookies non-essentiels</h3><p>Ces cookies ne sont pas nécessaires au bon fonctionnement de notre site internet mais ils nous aident à vous proposer une expérience d’utilisation améliorée et personnalisée.</p><ul><li><strong>Cookies fonctionnels:</strong><br />Grâce à ces cookies, nous pouvons vous offrir un fonctionnement et une expérience d’utilisation de notre site internet qui soient optimales et personnalisés. Ces cookies peuvent être placés sur votre ordinateur par [X] ou par des fournisseurs externes dont nous avons ajouté les services à nos pages.</li><li><strong>Cookies analytiques:</strong><br />Avec ces cookies, nous pouvons analyser les visites de notre site internet et le trafic, afin de pouvoir mesurer et améliorer les performances de notre site internet. Ces cookies nous permettent de voir quelles pages sont les plus populaires et quelles sont les habitudes de navigation de nos visiteurs.</li><li><strong>Cookies de suivi:</strong><br />Ces cookies peuvent être placés sur notre site internet par nos partenaires publicitaires. Ils peuvent être utilisés par ces entreprises pour cibler vos préférences et vous proposer des publicités pertinentes sur d’autres sites internet.</li></ul><p>Nous utilisons deux types de cookies, nos propres cookies et les cookies de partenaires soigneusement sélectionnés avec lesquels nous collaborons et qui font de la publicité pour nos services sur leur site internet.</p><h3>First Party Cookies:</h3><p>Nom de domaine: [URL]</p><div class=\\\"table-responsive\\\"><table><thead><tr><th>Nom du cookie</th><th>Type de cookie</th><th>Durée de conservation</th></tr></thead><tbody><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><h3>Third party cookies:</h3><p>Nom de domaine: [URL]</p><div class=\\\"table-responsive\\\"><table><thead><tr><th>Nom du cookie</th><th>Type de cookie</th><th>Durée de conservation</th></tr></thead><tbody><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><p>Nom de domaine: [URL]</p><div class=\\\"table-responsive\\\"><table><thead><tr><th>Nom du cookie</th><th>Type de cookie</th><th>Durée de conservation</th></tr></thead><tbody><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><p>Nous vous invitions à consulter notre <a href=\\\"{!! page:4 !!}\\\">Charte vie privée</a> pour plus d’information concernant le traitement de vos données personnelles par [X].</p><h2>4. Comment puis-je gérer mes cookies ?</h2><p>Veillez à ce que les cookies soient activés dans votre navigateur. Pour activer les cookies, vous devez effectuer les opérations suivantes&nbsp;:</p><p>Si votre navigateur est de type Microsoft Internet Explorer</p><ul><li>Dans Internet Explorer, cliquez sur «&nbsp;Options Internet&nbsp;» dans le menu «&nbsp;Outils&nbsp;».</li><li>Dans l’onglet «&nbsp;Confidentialité&nbsp;», cliquez sur «&nbsp;Paramètres&nbsp;» et puis sur «&nbsp;Avancé&nbsp;».</li><li>Déplacez le curseur vers le bas pour autoriser les cookies.</li><li>Cliquez sur OK.</li></ul><p>Si votre navigateur est de type Mozilla Firefox</p><ul><li>Cliquez sur le bouton «&nbsp;Menu&nbsp;» (représenté par trois barres horizontales) dans le coin supérieur droit de votre navigateur et cliquez sur «&nbsp;Préférences&nbsp;».</li><li>Sélectionnez l’onglet «&nbsp;Vie Privée&nbsp;».</li><li>Dans l’espace «&nbsp;Historique&nbsp;», sélectionnez «&nbsp;Utiliser les paramètres personnalisés pour l’historique&nbsp;».</li><li>Cochez «&nbsp;Accepter les cookies&nbsp;».</li></ul><p>Si votre navigateur est de type Google Chrome</p><ul><li>Ouvrez Chrome.</li><li>Cliquez sur l’emblème des ‘trois petits points&nbsp;superposés’ qui se trouve en haut à droite de votre écran et puis sur «&nbsp;Paramètres&nbsp;».</li></ul><ul><li>Cliquez sur «&nbsp;Paramètres avancés&nbsp;».</li><li>Cherchez la section «&nbsp;Confidentialité et sécurité&nbsp;» et cliquez sur «&nbsp;Paramètres du contenu&nbsp;».</li><li>Cliquez sur l’option «&nbsp;Cookies&nbsp;».</li><li>Sélectionnez l’option «&nbsp;Autoriser les sites à enregistrer/lire les données des cookies&nbsp;».</li></ul><p>Si votre navigateur est de type Safari</p><ul><li>Cliquez sur «&nbsp;Préférences&nbsp;» dans le menu «&nbsp;Safari&nbsp;» (que vous trouverez dans la fenêtre du navigateur Safari).</li><li>Cliquez ensuite sur l’onglet «&nbsp;Sécurité&nbsp;».</li><li>Cliquez sur «&nbsp;Accepter les cookies&nbsp;» et sur «&nbsp;Toujours&nbsp;» ou «&nbsp;Provenant seulement des sites que je visite&nbsp;».&nbsp;</li></ul><p>Si vous consultez le site internet de [X], il est recommandé d’activer les cookies. Toutefois, vous restez libre de désactiver les cookies via les paramètres de votre navigateur. Pour désactiver les cookies, vous devez effectuer les opérations suivantes&nbsp;:</p><p>Si votre navigateur est de type Microsoft Internet Explorer</p><ul><li>Dans Internet Explorer, cliquez sur «&nbsp;Options Internet&nbsp;» dans le menu «&nbsp;Outils&nbsp;».</li></ul><ul><li>Dans l’onglet «&nbsp;Confidentialité&nbsp;», cliquez sur «&nbsp;Paramètres&nbsp;» et puis sur «&nbsp;Avancé&nbsp;».</li><li>Déplacez le curseur vers le haut pour bloquer les cookies.</li><li>Cliquez sur OK.</li></ul><p>Si votre navigateur est de type Mozilla Firefox</p><ul><li>Cliquez sur le bouton «&nbsp;Menu&nbsp;» (représenté par trois barres horizontales) dans le coin supérieur droit de votre navigateur et cliquez sur «&nbsp;Préférences&nbsp;».</li><li>Sélectionnez l’onglet «&nbsp;Vie Privée&nbsp;».</li><li>Dans l’espace «&nbsp;Historique&nbsp;», sélectionnez «&nbsp;Utiliser les paramètres personnalisés pour l’historique&nbsp;».</li><li>Désactivez la case «&nbsp;Accepter les cookies&nbsp;».</li></ul><p>Si votre navigateur est de type Google Chrome</p><ul><li>Ouvrez Chrome.</li><li>Cliquez sur l’emblème des ‘trois petits points&nbsp;superposés’ qui se trouve en haut à droite de votre écran et puis sur «&nbsp;Paramètres&nbsp;».</li><li>Cliquez sur «&nbsp;Paramètres avancés&nbsp;».</li><li>Cherchez la section «&nbsp;Confidentialité et sécurité&nbsp;» et cliquez sur «&nbsp;Paramètres du contenu&nbsp;».</li><li>Cliquez sur l’option «&nbsp;Cookies&nbsp;».</li><li>Désactivez l’option «&nbsp;Autoriser les sites à enregistrer/lire les données des cookies&nbsp;».</li></ul><p>Si vous utilisez un navigateur de type Safari</p><ul><li>Cliquez sur «&nbsp;Préférences&nbsp;» dans le menu «&nbsp;Safari&nbsp;» (que vous trouverez dans la fenêtre du navigateur Safari).</li><li>Cliquez ensuite sur l’onglet «&nbsp;Sécurité&nbsp;».</li><li>Choisissez de bloquer les Cookies.&nbsp;&nbsp;</li></ul><h2>5. Quels sont mes droits?</h2><p>Dès lors que les cookies peuvent impliquer un traitement de vos données personnelles, vous disposez du droit à un traitement légitime et sécurisé de vos données personnelles. En tant que sujet concerné par le traitement de vos données personnelles, vous disposez des droits suivants&nbsp;:</p><ul><li>Droit d’opposition : s’il existe des raisons impérieuses et légitimes, vous pouvez vous opposer au traitement de vos données personnelles.</li><li>Droit d’accès : toute personne qui prouve son identité a un droit d’accès aux informations relatives à l’existence ou non d’un traitement de ses données personnelles, ainsi qu’aux informations relatives aux finalités du traitement, aux catégories de données traitées, aux catégories de destinataires auxquels les données sont communiquées et à la durée de conservation des données personnelles.</li><li>Droit de correction&nbsp;: les données personnelles inexactes ou incomplètes peuvent toujours être corrigées, ou même effacées, sur demande de la personne concernée.</li></ul><p>L’exercice de ces droits se fait toujours conformément aux modalités prévues dans notre <a href=\\\"{!! page:4 !!}\\\">Charte vie privée</a>. Si, après avoir lu le présent document, vous avez encore des questions ou des remarques concernant les cookies, vous pouvez toujours nous contacter à [EMAIL].</p>\", \"nl\": \"<h2>1. Cookies</h2><p>Cookies zijn kleine data- of tekstbestanden die door websites en applicaties op uw lokale computer worden geplaatst. Dergelijke cookies kunnen verschillende doeleinden hebben: er zijn technische cookies (bijvoorbeeld voor het bijhouden van taalinstellingen), sessiecookies (tijdelijke cookies die verlopen na één sessie) en tracking cookies (cookies die uw gedrag op het internet volgen en bijhouden, om u op die manier een meer optimale gebruikservaring te kunnen aanbieden).</p><p>De Belgische Wet betreffende de elektronische communicatie van 13 juni 2005 bevat enkele bepalingen rond cookies en het gebruik ervan op websites. De wet is een omzetting van de Europese e-Privacyrichtlijn, wat betekent dat de cookiewetgeving verschillend kan geïmplementeerd worden in andere Europese lidstaten.</p><p>[X] is gevestigd in België en volgt bijgevolg de Belgische wetgeving inzake cookies.</p><h2>2. Doel en nut van cookies</h2><p>[X] wil elke bezoeker van het platform/de website zo goed mogelijk informeren over zijn rechten onder de Belgische wetgeving inzake cookies, en over welke cookies [X] gebruikt. Door het platform/de website te gebruiken gaat de bezoeker akkoord met het gebruik van cookies. Cookies helpen [X] om uw bezoek aan het platform/de website te optimaliseren, om technische keuzes te herinneren (bijvoorbeeld een taalkeuze, een nieuwsbrief, et cetera) en om u meer relevante diensten en aanbiedingen te tonen.</p><p>Als u het platform/de website van [X] wil consulteren, is het aan te raden dat de technische instellingen voor cookies ingeschakeld werden. Zonder ingeschakelde cookies kan [X] geen probleemloos bezoek op het platform/de website garanderen. Indien u de cookies liever niet wenst, bent u als bezoeker vrij om de cookies uit te schakelen.</p><p>Wij gebruiken cookies om uw bezoek aan ons platform/onze website te verbeteren. De cookies die wij gebruiken zijn veilig. De informatie die we verzamelen met behulp van cookies helpt ons om eventuele fouten te identificeren of om u specifieke diensten te laten zien waarvan wij denken dat ze voor u van belang kunnen zijn.</p><h2>3. Soorten cookies gebruikt door [X]</h2><p>We onderscheiden volgende types cookies, naargelang hun doeleinden:</p><h3>Essentiële / Strikt noodzakelijke cookies:</h3><p>Deze cookies zijn nodig om het platform/de website te laten functioneren en kunnen niet worden uitgeschakeld in onze systemen. Ze worden meestal alleen ingesteld als reactie op acties die door u zijn gesteld, zoals het instellen van uw privacyvoorkeuren, inloggen of het invullen van formulieren. Ze zijn noodzakelijk voor een goede communicatie en ze vergemakkelijken het navigeren (bijvoorbeeld naar een vorige pagina terugkeren, etc.).</p><h3>Niet-essentiële cookies:</h3><p>Deze cookies zijn op zich niet noodzakelijk om het platform/de website te laten functioneren, maar ze helpen ons wel een verbeterde en gepersonaliseerde website aan te bieden.</p><ul><li><strong>Functionele cookies:</strong><br />Deze cookies stellen het platform/de website in staat om verbeterde functionaliteit en personalisatie te bieden. Ze kunnen worden ingesteld door ons of door externe providers wiens diensten we hebben toegevoegd aan onze pagina’s.</li><li><strong>Analytische cookies:</strong><br />Met deze cookies kunnen we bezoeken en traffic bijhouden, zodat we de prestaties van ons platform/onze website kunnen meten en verbeteren. Ze helpen ons te weten welke pagina’s het meest en het minst populair zijn en hoe bezoekers zich door het platform/de website verplaatsen.</li><li><strong>Targeting / advertising cookies:</strong><br />Deze cookies kunnen door onze advertentiepartners via ons platform/onze website worden ingesteld. Ze kunnen door die bedrijven worden gebruikt om een profiel van uw interesses samen te stellen en u relevante advertenties op andere sites te laten zien.</li></ul><p>Wij gebruiken enerzijds onze eigen cookies&nbsp;(First Party cookies) en anderzijds cookies&nbsp;(Third Party cookies) van zorgvuldig geselecteerde partners met wie we samenwerken en die onze diensten op hun website adverteren.</p><h3>First Party Cookies:</h3><p><strong>Domeinnaam: [URL]</strong></p><div class=\\\"table-responsive\\\"><table><tbody><tr><td>Naam cookie</td><td>Type cookie</td><td>Bewaarduur</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><h3>Third party cookies:</h3><p><strong>Domeinnaam: [URL]</strong></p><div class=\\\"table-responsive\\\"><table><tbody><tr><td>Naam cookie</td><td>Type cookie</td><td>Bewaarduur</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><p><strong>Domeinnaam: [URL]</strong></p><div class=\\\"table-responsive\\\"><table><tbody><tr><td>Naam cookie</td><td>Type cookie</td><td>Bewaarduur</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></div><p>Neem kennis van ons <a href=\\\"{!! page:4 !!}\\\">Privacyverklaring</a> voor meer informatie omtrent de verwerking van persoonsgegevens door [X].</p><h2>4. Beheer van de cookies</h2><p>Zorg ervoor dat cookies zijn ingeschakeld in uw browser. Om cookies in te schakelen moeten de volgende handelingen uitgevoerd worden:</p><p>Bij browser - Microsoft Internet Explorer</p><ul><li>In Internet Explorer, klik op ‘Internetopties’ in het menu ‘Extra’.</li><li>Op het tabblad ‘Privacy’, verplaats de instellingen- schuifknop naar ‘laag’ of ‘accepteer alle cookies’ (instelling boven ‘medium’ schakelt cookies uit).</li><li>Klik op ‘OK’.</li></ul><p>Bij browser - Mozilla Firefox</p><ul><li>Klik op ‘Firefox’ in de linkerbovenhoek van uw browser en klik vervolgens op ‘Opties’.</li><li>Op het tabblad ‘Privacy’, zorg ervoor dat de ‘Websites laten weten dat ik niet gevolgd wil worden’ niet is aangevinkt.</li><li>Klik op ‘OK’.</li></ul><p>Bij browser - Google Chrome</p><ul><li>Klik op de drie puntjes naast de browserbalk bovenaan in uw browservenster en kies ‘Opties’.</li><li>Zoek het gedeelte ‘Privacy and security’ en klik op ‘content settings’.</li><li>Klik op de optie ‘cookies’.</li><li>Selecteer nu ‘Allow sites to save and read cookie data’.</li></ul><p>Bij browser - Safari</p><ul><li>Kies ‘Voorkeuren’ in het taakmenu. (Het taakmenu bevindt zich rechtsboven in het Safari-venster en ziet eruit als een tandwiel of klik op ‘Safari’ in het uitgebreide taakmenu.)</li><li>Klik op het Privacy tabblad. Zoek de sectie genaamd ‘Cookies en andere websitegegevens’</li><li>Duid aan dat u cookies aanvaardt.</li></ul><p>Als u het platform/de website van [X] wil consulteren, is het aan te raden dat u cookies ingeschakeld hebt. Echter, als u dit liever niet wenst, bent u als bezoeker vrij om de cookies uit te schakelen via uw browserinstellingen. Dit kan via volgende manieren:</p><p>Bij browser - Microsoft Internet Explorer</p><ul><li>Selecteer in Internet Explorer de knop Extra en selecteer Internetopties.</li><li>Selecteer het tabblad Privacy en verplaats onder Instellingen de schuifregelaar naar boven om alle cookies te blokkeren.</li><li>Klik op OK.</li></ul><p>Bij browser - Mozilla Firefox</p><ul><li>Klik op de menuknop en kies ‘Voorkeuren’.</li><li>Selecteer het paneel ‘Privacy &amp; Beveiliging’ en ga naar de sectie ‘Geschiedenis’.</li><li>Stel naast ‘Firefox zal’ in op ‘Aangepaste instellingen gebruiken voor geschiedenis’.</li><li>Stel&nbsp;‘Cookies van derden accepteren’&nbsp;in op&nbsp;‘Nooit’.</li><li>Sluit de pagina&nbsp;‘about: preferences’. Wijzigingen die u hebt aangebracht, worden automatisch opgeslagen.</li></ul><p>Bij browser - Google Chrome</p><ul><li>Selecteer ‘Meer Instellingen’ in de browserwerkbalk.</li><li>Selecteer onderaan de pagina de optie ‘Geavanceerd’.</li><li>Selecteer bij ‘Privacy en beveiliging’ de optie ‘Instellingen voor content’.</li><li>Selecteer ‘Cookies’.</li><li>Schakel ‘Sites toestaan cookiegegevens op te slaan en te lezen’ uit.</li></ul><p>Bij browser - Safari</p><ul><li>Kies ‘Voorkeuren’ in het taakmenu. (Het taakmenu bevindt zich rechtsboven in het Safari-venster en ziet eruit als een tandwiel of klik op ‘Safari’ in het uitgebreide taakmenu.)</li><li>Klik op het Privacy tabblad. Zoek de sectie genaamd ‘Cookies en andere websitegegevens’</li><li>Duid aan dat u cookies niet aanvaardt.</li></ul><p>Of raadpleeg hiervoor de help-functie van uw internetbrowser.</p><h2>5. Rechten van de bezoekers</h2><p>Aangezien cookies een verwerking van persoonsgegevens kunnen uitmaken, heeft u als betrokkene recht op de rechtmatige en veilige verwerking van de persoonsgegevens. Als betrokkene kan u volgende rechten uitoefenen:</p><ul><li>Recht op verzet: Indien er sprake is van een zwaarwegende en gerechtvaardigde redenen kan men zich verzetten tegen de verwerking van persoonsgegevens.</li><li>Recht op toegang: Iedere betrokkene die zijn identiteit bewijst, beschikt over een recht op toegang tot de informatie rond het al dan niet bestaan van verwerkingen van zijn persoonsgegevens, net als de doeleinden van deze verwerking, de categorieën van gegevens waarop deze verwerkingen betrekking hebben en de categorieën van ontvangers aan wie de gegevens worden verstrekt.</li><li>Recht op verbetering: Onnauwkeurige of onvolledige persoonsgegevens kunnen op verzoek van de betrokkene steeds verbeterd of zelfs uitgewist worden.</li></ul><p>De uitoefening van deze rechten gebeurt conform de modaliteiten zoals bepaald in onze <a href=\\\"{!! page:4 !!}\\\">Privacyverklaring</a>. Meer informatie over de rechten van bezoekers vindt u ook in de <a href=\\\"{!! page:4 !!}\\\">Privacyverklaring</a>. Mocht u na het lezen van deze Cookieverklaring toch nog vragen of opmerkingen rond cookies hebben, kan u steeds contact opnemen via [EMAIL].</p>\"}', '{\"en\": 1, \"fr\": 1, \"nl\": 1}', '{\"en\": null, \"fr\": null, \"nl\": null}', '{\"en\": null, \"fr\": null, \"nl\": null}', NULL, NULL, NULL, NULL, '2023-03-14 17:08:44', '2023-06-05 12:18:04'),
(7, NULL, NULL, 3, 0, 0, 0, '{\"en\":\"Home\",\"nl\":null}', '{\"en\":\"home\",\"nl\":null}', '{\"en\":\"home\",\"fr\":\"home\",\"nl\":\"home\"}', '{\"nl\":null}', '{\"en\":\"0\",\"fr\":\"0\",\"nl\":\"0\"}', '{\"nl\":null}', '{\"nl\":null}', NULL, NULL, NULL, NULL, '2023-03-14 17:28:19', '2023-06-07 09:12:15'),
(9, NULL, 6, 1, 0, 0, 0, '{\"en\":\"test2eng\",\"nl\":null}', '{\"en\":\"test\",\"nl\":null}', '{\"en\":\"cookie-policy\\/test\",\"fr\":\"politique-des-cookies\\/test\",\"nl\":\"cookieverklaring\\/test\"}', '{\"en\":\"<p>ENsteastaeta<\\/p>\",\"fr\":\"<p>FRsteastaeta<\\/p>\",\"nl\":\"<p>LNsteastaeta<\\/p>\"}', '{\"en\":\"1\",\"fr\":\"1\",\"nl\":\"1\"}', '{\"nl\":null}', '{\"nl\":null}', NULL, NULL, NULL, NULL, '2023-06-01 08:17:56', '2023-06-07 10:16:30'),
(10, NULL, NULL, 0, 0, 0, 0, '{\"en\":\"test page\",\"fr\":\"test page\",\"nl\":null}', '{\"en\":\"test-page\",\"fr\":\"test-page\",\"nl\":null}', '{\"nl\":null}', '{\"nl\":null}', '{\"nl\":null}', '{\"nl\":null}', '{\"nl\":null}', NULL, NULL, NULL, NULL, '2023-06-13 11:08:41', '2023-06-13 11:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_page_sections`
--

CREATE TABLE `typicms_page_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `template` varchar(255) NOT NULL,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`body`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_password_resets`
--

CREATE TABLE `typicms_password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_password_reset_tokens`
--

CREATE TABLE `typicms_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_password_reset_tokens`
--

INSERT INTO `typicms_password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('a.ahmad@crecentech.com', '$2y$10$Xa68Cfx9w0JzYu292DB0FOlIXU7d2t/t6fguFTXB/6K4xjPPleg7y', '2023-07-27 12:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_permissions`
--

CREATE TABLE `typicms_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_permissions`
--

INSERT INTO `typicms_permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'change locale', 'web', NULL, NULL),
(2, 'update preferences', 'web', NULL, NULL),
(3, 'clear cache', 'web', NULL, NULL),
(4, 'see navbar', 'web', NULL, NULL),
(5, 'see dashboard', 'web', NULL, NULL),
(6, 'read settings', 'web', NULL, NULL),
(7, 'update settings', 'web', NULL, NULL),
(8, 'see history', 'web', NULL, NULL),
(9, 'clear history', 'web', NULL, NULL),
(10, 'see unpublished items', 'web', NULL, NULL),
(11, 'read subscriptions', 'web', NULL, NULL),
(12, 'read news', 'web', NULL, NULL),
(13, 'create news', 'web', NULL, NULL),
(14, 'update news', 'web', NULL, NULL),
(15, 'delete news', 'web', NULL, NULL),
(16, 'read places', 'web', NULL, NULL),
(17, 'create places', 'web', NULL, NULL),
(18, 'update places', 'web', NULL, NULL),
(19, 'delete places', 'web', NULL, NULL),
(20, 'read blocks', 'web', NULL, NULL),
(21, 'create blocks', 'web', NULL, NULL),
(22, 'update blocks', 'web', NULL, NULL),
(23, 'delete blocks', 'web', NULL, NULL),
(24, 'read project_categories', 'web', NULL, NULL),
(25, 'create project_categories', 'web', NULL, NULL),
(26, 'update project_categories', 'web', NULL, NULL),
(27, 'delete project_categories', 'web', NULL, NULL),
(28, 'read contacts', 'web', NULL, NULL),
(29, 'create contacts', 'web', NULL, NULL),
(30, 'update contacts', 'web', NULL, NULL),
(31, 'delete contacts', 'web', NULL, NULL),
(32, 'read events', 'web', NULL, NULL),
(33, 'create events', 'web', NULL, NULL),
(34, 'update events', 'web', NULL, NULL),
(35, 'delete events', 'web', NULL, NULL),
(36, 'read files', 'web', NULL, NULL),
(37, 'create files', 'web', NULL, NULL),
(38, 'update files', 'web', NULL, NULL),
(39, 'delete files', 'web', NULL, NULL),
(40, 'read forum_categories', 'web', NULL, NULL),
(41, 'create forum_categories', 'web', NULL, NULL),
(42, 'update forum_categories', 'web', NULL, NULL),
(43, 'delete forum_categories', 'web', NULL, NULL),
(44, 'read forum_discussions', 'web', NULL, NULL),
(45, 'delete forum_discussions', 'web', NULL, NULL),
(46, 'read menulinks', 'web', NULL, NULL),
(47, 'create menulinks', 'web', NULL, NULL),
(48, 'update menulinks', 'web', NULL, NULL),
(49, 'delete menulinks', 'web', NULL, NULL),
(50, 'read menus', 'web', NULL, NULL),
(51, 'create menus', 'web', NULL, NULL),
(52, 'update menus', 'web', NULL, NULL),
(53, 'delete menus', 'web', NULL, NULL),
(54, 'read pages', 'web', NULL, NULL),
(55, 'create pages', 'web', NULL, NULL),
(56, 'update pages', 'web', NULL, NULL),
(57, 'delete pages', 'web', NULL, NULL),
(58, 'read partners', 'web', NULL, NULL),
(59, 'create partners', 'web', NULL, NULL),
(60, 'update partners', 'web', NULL, NULL),
(61, 'delete partners', 'web', NULL, NULL),
(62, 'read projects', 'web', NULL, NULL),
(63, 'create projects', 'web', NULL, NULL),
(64, 'update projects', 'web', NULL, NULL),
(65, 'delete projects', 'web', NULL, NULL),
(66, 'read roles', 'web', NULL, NULL),
(67, 'create roles', 'web', NULL, NULL),
(68, 'update roles', 'web', NULL, NULL),
(69, 'delete roles', 'web', NULL, NULL),
(70, 'read page_sections', 'web', NULL, NULL),
(71, 'create page_sections', 'web', NULL, NULL),
(72, 'update page_sections', 'web', NULL, NULL),
(73, 'delete page_sections', 'web', NULL, NULL),
(74, 'read slides', 'web', NULL, NULL),
(75, 'create slides', 'web', NULL, NULL),
(76, 'update slides', 'web', NULL, NULL),
(77, 'delete slides', 'web', NULL, NULL),
(78, 'read tags', 'web', NULL, NULL),
(79, 'create tags', 'web', NULL, NULL),
(80, 'update tags', 'web', NULL, NULL),
(81, 'delete tags', 'web', NULL, NULL),
(82, 'read translations', 'web', NULL, NULL),
(83, 'create translations', 'web', NULL, NULL),
(84, 'update translations', 'web', NULL, NULL),
(85, 'delete translations', 'web', NULL, NULL),
(86, 'read users', 'web', NULL, NULL),
(87, 'create users', 'web', NULL, NULL),
(88, 'update users', 'web', NULL, NULL),
(89, 'delete users', 'web', NULL, NULL),
(90, 'impersonate users', 'web', NULL, NULL),
(91, 'read taxonomies', 'web', NULL, NULL),
(92, 'create taxonomies', 'web', NULL, NULL),
(93, 'update taxonomies', 'web', NULL, NULL),
(94, 'delete taxonomies', 'web', NULL, NULL),
(95, 'read terms', 'web', NULL, NULL),
(96, 'create terms', 'web', NULL, NULL),
(97, 'update terms', 'web', NULL, NULL),
(98, 'delete terms', 'web', NULL, NULL),
(99, 'settings read', 'sanctum', '2023-06-08 08:52:54', '2023-06-08 08:52:54'),
(100, 'contacts create', 'sanctum', '2023-06-08 08:52:54', '2023-06-08 08:52:54'),
(101, 'contacts read', 'sanctum', '2023-06-08 08:52:54', '2023-06-08 08:52:54'),
(102, 'contacts update', 'sanctum', '2023-06-08 08:52:54', '2023-06-08 08:52:54'),
(103, 'contacts delete', 'sanctum', '2023-06-08 08:52:54', '2023-06-08 08:52:54'),
(104, 'settings.read', 'sanctum', '2023-06-09 05:49:07', '2023-06-09 05:49:07'),
(105, 'contacts.create', 'sanctum', '2023-06-09 05:49:07', '2023-06-09 05:49:07'),
(106, 'contacts.read', 'sanctum', '2023-06-09 05:49:07', '2023-06-09 05:49:07'),
(107, 'contacts.update', 'sanctum', '2023-06-09 05:49:07', '2023-06-09 05:49:07'),
(108, 'contacts.delete', 'sanctum', '2023-06-09 05:49:07', '2023-06-09 05:49:07'),
(109, 'roles.read', 'sanctum', '2023-06-09 07:11:17', '2023-06-09 07:11:17'),
(110, 'roles:read', 'sanctum', '2023-06-09 09:02:14', '2023-06-09 09:02:14'),
(111, 'navbar.see', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(112, 'dashboard.see', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(113, 'settings.update', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(114, 'history.see', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(115, 'history.clear', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(116, 'unpublished items.see', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(117, 'users.impersonate', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(118, 'blocks.read', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(119, 'blocks.create', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(120, 'blocks.update', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(121, 'blocks.delete', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(122, 'events.read', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(123, 'events.create', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(124, 'events.update', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(125, 'events.delete', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(126, 'files.read', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(127, 'files.create', 'sanctum', '2023-06-09 11:37:18', '2023-06-09 11:37:18'),
(128, 'files.update', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(129, 'files.delete', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(130, 'menulinks.read', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(131, 'menulinks.create', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(132, 'menulinks.update', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(133, 'menulinks.delete', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(134, 'menus.read', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(135, 'menus.create', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(136, 'menus.update', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(137, 'menus.delete', 'sanctum', '2023-06-09 11:37:19', '2023-06-09 11:37:19'),
(138, 'news.read', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(139, 'news.create', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(140, 'news.update', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(141, 'news.delete', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(142, 'page_sections.read', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(143, 'page_sections.create', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(144, 'page_sections.update', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(145, 'page_sections.delete', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(146, 'pages.read', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(147, 'pages.create', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(148, 'pages.update', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(149, 'pages.delete', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(150, 'projects.read', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(151, 'projects.create', 'sanctum', '2023-06-09 11:37:20', '2023-06-09 11:37:20'),
(152, 'projects.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(153, 'projects.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(154, 'project_categories.read', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(155, 'project_categories.create', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(156, 'project_categories.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(157, 'project_categories.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(158, 'roles.create', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(159, 'roles.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(160, 'roles.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(161, 'tags.read', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(162, 'tags.create', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(163, 'tags.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(164, 'tags.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(165, 'taxonomies.read', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(166, 'taxonomies.create', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(167, 'taxonomies.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(168, 'taxonomies.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(169, 'terms.read', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(170, 'terms.create', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(171, 'terms.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(172, 'terms.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(173, 'translations.read', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(174, 'translations.create', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(175, 'translations.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(176, 'translations.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(177, 'users.read', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(178, 'users.create', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(179, 'users.update', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21'),
(180, 'users.delete', 'sanctum', '2023-06-09 11:37:21', '2023-06-09 11:37:21');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_personal_access_tokens`
--

CREATE TABLE `typicms_personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `system_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `device_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_personal_access_tokens`
--

INSERT INTO `typicms_personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `ip`, `system_info`, `device_info`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'MyApp', 'd4d546df17261a86c4dc511280d941418ef1b9093b792cf1f3734cf3ad779b9c', '[\"*\"]', '', '', '', '2023-06-07 12:07:52', NULL, '2023-06-07 08:40:47', '2023-06-07 12:07:52'),
(4, 'App\\Models\\User', 1, 'MyApp', 'ee93e786fcdb79f77913a366a33d9420e5dea6b794ef8433f09a4ed5479441a2', '[\"*\"]', '', '', '', '2023-06-08 03:53:36', NULL, '2023-06-07 12:48:29', '2023-06-08 03:53:36'),
(5, 'App\\Models\\User', 4, 'MyApp', '3b3df668b650a7a3c931f4e4e3e5dd8db2f4ed5706d2f6bbe13cd974fa2ab072', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-07 12:50:31', '2023-06-07 12:50:31'),
(6, 'App\\Models\\User', 1, 'MyApp', 'd280b3cfbd0b30025023c5ee26ae14e3d987e6299e8f1a6097813d3407d82703', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-08 03:59:23', '2023-06-08 03:59:23'),
(7, 'App\\Models\\User', 1, 'MyApp', '3bcdc21f2b53458012cb76dcd2008b847a6bcbfebf63abc49b4b67acc40d7bbf', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-08 04:00:14', '2023-06-08 04:00:14'),
(8, 'App\\Models\\User', 1, 'MyApp', 'c9708e82663533a09ee54cc1951845920406461a80474743137f8d5b07f066cb', '[\"*\"]', '', '', '', '2023-06-08 04:15:58', NULL, '2023-06-08 04:08:07', '2023-06-08 04:15:58'),
(10, 'App\\Models\\User', 1, 'access-token', '6f4d1680f276e8368326fcde5a633690cfd93b7dc3c44256df720fd4c5eeb895', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-08 05:23:08', '2023-06-08 05:23:08'),
(11, 'App\\Models\\User', 1, 'web', 'c8c891bc14642c65f406f3c997455bfbbbbb68ffddd95f2c1c05967a96ba90c6', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-08 05:38:49', '2023-06-08 05:38:49'),
(13, 'App\\Models\\User', 1, 'web', '5c80a7d7109ba98025cdff6744e7355b3b31ef4ca283f727211fbb5de9c0e29a', '[\"*\"]', '', '', '', '2023-06-08 05:47:58', NULL, '2023-06-08 05:45:13', '2023-06-08 05:47:58'),
(14, 'App\\Models\\User', 1, 'web', '67f4429421e11588fa0319e620e33dbe1ce0803bcea7bbc73bbfe40a5cf6c743', '[\"*\"]', '', '', '', '2023-06-08 06:47:41', NULL, '2023-06-08 05:48:55', '2023-06-08 06:47:41'),
(15, 'App\\Models\\User', 1, 'web', 'f84571b6b592ae7e448b54af74a8403bccde2feb0f925e185d10c6a286cc103e', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-08 05:52:39', '2023-06-08 05:52:39'),
(16, 'App\\Models\\User', 1, 'web', '69a71d1f2c5774063863dd5a2346394d7449a1dcdc77d3bebf9abe4a8a5b6419', '[\"*\"]', '', '', '', '2023-06-08 09:05:21', NULL, '2023-06-08 06:50:53', '2023-06-08 09:05:21'),
(18, 'App\\Models\\User', 1, 'web', '8efc660bcc608a7e21b6b01da2685a20c3a14d0c34502903a5c836f7b61519d4', '[\"*\"]', '', '', '', '2023-06-09 04:51:57', NULL, '2023-06-08 09:25:42', '2023-06-09 04:51:57'),
(19, 'App\\Models\\User', 5, 'web', '6edf5cc4dbcea27993c63517c6f4a2f2f2e71828a7b4953154f880e87290e31f', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-08 10:27:06', '2023-06-08 10:27:06'),
(20, 'App\\Models\\User', 5, 'web', '40de48b45f4b9a7b89307322b3fda0b46a63b81c4c539987caee5f645afe3a65', '[\"settings:read\",\"contacts:read\"]', '', '', '', '2023-06-09 05:05:04', NULL, '2023-06-09 04:52:51', '2023-06-09 05:05:04'),
(21, 'App\\Models\\User', 5, 'web', '180f3f04ec17d19b47c946d16626058e4fdb6ca1e71676c7d10c47e2f95b5efa', '[\"settings read\",\"contacts read\"]', '', '', '', '2023-06-09 05:49:18', NULL, '2023-06-09 05:07:01', '2023-06-09 05:49:18'),
(22, 'App\\Models\\User', 5, 'web', 'd0e69865ebc9a8231d105947c78294196a8e7df88f546340d508ddcc7d00beb9', '[\"settings.read\",\"contacts read\"]', '', '', '', '2023-06-09 06:30:31', NULL, '2023-06-09 06:30:08', '2023-06-09 06:30:31'),
(23, 'App\\Models\\User', 5, 'web', 'fa6cefd051a5185bbd249fd274026b1cdfef22a6a2ab214d97a329d73ec000e2', '[\"settings:read\",\"contacts:read\"]', '', '', '', '2023-06-09 07:06:59', NULL, '2023-06-09 06:36:52', '2023-06-09 07:06:59'),
(24, 'App\\Models\\User', 5, 'web', '1b485c84607da4e0f628e00d5b8c5c38496b03f7254b14d32fecc25b90758af8', '[\"settings:read\",\"contacts:read\"]', '', '', '', '2023-06-09 07:11:17', NULL, '2023-06-09 07:07:20', '2023-06-09 07:11:17'),
(25, 'App\\Models\\User', 5, 'web', '79dbec9d5310ad5b9c810f83706be678441ac9fd3ba5f987a018a9ca11ca6cc2', '[\"settings:read\",\"contacts:read\"]', '', '', '', '2023-06-09 08:17:08', NULL, '2023-06-09 07:11:22', '2023-06-09 08:17:08'),
(26, 'App\\Models\\User', 5, 'web', '19e4a44891455b4238ab90e1e1748338702c3a369385b916b73ea5f0c9063e89', '[\"settings:read\",\"contacts:read\"]', '', '', '', '2023-06-09 08:54:41', NULL, '2023-06-09 08:44:42', '2023-06-09 08:54:41'),
(27, 'App\\Models\\User', 5, 'web', '35592fc4474176fac053751b837b581a5cc3a6d32873ea11e28cf6b9f012eca3', '[\"roles:read\"]', '', '', '', NULL, NULL, '2023-06-09 08:54:21', '2023-06-09 08:54:21'),
(28, 'App\\Models\\User', 5, 'web', '82435ac0fc338f41720d0a36377b60bafafd9807ad6cdb92b43babe66e2a3c2f', '[\"roles.read\"]', '', '', '', '2023-06-09 09:02:13', NULL, '2023-06-09 08:57:50', '2023-06-09 09:02:13'),
(29, 'App\\Models\\User', 5, 'web', 'be5b6cf45620e32c9c03ee5e0409598859c4f8d98195b1eefb555fd5422e7f8d', '[\"roles:read\"]', '', '', '', '2023-06-09 09:07:41', NULL, '2023-06-09 09:02:23', '2023-06-09 09:07:41'),
(30, 'App\\Models\\User', 5, 'web', '35490a162599e0b18279b74ea691ee8dec83fc96a7401aa0f325524cd34ba67c', '[\"roles.read\"]', '', '', '', '2023-06-09 09:39:37', NULL, '2023-06-09 09:07:45', '2023-06-09 09:39:37'),
(31, 'App\\Models\\User', 1, 'web', 'af418e61f8ff675969b2556c548b6192d510f3f18298040577616b5e8125a412', '[\"*\"]', '', '', '', '2023-06-09 09:43:42', NULL, '2023-06-09 09:39:43', '2023-06-09 09:43:42'),
(32, 'App\\Models\\User', 5, 'web', '4fef75ee9ec8ce2aa108ce9deef0ee306d6373eccb0458f43da3f982657be9e2', '[\"settings.read\",\"contacts.create\",\"contacts.read\",\"contacts.update\",\"contacts.delete\",\"roles.read\"]', '', '', '', '2023-06-09 09:47:09', NULL, '2023-06-09 09:44:04', '2023-06-09 09:47:09'),
(33, 'App\\Models\\User', 1, 'web', '603df220cefe85260fed51a20914f1e73f765bcd84a645d49c86a75b6d6b81b0', '[\"*\"]', '', '', '', '2023-06-09 09:47:54', NULL, '2023-06-09 09:47:24', '2023-06-09 09:47:54'),
(34, 'App\\Models\\User', 1, 'web', '284066e1f51c373afdac14d61b8bb4fc283289c521165ba798a1357be3cb4590', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 09:50:20', '2023-06-09 09:50:20'),
(36, 'App\\Models\\User', 5, 'web', '05ecd3aa4ad79f07fab806b00d13b5c15513277f4267c34f8b45ec32fe6be2a1', '[\"settings.read\"]', '', '', '', NULL, NULL, '2023-06-09 10:04:44', '2023-06-09 10:04:44'),
(38, 'App\\Models\\User', 5, 'web', '6878dbae7022cb783dcce7a2286339938c36790772508820e2feb5704303755e', '[\"settings.read\"]', '', '', '', NULL, NULL, '2023-06-09 10:17:47', '2023-06-09 10:17:47'),
(39, 'App\\Models\\User', 5, 'web', 'de0f7a5dede3102d663504d845d227d0fa59b07a098359f25ef9f81a476b7b5f', '[\"settings.read\"]', '', '', '', NULL, NULL, '2023-06-09 10:18:31', '2023-06-09 10:18:31'),
(40, 'App\\Models\\User', 1, 'web', '9f36d18fe1fe7e8e21b32fb9fa70da044d8f3a0a98e63d2615e071c6772112a5', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 10:18:55', '2023-06-09 10:18:55'),
(41, 'App\\Models\\User', 1, 'web', 'ab4595e3396222fd1095d0a4d6972d45353084801fb153b81d02a185a854f7b7', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 10:21:20', '2023-06-09 10:21:20'),
(42, 'App\\Models\\User', 5, 'web', 'fb9d1e93461a0aac61d5d00f5079c823bd352a7ff0d92b624bb27f1a5bf39cd9', '[\"settings.read\"]', '', '', '', NULL, NULL, '2023-06-09 10:21:36', '2023-06-09 10:21:36'),
(43, 'App\\Models\\User', 5, 'web', '3972914631410e458a5a3fd9f0ea4365fd36f296ab3c3f22d866cf3c11a96357', '[\"settings.read\"]', '', '', '', '2023-06-09 10:42:34', NULL, '2023-06-09 10:41:56', '2023-06-09 10:42:34'),
(44, 'App\\Models\\User', 5, 'web', '012f6241cd2d2e5040159460ec3d7831c79ea3968318e73e949d2e28177d687d', '[\"settings.read\"]', '', '', '', '2023-06-09 10:56:49', NULL, '2023-06-09 10:56:00', '2023-06-09 10:56:49'),
(45, 'App\\Models\\User', 5, 'web', '7503a69fadd1a744d06523cbf185e522983d419c438d15b554a23d3b24fc1742', '[\"settings.read\"]', '', '', '', NULL, NULL, '2023-06-09 10:59:37', '2023-06-09 10:59:37'),
(46, 'App\\Models\\User', 5, 'web', 'c9810eac8e220007a5ab34569f97d94f79ce69cbeb5d364394ebd9b8aad262c0', '[\"settings.read\"]', '', '', '', NULL, NULL, '2023-06-09 11:01:56', '2023-06-09 11:01:56'),
(47, 'App\\Models\\User', 5, 'web', '39dc248cc6e1c680fcd2a39be8960ddf4c44d5a94d81153a5175a1114ac5f23b', '[\"settings.read\"]', '', '', '', '2023-06-09 11:21:09', NULL, '2023-06-09 11:11:50', '2023-06-09 11:21:09'),
(48, 'App\\Models\\User', 1, 'web', 'd0f2a9b896c19cc3aec7607911107132b9e376b31fb86f76a4054ec867a5dc0d', '[\"*\"]', '', '', '', '2023-06-09 11:38:55', NULL, '2023-06-09 11:21:20', '2023-06-09 11:38:55'),
(50, 'App\\Models\\User', 1, 'web', '8659e2c8d953f3eb3a1adc8d1c6726a47a28ab0786d0c81cdd542bcbe4604cd2', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:01:50', '2023-06-09 12:01:50'),
(51, 'App\\Models\\User', 1, 'web', '3578e5cec57bae8fdfad55f8b7a07e9e46693a5565967f9f04436ef3ce5c4373', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:01:54', '2023-06-09 12:01:54'),
(52, 'App\\Models\\User', 1, 'web', '91fa6d17a7903eb4d177f1c8aa0b22f4ea8521caa8b91cbc7b1a92087661c8eb', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:01:57', '2023-06-09 12:01:57'),
(53, 'App\\Models\\User', 1, 'web', 'e8ad1f4ba66cc4d1ee24d60c03b9ffa75b5f80409b104814ced5cb6809aae46d', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:02:03', '2023-06-09 12:02:03'),
(54, 'App\\Models\\User', 1, 'web', '1f61c9d4850ddc27d685dae13b0ac73fe76d73b9880a9233d936e82c8713979c', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:02:57', '2023-06-09 12:02:57'),
(55, 'App\\Models\\User', 1, 'web', '2213e831bf83a8cc927ca99de94930d883e27d8ca8b214a8564506cdeeadd56d', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:03:40', '2023-06-09 12:03:40'),
(56, 'App\\Models\\User', 1, 'web', '69cc5eea3af1a77f6fa5129cd196e8944b9d9d4e41554a5ca3c4e2622175dbf0', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:05:37', '2023-06-09 12:05:37'),
(57, 'App\\Models\\User', 1, 'web', '6c683aa654602413da67ec4276faf2515a0422f5b0653b28d58d48de77445c71', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:06:28', '2023-06-09 12:06:28'),
(58, 'App\\Models\\User', 1, 'web', '8b7028334c2feb90c0dc2ba2f2326435a5fd7472c2a9303a488ddbb95b27d4ac', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:08:41', '2023-06-09 12:08:41'),
(59, 'App\\Models\\User', 1, 'web', 'e3b2a1c6806a09ec58d1f7212de4235df13f93729d34543e18ac95e3a7151934', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:09:00', '2023-06-09 12:09:00'),
(60, 'App\\Models\\User', 1, 'web', '12ba6fb0ba4b81acccd3aec433fc0d620f2d3843eadbb69d0d1ba40b356b8e76', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:10:04', '2023-06-09 12:10:04'),
(61, 'App\\Models\\User', 1, 'web', '376c0ecce397f3c57553dadde5d3997eaff4a95afb86312bbb410dd9c0ec60da', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:11:08', '2023-06-09 12:11:08'),
(62, 'App\\Models\\User', 1, 'web', 'fd91d4cc0ed251a874b49ae33225a889be370fc1c93585709226da3f1e154b4c', '[\"*\"]', '', '', '', NULL, NULL, '2023-06-09 12:11:26', '2023-06-09 12:11:26'),
(63, 'App\\Models\\User', 1, 'web', '1f5d345002f2ad1fbe4cfe57b8c19b7fe0c81692e54b185787d53654a20992b1', '[\"*\"]', '', '', '', '2023-07-26 12:20:04', NULL, '2023-06-12 05:20:14', '2023-07-26 12:20:04'),
(64, 'App\\Models\\User', 1, 'web', '97dbea98213d94aa1dc960d74ea680fa4728b3a19c16c6db0e775e2d8c378729', '[\"*\"]', '', '', '', NULL, NULL, '2023-07-24 06:46:42', '2023-07-24 06:46:42'),
(65, 'App\\Models\\User', 1, 'web', 'bce18a587c1ecb6b24b3557d23647ed42631f7a95003d398a9bf0aa83095723e', '[\"*\"]', '', '', '', NULL, NULL, '2023-07-24 08:05:40', '2023-07-24 08:05:40'),
(66, 'App\\Models\\User', 1, 'web', '08ebf17f183f6d758347042edf13862113fec36914d5bd230687c84ba99ab078', '[\"*\"]', '', '', '', NULL, NULL, '2023-07-24 08:27:55', '2023-07-24 08:27:55'),
(67, 'App\\Models\\User', 1, 'web', '1e747dee0f844f81b0c71d4bd93811507ac7eeeeeed5e3eac47826e59f5976fb', '[\"*\"]', '', '', '', NULL, NULL, '2023-07-24 08:28:01', '2023-07-24 08:28:01'),
(68, 'App\\Models\\User', 1, 'web', '8799f1e926462aeb9fe30934092800942cf398ddc5555a5d331de25bf8ccc934', '[\"*\"]', '', '', '', NULL, NULL, '2023-07-24 11:43:46', '2023-07-24 11:43:46'),
(69, 'App\\Models\\User', 1, 'web', 'ea4ad0794b30cf0ab6c9c795ef2ca30a4706b3ada4f24a9a529626d4f342a694', '[\"*\"]', '', '', '', NULL, NULL, '2023-07-25 12:20:37', '2023-07-25 12:20:37'),
(70, 'App\\Models\\User', 1, 'web', '85ad6ade3b0dd5dd6964551e5702322db7c04ade746f641a803b4de69eefb36e', '[\"*\"]', '', '', '', NULL, NULL, '2023-07-26 04:58:18', '2023-07-26 04:58:18'),
(71, 'App\\Models\\User', 1, 'web', 'fb679f46883a776aa6e6c31a5ab2c87ebc07132499f1295172bf42bb22d13dc8', '[\"*\"]', NULL, NULL, NULL, NULL, NULL, '2023-07-26 05:31:44', '2023-07-26 05:31:44'),
(72, 'App\\Models\\User', 1, 'web', 'e2c70dd9001715fb7d988bda93c2b4240391c30a20d15bb873bac4e66dbcd08a', '[\"*\"]', NULL, NULL, NULL, NULL, NULL, '2023-07-26 05:32:30', '2023-07-26 05:32:30'),
(73, 'App\\Models\\User', 1, 'web', '96c60d317bc9c0eede4235c8674827434096c69c54fc53c73bfd9df77d2f1391', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 05:34:54', '2023-07-26 05:34:54'),
(74, 'App\\Models\\User', 1, 'web', 'b195fbb805239dc16e53d9f85142629cfbf88f62580634d1906ae9900657e0c0', '[\"*\"]', NULL, '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 05:43:34', '2023-07-26 05:43:34'),
(75, 'App\\Models\\User', 1, 'web', '0082d3f05b703c344173daf271ef499cad7733a6f8d48cb9a2774cb66e2258cd', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 07:23:38', '2023-07-26 07:23:38'),
(76, 'App\\Models\\User', 1, 'web', '27b585a6eca9d7a13c1ffe7c2ecc4efc32e3f3db1781d7a01d760b5a43039b36', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 07:24:36', '2023-07-26 07:24:36'),
(77, 'App\\Models\\User', 1, 'web', '91cf85d6dd83783c0483a3f2a40f77ce97806ffd59967a382057ac241649e788', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 07:28:52', '2023-07-26 07:28:52'),
(78, 'App\\Models\\User', 1, 'web', '30204b4f709e344e38137c20659ad4a7dc361cf3c887af4abc066d7ada30a303', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 07:30:02', '2023-07-26 07:30:02'),
(79, 'App\\Models\\User', 1, 'web', '9a4109889f7dfea9067fc00a583e623bebf538faeb718505b387cbd79efc0610', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 07:30:49', '2023-07-26 07:30:49'),
(80, 'App\\Models\\User', 1, 'web', '369e8471141691a32fbfe85c386664e361176764ee48e54f1083ed43ceb34430', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 08:07:33', '2023-07-26 08:07:33'),
(81, 'App\\Models\\User', 1, 'web', '7414231ff4e71337543afb7e2fc9c3d6d9e140e60384c4ee37caf7eacf483c8d', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 08:38:33', '2023-07-26 08:38:34'),
(82, 'App\\Models\\User', 1, 'web', '6eb7c8908d867b34c9d4a7764dc18f575a93e73ebf0d1247d302450152f69bff', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 08:41:19', '2023-07-26 08:41:19'),
(83, 'App\\Models\\User', 1, 'web', '677798a8ba036affd74805d70c08135d57d8b2f6e9285a56e30f3a41549a3a14', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 08:48:06', '2023-07-26 08:48:06'),
(84, 'App\\Models\\User', 1, 'web', 'd24855476cd91be513364c2d44a8809077572fb1e6442a5d65bf4df15bfb1d76', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 08:48:21', '2023-07-26 08:48:21'),
(85, 'App\\Models\\User', 1, 'web', '86631438bab51bf954d2093a2fe9f10197d8648d4d8bf1cf652842dd47b3c945', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 08:48:38', '2023-07-26 08:48:38'),
(86, 'App\\Models\\User', 1, 'web', '6b97f52a33aa5091dd59e2cffa7ab14cd2c25b84fca05d4349609869056b999f', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 08:50:47', '2023-07-26 08:50:47'),
(87, 'App\\Models\\User', 1, 'web', 'd30ec7798057d9eb34fcc5237ccdfcfa055c5c035893370fbf787117fa99bd1b', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:26:42', '2023-07-26 10:26:42'),
(88, 'App\\Models\\User', 1, 'web', '9a03bf688e2eab57831924cf30720fd9e296088a355595b093dfe534bfcaa94c', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:29:15', '2023-07-26 10:29:15'),
(89, 'App\\Models\\User', 1, 'web', '025de9c72994063f51910e2efefd448bc6bf7e73a52b9b1e8078e0e79c1126d3', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:29:53', '2023-07-26 10:29:53'),
(90, 'App\\Models\\User', 1, 'web', '71a25a90a5b4a2b0d2ed31ecd23ee6588702ae153fc7d500a4c2bae3518c4072', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:32:32', '2023-07-26 10:32:32'),
(91, 'App\\Models\\User', 1, 'web', 'b3282f4a35105bb5a96e7a453b3d48c4488c8463193c3e75fce26c4e44a9bf33', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:32:49', '2023-07-26 10:32:50'),
(92, 'App\\Models\\User', 1, 'web', '59af642f45ab9e5753144a97858a2dc289d664b730812fa40e1d30139b81b188', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:40:26', '2023-07-26 10:40:26'),
(93, 'App\\Models\\User', 1, 'web', '4e9859ff24b722c2eeb1e0ffa9602c49ff966bc610edffea5a7984c62ff72c43', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:42:48', '2023-07-26 10:42:49'),
(94, 'App\\Models\\User', 1, 'web', '20f63c4376eeb2cb8a18d00ffe85bce0bfb21e2a6047803a6be6fc5119653bd5', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 10:43:06', '2023-07-26 10:43:06'),
(95, 'App\\Models\\User', 1, 'web', '7f7ba3a2365521a872e14fbf294946b61361e1c012f543da54560576c2fadde0', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 11:21:42', '2023-07-26 11:21:42'),
(96, 'App\\Models\\User', 364, 'web', 'e01fe5d33f6ff8be747ce4b1b6a5ac28c9ff8791f8c87e73c0ee54f8847862d2', '[\"*\"]', NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:12:20', '2023-07-26 12:12:20'),
(97, 'App\\Models\\User', 364, 'web', '70970c05d239e433bc1070eed050649dfb8377a6d9ea051cf7933e91dadb8d8e', '[]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 12:17:26', '2023-07-26 12:17:26'),
(98, 'App\\Models\\User', 366, 'web', '8aab44dac08eb505421acbe5c2fa0307faafc779b237f41662fcdaf5588225fa', '[\"*\"]', NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:46:57', '2023-07-26 12:46:57'),
(99, 'App\\Models\\User', 1, 'web', 'd3987505485231885741a5ff64efe0ccdc5b2ba9c62bbaf0105fdb9eb6858106', '[\"*\"]', NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:51:14', '2023-07-26 12:51:14'),
(100, 'App\\Models\\User', 2, 'web', 'a04dc4ff7c89e50b64b08f1b09d86738c935a2561011bc5d33403fd808bbd5eb', '[]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-07-26 12:55:04', '2023-07-26 12:55:04'),
(101, 'App\\Models\\User', 1, 'web', '8469751fdbe10497a9f91b9cd6fe187c19fc5b8becc50f193fcee02b7ea26ce5', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', '2023-08-25 07:04:03', NULL, '2023-07-27 07:00:31', '2023-08-25 07:04:03'),
(102, 'App\\Models\\User', 1, 'web', 'c52a89d6ca6d8d6a1ca26503dfb0da608da8400032eba1136b1c365a1b0c3f0d', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-02 06:31:14', '2023-08-02 06:31:15'),
(103, 'App\\Models\\User', 1, 'web', 'b52692192565a76c8d1abe00f264019381c22d5c4b20209d49126815cd9d4090', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-02 06:31:25', '2023-08-02 06:31:25'),
(104, 'App\\Models\\User', 2, 'web', '7c2c676d2a0c7a6b9cd8b5d62f4f7d38d92a774edf0644d6019bddf91a4a62f1', '[]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-02 06:31:49', '2023-08-02 06:31:49'),
(105, 'App\\Models\\User', 1, 'web', '35a0840dae03bd5b16c254238f511a0377cd1b190bc8bf8b992cd8be94a8061c', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-02 06:34:59', '2023-08-02 06:34:59'),
(106, 'App\\Models\\User', 1, 'web', '4275a08cb15c0d4e87bbc2d37dce46d234e13f9b107d6421205de2e090a4d8e6', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-02 06:35:12', '2023-08-02 06:35:12'),
(107, 'App\\Models\\User', 1, 'web', '32013f94d78c93ec9572cb044dc5251fca2489cfc1299b095b54e57002656c43', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":\"153.02\"}', '2023-08-07 10:22:02', NULL, '2023-08-07 06:39:46', '2023-08-07 10:22:02'),
(108, 'App\\Models\\User', 1, 'web', '5f20c715e0e3c0710d5163f589952c8fd5086d54dbcf625ec09c0684ff2ca84b', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":\"153.02\"}', NULL, NULL, '2023-08-07 08:47:06', '2023-08-07 08:47:07'),
(109, 'App\\Models\\User', 1, 'web', 'a89e4be63fce68149ec2f4f52f9d169ea51d7e1a5fc4d69a5c62da4ccbf24425', '[\"*\"]', '192.168.5.82', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 07:30:39', '2023-08-09 07:30:40'),
(110, 'App\\Models\\User', 1, 'web', '6165266b92579461dd544a09bd95c60535a034c7d9cb5a3be148a5bec3cbe18e', '[\"*\"]', '192.168.5.82', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 08:16:58', '2023-08-09 08:16:58'),
(111, 'App\\Models\\User', 1, 'web', 'cd32c818a377cfd8489047c6b90d5d5b85862a563cd82c7c95e4d434f11abb44', '[\"*\"]', '192.168.5.82', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 08:21:28', '2023-08-09 08:21:28'),
(112, 'App\\Models\\User', 1, 'web', 'dea1b5d85adac9188e64bcec6fe3f68b29260df2922a5f17a024d3ae4f89adb3', '[\"*\"]', '192.168.5.82', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 08:22:51', '2023-08-09 08:22:51'),
(113, 'App\\Models\\User', 1, 'web', '277e7858ffe87c43f86f069c1d37243ebc577c632643c6f347e51eaf37940ca8', '[\"*\"]', '192.168.5.82', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 08:26:18', '2023-08-09 08:26:18'),
(114, 'App\\Models\\User', 1, 'web', '52c46504effd63ccebbb3466272842aa7ab81ab42fbb47d39d7dd6a22e8cca40', '[\"*\"]', '192.168.5.82', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 08:42:19', '2023-08-09 08:42:19'),
(115, 'App\\Models\\User', 1, 'web', '002f1a9e92890f363059c15821ff561636cded02ec56d87273b7be7d1d9427dc', '[\"*\"]', '125.209.112.81', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 08:50:20', '2023-08-09 08:50:20'),
(116, 'App\\Models\\User', 1, 'web', '6e701a300fb89f7c53ece360eb0c3d576e8b206b02d160a8d5c619aecde3e55e', '[\"*\"]', '202.166.170.161', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":15.4}', NULL, NULL, '2023-08-09 08:51:42', '2023-08-09 08:51:42'),
(117, 'App\\Models\\User', 1, 'web', '123aba9781c7deb2b73a8469b2f83c9125f8442ee939a1675e208ff542f14336', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-09 08:59:43', '2023-08-09 08:59:43'),
(118, 'App\\Models\\User', 1, 'web', '9c196709c0f66b8a515ca8808c79c4ec22075ea2fe2c05f343098a7b00d400e8', '[\"*\"]', '125.209.112.81', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 06:35:06', '2023-08-10 06:35:07'),
(119, 'App\\Models\\User', 1, 'web', 'ecb08c11129e55199e88da98f3b69f9b00ab60fcf0600a2869ff8b1d34160e54', '[\"*\"]', '125.209.112.81', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 06:43:49', '2023-08-10 06:43:49'),
(120, 'App\\Models\\User', 1, 'web', '29d21893ecbec1fb6384dc21a5eb81493f29ea712c866f2f990659ef808d5fb5', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-10 07:07:24', '2023-08-10 07:07:24'),
(121, 'App\\Models\\User', 1, 'web', 'f4ada298cf8fcc0fbe184988d0235b2672b059ecbfa3239f08d5c3f78e9c61cd', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 07:09:28', '2023-08-10 07:09:28'),
(122, 'App\\Models\\User', 1, 'web', '2c07956e91164d572035fd38d8f5863891d581871ada69c26d503062a7083178', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-10 07:10:14', '2023-08-10 07:10:15'),
(123, 'App\\Models\\User', 1, 'web', 'c0ccb9f8a7acdc060857bcdb618ffac31648f651c99be76c630f4f9fcf2777b7', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 09:33:19', '2023-08-10 09:33:19'),
(124, 'App\\Models\\User', 1, 'web', 'cd2b569dd7832c97c568bc0293db83e97833adc192db336b9789069e03bc258f', '[\"*\"]', '125.209.112.85', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 10:24:42', '2023-08-10 10:24:42'),
(125, 'App\\Models\\User', 1, 'web', '6932b0bb046e1a0fb74c83ee0516a6b97ed6115be01d63a5b405849291930023', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 10:40:55', '2023-08-10 10:40:55'),
(126, 'App\\Models\\User', 1, 'web', '632f9ec0508f4c241fc4af60900059ef6ec38a6004dd9872ecdf86cad79f3a60', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 11:05:27', '2023-08-10 11:05:28'),
(127, 'App\\Models\\User', 1, 'web', 'f9d93bde85bfc00395610cc39470a58d1e1bc86f04b20b94b63cb53bbd04ac1d', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 11:06:14', '2023-08-10 11:06:14'),
(128, 'App\\Models\\User', 1, 'web', 'd9d8f2ff5b268ee3cff048a5d276f41a740bc995edf23c765a421799cb81e42d', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 11:07:13', '2023-08-10 11:07:13'),
(129, 'App\\Models\\User', 1, 'web', '731ad588f0cbecee33929199bd68be704fc1a8bd1fe00ec6666e4c63ada9bc70', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 11:13:51', '2023-08-10 11:13:51'),
(130, 'App\\Models\\User', 1, 'web', 'd3bfcb35a1b08ec266fde55faff69f8afdc109c39a331c62f20769129e59e35d', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 12:53:26', '2023-08-10 12:53:26'),
(131, 'App\\Models\\User', 1, 'web', 'b836e82e282a2697ebeaa325306a0de94c8b3400cd474493af04c9809facea3e', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 12:56:57', '2023-08-10 12:56:57'),
(132, 'App\\Models\\User', 1, 'web', '6e65fc0bec35d2f5c25540de44f732750c9fc21d9b03db251dc556bc1c21262b', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 12:57:53', '2023-08-10 12:57:53'),
(133, 'App\\Models\\User', 1, 'web', '6e44add3e7df09818989746f87e9b2651c97fb5eab3162f6c1885ecf4f979fa0', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-10 13:01:30', '2023-08-10 13:01:30'),
(134, 'App\\Models\\User', 1, 'web', '5fe1129a12e9348ef119dba9f60dc30cb085097d4d8475bb9ff06d83e8d86d9a', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-18 06:47:05', '2023-08-18 06:47:05'),
(135, 'App\\Models\\User', 1, 'web', 'c85d02da85a0d24eb16c762e223561c72bbc4fb81eb2228929adbc7cc67be1b2', '[\"*\"]', '125.209.112.85', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-18 06:59:37', '2023-08-18 06:59:37'),
(136, 'App\\Models\\User', 1, 'web', '59eac6feed54d321abe9f9ecb40e8625e92b4478ec6fe254da17042953e58784', '[\"*\"]', '125.209.112.85', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-18 07:03:37', '2023-08-18 07:03:37'),
(137, 'App\\Models\\User', 1, 'web', 'e5bc065aae2ab78b72d0bd81335a6d56669653ac4c8fc6a07252f4480036ec4e', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-18 08:19:16', '2023-08-18 08:19:17'),
(138, 'App\\Models\\User', 1, 'web', '5a5fcb011e150999972412e004f2688fac541952001f11909cff9ed5aadee3de', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-18 12:43:00', '2023-08-18 12:43:00'),
(139, 'App\\Models\\User', 1, 'web', '92339537fa76996787d94173bcdfe294cf15227246725cdeaad9af41cf3848e6', '[\"*\"]', '202.166.170.161', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-18 13:04:47', '2023-08-18 13:04:47'),
(140, 'App\\Models\\User', 1, 'web', '5c341e7d107f040d7416e88dd80dcbd98d05d3cf47dfa8749932d0602d728ce0', '[\"*\"]', '125.209.112.85', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-21 06:16:56', '2023-08-21 06:16:56'),
(141, 'App\\Models\\User', 1, 'web', '50c2db872742fd20989633e450240e187b7facc23b549feeeb199610ca627787', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', NULL, NULL, '2023-08-21 07:38:52', '2023-08-21 07:38:52'),
(142, 'App\\Models\\User', 1, 'web', '014d9e6138edb11133a40bbffdef8cd3ab69785703c4f35a20c615338659ca15', '[\"*\"]', '125.209.112.85', '{\"os\":\"Windows\"}', '{\"browser\":\"firefox\",\"version\":\"Firefox 116\"}', NULL, NULL, '2023-08-21 10:26:41', '2023-08-21 10:26:41'),
(143, 'App\\Models\\User', 1, 'web', 'a7f35e088c37293fa8b1b763b0a95e7370d5b97e4bf9bb679f7091422960ff8a', '[\"*\"]', '192.168.1.5', '{\"os\":\"windows\"}', '{\"browser\":\"chrome\",\"version\":153.02}', '2023-08-25 06:56:11', NULL, '2023-08-25 06:55:30', '2023-08-25 06:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_projects`
--

CREATE TABLE `typicms_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `summary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`summary`)),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`body`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_project_categories`
--

CREATE TABLE `typicms_project_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`status`)),
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_project_categories`
--

INSERT INTO `typicms_project_categories` (`id`, `image_id`, `position`, `status`, `title`, `slug`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, '{\"en\":\"0\",\"fr\":\"0\",\"nl\":\"0\"}', '{\"nl\":null}', '{\"nl\":null}', '2023-03-21 17:46:59', '2023-03-21 17:46:59');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_registrations`
--

CREATE TABLE `typicms_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `number_of_people` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_roles`
--

CREATE TABLE `typicms_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_roles`
--

INSERT INTO `typicms_roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Top Administraotors', 'sanctum', '2023-03-14 17:08:44', '2023-07-31 12:53:16'),
(2, 'visitor', 'sanctum', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(4, 'Test Permissions', 'sanctum', '2023-06-08 08:56:31', '2023-07-31 12:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_role_has_permissions`
--

CREATE TABLE `typicms_role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_role_has_permissions`
--

INSERT INTO `typicms_role_has_permissions` (`permission_id`, `role_id`) VALUES
(104, 1),
(104, 4),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1);

-- --------------------------------------------------------

--
-- Table structure for table `typicms_settings`
--

CREATE TABLE `typicms_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) NOT NULL DEFAULT 'config',
  `key_name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_settings`
--

INSERT INTO `typicms_settings` (`id`, `group_name`, `key_name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'config', 'webmaster_email', 'info@example.com', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(2, 'fr', 'website_title', 'Site web sans titre', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(3, 'fr', 'status', '1', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(4, 'nl', 'website_title', 'Untitled website', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(5, 'nl', 'status', '1', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(6, 'en', 'website_title', 'Untitled website', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(7, 'en', 'status', '1', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(8, 'config', 'welcome_message', 'Update Welcome to the administration panel of TypiCMS.', '2023-03-14 17:08:44', '2023-06-07 08:55:03'),
(9, 'config', 'auth_public', '0', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(10, 'config', 'register', '0', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(11, 'en', 'website_baseline', NULL, '2023-06-07 06:01:37', '2023-06-07 06:01:37'),
(12, 'fr', 'website_baseline', NULL, '2023-06-07 06:01:37', '2023-06-07 06:01:37'),
(13, 'nl', 'website_baseline', NULL, '2023-06-07 06:01:37', '2023-06-07 06:01:37'),
(19, 'config', 'image', 'slide-1.png', '2023-06-13 06:49:06', '2023-06-13 06:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_taggables`
--

CREATE TABLE `typicms_taggables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `taggable_id` bigint(20) UNSIGNED NOT NULL,
  `taggable_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_tags`
--

CREATE TABLE `typicms_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_taxonomies`
--

CREATE TABLE `typicms_taxonomies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `validation_rule` varchar(255) DEFAULT NULL,
  `result_string` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`result_string`)),
  `modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_array() CHECK (json_valid(`modules`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_taxonomies`
--

INSERT INTO `typicms_taxonomies` (`id`, `position`, `name`, `title`, `slug`, `validation_rule`, `result_string`, `modules`, `created_at`, `updated_at`) VALUES
(1, 1, 'Any_Body', '{\"nl\":null}', '{\"nl\":null}', 'required', '{\"nl\":null}', '[null]', '2023-05-31 08:33:02', '2023-05-31 08:33:02');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_terms`
--

CREATE TABLE `typicms_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `taxonomy_id` bigint(20) UNSIGNED NOT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`title`)),
  `slug` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`slug`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typicms_translations`
--

CREATE TABLE `typicms_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `translation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT json_object() CHECK (json_valid(`translation`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_translations`
--

INSERT INTO `typicms_translations` (`id`, `key`, `translation`, `created_at`, `updated_at`) VALUES
(1, 'More', '{\"fr\":\"En savoir plus\",\"en\":\"More\",\"nl\":\"Meer\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(2, 'Skip to content', '{\"fr\":\"Aller au contenu\",\"en\":\"Skip to content\",\"nl\":\"Naar inhoud\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(3, 'languages.fr', '{\"fr\":\"Français\",\"en\":\"Français\",\"nl\":\"Français\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(4, 'languages.en', '{\"fr\":\"English\",\"en\":\"English\",\"nl\":\"English\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(5, 'languages.nl', '{\"fr\":\"Nederlands\",\"en\":\"Nederlands\",\"nl\":\"Nederlands\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(6, 'Search', '{\"fr\":\"Chercher\",\"en\":\"Search\",\"nl\":\"Zoeken\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(7, 'message when contact form is sent', '{\"fr\":\"Merci\",\"en\":\"Thank you\",\"nl\":\"Dank u\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(8, 'message when registered to event', '{\"fr\":\"Merci\",\"en\":\"Thank you\",\"nl\":\"Dank u\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(9, 'Add to calendar', '{\"fr\":\"Ajouter au calendrier\",\"en\":\"Add to calendar\",\"nl\":\"Toevoegen aan Agenda\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(10, 'All news', '{\"fr\":\"Toutes les actualités\",\"nl\":\"Alle nieuws\",\"en\":\"All news\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(11, 'All events', '{\"fr\":\"Tous les événements\",\"nl\":\"Alle evenementen\",\"en\":\"All events\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(12, 'Partners', '{\"fr\":\"Partenaires\",\"nl\":\"Partners\",\"en\":\"Partners\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(13, 'Latest news', '{\"fr\":\"Dernières actualités\",\"nl\":\"Laatste Nieuws\",\"en\":\"Latest news\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(14, 'Upcoming events', '{\"fr\":\"Prochains événements\",\"nl\":\"Aankomende evenementen\",\"en\":\"Upcoming events\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(16, 'Error :code', '{\"fr\":\"Erreur :code\",\"nl\":\"Error :code\",\"en\":\"Error :code\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(17, 'Sorry, you are not authorized to view this page', '{\"fr\":\"Désolé, vous n’êtes pas autorisé à voir cette page\",\"nl\":\"Sorry, u bent niet bevoegd om deze pagina te bekijken\",\"en\":\"Sorry, you are not authorized to view this page\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(18, 'Go to our homepage?', '{\"fr\":\"Souhaitez-vous visiter notre :a_openpage d’accueil:a_close ?\",\"nl\":\"Wilt u onze :a_openhomepage:a_close te bezoeken?\",\"en\":\"Go to our :a_openhomepage:a_close?\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(19, 'Sorry, this page was not found', '{\"fr\":\"Désolé, cette page n’a pas été trouvée\",\"nl\":\"Sorry, deze pagina is niet gevonden\",\"en\":\"Sorry, this page was not found\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(20, 'Sorry, a server error occurred', '{\"fr\":\"Désolé, une erreur serveur est survenue\",\"nl\":\"Sorry, er is een serverfout opgetreden\",\"en\":\"Sorry, a server error occurred\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(21, 'Open navigation', '{\"fr\":\"Aller à la navigation\",\"nl\":\"Open navigatie\",\"en\":\"Open navigation\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44'),
(22, 'message when errors in form', '{\"fr\":\"Veuillez s’il vous plaît corriger les erreurs ci-dessous\",\"en\":\"Please correct the errors below\",\"nl\":\"Gelieve de onderstaande fouten te corrigeren\"}', '2023-03-14 17:08:44', '2023-03-14 17:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_users`
--

CREATE TABLE `typicms_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `app_password` blob DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT 0,
  `superuser` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_policy_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `macAddress` varchar(50) DEFAULT NULL,
  `client_app_version` varchar(10) DEFAULT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `box` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `api_token` char(36) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typicms_users`
--

INSERT INTO `typicms_users` (`id`, `email`, `password`, `app_password`, `activated`, `superuser`, `privacy_policy_accepted`, `first_name`, `last_name`, `phone`, `image`, `macAddress`, `client_app_version`, `locale`, `street`, `number`, `box`, `postal_code`, `city`, `country`, `preferences`, `api_token`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'a.ahmad@crecentech.com', '$2y$10$88zHnNmT6VyuZgmUZv1LV.UmDKzlDtoYOcZKLY8nLKb1BmmGhZTOu', '', 1, 1, 0, 'admin', 'admin', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '-68f64c2c-29f0-422a-b827-c6c37982a3a', '2023-03-14 17:09:05', 'wgN74RSTovuHZJz520lbmCVKbkolGd3T2CxP9syZRHpcObTtYBczTR8TbN9z', '2023-03-14 17:09:05', '2023-06-05 12:17:44'),
(2, 'visitor@crecentech.com', '$2y$10$gvbRPKhNfY73WqgD/Sd6jezyLtS7MxD1HrbbxdEehg28RbXEfCuVa', '', 1, 0, 0, 'visitor', 'visitor', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'e28558a6-c250-4ae5-90ab-0532abb9d848', '2023-06-05 09:13:09', NULL, '2023-06-05 09:13:09', '2023-06-05 09:13:09'),
(3, 'administrator@crecentech.com', '$2y$10$kXbyivNrmaiJkwa9OzHEy.WnCi1MhRBla5VsLzKSAV8x7d1wgO6Aq', '', 1, 0, 0, 'administrator', 'administrator', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3cb0091f-66cf-4ac1-ab34-94eb4dd0c2ef', '2023-06-05 09:17:33', NULL, '2023-06-05 09:17:33', '2023-06-05 09:17:33'),
(5, '5.ahmad@crecentech.com', '$2y$10$N/EZoeSaE5RTFvn275TcbuqNuAOmE98DU/uAYvObXnGAvyy8F1Moa', '', 1, 0, 0, '5.Adnan-update', 'Ahmad', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-08 10:27:06', '2023-07-31 08:09:26'),
(42, 'm.ejaz@crecentech.com', '$2y$10$o0VHnNxgewLB15TJYA8WmuVLIwvf16HSYwver7eCofbab2bPS4blm', 0x8b06d7b0f3214542f343c34267eff12c, 0, 0, 0, 'Muhammad', 'Ejaz', '333 8608839', 'http://app.staffviz.com/files/18', '24PN9W1', '10.2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PfaAi0QRFN92fQwPOCqoWERZgJcC5XEbQrvXlRp3wMqnda5jou84QVX7K8Po', '2022-08-30 09:49:51', '2022-08-30 09:49:51'),
(48, 't.haider@crecentech.com', '$2y$10$f94WqHDGZN8Y8v6Pm8PmN.0ZBDz.77IDpJowx0LeTtISAGJiBPN7O', 0xc0a846460230a1f5c2c3f74f11fa2cfe, 0, 0, 0, 'Taseer', 'Haider', '03355141232', 'http://app.tasktrackerhub.com/files/5', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AOLhDhDJROFW9BIqCemzpfTAQ6MhuZzYwFLJKSSxlhw9toZYLVmJLpMntZWO', NULL, '2023-06-23 13:02:50'),
(49, 'h.jameel@crecentech.com', '$2y$10$iIeKvqxFuWwXcZ9263eCpOrw1lRi0121G0P6CwrScTQSLtxVfPehG', 0xbf9f18d0eefe9274d6d9bb903a2f3e8a, 0, 0, 0, 'Hasan', 'Jameel', '3327268382', 'tth/crt_23/users/49/632c59de828b1_1663850974.jpeg', '5CG6094H70', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SQuf5fkBURRFplMrry9Kq0zHOqgwHNV1ADqKOcorQHnlk0bGaQiOct5ufEmu', NULL, '2022-08-31 09:12:05'),
(50, 'n.ahmed@crecentech.com', '$2y$10$aY8qsRLOYfW3.x/3F2AqG.vkysZdj3KpVD680kJPslON39r/ixz6K', 0x52ec035bd34814ddb358e16edd381e16, 0, 0, 0, 'Naseer', 'Ahmed', '3093646990', 'tth/har_52/users/50/6pU1HFmzKLcPwBzv_1669810702.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'POV4je6MpqJ1U2dJ74ov3gH7iA34eZuOiMcBTgrlOdyBI0i8JtKCB1onrRnT', NULL, '2023-01-03 05:15:38'),
(51, 'z.khan@crecentech.com', '$2y$10$do9qGjr3JLC7S/fchL7fFuQsx5ja3yWEgyot09Z4T0dcx.hD6I3Y.', 0x8da1d62b01af38d58996edd8c77fac37, 0, 0, 0, 'Zaima', 'Khan', '3340712563', '', '5CB3500XG6', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AkDdyKDdX6D96ZfqlNciHcSNZnFMr2DbDrU2heSUMG3DTmOBHFloaxreOt8ZiL7U', NULL, NULL),
(52, 't.austin@crecentech.com', '$2y$10$SMVodvwIo1hUDP.MbfAYqOzV1.lISHyVem1EdlCKMoiTFNkoibq5e', 0xf70a737222c50c2a09f40c4b6174d01d, 0, 0, 0, 'Tyler', 'Austin', '03037711814', '', 'CNU4199NP7', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UsYQVavPG8dHqc7YQD511QyxImD8BtkJCKVlYgSBAvpQjkLCNgSkrETnGbPq', NULL, NULL),
(53, 's.sinek@crecentech.com', '$2y$10$/J3hCCDN0fx3cQ28NMbewOEhjX5fhtIAA9qXKb6RAn591/HyHIxS.', 0x0e2fb9e0c0df0a73cda60527770c81f3, 0, 0, 0, 'Simon', 'Sinek', '03028805156', '', 'CNU410C6M6', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KjEwWUVdryaoEdpJ5UYJKfbIVGjAMUvn3lULaJHUjIOpRNuCjmkZmuYOZc9Wr9nN', NULL, NULL),
(54, 'd.miller@crecentech.com', '$2y$10$ZMFAL4ZFPdIwZJbokASCVu24X9wZaoZHz0kW2M2Uam/CNYBtUfrki', 0x733eb869a608b5eb84e81da235121811, 0, 0, 0, 'David', 'Miller', '3017872219', '', 'GBV3K32', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'xcqR4tXa2pEC0IZAb8qFbsLzvCoGI9lQg82M0lWlvPCqY4DTgceFEGTuho3n3Aoj', NULL, '2023-03-02 11:18:41'),
(55, 'n.jones@crecentech.com', '$2y$10$TbyDkebb374ny8GPqko2lerbchOLzSDi271ElYg7bzM1K0haPhd7O', 0x28af43243d4caaf30b7c6a89b0fb768f, 0, 0, 0, 'Nathan', 'jones', '3002792694', '', 'HRWRZW1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sCNK4JpMoIyHRf4ca5FKQ882vLlPSV5b5FT8TrNa33qKFqDrRKV3L5QN3CsA', NULL, '2023-03-20 04:23:10'),
(56, 's.robinson@crecentech.com', '$2y$10$tbEWO7td4tyoio3XxhCzNuxVToNQgSTxKSoQRnMEJLeSuqzswT.ZS', 0x6e72a164a5ed5943dd4d94ad649f9ac66ffd83bbd6e760c570b548545bf5d6b4, 0, 0, 0, 'Sophia', 'Robinson', '03022031527', '', 'R90BGL5X', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'iFGzCrPBFkXbvvC3hWLBDKJ3cCpaMJPhhrPRPWQzP9mDHRdFJKfDmctu1hSE', NULL, NULL),
(57, 'd.steele@crecentech.com', '$2y$10$swz6DuXb4/Vo4jEIfm1aduI0hpmhrLaR.3neNwZMu59UExl..m50K', 0x430db8d906be92fdffa3c332666a4921, 0, 0, 0, 'David', 'Steele', '3327268382', '', 'CNU410DT22', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Q2OX949dVGtP38jw5kcVOXfSvq7sexYnBV3ctExqKCy0aFmAI5wKK68nVt7l', NULL, NULL),
(58, 'm.hendrick@crecentech.com', '$2y$10$X6dBvDuSpMjk7GVtfqotsew7zWaxnxrEnuOpRUp85BKE15KD.ODlW', 0xc6fba9f6bbfb816c090eeed11ecb2791, 0, 0, 0, 'Mike', 'Hendrick', '3327268382', '', 'R901A02P', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'C1BdP2cZPemrzUdLPy5ek3bUdeXPrpFOBiM6FhvUBkm3eTd1dqXd8eDU8d9wLq9f', NULL, NULL),
(59, 'b.hart@crecentech.com', '$2y$10$.DHIPNt8rfZeO9YKjslhNO3xTWpCcEIEdWynUa4tkYQsKmnk/Ikay', 0x2032d7bddf406594fdb30178840d65bd, 0, 0, 0, 'Bret', 'Hart', '3074064255', '', 'R900B8AD', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MLKfKPtaH0zt5cWjYq2syjqVQJ2DYJdz59G49mcqG8jYkeCxvkAPxXV39D7L', NULL, NULL),
(60, 'c.kelly@crecentech.com', '$2y$10$E0LPvbpMxZ28kzQx4WvXMev9ncorQDYBM5xkJ6COtjb.OYCE4q9Ji', 0xd5c39c1819f58f7068c6aa7f2f56af11, 0, 0, 0, 'Christoph', 'Kelly', '3229044444', 'tth/crt_23/users/60/U4ODUrI9NluirKlt_1661957866.png', 'CNU415B7Q4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'zaLqNf3A2hTAg45KGMvpe0RZjJbgYqTeW4oQQDXJwcvpgKZT64Rfg7g765mMywug', NULL, NULL),
(61, 't.holland@crecentech.com', '$2y$10$BAjQkFaFaj.FDmJPAgQwVeCtKHkYrsSBzqP.2evXVndmuc4WZjurS', 0xee544685c79c952bf3974f04396ff332, 0, 0, 0, 'Tom', 'Holland', '3097756313', '', '8CG43803QJ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FtV6yx7rAIKwJTG1yIYGhkgpUtGPGgl48LsDQigtR2dEhe46Rt3PCF5W0Bd3', NULL, NULL),
(62, 's.asif@crecentech.com', '$2y$10$9x73xyFm5Z.XsIErDgVj8.oQyeK83FP48/RUZcvy8kNmt5.6mcx8O', 0x2d481f63327177f17c6d5a7413e0c256, 0, 0, 0, 'Shahrez', 'Asif', '335 4903531', 'tth/crt_23/users/62/blU0Vhglm2sRNRjL_1670585974.png', '2CE3440MDL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MB5czWNe2hsJ8ax99duBWMylIIt4iMyq2eGZf5Y1AY8qw9oTNzhDFclsF1jP', NULL, NULL),
(63, 'm.azam@crecentech.com', '$2y$10$2fm9aAGwUbH8PGg.dIGWE.dJVmv7Mr/oJEn5ywRftXU5bUDo41cG2', 0xd62b70835bdb19268ea569949a7138a9, 0, 0, 0, 'muhammad', 'azam', '3346688187', '', 'BC71D42', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'phaRTV6GsXpK9oeotttvGMlKdcgGq4l8XTVPYE6rY51a7T2Yc8yFEA4Tw2qmNWwW', NULL, NULL),
(64, 'j.henwick@crecentech.com', '$2y$10$m3zxDTVf621gc3z4UrDxKOZL6QNKAOYgYtZXqBy8vh0XfdTJaCLyq', 0x0a3c102f7503bc9b0a8a1975b206ac2b, 0, 0, 0, 'jessica', 'henwick', '3122226001', '', '2CE4161KN0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ip04exaUgpQPDT07DSwHI9Xa2p66UtNgKsW8EHErHqgBD6AZtsvIAUlUSBkjNHva', NULL, NULL),
(65, 'z.sohail@crecentech.com', '$2y$10$lrIy/UAPaArswOxu1iFy9.vUdJq5vOKeMOXVbrzzyNqstGzBzlLzW', 0x2bd763f86b1f08ba539367d2b6dd6b1f, 0, 0, 0, 'Zeeshan', 'Sohail', '3454932321', '', '6Y9W602', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'k290y7n2TgKgXliqykvx36RD8H3w3w9uBHij5E47k4h2Bec5GOVB4HnEAOb5', NULL, '2023-05-05 09:11:42'),
(66, 'r.tariq@crecentech.com', '$2y$10$yEpjB/lvbxJfimZ4J3fEd.Vi2vleR/4U41ua3eAYA13p402h2lwF6', 0x7eb44c0406561a83e172e41cbf6949b1, 0, 0, 0, 'Rukhshanda', 'Tariq', '3379822844', 'tth/crt_23/users/66/BjBaQQIdNdRWjkZ7_1681282375.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ybf1w0gVvlkofs6wGvmexQrjNa8JytegKqCpfyx7KHRV3kd36iWTVhc6L7cW', NULL, NULL),
(67, 'm.hussain@crecentech.com', '$2y$10$9ag/l7UZE7nAN9Hu/YoUaeuviUP03YoD0WXuG7zUr/WqQCAaAZ1hq', 0x41ec8ad0012181a6146dd7ef0b87c0f0, 0, 0, 0, 'Syed', 'Mustafa', '3205477994', 'tth/crt_23/users/67/18fpFRlTfNzofpqP_1663252632.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '89qv7EJZmvDGBMCenJ67OPHYmUHBfuNNw5vBC1YZA0ZtYyDgPjBpko3ZM6Tv', NULL, NULL),
(68, 'n.nabi@crecentech.com', '$2y$10$c4moGGqPUObJsthlB7VQwu43JxgltqEdg7f0OsUTKfxLX.oeJnjAq', 0x1e82358cf981c8d9def557a38b06f953, 0, 0, 0, 'Noor', 'Nabi', '3084752497', '', '5CG50336MC', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VK66NJlgxAVVDiq2l5zxn3HjosBGxdNrdGNY9e6wQb8QBzFEOLAAtLBZJYoK', NULL, NULL),
(69, 'c.john@crecentech.com', '$2y$10$N2maaSWsUqnl1rCpWWBS8.Mxk0F/n7Yh5bwTnnOfKqjU4578k9qQ6', 0x2f5e02362c71a6e8670a011b2592f6dd, 0, 0, 0, 'Chirs', 'John ( Zeeshan )', '3030209511', 'tth/crt_23/users/69/pYGnfIAK9Q6yQC3N_1672211213.jpeg', '2CE3512068', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rRbjfFeCbwEndGkgdcJ9eIDzQI39hubeXuH9TaIc7LRX2Q04HyarA7l4KB50', NULL, NULL),
(70, 'ethan@crecentech.com', '$2y$10$li15pqgnVh/DdD0r1lRO3eGqJbnWScuVxJAITMa1z0ngGYUa1nkdq', 0x646e451cbcdca85656fab7e9f4e3becf, 0, 0, 0, 'ethan', 'klein', '0000000', '', '5CB24503GZ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mpz0TxczDscwEv2FC7RnAl64Od5UkIVtgWPR5kQmRCv98MDK6iFD9qYYfxvVpGWo', NULL, NULL),
(71, 'm.damon@crecentech.com', '$2y$10$KEpYZcLnBHZkatadzKhnBumqrCj.JdUswrPzjTjgKyxACso9mMWgS', 0x384a007d00e21b7dfe60a18c95463f42, 0, 0, 0, 'Matt', 'Damon', '3436168634', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MVSK7KLuE188jjxYScRLTiAxAH1B3k8pXW8d2noNCoZUzWV9iRMkJEVmWL25o5Vh', NULL, '2022-09-05 11:18:41'),
(72, 'a.logan@crecentech.com', '$2y$10$Hht.ZoeB1cfPWiMoMlErBu7R8sfI6nDs5iH6QkupmfuqWQTn/L/We', 0x1edaf76a7511ee04649957b64a01eb53, 0, 0, 0, 'Aidan', 'Logan', '3069013588', '', '5CG5243PS9', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sX1nXQhPN7uujrLm8XDL1NR3Kc1ZufkaXmaPmbGeVfGVmfpxDQDaLa9JTZku', NULL, NULL),
(73, 'J.carter@crecentech.com', '$2y$10$F/NFQvlheH4GfCV/KYKBxurlBREYQfBGi1rJ0tRBmGWTNI6qzv8qi', 0xb13d5c9c38f3c6cfaebd3f7f466f1af1, 0, 0, 0, 'Jimmy', 'Carter', '3452662068', '', '1C74VV1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9Lio1UyOUlrLlq1i2oRdY1i0Xa5vnKGjEAv9XOC3H3caSLNlkGb1wnT0ABfqK7dr', NULL, '2022-11-18 13:02:34'),
(74, 'n.cage@crecentech.com', '$2y$10$bj1/jVqF7pIZFYgr7Vzqt.ZC.zy6FXq/F.k4Rd9qglcJWsojS3uZm', 0x41628e83c391f701e609f50cb93e1473, 0, 0, 0, 'Nicolas', 'Cage', '3101656624', '', 'unknown', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9DWTqjwkRuYGNfANRXxbh40C2F0cFRmpO6BC7L0a5sQ7DLG3CsC1n5V08EA7th5C', NULL, NULL),
(75, 's.reed@crecentech.com', '$2y$10$vH4inrsB38ZrcACQoswjd.umt2LHfj56c.mFE/okAvbZCbmXCP1Te', 0x64476cace6d04a44c1653b98ba152369, 0, 0, 0, 'Seth', 'Reed', '3003890888', '', '5L6FH12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cDVEzhhIQ4VsDCnSFKL5bPLPFSw0cWey0Ouj7rV1rpBbJIFP6bX95KFOkkslDYVU', NULL, NULL),
(76, 'f.solis@crecentech.com', '$2y$10$SmIjW0oaQeHBBwq7PcpMtOWNZIEGo9UBrlCLt.uVP4IYKpI3zbq/O', 0x39cc532dac1e8efcfd09af6e54f7e98f, 0, 0, 0, 'Felix', 'Solis', '3036379799', '', '5CB35041Y1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '445zfQsxvpFx2WYVKHsXB65p9qxdHUT6oN0HwCh9hrk3NbudLshblw4T2d75nCdb', NULL, NULL),
(77, 'd.lawrence@crecentech.com', '$2y$10$s9BVwXQCZdtnlgA7qO4IX.J4GrqifGcJvVN70UGxnRFkLhA3HMWI.', 0x8a7bfeca35be362bce5b5abdcad26639, 0, 0, 0, 'David', 'Lawrence', '+923007826803', 'tth/crt_23/users/77/OeWW6sKLjaj9o3k3_1672122980.jpeg', '5CB34504WL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6Hh6g8g2mfQH3eKCviWn3VvcYNyTDUrTGnRA8Wtxbp4sqvMqVFo56BpbioZa', NULL, '2022-12-14 11:11:37'),
(78, 'v.glover@crecentech.com', '$2y$10$cmQH7Jc8xodSx1BxKD2UGuMB5Yjs1zuFZx7Ff/zU2asgtvgUEQ7Ua', 0x5d01cc0ff0757d70abd101f94b3c195f, 0, 0, 0, 'Victor', 'Glover', '3454848189', '', '9BZ12R1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tFobGFPU5HWxRmG9StxoT4F9EgkBsvl170ax5kW5HFa3yG3rssKfbLZlHAWyRljH', NULL, NULL),
(79, 'b.harder@crecentech.com', '$2y$10$Zypm.v7i9xkwfqMV2NJzX.q1rDtxpzmCUvJWlgnIXDcy.tI9SdqoS', 0x29ee93c4cedfdba174a7585b956f4f63, 0, 0, 0, 'Bill', 'Harder', '3464683252', 'tth/crt_23/users/79/RUIw1B6Up6cICSXj_1662457424.png', '5CB2461HMJ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hGflHCqT81ezH1DHOr1zsHJ9CMvsVnNJa1x6Bc1UBey7EVfhpB0RE7meY702zHhw', NULL, NULL),
(80, 'j.taylor@crecentech.com', '$2y$10$9/9sXZult5JnQvRzJzW40Ord1jnRneRPID3NPjItI2RhfBww3CXvi', 0xf5cd444ffc3fde8b19f8685c50ba86ae308838ddc63404ba914b9af9e0dae451, 0, 0, 0, 'Jaffery', 'Taylor', '3164362950', '', '318QM22', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'zylWqrAPL9Dr2QNHFPWW3bkexJP2viT8LohHoKLbzE7eOGBER2PbzLbuCqKK45Xr', NULL, NULL),
(81, 'a.ashe@crecentech.com', '$2y$10$lj8EIOOm1rw7JJb2lP4DR.u2nK5IG5VGv4ygw0x4LQXaapRFjevGe', 0xeae622dca3c2d155689d9c7cb0e0333a, 0, 0, 0, 'Arthur', 'Ashe', '03023959233', '', '5CB3081QGK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'xFFgcOASeWBljSab8OXdyl461bHw3iay1CAQBqNZ7Eez5vRKGfHpxDcTHCRuMLDx', NULL, NULL),
(82, 'L.wheeler@crecentech.com', '$2y$10$vFAVdk6F9WrJxyhMxNCZpeMW723mQGuvZU9cblFMN4OVUnprTFmPG', 0xc406ed2e5509027ba0578fd5455017fa, 0, 0, 0, 'Luis', 'Wheeler', '3442131185', '', 'JMWMW52', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'iKll8gVEO32qqSyGjzLtSignMaMQnyLddhepr8L1OC0tb5PpOpk2m1140UY4CZaw', NULL, NULL),
(83, 'm.nicholas@crecentech.com', '$2y$10$vwWHEKnoLcon0MDSGP3rNOKKBJbuNnSRhFcpCpY8omm0K4uEbljNO', 0xba7de3157b536e1f95d38d70875c3e75, 0, 0, 0, 'Mark', 'Nicholas', '3166107476', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cFIgMMf5j7QCiBeoI1ppUIeHF0v2tTIji2VCDzsJIeQd1oQ2gbBHt98gGTTg4jiP', NULL, '2022-12-02 14:34:46'),
(84, 'e.albert@crecentech.com', '$2y$10$EwefFeVxkJ7NTvcqvHgci.Oe6GkS94Wat4Ao/Uj7Vr8GutGfAdC16', 0xea619a277de4fefc2884b9ee833bb458, 0, 0, 0, 'Edward', 'Albert', '3418482438', '', '5CG425DRHY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'O7I5ZLthJ9t1UDVhB3IWo3vOPVNCzzx4redn2R3FrHc97mGee19EyLWRqF013Ou5', NULL, NULL),
(85, 'j.denly@crecentech.com', '$2y$10$yVub3ENKYk.qD0aQsdtZj.W.3t.ihM3mfNUdtgpRSm7s92vnf.mMi', 0x1f80332cd6c481cbe8bbf09e2fdbac5d, 0, 0, 0, 'Joe', 'Denly', '3048091960', '', '1MXYTY1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'I9Q8ZcoshjXLysMEHMJGdG4ZvUtGjqhZ1QjELBcA0HdifavuXvSXlcZR4CxoFFCT', NULL, '2022-11-30 12:06:02'),
(86, 'k.roberts@crecentech.com', '$2y$10$K/ehZ9NVUVOocVRJWlvfeu7w7DBpY3p2hMeP552CpMkYMGrZEdxgC', 0xa74fe0f80692c7c2d9c2267763ab0583, 0, 0, 0, 'Keith', 'Roberts', '3244073755', '', '8JX5CW1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AnwRFrNxoMtu5mhXPFYswbqhyCS0MR8LABphCFrFdKm9u9TWD45Ip6EoBif0', NULL, NULL),
(87, 'T.fury@crecentech.com', '$2y$10$4aVwIUAsYfsrNf7WQtKjBOJu.J0rE1phKQrlqOQkmgXImujXTzC8K', 0xb1342c04d10cc82c32fcc7eea204ca2c, 0, 0, 0, 'Tyson', 'Fury', '3455965592', '', '7SXZK12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fYNriIHJjbdJoqrMcTXIbrzb5KrI3HWpqfj5vgLDYIS5k4PDHS70cUPsoNwy', NULL, '2022-12-02 13:48:48'),
(88, 'b.allen@crecentech.com', '$2y$10$BLD.97sYEBOogDzmYzCaWOZV6gT21vGe//bducLe0laVkcY/DpgUC', 0x2458cd7ef1638593ee16532d945ff0d2, 0, 0, 0, 'Ben', 'Allen', '3025073022', '', 'CND5107ZMY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0jhSapcLqBBDjX7pCa9ldgcdVfS7YdL32wzfwd7pEmAD48UV4lNUbLwTc9xgEVWy', NULL, NULL),
(89, 'e.norton@crecentech.com', '$2y$10$B.TqpETEqg3bDGxlZ20SQOF7ym4ML6W9uDjKckz6fZlZquFPvvqse', 0xf93bfe047b6206320f2d1bf68c3bcba7, 0, 0, 0, 'Edward', 'Norton', '3093455550', '', 'CNU421BC8X', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4G5ujteQ04AugGeRYdMjKCnypbzzpbgaVfZoOOiIJXBfyg42qqzjjnouakhjYFGC', NULL, NULL),
(90, 'p.martin@crecentech.com', '$2y$10$GTy.fKi538eXX5R7zOofGOPc7gaC.ktKUQeDPwWGBHvpsrHVBDKhW', 0x314ba3fb4eb3f107fad7a143dbb135cb, 0, 0, 0, 'Peter', 'Martin', '315-215-0310', '', '66YVXY1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'skb9eo553MZblCDYe9QqfzubMAUG0GyM0Vtx0be5firbk1m0VXBstl7aVXK1Ntvq', NULL, NULL),
(91, 'j.lever@crecentech.com', '$2y$10$9tzAc.ifLR1d18Ju2TVKauRpdPFVykGxFfxvgfd.qe0Zfa.tzzIPC', 0x1d6ba49bf0b23169ab63e981e510694f, 0, 0, 0, 'John', 'Lever', '3004479516', '', '5CG5102CVS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'T2tUN58c5XuZoqWWHXb2oyy8PPmF2daSKLsNhR7KkXEIZbIXLtiDiFl1ZncIAd1h', NULL, NULL),
(92, 'Steven@crecentech.com', '$2y$10$hkUJu5rvq9M/mcXpas2zxO/nBp0YCLAYDHclTrIGQQa3NXk1Odb1O', 0xfb8bbe7539e1e7c3f3348329bb78cf6c, 0, 0, 0, 'Steven', 'Wilson', '3426718612', 'http://app.staffviz.com/files/24', 'CNU352DB4S', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lgp7ocQnGJVWcaYnPM8IVyDxrJuBSk1vkKaL15XHti1qJadBkLG6gwYnm8je', NULL, NULL),
(93, 'Richard@crecentech.com', '$2y$10$PF5Zw/VVfCuj47mhWCFr.O97guleAFK4uRS7WarXKN5G2GqJDsvV2', 0xdbf9fcc4a9c7e91459fdaf6c84e580fe, 0, 0, 0, 'Richard', 'Wilson', '3152150310', '', '5CG4350C9T', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ZTByZJikM1xuSkghdimjkeLCKnMR5hmJ2Xd9R7LFLlhdWLAd3ek35m9IvG5R', NULL, '2022-11-28 15:46:38'),
(94, 'j.phillips@crecentech.com', '$2y$10$21rMkSGC1DoiRiWqJYkeren.vp.4LPPois3ZhlZqPHoyxsvwhHgJS', 0xa96351983f2ba053c24f5919dea135a9, 0, 0, 0, 'Jack', 'Phillips', '3340440564', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GpJaBXaPZgfAL0W2diJoe9tt1C4jZYm1Zu0SI2gKGFjis2dzuoZGxxOfeFoYuo99', NULL, NULL),
(95, 'W.scott@crecentech.com', '$2y$10$47S0yYAteNo5HJHIEx.GNec9KcNVMcnnByC.v38aZ4GgIpdblQOkq', 0xe96633c56b691d2dc0e3b91e76952e8e, 0, 0, 0, 'Walter', 'Scott', '3152150310', '', 'GTZZP12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'w5rjZwlUoAdZbxOoll6vK6Xyt8VwM8J3d5437YlWohWJIZ5TPN92xgmh2LwyO572', NULL, NULL),
(96, 'H.erickson@crecentech.com', '$2y$10$H8GVzMD7njaArUlxC8N1meRbJ/JLiuYZwGx3hjHYLrEWmsDVG3KfK', 0x0e55d5bc3593b5e6b69a814ae419d79a, 0, 0, 0, 'Hector', 'Erickson', '3216117786', '', '5CG5170RXB', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'wPRjdGTVRd17I8ypcSu97H7Ztpi51xDN2Y6tkhVkcNdt7hYGn2kWFMBy1qxX6Xhb', NULL, '2023-01-06 14:02:17'),
(97, 'Winston@crecentech.com', '$2y$10$rj0Iw24HA.5JbpWEKQkvLOTp6QGv9P8fNNBtQ.D7mGa2/mY5/Bjnq', 0x9fa929a7abb25ddb3ea323c2a51ad8b7, 0, 0, 0, 'Harry', 'Winston', '3056614142', '', '5CB34005JX', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4eK4zHK9f7WIp9cBR1ChUcwYVMWZxLHVypd57ikU3YK574hiOLWLsaPwjB5Da8VO', NULL, NULL),
(98, 'j.roy@crecentech.com', '$2y$10$4kaVr1fevW7nQ9Z4asnDoeC/hCWd.KYuEzY.b.oJ9g.80Esq6Bl3O', 0x1b2bec623e2c8436d7986c0ff94cc214, 0, 0, 0, 'Jason', 'Roy', '3152150310', '', '5CG50630RB', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uvn2Asa1JfuTSXgRHcgwZYcHl4MiepZF2gXxq1X7cU3rtmol66qp7QUuvTwco5CG', NULL, NULL),
(99, 'j.franco@crecentech.com', '$2y$10$SI2a5yj1D8vR3qFk7LP.LuR4uVlAbkYF0J9xrPsJlE402gtBx81xS', 0x0ce49ede920018cfad86856aeb693f42, 0, 0, 0, 'Josh', 'Franco', '3184179711', '', 'J8CJNX1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'F2PKzKqq1nxzfpJ5YRxTjENafLsbhtJCbDNti7RRyitXTRIuYcUmFTlO5Sa9s2Fw', NULL, NULL),
(100, 's.howard@crecentech.com', '$2y$10$yZm7K8Lkvd/crmJkWHUlHuzwrnoeliePcDafhzYyrzNjJnXWR4IZW', 0x02b93dae8069ee2631bef032e211fd88, 0, 0, 0, 'Seth', 'Howard', '03002000200200200', '', 'CNU416BVB1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GjVVglb1NywvUMOnNvenXCLj2BYVrzdYyh3Y4Z8PWJninnOsrQzyq9kmW9P7', NULL, NULL),
(101, 'Harvey@crecentech.com', '$2y$10$89bgv.b2xNraaO0w4cevQOnwuws6aqeVPc4L.AlsvZK9kcZsfULk2', 0x99b6529e7488b677fc145ec5c9e9f926, 0, 0, 0, 'Harvey', 'Dent', '3087109998', '', 'JB0BW32', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mA4QRQgpcu5PCxYaHJOVuQcIaDZn5PBTmMb7USetcWVbwfzVDFAd1MU4gcPVMk5q', NULL, '2022-12-12 13:39:43'),
(102, 'j.emburey@crecentech.com', '$2y$10$fFPRyVViQm3Z/sffE.k/ZO.jgz54VPvxBQHNOj5HZ3QMexCMchtju', 0x6b34b18336c75eb0a884379f733fd230, 0, 0, 0, 'John', 'Emburey', '01234689965', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SsCrn13J3vZetWP0mFJMXxkUdilDEQW9q5i4pAy43LfCPmM4P3g6SgtkEMEX', NULL, '2022-09-05 06:47:48'),
(103, 'k.ijaz@crecentech.com', '$2y$10$aKjWTW2emC4XqJbm4QqqBu1PaB6O2Y8HWZWng1/Q6Q2EoBebBDrFW', 0xe69dcfe3771ff3ba40d84020e97a9d1f, 0, 0, 0, 'Khizer', 'Ijaz', '3333157695', '', '3PQLY52', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0pCJyx69ApuZGw8BEj7S5Gziqm4Z1xlVbgFekb6D8D1aTdYYWiNt4QwaYlsrW9cS', NULL, NULL),
(104, 'r.petty@crecentech.com', '$2y$10$ioM.GyDRXf7ej8Ht.RXWJOKstVfCK0ahbCAk3jUxt2e7qi94Al7S2', 0xe3090bcd45dd1cf2ba3cfb34991c8ffc, 0, 0, 0, 'Roy', 'Petty', '03000488831', '', '29SHKX1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'f9FyblNtKKMdrVnjzkX6MiFHiTase6szHFEbq699Gbiv4DTzQ6J83RDMxEHbYaa5', NULL, NULL),
(105, 'c.holmes@crecentech.com', '$2y$10$Rj/838DXyrrVLQWl61KWPuE/4K9YL9.dFmiZ2bJVcKCogLN3xt7D6', 0xd1de70575f08bd4cefbb8afd588c5503, 0, 0, 0, 'Carson', 'Holmes', '03428882266', '', '1RCQXY1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'C2ia7iiwNj9pm4woXLmLehk6dK2NDHsepjDc91rdu2bOVKLnpWXsozgUM6XNgjJq', NULL, '2022-12-19 18:43:25'),
(106, 'r.morgan@crecentech.com', '$2y$10$l91/OHKSsdtivcvOLywhWOk74J5GjO5x8LZaaQodraz3tDhTndWf6', 0x0aec13a212e8a4935f6c3b4f5a641e7e, 0, 0, 0, 'Rina', 'Morgan', '3371441374', '', 'JMWPW52', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aqUA1W6GZ4OOhEoJMfkITmKRzDW4mylyA3dqhKIJyZveLchI5JQmIAe6qt5i87oO', NULL, NULL),
(107, 't.down@crecentech.com', '$2y$10$JJ1HK/Dgex5jMOCxsOHWnOR.aFbNFmvMI6VY12n8RxqcpWdW9Ogg.', 0x20be71199b86f6957366d678ae8f9b89, 0, 0, 0, 'Tyler', 'Down', '3064909298', '', 'R901GAA3', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'eD6lChajAjYorH6QfSwQO9A7H2r19Cjnc6c1f7NelgXZU5L1Suv3aeiZkawLItjI', NULL, NULL),
(108, 'j.kerry@crecentech.com', '$2y$10$uG1bzfpqAA7n.eEjTpUxPuOblspbVWLb3MgQDD6.vHhmX1oCzsn4K', 0x96a41e71f6d89fd8ccea854c6d874771, 0, 0, 0, 'John', 'kerry', '3319881899', '', '5CG5305898', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ZttPZlHDWYqeHruVLsAgrtjbNzOdQzn3ZL8Z4aPE9vNoFjrJRin0KZJjncLs1Cpu', NULL, '2022-12-20 16:12:18'),
(109, 'j.snell@crecentech.com', '$2y$10$4X5sl5hbdPdhSOzQt75cG.ZJkNfNk9eL315QAwtBa2iOq5sSPAEC.', 0x4ff64c3abe14dba6c7c4a2f23d3743ea, 0, 0, 0, 'Jacob', 'Snell', '3036265270', '', 'B1DRP22', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4g71P7FN09VRzJigwH7cTGYhqZhpUQ8f4JRuEwSizIiAEXEWZwS3c2aFknew', NULL, '2022-12-27 15:31:58'),
(110, 'e.ford@crecentech.com', '$2y$10$fp5nwMTq7NKsS0d2XK.12uPybXyvKMGQiU.oEGkvHxhvjxfISQvkm', 0x5e35c755209a3d13b642d89283d10362, 0, 0, 0, 'Erik', 'ford', '3174586155', '', '5CB3480NC3', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'iB0CVDy5C4FP9e6GXF4YWNqoIui82PeoRA7gsVQiZYIA0BWxSutMT4QghocmaCxX', NULL, '2023-05-15 13:29:02'),
(111, 'M.tyson@crecentech.com', '$2y$10$sH9KrMSR7yxiAHSfNkBbtuU/3lOtmtxG47EcVbQNVWB8zWodVIvXe', 0x1787e757f2ea00fd628f87f1561d0830, 0, 0, 0, 'Mike', 'Tyson', '3037005998', '', '5CG428HXM3', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'iknnWbtIKm6c3xfxHkutxxE0FsJjh53NOcva35V9Q3KtrXZ990fgxB6zLCxY', NULL, '2023-01-20 14:11:10'),
(112, 'j.bell@crecentech.com', '$2y$10$Qi4fknhV8z2xS2ZAj6opvO1nwf6xQDinReI0aZsnEFz179NXFx5C2', 0xf0d46570d4e55e9794ad39160decbfed, 0, 0, 0, 'Jamie', 'Bell', '3440428104', '', '9RKZZ12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'qodFu3WwfIAGRY1DGAPzDA6xgs1V8XU6jYHZ4sqIU0tA3mg67x5hbfn2kGfIo9Iz', NULL, NULL),
(113, 'l.austin@crecentech.com', '$2y$10$1RmGf8YsXLk.IUIdxKrDeOQ2qDtWtkrqExuvkZh1Dzwq31A9zF.2y', 0x38d956845eea38d288a3c938a5f71162, 0, 0, 0, 'Lan', 'Austin', '3467264674', '', '5CD4180SJS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'j3TiJfrC2xHiOyOtQkOG7F2dBKUnEwhO6jlCPyHGj51iaAszRj7C0agfPm3J', NULL, NULL),
(114, 'k.jackson@crecentech.com', '$2y$10$t9x29/wILMEfZYhwzOvYauJiqNJQEgg3SVS15u59GtroI18hbi40K', 0x6ad888e11b98257a2ff244793f988209, 0, 0, 0, 'Kevin', 'jackson', '3128304307', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GMC96jkH7bHsn3JY1pw7ObPM5kWyTxPkQvPLwKOE5mXiEFc9gloazXJXTbRsvNEv', NULL, '2022-11-28 13:14:56'),
(115, 'm.smith@crecentech.com', '$2y$10$UNlzkM/FmCXWPFL/Ejlg..abEgh5KL7rFmo2S0HpdO5hr2FroMFiy', 0x3265b969c9dcf97d8755cee0031458d0, 0, 0, 0, 'Michael', 'Smith', '3427066400', '', 'PF00MXLN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'i2KeaLUxKFs4qJ7jJx9g0TVqfkhWXMABegwt6QaaMxAWCKpJSVGvjZbarBFV', NULL, '2022-12-13 13:09:28'),
(116, 's.jones@crecentech.com', '$2y$10$AlGqNjLBZ0ju5C4xghGI/.I9Ye4i8UwHYxasrPGZOG0YisBUOeHna', 0x8fa03f23a8e37b70c1ba4b8a33ac2358, 0, 0, 0, 'Steven', 'Jones', '3014139250', '', '7DJKL02', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5naooOQbmdBLImhT2L6OvLgp2yoVDc6YhSzxykiVSuYUl0nnvBuWTwZgGLif', NULL, '2022-09-05 12:16:41'),
(117, 'o.clive@crecentech.com', '$2y$10$/cvGYZ9w8wLTowvS2la2duzjWijwPyklXRdtBY/f//ZGerg1vdURW', 0x91978313cc2da96f098be81ebb07cfea, 0, 0, 0, 'Owen', 'Clive', '3467101100', '', '4BVSJV1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sb3WL86dOrWpWMbvSwaEZSYQh2yrxZz56EPy4AG4dHsT8wEJmrd5G4YDPSbo', NULL, NULL),
(118, 'e.adam@crecentech.com', '$2y$10$XE.UwSRkQKUx6F8e9rOsbOtq6dnIZ.IcEemCftBWMdVOCqTVWicGC', 0x40086564d6a0e74fc312f68ae725cedc, 0, 0, 0, 'Eliza', 'Adam', '3430675747', '', 'HJ79P12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'v8AiyOkTYxoqZyYPPOqokOLv4wKji1Y2LA4pkaJLqvEJHIp6n25CVszukR24pTBr', NULL, NULL),
(119, 'wixomey640@seinfaq.com', '$2y$10$h3Z5Y6VmiQQxvct0BS5AyeXM70qGf1BThOfvFcD9GKyZOfl5khgPS', 0x1aa1d11520b85da39963e0e2938bd63f, 0, 0, 0, 'Deja', 'vu', '3327268382', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-06 06:03:18', '2022-09-06 06:03:18'),
(120, 'lihad89105@vpsrec.com', '$2y$10$awlJmndZucsS1bGFzp5JYOtl374mwH70VmzJiInAgF6O9ssjzVs2q', 0x40515b47ca35802c38f3669de393831c, 0, 0, 0, 'Zachery', 'Solis', '204 445 9889', 'tth/hatf_42/users/120/8oCnTF8fZOoq6rfA_1662450842.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-06 06:20:06', '2022-09-06 06:20:06'),
(121, 'p.mapel@crecentech.com', '$2y$10$ntNHDlS23SZia23kuzaeBerhLAH8GdbxsbtasFGle5Skw07X2p9e.', 0x74e04b3637edc42e5c51fd4a8f568bad, 0, 0, 0, 'Patrick', 'Mapel', '3124439901', '', 'JFQXKX1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BJi9RWf9YeTLMkShYTo6N9TCChCwTHDqOvemQLpLzL6CgXWSfQfC9BPNzYpLcb9M', NULL, '2022-09-19 07:19:19'),
(122, 'm.benson@crecentech.com', '$2y$10$fTU1n0Hw5ix.siymOgTgb./jJisQMXfhLGNlcCReUGvJu4aXItfl2', 0x39f3e374b5dd356fe0b9f8d5d75321f1, 0, 0, 0, 'Mark', 'Benson', '3005781212', 'tth/crt_23/users/122/GtjjesfUv7C3qn4i_1662453434.png', 'JPA538XFCH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VeEHUR5Vu0rdExFpZqpuYNdd6In1lOmDqoGtqpnCvsC5GveB3PopKQ1MfJHpOPxJ', NULL, NULL),
(123, 'jeqo@mailinator.com', '$2y$10$Qax4F8krLnywkRMMVJW2m.MlCJOb0spOB2qYUn4RfkJbsy9yBpGby', 0x4d0e4ad641959f9e9aac8fb43d98a55f, 0, 0, 0, 'Maya', 'Palmer', NULL, 'tth/hatf_42/users/120/documents/sbNpxwMwJP74P0gX_1662453029/IviAQmp5TDFBNbaPwTWxVWyAW1RxhrOWBKvYkl5I.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(124, 'rakavaj290@seinfaq.com', '$2y$10$M.np.foaK4Y9OqAOYug1Z..eKku/AFgIAYgvVeWbSVucQjxHAx5FS', 0x454deeb0a1cb155e1a5889f610aa7f78, 0, 0, 0, 'Shahzad', 'Hassan', '3212345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-06 10:02:08', '2022-09-06 10:02:08'),
(125, 'fapojar165@esmoud.com', '$2y$10$hNyTt9f5TjYmT34U2mjm6ecN49HZ/BNqhg/NwQh1PZ37cU4ZOX7VW', 0x9397166a9b3d1c99446625c2f7a3a2cf, 0, 0, 0, 'Shahzad', 'Hassan', '3212345678', 'tth/sa_44/users/125/4p7Bwt4kB7puLN5k_1666851298.png', '5CD012HGH4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-06 10:05:59', '2022-09-06 10:05:59'),
(126, 'wibari7449@iunicus.com', '$2y$10$XT62IEmg.prxbLohfjKIwuBBHh6YenNWrHXrPvgSGM9ZbFiBBD71e', 0x1e47b71d85bbd1fbe264c8b160216b3d, 0, 0, 0, 'Talha', 'Zaryab', '3212345678', 'tth/sa_44/users/126/JEV8NQzLZTipEb2G_1663574164.png', '5CD012HGH4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'r4zSk22ap1SgjtR7hI1kYf906BzpHx6HlOgePDOZBwe410mU6sMSoM9zJKWS', NULL, NULL),
(127, 'nefanom795@esmoud.com', '$2y$10$7HwKmr7v.D/DmoFR253f5eDYdAVgpJyUvj5S6y3TSbl.bcMX4t9GW', 0xfc6d5dcd2926efe727900cad7f3c5eb4, 0, 0, 0, 'Faizan', 'Younas', '3212345678', 'tth/sa_44/users/127/LnK2ARI5yr7VEx0w_1663575767.png', '5CD012HGH4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8AwFLZeA1Vf5P3058mcm3CVU5Q6WUchko7nQxXWBg8R07n8vNAhAUA2Aljgj', NULL, NULL),
(128, 'm.hill@crecentech.com', '$2y$10$VG8nkI/K4RLYIzmfnEUNiek0qOi4bvwpk.inLsYCDMfqvgsdm55sS', 0x6bc4a014ca4c00cffa9f9cb2c7343963, 0, 0, 0, 'Martin', 'Hill', '3014746073', 'tth/crt_23/users/128/oCUsUgCBb2lzdpAQ_1675773718.jpeg', '9JR0022', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2f8wBI2Xc5OSzIwDoBxoG1k7kDIfZjIHaDN7RhKHeYsvwGgwVUsrOVMeng6s', NULL, NULL),
(130, 'd.bush@crecentech.com', '$2y$10$fbVcvel9hgD7JO2RPXc2rOCLw7uDTjMI/qjMv.NCktb.intGYaumC', 0xee2e1876a49efe06ae3007908e96ea95, 0, 0, 0, 'Derrick', 'Bush', '3000972007', '', '5CB33802KL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mmmUVvlolbj4bqBFHsFjcqnbokEu9Sqa9QsVAEjASqwMVOrPIvzlbBNWQ43EzEKK', NULL, NULL),
(131, 'd.randall@crecentech.com', '$2y$10$hk6t5vmMZDfJfZFi6Hk5Ae5/3gf4Pixfl1O6C.Ms8Ar2G.B8dth7e', 0xa729497ac0f39db47ede939862345ea7, 0, 0, 0, 'Derek', 'Randall', '3007107082', '', '42PCP12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'n2TA4CcqrbdAV5cuGCxvdHVENVPAJGT8VV63eIN3BZBt3rZHjtZaKo6uEvwW3nVm', NULL, '2023-04-12 15:19:13'),
(132, 'i.jordan@crecentech.com', '$2y$10$MfNZKEgOPjO6Wpede83J7.isMIo341prlUElj78Sl0gYE8.PJPDcO', 0xb15369f115a1f63309bffd1439286c87, 0, 0, 0, 'Isabella', 'Jordan', '03099066791', '', '5CG5133VR3', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '39lAbqCCMXRja0aPl6drzG3WWendTXAfwAxBbMKcKvEBmqZg5S4P2Jy4qsYJR3qf', NULL, NULL),
(133, 'r.leads@crecentech.com', '$2y$10$dverHUTuhswpAhlRfF.XWuf7vdxbgjnMcd/FAoUT3101s5yoUHI3u', 0x24cf4b6f74f85b86e1bf7c16400ca192, 0, 0, 0, 'Rowen', 'Leads', '3040015404', '', '5CB3500Y1H', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'V0fdjyPD6a6X5WPSaii8xTuYLn4JwZKGpCxLDkF1Zrk5GF43HdJV1dqGEcBuwucM', NULL, '2022-12-21 14:43:29'),
(134, 'j.bradley@crecentech.com', '$2y$10$Vo0zln2lw0AB/0fxbAy7iu8hS7.NYYFr8JEcCPjiyVT956S/mEP9u', 0x968e2f7fcb6f4d9e73b167c5059f6761, 0, 0, 0, 'John', 'Bradley', '03065395839', '', '5CG428HRZ0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N54Q7U5oAgZ0wnc2lWFoGkWM1FlBP5f6s7MadcTACfyCmQhM1PooKTj5BaHykoTt', NULL, '2022-11-28 14:38:23'),
(135, 'a.gites@crecentech.com', '$2y$10$jNJx5DP1.gq/Jfwgpz/aWOVlgrhiBytk3d0YwmtGItgXa7iOHDOGS', 0xbd735bed83b35247f792bd1fba056cf6, 0, 0, 0, 'Ashley', 'Gites', '3204090508', '', 'CNU408C8KH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6LRuCBQuQ4z8mnmvlNDzj8UWMGpxqdGekL8KzEz4L1xA6rLViMkNs889ikEakcsf', NULL, '2023-03-30 14:28:31'),
(136, 'e.reid@crecentech.com', '$2y$10$uip59jJ6Gr/U.T3o0bgqhuz/pqv9ZAGe/84ZEN6h62tVz5yOieCeO', 0x5f089272196b5985fd84d07d387b8ff9, 0, 0, 0, 'Edwin', 'Reid', '3208490883', 'tth/crt_23/users/136/yQkAX2EDoHfTb4rI_1663254223.jpeg', 'H4CL282', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'gCG1dg4pR8dMxrWZEVeqdkZjk6xweZKpDNAwr3RiRqoLpMEwYEuTi0lgUVpdcrgN', NULL, NULL),
(137, 'j.stephens@crecentech.com', '$2y$10$at5Iup9g7rj01z4S18lYZeUwgfBQJNWntr0o1CG.oFqQ4OFJgdnZa', 0x3872d91027179bae124e9b14a95c6ff5, 0, 0, 0, 'Joey', 'Stephens', '3117893527', '', '5CB1515L2N', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IykPESkeq3SwRXXuKhVQE0rKSaQURZ1qUxNlRGcNPaKH3qqSRJ2n9SwNJP1Ltraz', NULL, NULL),
(138, 'j.andersen@crecentech.com', '$2y$10$M7zsUWvSTthKSr7NBF3pXeqzG2ajP7KyUH4g/cMCBu76Te2wITAhK', 0xb650172e6705f0b4ca34c3933ded550c, 0, 0, 0, 'John', 'Andersen', '3070797728', '', '2D7TZQ1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pWqRCsTAS1RlJLD1pVOCBsSLnwci8tkKiFjBdzNcNmRXq90Wt6h2IoOvgP4cnmzz', NULL, '2023-03-03 14:12:41'),
(139, 'd.shawn@crecentech.com', '$2y$10$iF5EVOq1OmwQATpmRCwvMeCJT2Q4J4XnID53aWJ.VrLZ5FgvZeaXW', 0x5ed66f5d3ade078b0a6c03153810592d, 0, 0, 0, 'Daniel', 'Shawn', '3094751775', '', 'J8SH7W1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'iPOtQ73UgZwNWuwIcih3uTVuqr9LH098r2thZRUT2lLn2DHDujaf2QViwFEd', NULL, NULL),
(140, 'd.marsh@crecentech.com', '$2y$10$I4msAgvvL3J8nrKN0am08OulkUhtaBIDGQyeiZHC0oUdg.ITcNxG.', 0x23553b647f56155424e762328a657802, 0, 0, 0, 'David', 'Marsh', '3070481350', '', '7Y6HJ12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lYG1g5hZOJmT0dGMEeN92PNR4qvPHVzet7Hckg5uWTlJu2dqmwEifTAL1v1FznMt', NULL, '2022-12-02 15:07:36'),
(141, 'j.parker@crecentech.com', '$2y$10$72rThB4UoqnkPlfeNarxGeTYNHF5W.RFKhHSgl9nJC0Kuloxqg8Ey', 0x85e3dd886f90e67302c985c3116e7e06, 0, 0, 0, 'Jasmine', 'Parker', '03074133934', '', 'CNU412D2Q0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bPA5LjsfF7LrVkJZuxaGNhSJtrLPWBwVVKwIkOcoCLrR3GnboQavcYQYneuuZkKz', NULL, NULL),
(142, 'n.lee@crecentech.com', '$2y$10$hOIyD5JDyh.qaoEvfdGLxu51kP7okFrfYT8wXhCppsZA8B8rQR9ES', 0xa6d7aaa46463df5ddf8f626f1d98e971, 0, 0, 0, 'nick', 'lee', '3024378244', '', 'CNU421CQZ1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fhqBgOI0MQMUeagyW1bhlj3zNueBgZaQAwNxIIcJJ2gYLjcMp1mC5dVGE0HnRbMC', NULL, NULL),
(143, 'e.bana@crecentech.com', '$2y$10$GRgJy9YKR51aHMniT1YmreBeCee/RJcsxPiGKGUuyH0LqPxhsY88u', 0x15edea3e52aabdd753b5397949bf6654, 0, 0, 0, 'Eric', 'Bana', '3346885000', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QWfLXyokn2TTd2YLYdZf66aK3z8scfoAGaVXOcoMNoBF39s2k0BrvZeTDL0eUZ4V', NULL, NULL),
(146, 'j.travis@crecentech.com', '$2y$10$uict8ti1YNqMWOhsyjPyd.tX3ckVuBkqPcaZrsSlECCgQoyq8AqY6', 0xcc3d40894a2fa15e48902788c45aa7f6, 0, 0, 0, 'Jason', 'Travis', '3013142817', '', '5CB3270RZV', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LKyO4FPRSQZAk47y662R9r9ZyN0ufSusmNpDl4PftNHMnGtkuawcYjYfdGRd4iS6', NULL, NULL),
(147, 'j.parson@crecentech.com', '$2y$10$RTI87.1fM4C4a/UwpUjiUOziVIT5jPkxKQrt/SEXnXz3KWYI9qzl.', 0x222bc7bc9c55fe08b4355041d5383d6c, 0, 0, 0, 'Jim', 'Parson', '3344954526', 'tth/crt_23/users/147/3iyg8s8D7ePdDXDO_1663013179.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'n38Wgt1SOfZw35xfq5PP3aUrPV9NqE8v6A4YQXsd8IphmupVKCtqOPzHnCDvxA62', NULL, NULL),
(148, 'a.franke@crecentech.com', '$2y$10$05HETmkAM.jFNSSp/5Qj6OtdvSlRkHgaNz1Odhq0xkc6b.GxmXVYW', 0x55032974344b5e243a05ab015430d21e, 0, 0, 0, 'Adam', 'Franke', '03084346437', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5aYPFIoLxz0hiHS3YNfiPMmDI68dlQwvpbCXx53TDiatb5o68Tqp4ZEMIf7fdfjM', NULL, NULL),
(149, 'e.young@crecentech.com', '$2y$10$2ja/tJd6hNB8TSzI983gfeXXVFzS7ZI.nkjN4cMUzQ.u48aiZqPxa', 0x5e87d6495f89b3e2a810771bc4fc387b, 0, 0, 0, 'Eric', 'Young', '3070115912', '', '5CB3501HDX', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Als49tTW361qNyTunTfP96YZ1e55IkLPC5LG9mgCE7lj03sxbLcoEpjYSiEB', NULL, '2023-01-27 07:47:36'),
(150, 'a.davis@crecentech.com', '$2y$10$QudtNzc5ls2ZzBQMtllkmunar.4Tz3wnJvmzVN.Ob/2BXLM0qpQ7C', 0x1f96bdd6663fda423fd13e5b6fa6071f, 0, 0, 0, 'Andrew', 'Davis', '3049018017', '', 'unknown', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IpFynbqPmQhVbo1C5Cbu2q2oYEWMLwIN9FYDaXlKQM3EPv0iRUUiWT5FdMMY', NULL, '2022-12-19 08:17:08'),
(151, 'm.ward@crecentech.com', '$2y$10$4cMltWDeMg.N9XxcfZUWV.47gz5qzXAc60QQf0D5midHy80U2ZGei', 0xd1ce9fdeaedf6841a4229b42782712a01528cd529771ea34b56ab7895f2239a7, 0, 0, 0, 'Marshall', 'Ward', '3404013102', '', '5CG516021W', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TTO0lmtbfwcARisVNRqx50rpgiSteAN4ZtTOI5pz67KjYFk0YRXk2H1mt0ppLCaB', NULL, NULL),
(153, 'a.stewart@crecentech.com', '$2y$10$rak2Pz4yDE/z33r7mh7.2.U39QX13VTbp1hd/5hO3gMUUy32PqMIy', 0x22b9be268482b09565bbf9fb4e3e8cd8, 0, 0, 0, 'Alec', 'Stewart', '3334899700', '', '859DK02', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0K3ORCwEhPgmDWS9tRmypAASc1gyHvyl3LPzxlUYJSUyLxiAODzXqxQ9a7YQwhj0', NULL, NULL),
(154, 'z.efron@crecentech.com', '$2y$10$fCKYWZpFtZlYzulyf4DSa.lng7LakosoWuk4eRX4Xi.4qJLOmhY1K', 0x20e18367672389d2dd768a3642f5a938, 0, 0, 0, 'Zac', 'Efron', '215-555-0310', '', 'HPFTSZ1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Qzb5bZ0E4OdocauhN6iFAQF49sEixsagqGHL5ljogYTx55XwFjWi59la8eJqswO3', NULL, NULL),
(155, 'b.willis@crecentech.com', '$2y$10$0TNQ/l8gl36jU6DGPGTuE.DkuF3PipKygSd7gVqyKvdh03KW6ROt.', 0xb10b87878d3d8b2dac405a9428cb57ba, 0, 0, 0, 'Bob', 'Willis', '3152150310', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AaA9osrpAOOzO3gHcqKMbXgtAuvF11yRSzjgnDwbqOP9kYuTD6i2moQvh1rJDLrA', NULL, NULL),
(156, 'c.thomas@crecentech.com', '$2y$10$McWrWtGrzgeOgFHfmpHawe6qNe1TMRLvVlYrQVy/iL0z9d3eJmd3m', 0x2df35d9a63eac059103fb330e603d534, 0, 0, 0, 'Chris', 'Thomas', '203-666-9874', '', 'CND4382Z78', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6gM8M5Dq4SFxEgCUGWhGDDhCRSj07Y7lW0QHBfQI5byXzHKZxxswtB77JUw3ljkN', NULL, '2022-10-31 11:53:50'),
(158, 'j.bateman@crecentech.com', '$2y$10$bvtUNO9fC/icTG7pHmxtZ.Fs8TCLN4NMMRwFcLFRU5OFbZ9gOvwYC', 0x2779ff976630c6cfa1da472c25edc9ed, 0, 0, 0, 'Jason', 'Bateman', '3454633365', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'T5xKVt31ey5XVRSroFSVMUCl73jvCZ0IDXHS8f8xXcmhBYcPKy23aiNUEuG6Bp9V', NULL, NULL),
(160, 'h.bruce@crecentech.com', '$2y$10$xO0iP37LNTR4CbCP5Emf8uotDwIcod8ZpIKpxdLZq1bR8d1zKALsy', 0x5927347318c601fc19b0459771c43aed, 0, 0, 0, 'Hubert', 'Bruce', '3214499229', '', '5CG529487Y', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OPQcpfBkai7Wig9W8whz16MaeolbWQEBxzaNQt7cC8iLprIKErWhm4Fdd4FfnPt7', NULL, NULL),
(161, 'h.cameron@crecentech.com', '$2y$10$VCn1kyNkwgECDjZiLz6z2O54Gi.yJfX/qDryQPHVWyuERVfBldt5m', 0x454fab5ccfd6e862349e9553bd83afb4, 0, 0, 0, 'Henry', 'Cameron', '3209471663', '', '5CG62508SL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5ifgDjtlu4vyeJIKprbezX0mZPCvNSZ7D4pONiOxdkhGH39u8KQ28aAUUIgKwriV', NULL, '2022-12-19 19:55:28'),
(162, 'm.scofield@crecentech.com', '$2y$10$WuwISQ4IBAOQiLD6t58c1.hqdZiJS.osjFIfUeNm.ZThgzyUcns2G', 0x74f4738853bd433fcd297747daeed44a, 0, 0, 0, 'Usama', 'Rao', '3165949299', '', 'CNU352D9S9', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'y6QGaMcKLTN73mOVASarEqQdtq2lFljhzdZIDRxhGBKW2yQG6sF2L2wKOlUE', NULL, '2022-10-18 10:37:08'),
(163, 'a.hales@crecentech.com', '$2y$10$kw8QgPlaMB4PPk9mXkcOYOm3XbJvuSeDqBbUXgF2FYCVB11Qs4zfy', 0x299e2c4fd3ee48cedc687eb07f905bfa, 0, 0, 0, 'Alex', 'Hales', '3454245570', '', 'MP1GUS6H', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fyN5HMBosl5aKiI2ZKkRvQAR4BxrTYPIPybOptdeJwZ5FNT0acGXIw6t0ZlG', NULL, NULL),
(164, 'avery@crecentech.com', '$2y$10$KkwrWL/Nrsr0/tkRS0OaB.3gFmhSid5wGdcAKDm1kkqw1S7USSo6e', 0x0a8f53eafb66b7330779797d0bf9a1eb, 0, 0, 0, 'Avery', 'Johnson', NULL, 'http://app.tasktrackerhub.com/files/16', '5CG5521727', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uT0yGM5iUKqcxGXKy5msVvEreRvno2PpDYlaK06UAJSJk9AV5Bmd0wzq2m4j', NULL, '2023-01-10 13:25:39'),
(165, 's.shelton@crecentech.com', '$2y$10$1rwbspt6LSSXAFJDgdAvcu1HgcjZkgEVbem6IZK/nGTdfafrLwHvS', 0xc880415690ac25b8e1cf4fb5f6c1303c, 0, 0, 0, 'Stella', 'Shelton', '3114599967', '', 'D2SG9W1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '90qNhBjDolbiP5vhCpyqN9OYj3rSKuim9poMIjmm9iCz0PL62vk7bUlST7Ko', NULL, NULL),
(166, 'h.madison@crecentech.com', '$2y$10$r4ee6Ppm1QPveDuejQbljuZD3uEBmADJlyzxKGo0d1gUerL6TqOaK', 0x450e333c0e540cbeb4f22ae1cbe847b6, 0, 0, 0, 'Riifat', 'Abbas', '03078879436', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OH3YjuHxtCEPAvohZJ980CHW6Z2wtysaKvBFKh60TdGOTWKKS0y3sNkCfdWwtwkJ', NULL, NULL),
(167, 'a.heard@crecentech.com', '$2y$10$jTiENojGnHJ3/WlMzrBXvOzZwluIv8ncYrNyC/CbhbqdcN3GID03C', 0x8b58ae1cd74b1c2303564a35e8eae183, 0, 0, 0, 'Amber', 'Heard', '3166150801', '', '37JJW52', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GGnwrHhwpABE4q5FE1gPhctGVThlfrfcmx9G3WKkm1WnetfvzIiLNcmv8sUm', NULL, NULL),
(168, 'P.knight@crecentech.com', '$2y$10$6o0X3/IovqU7iVBchwAvUucK6vVp1TW.yuGDWmXy.wfeQxLdUpUHq', 0xeb57385a4eb156b1ccb7da4dc164d679, 0, 0, 0, 'Phil', 'Knight', '3174366766', 'tth/crt_23/users/168/mAuEWu5SivcLETyL_1663220253.png', '8CG4251H16', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UIoPrUzrZnZnaXW1fiOjMhuHixG40MnkxVzpuXZrB1E20sdQ7qWhBlOWD7lcif0m', NULL, NULL),
(169, 'b.peterson@crecentech.com', '$2y$10$4K/5jvLSxAf.ZUEQLAszMObgmJLqA9OTppXR6bUb3EcuvGflEDr6O', 0x7cead3c00eff5f272d43ab8ca1c99646, 0, 0, 0, 'laiba', 'habib', '3174434848', '', '5CG5344MWM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DVGFERbB1CFO9xHnxSNJbFzq6l0HWqU0JffDZLLBgtqAS1vUoC2dmat5ruWPe5Ez', NULL, '2022-11-25 18:57:34'),
(170, 'j.benson@crecentech.com', '$2y$10$JGDgex5ZEDHfAdgW5GxAEuGcEn89dsSAvX.JnvGpN/uM9lYesKGoe', 0x806ef6d5cc897a8adac58e4a15996c47935071d319a5b8a167e0aa423c8f0208, 0, 0, 0, 'Jordan', 'Benson', '3092013020', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Smx0UJS8CONoqeiSEBSBncZ0IJrLx8kvBQl4vzZK1PT3S2WV7KLptIujTp2rrImv', NULL, '2023-05-03 16:59:51'),
(171, 's.ray@crecentech.com', '$2y$10$Jx0yVNAlhxe5gWmKK7u30.u/y3JWdwOwD0jz7w4dCkKtFLyPqkmf6', 0x4780ecc4c12b81f8611e7d9bc9205a36, 0, 0, 0, 'samara', 'ray', '3004173334', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'xLD4zHglOxnLQxSGcy1RwpD1doth1ROICWmAIwkfrNu3Yzl0lPDpdTZKxA4FqScn', NULL, NULL),
(172, 'd.gibbs@crecentech.com', '$2y$10$tcS.GDhyDk5F7CeG51fDu./QNwlSWbPCQhHENT1vSzh7O54V2kFm2', 0x9340aa149fe740fc223c62dd52255c40, 0, 0, 0, 'dexter', 'gibbs', '3154598694', '', 'CMMGH22', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QuIx0kB4BXctZimsrVwNS6TSo2kV6kXcsYIGSSUSVXCASbgugIdd1RCGZ3PKbXJE', NULL, '2022-12-28 15:03:19'),
(173, 'd.vinson@crecentech.com', '$2y$10$Fytfl0jrHp32v9cxJkF39ugr3X21/ymXZoHPZ/tyw39LYjf9DsHyG', 0x73b0efb88a497b57753f4e5f96bf1282, 0, 0, 0, 'Daniel', 'Vinson', '3033388899', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DwLvJFQNnmqvb3PdKWgZ9QN1kXVex2cEmKrEA2zmr9BWuH5pIgjivEb4VMIiOZB2', NULL, NULL),
(174, 'n.park@crecentech.com', '$2y$10$ppHx8vZobpGHVa1WeVuUmu/j6KGZNftOfd3LPn3AHEYWgGEi4Urca', 0xe337fe7761213a422e72e0f8e4c009ee, 0, 0, 0, 'Nora', 'Park', '3235459539', '', 'R90HUPTU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0vQwUwhYPvGYAG4MdbVD9It3pEv38gIhrldeB3PrPkZdaMyWe8kdsfSG4FXX', NULL, '2022-12-27 14:26:12'),
(176, 'kikoh94558@dineroa.com', '$2y$10$vchAKHP.avtnsJdyx0gJyuHqltSvDpZzTpOAQ2ozNFVpsHi0MpzUG', 0x735208cf7c80c636372454bc0ca5d219, 0, 0, 0, 'Haider', 'Ali', '3212345678', '', '2141XY1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5IPqm1dRt9ihNn50apSmZd8DSuF3brsRZBwAFZtuMGoLfbadaABLC5dF6KCZsOwZ', NULL, NULL),
(177, 'gihorof179@bongcs.com', '$2y$10$RMBbDeaTGO2RgupJBTghy.jXb8E6HuK2rtckxTZrS1aHOGHPWZsq6', 0x067066782aeec71a3f7c06cef635aa4d, 0, 0, 0, 'Shahzad', 'Hassan', '3212345678', '', 'D86WHM2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-21 07:07:40', '2022-09-21 07:07:41'),
(178, 'lagipo5174@dnitem.com', '$2y$10$Q8fX.SohUA.fgqGGm96pz.FF/63w.YJTO/F3XrVYv9fOYuXpCB9Z6', 0x0efa731ccbac3478318f27e58dc96b1a, 0, 0, 0, 'Shahzad', 'Hassan', '3212345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-21 07:14:06', '2022-09-21 07:14:06'),
(179, 'xewar38200@bongcs.com', '$2y$10$xLWLysxf.gb1B9Bvk9u7Be6S5PFfWtvfji3zCCtwtD/gPDyNs4ABe', 0x67432d43b9e028373811c379decdcb74, 0, 0, 0, 'Mohid', 'Ahmad', '3212345678', '', '5CD012HGH4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cC1eus1kuksEU7vOTHHulOCP51lNE2hUhHI7b42iVaV1S132O3reExWmDug5', NULL, NULL),
(180, 'lolopej955@bongcs.com', '$2y$10$.uo9TvfA2FDQzMxTERX/oukOvL1KIhEbWtV6XOxnfgl8iB99armEO', 0x96cb7ff607d151fdf72bbe5cffb4bebe, 0, 0, 0, 'Abdul', 'Ahad', '3212345678', '', '5CD012HGH4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9Gx1P0F9YIzLsRlwS45xmZUig9okyW8QcPnRXsNfhg2JnSuZkuPFpZQvPaU4', NULL, NULL),
(181, 'xiribo1043@dineroa.com', '$2y$10$4EQNxOrfijqKX3OpsjCAG.qGBjRWJadwOCvvBPcLM..322M3hHW3m', 0xb8d375bf69b0f7a3d87822ff9a46dc7d, 0, 0, 0, 'Muhammad', 'Yayha', '3212345678', '', '5CD012HGH4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'idANQqTubCDUjdR1E1uoxX9SNw4PSl2Lv8dZnEOy1yAXq6CjKJkdFBk40f6Vuv86', NULL, NULL),
(183, 'bisag22536@kaimdr.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'YPNjQ1sICeWYGDXI3RTnclulwlvOzCSAIzXZ2cprlfaC6DGQTLUdBvqsiMkrnAg8', NULL, NULL),
(184, 'u.abid@g.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MIDmSsjVzsRZKH5QIKkCEsqANxaxW4IDvsISczPYe3e0KZV0RcOBzC2nh62Ybm2T', NULL, NULL),
(185, 't.w@g.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A2zpJlGv9LT7GKIsufDzEsm2xinZG8I1g3Vk0NKcg7eV7JiCS3tiVjFiMFLF85Dr', NULL, NULL),
(186, 'fugyjesiw@mailinator.com', '$2y$10$RPwRmFx9plsNz6dq8JVjrOA30lg2FDh5p8X1zHuI2EI2ThW0RJJM2', 0x983a4455730a02c9ab125b9e9ace83f7, 0, 0, 0, 'Faizan', 'Ali', '201 444 7898', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-14 03:24:37', '2022-10-14 03:24:37'),
(187, 'f.ali@crecentech.com', '$2y$10$epQVxuNZpLEQ0H/aTId2m.dGoDqaxRJLFL5ywGIMD6shVf5CHJJSS', 0xfca8e7601d62d284939bdc4ce5340d3a, 0, 0, 0, 'Faizan', 'Ali', '+1 (672) 387-7196', '', 'CND8224Y4W', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-14 03:28:08', '2022-10-14 08:35:00'),
(188, 'z.shahzad@crecentech.com', '$2y$10$o0VHnNxgewLB15TJYA8WmuVLIwvf16HSYwver7eCofbab2bPS4blm', 0x5844f05ac7d3e3ac5dc457477fecf51e, 0, 0, 0, 'Zohaib', 'Shahzad', '3338640553', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5ByBNQMwtPt24PZeardh4upyuJ6nljkwqZjxLW1PuiANuqCiHKqKovLVwcsu', NULL, NULL),
(189, 'podio.support@crecentech.com', '$2y$10$F24bknEbxK.izAFLwLRzdOvB2XOKV0FZsoMYUGpaYVPozNbXg7uWm', 0xce755138ca177f128f7592c19879cc16, 0, 0, 0, 'Zohaib', 'Mang', '3338640553', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0WlEusLtQ7c1engfTZTkIM0KsmDDcmsUsTckz67jKEG7FhPnmWILxLtvkoLb', NULL, NULL),
(190, 'i.fatima@crecentech.com', '$2y$10$ziEQPaeKf8emq7Cm3I5Jn.KJk637xFCtRJfwB7boFdnKvme39YuYO', 0xf6e88f6d6ad83d74d1ae32e6828e25ea, 0, 0, 0, 'Ishrat', 'Fatima', '3024159394', '', '5CB4191LVR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3amTrR4cko20JKaU8pc5uIPBVqHbq5QxvvWsvoYJxdRm3Q4YnDK2m0BtiqsftnVi', NULL, NULL),
(191, 'm.subhan@crecentech.com', '$2y$10$Odkg50BXnwzg3Io1f7NReuGm78MRT7wG3HtZ6E6GdOvgVR9rnKfMa', 0x8fd0f1928ad095087d6d56f7fdccbc55, 0, 0, 0, 'Muhammad', 'Subhan', NULL, 'tth/crt_23/users/191/G1VWzkTGkiwfJ3YS_1672769367.jpeg', 'CND5293G54', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tpFqBjCsqUpm5Dgy0clD9R6cIxlqfVYxDCmJgl0pBLphF1hCXhYYXWrIi8fw', NULL, '2023-05-31 09:34:46'),
(192, 'a.rafiq@crecentech.com', '$2y$10$zBs4eXQoJryKbYvpRnr.D.2zspEO1eBYLkkIqHR5GRwMuicRjxFZa', 0x635b679c93cf089a2d54c04fd65fba47, 0, 0, 0, 'Asad', 'Rafiq', '3017687834', '', '5CG50424Q5', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mTVez8Xy7YvgetFbi9UdYZrRB0b1jFnuhRUvInF323XKtJVRfNnGQCqXHF4o5WzM', NULL, NULL),
(193, 'm.ahmad@crecentech.com', '$2y$10$iUfanY1/Ii/mfz3.eu8oF.r.7Gp5G5HbvwccKKDMMQBirdf507/le', 0x95639be2fb3f489da2255d8ae936130e, 0, 0, 0, 'Muhammad', 'Ahmad', '0308-7992596', '', 'FWNZXY1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'YsdyhyyQnQPH92NPBvYO4Hc6EcKrlJmHs4f4f3Q760YpJW6ojXe5oj06ocm1', NULL, '2022-11-25 07:17:43'),
(194, 'f.azmat@crecentech.com', '$2y$10$WnlBjdibCvIegOUec7ZjRezPdrOHZpJLieJP52CXAKvfs3gFj73Ge', 0x952d0f449392214884a386e2696c9359, 0, 0, 0, 'Farzan', 'Azmat', '3454308583', 'tth/crt_23/users/194/ubStKFp6eYJzg0I8_1670912243.jpeg', 'R90KBUK7', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aGUfsCazmB404l6zNcmROIjeKsARdwWZHpPuyGZfPFyjSOcI3iw853A8EewsebIl', NULL, '2022-12-02 07:44:47'),
(195, 'a.zawar@crecentech.com', '$2y$10$RNsyDV9jaKWH7RZw9y27VemGeNzaT56o9zpXdcq3IoV9eUSHtH3Ei', 0x2f9d8dd5baceafbe5d8d23ff2b93ff97, 0, 0, 0, 'Syed', 'Ali Zawar', NULL, '', 'R901A03E', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Rdo9S0uC3wNUpfyKW7F7xzkhyBE1AVNtz0jDVYmvtcrGjSHLxlGVC3iCyVcv', NULL, '2022-11-22 07:48:40');
INSERT INTO `typicms_users` (`id`, `email`, `password`, `app_password`, `activated`, `superuser`, `privacy_policy_accepted`, `first_name`, `last_name`, `phone`, `image`, `macAddress`, `client_app_version`, `locale`, `street`, `number`, `box`, `postal_code`, `city`, `country`, `preferences`, `api_token`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(196, 'r.ullah@crecentech.com', '$2y$10$qIsZF/eIqIdMCJb1ZUfP/uF4luIRAG9kKKoTkpe/uNibEStF7YMkq', 0xc49329c52ae8ce08b244d9b75a910bd8, 0, 0, 0, 'Rehmat', 'Ullah', '3063685255', '', 'HJ67K12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'XlPN589dyD6jUdKgdFIILXlImIAn2D8yuUBVuy2EmSP3ASX1QahewrvWZndG', NULL, '2022-10-17 05:21:18'),
(197, 'i.khan@crecentech.com', '$2y$10$zC4Jvtn/MTwcgSjtPeRTfOfYQBDkEpYtoUNhYJ49JQONDBWClclZy', 0x76bac5e1664b193699e219c9fe073a6b, 0, 0, 0, 'Imran', 'Khan', '3090547315', '', '4CZ1300P21', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LGIVIAxwP9EnEa0A434H0jjQOPuAH9u8qnQCNTv8zmcQQHOuAVRFvmKod2TzhS6k', NULL, '2022-10-17 05:06:42'),
(198, 'm.basit@crecentech.com', '$2y$10$tz4vs/z6mIHiWHlUukCqAOlM8hncm8Zc1THfNMGnR/5Ls2xxBPQYu', 0xc354b7aab5d95398c0383bfd58d929b2, 0, 0, 0, 'Muneeba', 'Basit', NULL, '', '5CG4474YJY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GmYRYsoa45fbU6L8iglq9s5BCYEAKTSNNAck7JIp5NCyAovu7xhVqairqyzH', NULL, NULL),
(199, 'n.mahmood@crecentech.com', '$2y$10$NOkeRhvHr1Y9fwWJbFTeHubIyrjynaX0We4B9GNFvz8a9SnKfl0Qa', 0xda4284ddca4bd17a384eb659040ee7c9, 0, 0, 0, 'Nouman', 'Mahmood', '+923314554025', '', 'PF2DF4VD', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a9paWujcTU989DMcJJMmWLjO7T5tOopV14mn5PHuyW6Hin38EgAveNZJ1Q3O', NULL, '2022-12-09 08:24:25'),
(200, 'r.mahmood@crecentech.com', '$2y$10$R4.D0iOmWpTbSOuE90YgxuLIemuM/HSfKjbz/w6AVFcyDYT2k1m6u', 0x0dfa7de3bf19d54017dada5edb039ff6, 0, 0, 0, 'Rana', 'Mehmood', '+923204100745', '', '5CG529487Y', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sdNRofiqohoMSnHNPaRwYjKqXt2SiqJInzHUKeoKNB1JfoxOeLwav2LZWYcl4YmM', NULL, NULL),
(201, 'a.ahmad_n@crecentech.com', '$2y$10$g4/w029WWtHuRvrqWik/HOL9h57c0gtlOl884pnJA6Cda3Ikqrb/m', 0x256e6489ce9ded924c0026a85afb39c3, 0, 0, 0, 'Adnan', 'Ahmad', '3332356668', 'tth/crt_23/users/201/uG1QyqJPsbuX3hav_1671112612.jpeg', '5CG4504ZPQ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'zn3YwScyj4GpcMh3I8mZiTvPpOUiEmYXE3YEKYV5K2XA3F53fu0rCGKOREQ2', NULL, '2022-11-29 10:12:52'),
(203, 'logewi7783@imdutex.com', '$2y$10$Wg52.6w9/97Y/xnVcXLd2Ov2yUBCA0u0VJCU4s8.pX2z3nAwjb6eK', 0x91924555b474d228e5a7cdd8466aecb0, 0, 0, 0, 'Naseer Ahmed', 'Soomro', '2015554477', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-18 06:54:50', '2022-10-18 06:54:50'),
(204, 'hanaxi6329@charav.com', '$2y$10$EIFJQuMsGZUKwKc1lXh8LOYcOmxyd4wDEVyB1m3oMUl9tNwZfnQj6', 0xa4cba46bed4b2a67ff616ed925b75fb4, 0, 0, 0, 'Dev', 'Manager', '3362661194', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'epOkmWi4vO2883D1jCPPvjjQ8ybKHOG7TM07Sk5irC8PL44ladobRYLzgS2IPY0Y', NULL, NULL),
(206, 'coyol90350@inkmoto.com', '$2y$10$S0d6EF0Q3DLxc7yb3pTVyOSuty70PzUV5KW7E17p02GagvHA8Eb1u', 0x07427b930eb2265dd84ca755e80c9e31, 0, 0, 0, 'Dev', 'User', '3372667789', '', 'CNU412D2Q0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'WrCYBR4iuvAPyMk8AA7KyxUrFBWrqthUlwQxHjQvllM0YKnhFZepDIMIVZ1J7QNt', NULL, NULL),
(207, 'gotilej436@klblogs.com', '$2y$10$RfRg1VgC5CH1r2wnoCGpvuMfTt0zwzDta5/jI2PSx2WL7soP/08Ga', 0x6e9d15a4419b011cde2edb353f7ad53e, 0, 0, 0, 'Azan', 'Tariq', '+9212345678', 'tth/alc_50/gallery/all/MWdUwYGQuObqEqbeL8IIoZnFkR31987X2QoAqk7U.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-19 05:53:29', '2022-11-19 05:53:29'),
(208, 'vorey38600@jernang.com', '$2y$10$RfRg1VgC5CH1r2wnoCGpvuMfTt0zwzDta5/jI2PSx2WL7soP/08Ga', 0x9230922b19f9678be11e90f9d5e8086a, 0, 0, 0, 'Faizan', 'Ali', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-19 05:59:48', '2022-11-19 05:59:49'),
(209, 'ceroh27742@kixotic.com', '$2y$10$IGAGc0Bjn5ZchYL3fUm9aep8qezwsjrvmGsJIKdoC5S.U4gT4iv4O', 0xe6e41396fb029f7188e60b9f33c8c5eb, 0, 0, 0, 'Dalton', 'Brooks', '205 455 8987', 'tth/har_52/users/209/4fXF8Hf1XhW4HSYe_1669810815.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-19 06:08:37', '2022-11-19 06:08:37'),
(210, 'solaj17797@invodua.com', '$2y$10$WiFDmM62nZyqEb4YJYaxze69TlT98IhTMeEywnBCHelCl8sfcNdW2', 0x29794e09da6017ab086705452c1b9c28, 0, 0, 0, 'Naseer', 'Ahmed', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'xRLe5mut6NJVap74ddKs4piKF3uyLbmMK2uAVTIgOL93nQN2dNeTu0jmkFHQ', NULL, NULL),
(211, 'vedote5697@invodua.com', '$2y$10$KwaSw.hoCyQ0Ubpykz0DW.qy3pLAoiCkW2DPGikpSbEtfmoOPWAG.', 0xfc90f4f475e898256b978c48d4ea1190, 0, 0, 0, 'User', 'Role', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vm5qyiKM1C3h1PDg3YuzJiVIBaY0Hr4N6ONmBrGbBHgtbEm3RUk5MrKZ5aq3', NULL, NULL),
(212, 'wabex44287@kixotic.com', '$2y$10$C46dK1rfc2hwwVkAolcVlOod2ReA8wB5SUMXvhewmAgaZofggwvOe', 0x7c2914ed8f77600d1403397c85cb38c6, 0, 0, 0, 'Snapshot', 'user', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tBHQQCqBzOkMigsyXwNYJTWL4RY3dDMQcyGBgSVa9sES3OCom2hJB3wTLMcs', NULL, NULL),
(213, 'dajiyim616@jernang.com', '$2y$10$UScYNWxrPzI5ufsdJGYAJuLX5OGCCBiv0Ol0825ClOWjN4KUbMc5m', 0x915ac293267cbe1de7fb894a13c30f34, 0, 0, 0, 'Mustafa', 'Ahmed', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8kmeSbEm0wrF2qKVhxEkjzrVQ6QYzPK0CtEbP1uYwWzPHDL3mfDpnZzaR2Vx', NULL, NULL),
(214, 'h.saeed@crecentech.com', '$2y$10$dOWTCM1O6Jc2..BlHO2B4e0xmHzxDsnhX13jt8W0I4rHrDZUQWKQO', 0x5c6169979670a4554bc3f26429acf779, 0, 0, 0, 'Hamza', 'Saeed', '3048247277', '', 'CND5035K8V', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Q6SWaLv94Cnqob7BfR7uZ9EeYH43uLm08p3pukbNhRP8HfUvjx9l4xsJ97UN', NULL, '2023-01-18 10:19:23'),
(215, 't.ahmad@crecentech.com', '$2y$10$dEpqcLyTOVCqWzb9KtGbCeTRlv6LMd5XHKjiH.TXfArMEd02d2wIy', 0x98d938993f84e5e4ec35fe9e3c66f447, 0, 0, 0, 'Tanveer', 'Ahmad', '3314692646', '', 'MP1GUU74', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dJKQwSK1Fxuwud5rJCgnPbK2MyiBLTRHQbhXR6AADeIkpUwVbtFEOjzq0USf', NULL, '2023-06-01 07:38:49'),
(216, 'l.naghman@crecentech.com', '$2y$10$GHjktaeGQz52PRzeCTJQqu0WupQMNnDOTU2WOUWKDnHASdLHMfl/S', 0x017d21f7450f8c975d87d2c7de3d30e1, 0, 0, 0, 'Laraib', 'Naghman', '03164555075', '', 'unknown', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VeZVbCdsjO5zEaKgrYwwSjz2PrgO0BphFtYzOMaW2WfVxz8G2x3cOIEAJGNj', NULL, '2023-02-08 10:29:40'),
(217, 'a.zulfiqar@crecentech.com', '$2y$10$55/i87yeXRji7/7qPTIYSeCFkuJY6RTsEnZbn6G/NoaURfp8XTR3W', 0x3edbfdd5293ceff76956ba708658a444, 0, 0, 0, 'Abdullah', 'Zulfiqar', '3234346918', '', 'FMWJXZ1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'xrsE5FNHieGae8FJ4QFw2XJmhMjm2CLZVoWSpnUDE6RCsDf794DwT7Fu8mH83diA', NULL, NULL),
(218, 'a.asghar@crecentech.com', '$2y$10$4Ls9gdjSHzWSPS9CEcPri.6IhIAPgQ2nMbZ0Z4c/RjbvGvFW4ircG', 0xfe72d05d4a3d94af5b1b45bcaa9bc8cb, 0, 0, 0, 'Aqsa', 'Asghar', '3058392726', '', '66YVXY1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hdzteopkwA71JdAIOGuFh7EHXDnGL2wMQ7Khhm5oRjk1vJBUesjWoKgrfJPR', NULL, '2023-01-02 11:55:59'),
(219, 'u.ellahi@crecentech.com', '$2y$10$lInstefRFEyNg3a6kNQjteZ6c0C3p3gPSmBgYU3U4yrd86S9eARlO', 0x8c95772049d3116f87b47e5208399645, 0, 0, 0, 'Umar', 'Ellahi', '3089455751', '', '9CYZP12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'E4T01goUtd6XMSsaFD6K22EDhzqZxOKMRX27vaZrSHODCB1JiGDEBxajXb9lN2aS', NULL, NULL),
(220, 'm.assadullah@crecentech.com', '$2y$10$AC3iLpNe0hl1vnhbLA871uUnFvpfd5G7slhxdR.TLWBX6jd4oa8yi', 0x04551961b1bbdad1ef53b1f47d894b07, 0, 0, 0, 'Assad', 'Ullah', '03114809337', '', '6Z75VZ1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2YtcPkryX1JRImtFMTsgsVMG4bhLwkq5EzARKnXQAkXAQrWYxwYMkMsTFX1m', NULL, '2023-01-18 10:25:03'),
(221, 'a.addison@crecentech.com', '$2y$10$oHVub./fGgWXAMQmXs6/dOfcNpkGQqk8NX6uGt6oguBe.Oh1bZmJa', 0x59b0f12fa3def2a1c0f05587d792bdea, 0, 0, 0, 'Austin', 'Addison', '2015552109', '', '8CG4251H16', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mzBNKh1cs4YNRntGiFbW2pTlPAy3OvaOXiwkBG7ulyeUMG6WzgipF22p1Fj3', NULL, NULL),
(222, 'renis10835@rubeshi.com', '$2y$10$mohFlzFdyH2PDOcGC3ZrSuScbU8PrMmUlFMPRTS1awQpJ0Rci9MHO', 0x286234802fcd7b1d2a04265cf938f965, 0, 0, 0, 'Live', 'Admin', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MyEQZEzBwRQCjCZGmv01ZHqTJwjWaf3FTKXdGugtWmzO5mcqjsSwUs29ygG7', NULL, NULL),
(223, 'j.bhatti@crecentech.com', '$2y$10$9DvL5FTErjibHny2e/fDW.ur2Lr2FnnktrOMxy22Ow2D495Qoe1l2', 0x8a9843c3d4f8838584b820699b9e0e67, 0, 0, 0, 'Irfan', 'Bhatti', '3091503545', 'tth/crt_23/users/223/tXbPFdmtZBj2lbuc_1669727219.png', 'CND01277KG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'jhFoq8fXKEx71ABxSstjyTy3iiTfeUH5tMSgU3nVqlYlbA0AfnNQ2Pb2HKgj', NULL, NULL),
(224, 'farzand.ali@crecentech.com', '$2y$10$zwi4A3YdtfA/0vpj7rxVv.v8loMIiB5P.kXluOqphi37WM9nt1I0i', 0xff37b30e924a62c5f91127999783545f, 0, 0, 0, 'Farzand', 'Ali', '3214063879', '', '5CG5400QC8', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gae4ENVCR1BYeXrvGGtQKH4CXWOqeCVugXjvBlCDk2oHBxd9SImrpN3Za5E0', NULL, '2022-12-05 05:27:56'),
(225, 's.hayat@crecentech.com', '$2y$10$X7G.Ab034joxODkNwkrnhuJbtwy6u4hcA/v46owulCqjZCOHUYP/W', 0x57021f692006da7ca8ad7d085b0a829b, 0, 0, 0, 'Sikandar', 'Hayat', '3489613980', 'http://125.209.112.83:9093/files/40', '', '5.3.8 -QA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9SxPOJRjroHMDmW93sF1deZANe48ZKaHTfFE0fJH95WVEd6Xncck6Rg9ngdD', NULL, NULL),
(226, 'a.tariq@crecentech.com', '$2y$10$Wg52.6w9/97Y/xnVcXLd2Ov2yUBCA0u0VJCU4s8.pX2z3nAwjb6eK', 0x26c4209630f1077efe315a2dfaa4d53d, 0, 0, 0, 'Azan', 'Tariq', '3364992195', '', 'CNU412D2Q0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4mMBa1bdpzgAXPLOa4rRSlaq4iTzbktNFWmb9h5Uw2ULcbbkmtpLXT2nLy4j', NULL, NULL),
(227, 'f.highmore@crecentech.com', '$2y$10$I4i39nmBeAStYdx3G8WnS.SS23HLe8QGojVk1VLt8JZICf8ljVYLm', 0x44d709aeebd1f415483a3b1b379f5c64, 0, 0, 0, 'Freddie', 'Highmore', '3059332634', 'http://app.tasktrackerhub.com/files/17', '5CB34302J7', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '80cfRZGUSDnXhaantfIhLQg1lMxV5AClxP1XXv6zYR95u4tamXPNzUbkK3JU', NULL, '2022-11-28 16:20:30'),
(228, 'r.butcher@crecentech.com', '$2y$10$nP60blwxXmSZcG2VOXDBRugX7HTarIXyRE5vDrgJ48wV6GQjKyVze', 0x23b10ac930c41e2c87e0b60b3465c481, 0, 0, 0, 'Ryan', 'Butcher', '3059480521', '', 'BXDQ302', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'i0J3FUv2utB9LtzVnnrsOWp0QpuvxJuqchnELsQ4RSRHABFeyEgGURpxwDy9wgCj', NULL, NULL),
(229, 'wabisaw363@probdd.com', '$2y$10$NkdIxsybIcCr.KDGI4vi9OhyKSK4UOHUEqbiFluo4FCNFW6klYT8a', 0xa7449fdd3a087b90caf180c8cb12dea4, 0, 0, 0, 'New', 'Snapshotuser', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bqd1lkxQFAAq8Y0eBQqHKzNftfbg9HljbS44FY7GOBp3GfKwagJiXqGkDZF7', NULL, NULL),
(230, 'l.marco@crecentech.com', '$2y$10$rxJR06xErCrNA1VRUpsYPu4.os0MTFcfAaI9IJvGxJXnxal4SBEqq', 0x0850541f136ae474470de49122c2beeb, 0, 0, 0, 'Luis', 'Marco', '3353535505', '', '859DK02', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OH3yrwsyLFKC7IegGdqGcX1i5yoacQXbSR6oAigei7MF08wxeNBdjWiymBJscu4N', NULL, NULL),
(231, 'p.willey@crecentech.com', '$2y$10$MCBtNw2jD16jzILzHETat.fJN7PyfKA25OJ20vWSYjaUMwDFdHIKW', 0x9d0bf56e8b16d4e29bac6d118f2aa019, 0, 0, 0, 'Peter', 'Willey', '3174857835', '', '5CG5243WSK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'XQUyZ26KKdowaz00lKDCuQiGjsuX9GnJXON7Pkyy5scuxEHtF9Bplt0lO53uEL6j', NULL, NULL),
(232, 'r.redford@crecentech.com', '$2y$10$7WZCVubl0KcRAs4/ecRvIeqkvMuqUvQCIFA2DC4eWSN5CKd1CeeGi', 0x9d2e662bd2120461653d4af9dad0b2e1, 0, 0, 0, 'Robert', 'Redford', '+1 (855) 855-0860', '', '4QK7N12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3yT5LtBKKU9knbJMOyc2bHDOOwCoM49Nq4wzlSUUEUZsI1gaekvLW2PNKN2YixbQ', NULL, NULL),
(234, 'capavo5585@cosaxu.com', '$2y$10$r8q1/qm2B8RGBlHpewbSiO7u/dNJCENVt9boNHTHy2559SlrJOwe.', 0x0960ba0064bccec30713c3b858c98d30, 0, 0, 0, 'First', 'User', '123123123', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bXKrrVzWaa4wtVHCRsVm1npw9z2WEcruM7RTwFQ4R0ltpDZqVZA03CQCrgDTKlpf', NULL, NULL),
(235, 'noxaj32236@dmonies.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dx6hERjp1EwsNpwCFNxlGMtIowzGl1H82iimPXOL4xo2aQ4dx7yNy9h5ec9jY8hK', NULL, NULL),
(236, 'gakaj45878@cnogs.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FyEGRInmO0UtU7Tdme3pNElAVhPVFv75D18N2poO2rNOuLupWf9YxVnN9UmjKeVx', NULL, NULL),
(237, 'z.shafqat@crecentech.com', '$2y$10$UQua/6vwaNGRZecpdd.SRew5nHV2p9TQEOBuTG2mRytAqh5ygfaJS', 0x40f43700eb458316fd7116c05c18cc39, 0, 0, 0, 'zubair', 'shafqat', '3026170893', '', '5CD62375QR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4HkM0dkUUc64mX5AELmSBg3OpKkKs6pPPnQCiGc68e0eInqbSAeIyUrVd6O8W2ei', NULL, NULL),
(238, 'm.brown@crecentech.com', '$2y$10$LrwTP/tHCNOZxH1KgC.TSuGGNqWpSUYJnlgDOkpr.ztUmzrofkBTu', 0xdaa323ac18c5cbfef36f43d8a9bd4a75, 0, 0, 0, NULL, NULL, NULL, '', '8W72YZ1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'qEyLBtrT7CG2DYOGgBYRS7hWXPoi51k2XYN8IYwURqdx0wQlLzvOMKBzNnaQ', NULL, '2023-01-19 14:08:36'),
(239, 'b.cooper@crecentech.com', '$2y$10$qr6ITbyPL3en8Gwvxr5jEOC9dzgCDbq4VOJcR2RjhYEzOqowhnn4W', 0x3a785d3af0ac491add5d887758001b4e, 0, 0, 0, NULL, NULL, NULL, 'tth/crt_23/users/239/wxZMOAVHA0R7Cl81_1674148905.jpeg', '5CB3501HDX', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'HRH8h1m2Lju9ZlFmlC5g5LQ6EPESOsGyAJr1niJ7zTRHthdc1EZoUrh4eDy6', NULL, '2023-01-19 14:01:05'),
(240, 'charlie@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6i3GDG60II2oy137PMOsky3kgW71ToUMXAvb9u6qqhfhmL4ST8TjqPbFunCh0R93', NULL, NULL),
(241, 's.dil@crecentech.com', '$2y$10$sAR1ANjtWFLnFPEeOncpDun0E/nrqceyQ6BkCtkK60PLVDRffcZb.', 0xb5b896e275136b02cb7a4b759e9f3f1d, 0, 0, 0, 'Sadia', 'Dil', '3084800695', '', '6YHZTN2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'G6wUKukXYAfNejsMSfVOGT3NSBXmwyHFlh5pgiuKzzDQYBJ4bBJ5ybGyA2Se', NULL, '2022-12-16 05:28:25'),
(242, 's.ahmad@crecentech.com', '$2y$10$14c9r3fyLdlU1x4EobKBu.WTUB.3FSUGWmVpGodHM7yRrXXtgMdQm', 0x4d70404df1d4d4eb747e6f1b041c89ae, 0, 0, 0, 'Sidra', 'Ahmad', '3164532325', '', '2CE3201MTY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'xlYY61CEKB4JEjpcGKmHWmjYMb7L0ABDlPviALZixe9pQ4FDbYl35NxH4MU3WQuv', NULL, NULL),
(244, 'F.ASIM@CRECENTECH.COM', '$2y$10$CccnRdfGe9bQW9qkOYihv.Fba6wMyNZIqCCP1PJSCQreX/Idvh/MW', 0xef9b542d16c7008c0ffc3dec3dc2ea76, 0, 0, 0, 'Faiza', 'Asim', '123458863', '', '2UA43023PZ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'EfIdK3Spblwr30zAGIsTI1uYF8DIHVbZR5KpySCDE9Aa0ZmAQib1SD4seNBarq9x', NULL, NULL),
(245, 's.gabriel@crecentech.com', '$2y$10$pUB8TsVJyJDRJ04DAL77VedR3fibQoezIiAnIJzfhwBV2mqw2gklm', 0x4eccbb11c78cde8c53cd868a9a624e08, 0, 0, 0, 'Shawn', 'Gabriel', '31194-06519', '', '1TRXYF2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'W0ibqaKOBJr3oY9yds9I1anaBwJiGDFssZTqih09JVjduvy5tw5nYBTErq35EaAw', NULL, '2023-02-06 12:33:15'),
(246, 'a.stone@crecentech.com', '$2y$10$0QBlrht623Y7Qdd3s8ETE.zbTpDWX9PSgRZM7slqyeJrRmmQQIuge', 0x30bba1f427a68ab0f122bd4e32e8ed48, 0, 0, 0, 'Alec', 'Stone', '3121480955', '', '5CB2461HMJ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AQFEt3LPWyi1c5oNiU9aIDX5BYlnsaafxF63hFQlDgFp1Rot93plsU5zqwwTWQ4n', NULL, '2023-03-10 12:42:03'),
(249, 'barar29377@letpays.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LuHoie6MWriktkl57vybyVTsATikXEmH57qLarahaJiWWsvQyTabjj0fXfWNpmGP', NULL, NULL),
(251, 'yodeko1424@letpays.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1rr63Tm4v3Y6BOCyfFYChXz4Axz7lpoCjCVWh9C3ERcGeBwktKRmzFr0I4N5f68p', NULL, NULL),
(252, 'j.adler@crecentech.com', '$2y$10$aEdWvYBr5vSidzsr7TvlY.XHWzla.CDLd2f9b84acLAGAd5QEPjDi', 0x4bc547fafe4b5e6e3d94abce9e6a1fc9, 0, 0, 0, 'John', 'Adler', '3315276169', '', '8CG4251H16', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2iwKB8f5HXT4jkh5Vvg32RhAbwZ6TMpl1MaqSyIEXYHqXIbRCkCUHWQn5nPZ', NULL, '2023-04-13 18:49:30'),
(253, 'g.madison@crecentech.com', '$2y$10$AzbvGbOYM.H00m94zkn01.Ok/IIOYJiTp9iuZ4BkHYQtJQPUh8PGq', 0xcc3a015ca4132959fe4d4eefc5918dcf, 0, 0, 0, 'Basit', 'Ali', '3040731209', '', '5CG50336MC', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QFyjEyTyVmpwgI4MBDqvpbLIJXEWgQBMgxCNSL8PtP5zhK47ZmLIqoHQvHKj8JVG', NULL, '2023-03-20 13:27:57'),
(254, 'k.ali@crecentech.com', '$2y$10$9S2dWu0wDjdyYXR3lypKeePJKo3lvREFvsOkbbYElkMUCuw4jO0xy', 0x29bb2771b3dc4b697fcb1da997aa9f0e, 0, 0, 0, 'kausar', 'Ali', '3417308883', '', '4ZLZ533', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'oxxnLWCiwqDSMWzxj1HoDfxnmuE6iAnyyChQQsxNQVTpszDNjbjjEMd4WWQEu4VH', NULL, NULL),
(255, 'p.jarvis@crecentech.com', '$2y$10$33dFj7V.wtQdyQ5Xz7kryO.GnXvXFkwy7X.Wu.tzf53qkGJ6Adr6G', 0xe926de1e2b06b6a46a2e1ce0c094cd6e, 0, 0, 0, 'Paul', 'Jarvis', '3218544654', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SJ3HiH94pEvETaqa5zvhpbMiqMGGCZ3O6Tv6RAPfvlBrdRaJQdhCCf4zHVgl60EB', NULL, NULL),
(256, 'i.saleem@crecentech.com', '$2y$10$vpW21qxB5LupzHVdXWez9ef8p07krwYhcl76VnSVoDBJ0vrgJZDb.', 0x2c81c502c16b778abadf02311b5fa98a, 0, 0, 0, 'Irfan', 'Saleem', '3352222042', '', '2UA3231WGR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KV2E5SFuiiwgeXOY8v3LUxrLJxrgFColSOXJhXS2jhhZOUA9NRWv2G68DaOg', NULL, '2023-05-30 03:25:28'),
(257, 'd.draper@crecentech.com', '$2y$10$BXMAI9bSTrzxQ3iIxnEiG.6B2jnshirBmKu.mzwbCwK9ptVxBLhjS', 0xa40d3c576f0498fad89d61fbfd9b7780, 0, 0, 0, 'Numair', 'Imran', '3458441877', '', 'C5CX5Y2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Zq4ZrUwuPG0SOz6rZT1mEnEFFS8aKHRn4Sio5W5bd4xz1VJuQt8TQu4OK2O0J4BT', NULL, NULL),
(258, 'a.nelson@crecentech.com', '$2y$10$t5Vioxh4mIfUpB719zEeM.sVK3ZDPxa/C.nnItSucu7fprKhBpEve', 0x11dff0f176122f66ba531da5ecb711c0, 0, 0, 0, 'Farhan', 'Haider', '3478422110', '', '2UA3520YDZ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'viDEI5rGQIdzsf0r27AWPgyvh31qMo23pH6MFUrLOjowART6s2khoKhpwC90', NULL, NULL),
(259, 'w.shehzad@crecentech.com', '$2y$10$qLt2EXKzFKI3l9re9IW/U.TMhinT2XuAinv7AHhzdm4iZK144sY0.', 0x6ef3c3561d54f8bb73b16456309ab0a5, 0, 0, 0, 'Waseem', 'Shehzad', '3037112433', '', '2UA40328HY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6PXgJ1HWyzUN1Ae1xM4YBCukRdojWH789TUgSWgRfEMRV6gYUO8b6bwFk59TtT06', NULL, NULL),
(260, 'g.mills@crecentech.com', '$2y$10$UxKkc2.jZppPDpytUL/z4.eCYnrsgZauHD1bs7MMpbfS4xQ2KvlI6', 0x89b68ea696d0964d087db386abf53e00, 0, 0, 0, 'George', 'Mills', '3081250602', '', 'MP177MFC', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AYqLSWX0CojHEL9epRHn779KtA1EHQi1maa4tZTf0ThMdMDj3xRHs1fdERQ9', NULL, NULL),
(261, 'm.ali@crecentech.com', '$2y$10$13E8Udo3V0luNVT4WlMuTuQy4uPDiotqRZ2ERTwrp78cIWtclGH5y', 0xa34393c540900e1d593b9e2018483851, 0, 0, 0, 'Murtaza', 'Ali', '3344382898', '', '8P38H12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'gi3JpRaBoITo9m48ExOvEdoF1QRM3sxROUymFnCo7im2C0Rg1Odk29rTOxCX2FjW', NULL, '2023-06-02 03:19:22'),
(262, 'm.shakeel@crecentech.com', '$2y$10$05xB1p47TPHcmrLvqf77LO0MbLGqlq3xBVzUVZpsiLwueaAsQt7Q6', 0xc462134a5592d79bf174b75b4f0481ec, 0, 0, 0, 'Muhammad', 'Shakeel', '326354541564', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tmq2xpWeVxsa1Pild2Cw39kW3LQSRzdMfTcMRGK008CG4tNGwBavpGt3Jl0QkyW7', NULL, NULL),
(263, 'u.abid@crecentech.com', '$2y$10$z1j8GaPc0idWIxXNymKLxe/epdjpqJ7n5lmPhd.8z/RCU8/vKpORu', 0x81d76405ede5ae1b848c58ca10864908, 0, 0, 0, 'Uzair', 'Abid', '3163715703', '', 'R90GUXCP', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vd6IJXOoxcVM6LSiKz8GGv1oEEmgW5Thbzfu6tJbMrF1lGqL2NGU8wJvolOz', NULL, NULL),
(264, 'h.ahmad@crecentech.com', '$2y$10$Mv2Rc8.H1AOfeVuIaxT9J.9bIhxWhHVLUT674DNQ2lMymDfqjHJaO', 0x3b1353ccc5e5c3205639dcb96feba8fe, 0, 0, 0, 'Haseeb', 'Ahmad', '3092527196', '', 'R90GUXCP', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IUxt0ype6pDrFPMRbQWXCGcOf7avJC5NvDysQeKz59i03h4IaWAtDRhK6lVaUmfc', NULL, NULL),
(265, 'a.saleem@crecentech.com', '$2y$10$3LinhgC8MAsj94Jq5iI2COF/Zbauu/ho/r82meVUOC7udXBj0.93S', 0x15468fd4af3ae009834cce1508f0e5fb, 0, 0, 0, 'Asim', 'Sherwani', '522886956', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5BEEL5nrNyDdhYocwFeMb38B5JeSvBHFG6jH7aBRX8JS7mf2GTa08ylnHcYH', NULL, '2023-03-15 18:32:53'),
(266, 'h.mehmood@crecentech.com', '$2y$10$ozeCz8S9bK4rsxiOq46Oue458D3XqabCIRYkcIqOw/PUfhOirB.bC', 0xccfd0013985dc9ac3bd04ae63a9aa2b3, 0, 0, 0, 'Hasan', 'Mehmood', '8558550860', '', '5CG5344MWM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0yTAOYjfeO9NHJmUkEcAgwKO1j0rqSO8ez7YMNHKolr1TQdJMeg7GK7uN3DTt7RB', NULL, NULL),
(267, 'm.hamza@crecentech.com', '$2y$10$Smx8LqTwXRqX0IGHQyuf7.qIyqNVjRDivjzYyWt5VoG.4alrDbqWS', 0x6812ae37145da45adecb58d96ea63a77, 0, 0, 0, 'Hamza', 'Zohaib', '8558550860', '', 'MP1DK672', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BnqkdBbySdA47PwjVZM7kcsRgI08z5bQ3OI6HmRaL8WeyBEoV8zc9HBZY5l2AtxU', NULL, '2023-03-07 09:58:51'),
(268, 'a.hamayon@crecentech.com', '$2y$10$Kz58QZb/fv9FCIJbPhiQz.DTd1vCDRtGKqaMAkXa0dUzuEzEyHjf6', 0x0ddadcfed8f2fe9ba208df74c7624770, 0, 0, 0, 'Adham', 'Ali', '3204714969', '', 'R90HUPTU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rGoZlepsydpdI8gAaIHrfIrwu4AkIOsRczL61ZluN6PtZUb0zTmz1j86IfEc9qY2', NULL, NULL),
(269, 'l.freeman@crecentech.com', '$2y$10$O67HBe2YJN8x5wZdY4kT/uY66OWx4kg1mZ0PAa31aVivgGpegUKvK', 0x3b69dba10cebfc1936026e343fc8dc9e, 0, 0, 0, 'Lewis', 'Freeman', '3006768681', '', '5CB3500XZ4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2ORATcNS9r7kLityxZD8BUKDKKm9ffgZxb9PAd1hUdpCSq3PTionm0fnWjqiLmSP', NULL, NULL),
(270, 'a.brandan@crecentech.com', '$2y$10$af.OUc0//OUEG4l6lwJ4T.OAe7JrHtb4bGrORJos/QCHVaMuH5/i.', 0xd227a9aa03e4b65840a18aa1da502da3, 0, 0, 0, 'Anna', 'Brandan', '2015550123', '', '5CD62375QR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'etoaw0n5lpzPQoQ0VVYW9IhAJyJ870UgemAProCGWPDD8pTHc4NvjOTNWxAm', NULL, '2023-04-14 13:34:28'),
(271, 'm.ayaz@crecentech.com', '$2y$10$NxGJTf3PnG/96iaBRK1fNuBVt7/E6QHmaqVMQdT2F/6AeYEgQmTRa', 0xf6a1c10aed1c007bd5d55aaf46ab4212, 0, 0, 0, 'Muhammad', 'Ayaz', '3217545917', '', 'E43RDF4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SufgA6rReedrq2Q9fNpyVj93wOiRxYY7GWRimCUr9VpIP4oboMjGswL7fhDQnxyh', NULL, NULL),
(272, 's.watson@crecentech.com', '$2y$10$tA8/ibPaECgJLvxGournmOOsyZsyN/LkpeWHiu8WBParOr9si/iOS', 0x06d41117b11478b82384084c8f44c301, 0, 0, 0, 'Shane', 'Watson', '3114457162', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'zCOHpdnOIcLuHBsnqzYF75s3KWzv0cpwZPeTo07s9keHuBuTlW08r09mnrHY4VIw', NULL, NULL),
(273, 'wavabe9052@breazeim.com', '$2y$10$XsfFFYfDVRO.hgfrwVSR9ux5cYiRX/Y9eXSV7PUo67znzkPWh3yIa', 0x28a80a18365f0a3298053ea284316e0f, 0, 0, 0, 'Alpha', 'Manager', '3364992195', '', '5CG5400QC8', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mcMheIllGC29wicLJoONIpyQSwQNyUmtdr8SfS7zA2bYjkaoBthKFPZkWNHn', NULL, NULL),
(274, 'keveh92598@brandoza.com', '$2y$10$8HbbuMx4RL2cqWbfE0.Z7Opv4vxdlbS8xw3uE1LL2MP4B24gVql.e', 0x40d6c3ab9fcf3511ff6ad64e5301f88c, 0, 0, 0, 'Alpha', 'User', '3364992194', '', 'CNU412D2Q0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'f6vtcpAq4waaX2AiJNiwErYwhij2Wj7Ji6tS3ARRMQiJcuEZQUDjIt6mzYbx', NULL, NULL),
(275, 'hitahit252@brandoza.com', '$2y$10$RjJoQp6PWYqonPaVD7xUkuH67jps/oBEMA130gU3lnj6980VxdVFW', 0x924d5150efa54357f7f2b5224fbb0299, 0, 0, 0, 'Test', 'User', '12345678', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Bu9zKQipoCQsuXsHanxFByJRufh7cH9xHt7zfXqcBBPzqLFQJXpOdBvmpDB6', NULL, NULL),
(276, 'm.ilyas@crecentech.com', '$2y$10$GOGYrhSbNJ0kG2xjyIb/VOEWUDjItfJbeXYSTc8xPzajfVD7Vx4U.', 0xded096762337a604dd947241d94d78ef, 0, 0, 0, 'Moiz', 'Ilyas', '3214297816', 'http://app.staffviz.com/files/21', '38HM3M2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'EXfo9gLubUTxayMdDIAKFcwDj1OSz7JRHvb0P0yqdHrprSnct4zMQ1A7TgCG', NULL, NULL),
(277, 'h.khan@crecentech.com', '$2y$10$xp.FUCG5AXvOoXwAXdqXc.EwBpkzb4RGTnjQqhMqxEpH6KVcE8FMe', 0x002745e76db88674907c86ebdc180c2e, 0, 0, 0, 'Hazrat', 'khan', '3086712662', '', 'R900B8AD', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'iQFNbwowatUvqJQVP100lP2V5tBaEO0dYLv9eftz9pelNYWlbX3RTJNGm8Si', NULL, NULL),
(278, 'm.ahmad1@crecentech.com', '$2y$10$UwlzSmYy4/Q/5mhZ5qItDePvHv6uPGvOdVDiT89GD8IQnnGAEJHRe', 0x746418087ce0262e1f398fae6ca67d62, 0, 0, 0, 'Muhammad', 'Ahmad1', '3234765880', '', 'HJ67K12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'p2KdZh9tPY2ezJudcwOUlGMGfQGlQjo4LDvEynMB83QO6Ap9XG7cx9CfQZUFSApg', NULL, '2023-03-20 04:51:57'),
(280, 'n.saher@crecentech.com', '$2y$10$tZItj4ly69hvNlauE0cOOuT1A8Rj60dluRy6KBOoN.0.QB9Sb7Bs6', 0x1de997bcdffcc0befc7cfb2073baf786, 0, 0, 0, 'Noor', 'ESaher', '3074140087', '', 'R90BGL5X', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TSBeTros7HkKlTBdR41RkjHccs3ItRlt3aWECzjXh63sZPRFmCUuB9fBmI5l', NULL, '2023-03-20 04:16:40'),
(281, 'B.TAYLOR@CRECENTECH.COM', '$2y$10$roZA.LZdBTPwC0ttSOAn1OcLy0pJWCdN7CJvzZ.XQMw5aYUv8Va2W', 0x939ec7a6710cf2e8693bb02ed367fbdc, 0, 0, 0, 'Benjamin', 'Taylor', '3150206877', '', 'R90K7NSP', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QjkPzAX3jJHqGtBhMMmNKedz4udALNNUO7SLekPK39HLmGcpAtq6XytQx7Wu1BDC', NULL, '2023-04-13 18:42:14'),
(282, 'b.taylor@crecenetch.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '7rGojyDvd7HhWIQYwXAExiZYawiUUzWoqqhmfk6bk9vHuvs9IAko4Yae1BMtyLER', NULL, NULL),
(284, 'm.mohsin@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9AYgwxB1qK88wupfDdP9xiMnaE9p95ZkqyfH7DatT2GfC4iriEyIu3h07iODME2R', NULL, NULL),
(285, 'kayej53741@aosod.com', '$2y$10$HV4ydjFr0o1waCE6.MfkmOoDGd0Pswseh4GwYEu6fdK78x8bGOs.a', 0xc5b133b8ed987ca58d1416be15bf11db, 0, 0, 0, 'Jillian', 'Armstrong', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(286, 'j.flores@crecentech.com', '$2y$10$Oosyge/r5ma4QyBEx4oprO5KkIFnoqVpybCbsQ2O.pmgtY.cBdciO', 0xbaa6a5614dd02eb3c16b0c77ebacbbce, 0, 0, 0, 'Jorge', 'Flores', '3491603772', '', '8KV3K32', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hKdGGF1JnWGIrXDG4YIfM1xVZ9s1tMRMPBpILFf2623rpgWEmj38N1WuvRyLvXUM', NULL, NULL),
(287, 'm.mushtaq@crecentech.com', '$2y$10$W9UmF6Ujb9nUxuobNniMU.dS6IjL2fV8ijQZ0KYASIFgBeB5LHffO', 0xa7c8b6d0896ba56c9beeafc6d9fc39fd, 0, 0, 0, 'Mohsin', 'Mushtaq', '3224118400', '', '859DK02', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'wIBm7KIcBJKA687pIWVmcjfMHIG2EcMvY1opt6uxraBHQhqSwSrYE3Qb2cuf', NULL, NULL),
(288, 'xowefal927@vootin.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ISxnqo7wt4PHtTkJsseMJU7OzcC6NNcpc2jq77KinW7tp6EHnSHWW6eIJh9SaMqX', NULL, NULL),
(289, 'mowaho7766@wwgoc.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4crxCni69MLgWtOOs8F5O9DRPiXbBbLZ4uezEB1ZAJ3a7Bel8OngCKZZFF7Kt1Hz', NULL, NULL),
(290, 'wiweko6242@v2ssr.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'T81DspbZSFr4zQ1pJLDMyQnkGdBGJoxtl6x4B1cbuvUyKnMHsUvphtTx7VflJWvG', NULL, NULL),
(291, 'xetad87073@wwgoc.com', '$2y$10$ohQOIRhnjMVfDcmAStfujeNdsKt.P.1oeen/BALB6bTObkoDsecAC', 0x6ba845cbc803a96f594183bd23f641dd, 0, 0, 0, 'User', 'newABC', '2345235', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ulavtMUcw1DHckwu7OrCUtvYTU4BOYSS17tUjmBXZjyNDWj5l9YWMBDqCmmgVAJw', NULL, NULL),
(292, 'xifoken258@etondy.com', '$2y$10$sTiI0yNZ9ew0lNlYkqTeaODldEN8IBJ.FGkKUglcIt.dAipiDWsbC', 0x0cd7ed7471f09a7bd5a93830a5a01dd5, 0, 0, 0, 'Adward', 'Solis', NULL, 'tth/alc_50/users/207/documents/ipsHM9OwacqUeay3wg9WEg30pdCZjd6VXeNWhyuo.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(293, 'bicija7442@galcake.com', '$2y$10$YzKbif.LhbdgBa/GQBYGcOVF7ySG7RyAqm7uU2OcBBDh7u5SRrIfS', 0x85ee5421b55adce26e4faa0c3abf84a0, 0, 0, 0, 'Fredie', 'Roy', NULL, 'tth/alc_50/users/207/documents/a2acgDG8UQrqNGukkpFDYJ0VSlxRvGgldg0h226i.png', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(294, 'fecil24903@galcake.com', '$2y$10$Wg52.6w9/97Y/xnVcXLd2Ov2yUBCA0u0VJCU4s8.pX2z3nAwjb6eK', 0x21260dbb7ca50e5341ea26c233daa56b, 0, 0, 0, 'Azan', 'Tariq', NULL, 'tth/alc_50/users/207/documents/MnGinjeyhzfCMGk9MWk7MoUqWSp3eK68dewieGDY.jpg', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-18 13:04:42'),
(295, 'h.franco@crecentech.com', '$2y$10$r1qgGjW2dU0aNtVU0cde.OlZP3Vw5dJWSnMAShVDvJiomSOvvy/kW', 0x8680e59cd69557347d893b4c41f576fb, 0, 0, 0, 'Harry', 'Franco', '3068170861', '', '5CG6094H70', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'UeTXwNwbJ8gK3envhwTIbidD0IyLjQmg3RROve40zi79nr2NJfzkvDrJowNxMXGh', NULL, NULL),
(296, 'm.mahtab@crecentechusallc.onmicrosoft.com', '$2y$10$NgXNf9ty.Cbar9NaeMekcOYyFxKk7b6EbmfWXyXZNwcOj0inwia6a', 0x3df14117b3a941e7c3aafbb536576502, 0, 0, 0, 'Misbah', 'Mahtab', '3095131285', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'zW3zpVyky9QQDpWZVhbyMjSIShFCD8RcRWylOd415UrMVfFYAAh11iy57jsqqgve', NULL, NULL),
(297, 'r.rimsha@crecentech.com', '$2y$10$M0f/Tf3x3egbMCXgYZQbvOxu.pOusUMfICi4bXJLGBUY81QFRJOW.', 0x146c23b8e8d23fb906a6c42962279dcc, 0, 0, 0, 'Rimsha', 'Virk', '123456789', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ep4fokzyEWBc73EGnQucs7dWWftdAfBdnicSKI829GDZSSsQQyhu0bJcL6Qd', NULL, NULL),
(298, 'd.scofield@crecentech.com', '$2y$10$f0H605NxmDrgMXh0d6Xdg.uFBijhS0kQy81Yo78NuOIl/lfNKlrau', 0x7661a51cbf11394b2e49477c9f4e39e3, 0, 0, 0, 'danial', 'Scofield', '533385233', '', 'MP1DPY0W', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QZ4EWUmvEXq5JqPk6241oqqQByVKmbyDZN04jILLibacjRzhsTiHVnmodGVMqcFI', NULL, NULL),
(299, 'm.rashid@crecentech.com', '$2y$10$QHE1M4Ch/W/iBqaIkhjlq.2Z1978BvoIvUjpnIKklkD9Qoq4.Ob56', 0x0733798440c47bb0744e6d405ac1a827, 0, 0, 0, 'Maryam', 'Rashid', '3364179919', 'http://125.209.112.83:9093/files/42', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AuYWab6yhzHlRzqXjGCu1zo1pwcY1aSVcxGwI7fHFCbPqW0dVvJO7ax0Fz0T', NULL, NULL),
(300, 'g.giles@crecentech.com', '$2y$10$w2t7fwJQhcgjkWMxEb9nSujE7vZwpyHbP/oMW0GC2JWJy1h8lcaRe', 0xa003899d8372cadfc3acf3760b029073, 0, 0, 0, 'Gloria', 'Giles', '6107561085', '', 'MP1GUU5T', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '78Bmw3X0slWeKan8a47XurL2SD5F59M5puoiWCCZPY6obg9I1mPqpViGOfL30AH0', NULL, NULL),
(301, 'm.mahtab@crecentech.com', '$2y$10$8HDrdflg/J4PqbXNnEgB3Op27SNsN.kUgAgHBgVbxoLyH.YNjDBgi', 0x1cce9beb7e4cda06821d0c63956b6ef8, 0, 0, 0, 'Misbah', 'Mahtab', '3095131285', '', '5L6FH12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uU1XLKMw1xITyNnmCMbSrX0gKu4KS7cOEqtWXKrgSpRZdlCbwRJTvxbcO3yt7a6D', NULL, NULL),
(302, 't.davidson@crecenetch.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'qPgpeFbhSu4uO66ls9P8I0VMA8ijGIWbGi4xz5tlo6vkkGXqwXN9G2dXO1b3AWI2', NULL, NULL),
(303, 't.davidson@crecentech.com', '$2y$10$Kfrs1CHf4Pldis22Nvpyzu4Dtp.Bk7rwMUbsJxdVNwshipruSPqDC', 0x783230ca5c33cb02ceabf5a2b188e5f1, 0, 0, 0, 'Thomas', 'Davidson', '1111111111', '', '5CS7RN2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vo8rCbvizKfqPgJmlY2Eqll9yhLNT2lgMySk9gXziNEdKBnU35boqT19hqL9', NULL, '2023-05-15 14:05:49'),
(305, 'a.zafar@crecentech.com', '$2y$10$9fE2WtniCTUhc1yBu/xxJ.aHWr38geLN9gwfXXB2vd96ZJvFAHbyi', 0xff98c8d9306c0684a1ac94b1906338b5, 0, 0, 0, 'Awais', 'Zafar', '3069887342', '', 'CNU352D9S9', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'zHXVMZ2hUKnl9QmziX5IzWduTWTbC9UyI3IDxJVXDAQMbt1kIeLkVEQSk5V2M3O8', NULL, NULL),
(306, 'j.smith@crecentech.com', '$2y$10$qAOx2ICFxBo6bhSPOmS5JerDqqBrvHoMQkrWSoVOnhVHOD8/8w/qC', 0x44e214bfc846b484dc30a73c54691ee9, 0, 0, 0, 'jake', 'smith', '3007033904', '', '3PQLY52', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'kEXaVSDf92N9xx1R3A4Sqm5lUFrfsKqilIugKdBkDd61pDewVESr3UWDvTsqsVXP', NULL, NULL),
(307, 'm.afaque@crecentech.com', '$2y$10$H0/w1IXyfdgCgk8YJUD.F.G11hEQ1NNYasor2IoZjxt2qbW8nJUha', 0x2ebac41bfcd9138b35d56f1742c3f1c5, 0, 0, 0, 'Muhammad', 'Afaque', '3144454742', '', '5CG4432590', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1TVpAB8KRcezUB2FZcHZiWgyqNbTxRCQkRajuQYsVp5QjkVaNk1Zvo2rAV9kLiIB', NULL, NULL),
(308, 't.walker@crecentech.com', '$2y$10$azPObaJ63qou/fUClRhT..55oJQZ7HXplefrOYrHcQBYmiYXl3gDu', 0xb3a8e4cbfbec5784562018de5124704f, 0, 0, 0, 'Tim', 'Walker', '(315) 215-0310', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '004cyrSiG9x9isbq5RjS9p6b0ULyMGbO2oIIx5OfxhvVjsxWpwCFwYohfFVB', NULL, NULL),
(312, 'm.husnain@crecentech.com', '$2y$10$X5qzjUcbqlcrNSXKXbrTh.3vegT2WG2vwJ.3gToD9idIAFR3EvsyS', 0x604d06299336ca14fcb7332135cead2c, 0, 0, 0, 'Muhammad', 'husnain', '3015701364', '', 'JQCQPQ2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'estlg1Fnz6PVet1dM0ACJalAbVvtImzAkV7X73PLtMek68OQG00ScL8PC47lj9UW', NULL, NULL),
(313, 'n.abbas@crecentech.com', '$2y$10$gqgXKyogjMRd1vZozPaa1eemJkv4mcgKn8ckITGE7yq58z3OMRHCe', 0x6d29ae3f54c79b5ca024157550bab379, 0, 0, 0, 'Nadeem', 'Abbas', '3067371295', '', '53KNQN2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jpnas8UCgUcQjwMv8OwZ9aTvOxVid9WmLWNubBPaWwAyi1dWIC8J9tTYeAlz', NULL, '2023-07-17 05:48:06'),
(314, 'j.robinson@crecentech.com', '$2y$10$Zs4vulcmk.YovmXmkC.w/uuPsIQoTcICg0hHGQDt035Dq9dukIHEi', 0xf0dcc8e53109f185fb4365ec0010815b, 0, 0, 0, 'Jay', 'Robinson', '3004220458', '', '5DP3K32', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ki7ZgXeSwbCyxas2Xxg08Tj7BNh1lqWeRXzpEli0q80nBYCdnwgFs5fwMO9NvDaM', NULL, NULL),
(315, 'u.shafiq@crecentech.com', '$2y$10$Mr/WnZM.ygXx8Fm8xJFoee/v0ZgaoSbMaro1ZUOeG0HyNV.NlPSBq', 0x7f64d55ef6ba9681bcb1ce5d47c455b7, 0, 0, 0, 'Umer', 'Shafiq', NULL, '', '5CD4180SJS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'wQ9GlEybaafPItTEGrf2yWS4tJWJIH2C5qWaERXzc4prYVxogMIu5WMl768q', NULL, '2023-05-17 06:49:53'),
(316, 'e.henry@crecentech.com', '$2y$10$rRXwP6Z8NIPfnnxsxJBKqOKiS0ljTdxIloWJ7.uQ8tN.BCBw46C1i', 0x7e0e5b63c3ff3cad8fecb934a0269637, 0, 0, 0, 'Ezra', 'Henry', '3405400441', '', 'JYZ1N12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3hD0qtqvPWS5Vf18HT45076jpC7xXNG8D38sL9vCUWKHDFRKs2TZLnfZWFd1fuUO', NULL, '2023-06-02 13:24:53'),
(317, 'vajival929@pyadu.com', '$2y$10$QgPBo7P7Szx1gw4GaYSlEumBD4UnrzknZTrzqLo5ywitai3700Sfa', 0xd59ad7b4624e74ece819de1a061cb588, 0, 0, 0, 'Fatima', 'Shahid', '+923364992195', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JyJA1EZSfc6bbgl3t7jE2Z7hDD2Wal22SgT0ESrnODULVyYIcr1Fc4wBQWTX', NULL, NULL),
(319, 'h.william@crecentech.com', '$2y$10$XhIW40l9cltbQNZkjaziM.cNBqVjMl5M3FeY0jg1SgL9dtHrlVSjq', 0x9070f0e574ac599bcc0f9b75eee874da, 0, 0, 0, 'Henry', 'William', '+13152150310', '', '5CG5243WSK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'deIerTHFlwi8vzw0dewogMCcvpRRNnQ0S4NpF2TaYYRqjGhH7hLRIBCxqhs156il', NULL, NULL),
(329, 'd.carbyan@crecentech.com', '$2y$10$tnRuZVHsPYk48FhxNgiKRunm.Ll.eBKYuE7Lfw0dNE/hnhml7AdBG', 0x01a1e33df6b89fc8c41e83b201e9ecf6, 0, 0, 0, 'Daniel', 'Carbyan', '+923010422432', 'http://app.staffviz.com/files/26', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SxkGsMssNfXoTWODpGRf1BpmOQ1jDSMp4c1swpRoJo92QDu8gnnP0TexJn7sshlp', NULL, '2023-06-01 16:16:08'),
(330, 'w.salter@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'e3EfmgRUVfZZ2Vy6T8duY6jdaXq2juin5xvRpPytOMODpuudqfFlFi7un9nP5yMK', NULL, NULL),
(331, 't.keen@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'qnvkH2i0ZLCyQTNN06jty2T4u3TY85saZFgxG18RvznfsWHSl0Ei5LJnFlq1RQn8', NULL, NULL),
(332, 'h.davidson@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BPDtYImxRHrydmQMTbzVidGHEJ8E55i2QGhok6BsMs4h3kMGpysc7OfkeC8G4YZQ', NULL, NULL),
(333, 'n.younas@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BmyQiTOa7zTlmDt0iuNSE4MkFheDqJKFoxtphLNo8CXWHKfzJ3T53WErXuzMcxl6', NULL, NULL),
(334, 'p.cameron@crecentech.com', '$2y$10$jRlzpYfBV9WXD5eYTI6goevAeeCZyKhFkmlIN8rK/qDN9VtcQJqgu', 0xf77e63e84a1113814e5d03d387b5e5f3, 0, 0, 0, 'Peter', 'Cameron', '+13152150310', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'xpoAHx7Kr5phzF6rOdl2Sa0RI9ic6hcJtnDv3gxVfJxEkR6pe6UJP93UMOGtvZLZ', NULL, NULL),
(335, 'j.edison@crecentech.com', '$2y$10$GkXUQZIh6n2SX6Jvscd51e7GO0BkU5g10TdnZ6mVCyd6SGG4/o.RO', 0x49edf7dea47a1299e4b70745813bb415, 0, 0, 0, 'Jack', 'Edison', '+923454021595', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MploqVhlpekSwR51NrXbwaYiuc4ribkPpSlwbuOKshMFftZfmWFJGCQSuYNugQH1', NULL, NULL),
(336, 'm.irfan@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2XaTKmAVsJTqoz828LMQ6YjSfPq4ML1m2zz6HinspU1umNMSpLccPCvSLvqsrs7S', NULL, NULL),
(337, 'j.hendrix@crecentech.com', '$2y$10$/Ay5K4afMqsGRZU99ckNQ.ScFa3YSPeBLsQQOXbUYvo5fUoAutagC', 0x189555b63221e803ed7c712b3f649a0d, 0, 0, 0, 'Jason', 'Hendrix', '+13152150310', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'R3k5kS4nfmTJJoG8IruqcU2EN1CCALbvzdecQ64hK9Dt1qd2C7bbYCJXgsPPpvwb', NULL, NULL),
(338, 'b.scott@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'n69xUE2yP6nM3nQmWUbDOhTxX78mRsuLbKOPhCKDyguFvcilgrsAWpRTw6Zuzd7V', NULL, NULL),
(339, 'j.nill@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pqDkYQG0gMNeDXHLdFWYCvTzJvLIRCvZNeFTWjoK7gdIWf5VDEYLlagCSfESGxYi', NULL, NULL),
(340, 'a.robinson@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KkUC7Hab3MsK46qiapKHWGCPb5oAIzviz71fR7jh634yQNVeNA62kVBMF9VhzE6D', NULL, NULL),
(341, 'j.walker@crecentech.com', '$2y$10$HXS2F7C7IJg.wju6VycsPOYVQLeIZfYpEED3Gu4P2W9SbqagzlI.6', 0x2925411e9a91b951ef21b8bdf4aa6850, 0, 0, 0, 'Jasmine', 'Walker', '+923027951344', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'XyplWdNcZCbk4u6MXSf15SjiBuwl6PULtahMooiFYAbFXqQ6sfVbNbMZQ5vu', NULL, NULL),
(342, 'b.marco@crecentech.com', '$2y$10$i7SsLI0dbBAjXzK2ddLvWe7tJ24vmaYk/x.mVtUP5qBtTYzQgO/kC', 0x00f48f8d775878046c02b094c25fa1cf, 0, 0, 0, 'Bill', 'Marco', '+923035542601', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CqJvUPXY6w1UPwjn73Gtnw71WQLzLCE73TruFPIBmnEbDxfI0rRq2WU47HxaA4Vr', NULL, NULL),
(343, 'a.qadir@crecentech.com', '$2y$10$t8eVoi9.vhgJhhLPf9U/m.s9hfUqwLXQ5vnVy6z2RkSd/hx/MoTmi', 0xfbf7e35f9a97362e290d188c4bd66b77, 0, 0, 0, 'Abdul', 'Qadir', '+923426718612', 'http://app.staffviz.com/files/39', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'qVM8aXMJopcNVfxoXJzk8vzzX9NywXKi6dnl9ftgfQ1lhMFN1pzDVOB7ANAH', '2023-06-19 14:50:02', '2023-06-19 14:50:02'),
(344, 'wofisoh779@akoption.com', '$2y$10$XLm95Uzjfw9K3wgBBaZzgei9H6dXwpcxVG/TAUQPgpM5d9gkB5kSO', 0x8fa07f4a63b7ab45effb023e2d4816c5, 0, 0, 0, 'Azan', 'Ghuman', '+923364992195', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pTHcHAbRjc7QajLkC9mdUs34iwZXaZozwB8e5g6HRVGFhiifNbxz5dYA22hq', NULL, NULL),
(345, 'b.petreson@crecentech.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lfe0iEcR4vLTpJisCInqecu4ocIgfi1mC2UTqRYq4JVdBSRIlQQgr0fCWhhQcsAZ', NULL, NULL),
(346, 'yapetab916@aaorsi.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'jGvzU8JGreH4HsEtCP1uEu3hxk7D6OkG5Lt1fxyXdOOBP9sHXSTNAv3PauvPR4sb', NULL, NULL),
(347, 'kesomih815@anwarb.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GEF5tV1P5a4mOIN3YkPjxE8PxzWpE1X2A7v9k66XiuyHLq9R9BSzoBkcSEV8FzHK', NULL, NULL),
(348, 'rikagol508@bodeem.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TW72Y5UA7nFU5oepLegBoiotnepEgmFdIBPlHrZ5IKKViFd1CDLshTpKqi6gRsj4', NULL, NULL),
(349, 'hamexew245@akoption.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2iugI8VO8SfjmbWuPfW1GNFHAyAjwZdYuzuh4CmFir7Q1MdAoexDxG1cA9ISjAeq', NULL, NULL),
(350, 'cifepow345@aramask.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '125RjLPfqJLaOXX4BxQwzEoJimCFkUsF1LpjU2eMzmIOVYWFJkldZSV1a9Thrhvq', NULL, NULL),
(351, 'lapip56552@aaorsi.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rnA0mnDzTVNz0523t14yJJMlDvipypQcIIykn4NoznVjMIuegxY5Vu1leGDVSDri', NULL, NULL),
(352, 'xadedi2126@anomgo.com', '$2y$10$6k5GKlMNdYNrsW5dmGBA6ualOAG77m87/LuVKfx8QLWZL0lvcrI0G', 0x7722747df331d6224e0d978605fba6dd, 0, 0, 0, 'Fatima', 'User', '+13364992195', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SRsN5zhNm4jJgDnahkV6dC7mOkR9LFlz3ZoCO8Ae7jks0WhhIx5zWdQwiV8g', NULL, NULL),
(353, 'danadi5254@anwarb.com', '$2y$10$rUgwlY626C8.vGZ18ehXcedMMmdajWXIbzb1iu0M42D1ogS.pZYRG', 0x6ba0cfa35a2f8e141205cab8fdf4ba48, 0, 0, 0, 'qwertyui', 'qwertyui', '+1343434343', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mzrxe5xudre5ERoJiUANkn1rN79erxoVcxZK43CCd5RTJEvw1PphLFMgWUAuGbt4', NULL, NULL),
(354, 'mihol80173@lukaat.com', '$2y$10$3CrrVKYD.LEpzNDHgPhLiO1B5MClgckuHAFuAQoo0IL9Q/oPWf6Lu', 0x24c24602fd01469b55c51b04a1103698, 0, 0, 0, 'Maryam', 'Rashid', '+92322639909', 'tth/alc_50/gallery/all/tyjcmOj4QMsaDeaaG8jrLX5Mo0ITH8gqAEhc2BgM.png', '5CG5400QC8', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '83hZuizFTnziirQsN5lDlQ80W4e16SsdxQOcJL7ZVpTiKH0bo9jXx0gDm2SS', NULL, NULL),
(355, 'xalaji1068@mahmul.com', '', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'M1nnWpq33qX6aqdsBrZUxDvoFiTiYigGSkeqjDiRyGZmwaYEU6itDTTlU6bi0Sr3', NULL, NULL),
(356, 'f.shahid@crecentech.com', '$2y$10$U8.76XRlwGhLGn8arMosUuBsrcbaskHQzwAJHD478QfudpODH/bnW', '', 0, 0, 0, NULL, NULL, NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'X4imVIOL991aQWTg8aQMieccdKn5yJrzMTAvFbOepOvISyaXDmliqeknwRdE', NULL, NULL),
(358, '6.ahmad@crecentech.com', '$2y$10$BihKr8mojjT9otCKzUGmG.z8cH7AuECC9PoF7DsiSuH.bSfUDq6HC', NULL, 1, 0, 0, '6.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 11:48:55', '2023-07-26 11:48:55'),
(359, '7.ahmad@crecentech.com', '$2y$10$spTxmyYIcq7x7DDD2Xx7oeouvhWzYmi9pBOpcVM5AO944U0KSOt5O', NULL, 1, 0, 0, '7.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 11:54:49', '2023-07-26 11:54:49'),
(360, '8.ahmad@crecentech.com', '$2y$10$pixTdkB5oizhk6KIz2GnreyBAKWrXAVhTj5e1MD3smdi8ZJsPx3vy', NULL, 1, 0, 0, '8.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:00:36', '2023-07-26 12:00:36'),
(361, '9.ahmad@crecentech.com', '$2y$10$WYiDU8z7HQ5zRNBNsc6DEu50NoVptTFNoUBA4jFgkeg1U3MVJA3GW', NULL, 1, 0, 0, '9.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:05:46', '2023-07-26 12:05:46'),
(362, '10.ahmad@crecentech.com', '$2y$10$/svMWb2pTRiNiF8./QGFOeuSiOIWiFSJHSlUwac1ioCA4mAtPk1WC', NULL, 1, 0, 0, '10.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:06:37', '2023-07-26 12:06:37'),
(363, '11.ahmad@crecentech.com', '$2y$10$rwIJ2c161YYkVfiwRsdpGOQ1LzMpUF/D2aysf2RYFxr3D59JuLBDC', NULL, 1, 0, 0, '11.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:08:17', '2023-07-26 12:08:17'),
(364, '12.ahmad@crecentech.com', '$2y$10$/Vt7mIsxZHs1yT69IrakJeD5uQNLYyqM2KMx.qV.Ju7MjQNGQ6/Iq', NULL, 1, 0, 0, '12.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:12:19', '2023-07-26 12:12:19'),
(365, '13.ahmad@crecentech.com', '$2y$10$edGoxAXON6U/KZTfpJiiSuxK9NNVqCBQr13fT7LoPY.E6.2fzvOM6', NULL, 0, 0, 0, '13.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:44:12', '2023-07-26 12:44:12'),
(366, '14.ahmad@crecentech.com', '$2y$10$XpMraP2hRcMB/.eRlcUoKuGgC5rpLjv0R1av26eb.GK4zTUxNkCR.', NULL, 0, 0, 0, '14.Adnan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-26 12:46:57', '2023-07-26 12:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `typicms_user_time_log`
--

CREATE TABLE `typicms_user_time_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `time_logs` text DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `exception` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_status` varchar(255) DEFAULT '0' COMMENT '0=pending\r\n1=sent\r\n2=fail'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `typicms_user_time_log`
--

INSERT INTO `typicms_user_time_log` (`id`, `user_id`, `company_id`, `time_logs`, `time_created`, `exception`, `created_at`, `updated_at`, `email_status`) VALUES
(1, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:42:06\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:43:49\",\"description\":\"TTH Daily Scrum | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 08:49:20', 'Unable to save data..', NULL, NULL, '0'),
(2, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:43:49\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:43:55\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 08:49:20', 'Unable to save data..', NULL, NULL, '0'),
(3, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:43:55\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:44:01\",\"description\":\"TTH Daily Scrum | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 08:49:20', 'Unable to save data..', NULL, NULL, '0'),
(4, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:44:01\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:44:02\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 08:49:20', 'Unable to save data..', NULL, NULL, '0'),
(5, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:44:02\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:44:23\",\"description\":\"Course Assignments - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 08:49:20', 'Unable to save data..', NULL, NULL, '0'),
(6, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:44:23\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:46:04\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 08:49:20', 'Unable to save data..', NULL, NULL, '0'),
(7, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:52:57\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:53:23\",\"description\":\"TTH Daily Scrum | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(8, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:53:23\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:53:30\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(9, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:53:30\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:56:22\",\"description\":\"General (StaffViz) | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(10, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:56:22\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:56:40\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(11, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:56:40\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:57:36\",\"description\":\"User Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(12, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:57:36\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:57:53\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(13, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"ShellExperienceHost.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:57:53\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:58:28\",\"description\":\"Network Connections\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(14, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:58:28\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:58:32\",\"description\":\"User Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(15, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:58:32\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:58:36\",\"description\":\"Networks-StaffViz (StaffViz) | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(16, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:58:36\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:58:45\",\"description\":\"User Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(17, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:58:45\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 08:59:47\",\"description\":\"Adnan, Azan, Faizan, +10 | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(18, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 08:59:47\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:00:24\",\"description\":\"Microsoft Teams Notification\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(19, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:00:24\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:00:25\",\"description\":\"Microsoft Teams | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(20, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:00:25\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:01:04\",\"description\":\"Adnan, Azan, Faizan, +10 | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(21, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:01:04\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:01:05\",\"description\":\"User Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:02:56', 'Unable to save data..', NULL, NULL, '0'),
(22, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:01:05\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:02:33\",\"description\":\"Azan and 3 others | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(23, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:02:33\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:03:03\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(24, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:03:03\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:03:07\",\"description\":\"Snapshots - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(25, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:03:07\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:04:09\",\"description\":\"Azan and 4 others | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(26, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:04:09\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:04:10\",\"description\":\"Tasks - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(27, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:04:10\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:04:16\",\"description\":\"Untitled - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(28, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:04:16\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:04:18\",\"description\":\"Tasks - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(29, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:04:18\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:04:59\",\"description\":\"Azan and 4 others | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(30, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:04:59\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:05:35\",\"description\":\"Add Project - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(31, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:05:35\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:06:17\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(32, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:06:17\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:07:12\",\"description\":\"User Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(33, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:07:12\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:08:43\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(34, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:08:43\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:08:44\",\"description\":\"Azan and 4 others | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(35, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:08:44\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:08:48\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(36, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:08:48\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:08:49\",\"description\":\"Project Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(37, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:08:49\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:08:54\",\"description\":\"Azan and 4 others | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(38, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:08:54\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:00\",\"description\":\"Snapshots - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(39, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:00\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:03\",\"description\":\"Adnan, Azan, Faizan, +10 | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(40, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:03\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:04\",\"description\":\"Project Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(41, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"WINWORD.EXE\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:04\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:09\",\"description\":\"Document1 - Word\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(42, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:09\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:10\",\"description\":\"Project Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(43, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"WINWORD.EXE\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:10\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:15\",\"description\":\"Document1 - Word\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(44, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:15\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:16\",\"description\":\"Company Settings - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(45, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"explorer.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:16\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:17\",\"description\":\"Downloads\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(46, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:17\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:19\",\"description\":\"Company Settings - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:30', 'Unable to save data..', NULL, NULL, '0'),
(47, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:19\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:30\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:33', 'Unable to save data..', NULL, NULL, '0'),
(48, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:30\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:45\",\"description\":\"Company Settings - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:33', 'Unable to save data..', NULL, NULL, '0'),
(49, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:45\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:46\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:10:33', 'Unable to save data..', NULL, NULL, '0'),
(50, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:46\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:10:29\",\"description\":\"Company Settings - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:12:11', 'Unable to save data..', NULL, NULL, '0'),
(51, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:10:29\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:11:36\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:12:11', 'Unable to save data..', NULL, NULL, '0'),
(52, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:15:01\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:15:35\",\"description\":\"User Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:16:39', 'Unable to save data..', NULL, NULL, '0'),
(53, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:15:35\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:15:58\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:16:39', 'Unable to save data..', NULL, NULL, '0'),
(54, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:15:58\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:16:39\",\"description\":\"Reports - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:27:29', 'Unable to save data..', NULL, NULL, '0'),
(55, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"Teams.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:16:39\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:17:10\",\"description\":\"Adnan, Azan, Faizan, +10 | Microsoft Teams\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:27:29', 'Unable to save data..', NULL, NULL, '0'),
(56, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:17:10\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:17:38\",\"description\":\"Idle & Manual Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:27:29', 'Unable to save data..', NULL, NULL, '0'),
(57, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:17:38\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:17:45\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:27:29', 'Unable to save data..', NULL, NULL, '0'),
(58, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"chrome.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:17:45\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:18:11\",\"description\":\"Idle & Manual Time Report - Google Chrome\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:27:29', 'Unable to save data..', NULL, NULL, '0'),
(59, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:18:11\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:19:20\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:27:29', 'Unable to save data..', NULL, NULL, '0'),
(60, 273, 50, '{\"formId\":\"web_app_tracking\",\"action\":\"update\",\"formData\":{\"app\":\"javaw.exe\",\"course_id\":4,\"start_time\":\"2023-05-31 09:09:45\",\"user_id\":\"273\",\"end_time\":\"2023-05-31 09:09:46\",\"description\":\"StaffViz\",\"id\":\"auto_increment\"},\"id\":\"auto_increment\",\"entity\":\"web_app_tracking\"}', '2023-05-31 09:52:42', 'Unable to save data..', NULL, NULL, '0'),
(61, 225, 40, '{\"formId\":\"users.version.update\",\"action\":\"update\",\"formData\":{\"user_id\":\"225\",\"client_app_version\":\"5.3.8 -QA\",\"id\":\"225\"},\"id\":\"225\",\"entity\":\"users\"}', '2023-07-20 08:04:46', 'Unable to save data..Unknown column \'user_id\' in \'field list\'', NULL, NULL, '0'),
(62, 0, 40, '{\"path\":\"?service.key=master.update\",\"data\":{\"data\":[{\"formId\":\"users\",\"action\":\"update\",\"formData\":{\"client_app_version\":\"5.3.8 -QA\",\"id\":\"225\"},\"id\":\"225\",\"entity\":\"users\"}]},\"method\":\"POST\"}', '2023-07-20 08:13:12', NULL, NULL, NULL, '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `typicms_app_fd_api_service`
--
ALTER TABLE `typicms_app_fd_api_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_blocks`
--
ALTER TABLE `typicms_blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_companies`
--
ALTER TABLE `typicms_companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instance_id` (`instance_id`);

--
-- Indexes for table `typicms_companies_currency`
--
ALTER TABLE `typicms_companies_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_companies_industry`
--
ALTER TABLE `typicms_companies_industry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_companies_timezone`
--
ALTER TABLE `typicms_companies_timezone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_companies_users`
--
ALTER TABLE `typicms_companies_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `typicms_contacts`
--
ALTER TABLE `typicms_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_events`
--
ALTER TABLE `typicms_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_events_image_id_foreign` (`image_id`);

--
-- Indexes for table `typicms_failed_jobs`
--
ALTER TABLE `typicms_failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `typicms_files`
--
ALTER TABLE `typicms_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_files_folder_id_foreign` (`folder_id`);

--
-- Indexes for table `typicms_history`
--
ALTER TABLE `typicms_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_menulinks`
--
ALTER TABLE `typicms_menulinks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_menulinks_menu_id_foreign` (`menu_id`),
  ADD KEY `typicms_menulinks_page_id_foreign` (`page_id`),
  ADD KEY `typicms_menulinks_section_id_foreign` (`section_id`),
  ADD KEY `typicms_menulinks_parent_id_foreign` (`parent_id`),
  ADD KEY `typicms_menulinks_image_id_foreign` (`image_id`);

--
-- Indexes for table `typicms_menus`
--
ALTER TABLE `typicms_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_menus_image_id_foreign` (`image_id`);

--
-- Indexes for table `typicms_migrations`
--
ALTER TABLE `typicms_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_model_has_files`
--
ALTER TABLE `typicms_model_has_files`
  ADD PRIMARY KEY (`file_id`,`model_id`,`model_type`),
  ADD KEY `file` (`model_type`,`model_id`);

--
-- Indexes for table `typicms_model_has_permissions`
--
ALTER TABLE `typicms_model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `typicms_model_has_roles`
--
ALTER TABLE `typicms_model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `typicms_model_has_terms`
--
ALTER TABLE `typicms_model_has_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_model_has_terms_term_id_foreign` (`term_id`);

--
-- Indexes for table `typicms_news`
--
ALTER TABLE `typicms_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_news_image_id_foreign` (`image_id`);

--
-- Indexes for table `typicms_notification_companies`
--
ALTER TABLE `typicms_notification_companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_pages`
--
ALTER TABLE `typicms_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_pages_image_id_foreign` (`image_id`),
  ADD KEY `typicms_pages_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `typicms_page_sections`
--
ALTER TABLE `typicms_page_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_page_sections_page_id_foreign` (`page_id`),
  ADD KEY `typicms_page_sections_image_id_foreign` (`image_id`);

--
-- Indexes for table `typicms_password_resets`
--
ALTER TABLE `typicms_password_resets`
  ADD KEY `typicms_password_resets_email_index` (`email`),
  ADD KEY `typicms_password_resets_token_index` (`token`);

--
-- Indexes for table `typicms_password_reset_tokens`
--
ALTER TABLE `typicms_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `typicms_permissions`
--
ALTER TABLE `typicms_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `typicms_permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `typicms_personal_access_tokens`
--
ALTER TABLE `typicms_personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `typicms_personal_access_tokens_token_unique` (`token`),
  ADD KEY `typicms_personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `typicms_projects`
--
ALTER TABLE `typicms_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_projects_category_id_foreign` (`category_id`),
  ADD KEY `typicms_projects_image_id_foreign` (`image_id`);

--
-- Indexes for table `typicms_project_categories`
--
ALTER TABLE `typicms_project_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_project_categories_image_id_foreign` (`image_id`);

--
-- Indexes for table `typicms_registrations`
--
ALTER TABLE `typicms_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_registrations_event_id_foreign` (`event_id`),
  ADD KEY `typicms_registrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `typicms_roles`
--
ALTER TABLE `typicms_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `typicms_roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `typicms_role_has_permissions`
--
ALTER TABLE `typicms_role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `typicms_role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `typicms_settings`
--
ALTER TABLE `typicms_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_taggables`
--
ALTER TABLE `typicms_taggables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_taggables_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `typicms_tags`
--
ALTER TABLE `typicms_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_taxonomies`
--
ALTER TABLE `typicms_taxonomies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_terms`
--
ALTER TABLE `typicms_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typicms_terms_taxonomy_id_foreign` (`taxonomy_id`);

--
-- Indexes for table `typicms_translations`
--
ALTER TABLE `typicms_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typicms_users`
--
ALTER TABLE `typicms_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `typicms_users_email_unique` (`email`),
  ADD UNIQUE KEY `typicms_users_api_token_unique` (`api_token`);

--
-- Indexes for table `typicms_user_time_log`
--
ALTER TABLE `typicms_user_time_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `typicms_blocks`
--
ALTER TABLE `typicms_blocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_companies`
--
ALTER TABLE `typicms_companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `typicms_companies_currency`
--
ALTER TABLE `typicms_companies_currency`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `typicms_companies_industry`
--
ALTER TABLE `typicms_companies_industry`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `typicms_companies_timezone`
--
ALTER TABLE `typicms_companies_timezone`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=593;

--
-- AUTO_INCREMENT for table `typicms_companies_users`
--
ALTER TABLE `typicms_companies_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT for table `typicms_contacts`
--
ALTER TABLE `typicms_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `typicms_events`
--
ALTER TABLE `typicms_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_failed_jobs`
--
ALTER TABLE `typicms_failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_files`
--
ALTER TABLE `typicms_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `typicms_history`
--
ALTER TABLE `typicms_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `typicms_menulinks`
--
ALTER TABLE `typicms_menulinks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `typicms_menus`
--
ALTER TABLE `typicms_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `typicms_migrations`
--
ALTER TABLE `typicms_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `typicms_model_has_terms`
--
ALTER TABLE `typicms_model_has_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_news`
--
ALTER TABLE `typicms_news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_notification_companies`
--
ALTER TABLE `typicms_notification_companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_pages`
--
ALTER TABLE `typicms_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `typicms_page_sections`
--
ALTER TABLE `typicms_page_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_permissions`
--
ALTER TABLE `typicms_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `typicms_personal_access_tokens`
--
ALTER TABLE `typicms_personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `typicms_projects`
--
ALTER TABLE `typicms_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_project_categories`
--
ALTER TABLE `typicms_project_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `typicms_registrations`
--
ALTER TABLE `typicms_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_roles`
--
ALTER TABLE `typicms_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `typicms_settings`
--
ALTER TABLE `typicms_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `typicms_taggables`
--
ALTER TABLE `typicms_taggables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_tags`
--
ALTER TABLE `typicms_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_taxonomies`
--
ALTER TABLE `typicms_taxonomies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `typicms_terms`
--
ALTER TABLE `typicms_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typicms_translations`
--
ALTER TABLE `typicms_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `typicms_users`
--
ALTER TABLE `typicms_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

--
-- AUTO_INCREMENT for table `typicms_user_time_log`
--
ALTER TABLE `typicms_user_time_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `typicms_events`
--
ALTER TABLE `typicms_events`
  ADD CONSTRAINT `typicms_events_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `typicms_files`
--
ALTER TABLE `typicms_files`
  ADD CONSTRAINT `typicms_files_folder_id_foreign` FOREIGN KEY (`folder_id`) REFERENCES `typicms_files` (`id`);

--
-- Constraints for table `typicms_menulinks`
--
ALTER TABLE `typicms_menulinks`
  ADD CONSTRAINT `typicms_menulinks_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `typicms_menulinks_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `typicms_menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typicms_menulinks_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `typicms_pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typicms_menulinks_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `typicms_menulinks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typicms_menulinks_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `typicms_page_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_menus`
--
ALTER TABLE `typicms_menus`
  ADD CONSTRAINT `typicms_menus_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `typicms_model_has_files`
--
ALTER TABLE `typicms_model_has_files`
  ADD CONSTRAINT `typicms_model_has_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `typicms_files` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_model_has_permissions`
--
ALTER TABLE `typicms_model_has_permissions`
  ADD CONSTRAINT `typicms_model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `typicms_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_model_has_roles`
--
ALTER TABLE `typicms_model_has_roles`
  ADD CONSTRAINT `typicms_model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `typicms_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_model_has_terms`
--
ALTER TABLE `typicms_model_has_terms`
  ADD CONSTRAINT `typicms_model_has_terms_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `typicms_terms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_news`
--
ALTER TABLE `typicms_news`
  ADD CONSTRAINT `typicms_news_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `typicms_pages`
--
ALTER TABLE `typicms_pages`
  ADD CONSTRAINT `typicms_pages_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `typicms_pages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `typicms_pages` (`id`);

--
-- Constraints for table `typicms_page_sections`
--
ALTER TABLE `typicms_page_sections`
  ADD CONSTRAINT `typicms_page_sections_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `typicms_page_sections_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `typicms_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_projects`
--
ALTER TABLE `typicms_projects`
  ADD CONSTRAINT `typicms_projects_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `typicms_project_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typicms_projects_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `typicms_project_categories`
--
ALTER TABLE `typicms_project_categories`
  ADD CONSTRAINT `typicms_project_categories_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `typicms_files` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `typicms_registrations`
--
ALTER TABLE `typicms_registrations`
  ADD CONSTRAINT `typicms_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `typicms_events` (`id`),
  ADD CONSTRAINT `typicms_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `typicms_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `typicms_role_has_permissions`
--
ALTER TABLE `typicms_role_has_permissions`
  ADD CONSTRAINT `typicms_role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `typicms_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typicms_role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `typicms_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_taggables`
--
ALTER TABLE `typicms_taggables`
  ADD CONSTRAINT `typicms_taggables_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `typicms_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `typicms_terms`
--
ALTER TABLE `typicms_terms`
  ADD CONSTRAINT `typicms_terms_taxonomy_id_foreign` FOREIGN KEY (`taxonomy_id`) REFERENCES `typicms_taxonomies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
