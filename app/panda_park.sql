/*
Navicat MySQL Data Transfer

Source Server         : 47.94.93.160(冠劼) 
Source Server Version : 50548
Source Host           : 47.94.93.160:3306
Source Database       : panda_park

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-05-17 18:22:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `login_log`
-- ----------------------------
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log` (
  `user_id` int(11) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL,
  `login_ip` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of login_log
-- ----------------------------
INSERT INTO `login_log` VALUES ('1', '1495016462', '10.10.32.65');
INSERT INTO `login_log` VALUES ('1', '1495016505', '10.10.32.65');

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) DEFAULT NULL,
  `get_username` varchar(56) DEFAULT NULL,
  `get_real_name` varchar(56) DEFAULT NULL,
  `gold` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('17', '1', 't1', '测试1', '10', '1', '1494667612');
INSERT INTO `order` VALUES ('18', '1', 't1', '测试1', '11', '1.1', '1494668369');
INSERT INTO `order` VALUES ('19', '14', 'admin', 'admin', '10', '1', '1494669220');

-- ----------------------------
-- Table structure for `order_selll`
-- ----------------------------
DROP TABLE IF EXISTS `order_selll`;
CREATE TABLE `order_selll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) DEFAULT NULL,
  `seller_username` varchar(56) DEFAULT NULL,
  `get_id` int(11) DEFAULT NULL,
  `get_username` varchar(56) DEFAULT NULL,
  `gold` float DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `get_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order_selll
-- ----------------------------
INSERT INTO `order_selll` VALUES ('3', '1', 'admin', null, null, '1', '1494668966', null);
INSERT INTO `order_selll` VALUES ('4', '1', 'admin', null, null, '1', '1494668977', null);
INSERT INTO `order_selll` VALUES ('5', '1', 'admin', null, null, '1', '1494669002', null);

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(56) DEFAULT NULL,
  `real_name` varchar(56) DEFAULT NULL,
  `password` varchar(56) DEFAULT NULL,
  `password2` varchar(56) DEFAULT NULL,
  `zfb` varchar(56) DEFAULT NULL,
  `wx` varchar(56) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `bamboo` float DEFAULT '0',
  `parent_user_id` int(11) DEFAULT '0',
  `gold` float DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_idx` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', 'admin', 'a66abb5684c45962d887564f08346e8d', 'a66abb5684c45962d887564f08346e8d', 'admin_zfb', 'admin_wx', '13888888888', '1', '0', '326');
INSERT INTO `user` VALUES ('14', 't1', '测试1', 'e10adc3949ba59abbe56e057f20f883e', 'e10adc3949ba59abbe56e057f20f883e', 'zfb1', 'wx1', null, '0', '1', '10');

-- ----------------------------
-- Table structure for `user_land`
-- ----------------------------
DROP TABLE IF EXISTS `user_land`;
CREATE TABLE `user_land` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `land_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `land_weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_land
-- ----------------------------
INSERT INTO `user_land` VALUES ('1', '1', '1', '1', '200');
INSERT INTO `user_land` VALUES ('5', '1', '6', '1', '200');
INSERT INTO `user_land` VALUES ('6', '1', '14', '1', '200');
INSERT INTO `user_land` VALUES ('7', '1', '8', '1', '200');
