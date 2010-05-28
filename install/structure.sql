-- phpMyAdmin SQL Dump
-- version 3.1.2deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 01, 2010 at 01:11 PM
-- Server version: 5.0.75
-- PHP Version: 5.2.6-3ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mucms_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `V0_article`
--

CREATE TABLE IF NOT EXISTS `V0_article` (
  `page_id` int(11) NOT NULL,
  `page_content` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `V0_global`
--

CREATE TABLE IF NOT EXISTS `V0_global` (
  `cms_title` varchar(100) NOT NULL,
  `allow_pagespecific_header` int(11) NOT NULL,
  `allow_pagespecific_template` int(11) NOT NULL,
  `default_template` varchar(50) NOT NULL,
  `default_user_activate` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `V0_pages`
--

CREATE TABLE IF NOT EXISTS `V0_pages` (
  `page_id` int(11) NOT NULL auto_increment,
  `page_name` varchar(27) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `page_access` int(11) NOT NULL default '1' COMMENT '1 means ROOT,  0 means GUEST',
  `page_menu_display` int(11) NOT NULL default '1',
  `page_menuitem_display` int(1) NOT NULL,
  `page_menuitem_order` int(11) NOT NULL,
  `page_type` varchar(50) NOT NULL,
  `page_rightbar_display` int(11) NOT NULL default '1',
  `page_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `page_template` varchar(50) NOT NULL default 'default',
  `login_required` int(11) NOT NULL default '0',
  PRIMARY KEY  (`page_id`),
  UNIQUE KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `V0_pagetypes`
--

CREATE TABLE IF NOT EXISTS `V0_pagetypes` (
  `page_type` varchar(50) NOT NULL,
  `page_type_table` varchar(50) NOT NULL,
  `page_type_table_fields` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `V0_profile`
--

CREATE TABLE IF NOT EXISTS `V0_profile` (
  `user_id` int(11) NOT NULL,
  `user_contactaddr` text NOT NULL,
  `user_contactnum` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `V0_templates`
--

CREATE TABLE IF NOT EXISTS `V0_templates` (
  `template_name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `V0_users`
--

CREATE TABLE IF NOT EXISTS `V0_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_lastlogin` datetime NOT NULL,
  `user_regdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `user_activated` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1018 ;
