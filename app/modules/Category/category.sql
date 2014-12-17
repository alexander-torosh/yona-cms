--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('catalog','publication') NOT NULL DEFAULT 'catalog',
  `parent_id` int(11) DEFAULT NULL,
  `sortorder` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title_uk` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_title_uk` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_description_uk` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_keywords_uk` varchar(255) DEFAULT NULL,
  `text` text,
  `text_uk` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `type` (`type`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;