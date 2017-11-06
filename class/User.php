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
        $sql = "CALL procedure_getUserInfo('$this->username')";
        $result = $mysqli->query($sql)->fetch_array();
        return $result;
    }

    function getUserID(mysqli $mysqli)
    {
        $sql = "SELECT function_getUserID('$this->username')";
        return $mysqli->query($sql)->fetch_row()[0];
    }

    function getContacts(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_contacts WHERE user_id = '$this->userID'";
        $result = $mysqli->query($sql);
        $list = array();
        $index = 0;
        while ($row = $result->fetch_array()) {
            $temp = new Contact();
            $temp->contactID = $row['contact_id'];
            $temp->contactName = $row['contact_name'];
            $temp->contactInit = $row['contact_init'];
            $temp->contactMark = $row['contact_mark'];
            $temp->userID = $this->userID;
            $list[$index] = $temp;
            $index++;
        }
        foreach ($list as $temp) {
            $temp->getPhoneList($mysqli);
            $temp->getEmailList($mysqli);
        }
        return $list;
    }
}