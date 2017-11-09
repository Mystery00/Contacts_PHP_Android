USE db_contacts;
DELIMITER //

# 新增记录 存储过程
DROP PROCEDURE IF EXISTS procedure_contactInsert;
CREATE PROCEDURE procedure_contactInsert(contactName VARCHAR(45), contactInit VARCHAR(1),
                                         contactMark TEXT,
                                         userID      INT,
    OUT                                  insert_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        SET old_count = function_getContactCount();
        INSERT INTO table_contacts (contact_name, contact_init, contact_mark, user_id)
        VALUES (contactName, contactInit, contactMark, userID);
        SET new_count = function_getContactCount();
        IF old_count + 1 = new_count
        THEN
            SET insert_code = TRUE;
        ELSE
            SET insert_code = FALSE;
        END IF;
    END //

# 删除记录 存储过程
DROP PROCEDURE IF EXISTS procedure_contactDelete;
CREATE PROCEDURE procedure_contactDelete(contactID INT, OUT delete_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        SET old_count = function_getContactCount();
        DELETE FROM table_contacts
        WHERE contactID = contact_id;
        SET new_count = function_getContactCount();
        IF old_count - 1 = new_count
        THEN
            SET delete_code = TRUE;
        ELSE
            SET delete_code = FALSE;
        END IF;
    END //

# 查询记录 存储过程
DROP PROCEDURE IF EXISTS procedure_checkContact;
CREATE PROCEDURE procedure_checkContact(contactName VARCHAR(45), userID INT, OUT check_code BOOLEAN)
    BEGIN
        DECLARE temp INT;
        SET temp = (SELECT count(contact_id)
                    FROM table_contacts
                    WHERE contactName = contact_name AND userID = user_id);
        IF temp = 1
        THEN
            SET check_code = TRUE;
        ELSE
            SET check_code = FALSE;
        END IF;
    END //

# 查询记录 存储过程
DROP PROCEDURE IF EXISTS procedure_checkContactExist;
CREATE PROCEDURE procedure_checkContactExist(contactName VARCHAR(45), userID INT, contactID INT,
    OUT                                      check_code  BOOLEAN)
    BEGIN
        DECLARE temp INT;
        SET temp = 0;
        SET temp = temp + (SELECT count(temp)
                           FROM table_contacts
                           WHERE contactName = contact_name AND userID = user_id);
        SET temp = temp + (SELECT count(temp)
                           FROM table_contacts
                           WHERE contactName = contact_name AND userID = user_id AND
                                 contact_id = contactID);
        IF temp = 1
        THEN
            SET check_code = TRUE;
        ELSE
            SET check_code = FALSE;
        END IF;
    END //

# 修改记录 存储过程
DROP PROCEDURE IF EXISTS procedure_contactUpdate;
CREATE PROCEDURE procedure_contactUpdate(contactName VARCHAR(45), contactInit VARCHAR(1),
                                         contactMark TEXT,
                                         userID      INT,
                                         contactID   INT)
    BEGIN
        UPDATE table_contacts
        SET contact_name = contactName, contact_init = contactInit, contact_mark = contactMark,
            user_id      = userID
        WHERE contact_id = contactID;
    END //

# 获取电话列表 存储过程
DROP PROCEDURE IF EXISTS procedure_getPhoneList;
CREATE PROCEDURE procedure_getPhoneList(new_contact_id INT)
    BEGIN
        SELECT *
        FROM table_phone
        WHERE contact_id = new_contact_id;
    END //

# 获取邮箱列表 存储过程
DROP PROCEDURE IF EXISTS procedure_getEmailList;
CREATE PROCEDURE procedure_getEmailList(new_contact_id INT)
    BEGIN
        SELECT *
        FROM table_email
        WHERE contact_id = new_contact_id;
    END //

# 获取ID 函数
DROP FUNCTION IF EXISTS function_getContactID;
CREATE FUNCTION function_getContactID(contactName VARCHAR(45), userID INT)
    RETURNS INT
    BEGIN
        DECLARE check_code BOOLEAN;
        CALL procedure_checkContact(contactName, userID, check_code);
        IF check_code
        THEN
            RETURN (SELECT contact_id
                    FROM table_contacts
                    WHERE contactName = contact_name AND userID = user_id);
        ELSE
            RETURN -1;
        END IF;
    END //

# 获取联系人数量 函数
DROP FUNCTION IF EXISTS function_getContactCount;
CREATE FUNCTION function_getContactCount()
    RETURNS INT
    BEGIN
        RETURN (SELECT count(contact_id)
                FROM table_contacts);
    END //

# 获取对应用户联系人数量 函数
DROP FUNCTION IF EXISTS function_getContactCountForUser;
CREATE FUNCTION function_getContactCountForUser(userID INT)
    RETURNS INT
    BEGIN
        RETURN (SELECT count(contact_id)
                FROM table_contacts
                WHERE user_id = userID);
    END //

# 新增记录 函数
DROP FUNCTION IF EXISTS function_contactInsert;
CREATE FUNCTION function_contactInsert(contactName VARCHAR(45), contactInit VARCHAR(1),
                                       contactMark TEXT, userID INT)
    RETURNS INT
    BEGIN
        DECLARE insert_code BOOLEAN;
        DECLARE isExist BOOLEAN;
        CALL procedure_checkContact(contactName, userID, isExist);
        IF isExist
        THEN
            RETURN 1; #联系人已存在
        END IF;
        CALL procedure_contactInsert(contactName, contactInit, contactMark, userID, insert_code);
        IF insert_code
        THEN
            RETURN 0; #插入成功
        ELSE
            RETURN 2; #插入失败
        END IF;
    END //

# 删除记录 函数
DROP FUNCTION IF EXISTS function_contactDelete;
CREATE FUNCTION function_contactDelete(contactName VARCHAR(45), userID INT)
    RETURNS INT
    BEGIN
        DECLARE id INT;
        DECLARE delete_code BOOLEAN;
        SET id = function_getContactID(contactName, userID);
        IF id = -1
        THEN
            RETURN 1; #记录不存在
        END IF;
        CALL procedure_contactDelete(id, delete_code);
        IF delete_code
        THEN
            RETURN 0; #删除成功
        ELSE
            RETURN 2; #删除失败
        END IF;
    END //

# 修改记录 函数
DROP FUNCTION IF EXISTS function_contactUpdate;
CREATE FUNCTION function_contactUpdate(contactName VARCHAR(45), contactInit VARCHAR(1),
                                       contactMark TEXT, userID INT,
                                       contactID   INT)
    RETURNS INT
    BEGIN
        DECLARE temp INT;
        DECLARE isExist BOOLEAN;
        CALL procedure_checkContactExist(contactName, userID, contactID, isExist);
        IF isExist
        THEN
            RETURN -1; #联系人已存在
        END IF;
        SET temp = (SELECT count(contact_id)
                    FROM table_contacts
                    WHERE contact_id = contactID);
        IF temp = 0
        THEN
            RETURN 1; #记录不存在
        END IF;
        CALL procedure_contactUpdate(contactName, contactInit, contactMark, userID, contactID);
        RETURN 0; #更新成功
    END //