-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 17 日 14:55
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
-- 表的结构 `my_station_person`
--

CREATE TABLE IF NOT EXISTS `my_station_person` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `sid` int(12) NOT NULL,
  `Operater` varchar(50) NOT NULL,
  `Operater_cellphone` varchar(50) NOT NULL,
  `Alarm_SMS_receive_cellphone` varchar(50) NOT NULL,
  `Alarm_SMS_receive_emal` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
