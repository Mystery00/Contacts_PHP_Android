<?php
/**
 * Created by PhpStorm.
 * User: myste
 */
require_once '../config.php';
require_once WWW . '/class/Response.php';

function loginResponseFormat(int $code)
{
    $response = new Response();
    $response->code = $code;
    switch ($code) {
        case 0:
            $response->message = '登陆成功';
            break;
        case 1:
            $response->message = '数据库连接错误';
            break;
        case 2:
            $response->message = '用户名或者密码为空';
            break;
        case 3:
            $response->message = '该用户不存在';
            break;
        case 4:
            $response->message = '用户不唯一';
            break;
        case 5:
            $response->message = '密码错误';
            break;
        default:
            $response->message = '其他错误';
            break;
    }
    return $response;
}

function registerResponseFormat(int $code)
{
    $response = new Response();
    $response->code = $code;
    switch ($code) {
        case 0:
            $response->message = '注册成功';
            break;
        case 1:
            $response->message = '数据库连接错误';
            break;
        case 2:
            $response->message = '用户名或者密码为空';
            break;
        case 3:
            $response->message = '该用户已存在';
            break;
        default:
            $response->message = '其他错误';
            break;
    }
    return $response;
}