<?php
/**
 * Created by PhpStorm.
 * User: myste
 */

require_once '../config.php';
require_once WWW . '/util/MysqlUtil.php';
require_once WWW . '/util/ResponseUtil.php';
require_once WWW . '/util/MysqlUtil.php';
require_once WWW . '/class/Contact.php';

$action = $_POST['action'];
$userID = $_POST['user_id'];

if (empty($action) || empty($userID)) {
    echo json_encode(actionResponseFormat('', -1));
    return;
}

$mysqli = connectDatabase();
if (!$mysqli) {
    echo json_encode(actionResponseFormat('database', 1));
    return;
}

switch ($action) {
    case 'insert':
        $contactName = $_POST['contact_name'];
        if (empty($contactName)) {
            echo json_encode(actionResponseFormat('', -1));
            return;
        }
        $contactInit = getFirstCharter($contactName);
        $contactMark = '';
        if (!empty($_POST['contact_mark']))
            $contactMark .= $_POST['contact_mark'];
        $contact = new Contact();
        $contact->userID = $userID;
        $contact->contactName = $contactName;
        $contact->contactInit = $contactInit;
        $contact->contactMark = $contactMark;
        switch ($contact->save($mysqli))
        {
            case 0:
                echo json_encode(actionResponseFormat($action, 0));
                break;
            case 1:
                echo json_encode(actionResponseFormat($action, 2));
                break;
            default:
                echo json_encode(actionResponseFormat($action, 1));
                break;
        }
        break;
}