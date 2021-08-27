<?php
session_start();
require_once '../connect.php';
ini_set('display_errors', 1);


if (!isset($_SESSION['iUserId'])) {
    //header('Location: login');
    echo "
      <script type=\"text/javascript\"> 
      window.location.href=\"login.php\";
      </script>
      ";
}


$iImageId = $_POST['imageId'] ?? '';
$sEmotionTxt = $_POST['emotion'] ?? '';
$iUserId = $_SESSION['iUserId'] ?? '';

// emotion name to number
switch ($sEmotionTxt) {
    case "love":
        $iEmotionId = 3;
        break;
    case "like":
        $iEmotionId = 2;
        break;
    case "dislike":
        $iEmotionId = 1;
        break;
    case "poop":
        $iEmotionId = 0;
        break;
    default:
        sendResponse(0, __LINE__, 'Unknown emotion');
}


//emotion removal
//checks if there is a pair of certain user+image+emotion in the emotions table
//if yes removes the record


//emotion update
//checks if there is a pair of user+image in the emotions table
//if no then inserts new record
//if yes then updates emotion id


try {
    $stmt = $db->prepare('SELECT * from emotions 
                                    where image_fk=:iImageId and user_fk=:iUserId and emotion=:iEmotionId 
           ');
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->bindValue(':iImageId', $iImageId);
    $stmt->bindValue(':iEmotionId', $iEmotionId);
    $stmt->execute();
    $aRows = $stmt->fetchAll();

    if (count($aRows) != 0) {
        $operation = "delete";
    } else {
        $stmt = $db->prepare('SELECT * from emotions 
                                    where image_fk=:iImageId and user_fk=:iUserId  
           ');
        $stmt->bindValue(':iUserId', $iUserId);
        $stmt->bindValue(':iImageId', $iImageId);
        $stmt->execute();
        $aRows = $stmt->fetchAll();

        if (count($aRows) != 0) {
            $operation = "update";
        } else {
            $operation = "insert";
        }


    }

} catch (PDOEXception $ex) {
    echo $ex;
}


//update db based on status of operation

switch ($operation) {
    case "delete":

        try {
            $stmt = $db->prepare('delete from emotions 
                                            where image_fk=:iImageId and user_fk=:iUserId and emotion=:iEmotionId');
            $stmt->bindValue(':iUserId', $iUserId);
            $stmt->bindValue(':iImageId', $iImageId);
            $stmt->bindValue(':iEmotionId', $iEmotionId);
            $stmt->execute();

        } catch (PDOException $ex) {
            echo $ex;
        }

        break;
    case "update":


        try {
            $stmt = $db->prepare('update emotions 
                                            set emotion = :iEmotionId 
                                            where image_fk=:iImageId and user_fk=:iUserId');
            $stmt->bindValue(':iUserId', $iUserId);
            $stmt->bindValue(':iImageId', $iImageId);
            $stmt->bindValue(':iEmotionId', $iEmotionId);
            $stmt->execute();

        } catch (PDOException $ex) {
            echo $ex;
        }


        break;
    case "insert":

        try {
            $stmt = $db->prepare('insert into emotions(image_fk,user_fk,emotion) 
                                             values(:iImageId,:iUserId,:iEmotionId)');

            $stmt->bindValue(':iUserId', $iUserId);
            $stmt->bindValue(':iImageId', $iImageId);
            $stmt->bindValue(':iEmotionId', $iEmotionId);
            $stmt->execute();

        } catch (PDOEXception $ex) {
            echo $ex;
        }

        break;

    default:
        sendResponse(0, __LINE__, 'Unknown operation');
}


function sendResponse($iStatus, $iLineNumber, $sMessage)
{
    echo '{"status":' . $iStatus . ', "code":' . $iLineNumber . ',"message":"' . $sMessage . '"}';
    exit;
}

