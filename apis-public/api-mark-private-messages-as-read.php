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
$iUserId2 = $_GET['iUserId2'];


//update isread status of messages

try {

    $stmt = $db->prepare('CALL mark_private_messages_as_read(:iUserId,:iUserId2)');

    $stmt->bindValue(':iUserId2', $iUserId2);
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->execute();


} catch (PDOEXception $ex) {
    echo $ex;
}




?>