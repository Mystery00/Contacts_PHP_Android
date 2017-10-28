<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

class User
{
    var $userID;
    var $username;
    var $password;

    function login()
    {

    }

    function register($connection)
    {
        $sql = "INSERT INTO table_user (username, password) VALUES ('$this->username', '$this->password')";
        $result = mysqli_query($connection, $sql);
        return $result;
    }
}