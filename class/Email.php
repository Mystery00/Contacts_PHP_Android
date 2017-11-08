<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

class Email
{
    var $emailID;
    var $emailAddress;
    var $contactID;

    function save(mysqli $mysqli)
    {
        $sql = "SELECT function_emailInsert('$this->emailAddress',  '$this->contactID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }

    function getPhoneID(mysqli $mysqli)
    {
        $sql = "SELECT function_getEmailID('$this->emailAddress','$this->contactID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }
}