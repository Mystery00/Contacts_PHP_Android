USE db_contacts;
DELIMITER //

# 新增电话号码时更新联系人信息 触发器
CREATE TRIGGER trigger_updatePhoneCountInsert
AFTER INSERT ON table_phone
FOR EACH ROW
    BEGIN
        DECLARE num INT;
        SET num = (SELECT count(phone_id)
                   FROM table_phone
                   WHERE contact_id = NEW.contact_id);
        UPDATE table_contacts
        SET phone_count = num
        WHERE contact_id = NEW.contact_id;
    END //

# 新增邮箱时更新联系人信息 触发器
CREATE TRIGGER trigger_updateEmailCountInsert
AFTER INSERT ON table_email
FOR EACH ROW
    BEGIN
        DECLARE num INT;
        SET num = (SELECT count(email_id)
                   FROM table_email
                   WHERE contact_id = NEW.contact_id);
        UPDATE table_contacts
        SET email_count = num
        WHERE contact_id = NEW.contact_id;
    END //

# 更新联系人信息时更新数据 触发器
CREATE TRIGGER trigger_updateCount
AFTER UPDATE ON table_contacts
FOR EACH ROW
    BEGIN
        DECLARE phoneCount INT;
        DECLARE emailCount INT;
        SET phoneCount = (SELECT count(phone_id)
                          FROM table_phone
                          WHERE contact_id = NEW.contact_id);
        UPDATE table_contacts
        SET phone_count = phoneCount
        WHERE contact_id = NEW.contact_id;
        SET emailCount = (SELECT count(email_id)
                          FROM table_email
                          WHERE contact_id = NEW.contact_id);
        UPDATE table_contacts
        SET email_count = emailCount
        WHERE contact_id = NEW.contact_id;
    END //

# 删除电话号码时更新联系人信息 触发器
CREATE TRIGGER trigger_updatePhoneCountDelete
AFTER DELETE ON table_phone
FOR EACH ROW
    BEGIN
        DECLARE num INT;
        SET num = (SELECT count(phone_id)
                   FROM table_phone
                   WHERE contact_id = OLD.contact_id);
        UPDATE table_contacts
        SET phone_count = num
        WHERE contact_id = OLD.contact_id;
    END //

# 删除邮箱时更新联系人信息 触发器
CREATE TRIGGER trigger_updateEmailCountDelete
AFTER DELETE ON table_email
FOR EACH ROW
    BEGIN
        DECLARE num INT;
        SET num = (SELECT count(email_id)
                   FROM table_email
                   WHERE contact_id = OLD.contact_id);
        UPDATE table_contacts
        SET email_count = num
        WHERE contact_id = OLD.contact_id;
    END //

# 删除联系人时删除对应信息
CREATE TRIGGER trigger_deleteInfo
BEFORE DELETE ON table_contacts
FOR EACH ROW
    BEGIN
        DELETE FROM table_phone
        WHERE table_phone.contact_id = table_contacts.contact_id;
        DELETE FROM table_email
        WHERE table_email.contact_id = table_contacts.contact_id;
    END //