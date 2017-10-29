<?php

define('ServerName', 'localhost');
define('db_username', 'root');
define('db_password', 'root');
define('db_name', 'db_contacts');

function connectDatabase()
{
    $mysqli = new mysqli(ServerName, db_username, db_password, db_name);
    if ($mysqli->connect_error) {
        die('连接错误' . $mysqli->connect_error);
    }
    return true;
}