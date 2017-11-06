# 创建数据库
CREATE DATABASE `db_contacts` /*!40100 DEFAULT CHARACTER SET utf8 */;

# 创建用户表
CREATE TABLE `db_contacts`.`table_user` (
    `user_id`  INT(11) NOT NULL AUTO_INCREMENT,
    `username` TEXT    NOT NULL,
    `password` TEXT    NOT NULL,
    PRIMARY KEY (`user_id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;

# 创建联系人表
CREATE TABLE `db_contacts`.`table_contacts` (
    `contact_id`   INT(11)     NOT NULL AUTO_INCREMENT,
    `contact_name` VARCHAR(45) NOT NULL,
    `contact_init` VARCHAR(1)  NOT NULL,
    `contact_mark` TEXT,
    `user_id`      INT(11)     NOT NULL,
    PRIMARY KEY (`contact_id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;

# 创建邮箱表
CREATE TABLE `db_contacts`.`table_email` (
    `email_id`      INT(11)     NOT NULL AUTO_INCREMENT,
    `email_address` VARCHAR(45) NOT NULL,
    `contact_id`    INT(11)     NOT NULL,
    PRIMARY KEY (`email_id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;

# 创建电话表
CREATE TABLE `db_contacts`.`table_phone` (
    `phone_id`     INT(11)     NOT NULL AUTO_INCREMENT,
    `phone_number` VARCHAR(20) NOT NULL,
    `phone_type`   VARCHAR(45) NOT NULL,
    `contact_id`   INT(11)     NOT NULL,
    PRIMARY KEY (`phone_id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;