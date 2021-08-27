<?php
session_start();
require_once '../connect.php';

$iImageId = $_GET['imageId'];

$stmt = $db->prepare('SELECT image_fk 
            as image_id, emotion, COUNT(emotion) 
            as total FROM emotions 
            where image_fk=:iImageId
            GROUP BY emotion');
$stmt->bindValue(':iImageId', $iImageId);
$stmt->execute();
$aRows = $stmt->fetchAll();
echo json_encode($aRows);



