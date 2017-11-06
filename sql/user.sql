USE db_contacts;
DELIMITER //

# 登陆 存储过程
DROP PROCEDURE IF EXISTS procedure_login;
CREATE PROCEDURE procedure_login(new_username TEXT, new_password TEXT, OUT login_code INT)
    BEGIN
        SET login_code = (SELECT count(user_id)
                          FROM table_user
                          WHERE new_username = username AND new_password = password);
    END //

# 注册 存储过程
DROP PROCEDURE IF EXISTS procedure_register;
CREATE PROCEDURE procedure_register(new_username TEXT, new_password TEXT, OUT register_code BOOLEAN)
    BEGIN
        DECLARE old_count INT;
        DECLARE new_count INT;
        CALL procedure_getUserCount(old_count);
        INSERT INTO table_user (username, password) VALUES (new_username, new_password);
        CALL procedure_getUserCount(new_count);
        IF old_count + 1 = new_count
        THEN
            SET register_code = TRUE;
        ELSE
            SET register_code = FALSE;
        END IF;
    END //

# 获取当前用户数量 存储过程
DROP PROCEDURE IF EXISTS procedure_getUserCount;
CREATE PROCEDURE procedure_getUserCount(OUT userCount INT)
    BEGIN
        SET userCount = (SELECT count(user_id)
                         FROM table_user);
    END //

# 检查用户是否存在 存储过程
DROP PROCEDURE IF EXISTS procedure_checkUser;
CREATE PROCEDURE procedure_checkUser(new_username TEXT, OUT check_code BOOLEAN)
    BEGIN
        DECLARE temp INT;
        SET temp = (SELECT count(user_id)
                    FROM table_user
                    WHERE new_username = username);
        IF temp = 1
        THEN
            SET check_code = TRUE;
        ELSE
            SET check_code = FALSE;
        END IF;
    END //

# 查询用户信息 存储过程
DROP PROCEDURE IF EXISTS procedure_getUserInfo;
CREATE PROCEDURE procedure_getUserInfo(new_username TEXT)
    BEGIN
        SELECT *
        FROM table_user
        WHERE username = new_username;
    END //

# 登陆 函数
DROP FUNCTION IF EXISTS function_login;
CREATE FUNCTION function_login(new_username TEXT, new_password TEXT)
    RETURNS INT
    BEGIN
        DECLARE login_code INT;
        DECLARE isExist BOOLEAN;
        CALL procedure_checkUser(new_username, isExist);
        IF !isExist
        THEN
            RETURN 1; #用户不存在
        END IF;
        SET login_code = -1;
        CALL procedure_login(new_username, new_password, login_code);
        IF login_code = 1
        THEN
            RETURN 0; #登陆成功
        ELSE
            RETURN 2; #密码错误
        END IF;
    END //

# 注册 函数
DROP FUNCTION IF EXISTS function_register;
CREATE FUNCTION function_register(new_username TEXT, new_password TEXT)
    RETURNS INT
    BEGIN
        DECLARE register_code BOOLEAN;
        DECLARE isExist BOOLEAN;
        CALL procedure_checkUser(new_username, isExist);
        IF isExist
        THEN
            RETURN 1; #用户已存在
        END IF;
        CALL procedure_register(new_username, new_password, register_code);
        IF register_code
        THEN
            RETURN 0; #注册成功
        ELSE
            RETURN 2; #注册失败
        END IF;
    END //

# 查询用户id 函数
DROP FUNCTION IF EXISTS function_getUserID;
CREATE FUNCTION function_getUserID(username TEXT)
    RETURNS INT
    BEGIN
        RETURN (SELECT user_id
                FROM table_user
                WHERE table_user.username = username);
    END //