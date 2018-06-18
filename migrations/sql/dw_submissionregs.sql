/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost
 Source Database       : ca_hc4l

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 03/16/2018 17:02:32 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `dw_submissionregs`
-- ----------------------------
DROP TABLE IF EXISTS `dw_submissionregs`;
CREATE TABLE `dw_submissionregs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `submissionId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dwSubmittedAt` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dwSubmittedAt_u` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dwUpdatedAt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dwUpdatedAt_u` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isValid` tinyint(4) NOT NULL DEFAULT '0',
  `datasenderId` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cleanFlag` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pushIdnrStatus` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
