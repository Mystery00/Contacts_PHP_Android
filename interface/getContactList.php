<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once '../config.php';
require_once WWW . '/util/MysqlUtil.php';
require_once WWW . '/class/User.php';
require_once WWW . '/class/Contact.php';

$mysqli = connectDatabase();
if (!$mysqli) {
    echo json_encode(loginResponseFormat(1));
    return;
}

$user = new User();
$username = $_GET['username'];
$user->username = $username;
$user->userID = $user->getUserID($mysqli);
$contactList = $user->getContacts($mysqli);
echo json_encode($contactList);