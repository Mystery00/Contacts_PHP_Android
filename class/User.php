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
            $searchResult = $this->search($mysqli);
            if ($searchResult->num_rows != 1)
                return 3;//用户数量不唯一
            $sql = "SELECT password FROM table_user WHERE username='$this->username'";
            $result = $mysqli->query($sql);
            if ($result->num_rows != 1)
                return 4;//用户数量不唯一
            if ($result->fetch_array()[0] == $this->password)
                return 0;//登陆成功
            else
                return 5;//登录失败，未知原因
        } catch (mysqli_sql_exception $exception) {
            return 6;
        }
    }

    function register(mysqli $mysqli)
    {
        try {
            $searchResult = $this->search($mysqli);
            if ($searchResult->num_rows != 0)
                return -1;//用户已存在
            $sql = "INSERT INTO table_user (username, password) VALUES ('$this->username', '$this->password')";
            $result = $mysqli->query($sql);
            return $result;//返回插入影响行数
        } catch (mysqli_sql_exception $exception) {
            return -2;
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