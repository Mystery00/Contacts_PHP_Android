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
            $response->message = '该用户不存在';
            break;
        case 2:
            $response->message = '密码错误';
            break;
        case 3:
            $response->message = '数据库连接错误';
            break;
        case 4:
            $response->message = '用户名或密码为空';
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
            $response->message = '该用户已存在';
            break;
        case 2:
            $response->message = '注册失败';
            break;
        case 3:
            $response->message = '数据库连接错误';
            break;
        case 4:
            $response->message = '用户名或密码为空';
            break;
        default:
            $response->message = '其他错误';
            break;
    }
    return $response;
}

function actionResponseFormat($object, $action, int $code)
{
    $response = new Response();
    $response->code = $code;
    $message = '';
    switch ($object) {
        case 'contact':
            $object = '联系人';
            break;
        case 'phone':
            $object = '电话';
            break;
        case 'email':
            $object = '邮箱';
            break;
    }
    switch ($action) {
        case 'insert':
            $message = $object . '插入';
            break;
        case 'delete':
            $message = $object . '删除';
            break;
        case 'update':
            $message = $object . '更新';
            break;
        case 'database':
            $message .= '数据库连接';
            break;
    }
    switch ($code) {
        case -1:
            $response->message = $object . '已存在';
            break;
        case 0:
            $response->message = $message . '成功';
            break;
        case 1:
            switch ($action) {
                case 'insert':
                    $response->message = $object . '已存在';
                    break;
                case 'delete':
                    $response->message = $object . '不存在';
                    break;
                case 'update':
                    $response->message = $object . '不存在';
                    break;
            }
            break;
        case 2:
            $response->message = $message . '失败';
            break;
            break;
        case 3:
            $response->message = '参数不能为空';
            break;
        case 4:
            $response->message = '异常操作';
            break;
        default:
            $response->message = '未知错误';
            break;
    }
    return $response;
}