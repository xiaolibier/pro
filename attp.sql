/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : attp

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-03-22 17:09:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `list`
-- ----------------------------
DROP TABLE IF EXISTS `list`;
CREATE TABLE `list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `width` varchar(255) DEFAULT NULL COMMENT '元素宽度',
  `height` varchar(255) DEFAULT NULL COMMENT '元素高度',
  `long` varchar(255) DEFAULT NULL COMMENT '元素长度',
  `left` varchar(255) DEFAULT NULL,
  `top` varchar(255) DEFAULT NULL,
  `right` varchar(255) DEFAULT NULL,
  `bottom` varchar(255) DEFAULT NULL,
  `translatex` varchar(255) DEFAULT NULL,
  `translatey` varchar(255) DEFAULT NULL,
  `translatez` varchar(255) DEFAULT NULL,
  `rotatex` varchar(255) DEFAULT NULL,
  `rotatey` varchar(255) DEFAULT NULL,
  `rotatez` varchar(255) DEFAULT NULL,
  `topsrc` varchar(255) DEFAULT NULL COMMENT '元素上面图片',
  `bottomsrc` varchar(255) DEFAULT NULL COMMENT '元素下面图片',
  `leftsrc` varchar(255) DEFAULT NULL COMMENT '元素左面图片',
  `rightsrc` varchar(255) DEFAULT NULL COMMENT '元素右面图片',
  `beforesrc` varchar(255) DEFAULT NULL COMMENT '元素前面图片',
  `aftersrc` varchar(255) DEFAULT NULL COMMENT '元素后面图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of list
-- ----------------------------
INSERT INTO `list` VALUES ('5', '地毯', '813', '5', '1000', '0', '', '', '0', '', '', '', '41', '20', '61', '/pro/upload//img/20180322/5ab354d6cccc6.jpg', '', '', '', '', '');
INSERT INTO `list` VALUES ('6', '房屋1', '175', '177', '200', '0', '', '', '18', '15', '', '-226', '', '', '', '/pro/upload//img/20180322/5ab369074cccd.jpg', '/pro/upload//img/20180322/5ab3696adf3fb.jpg', '/pro/upload//img/20180322/5ab36936789fc.jpg', '', '/pro/upload//img/20180322/5ab3692447081.jpg', '/pro/upload//img/20180322/5ab3691b67550.jpg');
