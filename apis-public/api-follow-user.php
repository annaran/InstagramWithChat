<?php
session_start();
require_once '../connect.php';

ini_set('display_errors', 0);
$iFollowedUserId = $_GET['userId'];
$sAction = $_GET['action'];
$iUserId = $_SESSION['iUserId'];


if ($sAction == 'follow') {
//insert followed user into followed_users table
    try {
        $stmt = $db->prepare('insert into followed_users (fk_user,fk_followed_user)
                                    values( :iUserId , :iFollowedUserId)');
        $stmt->bindValue(':iUserId', $iUserId);
        $stmt->bindValue(':iFollowedUserId', $iFollowedUserId);
        $stmt->execute();
        header('Location: ../profile?id=' . $iFollowedUserId);
    } catch (PDOException $ex) {
        echo $ex;
    }


} else {
//delete user from followed_users table

    try {
        $stmt = $db->prepare('delete from followed_users 
                                    where fk_user = :iUserId and fk_followed_user =:iFollowedUserId');
        $stmt->bindValue(':iUserId', $iUserId);
        $stmt->bindValue(':iFollowedUserId', $iFollowedUserId);
        $stmt->execute();
        header('Location: ../profile?id=' . $iFollowedUserId);
    } catch (PDOException $ex) {
        echo $ex;
    }


}



