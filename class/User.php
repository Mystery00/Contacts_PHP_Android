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

    function register($mysqli)
    {
        echo "注册";
        $sql = "INSERT INTO table_user (username, password) VALUES ('$this->username', '$this->password')";
        $result = $mysqli->query($sql);
        return $result;
    }
}