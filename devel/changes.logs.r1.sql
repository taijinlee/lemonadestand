CREATE TABLE `page_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `entity_id` bigint unsigned NOT NULL default 0,
  `entity_type` varchar(255) NOT NULL default '',
  `session_id` varchar(255) NOT NULL default '',
  `ip` bigint unsigned NOT NULL default 0,
  `uri` blob NOT NULL,
  `referrer_hash` varchar(255) NOT NULL,
  `date_added` bigint unsigned NOT NULL default 0,
  `date_updated` bigint unsigned NOT NULL default 0,
  PRIMARY KEY (`id`)
);


CREATE TABLE `referrers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `referrer_hash` varchar(255) NOT NULL default '',
  `referrer_uri` blob NOT NULL default '',
  `date_added` bigint unsigned NOT NULL default 0,
  `date_updated` bigint unsigned NOT NULL default 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `referrer_hash` (`referrer_hash`)
);
