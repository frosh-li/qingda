-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 02 日 11:36
-- 服务器版本: 5.0.51b-community-nt-log
-- PHP 版本: 5.4.35

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `qingdalinghang`
--

-- --------------------------------------------------------

--
-- 表的结构 `my_adminuser`
--

DROP TABLE IF EXISTS `my_adminuser`;
CREATE TABLE IF NOT EXISTS `my_adminuser` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(128) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `department` varchar(100) NOT NULL COMMENT '部门',
  `password` varchar(128) NOT NULL,
  `salt` char(10) NOT NULL,
  `role` int(11) NOT NULL,
  `catalog` varchar(50) NOT NULL COMMENT '分类的权限id',
  `pid` varchar(50) NOT NULL,
  `cid` varchar(50) NOT NULL,
  `email` varchar(128) NOT NULL,
  `profile` text,
  `ipaddress` char(15) NOT NULL,
  `last_login_time` datetime NOT NULL,
  `create_time` datetime NOT NULL,
  `create_user_id` int(10) NOT NULL,
  `update_time` datetime NOT NULL,
  `update_user_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `my_adminuser`
--

INSERT INTO `my_adminuser` (`id`, `username`, `name`, `department`, `password`, `salt`, `role`, `catalog`, `pid`, `cid`, `email`, `profile`, `ipaddress`, `last_login_time`, `create_time`, `create_user_id`, `update_time`, `update_user_id`) VALUES
(1, 'admin', '系统管理员', '办公室', '21232f297a57a5a743894a0e4a801fc3', 'gqo8rZ', 0, '0', 'empty', 'empty', 'jerryteng@tencent.com', '', '61.135.172.68', '2015-12-31 15:05:11', '2011-09-05 11:50:30', 0, '2015-12-31 15:05:11', 0),
(2, 'dzbgs', '党政办公室', '党政办公室', 'bb0a81826334160574b056fdedfce813', 'zLM9Fv', 1, '2', '17', '68', 'test@test.com', '127.0.0.1', '220.181.73.70', '2014-11-02 09:34:06', '2014-05-12 17:14:02', 1, '2014-11-02 09:34:06', 0),
(3, 'gh', '工会', '工会', '7d93e7680213bd6a695e9dba3aa613e1', 'YxxGM4', 1, '3', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:33:29', '2014-10-14 18:46:01', 1, '2014-10-28 08:33:29', 0),
(4, 'kyc', '科研处', '科研处', '1a5159ca34233c51008f5eb140817dbc', 'LsvZcD', 1, '1', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:34:02', '2014-10-15 17:12:11', 1, '2014-10-28 08:34:02', 0),
(5, 'jwc', '教务处', '教务处', 'd1683efa658538160f36788dc98a84ff', 'EO5HD9', 1, '1', '', '', 'test@test.com', NULL, '114.246.165.119', '2014-11-01 22:41:03', '2014-10-15 17:38:33', 1, '2014-11-01 22:41:03', 0),
(6, 'jxjyxy', '继续教育学院', '继续教育学院', 'a61e307f2f81fd270550ba78f514c26f', 'e9Lv6p', 1, '0', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:41:23', '2014-10-15 17:39:13', 1, '2014-10-28 08:41:23', 0),
(7, 'yjsy', '研究生院', '研究生院', '77f5648eb6b072d6f34a878fdf6e07bd', 'ms5VDr', 1, '2', '17', '68', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:42:24', '2014-10-25 18:47:57', 1, '2014-10-28 08:42:24', 0),
(8, 'glxy', '管理学院', '管理学院', '19937a4183b3f7e54d56fcb1d0207c19', 'OZftdk', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:44:17', '2014-10-27 10:58:27', 1, '2014-10-28 08:44:17', 0),
(9, 'cwc', '财务处', '财务处', '38e2dd0c65b2cd5c257346045526397d', 'nxhHii', 1, '', '', '', 'test@test.com', NULL, '61.135.172.68', '2014-11-01 13:46:51', '2014-10-27 14:41:44', 1, '2014-11-01 13:46:51', 0),
(10, 'zcysbglc', '资产处', '资产处', '59a536bdcaf70dd19db3783d380d51ff', 'ZRdjjf', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:46:41', '2014-10-27 14:46:12', 1, '2014-10-28 08:46:41', 0),
(11, 'kjcyc', '科技产业处', '科技产业处', 'aa1c2dc149351ea3398d448c99cf5df9', 'N5ZFWG', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:48:27', '2014-10-27 14:47:53', 1, '2014-10-28 08:48:27', 0),
(12, 'tsg', '图书馆', '图书馆', '30c5e4fa127f5c42eda238333989b7d0', 'RW0PxT', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:48:42', '2014-10-27 14:50:28', 1, '2014-10-28 08:48:42', 0),
(13, 'hqc', '后勤处', '后勤处', '7926d0833cfa61f1477e259b950d9ec4', 'FFyvb7', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:49:04', '2014-10-27 14:51:37', 1, '2014-10-28 08:49:04', 0),
(14, 'jjc', '基建处', '基建处', '7a32b19502d8c4163c96fad7553debc4', 'QGQRWC', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:49:57', '2014-10-27 14:52:47', 1, '2014-10-28 08:49:57', 0),
(15, 'zzb', '组织部 ', '组织部', '5d52f12fbe034b0ef76ee4a338cb5d14', '1ahKAv', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:50:41', '2014-10-27 14:54:14', 1, '2014-10-28 08:50:41', 0),
(16, 'rsc', '人事处', '人事处', 'd6da53a5215995e8b627c43bab693bdb', 'bUxf2a', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:51:50', '2014-10-27 14:55:21', 1, '2014-10-28 08:51:50', 0),
(17, 'xsgzc', '学生工作处', '学生工作处', '39bcf305995833ee1136436b633ab9e3', 'Uciabk', 1, '', '', '', 'test@test.com', NULL, '10.11.50.51', '2014-10-30 10:52:15', '2014-10-27 14:56:54', 1, '2014-10-30 10:52:15', 0),
(18, 'xkjsbgs', '学科办', '学科办', '28f9bfe15c35adfec635d5bdace94398', 'kg16fR', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:53:29', '2014-10-27 14:58:53', 1, '2014-10-28 08:53:29', 0),
(19, 'gjjlyhzc', '外事处', '外事处', 'f2ba0df354f48c6269d8b852461fa1a9', 'w6a12W', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:55:32', '2014-10-27 15:07:04', 1, '2014-10-28 08:55:32', 0),
(20, 'bwc', '保卫处', '保卫处', 'ec86805eef0b4db89dcaa0eea2e09151', 'EM1dpa', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:55:58', '2014-10-27 15:13:58', 1, '2014-10-28 08:55:58', 0),
(21, 'jw', '纪委', '纪委', '736c2d999c13acb78f4f8eadb6374781', 'vqMHFj', 1, '', '', '', 'test@test.com', NULL, '219.242.67.10', '2014-10-28 08:57:01', '2014-10-27 15:15:32', 1, '2014-10-28 08:57:01', 0),
(24, 'test_001', 'xujiangbo_test', 'test', '0e4d5dd9c941d7de3268166a5caf22b0', '1I0HGZ', 1, '', '', '', '111@2222.com', NULL, '124.200.52.64', '2014-11-01 22:40:01', '2014-11-01 22:35:50', 1, '2014-11-01 22:40:01', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
