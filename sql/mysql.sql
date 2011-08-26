CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `reply_user_id` int(11) DEFAULT NULL,
  `comment` varchar(140) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `reply_user_id` (`reply_user_id`),
  CONSTRAINT `status_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `xryus_users` (`uid`),
  CONSTRAINT `status_ibfk_2` FOREIGN KEY (`reply_user_id`) REFERENCES `xryus_users` (`uid`)
) ENGINE=MyISAM;

CREATE TABLE `request` (
  `user_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`request_id`),
  KEY `request_id` (`request_id`),
  CONSTRAINT `request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `xryus_users` (`uid`),
  CONSTRAINT `request_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `xryus_users` (`uid`)
) ENGINE=MyISAM;

CREATE TABLE `follower` (
  `user_id` int(11) NOT NULL,
  `follow_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`follow_id`),
  KEY `follow_id` (`follow_id`),
  CONSTRAINT `follower_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `xryus_users` (`uid`),
  CONSTRAINT `follower_ibfk_2` FOREIGN KEY (`follow_id`) REFERENCES `xryus_users` (`uid`)
) ENGINE=MyISAM;
