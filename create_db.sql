CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `shortname` varchar(250) NOT NULL,
  `postcontent` text NOT NULL,
  `publishdate` int(11) NOT NULL,
  `lastedited` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;