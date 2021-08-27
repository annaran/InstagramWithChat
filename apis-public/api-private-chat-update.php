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

$iUserId = $_SESSION['iUserId'];
$iUserId2 = $_POST['iUserId2'];
$sMessage = $_POST['sMessage'];


//insert message into db

if ($iUserId2 != $iUserId) {

    try {

        $stmt = $db->prepare('CALL send_private_message(:iUserId, :iUserId2, :sMessage)');

        $stmt->bindValue(':sMessage', $sMessage);
        $stmt->bindValue(':iUserId', $iUserId);
        $stmt->bindValue(':iUserId2', $iUserId2);
        $stmt->execute();


    } catch (PDOEXception $ex) {
        echo $ex;
    }


//retrieve top 10 messages for conversation
    try {
        $stmt = $db->prepare('CALL get_recent_private_messages(:iUserId, :iUserId2)');
        $stmt->bindValue(':iUserId', $iUserId);
        $stmt->bindValue(':iUserId2', $iUserId2);
        $stmt->execute();
        $aMessagesRows = $stmt->fetchAll();
    } catch (PDOEXception $ex) {
        echo $ex;
    }

//sending back to php


    if (count($aMessagesRows) != 0) {

        foreach ($aMessagesRows as $aRow) {
            echo '<div class=\"private-message\"><p><b>' . $aRow->timestamp . '</b> ' . $aRow->name . ': ' . $aRow->message . '</p></div>';

        }
    }

}

?>