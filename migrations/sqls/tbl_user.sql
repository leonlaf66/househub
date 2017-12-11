/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50619
Source Host           : localhost:3306
Source Database       : wsnail

Target Server Type    : MYSQL
Target Server Version : 50619
File Encoding         : 65001

Date: 2016-03-03 22:46:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `blocked_at` datetime DEFAULT NULL,
  `registration_ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_sys` smallint(2) DEFAULT '0',
  `flags` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
INSERT INTO `tbl_user` VALUES ('1', 'ws-android-app@wesnail.com', 'ws-android-app-!@#', '', 'ws-android-app-test', null, null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');
INSERT INTO `tbl_user` VALUES ('2', 'ws-ios-app', 'ws-ios-app-!@#', '', 'ws-ios-app-test', null, null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');
INSERT INTO `tbl_user` VALUES ('3', 'ws-web-app', 'ws-web-app-!@#', '', 'ws-web-app-test', null, null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');
