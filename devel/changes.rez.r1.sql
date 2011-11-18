CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL default '',
  `last_name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `date_added` bigint unsigned NOT NULL default 0,
  PRIMARY KEY (`id`)
);

/* authentication types */
CREATE TABLE `user_authentication` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL default 0,
  `type` varchar(255) NOT NULL default '',


);

CREATE TABLE `groups` (
  `id`

)