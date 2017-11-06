USE db_contacts;
DELIMITER //

# 删除联系人时删除对应信息
DROP TRIGGER IF EXISTS trigger_deleteInfo;
CREATE TRIGGER trigger_deleteInfo
BEFORE DELETE ON table_contacts
FOR EACH ROW
    BEGIN
        DELETE FROM table_phone
        WHERE table_phone.contact_id = OLD.contact_id;
        DELETE FROM table_email
        WHERE table_email.contact_id = OLD.contact_id;
    END //