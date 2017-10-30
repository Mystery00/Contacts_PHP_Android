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
$result = $user->login($mysqli);
if ($result instanceof mysqli_result)
    switch ($result->num_rows) {
        case 1:
            $response->code = 0;
            $response->message = '登录成功！';
            break;
        default:
            $response->code = 4;
            $response->message = '登陆失败！';
            break;
    }
else if ($result == -1) {
    $response->code = 2;
    $response->message = '该用户不存在！';
}else
{
    $response->code = 5;
    $response->message = '登陆失败！';
}
echo json_encode($response);