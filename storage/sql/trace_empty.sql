-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2020 at 05:29 AM
-- Server version: 5.7.26-log
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trace_empty`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_05_02_020615_create_user_groups_table', 1),
(2, '2017_05_01_015107_create_groups_table', 1),
(3, '2017_05_02_000000_create_users_table', 1),
(4, '2017_05_02_020810_create_user_rights_table', 1),
(5, '2017_05_02_020912_create_user_group_rights_table', 1),
(6, '2017_05_02_022015_create_settings_table', 1),
(7, '2017_05_02_052657_create_user_logs_table', 1),
(8, '2017_05_03_050415_create_group_members_table', 1),
(9, '2017_05_03_071011_create_companies_table', 1),
(10, '2017_05_03_075416_create_events_table', 1),
(11, '2017_05_03_102248_create_document_types_table', 1),
(12, '2017_05_03_112343_create_event_attachments_table', 1),
(13, '2017_05_04_065130_create_documents_table', 1),
(14, '2017_05_04_075743_create_document_routings_table', 1),
(15, '2017_05_08_015455_create_comments_table', 1),
(16, '2017_05_08_033025_create_actions_table', 1),
(17, '2017_05_08_064420_create_document_attachments_table', 1),
(18, '2017_05_09_013921_create_event_seens_table', 1),
(19, '2017_05_09_032050_create_d_comment_seens_table', 1),
(20, '2017_05_09_032308_create_e_comment_seens_table', 1),
(21, '2017_05_10_031604_create_meetings_table', 1),
(22, '2017_05_10_035954_create_participants_table', 1),
(23, '2017_06_08_113502_create_action_dones_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_actions`
--

CREATE TABLE `t_actions` (
  `a_id` int(10) UNSIGNED NOT NULL,
  `a_action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `a_number` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_actions`
--

INSERT INTO `t_actions` (`a_id`, `a_action`, `a_number`, `created_at`, `updated_at`) VALUES
(1, 'Please RUSH', 1, '2016-08-24 02:00:00', '2018-06-05 18:30:09'),
(2, 'Please Attend', 2, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(3, 'Please draft reply/memo/letter', 3, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(4, 'Please acknowledge receipt', 4, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(5, 'Please discuss with me', 5, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(6, 'For your information/study/reference', 12, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(7, 'For your comments', 13, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(8, 'For your initial/signature', 14, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(9, 'Please file', 11, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(10, 'Please follow up', 7, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(11, 'Please act on this', 8, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(12, 'Please give me feedback', 10, '2016-08-24 02:00:00', '0000-00-00 00:00:00'),
(13, 'Please calendar', 6, '2018-01-16 02:18:05', '2018-01-16 02:18:05'),
(14, 'Please post', 9, '2018-01-16 02:18:05', '2018-01-16 02:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `t_action_done`
--

CREATE TABLE `t_action_done` (
  `ad_id` int(10) UNSIGNED NOT NULL,
  `comm_id` int(10) UNSIGNED NOT NULL,
  `d_id` bigint(20) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `ad_seen` tinyint(4) NOT NULL DEFAULT '0',
  `ad_rd` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_comments`
--

CREATE TABLE `t_comments` (
  `comm_id` int(10) UNSIGNED NOT NULL,
  `comm_document` tinyint(4) NOT NULL DEFAULT '0',
  `comm_event` tinyint(4) NOT NULL DEFAULT '0',
  `comm_reference` int(11) NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `comm_text` text COLLATE utf8mb4_unicode_ci,
  `comm_rd` tinyint(4) NOT NULL DEFAULT '0',
  `comm_for_rd` tinyint(4) NOT NULL DEFAULT '0',
  `comm_reply` bigint(20) NOT NULL DEFAULT '0',
  `comm_tag` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_companies`
--

CREATE TABLE `t_companies` (
  `c_id` int(10) UNSIGNED NOT NULL,
  `c_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_acronym` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `u_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_dcomment_seen`
--

CREATE TABLE `t_dcomment_seen` (
  `dcs_id` int(10) UNSIGNED NOT NULL,
  `d_id` bigint(20) UNSIGNED NOT NULL,
  `comm_id` int(10) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `dcs_seen` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_documents`
--

CREATE TABLE `t_documents` (
  `d_id` bigint(20) UNSIGNED NOT NULL,
  `d_status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `d_subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dt_id` int(10) UNSIGNED NOT NULL,
  `d_documentdate` date NOT NULL,
  `d_datereceived` date DEFAULT NULL,
  `d_datesent` date DEFAULT NULL,
  `d_sender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_addressee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_id` int(10) UNSIGNED NOT NULL,
  `d_keywords` text COLLATE utf8mb4_unicode_ci,
  `d_remarks` text COLLATE utf8mb4_unicode_ci,
  `d_routingslip` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `d_incomingreference` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_routingthru` int(11) NOT NULL DEFAULT '0',
  `d_routingfrom` int(11) NOT NULL DEFAULT '0',
  `d_actions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_datetimerouted` datetime DEFAULT NULL,
  `d_istrack` tinyint(4) NOT NULL DEFAULT '0',
  `d_iscompleted` tinyint(4) NOT NULL DEFAULT '0',
  `d_datecompleted` date DEFAULT NULL,
  `d_encoded_by` int(11) DEFAULT NULL,
  `d_group_encoded` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_document_attachments`
--

CREATE TABLE `t_document_attachments` (
  `da_id` int(10) UNSIGNED NOT NULL,
  `d_id` bigint(20) UNSIGNED NOT NULL,
  `da_file` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_document_routings`
--

CREATE TABLE `t_document_routings` (
  `dr_id` int(10) UNSIGNED NOT NULL,
  `d_id` bigint(20) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `dr_seen` tinyint(4) NOT NULL DEFAULT '0',
  `dr_completed` tinyint(4) NOT NULL DEFAULT '0',
  `dr_date` date DEFAULT NULL,
  `dr_status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_document_types`
--

CREATE TABLE `t_document_types` (
  `dt_id` int(10) UNSIGNED NOT NULL,
  `dt_type` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_document_types`
--

INSERT INTO `t_document_types` (`dt_id`, `dt_type`, `created_at`, `updated_at`) VALUES
(1, 'Letter', '2016-08-16 00:00:00', '2018-06-05 18:27:34'),
(2, 'Announcement', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(3, 'Notice of Meeting', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(4, 'Memorandum', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(5, 'MOA', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(6, 'Administrative Order', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(7, 'Special Order', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(8, 'Minutes of Meeting', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(9, 'Publication', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(10, 'Resolution', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(11, 'Schedule', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(12, 'Fax Message', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(13, 'E-mail Message', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(14, 'Receipt', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(15, 'Executive Order', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(16, 'Primer', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(17, 'Form', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(18, 'Evaluation Form', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(19, 'Speech', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(20, 'Solicitation', '2016-08-16 00:00:00', '0000-00-00 00:00:00'),
(21, 'Report', '2016-08-16 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `t_ecomment_seen`
--

CREATE TABLE `t_ecomment_seen` (
  `ecs_id` int(10) UNSIGNED NOT NULL,
  `e_id` int(10) UNSIGNED NOT NULL,
  `comm_id` int(10) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `ecs_seen` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_events`
--

CREATE TABLE `t_events` (
  `e_id` int(10) UNSIGNED NOT NULL,
  `e_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `e_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `e_start_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `e_start_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `e_end_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `e_end_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `e_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `e_staff` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `e_venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `e_live` tinyint(4) NOT NULL DEFAULT '0',
  `u_id` int(10) UNSIGNED NOT NULL,
  `e_confirm` tinyint(4) NOT NULL DEFAULT '0',
  `e_online` tinyint(4) NOT NULL DEFAULT '0',
  `e_zoom` tinyint(4) NOT NULL DEFAULT '0',
  `zs_id` int(11) DEFAULT NULL,
  `e_zoom_pw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `e_zoom_approved` tinyint(4) NOT NULL DEFAULT '0',
  `e_zoom_date` datetime DEFAULT NULL,
  `e_zoom_link` text COLLATE utf8mb4_unicode_ci,
  `e_zoom_mtgid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `e_zoom_reason` text COLLATE utf8mb4_unicode_ci,
  `e_zoom_seen` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_event_attachments`
--

CREATE TABLE `t_event_attachments` (
  `ea_id` int(10) UNSIGNED NOT NULL,
  `e_id` int(10) UNSIGNED NOT NULL,
  `ea_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_event_seen`
--

CREATE TABLE `t_event_seen` (
  `es_id` int(10) UNSIGNED NOT NULL,
  `e_id` int(10) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `es_seen` tinyint(4) NOT NULL DEFAULT '0',
  `es_invited` tinyint(4) NOT NULL DEFAULT '0',
  `e_confirm` tinyint(4) NOT NULL DEFAULT '0',
  `es_confirmed` tinyint(4) NOT NULL DEFAULT '99',
  `es_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_groups`
--

CREATE TABLE `t_groups` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_groups`
--

INSERT INTO `t_groups` (`group_id`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'Office of the Regional Director', '2016-08-15 07:56:03', NULL),
(2, 'Office of the Assistant Regional Director for TO', '2016-08-15 07:56:03', '2020-04-08 06:27:41'),
(3, 'Office of the Assistant Regional Director for FAS', '2016-08-15 07:56:03', '2020-04-08 06:27:32'),
(4, 'FAS - Accounting Unit', '2016-08-15 07:56:03', '2020-04-08 06:32:37'),
(5, 'FAS - Budget Unit', '2016-08-15 07:56:03', NULL),
(6, 'FAS - Cashier Unit', '2016-08-15 07:56:03', '2020-04-08 07:11:50'),
(7, 'FAS - Property Unit', '2016-08-15 07:56:03', '2020-04-08 06:33:02'),
(8, 'FAS - HR Unit', '2016-08-15 07:56:03', NULL),
(9, 'TO - MIS Unit', '2016-08-15 07:56:03', '2020-04-08 06:34:28'),
(10, 'TO - PARCU', '2016-08-15 07:56:03', '2020-04-08 06:35:03'),
(11, 'TO - Planning Unit', '2016-08-15 07:56:03', '2020-04-08 06:42:47'),
(12, 'TO - Food Safety Unit', '2016-08-15 07:56:03', '2019-10-01 09:01:15'),
(13, 'TO - RSTL', '2016-08-15 07:56:03', '2020-04-08 06:40:01'),
(14, 'TO - RML', '2016-08-15 07:56:03', '2020-04-08 06:38:24'),
(15, 'TO - RPMO', '2016-08-15 07:56:03', '2020-03-25 11:47:10'),
(16, 'TO - Scholarship Unit', '2016-08-15 07:56:03', '2020-04-08 06:39:03'),
(17, 'TO - Special Project (RxBox)', '2016-08-15 07:56:03', '2020-04-08 06:41:04'),
(18, 'PSTC - Cavite', '2016-08-15 07:56:03', NULL),
(19, 'PSTC - Laguna', '2016-08-15 07:56:03', NULL),
(20, 'PSTC - Batangas', '2016-08-15 07:56:03', NULL),
(21, 'PSTC - Rizal', '2016-08-15 07:56:03', NULL),
(22, 'PSTC - Quezon', '2016-08-15 07:56:03', '2019-10-01 09:09:31'),
(23, 'Permanent Staff', '2017-01-12 01:33:05', NULL),
(24, 'Provincial Directors', '2017-03-09 07:00:13', NULL),
(25, 'TO - DRRM', '2020-04-08 06:42:00', '2020-04-08 06:42:14'),
(26, 'TO - Special/Other Projects', '2020-04-08 06:42:28', '2020-04-08 07:25:31');

-- --------------------------------------------------------

--
-- Table structure for table `t_group_members`
--

CREATE TABLE `t_group_members` (
  `gm_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_meetings`
--

CREATE TABLE `t_meetings` (
  `m_id` int(10) UNSIGNED NOT NULL,
  `m_startdate` date DEFAULT NULL,
  `m_enddate` date DEFAULT NULL,
  `m_starttime` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_endtime` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_tstartdate` date DEFAULT NULL,
  `m_tenddate` date DEFAULT NULL,
  `m_tstarttime` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_tendtime` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_purpose` text COLLATE utf8mb4_unicode_ci,
  `m_destination` text COLLATE utf8mb4_unicode_ci,
  `m_others` text COLLATE utf8mb4_unicode_ci,
  `m_encodedby` int(10) UNSIGNED NOT NULL,
  `m_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `m_reason` text COLLATE utf8mb4_unicode_ci,
  `m_datechecked` date DEFAULT NULL,
  `m_postponedby` int(11) DEFAULT '0',
  `m_stat` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_participants`
--

CREATE TABLE `t_participants` (
  `p_id` int(10) UNSIGNED NOT NULL,
  `m_id` int(10) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `p_ord` tinyint(4) NOT NULL DEFAULT '0',
  `p_seen` tinyint(4) NOT NULL DEFAULT '0',
  `p_notif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_settings`
--

CREATE TABLE `t_settings` (
  `s_id` int(10) UNSIGNED NOT NULL,
  `s_sysname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `s_abbr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `s_pending_days` int(11) NOT NULL,
  `s_background` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_settings`
--

INSERT INTO `t_settings` (`s_id`, `s_sysname`, `s_abbr`, `s_pending_days`, `s_background`, `created_at`, `updated_at`) VALUES
(1, 'Tracking, Retrieval, Archiving of Communications for Efficiency (TRACE)', 'TRACE', 9, 'images/system/background.jpg', '2017-05-02 17:00:18', '2018-06-05 18:19:43');

-- --------------------------------------------------------

--
-- Table structure for table `t_ug_rights`
--

CREATE TABLE `t_ug_rights` (
  `ugr_id` int(10) UNSIGNED NOT NULL,
  `ug_id` int(10) UNSIGNED NOT NULL,
  `ur_id` int(10) UNSIGNED NOT NULL,
  `ugr_view` tinyint(4) NOT NULL,
  `ugr_add` tinyint(4) NOT NULL,
  `ugr_edit` tinyint(4) NOT NULL,
  `ugr_delete` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_ug_rights`
--

INSERT INTO `t_ug_rights` (`ugr_id`, `ug_id`, `ur_id`, `ugr_view`, `ugr_add`, `ugr_edit`, `ugr_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 0, 0, '2017-05-03 01:00:00', '2017-05-08 00:52:00'),
(2, 1, 2, 1, 1, 1, 0, '2017-05-03 01:00:00', '2017-05-02 18:28:56'),
(3, 1, 3, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(4, 1, 4, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(5, 1, 5, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(6, 1, 6, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:29:05'),
(7, 1, 7, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:29:10'),
(8, 1, 8, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:29:16'),
(9, 1, 9, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(10, 1, 10, 1, 1, 1, 0, '2017-05-03 01:00:00', '2017-05-02 18:29:48'),
(11, 1, 11, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:29:53'),
(12, 1, 12, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:29:58'),
(13, 1, 13, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:30:01'),
(14, 2, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '2017-05-02 18:30:12'),
(15, 2, 2, 1, 1, 1, 0, '2017-05-03 01:00:00', '2017-05-02 18:30:16'),
(16, 2, 3, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-09 22:45:43'),
(17, 2, 4, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(18, 2, 5, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(19, 2, 6, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:30:24'),
(20, 2, 7, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:30:28'),
(21, 2, 8, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:30:31'),
(22, 2, 9, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(23, 2, 10, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(24, 2, 11, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:31:37'),
(25, 2, 12, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:31:33'),
(26, 2, 13, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:31:17'),
(27, 3, 1, 1, 1, 1, 0, '2017-05-03 01:00:00', '2017-05-02 18:31:49'),
(28, 3, 2, 1, 1, 1, 0, '2017-05-03 01:00:00', '2017-05-02 18:31:54'),
(29, 3, 3, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(30, 3, 4, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(31, 3, 5, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(32, 3, 6, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:31:59'),
(33, 3, 7, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:04'),
(34, 3, 8, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:08'),
(35, 3, 9, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(36, 3, 10, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:15'),
(37, 3, 11, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:19'),
(38, 3, 12, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:22'),
(39, 3, 13, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:25'),
(40, 4, 1, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:32'),
(41, 4, 2, 1, 1, 1, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:37'),
(42, 4, 3, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-09 22:45:55'),
(43, 4, 4, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(44, 4, 5, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(45, 4, 6, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:43'),
(46, 4, 7, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:49'),
(47, 4, 8, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:52'),
(48, 4, 9, 1, 1, 1, 1, '2017-05-03 01:00:00', '0000-00-00 00:00:00'),
(49, 4, 10, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:32:57'),
(50, 4, 11, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:33:01'),
(51, 4, 12, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:33:05'),
(52, 4, 13, 1, 0, 0, 0, '2017-05-03 01:00:00', '2017-05-02 18:33:12'),
(53, 5, 1, 1, 1, 1, 0, '2020-03-18 02:30:34', NULL),
(54, 5, 2, 1, 1, 1, 0, '2020-03-18 02:30:34', NULL),
(55, 5, 3, 1, 0, 0, 0, '2020-03-18 02:30:34', NULL),
(56, 5, 4, 1, 1, 1, 1, '2020-03-18 02:30:34', NULL),
(57, 5, 5, 1, 1, 1, 1, '2020-03-18 02:30:34', NULL),
(58, 5, 6, 0, 0, 0, 0, '2020-03-18 02:30:34', NULL),
(59, 5, 7, 0, 0, 0, 0, '2020-03-18 02:30:34', NULL),
(60, 5, 8, 0, 0, 0, 0, '2020-03-18 02:30:34', NULL),
(61, 5, 9, 1, 1, 1, 0, '2020-03-18 02:30:34', NULL),
(62, 5, 10, 1, 0, 0, 0, '2020-03-18 02:30:34', NULL),
(63, 5, 11, 1, 0, 0, 0, '2020-03-18 02:30:34', NULL),
(64, 5, 12, 1, 0, 0, 0, '2020-03-18 02:30:34', NULL),
(65, 5, 13, 0, 0, 0, 0, '2020-03-18 02:30:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `u_id` int(10) UNSIGNED NOT NULL,
  `u_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_fname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_mname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_lname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ug_id` int(10) UNSIGNED NOT NULL DEFAULT '4',
  `group_id` int(10) UNSIGNED NOT NULL,
  `u_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_picture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upload/profile/no-user-photo.png',
  `u_active` tinyint(4) NOT NULL DEFAULT '0',
  `u_administrator` tinyint(4) NOT NULL DEFAULT '0',
  `u_head` tinyint(4) NOT NULL DEFAULT '0',
  `u_zoom_mgr` tinyint(4) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`u_id`, `u_username`, `u_email`, `u_password`, `u_fname`, `u_mname`, `u_lname`, `u_mobile`, `ug_id`, `group_id`, `u_position`, `u_picture`, `u_active`, `u_administrator`, `u_head`, `u_zoom_mgr`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$qcQLeDv9vXY.QFo.hLJ3MOLTjuOybjI8L.sFF6gHbLg.8YlbgzkC2', 'Admin', 'Is', 'Trator', '639471372454', 4, 1, 'Position', 'upload/profile/no-user-photo.png', 1, 1, 0, 0, NULL, '2020-06-16 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_user_groups`
--

CREATE TABLE `t_user_groups` (
  `ug_id` int(10) UNSIGNED NOT NULL,
  `ug_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_user_groups`
--

INSERT INTO `t_user_groups` (`ug_id`, `ug_name`, `created_at`, `updated_at`) VALUES
(1, 'Regional Director', '2017-05-02 17:00:18', NULL),
(2, 'Division Head / Unit Chief', '2017-05-02 17:00:18', NULL),
(3, 'Document Controller', '2017-05-02 17:00:18', NULL),
(4, 'Ordinary User', '2017-05-02 17:00:18', NULL),
(5, 'Temporary DC', '2020-03-18 02:28:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_user_logs`
--

CREATE TABLE `t_user_logs` (
  `ul_id` int(10) UNSIGNED NOT NULL,
  `u_id` int(10) UNSIGNED NOT NULL,
  `ul_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ul_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ul_session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_user_rights`
--

CREATE TABLE `t_user_rights` (
  `ur_id` int(10) UNSIGNED NOT NULL,
  `ur_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_user_rights`
--

INSERT INTO `t_user_rights` (`ur_id`, `ur_name`, `created_at`, `updated_at`) VALUES
(1, 'Incoming Documents', '2017-05-03 01:00:00', NULL),
(2, 'Outgoing Documents', '2017-05-03 01:00:00', NULL),
(3, 'RD\'s Calendar', '2017-05-03 01:00:00', NULL),
(4, 'Meetings', '2017-05-03 01:00:00', NULL),
(5, 'Events', '2017-05-03 01:00:00', NULL),
(6, 'Document Statistics', '2017-05-03 01:00:00', NULL),
(7, 'User Statistics', '2017-05-03 01:00:00', NULL),
(8, 'Unit Statistics', '2017-05-03 01:00:00', NULL),
(9, 'Company Information', '2017-05-03 01:00:00', NULL),
(10, 'Groups', '2017-05-03 01:00:00', NULL),
(11, 'Accounts', '2017-05-03 01:00:00', NULL),
(12, 'System Settings', '2017-05-03 01:00:00', NULL),
(13, 'User Groups', '2017-05-03 01:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_zoom_settings`
--

CREATE TABLE `t_zoom_settings` (
  `zs_id` int(10) UNSIGNED NOT NULL,
  `zs_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zs_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zs_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_actions`
--
ALTER TABLE `t_actions`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `t_action_done`
--
ALTER TABLE `t_action_done`
  ADD PRIMARY KEY (`ad_id`),
  ADD KEY `t_action_done_comm_id_foreign` (`comm_id`),
  ADD KEY `t_action_done_d_id_foreign` (`d_id`),
  ADD KEY `t_action_done_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_comments`
--
ALTER TABLE `t_comments`
  ADD PRIMARY KEY (`comm_id`),
  ADD KEY `t_comments_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_companies`
--
ALTER TABLE `t_companies`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `t_dcomment_seen`
--
ALTER TABLE `t_dcomment_seen`
  ADD PRIMARY KEY (`dcs_id`),
  ADD KEY `t_dcomment_seen_d_id_foreign` (`d_id`),
  ADD KEY `t_dcomment_seen_comm_id_foreign` (`comm_id`),
  ADD KEY `t_dcomment_seen_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_documents`
--
ALTER TABLE `t_documents`
  ADD PRIMARY KEY (`d_id`),
  ADD KEY `t_documents_dt_id_foreign` (`dt_id`),
  ADD KEY `t_documents_c_id_foreign` (`c_id`);

--
-- Indexes for table `t_document_attachments`
--
ALTER TABLE `t_document_attachments`
  ADD PRIMARY KEY (`da_id`),
  ADD KEY `t_document_attachments_d_id_foreign` (`d_id`);

--
-- Indexes for table `t_document_routings`
--
ALTER TABLE `t_document_routings`
  ADD PRIMARY KEY (`dr_id`),
  ADD KEY `t_document_routings_d_id_foreign` (`d_id`),
  ADD KEY `t_document_routings_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_document_types`
--
ALTER TABLE `t_document_types`
  ADD PRIMARY KEY (`dt_id`);

--
-- Indexes for table `t_ecomment_seen`
--
ALTER TABLE `t_ecomment_seen`
  ADD PRIMARY KEY (`ecs_id`),
  ADD KEY `t_ecomment_seen_e_id_foreign` (`e_id`),
  ADD KEY `t_ecomment_seen_comm_id_foreign` (`comm_id`),
  ADD KEY `t_ecomment_seen_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_events`
--
ALTER TABLE `t_events`
  ADD PRIMARY KEY (`e_id`),
  ADD KEY `t_events_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_event_attachments`
--
ALTER TABLE `t_event_attachments`
  ADD PRIMARY KEY (`ea_id`),
  ADD KEY `t_event_attachments_e_id_foreign` (`e_id`);

--
-- Indexes for table `t_event_seen`
--
ALTER TABLE `t_event_seen`
  ADD PRIMARY KEY (`es_id`),
  ADD KEY `t_event_seen_e_id_foreign` (`e_id`),
  ADD KEY `t_event_seen_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_groups`
--
ALTER TABLE `t_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `t_group_members`
--
ALTER TABLE `t_group_members`
  ADD PRIMARY KEY (`gm_id`),
  ADD KEY `t_group_members_group_id_foreign` (`group_id`),
  ADD KEY `t_group_members_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_meetings`
--
ALTER TABLE `t_meetings`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `t_meetings_m_encodedby_foreign` (`m_encodedby`);

--
-- Indexes for table `t_participants`
--
ALTER TABLE `t_participants`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `t_participants_m_id_foreign` (`m_id`),
  ADD KEY `t_participants_u_id_foreign` (`u_id`);

--
-- Indexes for table `t_settings`
--
ALTER TABLE `t_settings`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `t_ug_rights`
--
ALTER TABLE `t_ug_rights`
  ADD PRIMARY KEY (`ugr_id`);

--
-- Indexes for table `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `t_user_groups`
--
ALTER TABLE `t_user_groups`
  ADD PRIMARY KEY (`ug_id`);

--
-- Indexes for table `t_user_logs`
--
ALTER TABLE `t_user_logs`
  ADD PRIMARY KEY (`ul_id`);

--
-- Indexes for table `t_user_rights`
--
ALTER TABLE `t_user_rights`
  ADD PRIMARY KEY (`ur_id`);

--
-- Indexes for table `t_zoom_settings`
--
ALTER TABLE `t_zoom_settings`
  ADD PRIMARY KEY (`zs_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_actions`
--
ALTER TABLE `t_actions`
  MODIFY `a_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `t_action_done`
--
ALTER TABLE `t_action_done`
  MODIFY `ad_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_comments`
--
ALTER TABLE `t_comments`
  MODIFY `comm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_companies`
--
ALTER TABLE `t_companies`
  MODIFY `c_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_dcomment_seen`
--
ALTER TABLE `t_dcomment_seen`
  MODIFY `dcs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_documents`
--
ALTER TABLE `t_documents`
  MODIFY `d_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_document_attachments`
--
ALTER TABLE `t_document_attachments`
  MODIFY `da_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_document_routings`
--
ALTER TABLE `t_document_routings`
  MODIFY `dr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_document_types`
--
ALTER TABLE `t_document_types`
  MODIFY `dt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `t_ecomment_seen`
--
ALTER TABLE `t_ecomment_seen`
  MODIFY `ecs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_events`
--
ALTER TABLE `t_events`
  MODIFY `e_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_event_attachments`
--
ALTER TABLE `t_event_attachments`
  MODIFY `ea_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_event_seen`
--
ALTER TABLE `t_event_seen`
  MODIFY `es_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_groups`
--
ALTER TABLE `t_groups`
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `t_group_members`
--
ALTER TABLE `t_group_members`
  MODIFY `gm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_meetings`
--
ALTER TABLE `t_meetings`
  MODIFY `m_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_participants`
--
ALTER TABLE `t_participants`
  MODIFY `p_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_settings`
--
ALTER TABLE `t_settings`
  MODIFY `s_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_ug_rights`
--
ALTER TABLE `t_ug_rights`
  MODIFY `ugr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `u_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_user_groups`
--
ALTER TABLE `t_user_groups`
  MODIFY `ug_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_user_logs`
--
ALTER TABLE `t_user_logs`
  MODIFY `ul_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_user_rights`
--
ALTER TABLE `t_user_rights`
  MODIFY `ur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `t_zoom_settings`
--
ALTER TABLE `t_zoom_settings`
  MODIFY `zs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
