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
    echo json_encode(actionResponseFormat('contact', '', 3));
    return;
}

$mysqli = connectDatabase();
if (!$mysqli) {
    echo json_encode(actionResponseFormat('contact', 'database', 2));
    return;
}

switch ($action) {
    case 'insert':
        $contactName = $_POST['contact_name'];
        if (empty($contactName)) {
            echo json_encode(actionResponseFormat('contact', $action, 3));
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
        echo json_encode(actionResponseFormat('contact', $action, $contact->save($mysqli)));
        break;
    case 'delete':
        $contactName = $_POST['contact_name'];
        if (empty($contactName)) {
            echo json_encode(actionResponseFormat('contact', $action, 3));
            return;
        }
        $contact = new Contact();
        $contact->userID = $userID;
        $contact->contactName = $contactName;
        echo json_encode(actionResponseFormat('contact', $action, $contact->delete($mysqli)));
        break;
    case 'search':
        $contact = new Contact();
        $contact->userID = $userID;
        if (!empty($_POST['contact_name']))
            $contact->contactName = $_POST['contact_name'];
        if (!empty($_POST['contact_init']))
            $contact->contactInit = $_POST['contact_init'];
        if (!empty($_POST['contact_mark']))
            $contact->contactMark = $_POST['contact_mark'];
        echo json_encode($contact->search($mysqli)->fetch_assoc());
        break;
    case 'update':
        $contact = new Contact();
        $contact->userID = $userID;
        if (empty($_POST['old_contact_name'])) {
            echo json_encode(actionResponseFormat('contact', $action, 3));
            return;
        }
        $contact->contactName = $_POST['old_contact_name'];
        $contact->contactID = $contact->getContactID($mysqli);
        if ($contact->contactID == -1) {
            echo json_encode(actionResponseFormat('contact', $action, 1));
            return;
        }
        if (!empty($_POST['contact_name'])) {
            $contactName = $_POST['contact_name'];
            $contactInit = getFirstCharter($contactName);
            $contact->contactName = $contactName;
            $contact->contactInit = $contactInit;
        }
        if (!empty($_POST['contact_mark']))
            $contact->contactMark = $_POST['contact_mark'];
        echo json_encode(actionResponseFormat('contact', $action, $contact->update($mysqli)));
        break;
    default:
        echo json_encode(actionResponseFormat('contact', $action, 4));
        break;
}