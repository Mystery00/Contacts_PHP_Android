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
    echo json_encode(registerResponseFormat(1));
    return;
}

if (empty($username) || empty($password)) {
    echo json_encode(registerResponseFormat(2));
    return;
}

$user = new User();
$user->username = $username;
$user->password = $password;
$result = $user->register($mysqli);
switch ($result) {
    case 0:
        echo json_encode(registerResponseFormat(0));
        break;
    case -1:
        echo json_encode(registerResponseFormat(3));
        break;
    default:
        echo json_encode(registerResponseFormat(4));
        break;
}