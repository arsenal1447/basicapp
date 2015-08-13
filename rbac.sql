/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : rbac

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-08-13 16:25:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `node`
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `n_id` int(11) NOT NULL,
  `n_name` varchar(50) NOT NULL,
  `desc_name` varchar(100) NOT NULL,
  PRIMARY KEY (`n_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of node
-- ----------------------------

-- ----------------------------
-- Table structure for `node_role`
-- ----------------------------
DROP TABLE IF EXISTS `node_role`;
CREATE TABLE `node_role` (
  `role_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  KEY `roleid` (`role_id`),
  KEY `noteid` (`node_id`),
  CONSTRAINT `nodeid` FOREIGN KEY (`node_id`) REFERENCES `node` (`n_id`),
  CONSTRAINT `roleid` FOREIGN KEY (`role_id`) REFERENCES `role` (`r_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of node_role
-- ----------------------------

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `r_id` int(11) NOT NULL,
  `r_name` varchar(50) NOT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------

-- ----------------------------
-- Table structure for `role_user`
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `roleid2` (`role_id`),
  KEY `userid2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_user
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT '',
  `password_hash` varchar(100) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('8', 'zxx123', 'zxx123@qq.com', '$2y$13$o04zNHqho0ngHDDrkLL3aO/3F7hwFqPQlPWYToq2SkeMiD2mcd3hW', 'LmbXU8yhSGb23gqhPqsGnfKVWlyMBz-f', '10', null);
INSERT INTO `user` VALUES ('9', 'zhou1234', 'zhou1234@qq.com', '$2y$13$Ki3RSr6iv2FyHpbwxdXJ1.7WFG1K4/7/L4aliyEKrNHV8p2U/M6u6', 'StQtCV2YBfygoBhJX-oUhACBsH1fsW0V', '10', null);
INSERT INTO `user` VALUES ('10', 'abc', 'abc@qq.com', '$2y$13$/iyennkVdjXRRRA0vbWwuuIHNZFm14SyL3GPwzu8Th8nd8z1CXTPa', '4fGtXN_HgeFrqL74tvTX-sqTdWq6xQ6a', '10', null);
