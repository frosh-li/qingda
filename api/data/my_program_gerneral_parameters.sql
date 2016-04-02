-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 12 日 20:47
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
-- 表的结构 `my_program_gerneral_parameters`
--

CREATE TABLE IF NOT EXISTS `my_program_gerneral_parameters` (
  `id` int(12) NOT NULL auto_increment,
  `password` text,
  `time_for_db_write_interval` float(10,3) default NULL,
  `b_station_basic_infor_changed` int(8) default NULL,
  `N_histroy_average_number` float(10,3) default NULL,
  `station_delta_I_write_history_db` float(10,3) default NULL,
  `battery_delta_U_write_history_db` float(10,3) default NULL,
  `battery_delta_R_write_history_db` float(10,3) default NULL,
  `battery_delta_T_write_history_db` float(10,3) default NULL,
  `group_delta_I_write_history_db` float(10,3) default NULL,
  `group_delta_U_write_history_db` float(10,3) default NULL,
  `group_delta_T_write_history_db` float(10,3) default NULL,
  `memo` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `my_program_gerneral_parameters`
--

INSERT INTO `my_program_gerneral_parameters` (`id`, `password`, `time_for_db_write_interval`, `b_station_basic_infor_changed`, `N_histroy_average_number`, `station_delta_I_write_history_db`, `battery_delta_U_write_history_db`, `battery_delta_R_write_history_db`, `battery_delta_T_write_history_db`, `group_delta_I_write_history_db`, `group_delta_U_write_history_db`, `group_delta_T_write_history_db`, `memo`) VALUES
(1, 'bms', 0.100, 0, 5.000, 0.500, 0.950, 2.000, 1.000, 0.920, 4.800, 2.000, '无');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
