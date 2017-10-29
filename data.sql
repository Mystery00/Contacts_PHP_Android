CREATE DATABASE `db_contacts` /*!40100 DEFAULT CHARACTER SET utf8 */;

CREATE TABLE `db_contacts`.`table_user` (
  `user_id`  INT(11) NOT NULL AUTO_INCREMENT,
  `username` TEXT    NOT NULL,
  `password` TEXT    NOT NULL,
  PRIMARY KEY (`user_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `db_contacts`.`table_contacts` (
  `contact_id`   INT(11)     NOT NULL AUTO_INCREMENT,
  `contact_name` VARCHAR(45) NOT NULL,
  `contact_init` VARCHAR(1)  NOT NULL,
  `contact_mark` TEXT,
  `user_id`      INT(11)     NOT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `db_contacts`.`table_user` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `db_contacts`.`table_email` (
  `email_id`      INT(11)     NOT NULL AUTO_INCREMENT,
  `email_address` VARCHAR(45) NOT NULL,
  `contact_id`    INT(11)     NOT NULL,
  PRIMARY KEY (`email_id`),
  KEY `contacts_idx` (`contact_id`),
  CONSTRAINT `contacts_email` FOREIGN KEY (`contact_id`) REFERENCES `db_contacts`.`table_contacts` (`contact_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `db_contacts`.`table_phone` (
  `phone_id`     INT(11)     NOT NULL AUTO_INCREMENT,
  `phone_number` VARCHAR(20) NOT NULL,
  `phone_type`   VARCHAR(45) NOT NULL,
  `contact_id`   INT(11)     NOT NULL,
  PRIMARY KEY (`phone_id`),
  KEY `contacts_idx` (`contact_id`),
  CONSTRAINT `contacts_phone` FOREIGN KEY (`contact_id`) REFERENCES `db_contacts`.`table_contacts` (`contact_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
