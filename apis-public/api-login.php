<?php

session_start();
ini_set('display_errors', 1);
require_once '../connect.php';

$sEmail = $_POST['txtLoginEmail'] ?? '';
if (empty($sEmail)) {
    sendResponse(0, __LINE__, 'Email is empty', '');
}
if (!filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
    sendResponse(0, __LINE__, 'This is not a valid email address', '');
}

$sPassword = $_POST['txtLoginPassword'] ?? '';
if (empty($sPassword)) {
    sendResponse(0, __LINE__, 'Password is empty', '');
}
if (strlen($sPassword) < 4) {
    sendResponse(0, __LINE__, 'Password must be at least 4 characters long', '');
}
if (strlen($sPassword) > 50) {
    sendResponse(0, __LINE__, 'Password is too long', '');
}


$stmt = $db->prepare('SELECT * FROM users where email like :sEmail');
$stmt->bindValue(':sEmail', $sEmail);
$stmt->execute();
$aRows = $stmt->fetch();


if (!isset($aRows->email)) {
    sendResponse(0, __LINE__, 'Email is not registered', '');
}


$sHashedPassword = $aRows->password;
//echo $sHashedPassword;

if (!password_verify($sPassword, $sHashedPassword)) {
    sendResponse(0, __LINE__, 'Failed to log in, password incorrect', '');
}


$iUserId = $aRows->id;
$_SESSION['iUserId'] = $iUserId;
sendResponse(1, __LINE__, 'Login successful ', '');


// **************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage, $sEmail)
{
    echo '{"status":' . $iStatus . ', "code":' . $iLineNumber . ',"message":"' . $sMessage . '", "user":"' . $sEmail . '"}';
    exit;
}
























