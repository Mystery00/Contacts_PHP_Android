<?php

define('ServerName', 'localhost');
define('db_username', 'root');
define('db_password', 'root');
define('db_name', 'db_contacts');

function connectDatabase()
{
    $mysqli = new mysqli(ServerName, db_username, db_password, db_name);
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        return false;
    }
    return $mysqli;
}

/**
 * @param $str
 *      要获取首字母的字符串
 * @return string
 *      获取到的首字母
 */
function getFirstCharter($str)
{
    if (empty($str)) {
        return '';
    }
    $first_char = ord($str{0});
    if ($first_char >= ord('0') && $first_char <= ord('9'))
        return chr($first_char);
    if ($first_char >= ord('A') && $first_char <= ord('z'))
        return strtoupper($str{0});
    $s1 = iconv('UTF-8', 'gb2312', $str);
    $s2 = iconv('gb2312', 'UTF-8', $s1);
    $s = $s2 == $str ? $s1 : $str;
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 && $asc <= -20284)
        return 'A';
    if ($asc >= -20283 && $asc <= -19776)
        return 'B';
    if ($asc >= -19775 && $asc <= -19219)
        return 'C';
    if ($asc >= -19218 && $asc <= -18711)
        return 'D';
    if ($asc >= -18710 && $asc <= -18527)
        return 'E';
    if ($asc >= -18526 && $asc <= -18240)
        return 'F';
    if ($asc >= -18239 && $asc <= -17923)
        return 'G';
    if ($asc >= -17922 && $asc <= -17418)
        return 'H';
    if ($asc >= -17417 && $asc <= -16475)
        return 'J';
    if ($asc >= -16474 && $asc <= -16213)
        return 'K';
    if ($asc >= -16212 && $asc <= -15641)
        return 'L';
    if ($asc >= -15640 && $asc <= -15166)
        return 'M';
    if ($asc >= -15165 && $asc <= -14923)
        return 'N';
    if ($asc >= -14922 && $asc <= -14915)
        return 'O';
    if ($asc >= -14914 && $asc <= -14631)
        return 'P';
    if ($asc >= -14630 && $asc <= -14150)
        return 'Q';
    if ($asc >= -14149 && $asc <= -14091)
        return 'R';
    if ($asc >= -14090 && $asc <= -13319)
        return 'S';
    if ($asc >= -13318 && $asc <= -12839)
        return 'T';
    if ($asc >= -12838 && $asc <= -12557)
        return 'W';
    if ($asc >= -12556 && $asc <= -11848)
        return 'X';
    if ($asc >= -11847 && $asc <= -11056)
        return 'Y';
    if ($asc >= -11055 && $asc <= -10247)
        return 'Z';
    return '0';
}