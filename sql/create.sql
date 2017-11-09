# 创建数据库
DROP DATABASE IF EXISTS db_contacts;
CREATE DATABASE db_contacts
    DEFAULT CHARACTER SET utf8;

USE db_contacts;
# 创建用户表
DROP TABLE IF EXISTS table_user;
CREATE TABLE db_contacts.table_user (
    `user_id`  INT(11) NOT NULL AUTO_INCREMENT,
    `username` TEXT    NOT NULL,
    `password` TEXT    NOT NULL,
    PRIMARY KEY (`user_id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;

# 创建联系人表
DROP TABLE IF EXISTS table_contacts;
CREATE TABLE db_contacts.table_contacts (
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
DROP TABLE IF EXISTS table_email;
CREATE TABLE db_contacts.table_email (
    `email_id`      INT(11)     NOT NULL AUTO_INCREMENT,
    `email_address` VARCHAR(45) NOT NULL,
    `contact_id`    INT(11)     NOT NULL,
    PRIMARY KEY (`email_id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;

# 创建电话表
DROP TABLE IF EXISTS table_phone;
CREATE TABLE db_contacts.table_phone (
    `phone_id`     INT(11)     NOT NULL AUTO_INCREMENT,
    `phone_number` VARCHAR(20) NOT NULL,
    `phone_type`   VARCHAR(45) NOT NULL,
    `contact_id`   INT(11)     NOT NULL,
    PRIMARY KEY (`phone_id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8;