CREATE DATABASE  IF NOT EXISTS `booktalk_cms` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `booktalk_cms`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: booktalk_cms
-- ------------------------------------------------------
-- Server version	5.7.17-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bt_activity`
--

DROP TABLE IF EXISTS `bt_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_activity` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `book_id` bigint(19) unsigned NOT NULL,
  `component` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `item_id` bigint(19) unsigned NOT NULL,
  `secondary_item_id` bigint(19) unsigned NOT NULL,
  `hide_sitewide` tinyint(1) unsigned NOT NULL,
  `created_dt` datetime NOT NULL,
  `count_hit` int(10) unsigned NOT NULL COMMENT '조회 수 ',
  `count_like` int(10) unsigned NOT NULL COMMENT '추천 수 ',
  `count_comment` int(10) unsigned NOT NULL COMMENT '댓글 수 ',
  `is_deleted` varchar(10) NOT NULL DEFAULT '0' COMMENT '삭제여부 ',
  `deleted_dt` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bt_books_activity_bt_users1_idx` (`user_id`),
  KEY `fk_bt_activity_bt_books1_idx` (`book_id`),
  CONSTRAINT `fk_bt_activity_bt_books1` FOREIGN KEY (`book_id`) REFERENCES `bt_books` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bt_books_activity_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_activity_comment`
--

DROP TABLE IF EXISTS `bt_activity_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_activity_comment` (
  `comment_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `comment_author` varchar(100) NOT NULL,
  `comment_author_ip` varchar(100) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_content` text NOT NULL,
  `comment_read` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '게시글 등록자가 댓글 읽었는지 여부 ',
  `activity_id` bigint(19) unsigned NOT NULL,
  `comment_user_id` bigint(19) unsigned NOT NULL,
  `comment_user_level` int(10) unsigned NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `fk_bt_activity_comment_bt_activity1_idx` (`activity_id`),
  CONSTRAINT `fk_bt_activity_comment_bt_activity1` FOREIGN KEY (`activity_id`) REFERENCES `bt_activity` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_activity_meta`
--

DROP TABLE IF EXISTS `bt_activity_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_activity_meta` (
  `meta_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(19) unsigned NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `fk_bt_activity_meta_bt_activity1_idx` (`activity_id`),
  CONSTRAINT `fk_bt_activity_meta_bt_activity1` FOREIGN KEY (`activity_id`) REFERENCES `bt_activity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_app_mobile_auth`
--

DROP TABLE IF EXISTS `bt_app_mobile_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_app_mobile_auth` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(12) NOT NULL,
  `authcode` varchar(4) NOT NULL,
  `created_dt` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_banned_users`
--

DROP TABLE IF EXISTS `bt_banned_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_banned_users` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `banned_user_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_banned_users_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_banned_users_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='차단한 회원 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_banner`
--

DROP TABLE IF EXISTS `bt_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_banner` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `bnr_title` varchar(100) NOT NULL,
  `bnr_url` text NOT NULL,
  `bnr_target` varchar(10) NOT NULL,
  `hide_or_show` varchar(10) NOT NULL,
  `bnr_file_path` varchar(250) NOT NULL,
  `bnr_file_url` varchar(250) NOT NULL,
  `bnr_file_name` varchar(100) NOT NULL,
  `bnr_created` datetime NOT NULL,
  `bnr_order` bigint(19) unsigned NOT NULL COMMENT '정렬순서 ',
  `bnr_from` date DEFAULT NULL,
  `bnr_to` date DEFAULT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_banner_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_banner_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_books`
--

DROP TABLE IF EXISTS `bt_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_books` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `book_title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `normal_price` int(10) unsigned NOT NULL,
  `discount_rate` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '할인율 ',
  `sale_price` int(10) unsigned NOT NULL,
  `cover_img` varchar(150) DEFAULT NULL,
  `epub_path` varchar(1000) NOT NULL,
  `epub_name` varchar(1000) DEFAULT NULL,
  `period_from` date DEFAULT NULL,
  `period_to` date DEFAULT NULL,
  `category_first` bigint(19) unsigned DEFAULT NULL,
  `category_second` bigint(19) unsigned DEFAULT NULL,
  `category_third` bigint(19) unsigned DEFAULT NULL,
  `is_pkg` varchar(5) NOT NULL DEFAULT 'N' COMMENT '단품 or Set',
  `is_free` varchar(5) NOT NULL DEFAULT 'N',
  `upload_type` varchar(20) NOT NULL COMMENT '등록형태 ',
  `book_status` int(10) unsigned NOT NULL COMMENT '처리상태 ',
  `created_dt` datetime NOT NULL,
  `req_edit_dt` datetime DEFAULT NULL COMMENT '수정요청시간 ',
  `req_del_dt` datetime DEFAULT NULL COMMENT '삭제요청시간 ',
  `user_id` bigint(19) unsigned NOT NULL,
  `publisher_id` bigint(19) unsigned NOT NULL COMMENT '출판사 아이디, 1인 작가의 경우 북톡출판사 아이디 ',
  PRIMARY KEY (`ID`),
  KEY `fk_bt_books_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_books_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_books_meta`
--

DROP TABLE IF EXISTS `bt_books_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_books_meta` (
  `bmeta_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint(19) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`bmeta_id`),
  KEY `fk_bt_books_meta_bt_books1_idx` (`book_id`),
  CONSTRAINT `fk_bt_books_meta_bt_books1` FOREIGN KEY (`book_id`) REFERENCES `bt_books` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=382 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_coupon`
--

DROP TABLE IF EXISTS `bt_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_coupon` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_name` varchar(200) NOT NULL,
  `coupon_type` varchar(20) NOT NULL,
  `coupon_desc` text NOT NULL,
  `period_from` date DEFAULT NULL,
  `period_to` date DEFAULT NULL,
  `discount_type` varchar(20) NOT NULL,
  `discount_amount` int(10) unsigned NOT NULL COMMENT '할인 금액 ',
  `discount_rate` tinyint(3) unsigned NOT NULL COMMENT '할인율 ',
  `item_price_min` int(10) unsigned NOT NULL COMMENT '최소 사용 가능 금액 ',
  `item_price_max` int(10) unsigned NOT NULL COMMENT '최대 할인 가능 금액 ',
  `related_publisher` bigint(19) unsigned DEFAULT NULL COMMENT '쿠폰 적용 출판사 ',
  `created_dt` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_curation`
--

DROP TABLE IF EXISTS `bt_curation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_curation` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `curation_title` varchar(200) NOT NULL,
  `cover_img` text NOT NULL,
  `curation_order` int(11) NOT NULL,
  `curation_status` int(11) NOT NULL,
  `curator_level` int(10) unsigned NOT NULL COMMENT 'User Level',
  `curation_meta` longtext NOT NULL COMMENT 'Book IDs',
  `created_dt` datetime NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  `hit_count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_curation_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_curation_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_friends`
--

DROP TABLE IF EXISTS `bt_friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_friends` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `initiator_user_id` bigint(19) unsigned NOT NULL,
  `friend_user_id` bigint(19) unsigned NOT NULL,
  `is_confirmed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_limited` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_favorite` tinyint(4) NOT NULL DEFAULT '0' COMMENT '즐겨찾기 ',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_mail_logs`
--

DROP TABLE IF EXISTS `bt_mail_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_mail_logs` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `message` longtext NOT NULL COMMENT '메 내용',
  `mail_type` varchar(45) NOT NULL COMMENT '메일 성격 ',
  `sent_dt` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_mail_logs_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_mail_logs_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='메일 발송 로그 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_memo_logs`
--

DROP TABLE IF EXISTS `bt_memo_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_memo_logs` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `memo` text NOT NULL,
  `created_by` varchar(45) NOT NULL,
  `created_dt` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_memo_logs_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_memo_logs_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='관리자가 작성한 사용자 메모 로그 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_options`
--

DROP TABLE IF EXISTS `bt_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_options` (
  `option_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_order`
--

DROP TABLE IF EXISTS `bt_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_order` (
  `order_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `order_status` tinyint(4) NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  `total_amount` int(10) unsigned NOT NULL COMMENT '합계금액 ',
  `coupon_discount` int(11) DEFAULT NULL,
  `discount_amount` int(10) unsigned NOT NULL COMMENT '실제 판매금액 ',
  `cybercash_paid` int(10) unsigned NOT NULL COMMENT '사용한 CASH',
  `cyberpoint_paid` int(10) unsigned NOT NULL COMMENT '사용한 적립금 ',
  `total_paid` int(10) unsigned NOT NULL COMMENT '실 결제금액 ',
  `total_refund_amount` int(10) unsigned DEFAULT NULL COMMENT '총 환불금액 ',
  `cybercash_refunded` int(11) DEFAULT NULL COMMENT 'CASH 환불금액 ',
  `cyberpoint_refunded` int(11) DEFAULT NULL COMMENT '적립금 환불금액 ',
  `total_refunded` int(10) unsigned DEFAULT NULL COMMENT '실 환불금액 ',
  `created_dt` datetime NOT NULL,
  `updated_dt` datetime NOT NULL,
  `remote_ip` varchar(45) NOT NULL,
  `coupon_code` varchar(100) DEFAULT NULL COMMENT '쿠폰코드 ',
  `coupon_name` text COMMENT '쿠폰명 ',
  `refunded_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `fk_bt_order_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_order_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_order_item`
--

DROP TABLE IF EXISTS `bt_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_order_item` (
  `item_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(19) unsigned NOT NULL,
  `book_id` bigint(19) unsigned NOT NULL,
  `original_price` int(10) unsigned NOT NULL,
  `sale_price` int(10) unsigned NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `epub_url` varchar(1000) NOT NULL COMMENT 'private epub URL',
  `book_dc_rate` tinyint(3) unsigned NOT NULL COMMENT '책 다운로드 완료 시각 ',
  `book_down_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `fk_bt_order_item_bt_order1_idx` (`order_id`),
  KEY `fk_bt_order_item_bt_books1_idx` (`book_id`),
  CONSTRAINT `fk_bt_order_item_bt_books1` FOREIGN KEY (`book_id`) REFERENCES `bt_books` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bt_order_item_bt_order1` FOREIGN KEY (`order_id`) REFERENCES `bt_order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_posts`
--

DROP TABLE IF EXISTS `bt_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_posts` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `post_name` varchar(200) NOT NULL,
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_date` datetime NOT NULL,
  `post_parent` bigint(19) unsigned NOT NULL,
  `post_status` varchar(20) NOT NULL,
  `post_password` varchar(20) NOT NULL,
  `post_user_id` bigint(19) unsigned NOT NULL,
  `post_email` varchar(60) NOT NULL,
  `post_order` int(10) unsigned NOT NULL,
  `post_type` varchar(45) NOT NULL,
  `post_type_secondary` varchar(45) NOT NULL,
  `post_type_area` set('app','web','post','chat') NOT NULL COMMENT 'App/Web/답벼락/채팅 ',
  `post_modified` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_posts_meta`
--

DROP TABLE IF EXISTS `bt_posts_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_posts_meta` (
  `meta_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(19) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `fk_bt_posts_meta_bt_posts1_idx` (`post_id`),
  CONSTRAINT `fk_bt_posts_meta_bt_posts1` FOREIGN KEY (`post_id`) REFERENCES `bt_posts` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_posts_qnas`
--

DROP TABLE IF EXISTS `bt_posts_qnas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_posts_qnas` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `post_type` varchar(45) NOT NULL,
  `post_name` varchar(200) NOT NULL,
  `post_content` longtext NOT NULL,
  `post_title` varchar(200) NOT NULL,
  `post_date` datetime NOT NULL,
  `post_term_id` bigint(19) unsigned NOT NULL COMMENT '카테고리 ID',
  `post_user_id` bigint(19) unsigned NOT NULL,
  `post_status` varchar(20) NOT NULL,
  `post_attachment` text NOT NULL,
  `post_ans_title` varchar(200) DEFAULT NULL,
  `post_ans_content` longtext,
  `post_ans_attachment` text,
  `post_ans_user_id` bigint(19) unsigned DEFAULT NULL,
  `post_ans_date` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='질문 답변형 게시글 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_promotion`
--

DROP TABLE IF EXISTS `bt_promotion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_promotion` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `prom_title` varchar(100) NOT NULL,
  `prom_content` mediumtext NOT NULL,
  `prom_type` varchar(20) NOT NULL,
  `user_count` varchar(10) NOT NULL COMMENT '수신자 수 ',
  `created_dt` datetime NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_publisher_book`
--

DROP TABLE IF EXISTS `bt_publisher_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_publisher_book` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `publisher_id` bigint(19) unsigned NOT NULL,
  `period_from` date NOT NULL,
  `period_to` date NOT NULL,
  `publisher_order` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  `publisher_ci` text,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_publisher_book_bt_users1_idx` (`publisher_id`),
  CONSTRAINT `fk_bt_publisher_book_bt_users1` FOREIGN KEY (`publisher_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Main 출판사 리스트 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_session`
--

DROP TABLE IF EXISTS `bt_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_session` (
  `sess_id` varchar(100) NOT NULL,
  `sess_expiry` int(10) unsigned NOT NULL,
  `sess_data` mediumtext NOT NULL,
  `sess_created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_term_taxonomy`
--

DROP TABLE IF EXISTS `bt_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_term_taxonomy` (
  `term_taxonomy_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(19) unsigned NOT NULL,
  `taxonomy` varchar(32) NOT NULL,
  `description` longtext NOT NULL,
  `parent` bigint(19) unsigned NOT NULL,
  `count` bigint(20) NOT NULL,
  PRIMARY KEY (`term_taxonomy_id`),
  KEY `fk_bt_term_taxonomy_bt_terms1_idx` (`term_id`),
  CONSTRAINT `fk_bt_term_taxonomy_bt_terms1` FOREIGN KEY (`term_id`) REFERENCES `bt_terms` (`term_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_terms`
--

DROP TABLE IF EXISTS `bt_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_terms` (
  `term_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `term_group` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`term_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_user_book_read`
--

DROP TABLE IF EXISTS `bt_user_book_read`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_user_book_read` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `book_id` bigint(19) unsigned NOT NULL,
  `read_dt_from` datetime NOT NULL,
  `read_dt_to` datetime NOT NULL,
  `read_page_to` int(10) unsigned NOT NULL COMMENT '최종 읽은 페이지 No.',
  `epub_index` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_user_book_read_bt_users1_idx` (`user_id`),
  KEY `fk_bt_user_book_read_bt_books1_idx` (`book_id`),
  CONSTRAINT `fk_bt_user_book_read_bt_books1` FOREIGN KEY (`book_id`) REFERENCES `bt_books` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bt_user_book_read_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_user_cash_logs`
--

DROP TABLE IF EXISTS `bt_user_cash_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_user_cash_logs` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `cash_used` int(11) NOT NULL,
  `cash_total` int(11) NOT NULL,
  `cash_comment` mediumtext NOT NULL COMMENT '상세설명',
  `point_used` int(11) NOT NULL,
  `point_total` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_user_cash_logs_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_user_cash_logs_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8 COMMENT='캐시 충전 및 사용 내역';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_user_level_terms`
--

DROP TABLE IF EXISTS `bt_user_level_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_user_level_terms` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_level` int(10) unsigned DEFAULT NULL,
  `term_items` text,
  `meta_value` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='회원 등급별 메뉴 사용 권한 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_user_payment_list`
--

DROP TABLE IF EXISTS `bt_user_payment_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_user_payment_list` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `payment_amount` int(11) NOT NULL COMMENT '결제금액',
  `payment_method` varchar(40) NOT NULL,
  `payment_state` varchar(40) NOT NULL COMMENT '결제 상태',
  `point_amount` int(10) unsigned NOT NULL COMMENT '적립포인트 ',
  `created_dt` datetime NOT NULL COMMENT '신청날짜',
  `payment_dt` date NOT NULL COMMENT '결제완료 날짜',
  `meta_value` mediumtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bt_user_payment_list_bt_users1_idx` (`user_id`),
  CONSTRAINT `fk_bt_user_payment_list_bt_users1` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='결제 내역';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_users`
--

DROP TABLE IF EXISTS `bt_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_users` (
  `ID` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL,
  `user_pass` varchar(60) NOT NULL,
  `user_name` varchar(45) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `display_name` varchar(100) NOT NULL,
  `user_registered` datetime NOT NULL,
  `user_status` int(10) unsigned NOT NULL,
  `user_level` int(10) unsigned NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `birthday` varchar(10) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `residence` smallint(6) DEFAULT NULL,
  `last_school` smallint(6) DEFAULT NULL,
  `join_path` varchar(20) DEFAULT NULL COMMENT '가입경로 ',
  `last_login_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `user_login_UNIQUE` (`user_login`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='회원';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bt_users_meta`
--

DROP TABLE IF EXISTS `bt_users_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bt_users_meta` (
  `umeta_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `fk_bt_users_meta_bt_users_idx` (`user_id`),
  CONSTRAINT `fk_bt_users_meta_bt_users` FOREIGN KEY (`user_id`) REFERENCES `bt_users` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=357 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-13 11:31:00
