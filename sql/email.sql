USE db_contacts;
DELIMITER //

# 新增记录 存储过程
CREATE PROCEDURE procedure_emailInsert(emailAddress VARCHAR(45), contactID INT,
    OUT                                insert_code  BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        CALL procedure_getEmailCount(old_count);
        INSERT INTO table_email (email_address, contact_id)
        VALUES (emailAddress, contactID);
        CALL procedure_getEmailCount(new_count);
        IF old_count + 1 = new_count
        THEN
            SET insert_code = TRUE;
        ELSE
            SET insert_code = FALSE;
        END IF;
    END //

# 删除记录 存储过程
CREATE PROCEDURE procedure_emailDelete(emailID INT, delete_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        CALL procedure_getEmailCount(old_count);
        DELETE FROM table_email
        WHERE emailID = email_id;
        CALL procedure_getEmailCount(new_count);
        IF old_count + 1 = new_count
        THEN
            SET delete_code = TRUE;
        ELSE
            SET delete_code = FALSE;
        END IF;
    END //

# 查询记录 存储过程
CREATE PROCEDURE procedure_checkEmail(emailAddress VARCHAR(45), contactID INT, check_code BOOLEAN)
    BEGIN
        DECLARE temp INT;
        SET temp = (SELECT count(email_id)
                    FROM table_email
                    WHERE emailAddress = email_address AND contactID = contact_id);
        IF temp = 1
        THEN
            SET check_code = TRUE;
        ELSE
            SET check_code = FALSE;
        END IF;
    END //

# 修改记录 存储过程
CREATE PROCEDURE procedure_emailUpdate(emailAddress VARCHAR(45),
                                       contactID    INT,
                                       emailID      INT)
    BEGIN
        UPDATE table_email
        SET email_address = emailAddress, contact_id = contactID
        WHERE email_id = emailID;
    END //

# 获取联系人数量 存储过程
CREATE PROCEDURE procedure_getEmailCount(OUT emailCount INT)
    BEGIN
        SET emailCount = (SELECT count(email_id)
                            FROM table_email);
    END //

# 获取ID 函数
CREATE FUNCTION function_getEmailID(emailAddress VARCHAR(45), contactID INT)
    RETURNS INT
    BEGIN
        DECLARE check_code BOOLEAN;
        CALL procedure_checkEmail(emailAddress, contactID, check_code);
        IF check_code
        THEN
            RETURN (SELECT email_id
                    FROM table_email
                    WHERE emailAddress = email_address AND contactID = contact_id);
        ELSE
            RETURN -1;
        END IF;
    END //

# 新增记录 函数
CREATE FUNCTION function_emailInsert(emailAddress VARCHAR(45), contactID INT)
    RETURNS INT
    BEGIN
        DECLARE insert_code BOOLEAN;
        DECLARE isExist BOOLEAN;
        CALL procedure_checkEmail(emailAddress, contactID, isExist);
        IF isExist
        THEN
            RETURN 1; #联系人已存在
        END IF;
        CALL procedure_emailInsert(emailAddress, contactID, insert_code);
        IF insert_code
        THEN
            RETURN 0; #插入成功
        ELSE
            RETURN 2; #插入失败
        END IF;
    END //

# 删除记录 函数
CREATE FUNCTION function_emailDelete(emailAddress VARCHAR(45), contactID INT)
    RETURNS INT
    BEGIN
        DECLARE id INT;
        DECLARE delete_code BOOLEAN;
        SET id = function_getEmailID(emailAddress, contactID);
        IF id = -1
        THEN
            RETURN 1; #记录不存在
        END IF;
        CALL procedure_emailDelete(id, delete_code);
        IF delete_code
        THEN
            RETURN 0; #删除成功
        ELSE
            RETURN 2; #删除失败
        END IF;
    END //

# 修改记录 函数
CREATE FUNCTION function_emailUpdate(emailAddress VARCHAR(45), contactID INT,
                                     emailID      INT)
    RETURNS INT
    BEGIN
        DECLARE id INT;
        SET id = function_getEmailID(emailAddress, contactID);
        IF id = -1
        THEN
            RETURN 1; #记录不存在
        END IF;
        CALL procedure_emailUpdate(emailAddress, contactID, emailID);
        RETURN 0; #更新成功
    END //