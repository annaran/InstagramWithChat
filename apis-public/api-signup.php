<?php
ini_set('display_errors', 0);
require_once '../connect.php';


// validate username
$sName = $_POST['txtSignupName'] ?? '';
if (empty($sName)) {
    sendResponse(0, __LINE__, 'Name cannot be empty');
}
if (strlen($sName) < 2) {
    sendResponse(0, __LINE__, 'Name must be at least 2 characters long');
}
if (strlen($sName) > 20) {
    sendResponse(0, __LINE__, 'Name is too long');
}


// validate email
$sEmail = $_POST['txtSignupEmail'] ?? '';
if (empty($sEmail)) {
    sendResponse(0, __LINE__, 'Email cannot be empty');
}
if (!filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
    sendResponse(0, __LINE__, 'This is not a valid email address');
}

// validate password
$sPassword = $_POST['txtSignupPassword'] ?? '';
if (empty($sPassword)) {
    sendResponse(0, __LINE__, 'Password cannot be empty');
}
if (strlen($sPassword) < 4) {
    sendResponse(0, __LINE__, 'Password must be at least 4 characters long');
}
if (strlen($sPassword) > 50) {
    sendResponse(0, __LINE__, 'Password too long');
}

// validate confirm password
$sConfirmPassword = $_POST['txtSignupConfirmPassword'] ?? '';
if (empty($sConfirmPassword)) {
    sendResponse(0, __LINE__, 'Repeated password cannot be empty');
}
if ($sPassword != $sConfirmPassword) {
    sendResponse(0, __LINE__, 'Passwords do not match');
}


try {
    $stmt = $db->prepare('SELECT * FROM users where email like :sEmail');
    $stmt->bindValue(':sEmail', $sEmail);
    $stmt->execute();
    $aRows = $stmt->fetchAll();

} catch (PDOEXception $ex) {
    echo $ex;
}

if (count($aRows) != 0) {
    sendResponse(0, __LINE__, 'Email is already registered');
}

try {
    $stmt = $db->prepare("insert into users (id,name,email,password,picture)
                                    values(null,:sName,:sEmail,:sPassword,'default.jpg')");

    $stmt->bindValue(':sName', $sName);
    $stmt->bindValue(':sEmail', $sEmail);
    $stmt->bindValue(':sPassword', password_hash($sPassword, PASSWORD_DEFAULT));
    $stmt->execute();

} catch (PDOEXception $ex) {
    echo $ex;
}

// SUCCESS
sendResponse(1, __LINE__, 'Sign up successful');


// **************************************************

function sendResponse($bStatus, $iLineNumber, $sMessage)
{
    echo '{"status":' . $bStatus . ', "code":' . $iLineNumber . ', "message":"' . $sMessage . '"}';
    exit;
}






















