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

    function login()
    {

    }

    function register(mysqli $mysqli)
    {
        $searchResult = $this->search($mysqli);
        if ($searchResult->num_rows != 0)
            return -233;
        $sql = "INSERT INTO table_user (username, password) VALUES ('$this->username', '$this->password')";
        $result = $mysqli->query($sql);
        return $result;
    }

    function search(mysqli $mysqli)
    {
        $sql = "SELECT * FROM table_user WHERE username='$this->username'";
        $result = $mysqli->query($sql);
        return $result;
    }
}