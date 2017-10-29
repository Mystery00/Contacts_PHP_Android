<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once '../config.php';
require_once WWW . '/util/MysqlUtil.php';
require_once WWW . '/class/User.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    echo "error";
    return;
}

$mysqli = connectDatabase();
if (!$mysqli)
    return;

$user = new User();
$user->username = $username;
$user->password = $password;
$result = $user->register($mysqli);
$response = new Response();
switch ($result) {
    case 1:
        $response->code = 0;
        $response->message = '注册成功！';
        break;
    case -233:
        $response->code = 1;
        $response->message = '该用户已存在！';
        break;
    default:
        $response->code = 2;
        $response->message = '注册失败！';
        break;
}
echo json_encode($response);