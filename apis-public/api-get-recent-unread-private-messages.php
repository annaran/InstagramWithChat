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

$iUserId2 = $_SESSION['iUserId'];
//$iUserId2 = $_GET['iUserId2'];

//retrieve top 5 unread private messages for displaying buttons
try {
    $stmt = $db->prepare('CALL get_recent_unread_private_messages(:iUserId2)');
    //$stmt->bindValue(':iUserId', $iUserId);
    $stmt->bindValue(':iUserId2', $iUserId2);
    $stmt->execute();
    $aMessagesRows = $stmt->fetchAll();
} catch (PDOEXception $ex) {
    echo $ex;
}


if (count($aMessagesRows) != 0) {

    foreach ($aMessagesRows as $aRow) {
        echo "<button class=\"open-unread-buttons\" onclick=\"openPrivateForm('$aRow->user_fk,$iUserId2')\">Unread messages from ".$aRow->name." </button>";

    }

}


?>