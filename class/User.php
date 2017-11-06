<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once 'Response.php';

class User
{
    var $userID;
    var $username;
    var $password;

    /**
     * @param mysqli $mysqli
     * @return bool|int|mysqli_result
     */
    function login(mysqli $mysqli)
    {
        try {
            $sql = "SELECT function_login('$this->username','$this->password')";
            $result = $mysqli->query($sql)->fetch_row()[0];
            return $result;
        } catch (mysqli_sql_exception $exception) {
            return 5;
        }
    }

    function register(mysqli $mysqli)
    {
        try {
            $sql = "SELECT function_register('$this->username','$this->password')";
            $result = $mysqli->query($sql)->fetch_row()[0];
            return $result;
        } catch (mysqli_sql_exception $exception) {
            return 5;
        }
    }

    function search(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_user WHERE username='$this->username'";
        $result = $mysqli->query($sql);
        return $result;
    }

    function getContacts(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_contacts WHERE user_id = $this->userID";
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
            $list[$index] = $temp;
            $index++;
        }
        return $list;
    }
}