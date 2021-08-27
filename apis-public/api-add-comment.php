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
$sComment = $_POST['comment'] ?? '';
$iUserId = $_SESSION['iUserId'];


$iIsReply = 0;
$iRefComment = 0;
$tTimestamp = date('Y-m-d h:i:sa', time());


//insert comment into db

try {
    $stmt = $db->prepare('insert into comments 
 values(null,:sComment,:iIsReply,:iRefComment,:iUserId,:iImageId,:tTimestamp)');

    $stmt->bindValue(':sComment', $sComment);
    $stmt->bindValue(':iIsReply', $iIsReply);
    $stmt->bindValue(':iRefComment', $iRefComment);
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->bindValue(':iImageId', $iImageId);
    $stmt->bindValue(':tTimestamp', $tTimestamp);


    $stmt->execute();


} catch (PDOEXception $ex) {
    echo $ex;
}


//retrieve data for posting comment on page
try {
    $stmt = $db->prepare('select * from users where id = :iUserId');
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->execute();
    $aRow = $stmt->fetch();
    $sName = $aRow->name;

} catch (PDOEXception $ex) {
    echo $ex;
}

//retrieve all comments for picture
try {
    $stmt = $db->prepare('SELECT c.*, u.name as commenter, u.picture as commenter_pic
                                FROM comments c
                                left join users u 
                                on c.user_fk = u.id
                                where c.image_fk = :iImageId
                                order by c.timestamp desc');
    $stmt->bindValue(':iImageId', $iImageId);
    $stmt->execute();
    $aCommentsRows = $stmt->fetchAll();
} catch (PDOEXception $ex) {
    echo $ex;
}

//sending back to php

if (count($aCommentsRows) != 0) {

    foreach ($aCommentsRows as $aRow) {
        echo '<div class=\"comment\"><p><b>' . $aRow->timestamp . '</b> ' . $aRow->commenter . ' says: ' . $aRow->comment . '</p></div>';

    }
}


?>