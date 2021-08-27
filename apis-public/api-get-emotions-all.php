<?php
session_start();
require_once '../connect.php';

$iImageId = $_GET['imageId'];

try {
    $stmt = $db->prepare('SELECT id as image_id, number_of_loves, number_of_likes,number_of_dislikes,number_of_poops            
            FROM v_images_with_emotions 
            where id=:iImageId         
            ');
    $stmt->bindValue(':iImageId', $iImageId);
    $stmt->execute();
    $aRows = $stmt->fetchAll();
    echo json_encode($aRows);


} catch (PDOEXception $ex) {
    echo $ex;
}

