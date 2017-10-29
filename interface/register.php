<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once '../util/MysqlUtil.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    echo "error";
    return;
}

$mysqli = connectDatabase();
if (!$mysqli)
{
    echo "err";
    return;
}

$user = new User();
$user->username = $username;
$user->password = $password;
$result = $user->register($mysqli);
echo $result->num_rows;