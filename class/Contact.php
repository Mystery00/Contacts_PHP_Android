<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

class Contact
{
    var $contactName;
    var $contactInit;
    var $contactMark;
    var $userID;

    function saveToDatabase($connection)
    {
        $sql = "INSERT INTO table_contact (contact_name, contact_init, contact_mark, user_id) VALUES ('$this->contactName', '$this->$this->contactInit', '$this->$this->contactMark', '$this->userID')";
        $result = mysqli_query($connection, $sql);
        echo $result;
    }
}