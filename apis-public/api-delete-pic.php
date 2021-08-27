<?php
session_start();
require_once '../connect.php';

ini_set('display_errors', 0);



// remove pic if it is part of account deletion or profile pic change
if (isset($_GET['imageName'])){
    $sImageToDelete = $_GET['imageName'] ;
    if (file_exists('../images/' . $sImageToDelete)) {
        unlink('../images/' . $sImageToDelete);
    }
    echo 'profile pic deleted from directory';

}



//remove pic from folder when delete picture button is clicked

if(isset($_GET['imageId'])) {
    $iImageId = $_GET['imageId'];




    try {
        $stmt = $db->prepare('select * from images where id = :iImageId');
        $stmt->bindValue(':iImageId', $iImageId);
        $stmt->execute();
        $aRows = $stmt->fetch();

        $sImageToDelete = $aRows->url;
        echo 'images/' . $sImageToDelete . '<br>';
        if (file_exists('../images/' . $sImageToDelete)) {
            unlink('../images/' . $sImageToDelete);
        }


        echo 'image deleted from directory';


        //delete picture from db

        $stmt = $db->prepare('delete from images 
                                    where id=:iImageId         
                                                        ');
        $stmt->bindValue(':iImageId', $iImageId);
        $stmt->execute();

//only do this if picture is being deleted by button
        header('Location: ../picture_delete_success.php');


    } catch (PDOException $ex) {
        echo $ex;
    }


}



