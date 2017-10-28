<?php

define('ServerName', 'localhost');
define('db_username', 'root');
define('db_password', 'root');
define('db_name', 'db_contacts');

function connectDatabase()
{
    return mysqli_connect(ServerName, db_username, db_password, db_name) or die("数据库错误，错误信息：" . mysqli_connect_error());
}