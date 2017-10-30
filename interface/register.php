<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once '../config.php';
require_once WWW . '/util/MysqlUtil.php';
require_once WWW . '/class/User.php';

$response = new Response();

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    $response->code = 1;
    $response->message = '用户名或者密码为空！';
    echo json_encode($response);
    return;
}

$mysqli = connectDatabase();
if (!$mysqli) {
    $response->code = 3;
    $response->message = '数据库连接错误！';
    echo json_encode($response);
    return;
}

$user = new User();
$user->username = $username;
$user->password = $password;
$result = $user->register($mysqli);
switch ($result) {
    case 0:
        $response->code = 0;
        $response->message = '注册成功！';
        break;
    case -1:
        $response->code = 2;
        $response->message = '该用户已存在！';
        break;
    default:
        $response->code = 4;
        $response->message = '注册失败！';
        break;
}
echo json_encode($response);