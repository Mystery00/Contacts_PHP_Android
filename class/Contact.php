<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

class Contact
{
    var $contactID;
    var $contactName;
    var $contactInit;
    var $contactMark;
    var $phoneList;
    var $emailList;
    var $userID;

    function save(mysqli $mysqli)
    {
        $check = $this->search($mysqli);
        if ($check->num_rows > 0)
            return 1;//联系人已存在
        $sql = "INSERT INTO table_contacts (contact_name, contact_init, contact_mark, user_id) VALUES ('$this->contactName', '$this->contactInit', '$this->contactMark', '$this->userID')";
        $result = $mysqli->query($sql);
        if ($result == 1)
            return 0;//插入成功
        else
            return 2;//插入失败
    }

    function delete(mysqli $mysqli)
    {
        $check = $this->search($mysqli);
        if ($check->num_rows != 1)
            return 1;//联系人不存在
        $sql = "DELETE FROM table_contacts WHERE contact_id = '$this->contactID'";
        $result = $mysqli->query($sql);
        if ($result == 1)
            return 0;//删除成功
        else
            return 2;//删除失败
    }

    function search(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_contacts WHERE contact_name = '$this->contactName' AND user_id = '$this->userID'";
        $result = $mysqli->query($sql);
        if ($result->num_rows == 1)
            $this->contactID = $result[0]['contact_id'];
        return $result;
    }

    function update(mysqli $mysqli)
    {
        $check = $this->search($mysqli);
        if ($check->num_rows != 1)
            return 1;//联系人不存在
        $sql = "DELETE FROM table_contacts WHERE contact_name = '$this->contactName' AND user_id = '$this->userID'";
        $result = $mysqli->query($sql);
        if ($result == 1)
            return 0;//删除成功
        else
            return 2;//删除失败
    }

    function getPhoneList(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_phone WHERE contact_id = '$this->contactID'";
        $result = $mysqli->query($sql);
        $this->phoneList = array();
        $index = 0;
        while ($row = $result->fetch_assoc()) {
            $temp = new Phone();
            $temp->phoneID = $row['phone_id'];
            $temp->phoneNumber = $row['phone_number'];
            $temp->phoneType = $row['phone_type'];
            $list[$index] = $temp;
            $index++;
        }
    }

    function getEmailList(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_email WHERE contact_id = '$this->contactID'";
        $result = $mysqli->query($sql);
        $this->emailList = array();
        $index = 0;
        while ($row = $result->fetch_assoc()) {
            $temp = new Email();
            $temp->emailID = $row['email_id'];
            $temp->emailAddress = $row['email_address'];
            $list[$index] = $temp;
            $index++;
        }
    }
}