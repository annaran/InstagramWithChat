<?php
require_once 'top.php';
require_once 'connect.php';
ini_set('display_errors', 1);

//deleting account
if (!isset($_SESSION['iUserId'])) {
    //header('Location: login');
    echo "
      <script type=\"text/javascript\"> 
      window.location.href=\"login.php\";
      </script>
      ";
}


$iUserId = $_SESSION['iUserId'];

//delete user's images from images directory
//select all images uploaded by user and user pic
//foreach delete files if they exist


try {
    $stmt = $db->prepare('select * from images where user_fk = :iUserId');
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->execute();
    $aRows = $stmt->fetchAll();

    foreach ($aRows as $aRow) {
        $sImageToDelete = $aRow->url;
        echo 'images/' . $sImageToDelete . '<br>';
        if (file_exists('images/' . $sImageToDelete)) {
            unlink('images/' . $sImageToDelete);
        }

    }
    echo 'user images deleted from directory';


} catch (PDOException $ex) {
    echo $ex;
}


//delete user profile picture from disk

try {


    $stmt = $db->prepare('SELECT * FROM users where id = :iUserId');
    $stmt->bindValue(':iUserId',$iUserId);
    //$sQuery = $db->prepare('select * from users');
    $stmt->execute();
    $aRowImageName = $stmt->fetch();
    echo json_encode($aRowImageName);

    if(isset($aRowImageName->picture)) {
       echo '<div>' . $aRowImageName->picture . ' </div>';
        $sImageName = $aRowImageName->picture;


    $sUrl = 'http://localhost:8080/instagram/apis-public/api-delete-pic?imageName='.$sImageName;
    echo $sUrl.'<br>';
    $sResponse =  file_get_contents($sUrl);
    echo 'user profile pic deleted from directory';
    }

} catch (PDOException $ex) {
    echo $ex;
}



//delete user from db
try {
    $stmt = $db->prepare('delete from users where id = :iUserId');
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->execute();
    echo 'user deleted from db';
    unset($_SESSION['iUserId']);
    session_destroy();
    header('Location: account_delete_success.php');

} catch (PDOException $ex) {
    echo $ex;
}


?>

<?php

require_once 'bottom.php';
?>