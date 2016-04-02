-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 13 日 21:59
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
-- 表的结构 `my_site`
--

DROP TABLE IF EXISTS `my_site`;
CREATE TABLE IF NOT EXISTS `my_site` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `sid` int(11) unsigned NOT NULL COMMENT '站号',
  `site_name` varchar(255) NOT NULL COMMENT '站点全称',
  `StationFullChineseName` varchar(300) NOT NULL COMMENT '站点全称',
  `serial_number` varchar(100) NOT NULL COMMENT '硬件序列号',
  `site_property` varchar(50) NOT NULL COMMENT '站点性质',
  `site_location` varchar(255) NOT NULL COMMENT '站点地址',
  `site_chname` varchar(255) NOT NULL COMMENT '站点中文名称',
  `site_longitude` double NOT NULL COMMENT '站点经度',
  `site_latitude` double NOT NULL COMMENT '站点维度',
  `ipaddress` varchar(20) NOT NULL COMMENT 'ip地址',
  `ipaddress_method` varchar(30) NOT NULL COMMENT '控制器IP地址或方式',
  `site_control_type` varchar(30) NOT NULL COMMENT '站点控制器型号',
  `postal_code` varchar(20) NOT NULL COMMENT '邮政编码',
  `person_phone` varchar(20) NOT NULL COMMENT '负责人手机',
  `person_tel` varchar(20) NOT NULL COMMENT '负责人电话',
  `person_tel_sec` varchar(20) NOT NULL COMMENT '电话2',
  `person_name` varchar(30) NOT NULL COMMENT '负责人姓名',
  `person_name_sec` varchar(50) NOT NULL COMMENT '负责人2',
  `person_sec_phone` varchar(20) NOT NULL COMMENT '负责人电话2',
  `person_name_th` varchar(50) NOT NULL COMMENT '负责人3',
  `person_th_phone` int(11) NOT NULL,
  `emergency_phone` varchar(20) NOT NULL COMMENT '紧急联系人手机',
  `emergency_person` varchar(30) NOT NULL COMMENT '紧急联系人姓名',
  `remark` text NOT NULL COMMENT '备注',
  `aid` int(11) NOT NULL COMMENT '隶属区域id',
  `area` varchar(50) NOT NULL COMMENT '隶属区域',
  `groups` int(11) NOT NULL COMMENT '组数',
  `batteries` int(11) NOT NULL COMMENT '电池数',
  `battery_status` varchar(30) NOT NULL COMMENT '电池码放类型',
  `bms_install_date` datetime NOT NULL,
  `group_collect_type` varchar(30) NOT NULL,
  `group_collect_num` int(10) NOT NULL,
  `inductor_brand` varchar(50) NOT NULL,
  `inductor_type` varchar(30) NOT NULL,
  `group_collect_install_type` varchar(30) NOT NULL,
  `battery_collect_type` varchar(30) NOT NULL,
  `battery_collect_num` int(10) NOT NULL,
  `humiture_type` varchar(30) NOT NULL,
  `equip_one` varchar(30) NOT NULL,
  `equip_fun_one` varchar(50) NOT NULL,
  `equip_sec` varchar(30) NOT NULL,
  `equip_fun_sec` varchar(50) NOT NULL,
  `equip_th` varchar(30) NOT NULL,
  `equip_fun_th` varchar(50) NOT NULL,
  `equip_four` varchar(30) NOT NULL,
  `equip_fun_four` varchar(50) NOT NULL,
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
