-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 20, 2015 at 12:31 AM
-- Server version: 5.5.46-0ubuntu0.14.04.2-log
-- PHP Version: 5.6.16-2+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yona-cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` enum('journalist','editor','admin') NOT NULL DEFAULT 'journalist',
  `login` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`id`, `role`, `login`, `email`, `name`, `password`, `active`) VALUES
(1, 'admin', 'admin', 'web@wezoom.net', 'Admin Name', '$2y$10$IgvGXdrkaRpuXnQLcpva3ebuRdNqbcY7NvlS9aluVQIgHWLf1bIMa', 1),
(2, 'admin', 'yona', 'yona@wezoom.net', 'Yona CMS User', '$2y$10$2UUYmTf4f13el.b5K69WmeijY6E/nY4.hRYaokNe/lfyfvJ3Bz05O', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_configuration`
--

CREATE TABLE IF NOT EXISTS `cms_configuration` (
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_configuration`
--

INSERT INTO `cms_configuration` (`key`, `value`) VALUES
('ADMIN_EMAIL', 'webmaster@localhost'),
('DEBUG_MODE', '1'),
('DISPLAY_CHANGELOG', '1'),
('PROFILER', '1'),
('TECHNICAL_WORKS', '0'),
('WIDGETS_CACHE', '1');

-- --------------------------------------------------------

--
-- Table structure for table `cms_javascript`
--

CREATE TABLE IF NOT EXISTS `cms_javascript` (
  `id` varchar(20) NOT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_javascript`
--

INSERT INTO `cms_javascript` (`id`, `text`) VALUES
('body', '<!-- custom javascript code or any html -->'),
('head', '<!-- custom javascript code or any html -->');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` varchar(10) NOT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `url` varchar(20) DEFAULT NULL,
  `sortorder` int(11) DEFAULT NULL,
  `primary` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `iso` (`iso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `iso`, `locale`, `name`, `short_name`, `url`, `sortorder`, `primary`) VALUES
(1, 'ru', 'ru_RU', 'Русский', 'Рус', 'ru', 3, '0'),
(2, 'en', 'en_EN', 'English', 'Eng', 'en', 1, '1'),
(3, 'uk', 'uk_UA', 'Українська', 'Укр', 'uk', 2, '0');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `root` enum('top') NOT NULL DEFAULT 'top',
  `parent_id` int(11) DEFAULT NULL,
  `work_title` varchar(255) DEFAULT NULL,
  `depth` tinyint(2) NOT NULL DEFAULT '0',
  `left_key` int(11) DEFAULT NULL,
  `right_key` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`work_title`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `head_title` varchar(500) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `text` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `slug`, `title`, `head_title`, `meta_description`, `meta_keywords`, `text`, `created_at`, `updated_at`) VALUES
(1, 'index', 'Homepage', 'Homepage', 'Description of homepage', 'homepage, keywords', '<h1>Yona CMS</h1>\r\n<p>Yona CMS - open source content management system. Written in <a href="http://phalconphp.com/" target="_blank">Phalcon PHP Framework</a>&nbsp;(version 1.3.x).</p>\r\n<p>Has a convenient modular structure. It is intended for the design of simple sites, and major portals and web applications. Thanks to its simple configuration and architecture, can be easily modified for any task.</p>\r\n<p>The official repository on&nbsp;<a href="https://github.com/oleksandr-torosh/yona-cms" target="_blank">Github</a></p>\r\n<h2>Subtitle</h2>\r\n<p>Proin aliquet eros vel magna semper facilisis. Nunc tellus urna, bibendum vitae malesuada vel, molestie non lectus. Suspendisse sit amet ante arcu. Maecenas interdum eu neque eu dapibus. Sed maximus elementum tortor at dapibus. Phasellus rhoncus odio vel suscipit dapibus. Nullam sed luctus mauris. Nunc blandit vitae nisl at malesuada. Sed ac est ut diam hendrerit sodales quis et massa. Proin aliquet vitae massa luctus ultricies. Nullam accumsan leo nibh, non varius tortor elementum auctor. Fusce sollicitudin a dui porttitor euismod. Ut at iaculis neque, nec finibus diam. Integer pharetra vehicula urna vitae imperdiet.</p>\r\n<h3>sub-subtitle</h3>\r\n<p>List:</p>\r\n<ul>\r\n<li>First item</li>\r\n<li>Second item<br />\r\n<ul>\r\n<li>Inner level of second item</li>\r\n<li>Another one</li>\r\n</ul>\r\n</li>\r\n<li>Third item</li>\r\n</ul>\r\n<p>Table</p>\r\n<table class="table" style="width: 100%;">\r\n<tbody>\r\n<tr><th>Header</th><th>Header</th><th>Header</th></tr>\r\n<tr>\r\n<td>Text in cell1</td>\r\n<td>Text in cell2</td>\r\n<td>Text in cell3</td>\r\n</tr>\r\n<tr>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Decimal list:</p>\r\n<ol>\r\n<li>First</li>\r\n<li>Second</li>\r\n<li>Third</li>\r\n</ol>', '2014-08-03 15:18:47', '2015-12-20 00:16:39'),
(2, 'contacts', NULL, NULL, NULL, NULL, NULL, '2014-08-03 22:25:13', '2015-06-18 16:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `publication`
--

CREATE TABLE IF NOT EXISTS `publication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `preview_inner` enum('1','0') DEFAULT '1',
  `preview_src` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `publication`
--

INSERT INTO `publication` (`id`, `type_id`, `slug`, `created_at`, `updated_at`, `date`, `preview_inner`, `preview_src`) VALUES
(1, 1, 'phalcon-132-released', '2014-08-22 10:33:26', '2015-06-26 16:48:36', '2014-08-19 00:00:00', '0', 'img/original/publication/0/1.jpg'),
(2, 1, 'phalcon-community-hangout', '2014-08-22 10:42:08', '2015-06-26 16:48:44', '2014-08-21 00:00:00', '1', 'img/original/publication/0/2.jpg'),
(3, 2, 'builtwith-phalcon', '2014-11-05 18:00:20', '2015-06-26 16:48:53', '2014-11-05 00:00:00', '1', 'img/original/publication/0/3.jpg'),
(4, 2, 'vtoraya-statya', '2014-11-06 18:23:17', '2015-06-26 16:49:02', '2014-11-06 00:00:00', '0', 'img/original/publication/0/4.jpg'),
(5, 1, 'new-modular-widgets-system', '2015-04-29 10:42:49', '2015-11-21 11:16:31', '2015-06-05 14:32:44', '0', 'img/original/publication/0/5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `publication_type`
--

CREATE TABLE IF NOT EXISTS `publication_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) DEFAULT NULL,
  `limit` int(4) DEFAULT NULL,
  `format` enum('list','grid') DEFAULT NULL,
  `display_date` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `publication_type`
--

INSERT INTO `publication_type` (`id`, `slug`, `limit`, `format`, `display_date`) VALUES
(1, 'news', 10, 'grid', '1'),
(2, 'articles', 10, 'list', '0');

-- --------------------------------------------------------

--
-- Table structure for table `seo_manager`
--

CREATE TABLE IF NOT EXISTS `seo_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `head_title` varchar(500) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `seo_text` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `seo_manager`
--

INSERT INTO `seo_manager` (`id`, `url`, `head_title`, `meta_description`, `meta_keywords`, `seo_text`, `created_at`, `updated_at`) VALUES
(1, '/news', 'Latest News', 'Greate latest and fresh news!', 'news, latest news, fresh news', '<p>Presenting your attention the latest news!</p>', '2014-09-30 10:39:23', '2015-07-02 11:28:57'),
(2, '/contacts.html', 'Yona CMS Contacts', '', '', '', '2015-05-21 16:33:14', '2015-07-02 11:19:40');

-- --------------------------------------------------------

--
-- Table structure for table `translate`
--

CREATE TABLE IF NOT EXISTS `translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(20) DEFAULT NULL,
  `phrase` varchar(500) DEFAULT NULL,
  `translation` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `translate`
--

INSERT INTO `translate` (`id`, `lang`, `phrase`, `translation`) VALUES
(1, 'ru', 'Ошибка валидации формы', 'Ошибка валидации формы'),
(2, 'ru', 'Подробнее', 'Подробнее'),
(3, 'ru', 'Назад к перечню публикаций', 'Назад к перечню публикаций'),
(4, 'ru', 'SITE NAME', 'Yona CMS Русская версия'),
(5, 'ru', 'Главная', 'Главная'),
(6, 'ru', 'Новости', 'Новости'),
(7, 'ru', 'Контакты', 'Контакты'),
(8, 'en', 'Ошибка валидации формы', 'Form validation fails'),
(9, 'en', 'Подробнее', 'Read more'),
(10, 'en', 'Назад к перечню публикаций', 'Back to the publications list'),
(11, 'en', 'SITE NAME', 'Yona CMS'),
(12, 'en', 'Главная', 'Home'),
(13, 'en', 'Новости', 'News'),
(14, 'en', 'Контакты', 'Contacts'),
(15, 'uk', 'Ошибка валидации формы', 'Помилка валідації форми'),
(16, 'uk', 'Подробнее', 'Детальніше'),
(17, 'uk', 'Назад к перечню публикаций', 'Повернутись до переліку публікацій'),
(18, 'uk', 'SITE NAME', 'Yona CMS Українська версія'),
(19, 'uk', 'Главная', 'Головна'),
(20, 'uk', 'Новости', 'Новини'),
(21, 'uk', 'Контакты', 'Контакти'),
(22, 'ru', 'Статьи', 'Статьи'),
(23, 'en', 'Статьи', 'Articles'),
(24, 'uk', 'Статьи', 'Статті'),
(25, 'en', 'Home', 'Home'),
(26, 'en', 'News', 'News'),
(27, 'en', 'Articles', 'Articles'),
(28, 'en', 'Contacts', 'Contacts'),
(29, 'en', 'Admin', 'Admin'),
(30, 'en', 'YonaCms Admin Panel', 'YonaCms Admin Panel'),
(31, 'en', 'Back к перечню публикаций', 'Back to publications list'),
(32, 'en', 'Страница №', 'Page num.'),
(33, 'ru', 'Home', 'Главная'),
(34, 'ru', 'News', 'Новости'),
(35, 'ru', 'Articles', 'Статьи'),
(36, 'ru', 'Contacts', 'Контакты'),
(37, 'ru', 'Admin', 'Админка'),
(38, 'ru', 'YonaCms Admin Panel', 'Административная панель YonaCms'),
(39, 'ru', 'Back к перечню публикаций', 'Назад к перечню публикаций'),
(40, 'ru', 'Страница №', 'Страница №'),
(41, 'uk', 'Home', 'Головна'),
(42, 'uk', 'News', 'Новини'),
(43, 'uk', 'Articles', 'Статті'),
(44, 'uk', 'Contacts', 'Контакти'),
(45, 'uk', 'Admin', 'Адмінка'),
(46, 'uk', 'YonaCms Admin Panel', 'Адміністративна панель YonaCms'),
(47, 'uk', 'Back к перечню публикаций', 'Назад до переліку публікацій'),
(48, 'uk', 'Страница №', 'Сторінка №'),
(49, 'en', 'Полная версия', 'Full version'),
(50, 'en', 'Мобильная версия', 'Mobile version'),
(51, 'en', 'Services', 'Services'),
(52, 'en', 'Printing', 'Printing'),
(53, 'en', 'Design', 'Design'),
(54, 'ru', 'Полная версия', 'Полная версия'),
(55, 'ru', 'Мобильная версия', 'Мобильная версия'),
(56, 'ru', 'Services', 'Services'),
(57, 'ru', 'Printing', 'Printing'),
(58, 'ru', 'Design', 'Design'),
(59, 'uk', 'Полная версия', 'Повна версія'),
(60, 'uk', 'Мобильная версия', 'Мобільна версія'),
(61, 'uk', 'Services', 'Services'),
(62, 'uk', 'Printing', 'Printing'),
(63, 'uk', 'Design', 'Design'),
(64, 'en', 'Latest news', 'Latest news'),
(65, 'ru', 'Latest news', 'Последние новости'),
(66, 'uk', 'Latest news', 'Останні новини'),
(67, 'en', 'Entries not found', 'Entries not found'),
(68, 'en', 'Back to publications list', 'Back to publications list'),
(69, 'uk', 'Entries not found', 'Записів не знайдено'),
(70, 'uk', 'Back to publications list', 'Повернутись до переліку публікацій'),
(71, 'ru', 'Entries not found', 'Записи не найдены'),
(72, 'ru', 'Back to publications list', 'Обратно к перечню публикаций');

-- --------------------------------------------------------

--
-- Table structure for table `tree_category`
--

CREATE TABLE IF NOT EXISTS `tree_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `root` enum('articles','news') NOT NULL DEFAULT 'articles',
  `parent_id` int(11) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `depth` tinyint(2) NOT NULL DEFAULT '0',
  `left_key` int(11) DEFAULT NULL,
  `right_key` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `tree_category`
--

INSERT INTO `tree_category` (`id`, `root`, `parent_id`, `slug`, `depth`, `left_key`, `right_key`, `created_at`, `updated_at`) VALUES
(15, 'articles', NULL, 'computers', 1, 2, 7, '2015-05-19 16:46:38', '2015-05-20 13:31:24'),
(16, 'articles', NULL, 'software', 1, 14, 21, '2015-05-19 16:47:32', '2015-05-20 13:31:25'),
(17, 'articles', NULL, 'gadgets', 1, 8, 13, '2015-05-19 16:47:45', '2015-05-20 13:31:25'),
(18, 'articles', 16, 'microsoft', 2, 17, 18, '2015-05-19 17:23:44', '2015-05-20 13:31:25'),
(19, 'articles', 16, 'oracle', 2, 19, 20, '2015-05-19 17:24:00', '2015-05-20 13:31:25'),
(20, 'articles', 16, 'google', 2, 15, 16, '2015-05-19 17:24:24', '2015-05-20 13:31:25'),
(21, 'articles', 15, 'netbooks', 2, 3, 4, '2015-05-19 17:24:49', '2015-05-20 13:31:25'),
(22, 'articles', 15, 'laptops', 2, 5, 6, '2015-05-19 17:30:49', '2015-05-20 13:31:25'),
(23, 'articles', 17, 'smartpfone', 2, 9, 10, '2015-05-19 17:32:06', '2015-05-20 13:31:25'),
(24, 'articles', 17, 'tablet', 2, 11, 12, '2015-05-19 17:32:53', '2015-05-20 13:31:25'),
(25, 'news', NULL, 'world', 1, 2, 3, '2015-05-19 17:33:04', '2015-05-20 15:24:45'),
(26, 'news', NULL, 'business', 1, 6, 11, '2015-05-19 17:33:11', '2015-05-20 15:24:45'),
(27, 'news', NULL, 'politics', 1, 4, 5, '2015-05-19 17:33:16', '2015-05-20 15:24:45'),
(28, 'news', 26, 'real-estate', 2, 7, 8, '2015-05-19 17:33:30', '2015-05-20 15:24:45'),
(29, 'news', 26, 'investitions', 2, 9, 10, '2015-05-19 17:33:54', '2015-05-20 15:24:45'),
(30, 'news', NULL, 'life', 1, 12, 17, '2015-05-20 15:24:05', '2015-05-20 15:24:45'),
(31, 'news', 30, 'health', 2, 13, 14, '2015-05-20 15:24:22', '2015-05-20 15:24:45'),
(32, 'news', 30, 'family', 2, 15, 16, '2015-05-20 15:24:42', '2015-05-20 15:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `widget`
--

CREATE TABLE IF NOT EXISTS `widget` (
  `id` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `html` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `widget`
--

INSERT INTO `widget` (`id`, `title`, `html`) VALUES
('phone', 'Phone in header', '<div class="phone">+1 (001) 555-44-33</div>');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `publication`
--
ALTER TABLE `publication`
  ADD CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `publication_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translate`
--
ALTER TABLE `translate`
  ADD CONSTRAINT `translate_ibfk_1` FOREIGN KEY (`lang`) REFERENCES `language` (`iso`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `tree_category`
--
ALTER TABLE `tree_category`
  ADD CONSTRAINT `tree_category_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `tree_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
