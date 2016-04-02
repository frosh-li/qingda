-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 17 日 14:54
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
-- 表的结构 `my_station_device`
--

CREATE TABLE IF NOT EXISTS `my_station_device` (
  `id` int(11) NOT NULL auto_increment,
  `sid` int(12) NOT NULL,
  `Supervisory_Device_name` varchar(100) NOT NULL,
  `Supervisory_Device_fun` varchar(255) NOT NULL,
  `Supervisory_Device_Factory_Name` varchar(50) NOT NULL,
  `Supervisory_Device_Factory_Address` varchar(50) NOT NULL,
  `Supervisory_Device_Factory_PostCode` varchar(50) NOT NULL,
  `Supervisory_Device_Factory_website` varchar(50) NOT NULL,
  `Supervisory_Device_Factory_Technology_cable_phone` varchar(50) NOT NULL,
  `Supervisory_Device_Factory_Technology_cellphone` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
