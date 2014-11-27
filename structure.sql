-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Nov 10, 2014 at 11:35 AM
-- Server version: 5.5.38
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `board`
--

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `bo_table` varchar(20) NOT NULL,
  `gr_id` varchar(20) NOT NULL,
  `bo_subject` varchar(20) NOT NULL,
  `bo_admin` varchar(20) NOT NULL,
  `bo_list_level` tinyint(4) unsigned NOT NULL,
  `bo_read_level` tinyint(4) unsigned NOT NULL,
  `bo_write_level` tinyint(4) unsigned NOT NULL,
  `bo_reply_level` tinyint(4) unsigned NOT NULL,
  `bo_comment_level` tinyint(4) unsigned NOT NULL,
  `bo_upload_level` tinyint(4) unsigned NOT NULL,
  `bo_download_level` tinyint(4) unsigned NOT NULL,
  `bo_count_delete` tinyint(4) unsigned NOT NULL,
  `bo_count_modify` tinyint(4) unsigned NOT NULL,
  `bo_use_private` tinyint(2) unsigned NOT NULL,
  `bo_use_rss` tinyint(2) unsigned NOT NULL,
  `bo_use_sns` tinyint(2) unsigned NOT NULL,
  `bo_use_comment` tinyint(2) unsigned NOT NULL,
  `bo_use_category` tinyint(2) unsigned NOT NULL,
  `bo_use_sideview` tinyint(2) unsigned NOT NULL,
  `bo_use_secret` tinyint(2) unsigned NOT NULL,
  `bo_use_editor` tinyint(2) unsigned NOT NULL,
  `bo_use_name` tinyint(2) unsigned NOT NULL,
  `bo_use_ip_view` tinyint(2) unsigned NOT NULL,
  `bo_use_list_view` tinyint(2) unsigned NOT NULL,
  `bo_use_email` tinyint(2) unsigned NOT NULL,
  `bo_use_extra` tinyint(2) unsigned NOT NULL,
  `bo_use_syntax` tinyint(2) unsigned NOT NULL,
  `bo_skin` varchar(20) NOT NULL,
  `bo_page_rows` int(10) unsigned NOT NULL,
  `bo_page_rows_comt` int(10) unsigned NOT NULL,
  `bo_subject_len` int(10) unsigned NOT NULL,
  `bo_new` int(10) unsigned NOT NULL,
  `bo_hot` int(10) unsigned NOT NULL,
  `bo_image_width` int(10) unsigned NOT NULL,
  `bo_reply_order` tinyint(4) unsigned NOT NULL,
  `bo_sort_field` varchar(50) NOT NULL,
  `bo_upload_ext` varchar(50) NOT NULL,
  `bo_upload_size` int(10) unsigned NOT NULL,
  `bo_head` varchar(50) NOT NULL,
  `bo_tail` varchar(50) NOT NULL,
  `bo_insert_content` text NOT NULL,
  `bo_use_search` tinyint(2) unsigned NOT NULL,
  `bo_order_search` tinyint(4) unsigned NOT NULL,
  `bo_count_write` int(10) unsigned NOT NULL,
  `bo_count_comment` int(10) unsigned NOT NULL,
  `bo_notice` varchar(255) NOT NULL,
  `bo_min_wr_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`bo_table`, `gr_id`, `bo_subject`, `bo_admin`, `bo_list_level`, `bo_read_level`, `bo_write_level`, `bo_reply_level`, `bo_comment_level`, `bo_upload_level`, `bo_download_level`, `bo_count_delete`, `bo_count_modify`, `bo_use_private`, `bo_use_rss`, `bo_use_sns`, `bo_use_comment`, `bo_use_category`, `bo_use_sideview`, `bo_use_secret`, `bo_use_editor`, `bo_use_name`, `bo_use_ip_view`, `bo_use_list_view`, `bo_use_email`, `bo_use_extra`, `bo_use_syntax`, `bo_skin`, `bo_page_rows`, `bo_page_rows_comt`, `bo_subject_len`, `bo_new`, `bo_hot`, `bo_image_width`, `bo_reply_order`, `bo_sort_field`, `bo_upload_ext`, `bo_upload_size`, `bo_head`, `bo_tail`, `bo_insert_content`, `bo_use_search`, `bo_order_search`, `bo_count_write`, `bo_count_comment`, `bo_notice`, `bo_min_wr_num`) VALUES
('test', 'test', 'test', 'admin', 1, 1, 2, 2, 2, 2, 2, 0, 0, 0, 0, 0, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 'basic', 15, 50, 75, 24, 100, 800, 1, '', 'zip|swf', 2048, '', '', '', 1, 0, 1, 0, '', -1);

-- --------------------------------------------------------

--
-- Table structure for table `board_file`
--

CREATE TABLE `board_file` (
  `bo_table` varchar(20) NOT NULL,
  `wr_id` int(10) unsigned NOT NULL,
  `bf_editor` tinyint(2) unsigned NOT NULL,
  `bf_no` int(10) unsigned NOT NULL,
  `bf_source` varchar(255) NOT NULL,
  `bf_file` varchar(255) NOT NULL,
  `bf_download` int(10) unsigned NOT NULL,
  `bf_filesize` int(10) unsigned NOT NULL,
  `bf_width` smallint(6) unsigned NOT NULL,
  `bf_height` smallint(6) unsigned NOT NULL,
  `bf_type` tinyint(4) NOT NULL,
  `bf_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `board_group`
--

CREATE TABLE `board_group` (
  `gr_id` varchar(20) NOT NULL,
  `gr_subject` varchar(20) NOT NULL,
  `gr_admin` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `board_group`
--

INSERT INTO `board_group` (`gr_id`, `gr_subject`, `gr_admin`) VALUES
('test', 'test', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ca_type` varchar(20) NOT NULL,
  `ca_code` varchar(255) NOT NULL,
  `ca_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `bo_table` varchar(20) NOT NULL,
  `wr_id` int(10) unsigned NOT NULL,
`co_id` int(10) unsigned NOT NULL,
  `co_num` int(11) NOT NULL,
  `co_reply` varchar(10) NOT NULL,
  `ca_code` varchar(255) NOT NULL,
  `co_option` set('editor','secret') NOT NULL,
  `co_content` text NOT NULL,
  `mb_id` varchar(20) NOT NULL,
  `co_password` char(128) NOT NULL,
  `co_name` varchar(10) NOT NULL,
  `co_datetime` datetime NOT NULL,
  `co_last` datetime NOT NULL,
  `co_ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE `mail` (
`ma_id` int(10) unsigned NOT NULL,
  `ma_subject` varchar(255) NOT NULL,
  `ma_content` mediumtext NOT NULL,
  `ma_time` datetime NOT NULL,
  `ma_ip` varchar(20) NOT NULL,
  `ma_last_option` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
`mb_no` int(10) unsigned NOT NULL,
  `mb_id` varchar(20) NOT NULL,
  `mb_password` char(128) NOT NULL,
  `mb_name` varchar(10) NOT NULL,
  `mb_nick` varchar(20) NOT NULL,
  `mb_nick_date` date NOT NULL,
  `mb_email` varchar(50) NOT NULL,
  `mb_homepage` varchar(40) NOT NULL,
  `mb_password_q` varchar(50) NOT NULL,
  `mb_password_a` varchar(50) NOT NULL,
  `mb_level` tinyint(4) unsigned NOT NULL,
  `mb_sex` char(1) NOT NULL,
  `mb_birth` date NOT NULL,
  `mb_tel` varchar(14) NOT NULL,
  `mb_hp` varchar(14) NOT NULL,
  `mb_zip` char(7) NOT NULL,
  `mb_addr1` varchar(100) NOT NULL,
  `mb_addr2` varchar(100) NOT NULL,
  `mb_point` int(11) NOT NULL,
  `mb_today_login` datetime NOT NULL,
  `mb_login_ip` varchar(20) NOT NULL,
  `mb_datetime` datetime NOT NULL,
  `mb_ip` varchar(20) NOT NULL,
  `mb_leave_date` varchar(8) NOT NULL,
  `mb_email_certify` datetime NOT NULL,
  `mb_mailling` tinyint(2) unsigned NOT NULL,
  `mb_open` tinyint(2) unsigned NOT NULL,
  `mb_open_date` date NOT NULL,
  `mb_profile` text NOT NULL,
  `mb_memo_call` varchar(20) NOT NULL,
  `mb_memo_cnt` tinyint(4) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`mb_no`, `mb_id`, `mb_password`, `mb_name`, `mb_nick`, `mb_nick_date`, `mb_email`, `mb_homepage`, `mb_password_q`, `mb_password_a`, `mb_level`, `mb_sex`, `mb_birth`, `mb_tel`, `mb_hp`, `mb_zip`, `mb_addr1`, `mb_addr2`, `mb_point`, `mb_today_login`, `mb_login_ip`, `mb_datetime`, `mb_ip`, `mb_leave_date`, `mb_email_certify`, `mb_mailling`, `mb_open`, `mb_open_date`, `mb_profile`, `mb_memo_call`, `mb_memo_cnt`) VALUES
(1, 'admin', '1yRONOOjfSKTL90dyjv+TjlSBeN3LB0ftTgH/4iDraMttvHqJPtxpg0Tq9tE4T1HP6nKNPsdjxL2Js7WfuWbdg==', '관리자', '관리자', '2014-11-06', 'admin@test.com', '', '', '', 10, 'M', '2014-11-06', '', '', '-', '', '', 200, '2014-11-09 15:28:25', '127.0.0.1', '2014-11-06 17:31:17', '127.0.0.1', '', '2014-11-06 17:31:17', 0, 0, '2014-11-06', '', '', 0),
(2, 'test', '77SgJkzy2SD5DLdf9+mP17FI8C5pC6pZdrPaMc41+scuiyeL7smYhqhYOvXKz7KEkbKTufUU04iR4xBdWIbpiQ==', '후니', 'hooni', '2014-11-07', 'jsimson@naver.com', '', '내가 좋아하는 캐릭터는?', '1234', 2, '', '0000-00-00', '', '', '-', '', '', 100, '2014-11-07 11:18:51', '127.0.0.1', '2014-11-07 11:18:51', '127.0.0.1', '', '2014-11-07 11:18:51', 1, 1, '2014-11-07', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `memo`
--

CREATE TABLE `memo` (
  `me_no` int(10) unsigned NOT NULL,
  `me_parent` int(10) unsigned NOT NULL,
  `me_flag` enum('R','S') NOT NULL,
  `mb_id` varchar(20) NOT NULL,
  `me_mb_id` varchar(20) NOT NULL,
  `me_content` text NOT NULL,
  `me_datetime` datetime NOT NULL,
  `me_check` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point`
--

CREATE TABLE `point` (
`po_id` int(10) unsigned NOT NULL,
  `mb_id` varchar(20) NOT NULL,
  `po_datetime` datetime NOT NULL,
  `po_content` varchar(255) NOT NULL,
  `po_point` int(11) NOT NULL,
  `po_rel_table` varchar(20) NOT NULL,
  `po_rel_id` varchar(20) NOT NULL,
  `po_rel_action` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `point`
--

INSERT INTO `point` (`po_id`, `mb_id`, `po_datetime`, `po_content`, `po_point`, `po_rel_table`, `po_rel_id`, `po_rel_action`) VALUES
(1, 'admin', '2014-11-07 09:57:32', '2014-11-07 첫로그인', 100, '@login', 'admin', '2014-11-07'),
(2, 'test', '2014-11-07 11:18:51', '회원가입 축하', 100, '@member', 'test', '회원가입'),
(3, 'admin', '2014-11-09 15:28:25', '2014-11-09 첫로그인', 100, '@login', 'admin', '2014-11-09');

-- --------------------------------------------------------

--
-- Table structure for table `popular`
--

CREATE TABLE `popular` (
`pp_id` int(10) unsigned NOT NULL,
  `pp_word` varchar(50) NOT NULL,
  `pp_date` date NOT NULL,
  `pp_ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `popup`
--

CREATE TABLE `popup` (
`pu_id` int(10) unsigned NOT NULL,
  `pu_name` varchar(20) NOT NULL,
  `pu_file` varchar(20) NOT NULL,
  `pu_use` tinyint(2) unsigned NOT NULL,
  `pu_type` tinyint(2) unsigned NOT NULL,
  `pu_sdate` datetime NOT NULL,
  `pu_edate` datetime NOT NULL,
  `pu_width` smallint(6) unsigned NOT NULL,
  `pu_height` smallint(6) unsigned NOT NULL,
  `pu_x` smallint(6) unsigned NOT NULL,
  `pu_y` smallint(6) unsigned NOT NULL,
  `pu_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('07a41aafa262adf16008eae13b4eb2dc', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.3', 1415523066, 'a:4:{s:9:"user_data";s:0:"";s:11:"ck_visit_ip";s:9:"127.0.0.1";s:8:"ss_mb_id";s:5:"admin";s:8:"ss_token";s:32:"f0ccbdd6d0882f1d613711a99595610e";}'),
('87dea004f192e0dd6b836de9e12cee17', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.3', 1415578083, 'a:3:{s:9:"user_data";s:0:"";s:11:"ck_visit_ip";s:9:"127.0.0.1";s:8:"ss_token";s:32:"7088e382b1c1379c76048bf59b35e06a";}'),
('b493cba5fce36113c94a2ebecf2230f3', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25', 1415578093, 'a:3:{s:9:"user_data";s:0:"";s:11:"ck_visit_ip";s:9:"127.0.0.1";s:8:"ss_token";s:32:"c9fd59dd7b5f476cc4696d3f894dea9c";}');

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
`vi_id` int(10) unsigned NOT NULL,
  `vi_ip` varchar(20) NOT NULL,
  `vi_date` date NOT NULL,
  `vi_time` time NOT NULL,
  `vi_referer` text NOT NULL,
  `vi_agent` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`vi_id`, `vi_ip`, `vi_date`, `vi_time`, `vi_referer`, `vi_agent`) VALUES
(1, '127.0.0.1', '2014-11-06', '17:31:17', '/index.php/make', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36'),
(2, '::1', '2014-11-06', '19:16:57', '/index.php/', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36'),
(3, '127.0.0.1', '2014-11-07', '09:32:56', '/index.php/board/test', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36'),
(4, '127.0.0.1', '2014-11-09', '15:28:18', '/index.php/', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36'),
(5, '127.0.0.1', '2014-11-10', '08:46:57', '/index.php/admin/member/lists', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `write`
--

CREATE TABLE `write` (
  `bo_table` varchar(20) NOT NULL,
`wr_id` int(10) unsigned NOT NULL,
  `wr_num` int(11) NOT NULL,
  `wr_reply` varchar(10) NOT NULL,
  `ca_code` varchar(255) NOT NULL,
  `wr_comment` int(10) unsigned NOT NULL,
  `wr_option` set('editor','secret','mail','nocomt') NOT NULL,
  `wr_subject` varchar(255) NOT NULL,
  `wr_content` text NOT NULL,
  `wr_hit` int(10) unsigned NOT NULL,
  `mb_id` varchar(20) NOT NULL,
  `wr_password` char(128) NOT NULL,
  `wr_name` varchar(10) NOT NULL,
  `wr_email` varchar(50) NOT NULL,
  `wr_datetime` datetime NOT NULL,
  `wr_last` datetime NOT NULL,
  `wr_ip` varchar(20) NOT NULL,
  `wr_count_file` tinyint(4) unsigned NOT NULL,
  `wr_count_image` tinyint(4) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `write`
--

INSERT INTO `write` (`bo_table`, `wr_id`, `wr_num`, `wr_reply`, `ca_code`, `wr_comment`, `wr_option`, `wr_subject`, `wr_content`, `wr_hit`, `mb_id`, `wr_password`, `wr_name`, `wr_email`, `wr_datetime`, `wr_last`, `wr_ip`, `wr_count_file`, `wr_count_image`) VALUES
('test', 1, -1, '', '', 0, 'editor', '111', '<p>1111</p>', 3, 'admin', '1yRONOOjfSKTL90dyjv+TjlSBeN3LB0ftTgH/4iDraMttvHqJPtxpg0Tq9tE4T1HP6nKNPsdjxL2Js7WfuWbdg==', '관리자', 'admin@test.com', '2014-11-06 18:40:30', '2014-11-06 18:40:30', '127.0.0.1', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board`
--
ALTER TABLE `board`
 ADD PRIMARY KEY (`bo_table`);

--
-- Indexes for table `board_file`
--
ALTER TABLE `board_file`
 ADD PRIMARY KEY (`bo_table`,`wr_id`,`bf_editor`,`bf_no`);

--
-- Indexes for table `board_group`
--
ALTER TABLE `board_group`
 ADD PRIMARY KEY (`gr_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`ca_type`,`ca_code`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
 ADD PRIMARY KEY (`co_id`), ADD KEY `list` (`bo_table`,`wr_id`,`co_num`,`co_reply`,`ca_code`);

--
-- Indexes for table `mail`
--
ALTER TABLE `mail`
 ADD PRIMARY KEY (`ma_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
 ADD PRIMARY KEY (`mb_no`), ADD UNIQUE KEY `mb_id` (`mb_id`);

--
-- Indexes for table `memo`
--
ALTER TABLE `memo`
 ADD PRIMARY KEY (`me_no`), ADD KEY `list` (`me_flag`,`mb_id`);

--
-- Indexes for table `point`
--
ALTER TABLE `point`
 ADD PRIMARY KEY (`po_id`), ADD KEY `where` (`mb_id`,`po_rel_table`,`po_rel_id`,`po_rel_action`);

--
-- Indexes for table `popular`
--
ALTER TABLE `popular`
 ADD PRIMARY KEY (`pp_id`), ADD UNIQUE KEY `isset` (`pp_word`,`pp_date`,`pp_ip`);

--
-- Indexes for table `popup`
--
ALTER TABLE `popup`
 ADD PRIMARY KEY (`pu_id`), ADD KEY `po_use` (`pu_sdate`,`pu_edate`,`pu_use`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
 ADD PRIMARY KEY (`session_id`), ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
 ADD PRIMARY KEY (`vi_id`), ADD UNIQUE KEY `unique` (`vi_ip`,`vi_date`), ADD KEY `vi_date` (`vi_date`);

--
-- Indexes for table `write`
--
ALTER TABLE `write`
 ADD PRIMARY KEY (`wr_id`), ADD KEY `list` (`bo_table`,`wr_num`,`wr_reply`,`ca_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
MODIFY `co_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mail`
--
ALTER TABLE `mail`
MODIFY `ma_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
MODIFY `mb_no` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `point`
--
ALTER TABLE `point`
MODIFY `po_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `popular`
--
ALTER TABLE `popular`
MODIFY `pp_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `popup`
--
ALTER TABLE `popup`
MODIFY `pu_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
MODIFY `vi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `write`
--
ALTER TABLE `write`
MODIFY `wr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;