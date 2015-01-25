/**
 * MySQL
 */

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT
);

DROP TABLE IF EXISTS `post_translation`;

CREATE TABLE `post_translation` (
  `post_id`  INT(11)      NOT NULL,
  `language` VARCHAR(16) NOT NULL,
  `title`    VARCHAR(255) NOT NULL,
  `body`     TEXT         NOT NULL,
  PRIMARY KEY (`post_id`, `language`)
);
