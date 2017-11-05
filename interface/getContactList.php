<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once '../config.php';
require_once WWW . '/util/MysqlUtil.php';
require_once WWW . '/class/User.php';
require_once WWW . '/class/Contact.php';

$contact = new Contact();

$mysqli = connectDatabase();
if (!$mysqli) {
    echo json_encode(loginResponseFormat(1));
    return;
}

$user = new User();
$userID = $_GET['user_id'];
$user->userID = $userID;
$contactList = $user->getContacts($mysqli);
echo json_encode($contactList);