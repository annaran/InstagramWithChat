<?php
session_start();
require_once '../connect.php';

$iImageId = $_GET['imageId'];
$iUserId = $_SESSION['iUserId'];


try {
    $stmt = $db->prepare('SELECT *            
            FROM v_color_of_emotions 
            where image_id=:iImageId and user_id=:iUserId        
            ');
    $stmt->bindValue(':iImageId', $iImageId);
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->execute();
    $aRows = $stmt->fetchAll();
    echo json_encode($aRows);


} catch (PDOEXception $ex) {
    echo $ex;
}

