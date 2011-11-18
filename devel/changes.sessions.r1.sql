CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL default '',
  `user_id` bigint unsigned NOT NULL default 0,
  `data` blob NOT NULL,
  `date_added` bigint unsigned NOT NULL default 0,
  PRIMARY KEY (`id`)
);
