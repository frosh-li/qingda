-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost:3306
-- 生成日期: 2016 年 01 月 12 日 20:16
-- 服务器版本: 5.0.51b-community-nt-log
-- PHP 版本: 5.4.35

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `qingda`
--

-- --------------------------------------------------------

--
-- 表的结构 `my_battery_info`
--

DROP TABLE IF EXISTS `my_battery_info`;
CREATE TABLE IF NOT EXISTS `my_battery_info` (
  `id` int(11) NOT NULL auto_increment COMMENT 'id',
  `sid` int(11) NOT NULL COMMENT '站id',
  `battery_factory` varchar(100) NOT NULL COMMENT '电池生产厂家',
  `battery_num` varchar(50) NOT NULL COMMENT '电池编号',
  `battery_date` datetime NOT NULL COMMENT '电池生产日期',
  `battery_voltage` double(10,2) NOT NULL COMMENT '电池标称电压',
  `battery_oum` double(10,2) NOT NULL COMMENT '电池标称内阻 毫欧',
  `battery_max_current` double(10,2) NOT NULL COMMENT '电池最大充电电流',
  `battery_float_up` double(10,2) NOT NULL COMMENT '电池浮充电压上限',
  `battery_float_dow` double(10,2) NOT NULL COMMENT '电池浮充电压下限',
  `battery_discharge_down` double(10,2) NOT NULL COMMENT '电池放电电压下限',
  `battery_scrap_date` date NOT NULL COMMENT '电池强制报废日期',
  `battery_life` double(10,2) NOT NULL COMMENT '电池设计寿命 年',
  `battery_column_type` varchar(20) NOT NULL COMMENT '电池级柱类型',
  `battery_humidity` double(10,2) NOT NULL COMMENT '电池湿度要求 %',
  `battery_type` varchar(50) NOT NULL COMMENT '电池类型',
  `battery_factory_phone` varchar(20) NOT NULL COMMENT '电池厂家联系电话',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `my_ups_info`
--

DROP TABLE IF EXISTS `my_ups_info`;
CREATE TABLE IF NOT EXISTS `my_ups_info` (
  `id` int(11) NOT NULL auto_increment COMMENT 'id',
  `sid` int(11) NOT NULL COMMENT '站点id',
  `ups_factory` varchar(255) NOT NULL COMMENT 'ups 厂家名称',
  `ups_type` varchar(50) NOT NULL COMMENT 'ups类型',
  `ups_create_date` datetime NOT NULL COMMENT 'ups 生成日期',
  `ups_install_date` datetime NOT NULL COMMENT 'ups 安装日期',
  `ups_power` double(10,2) NOT NULL COMMENT 'ups 功率',
  `redundancy_num` int(10) NOT NULL COMMENT '冗余数量',
  `flating_charge` double(10,2) NOT NULL COMMENT '浮充电压',
  `ups_vdc` double(10,2) NOT NULL COMMENT '电压范围',
  `ups_reserve_hour` int(10) NOT NULL COMMENT 'ups额定后备时间',
  `ups_charge_mode` varchar(50) NOT NULL COMMENT 'ups充电方式',
  `ups_max_charge` double(10,2) NOT NULL COMMENT 'ups最大充电电流',
  `ups_max_discharge` double(10,2) NOT NULL COMMENT 'ups最大放电电流',
  `ups_period_days` int(10) NOT NULL COMMENT 'ups规定维护周期',
  `ups_discharge_time` int(10) NOT NULL COMMENT 'ups维护放电时长 分钟',
  `ups_discharge_capacity` double(10,2) NOT NULL COMMENT 'ups 维护放电容量 %',
  `ups_maintain_date` int(10) NOT NULL COMMENT 'ups 维护到期日',
  `ups_vender_phone` varchar(20) NOT NULL COMMENT 'ups厂家联系电话',
  `ups_service` varchar(50) NOT NULL COMMENT 'ups服务商名称',
  `ups_service_phone` varchar(20) NOT NULL COMMENT 'ups服务商家电话',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ups信息表' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
