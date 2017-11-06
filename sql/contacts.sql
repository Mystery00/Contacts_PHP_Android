USE db_contacts;
DELIMITER //

# 新增记录 存储过程
CREATE PROCEDURE procedure_contactInsert(contactName VARCHAR(45), contactInit VARCHAR(1),
                                         contactMark TEXT,
                                         userID      INT,
    OUT                                  insert_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        CALL procedure_getContactCount(old_count);
        INSERT INTO table_contacts (contact_name, contact_init, contact_mark, user_id)
        VALUES (contactName, contactInit, contactMark, userID);
        CALL procedure_getContactCount(new_count);
        IF old_count + 1 = new_count
        THEN
            SET insert_code = TRUE;
        ELSE
            SET insert_code = FALSE;
        END IF;
    END //

# 删除记录 存储过程
CREATE PROCEDURE procedure_contactDelete(contactID INT, OUT delete_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        CALL procedure_getContactCount(old_count);
        DELETE FROM table_contacts
        WHERE contactID = contact_id;
        CALL procedure_getContactCount(new_count);
        IF old_count - 1 = new_count
        THEN
            SET delete_code = TRUE;
        ELSE
            SET delete_code = FALSE;
        END IF;
    END //

# 查询记录 存储过程
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

# 修改记录 存储过程
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

# 获取联系人数量 存储过程
CREATE PROCEDURE procedure_getContactCount(OUT contactCount INT)
    BEGIN
        SET contactCount = (SELECT count(contact_id)
                            FROM table_contacts);
    END //

# 获取ID 函数
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

# 新增记录 函数
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
CREATE FUNCTION function_contactUpdate(contactName VARCHAR(45), contactInit VARCHAR(1),
                                       contactMark TEXT, userID INT,
                                       phoneID     INT)
    RETURNS INT
    BEGIN
        DECLARE id INT;
        SET id = function_getContactID(contactName, userID);
        IF id = -1
        THEN
            RETURN 1; #记录不存在
        END IF;
        CALL procedure_contactUpdate(contactName, contactInit, contactMark, userID, phoneID);
        RETURN 0; #更新成功
    END //