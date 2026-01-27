DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `image_url`   varchar(255) NOT NULL,
    `name`        varchar(255) NOT NULL,
    `description` varchar(255) NOT NULL,
    `content`     text         NOT NULL,
    `views_count` int          NOT NULL DEFAULT '0',
    `created_at`  datetime              DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `name`        varchar(255) NOT NULL,
    `description` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `article_category`;
CREATE TABLE `article_category`
(
    `article_id`  int NOT NULL,
    `category_id` int NOT NULL,
    KEY `article_id` (`article_id`),
    KEY `category_id` (`category_id`),
    CONSTRAINT `article_category_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `article_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);