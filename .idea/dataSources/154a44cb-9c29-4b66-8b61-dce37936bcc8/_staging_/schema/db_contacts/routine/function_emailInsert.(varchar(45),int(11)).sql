CREATE PROCEDURE procedure_emailInsert1(emailAddress VARCHAR(45), contactID INT)
    BEGIN
        DECLARE insert_code BOOLEAN;
        DECLARE isExist BOOLEAN;
        DECLARE temp INT;
        SET temp = (SELECT count(email_id)
                    FROM table_email
                    WHERE emailAddress = email_address AND contactID = contact_id);
        IF temp = 1
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
    END;
