<?php
require_once 'top.php';
require_once 'connect.php';
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
$iImageId = $_GET['imageid'];


try {

    $stmt = $db->prepare('SELECT i.*, u.name, u.picture, u.id as id_uploader
                                FROM images i
                                left join users u 
                                on i.user_fk = u.id
                                where i.id = :iImageId');
    $stmt->bindValue(':iImageId', $iImageId);
    $stmt->execute();
    $aRow = $stmt->fetch();
} catch (PDOEXception $ex) {
    echo $ex;
}


?>
<div class="undernav">

    <h2><?= $aRow->title; ?></h2><br>
    <div id="imageid" style="display:none"><?= $iImageId; ?></div>
    <div class="thumbnailpic"><b>Uploaded by: </b><?= '<img src="images/' . $aRow->picture . '">'; ?><a
                href="profile?id=<?= $aRow->id_uploader; ?>"><?= $aRow->name; ?></a></div>
    <div><b>Upload date: </b><?= $aRow->uploaded_date; ?> </div>
    <div class="fullsizepic"><?= '<img src="images/' . $aRow->url . '">'; ?>


    </div>

    <?php
    if ($aRow->id_uploader == $_SESSION['iUserId']) {

        echo "<input class=\"button-red\" type=\"button\" value=\"Delete picture\" onclick=\"window.location.href='apis-public/api-delete-pic.php?imageId=$iImageId'\">";

    }
    ?>


    <br><br><br><br><br>

    <?php

    //comments

    try {

        $stmt = $db->prepare('SELECT c.*, u.name as commenter, u.picture as commenter_pic
                                FROM comments c
                                left join users u 
                                on c.user_fk = u.id
                                where c.image_fk = :iImageId
                                order by c.timestamp desc');
        $stmt->bindValue(':iImageId', $iImageId);
        $stmt->execute();
        $aRows = $stmt->fetchAll();

    } catch (PDOEXception $ex) {
        echo $ex;
    }


    ?>

    <input type="text" id="comment" placeholder="Write comment here...">
    <input type="submit" id="submit-comment" value="Add comment" onclick=addComment(<?= $iImageId; ?>)>
    <div class="comment" id="comment-text">

        <?php

        if (count($aRows) == 0) {
            echo 'No comments for this picture';
        }


        if (count($aRows) != 0) {
            foreach ($aRows as $aRow) {
                echo '<p><b>' . $aRow->timestamp . '</b> ' . $aRow->commenter . ' says: ' . $aRow->comment . '</p>';
            }
        }

        ?>
    </div>


</div>
<?php
$sLinkToScript = '<script src="js/comment.js"></script>';
require_once 'bottom.php';
?>

