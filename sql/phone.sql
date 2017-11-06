USE db_contacts;
DELIMITER //

# 新增记录 存储过程
CREATE PROCEDURE procedure_phoneInsert(phoneNumber VARCHAR(20), phoneType VARCHAR(45),
                                       contactID   INT,
    OUT                                insert_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        CALL procedure_getPhoneCount(old_count);
        INSERT INTO table_phone (phone_number, phone_type, contact_id)
        VALUES (phoneNumber, phoneType, contactID);
        CALL procedure_getPhoneCount(new_count);
        IF old_count + 1 = new_count
        THEN
            SET insert_code = TRUE;
        ELSE
            SET insert_code = FALSE;
        END IF;
    END //

# 删除记录 存储过程
CREATE PROCEDURE procedure_phoneDelete(phoneID INT, OUT delete_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        CALL procedure_getPhoneCount(old_count);
        DELETE FROM table_phone
        WHERE phoneID = phone_id;
        CALL procedure_getPhoneCount(new_count);
        IF old_count - 1 = new_count
        THEN
            SET delete_code = TRUE;
        ELSE
            SET delete_code = FALSE;
        END IF;
    END //

# 查询记录 存储过程
CREATE PROCEDURE procedure_checkPhone(phoneNumber VARCHAR(20), contactID INT,
    OUT                               check_code  BOOLEAN)
    BEGIN
        DECLARE temp INT;
        SET temp = (SELECT count(phone_id)
                    FROM table_phone
                    WHERE phoneNumber = phone_number AND contactID = contact_id);
        IF temp = 1
        THEN
            SET check_code = TRUE;
        ELSE
            SET check_code = FALSE;
        END IF;
    END //

# 修改记录 存储过程
CREATE PROCEDURE procedure_phoneUpdate(phoneNumber VARCHAR(20), phoneType VARCHAR(45),
                                       contactID   INT,
                                       phoneID     INT)
    BEGIN
        UPDATE table_phone
        SET phone_number = phoneNumber, phone_type = phoneType, contact_id = contactID
        WHERE phone_id = phoneID;
    END //

# 获取联系人数量 存储过程
CREATE PROCEDURE procedure_getPhoneCount(OUT phoneCount INT)
    BEGIN
        SET phoneCount = (SELECT count(phone_id)
                          FROM table_phone);
    END //

# 获取ID 函数
CREATE FUNCTION function_getPhoneID(phoneNumber VARCHAR(20), contactID INT)
    RETURNS INT
    BEGIN
        DECLARE check_code BOOLEAN;
        CALL procedure_checkPhone(phoneNumber, contactID, check_code);
        IF check_code
        THEN
            RETURN (SELECT phone_id
                    FROM table_phone
                    WHERE phoneNumber = phone_number AND contactID = contact_id);
        ELSE
            RETURN -1;
        END IF;
    END //

# 新增记录 函数
CREATE FUNCTION function_phoneInsert(phoneNumber VARCHAR(20), phoneType VARCHAR(45), contactID INT)
    RETURNS INT
    BEGIN
        DECLARE insert_code BOOLEAN;
        DECLARE isExist BOOLEAN;
        CALL procedure_checkPhone(phoneNumber, contactID, isExist);
        IF isExist
        THEN
            RETURN 1; #联系人已存在
        END IF;
        CALL procedure_phoneInsert(phoneNumber, phoneType, contactID, insert_code);
        IF insert_code
        THEN
            RETURN 0; #插入成功
        ELSE
            RETURN 2; #插入失败
        END IF;
    END //

# 删除记录 函数
CREATE FUNCTION function_phoneDelete(phoneNumber VARCHAR(20), contactID INT)
    RETURNS INT
    BEGIN
        DECLARE id INT;
        DECLARE delete_code BOOLEAN;
        SET id = function_getPhoneID(phoneNumber, contactID);
        IF id = -1
        THEN
            RETURN 1; #记录不存在
        END IF;
        CALL procedure_phoneDelete(id, delete_code);
        IF delete_code
        THEN
            RETURN 0; #删除成功
        ELSE
            RETURN 2; #删除失败
        END IF;
    END //

# 修改记录 函数
CREATE FUNCTION function_phoneUpdate(phoneNumber VARCHAR(20), phoneType VARCHAR(45), contactID INT,
                                     phoneID     INT)
    RETURNS INT
    BEGIN
        DECLARE id INT;
        SET id = function_getPhoneID(phoneNumber, contactID);
        IF id = -1
        THEN
            RETURN 1; #记录不存在
        END IF;
        CALL procedure_phoneUpdate(phoneNumber, phoneType, contactID, phoneID);
        RETURN 0; #更新成功
    END //