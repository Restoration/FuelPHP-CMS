-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: 2017 年 3 月 16 日 19:45
-- サーバのバージョン： 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dtb_fuelcms`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `mtr_category`
--

CREATE TABLE `mtr_category` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'タグID',
  `category_name` varchar(60) NOT NULL COMMENT 'タグ名',
  `category_slug` varchar(120) NOT NULL COMMENT 'タグスラッグ',
  `category_description` text NOT NULL COMMENT 'タグ説明',
  `category_parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'カテゴリーの親ID',
  `registerdate` datetime NOT NULL COMMENT '作成日',
  `modified` datetime NOT NULL COMMENT '変更日',
  `dltflg` int(11) NOT NULL COMMENT '削除フラグ',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `mtr_tag`
--

CREATE TABLE `mtr_tag` (
  `tag_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'タグID',
  `tag_name` varchar(60) NOT NULL COMMENT 'タグ名',
  `tag_slug` varchar(120) NOT NULL COMMENT 'タグスラッグ',
  `tag_description` text NOT NULL COMMENT 'タグ説明',
  `registerdate` datetime NOT NULL COMMENT '作成日',
  `modified` datetime NOT NULL COMMENT '変更日',
  `dltflg` int(11) NOT NULL COMMENT '削除フラグ',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `tbl_category`
--

CREATE TABLE `tbl_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'カテゴリーID',
  `category_name` varchar(60) NOT NULL COMMENT 'カテゴリーネーム',
  `registerdate` date NOT NULL COMMENT '作成日',
  `modified` date NOT NULL COMMENT '更新日',
  `dltflg` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `tbl_file`
--

CREATE TABLE `tbl_file` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ファイルID',
  `file_name` varchar(100) NOT NULL COMMENT 'ファイル名',
  `file_path` varchar(100) NOT NULL COMMENT 'ファイルまでのパス',
  `file_extension` char(10) NOT NULL COMMENT 'ファイルの拡張子',
  `file_saved_as` varchar(255) NOT NULL COMMENT '変更後の名前',
  `file_saved_to` varchar(255) NOT NULL COMMENT '保存先のパス',
  `file_saved_abs_to` varchar(255) NOT NULL,
  `registerdate` datetime NOT NULL COMMENT '作成日',
  `modified` datetime NOT NULL COMMENT '変更日',
  `dltflg` int(11) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=199 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `tbl_post`
--

CREATE TABLE `tbl_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(20) NOT NULL,
  `post_category` int(11) NOT NULL DEFAULT '0',
  `post_message` text,
  `registerdate` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `dltflg` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_login` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `login_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `profile_fields` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) NOT NULL,
  `dltflg` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `trn_category`
--

CREATE TABLE `trn_category` (
  `category_rel_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `post_id` int(11) DEFAULT NULL COMMENT '投稿記事ID',
  `category_id` int(11) DEFAULT NULL COMMENT 'タグID',
  PRIMARY KEY (`category_rel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `trn_tag`
--

CREATE TABLE `trn_tag` (
  `tag_rel_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `post_id` int(11) DEFAULT NULL COMMENT '投稿記事ID',
  `tag_id` int(11) DEFAULT NULL COMMENT 'タグID',
  PRIMARY KEY (`tag_rel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
