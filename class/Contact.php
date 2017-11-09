<?php
/**
 * Created by PhpStorm.
 * User: myste
 */


require_once '../config.php';
require_once WWW . '/class/Phone.php';
require_once WWW . '/class/Email.php';

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
        $code = $mysqli->query($sql)->fetch_row()[0];
        if ($code == '0') {
            $this->contactID = $mysqli->query("SELECT function_getContactID('$this->contactName','$this->userID')")->fetch_row()[0];
            foreach ($this->phoneList as $item) {
                $phone = new Phone();
                $phone->phoneNumber = $item->phoneNumber;
                $phone->phoneType = $item->phoneType;
                $phone->contactID = $this->contactID;
                $temp_code = $phone->save($mysqli);
                if ($temp_code != '0')
                    return $temp_code;
            }
            foreach ($this->emailList as $item) {
                $email = new Email();
                $email->emailAddress = $item->emailAddress;
                $email->contactID = $this->contactID;
                $temp_code = $email->save($mysqli);
                if ($temp_code != '0')
                    return $temp_code;
            }
        }
        return $code;
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
            $sql .= "contact_name like '%$this->contactName%' AND ";
        if (!empty($this->contactInit))
            $sql .= "contact_init like '%$this->contactInit%' AND ";
        if (!empty($this->contactMark))
            $sql .= "contact_mark like '%$this->contactMark%' AND ";
        $sql .= "user_id = '$this->userID'";
        $result = $mysqli->query($sql);
        $list = array();
        $index = 0;
        while ($row = $result->fetch_assoc()) {
            $temp = new Contact();
            $temp->contactID = $row['contact_id'];
            $temp->contactName = $row['contact_name'];
            $temp->contactInit = $row['contact_init'];
            $temp->contactMark = $row['contact_mark'];
            $temp->getPhoneList($mysqli);
            $temp->getEmailList($mysqli);
            $temp->userID = $this->userID;
            $list[$index] = $temp;
            $index++;
        }
        return $list;
    }

    function update(mysqli $mysqli)
    {
        $sql = "SELECT function_contactUpdate('$this->contactName','$this->contactInit','$this->contactMark','$this->userID','$this->contactID')";
        $code = $mysqli->query($sql)->fetch_row()[0];
        if ($code == '0') {
            $this->contactID = $mysqli->query("SELECT function_getContactID('$this->contactName','$this->userID')")->fetch_row()[0];
            if ($this->deletePhone($mysqli) != '0') {
                return 2;
            }
            if ($this->deleteEmail($mysqli) != '0') {
                return 2;
            }
            foreach ($this->phoneList as $item) {
                $phone = new Phone();
                $phone->phoneNumber = $item->phoneNumber;
                $phone->phoneType = $item->phoneType;
                $phone->contactID = $this->contactID;
                $temp_code = $phone->save($mysqli);
                if ($temp_code != '0')
                    return 2;
            }
            foreach ($this->emailList as $item) {
                $email = new Email();
                $email->emailAddress = $item->emailAddress;
                $email->contactID = $this->contactID;
                $temp_code = $email->save($mysqli);
                if ($temp_code != '0')
                    return 2;
            }
        }
        return $code;
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

    function deletePhone(mysqli $mysqli)
    {
        $sql = "SELECT function_phoneDeleteForContact('$this->contactID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }

    function deleteEmail(mysqli $mysqli)
    {
        $sql = "SELECT function_emailDeleteForContact('$this->contactID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }
}
