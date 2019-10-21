/*
Navicat MySQL Data Transfer

Source Server         : 微狐2019
Source Server Version : 50642
Source Host           : 47.94.0.228:3306
Source Database       : we7_20190709

Target Server Type    : MYSQL
Target Server Version : 50642
File Encoding         : 65001

Date: 2019-10-21 14:15:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_card`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_card`;
CREATE TABLE `ims_weihu_yuyue_card` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `name` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `daynum` int(9) DEFAULT NULL,
  `price` float(9,2) DEFAULT NULL,
  `num` int(9) DEFAULT NULL,
  `discount` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `discountcolor` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_card
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_card` VALUES ('7', '107', 'VIP卡', '365', '998.00', '52', '八五折', '#ff0000', '', 'images/107/2019/10/RLqpGBHp82PYzLLqHhhPgLP4JqK2gL.jpg', '0');
INSERT INTO `ims_weihu_yuyue_card` VALUES ('8', '107', '体验卡', '1', '8.00', '1', '', '#000000', '', 'images/107/2019/10/S9U0WqW9wj6Q7tNkZQ6976RR86qgTE.jpg', '0');

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_entitycard`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_entitycard`;
CREATE TABLE `ims_weihu_yuyue_entitycard` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `cid` int(9) DEFAULT NULL,
  `cardnum` varchar(155) COLLATE utf8_bin DEFAULT NULL,
  `actcode` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `jhmid` int(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_entitycard
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_entitycard` VALUES ('2', '107', '7', 'MC00000', 'weihu123', '', '1', '5');
INSERT INTO `ims_weihu_yuyue_entitycard` VALUES ('3', '107', '7', 'MC00001', 'weihu123', '', '1', '5');

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_hospital`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_hospital`;
CREATE TABLE `ims_weihu_yuyue_hospital` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `name` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `province` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `city` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `area` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `lng` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `lat` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_hospital
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_hospital` VALUES ('8', '107', 'xxx医院', '山东省', '济南市', '历城区', '117.06974', '36.689908', '', '0');
INSERT INTO `ims_weihu_yuyue_hospital` VALUES ('9', '107', 'yyy医院', '山东省', '济南市', '历城区', '117.092744', '36.687126', '', '0');

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_kscate`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_kscate`;
CREATE TABLE `ims_weihu_yuyue_kscate` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `name` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `hid` int(9) DEFAULT NULL,
  `sjid` int(9) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `ysopenid` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_kscate
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('16', '107', '中医科', '8', '0', '', '0', null);
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('17', '107', '西医科', '8', '0', '', '0', null);
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('18', '107', '针灸室', '8', '16', '', '0', null);
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('19', '107', '理疗室', '8', '16', '', '0', 'oZ71uxH-S4lOMLtOhoGXeugbjVz0');
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('20', '107', '打针室', '8', '17', '', '0', null);
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('21', '107', '手术室', '8', '17', '', '0', null);
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('22', '107', 'a', '9', '0', '', '0', null);
INSERT INTO `ims_weihu_yuyue_kscate` VALUES ('23', '107', 'b', '9', '22', '', '0', null);

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_member`;
CREATE TABLE `ims_weihu_yuyue_member` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `openid` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `nickname` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `mobile` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `createtime` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `birthday` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `ismanage` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_member
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_member` VALUES ('5', '107', 'oZ71uxH-S4lOMLtOhoGXeugbjVz0', '案山子', 'https://thirdwx.qlogo.cn/mmopen/m106lHrHg1qF2fmQLIyKWweUDkbzAKic22Eib7fjxa12DiaU5M2tib3eqHJB1PelbPNY03d4scibtSHBs9qTWaUhWw7TlUfnwibaPg/132', '杨翀', '15253379480', '', '1571284355', '775152000', '1', '1');
INSERT INTO `ims_weihu_yuyue_member` VALUES ('6', '107', 'oZ71uxLOO2dCeiedyK5EO62Li5ms', '公众号小程序开发朋友圈广告', 'https://thirdwx.qlogo.cn/mmopen/Q3auHgzwzM40GFrlh1oQtH2fsficD1mf2XH0cgibA9ttnSo3uqicUnBGyCtibfKV5qkD0rBDW2MoVje78rQ7yWWtP9Kgc3BjqmFjvLiaZZXzTqFQ/132', null, null, '', '1571623049', null, '1', null);
INSERT INTO `ims_weihu_yuyue_member` VALUES ('7', '107', 'oZ71uxCa-140p-IWdUW-VScs9L6U', 'A公众号，小程序，网站制作和运营', 'https://thirdwx.qlogo.cn/mmopen/m106lHrHg1qZtJH8V4GicKjMzfTlDeJVX4GDSxupKafQfZmpMY4F7jTRb8ZalnbdOnSCoV2OzUibApibB6DHz8AaaMRLS277DvX/132', '王海涛', '18560186018', null, '1571630189', '941644800', '1', null);
INSERT INTO `ims_weihu_yuyue_member` VALUES ('8', '107', 'oZ71uxEgVGNCXxMP-euLAsZU7Vpc', 'A小程序 公众号 网站定制开发运营', 'http://thirdwx.qlogo.cn/mmopen/vi_32/5pgt9NA3lCGrJ4fk3VuzJybaVW8JciaHEib5ZODnp7cxrX0Q6gO72MwkEAfCb8icDSQCscefoomeDZuD7njpqMPbg/132', null, null, null, '1571630271', null, '1', null);
INSERT INTO `ims_weihu_yuyue_member` VALUES ('9', '107', 'oZ71uxMDXzbM3cL59HaU-iIhKa4k', '风从东来，烧！', 'https://thirdwx.qlogo.cn/mmopen/m106lHrHg1qZtJH8V4GicKglfasITlYyqE4cPibbKHicAjOLbicOc4c7DevAdlevxnf1o5WqbZ5bo1ZhNhTQsc8q3icibxx7PFaQlG/132', '叶增伟', '18660102383', null, '1571631476', '918144000', '1', null);
INSERT INTO `ims_weihu_yuyue_member` VALUES ('10', '107', 'oZ71uxKuUQZF4cIkof93npLOO-_Y', '薛海涛', 'https://thirdwx.qlogo.cn/mmopen/YCOL3hU8ffXdTN6dpsjEzKyBNW1WY7V314IDv9sNjtwibe3BSb7mBG0hyKVAYJD77KdkgoNK1nXLruPkiaib5cCgw/132', '薛克跑', '13656466666', null, '1571631524', '230832000', '1', null);
INSERT INTO `ims_weihu_yuyue_member` VALUES ('11', '107', 'oZ71uxElbrI9I-s8Lwo4kFjGlqF4', '光霞', 'https://thirdwx.qlogo.cn/mmopen/m106lHrHg1pnZbIcRqAHTM7vRshryuJA3J6ibM3In8dlORU0vKSricam3ZHLIVEHk36uFe45FTtEibrVnnKQZ5WGQ/132', null, null, null, '1571631625', null, '1', null);

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_member_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_member_log`;
CREATE TABLE `ims_weihu_yuyue_member_log` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `ordernum` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `mid` int(9) DEFAULT NULL,
  `hid` int(9) DEFAULT NULL,
  `ksid` int(9) DEFAULT NULL,
  `ksid2` int(9) DEFAULT NULL,
  `mcardid` int(9) DEFAULT NULL,
  `seektime` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `yytype` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `manageid` int(9) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `createtime` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_member_log
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_member_log` VALUES ('5', '107', 'MO1571295385', '5', '8', '17', '21', '16', '1571558102', '拔牙', '5', null, '1', '1571295385');

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_membercard`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_membercard`;
CREATE TABLE `ims_weihu_yuyue_membercard` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `mid` int(9) DEFAULT NULL,
  `cid` int(9) DEFAULT NULL,
  `name` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `daynum` int(9) DEFAULT NULL,
  `num` int(9) DEFAULT NULL,
  `snum` int(9) DEFAULT NULL,
  `price` float(9,2) DEFAULT NULL,
  `starttime` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `endtime` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `cardnum` varchar(155) COLLATE utf8_bin DEFAULT NULL,
  `type` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_membercard
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_membercard` VALUES ('16', '107', '5', '7', 'VIP卡', '365', '52', '51', '998.00', '1571291330', '1602827330', '', 'MC1571291330', '1');
INSERT INTO `ims_weihu_yuyue_membercard` VALUES ('17', '107', '5', '7', 'VIP卡', '365', '52', '52', '998.00', '1571292545', '1602828545', '', 'MC00000', '2');
INSERT INTO `ims_weihu_yuyue_membercard` VALUES ('18', '107', '5', '7', 'VIP卡', '365', '52', '52', '998.00', '1571292616', '1602828616', '', 'MC1571292616', '1');
INSERT INTO `ims_weihu_yuyue_membercard` VALUES ('19', '107', '5', '7', 'VIP卡', '365', '52', '52', '998.00', '1571292944', '1602828944', '', 'MC00001', '2');
INSERT INTO `ims_weihu_yuyue_membercard` VALUES ('20', '107', '5', '7', 'VIP卡', '365', '52', '52', '998.00', '1571293099', '1602829099', '', 'MC1571293099', '1');

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_set`;
CREATE TABLE `ims_weihu_yuyue_set` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `tmpid` varchar(155) COLLATE utf8_bin DEFAULT NULL,
  `yytype` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_set
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_set` VALUES ('2', '107', 'nHu4sKKH7DyhAywegeSBLL1BIv4eYJLRWfCmc-5uWUU', '体检，拔牙');

-- ----------------------------
-- Table structure for `ims_weihu_yuyue_shiming`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihu_yuyue_shiming`;
CREATE TABLE `ims_weihu_yuyue_shiming` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `uniacid` int(9) DEFAULT NULL,
  `mid` int(9) DEFAULT NULL,
  `idcardnum` varchar(125) COLLATE utf8_bin DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `createtime` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of ims_weihu_yuyue_shiming
-- ----------------------------
INSERT INTO `ims_weihu_yuyue_shiming` VALUES ('1', '107', '5', '370302199407261119', 'https://20190709.jnweishangjia.com/addons/weihu_yuyue/imgfile/if1781571624743.jpg', '0', '发光飞碟', '1571387840');
