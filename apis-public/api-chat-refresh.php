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


//retrieve top 10 messages for conversation
try {
    $stmt = $db->prepare('CALL get_recent_messages()');
    $stmt->execute();
    $aMessagesRows = $stmt->fetchAll();
} catch (PDOEXception $ex) {
    echo $ex;
}


if (count($aMessagesRows) != 0) {

    foreach ($aMessagesRows as $aRow) {
        echo '<div class=\"message\"><p><b>' . $aRow->timestamp . '</b> ' . $aRow->name . ': ' . $aRow->message . '</p></div>';

    }
}


?>