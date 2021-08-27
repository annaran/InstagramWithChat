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


$iUserId2 = $_GET['iUserId2'];



//retrieve top 10 messages for conversation
try {
    $stmt = $db->prepare('CALL get_username_for_id(:iUserId2)');
    $stmt -> bindValue(':iUserId2',$iUserId2);
    $stmt->execute();
    $aMessagesRows = $stmt->fetch();
} catch (PDOEXception $ex) {
    echo $ex;
}


if (isset($aMessagesRows)) {


        echo   $aMessagesRows->name ;


}


?>