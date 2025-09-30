DROP TABLE IF EXISTS `consumptions`;
DROP TABLE IF EXISTS `bathroom_items`;
DROP TABLE IF EXISTS `bathrooms`;
DROP TABLE IF EXISTS `buildings`;
DROP TABLE IF EXISTS `bathroom_item_types`;
DROP TABLE IF EXISTS `account_rules`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_rules`;

CREATE TABLE  `buildings` (
	`id` INT PRIMARY KEY AUTO_INCREMENT,
    `n_floors` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL
);

CREATE TABLE `bathrooms` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `image_url` VARCHAR(255),
	`floor` INT NOT NULL,
    `building_id` INT NOT NULL,
	FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`)
);

CREATE TABLE `bathroom_item_types`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `vendor_consumption_expenditure` FLOAT NOT NULL,
    `name` 	VARCHAR(100) NOT NULL
);

CREATE TABLE `bathroom_items` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `bathroom_item_type_id` INT NOT NULL,
	`bathroom_id` INT NOT NULL,
	FOREIGN KEY (`bathroom_id`) REFERENCES `bathrooms` (`id`),
	FOREIGN KEY (`bathroom_item_type_id`) REFERENCES `bathroom_item_types` (`id`)
);

CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `encrypted_password` VARCHAR(255) NOT NULL COMMENT 'BCrypt',
  `is_active` boolean DEFAULT true,
  `last_login` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `user_rules` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `rule_type` VARCHAR(12) NOT NULL
);



CREATE TABLE `account_rules` (
  `user_id` INT NOT NULL,
  `user_rule_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `user_rule_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`user_rule_id`) REFERENCES `user_rules` (`id`)
);

CREATE TABLE `consumptions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `quantity`FLOAT NOT NULL,
    `bathroom_id` INT NOT NULL,
    `bathroom_item_id` INT NOT NULL,
    `date` DATE NOT NULL,
    FOREIGN KEY (`bathroom_id`) REFERENCES `bathrooms` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`bathroom_item_id`) REFERENCES `bathroom_items` (`id`)
);

