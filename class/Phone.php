<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

class Phone
{
    var $phoneID;
    var $phoneNumber;
    var $phoneType;
    var $contactID;

    function save(mysqli $mysqli)
    {
        $sql = "SELECT function_phoneInsert('$this->phoneNumber', '$this->phoneType',  '$this->contactID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }

    function getPhoneID(mysqli $mysqli)
    {
        $sql = "SELECT function_getPhoneID('$this->phoneNumber','$this->contactID')";
        return $mysqli->query($sql)->fetch_row()[0];
    }
}