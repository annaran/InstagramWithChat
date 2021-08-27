<?php
session_start();
require_once '..\connect.php';
ini_set('display_errors', 0);
$sTag = $_POST['searchword'] ?? '';
$sTag = $sTag . '%';

try {
    $sQuery2 = $db->prepare('SELECT * 
                                        FROM tags
                                        where tag like :sTag                                       
                                        ');

    $sQuery2->bindValue(':sTag', $sTag);
    $sQuery2->execute();
    $aTagsRows = $sQuery2->fetchAll();

    if (count($aTagsRows) == 0) {
        echo 'Sorry, no results';
        exit;
    }

    foreach ($aTagsRows as $aRow) {
        echo '<a href="user_pictures?tag=' . $aRow->tag . '">' . $aRow->tag . '</a>';
    }

} catch (PDOEXception $ex) {
    echo $ex;
}




