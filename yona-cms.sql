-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Квт 29 2015 р., 17:31
-- Версія сервера: 5.6.23
-- Версія PHP: 5.6.8-1~dotdeb+wheezy.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- БД: `yona-cms`
--

-- --------------------------------------------------------

--
-- Структура таблиці `admin_user`
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
-- Дамп даних таблиці `admin_user`
--

INSERT INTO `admin_user` (`id`, `login`, `email`, `password`, `active`) VALUES
(1, 'admin', 'web@wezoom.net', '$2y$10$IgvGXdrkaRpuXnQLcpva3ebuRdNqbcY7NvlS9aluVQIgHWLf1bIMa', 1),
(2, 'yona', 'yona@wezoom.net', '$2y$10$2UUYmTf4f13el.b5K69WmeijY6E/nY4.hRYaokNe/lfyfvJ3Bz05O', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `cms_configuration`
--

CREATE TABLE IF NOT EXISTS `cms_configuration` (
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `cms_configuration`
--

INSERT INTO `cms_configuration` (`key`, `value`) VALUES
('DEBUG_MODE', '1'),
('PROFILER', '1'),
('TECHNICAL_WORKS', '0'),
('WIDGETS_CACHE', '1');

-- --------------------------------------------------------

--
-- Структура таблиці `cms_javascript`
--

CREATE TABLE IF NOT EXISTS `cms_javascript` (
  `id` varchar(20) NOT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `cms_javascript`
--

INSERT INTO `cms_javascript` (`id`, `text`) VALUES
('body', '<!-- custom javascript code or any html -->'),
('head', '<!-- custom javascript code or any html -->');

-- --------------------------------------------------------

--
-- Структура таблиці `language`
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
-- Дамп даних таблиці `language`
--

INSERT INTO `language` (`id`, `iso`, `locale`, `name`, `short_name`, `url`, `sortorder`, `primary`) VALUES
(1, 'ru', 'ru_RU', 'Русский', 'Рус', 'ru', 2, '0'),
(2, 'en', 'en_EN', 'English', 'Eng', 'en', 1, '1'),
(3, 'uk', 'uk_UA', 'Українська', 'Укр', 'uk', 3, '0');

-- --------------------------------------------------------

--
-- Структура таблиці `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `page`
--

INSERT INTO `page` (`id`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'index', '2014-08-03 15:18:47', '2014-11-26 09:48:14'),
(2, 'contacts', '2014-08-03 22:25:13', '2014-11-26 09:37:59');

-- --------------------------------------------------------

--
-- Структура таблиці `page_translate`
--

CREATE TABLE IF NOT EXISTS `page_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foreign_id` int(11) NOT NULL,
  `lang` varchar(20) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Дамп даних таблиці `page_translate`
--

INSERT INTO `page_translate` (`id`, `foreign_id`, `lang`, `key`, `value`) VALUES
(1, 1, 'ru', 'title', 'Главная'),
(2, 1, 'ru', 'meta_title', 'Главная'),
(3, 1, 'ru', 'meta_description', 'meta-описание главной страницы'),
(4, 1, 'ru', 'meta_keywords', ''),
(5, 1, 'ru', 'text', '<h1>Yona CMS</h1>\r\n<p>Yona CMS - система управления контентом с открытым исходным кодом. Написана на <a href="http://phalconphp.com/" target="_blank">Phalcon PHP Framework</a>&nbsp;(версия 1.3.x).</p>\r\n<p>Имеет удобную модульную структуру. Предназначена для разработки как простых сайтов, так и крупных порталов и веб-приложений. Благодаря простой конфигурации и архитектуре, может быть легко модифицирована под любую задачу.</p>\r\n<p>Официальный репозиторий на&nbsp;<a href="https://github.com/oleksandr-torosh/yona-cms" target="_blank">Github</a></p>\r\n<h2>Подзаголовок</h2>\r\n<p>Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;</p>\r\n<h3>Под-подзаголовок</h3>\r\n<p>Список:</p>\r\n<ul>\r\n<li>Первый&nbsp;пункт</li>\r\n<li>Второй пукт<br />\r\n<ul>\r\n<li>Вложенный уровень второго пункта</li>\r\n<li>Еще один</li>\r\n</ul>\r\n</li>\r\n<li>Третий пункт</li>\r\n</ul>\r\n<p>Таблица</p>\r\n<table class="table" style="width: 100%;">\r\n<tbody>\r\n<tr><th>Заглавие</th><th>Заглавие</th><th>Заглавие</th></tr>\r\n<tr>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n</tr>\r\n<tr>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Числовой список:</p>\r\n<ol>\r\n<li>Первый</li>\r\n<li>Второй</li>\r\n<li>Третий</li>\r\n</ol>'),
(6, 1, 'en', 'title', 'Homepage'),
(7, 1, 'en', 'meta_title', 'Homepage'),
(8, 1, 'en', 'meta_description', 'meta-description of homepage'),
(9, 1, 'en', 'meta_keywords', ''),
(10, 1, 'en', 'text', '<h1>Yona CMS</h1>\r\n<p>Yona CMS - open source content management system. Written in <a href="http://phalconphp.com/" target="_blank">Phalcon PHP Framework</a>&nbsp;(version 1.3.x).</p>\r\n<p>Has a convenient modular structure. It is intended for the design of simple sites, and major portals and web applications. Thanks to its simple configuration and architecture, can be easily modified for any task.</p>\r\n<p>The official repository on&nbsp;<a href="https://github.com/oleksandr-torosh/yona-cms" target="_blank">Github</a></p>\r\n<h2>Subtitle</h2>\r\n<p>Proin aliquet eros vel magna semper facilisis. Nunc tellus urna, bibendum vitae malesuada vel, molestie non lectus. Suspendisse sit amet ante arcu. Maecenas interdum eu neque eu dapibus. Sed maximus elementum tortor at dapibus. Phasellus rhoncus odio vel suscipit dapibus. Nullam sed luctus mauris. Nunc blandit vitae nisl at malesuada. Sed ac est ut diam hendrerit sodales quis et massa. Proin aliquet vitae massa luctus ultricies. Nullam accumsan leo nibh, non varius tortor elementum auctor. Fusce sollicitudin a dui porttitor euismod. Ut at iaculis neque, nec finibus diam. Integer pharetra vehicula urna vitae imperdiet.</p>\r\n<h3>sub-subtitle</h3>\r\n<p>List:</p>\r\n<ul>\r\n<li>First item</li>\r\n<li>Second item<br />\r\n<ul>\r\n<li>Inner level of second item</li>\r\n<li>Another one</li>\r\n</ul>\r\n</li>\r\n<li>Third item</li>\r\n</ul>\r\n<p>Table</p>\r\n<table class="table" style="width: 100%;">\r\n<tbody>\r\n<tr><th>Header</th><th>Header</th><th>Header</th></tr>\r\n<tr>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n</tr>\r\n<tr>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Decimal list:</p>\r\n<ol>\r\n<li>First</li>\r\n<li>Second</li>\r\n<li>Third</li>\r\n</ol>'),
(11, 2, 'ru', 'title', 'Контакты'),
(12, 2, 'ru', 'meta_title', 'Контакты'),
(13, 2, 'ru', 'meta_description', ''),
(14, 2, 'ru', 'meta_keywords', ''),
(15, 2, 'ru', 'text', '<h2>Контакты</h2>\r\n<p>Email:&nbsp;oleksandr.torosh@wezoom.net</p>\r\n<p>Facebook:&nbsp;<a href="https://www.facebook.com/oleksandrtorosh" target="_blank">https://www.facebook.com/oleksandrtorosh</a></p>\r\n<p>VK:&nbsp;<a href="https://vk.com/webtor" target="_blank">https://vk.com/webtor</a></p>\r\n<p>Google+:&nbsp;<a href="https://plus.google.com/u/0/+OleksandrTorosh">https://plus.google.com/u/0/+OleksandrTorosh</a></p>\r\n<p>Github:&nbsp;<a href="https://github.com/oleksandr-torosh" target="_blank">https://github.com/oleksandr-torosh</a></p>\r\n<p>Адрес студии:&nbsp;<a href="http://wezoom.net/" target="_blank">http://wezoom.net</a></p>'),
(16, 2, 'en', 'title', 'Contacts'),
(17, 2, 'en', 'meta_title', 'Contacts'),
(18, 2, 'en', 'meta_description', ''),
(19, 2, 'en', 'meta_keywords', ''),
(20, 2, 'en', 'text', '<p>web@wezoom.net</p>'),
(21, 1, 'uk', 'title', 'Головна'),
(22, 1, 'uk', 'meta_title', 'Головна'),
(23, 1, 'uk', 'meta_description', 'meta-description головної сторінки'),
(24, 1, 'uk', 'meta_keywords', ''),
(25, 1, 'uk', 'text', '<h1>Yona CMS</h1>\r\n<p>Yona CMS - система керування&nbsp;контентом з відкритим&nbsp;програмним кодом. Написана на <a href="http://phalconphp.com/" target="_blank">Phalcon PHP Framework</a>&nbsp;(версія 1.3.x).</p>\r\n<p>Має зручну&nbsp;модульную структуру. Призначена для розробки як простих сайтів, так і великих&nbsp;порталів та веб-застосунків. Завдяки&nbsp;простій конфігурації і архитектурі, може бути легко модифікована під будь-яку&nbsp;задачу.</p>\r\n<p>Офіційний репозиторій на&nbsp;<a href="https://github.com/oleksandr-torosh/yona-cms" target="_blank">Github</a></p>\r\n<h2>Підзаголовок</h2>\r\n<p>З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;З''їж ще цих м''яких французьких булок і випий чаю.&nbsp;</p>\r\n<h3>Під-підзаголовок</h3>\r\n<p>Список:</p>\r\n<ul>\r\n<li>Перший&nbsp;пункт</li>\r\n<li>Другий&nbsp;пукт<br />\r\n<ul>\r\n<li>Вкладений рівень другого пункту</li>\r\n<li>Ще один</li>\r\n</ul>\r\n</li>\r\n<li>Третій пункт</li>\r\n</ul>\r\n<p>Таблиця</p>\r\n<table class="table" style="width: 100%;">\r\n<tbody>\r\n<tr><th>Заголовок</th><th>Заголовок</th><th>Заголовок</th></tr>\r\n<tr>\r\n<td>Текст в&nbsp;комірці</td>\r\n<td>Текст в&nbsp;комірці</td>\r\n<td>Текст в&nbsp;комірці</td>\r\n</tr>\r\n<tr>\r\n<td>Текст в&nbsp;комірці</td>\r\n<td>Текст в&nbsp;комірці</td>\r\n<td>Текст в&nbsp;комірці</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Числовий список:</p>\r\n<ol>\r\n<li>Перший</li>\r\n<li>Другий</li>\r\n<li>Третій</li>\r\n</ol>'),
(26, 2, 'uk', 'title', 'Контакти'),
(27, 2, 'uk', 'meta_title', 'Контакти'),
(28, 2, 'uk', 'meta_description', ''),
(29, 2, 'uk', 'meta_keywords', ''),
(30, 2, 'uk', 'text', '');

-- --------------------------------------------------------

--
-- Структура таблиці `publication`
--

CREATE TABLE IF NOT EXISTS `publication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `preview_inner` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп даних таблиці `publication`
--

INSERT INTO `publication` (`id`, `type_id`, `slug`, `created_at`, `updated_at`, `date`, `preview_inner`) VALUES
(1, 1, 'phalcon-132-released', '2014-08-22 10:33:26', '2014-11-26 09:54:15', '2014-08-19 00:00:00', '1'),
(2, 1, 'phalcon-community-hangout', '2014-08-22 10:42:08', '2014-11-26 09:52:58', '2014-08-21 00:00:00', '1'),
(3, 2, 'builtwith-phalcon', '2014-11-05 18:00:20', '2015-04-29 12:34:35', '2014-11-05 00:00:00', '1'),
(4, 2, 'vtoraya-statya', '2014-11-06 18:23:17', '2014-11-26 09:49:06', '2014-11-06 00:00:00', '0'),
(5, 1, 'new-modular-widgets-system', '2015-04-29 10:42:49', '2015-04-29 11:17:41', '2015-04-29 00:00:00', '0');

-- --------------------------------------------------------

--
-- Структура таблиці `publication_translate`
--

CREATE TABLE IF NOT EXISTS `publication_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foreign_id` int(11) NOT NULL,
  `lang` varchar(20) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Дамп даних таблиці `publication_translate`
--

INSERT INTO `publication_translate` (`id`, `foreign_id`, `lang`, `key`, `value`) VALUES
(1, 1, 'ru', 'title', 'Релиз Phalcon 1.3.2'),
(2, 1, 'ru', 'meta_title', 'Релиз Phalcon 1.3.2'),
(3, 1, 'ru', 'meta_description', ''),
(4, 1, 'ru', 'meta_keywords', ''),
(5, 1, 'ru', 'text', '<p>Релиз Phalcon 1.3.2. Дальше текст на английском...</p>\r\n<p>We are today releasing the much awaited 1.3.2 version.&nbsp;</p>\r\n<p>This version has a ton of contributions from our community and fixes to the framework. We thank everyone that has worked on this release, especially with their contributions both to 1.3.2 and our work in progress 2.0.0.</p>\r\n<p>Many thanks to dreamsxin, <a href="https://github.com/mruz">mruz</a>, <a href="https://github.com/kjdev">kjdev</a>, <a href="https://github.com/Cinderella-Man">Cinderella-Man</a>, <a href="https://github.com/andreadelfino">andreadelfino</a>, <a href="https://github.com/kfll">kfll</a>, <a href="https://github.com/brandonlamb">brandonlamb</a>, <a href="https://github.com/zacek">zacek</a>, <a href="https://github.com/joni">joni</a>, <a href="https://github.com/wandersonwhcr">wandersonwhcr</a>, <a href="https://github.com/kevinhatry">kevinhatry</a>, <a href="https://github.com/alkana">alkana</a> and many others that have contributed either on <a href="https://github.com/phalcon/cphalcon">Github or through discussion in our </a><a href="http://forum.phalconphp.com/">forum</a>.</p>\r\n<p>The changelog can be found <a href="https://github.com/phalcon/cphalcon/blob/master/CHANGELOG">here</a>.</p>\r\n<p>We also have a number of pull requests that have not made it to 1.3.2 but will be included to 1.3.3. We need to make sure that the fix or feature that each pull request offers are present both in 1.3.3 but also in 2.0.0</p>\r\n<p>A big thank you once again to our community! You guys are awesome!</p>\r\n<p>&lt;3 Phalcon Team</p>'),
(6, 2, 'ru', 'title', 'Видеовстреча сообщества Phalcon'),
(7, 2, 'ru', 'meta_title', 'Видеовстреча сообщества Phalcon'),
(8, 2, 'ru', 'meta_description', ''),
(9, 2, 'ru', 'meta_keywords', ''),
(10, 2, 'ru', 'text', '<p>Видеовстреча сообщества Phalcon.&nbsp;Дальше текст на английском...</p>\r\n<p>Yesterday (2014-04-05) we had our first Phalcon community hangout. The main purpose of the hangout was to meet the community, discuss about what Phalcon is and what our future steps are, and hear news, concerns, success stories from the community itself.</p>\r\n<p>We are excited to announce that the first Phalcon community hangout was a great success!</p>\r\n<p>We had an awesome turnout from all around the world, with members of the community filling the hangout (10 concurrent users) and more viewing online, asking questions and interacting with the team.</p>\r\n<p>We talked about where we are, where we came from and what the future steps are with Zephir and Phalcon 2.0. Contributions, bugs and NFRs were also topics in our discussion, as well as who are team and how Phalcon is funded.</p>\r\n<p>More hangouts will be scheduled in the near future, hopefully making this a regular event for our community. We will also cater for members of the community that are not English speakers, by creating hangouts for Spanish speaking, Russian etc. The goal is to engage as many members as possible!</p>\r\n<p>The love and trust you all have shown to our framework is what drives us to make it better, push performance, introduce more features and make Phalcon the best PHP framework there is.&nbsp;</p>\r\n<p>For those that missed it, the video is below.</p>'),
(11, 1, 'en', 'title', 'Phalcon 1.3.2 Released'),
(12, 1, 'en', 'meta_title', 'Phalcon 1.3.2 Released'),
(13, 1, 'en', 'meta_description', ''),
(14, 1, 'en', 'meta_keywords', ''),
(15, 1, 'en', 'text', '<p>We are today releasing the much awaited 1.3.2 version.&nbsp;</p>\r\n<p>This version has a ton of contributions from our community and fixes to the framework. We thank everyone that has worked on this release, especially with their contributions both to 1.3.2 and our work in progress 2.0.0.</p>\r\n<p>Many thanks to dreamsxin, <a href="https://github.com/mruz">mruz</a>, <a href="https://github.com/kjdev">kjdev</a>, <a href="https://github.com/Cinderella-Man">Cinderella-Man</a>, <a href="https://github.com/andreadelfino">andreadelfino</a>, <a href="https://github.com/kfll">kfll</a>, <a href="https://github.com/brandonlamb">brandonlamb</a>, <a href="https://github.com/zacek">zacek</a>, <a href="https://github.com/joni">joni</a>, <a href="https://github.com/wandersonwhcr">wandersonwhcr</a>, <a href="https://github.com/kevinhatry">kevinhatry</a>, <a href="https://github.com/alkana">alkana</a> and many others that have contributed either on <a href="https://github.com/phalcon/cphalcon">Github or through discussion in our </a><a href="http://forum.phalconphp.com/">forum</a>.</p>\r\n<p>The changelog can be found <a href="https://github.com/phalcon/cphalcon/blob/master/CHANGELOG">here</a>.</p>\r\n<p>We also have a number of pull requests that have not made it to 1.3.2 but will be included to 1.3.3. We need to make sure that the fix or feature that each pull request offers are present both in 1.3.3 but also in 2.0.0</p>\r\n<p>A big thank you once again to our community! You guys are awesome!</p>\r\n<p>&lt;3 Phalcon Team</p>'),
(16, 1, 'uk', 'title', 'Реліз Phalcon 1.3.2'),
(17, 1, 'uk', 'meta_title', 'Реліз Phalcon 1.3.2'),
(18, 1, 'uk', 'meta_description', ''),
(19, 1, 'uk', 'meta_keywords', ''),
(20, 1, 'uk', 'text', '<p>Реліз Phalcon 1.3.2. Далі текст англійською...</p>\r\n<p>We are today releasing the much awaited 1.3.2 version.&nbsp;</p>\r\n<p>This version has a ton of contributions from our community and fixes to the framework. We thank everyone that has worked on this release, especially with their contributions both to 1.3.2 and our work in progress 2.0.0.</p>\r\n<p>Many thanks to dreamsxin, <a href="https://github.com/mruz">mruz</a>, <a href="https://github.com/kjdev">kjdev</a>, <a href="https://github.com/Cinderella-Man">Cinderella-Man</a>, <a href="https://github.com/andreadelfino">andreadelfino</a>, <a href="https://github.com/kfll">kfll</a>, <a href="https://github.com/brandonlamb">brandonlamb</a>, <a href="https://github.com/zacek">zacek</a>, <a href="https://github.com/joni">joni</a>, <a href="https://github.com/wandersonwhcr">wandersonwhcr</a>, <a href="https://github.com/kevinhatry">kevinhatry</a>, <a href="https://github.com/alkana">alkana</a> and many others that have contributed either on <a href="https://github.com/phalcon/cphalcon">Github or through discussion in our </a><a href="http://forum.phalconphp.com/">forum</a>.</p>\r\n<p>The changelog can be found <a href="https://github.com/phalcon/cphalcon/blob/master/CHANGELOG">here</a>.</p>\r\n<p>We also have a number of pull requests that have not made it to 1.3.2 but will be included to 1.3.3. We need to make sure that the fix or feature that each pull request offers are present both in 1.3.3 but also in 2.0.0</p>\r\n<p>A big thank you once again to our community! You guys are awesome!</p>\r\n<p>&lt;3 Phalcon Team</p>'),
(21, 2, 'en', 'title', 'Phalcon Community Hangout'),
(22, 2, 'en', 'meta_title', 'Phalcon Community Hangout'),
(23, 2, 'en', 'meta_description', ''),
(24, 2, 'en', 'meta_keywords', ''),
(25, 2, 'en', 'text', '<p>Yesterday (2014-04-05) we had our first Phalcon community hangout. The main purpose of the hangout was to meet the community, discuss about what Phalcon is and what our future steps are, and hear news, concerns, success stories from the community itself.</p>\r\n<p>We are excited to announce that the first Phalcon community hangout was a great success!</p>\r\n<p>We had an awesome turnout from all around the world, with members of the community filling the hangout (10 concurrent users) and more viewing online, asking questions and interacting with the team.</p>\r\n<p>We talked about where we are, where we came from and what the future steps are with Zephir and Phalcon 2.0. Contributions, bugs and NFRs were also topics in our discussion, as well as who are team and how Phalcon is funded.</p>\r\n<p>More hangouts will be scheduled in the near future, hopefully making this a regular event for our community. We will also cater for members of the community that are not English speakers, by creating hangouts for Spanish speaking, Russian etc. The goal is to engage as many members as possible!</p>\r\n<p>The love and trust you all have shown to our framework is what drives us to make it better, push performance, introduce more features and make Phalcon the best PHP framework there is.&nbsp;</p>\r\n<p>For those that missed it, the video is below.</p>'),
(26, 2, 'uk', 'title', 'Відеозустріч спільноти Phalcon'),
(27, 2, 'uk', 'meta_title', 'Відеозустріч спільноти Phalcon'),
(28, 2, 'uk', 'meta_description', ''),
(29, 2, 'uk', 'meta_keywords', ''),
(30, 2, 'uk', 'text', '<p>Відеозустріч спільноти Phalcon. Далі текст англійською...</p>\r\n<p>Yesterday (2014-04-05) we had our first Phalcon community hangout. The main purpose of the hangout was to meet the community, discuss about what Phalcon is and what our future steps are, and hear news, concerns, success stories from the community itself.</p>\r\n<p>We are excited to announce that the first Phalcon community hangout was a great success!</p>\r\n<p>We had an awesome turnout from all around the world, with members of the community filling the hangout (10 concurrent users) and more viewing online, asking questions and interacting with the team.</p>\r\n<p>We talked about where we are, where we came from and what the future steps are with Zephir and Phalcon 2.0. Contributions, bugs and NFRs were also topics in our discussion, as well as who are team and how Phalcon is funded.</p>\r\n<p>More hangouts will be scheduled in the near future, hopefully making this a regular event for our community. We will also cater for members of the community that are not English speakers, by creating hangouts for Spanish speaking, Russian etc. The goal is to engage as many members as possible!</p>\r\n<p>The love and trust you all have shown to our framework is what drives us to make it better, push performance, introduce more features and make Phalcon the best PHP framework there is.&nbsp;</p>\r\n<p>For those that missed it, the video is below.</p>'),
(31, 3, 'ru', 'title', 'BuiltWith Phalcon'),
(32, 3, 'ru', 'meta_title', 'BuiltWith Phalcon'),
(33, 3, 'ru', 'meta_description', ''),
(34, 3, 'ru', 'meta_keywords', ''),
(35, 3, 'ru', 'text', '<p>Today we are launching a new site that would help us spread the word about Phalcon and show where Phalcon is used, whether this is production applications, hobby projects or tutorials.</p>\r\n<p>Introducing <a href="http://builtwith.phalconphp.com/">builtwith.phalconphp.com</a></p>\r\n<p>Taking the example from our friends at <a href="http://www.angularjs.org/">AngularJS</a> we have cloned their <a href="https://github.com/angular/builtwith.angularjs.org">repository</a> and we have Phalcon-ized it. Special thanks to the <a href="http://en.wikipedia.org/wiki/AngularJS">AngularJS</a>team as well as <a href="https://github.com/oaass">Ole Aass</a> (<a href="http://oleaass.com/">website</a>) who is leading the project.</p>\r\n<p>The new site has a very easy interface that users can navigate to and even search for projects with tags.&nbsp;</p>\r\n<p>You can add your own project by simply cloning our <a href="https://github.com/phalcon/builtwith">repository</a> and adding your project as well as a logo and screenshots and then issue a pull request for it to appear in the live site.</p>\r\n<p>Looking forward to seeing your projects listed up there!</p>\r\n<p>&lt;3 The Phalcon Team</p>'),
(36, 4, 'ru', 'title', 'Вторая статья'),
(37, 4, 'ru', 'meta_title', 'Вторая статья'),
(38, 4, 'ru', 'meta_description', ''),
(39, 4, 'ru', 'meta_keywords', ''),
(40, 4, 'ru', 'text', '<p>Текст второй статьи</p>'),
(41, 3, 'en', 'title', 'BuiltWith Phalcon'),
(42, 3, 'en', 'meta_title', 'BuiltWith Phalcon'),
(43, 3, 'en', 'meta_description', ''),
(44, 3, 'en', 'meta_keywords', ''),
(45, 3, 'en', 'text', '<p>Today we are launching a new site that would help us spread the word about Phalcon and show where Phalcon is used, whether this is production applications, hobby projects or tutorials.</p>\r\n<p>Introducing <a href="http://builtwith.phalconphp.com/">builtwith.phalconphp.com</a></p>\r\n<p>Taking the example from our friends at <a href="http://www.angularjs.org/">AngularJS</a> we have cloned their <a href="https://github.com/angular/builtwith.angularjs.org">repository</a> and we have Phalcon-ized it. Special thanks to the <a href="http://en.wikipedia.org/wiki/AngularJS">AngularJS</a>team as well as <a href="https://github.com/oaass">Ole Aass</a> (<a href="http://oleaass.com/">website</a>) who is leading the project.</p>\r\n<p>The new site has a very easy interface that users can navigate to and even search for projects with tags.&nbsp;</p>\r\n<p>You can add your own project by simply cloning our <a href="https://github.com/phalcon/builtwith">repository</a> and adding your project as well as a logo and screenshots and then issue a pull request for it to appear in the live site.</p>\r\n<p>Looking forward to seeing your projects listed up there!</p>\r\n<p>&lt;3 The Phalcon Team</p>'),
(46, 3, 'uk', 'title', 'BuiltWith Phalcon'),
(47, 3, 'uk', 'meta_title', 'BuiltWith Phalcon'),
(48, 3, 'uk', 'meta_description', ''),
(49, 3, 'uk', 'meta_keywords', ''),
(50, 3, 'uk', 'text', '<p>Today we are launching a new site that would help us spread the word about Phalcon and show where Phalcon is used, whether this is production applications, hobby projects or tutorials.</p>\r\n<p>Introducing <a href="http://builtwith.phalconphp.com/">builtwith.phalconphp.com</a></p>\r\n<p>Taking the example from our friends at <a href="http://www.angularjs.org/">AngularJS</a> we have cloned their <a href="https://github.com/angular/builtwith.angularjs.org">repository</a> and we have Phalcon-ized it. Special thanks to the <a href="http://en.wikipedia.org/wiki/AngularJS">AngularJS</a>team as well as <a href="https://github.com/oaass">Ole Aass</a> (<a href="http://oleaass.com/">website</a>) who is leading the project.</p>\r\n<p>The new site has a very easy interface that users can navigate to and even search for projects with tags.&nbsp;</p>\r\n<p>You can add your own project by simply cloning our <a href="https://github.com/phalcon/builtwith">repository</a> and adding your project as well as a logo and screenshots and then issue a pull request for it to appear in the live site.</p>\r\n<p>Looking forward to seeing your projects listed up there!</p>\r\n<p>&lt;3 The Phalcon Team</p>'),
(51, 4, 'en', 'title', 'Second article'),
(52, 4, 'en', 'meta_title', 'Second article'),
(53, 4, 'en', 'meta_description', ''),
(54, 4, 'en', 'meta_keywords', ''),
(55, 4, 'en', 'text', '<p>Second article text</p>'),
(56, 4, 'uk', 'title', 'Друга стаття'),
(57, 4, 'uk', 'meta_title', 'Друга стаття'),
(58, 4, 'uk', 'meta_description', ''),
(59, 4, 'uk', 'meta_keywords', ''),
(60, 4, 'uk', 'text', '<p>Текст другої статті</p>'),
(61, 5, 'en', 'title', 'New modular widgets system'),
(62, 5, 'en', 'meta_title', 'New widgets system'),
(63, 5, 'en', 'meta_description', ''),
(64, 5, 'en', 'meta_keywords', ''),
(65, 5, 'en', 'text', '<p>Here is the new features of YonaCMS - "System of modular widgets".</p>\r\n<p>Now, in any of your modules, you can create dynamic widgets with their business logic and templates. Forget about dozens of separate helper and the need to do the same routine operations! Also, this scheme will maintain cleanliness and order in the code for your project.</p>\r\n<p>Call each widget can be produced directly from the template Volt with the transfer set of parameters. Each widget is automatically cached and does not lead to additional load on the database. Caching can be disabled in the administrative panel, see Admin -&gt; Settings, option "Widgets caching". Automatic regeneration of the cache is carried out after 60 seconds.</p>\r\n<p>As an example of such a call is made to the widget template''s main page /app/modules/Index/views/index.volt</p>\r\n<pre>{{Helper.widget (''Publication''). LastNews ()}}</pre>\r\n<p><br />Files widget:<br />/app/modules/Publication/Widget/PublicationWidget.php - inherits \\ Application \\ Widget \\ AbstractWidget<br />/app/modules/Publication/views/widget/last-news.volt - template output</p>\r\n<p>The main class of the widget - \\ Application \\ Widget \\ Proxy<br />It is possible to set the default value for time caching.</p>\r\n<p>This system will be very useful for developers who have a lot of individual information units, as well as those who want to keep your code clean and easy tool to use.</p>'),
(66, 5, 'ru', 'title', 'Новая система модульных виджетов'),
(67, 5, 'ru', 'meta_title', 'Новая система виджетов'),
(68, 5, 'ru', 'meta_description', ''),
(69, 5, 'ru', 'meta_keywords', ''),
(70, 5, 'ru', 'text', '<p>Представляем вам новый функционал от YonaCMS - "Систему модульных виджетов".</p>\r\n<p>Теперь в любом из ваших модулей вы можете создать динамические виджеты со своей бизнес-логикой и шаблонами. Забудьте о десятках отдельных хелперов и необходимости делать одни и те же рутинные операции! Также эта схема позволит поддерживать чистоту и порядок в коде вашего проекта.</p>\r\n<p>Вызов каждого виджета может быть произведен непосредственно с шаблона Volt с передачей набора параметров. Каждый виджет автоматически кешируется и не влечет дополнительной нагрузки на базу данных. Кеширование можно отключить в административной панели в разделе Admin -&gt; Settings, опция "Widgets caching". Автоматическая перегенерация кеша осуществляется через 60 секунд.</p>\r\n<p>В качестве примера сделан вызов такого виджета в шаблоне главной страницы /app/modules/Index/views/index.volt</p>\r\n<pre>{{ helper.widget(''Publication'').lastNews() }}</pre>\r\n<p><br />Файлы виджета:<br />/app/modules/Publication/Widget/PublicationWidget.php - наследует класс \\Application\\Widget\\AbstractWidget<br />/app/modules/Publication/views/widget/last-news.volt - шаблон вывода</p>\r\n<p>Основной класс системы виджетов - \\Application\\Widget\\Proxy<br />В нем можно установить дефолтное значение времени кеширования.</p>\r\n<p>Данная система будет очень полезна для разработчиков, которые имеют много отдельных информационных блоков, а также тем, кто хочет поддерживать свой код в чистоте и пользоваться удобным инструментом.</p>'),
(71, 5, 'uk', 'title', 'Нова система модульних віджетів'),
(72, 5, 'uk', 'meta_title', 'Нова система віджетів'),
(73, 5, 'uk', 'meta_description', ''),
(74, 5, 'uk', 'meta_keywords', ''),
(75, 5, 'uk', 'text', '<p>Представляємо вам новий функціонал від YonaCMS - "Систему модульних віджетів".</p>\r\n<p>Тепер в будь-якому з ваших модулів ви можете створити динамічні віджети з власною&nbsp;бізнес-логікою і шаблонами. Забудьте про десятки окремих хелперів та необхідності робити одні і ті ж самі рутинні операції! Також ця схема дозволить підтримувати чистоту і порядок у коді вашого проекту.</p>\r\n<p>Виклик кожного віджета може бути проведений безпосередньо з шаблону Volt з передачею набору параметрів. Кожен віджет автоматично кешуєтся і не тягне додаткового навантаження на базу даних. Кешування можна відключити в адміністративній панелі в розділі Admin -&gt; Settings, опція "Widgets caching". Автоматична перегенерація кеша здійснюється через 60 секунд.</p>\r\n<p>Як приклад зроблений виклик такого віджета в шаблоні головної сторінки /app/modules/Index/views/index.volt</p>\r\n<pre>{{Helper.widget (''Publication''). LastNews ()}}</pre>\r\n<p><br />Файли віджету:<br />/app/modules/Publication/Widget/PublicationWidget.php - успадковує клас \\ Application \\ Widget \\ AbstractWidget<br />/app/modules/Publication/views/widget/last-news.volt - шаблон виводу</p>\r\n<p>Основний клас системи віджетів - \\ Application \\ Widget \\ Proxy<br />У ньому можна встановити дефолтне значення часу кешування.</p>\r\n<p>Дана система буде дуже корисною для розробників, які мають багато окремих інформаційних блоків, а також тим, хто хоче підтримувати свій код в чистоті і користуватися зручним інструментом.</p>');

-- --------------------------------------------------------

--
-- Структура таблиці `publication_type`
--

CREATE TABLE IF NOT EXISTS `publication_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) DEFAULT NULL,
  `limit` int(4) DEFAULT NULL,
  `format` enum('list','grid') DEFAULT NULL,
  `display_date` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `publication_type`
--

INSERT INTO `publication_type` (`id`, `slug`, `limit`, `format`, `display_date`) VALUES
(1, 'news', 10, 'grid', '1'),
(2, 'articles', 10, 'list', '0');

-- --------------------------------------------------------

--
-- Структура таблиці `publication_type_translate`
--

CREATE TABLE IF NOT EXISTS `publication_type_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foreign_id` int(11) NOT NULL,
  `lang` varchar(20) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;

--
-- Дамп даних таблиці `publication_type_translate`
--

INSERT INTO `publication_type_translate` (`id`, `foreign_id`, `lang`, `key`, `value`) VALUES
(1, 1, 'ru', 'head_title', 'Новости'),
(2, 1, 'ru', 'meta_description', ''),
(3, 1, 'ru', 'meta_keywords', ''),
(4, 1, 'ru', 'seo_text', ''),
(54, 1, 'en', 'title', 'News'),
(55, 1, 'en', 'head_title', 'News'),
(56, 1, 'en', 'meta_description', ''),
(57, 1, 'en', 'meta_keywords', ''),
(58, 1, 'en', 'seo_text', ''),
(59, 1, 'uk', 'title', 'Новини'),
(60, 1, 'uk', 'head_title', 'Новини'),
(61, 1, 'uk', 'meta_description', ''),
(62, 1, 'uk', 'meta_keywords', ''),
(63, 1, 'uk', 'seo_text', ''),
(64, 1, 'ru', 'title', 'Новости'),
(65, 2, 'ru', 'title', 'Статьи'),
(66, 2, 'ru', 'head_title', 'Статьи'),
(67, 2, 'ru', 'meta_description', ''),
(68, 2, 'ru', 'meta_keywords', ''),
(69, 2, 'ru', 'seo_text', ''),
(70, 2, 'en', 'title', 'Articles'),
(71, 2, 'en', 'head_title', 'Articles'),
(72, 2, 'en', 'meta_description', ''),
(73, 2, 'en', 'meta_keywords', ''),
(74, 2, 'en', 'seo_text', ''),
(75, 2, 'uk', 'title', 'Статті'),
(76, 2, 'uk', 'head_title', 'Статті'),
(77, 2, 'uk', 'meta_description', ''),
(78, 2, 'uk', 'meta_keywords', ''),
(79, 2, 'uk', 'seo_text', '');

-- --------------------------------------------------------

--
-- Структура таблиці `seo_manager`
--

CREATE TABLE IF NOT EXISTS `seo_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_name` varchar(50) DEFAULT NULL,
  `route` varchar(50) DEFAULT NULL,
  `route_ml` varchar(60) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `route_params_json` text,
  `query_params_json` text,
  `head_title` varchar(500) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `seo_text` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `seo_manager`
--

INSERT INTO `seo_manager` (`id`, `custom_name`, `route`, `route_ml`, `module`, `controller`, `action`, `language`, `route_params_json`, `query_params_json`, `head_title`, `meta_description`, `meta_keywords`, `seo_text`, `created_at`, `updated_at`) VALUES
(1, 'Новости', 'publications', 'ml__publications_ru', NULL, NULL, NULL, 'ru', '{"type" : "news"}', '', 'Последние новости', 'Самые свежие и последние новости!', 'новости, последние, свежие', 'Представляем вашему вниманию самые последние и последние новости!', '2014-09-30 10:39:23', '2014-11-27 11:11:41');

-- --------------------------------------------------------

--
-- Структура таблиці `translate`
--

CREATE TABLE IF NOT EXISTS `translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(20) DEFAULT NULL,
  `phrase` varchar(500) DEFAULT NULL,
  `translation` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Дамп даних таблиці `translate`
--

INSERT INTO `translate` (`id`, `lang`, `phrase`, `translation`) VALUES
(1, 'ru', 'Ошибка валидации формы', 'Ошибка валидации формы'),
(2, 'ru', 'Подробнее', 'Подробнее'),
(3, 'ru', 'Назад к перечню публикаций', 'Назад к перечню публикаций'),
(4, 'ru', 'SITE NAME', 'Yona CMS'),
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
(18, 'uk', 'SITE NAME', 'Yona CMS'),
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
(51, 'en', 'Services', 'Se'),
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
(63, 'uk', 'Design', 'Design');

-- --------------------------------------------------------

--
-- Структура таблиці `tree_category`
--

CREATE TABLE IF NOT EXISTS `tree_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `root` enum('articles','news') NOT NULL DEFAULT 'articles',
  `parent_id` int(11) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `depth` tinyint(2) NOT NULL DEFAULT '0',
  `left` int(11) DEFAULT NULL,
  `right` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `tree_category_translate`
--

CREATE TABLE IF NOT EXISTS `tree_category_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foreign_id` int(11) NOT NULL,
  `lang` varchar(20) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `widget`
--

CREATE TABLE IF NOT EXISTS `widget` (
  `id` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `html` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `widget`
--

INSERT INTO `widget` (`id`, `title`, `html`) VALUES
('phone', 'Phone in header', '<div class="phone">+1 (001) 555-44-33</div>');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `page_translate`
--
ALTER TABLE `page_translate`
  ADD CONSTRAINT `page_translate_ibfk_1` FOREIGN KEY (`foreign_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publication`
--
ALTER TABLE `publication`
  ADD CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `publication_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publication_translate`
--
ALTER TABLE `publication_translate`
  ADD CONSTRAINT `publication_translate_ibfk_1` FOREIGN KEY (`foreign_id`) REFERENCES `publication` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publication_type_translate`
--
ALTER TABLE `publication_type_translate`
  ADD CONSTRAINT `publication_type_translate_ibfk_1` FOREIGN KEY (`foreign_id`) REFERENCES `publication_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `tree_category_translate`
--
ALTER TABLE `tree_category_translate`
  ADD CONSTRAINT `tree_category_translate_ibfk_1` FOREIGN KEY (`foreign_id`) REFERENCES `tree_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
