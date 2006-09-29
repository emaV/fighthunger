-- MySQL dump 10.9
--
-- Host: localhost    Database: wtw06
-- ------------------------------------------------------
-- Server version	4.1.16-standard

--
-- Table structure for table `partners_node`
--

DROP TABLE IF EXISTS `partners_node`;
CREATE TABLE `partners_node` (
  `nid` int(10) NOT NULL default '0',
  `crmid` int(10) NOT NULL default '0',
  `ptid` enum('NGO','PRIVATE','ISTITUTION','UNAGENCY','OTHER') NOT NULL default 'NGO',
  `logosmall` varchar(255) NOT NULL default '',
  `logolarge` varchar(255) NOT NULL default '',
  `startdate` date NOT NULL default '0000-00-00',
  `enddate` date NOT NULL default '0000-00-00',
  `active` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM;

--
-- Table structure for table `partners_partners`
--

DROP TABLE IF EXISTS `partners_partners`;
CREATE TABLE `partners_partners` (
  `pid` int(10) NOT NULL default '0',
  `nid` int(10) NOT NULL default '0'
) ENGINE=MyISAM;

--
-- Table structure for table `partners_user`
--

DROP TABLE IF EXISTS `partners_user`;
CREATE TABLE `partners_user` (
  `uid` int(10) NOT NULL default '0',
  `pid` int(10) NOT NULL default '0'
) ENGINE=MyISAM;


