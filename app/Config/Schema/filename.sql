

DROP TABLE IF EXISTS `cake_mplayer`.`queue`;
DROP TABLE IF EXISTS `cake_mplayer`.`songs`;


CREATE TABLE `cake_mplayer`.`queue` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`song_id` int(11) NOT NULL,
	`created` datetime DEFAULT NULL,
	`updated` datetime DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `song_id` (`song_id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `cake_mplayer`.`songs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`filename` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`path` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`bitrate` int(5) NOT NULL,
	`artist` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`playtime` float NOT NULL,
	`format` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`filesize` int(20) NOT NULL,
	`created` datetime DEFAULT NULL,
	`updated` datetime DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `filename` (`filename`(333)),
	KEY `artist` (`artist`),
	KEY `title` (`title`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

