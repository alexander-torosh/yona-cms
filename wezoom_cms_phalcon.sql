-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 11, 2014 at 05:02 PM
-- Server version: 5.5.35
-- PHP Version: 5.4.6-1ubuntu1.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wezoom_cms_phalcon`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`id`, `login`, `email`, `password`, `active`) VALUES
(1, 'admin', 'webtorua@gmail.com', '$2y$10$CTJO0gjTpRYa04D3D5zhZuk4mLUg76pqIgAyNDsk8QnKLSce01IP6', 1),
(2, 'wezoom', 'web@wezoom.net', '$2y$10$57iPKSCtKrTDdE9tyEPhnudWilZtZNp.bMpm0vXVikiHsV4kqQ6yu', 1);

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
('APPLICATION_ENV', 'development'),
('DEBUG_MODE', '1'),
('TECHNICAL_WORKS', '0');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `text` text,
  `meta_title` varchar(500) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `title`, `slug`, `text`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES
(1, 'Главная', 'index', '<h1>Стартовая страница</h1>\r\n<p>Каждый сайт, разработанный нашей веб-студией, является уникальным продуктом. <strong>Мы не используем</strong> бесплатные CMS-системы такие как Joomla, Wordpress, 1С-битрикс&nbsp;и т.п.</p>\r\n<p>Наша веб-студия, создавая сайты, применяет технологии PHP,&nbsp;<br /><strong>Zend Framework</strong>, <strong>Phalcon</strong>, ORM Doctrine, MySQL, MongoDb, JavaScript, jQuery, AngularJs, KnockoutJs, современную HTML5 верстку, CSS3 и др.</p>\r\n<h2>Подзаголовок</h2>\r\n<p>С радостью беремся за интересные и нестандартные проекты.</p>\r\n<p>Применение технологий, которые мы используем, позволяет нам создавать сайты стандарта Web 2.0. Наши продукты отличаются своей гибкостью, что дает широкие возможности при модернизации сайта в любой момент его создания или поддержки.</p>\r\n<h3>Под-подзаголовок</h3>\r\n<p>В процессе создания сайта мы оптимизируем его под будущее продвижение в поисковых системах. Это имеет значительное влияние на качество и скорость индексации сайта поисковиками (Google, Яндекс, Yahoo) и<strong> экономит Ваш бюджет</strong> при продвижении сайта.</p>\r\n<p>Наша веб-студия использует свой самописный движок, который имеет:</p>\r\n<ul>\r\n<li>Высокую безопасность, надежность, быстродействие и легко масштабируется</li>\r\n<li>Функциональную и удобную панель администрирования сайта\r\n<ul>\r\n<li>Вложенный уровень</li>\r\n<li>Еще один</li>\r\n</ul>\r\n</li>\r\n<li>Нормальный уровень</li>\r\n</ul>\r\n<p>Таблица</p>\r\n<table class="table" style="width: 100%;">\r\n<tbody>\r\n<tr><th>Заглавие</th><th>Заглавие</th><th>Заглавие</th></tr>\r\n<tr>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n</tr>\r\n<tr>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Числовой список:</p>\r\n<ol>\r\n<li>Первый</li>\r\n<li>Второй</li>\r\n<li>Третий</li>\r\n</ol>', 'Главная', '', '', '2014-08-03 15:18:47', '2014-08-03 22:26:45'),
(2, 'Контакты', 'contacts', '<p>Тел. (044) 221-65-78</p>\r\n<p>Email: web@wezoom.net</p>', 'Контакты', '', '', '2014-08-03 22:25:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `visible` enum('1','0') NOT NULL DEFAULT '1',
  `sortorder` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_image`
--

CREATE TABLE IF NOT EXISTS `project_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `publication`
--

CREATE TABLE IF NOT EXISTS `publication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('events','press') NOT NULL DEFAULT 'events',
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `text` text,
  `meta_title` varchar(500) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `preview_inner` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `sortorder` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
-- Constraints for dumped tables
--

--
-- Constraints for table `project_image`
--
ALTER TABLE `project_image`
  ADD CONSTRAINT `project_image_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
