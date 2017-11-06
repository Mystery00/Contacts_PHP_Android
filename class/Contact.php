<?php
/**
 * Created by PhpStorm.
 * User: myste
 */


require_once '../config.php';
require_once WWW . '/class/Phone.php';

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
        $sql = "SELECT function_contactInsert('$this->contactName', '$this->contactInit', '$this->contactMark', '$this->userID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }

    function delete(mysqli $mysqli)
    {
        $sql = "SELECT function_contactDelete('$this->contactName', '$this->userID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }

    function search(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_contacts";
        $sql .= " WHERE ";
        if (!empty($this->contactName))
            $sql .= "contact_name = '$this->contactName' AND ";
        if (!empty($this->contactInit))
            $sql .= "contact_init = '$this->contactInit' AND ";
        if (!empty($this->contactMark))
            $sql .= "contact_mark = '$this->contactMark' AND ";
        $sql .= "user_id = '$this->userID'";
        $result = $mysqli->query($sql);
        if ($result->num_rows == 1)
            $this->contactID = $result[0]['contact_id'];
        return $result;
    }

    function update(mysqli $mysqli)
    {
        $sql = "SELECT function_contactUpdate('$this->contactName','$this->contactInit','$this->contactMark','$this->userID','$this->contactID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }

    function getContactID(mysqli $mysqli)
    {
        $sql = "SELECT function_getContactID('$this->contactName','$this->userID')";
        return $mysqli->query($sql)->fetch_row()[0];
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
            $this->phoneList[$index] = $temp;
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
            $this->emailList[$index] = $temp;
            $index++;
        }
    }
}