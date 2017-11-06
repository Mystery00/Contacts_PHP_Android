<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once '../config.php';
require_once WWW . '/util/MysqlUtil.php';
require_once WWW . '/class/User.php';
require_once WWW . '/util/ResponseUtil.php';

$username = $_POST['username'];
$password = $_POST['password'];

$mysqli = connectDatabase();
if (!$mysqli) {
    echo json_encode(registerResponseFormat(3));
    return;
}

if (empty($username) || empty($password)) {
    echo json_encode(registerResponseFormat(4));
    return;
}

$user = new User();
$user->username = $username;
$user->password = $password;
$code = $user->register($mysqli);

echo json_encode(loginResponseFormat($code));