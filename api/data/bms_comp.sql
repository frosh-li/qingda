-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 30 日 22:09
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
-- 表的结构 `my_bms_info`
--

DROP TABLE IF EXISTS `my_bms_info`;
CREATE TABLE IF NOT EXISTS `my_bms_info` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `bms_company` varchar(255) NOT NULL,
  `bms_device_addr` varchar(255) NOT NULL,
  `bms_postcode` varchar(20) NOT NULL,
  `bms_url` varchar(150) NOT NULL,
  `bms_tel` varchar(20) NOT NULL,
  `bms_phone` varchar(20) NOT NULL,
  `bms_service_phone` varchar(20) NOT NULL,
  `bms_service_name` varchar(200) NOT NULL,
  `bms_service_url` varchar(150) NOT NULL,
  `bms_version` varchar(20) NOT NULL,
  `bms_update_mark` text NOT NULL,
  `bms_mark` text NOT NULL,
  `modify_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `my_company_info`
--

DROP TABLE IF EXISTS `my_company_info`;
CREATE TABLE IF NOT EXISTS `my_company_info` (
  `id` int(11) NOT NULL auto_increment COMMENT 'id',
  `company_name` varchar(200) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `supervisor_phone` varchar(20) NOT NULL,
  `supervisor_name` varchar(100) NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL,
  `station_num` int(10) NOT NULL,
  `area_level` int(10) NOT NULL,
  `network_type` varchar(255) NOT NULL,
  `bandwidth` int(10) NOT NULL,
  `ipaddr` varchar(20) NOT NULL,
  `computer_brand` varchar(100) NOT NULL,
  `computer_os` varchar(100) NOT NULL,
  `computer_conf` varchar(150) NOT NULL,
  `browser_name` varchar(50) NOT NULL,
  `server_capacity` varchar(50) NOT NULL,
  `server_type` varchar(50) NOT NULL,
  `cloud_address` varchar(150) NOT NULL,
  `backup_period` varchar(20) NOT NULL,
  `backup_type` varchar(50) NOT NULL,
  `supervisor_depname` varchar(255) NOT NULL,
  `monitor_name1` varchar(50) NOT NULL,
  `monitor_phone1` varchar(20) NOT NULL,
  `monitor_name2` varchar(50) NOT NULL,
  `monitor_phone2` varchar(20) NOT NULL,
  `monitor_name3` varchar(50) NOT NULL,
  `monitor_phone3` varchar(50) NOT NULL,
  `monitor_tel1` varchar(20) NOT NULL,
  `monitor_tel2` varchar(20) NOT NULL,
  `modify_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
