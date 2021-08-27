<?php
require_once 'top.php';
require_once __DIR__ . '/connect.php';
ini_set('display_errors', 1);

if (!isset($_SESSION['iUserId'])) {
    //header('Location: login');
    echo "
      <script type=\"text/javascript\"> 
      window.location.href=\"login.php\";
      </script>
      ";
} else {
    $iUserId = $_SESSION['iUserId'];
}


$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        echo "<script type='text/javascript'>alert('File is not an image');</script>";
        $uploadOk = 0;
    }
}


if (isset($_FILES["fileToUpload"])) {
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }


    if (isset($_POST['txtTitle']) && $_POST['txtTitle'] !='' ) {
        $sTitle = $_POST['txtTitle'];
    } else {
        $sTitle = 'Untitled';
    }

    if (isset($_POST['txtTags'])) {
        $sTagsString = $_POST['txtTags'];
        $aTags = explode(',', $sTagsString);
    }


// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        $newFileName = uniqid('uploaded-', true) . '.' . $imageFileType;
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], 'images/' . $newFileName)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

            $sUrl = basename($_FILES["fileToUpload"]["name"]);

            try {
                //transaction start
                $db->beginTransaction();


                $stmt = $db->prepare('insert into images(title,url,user_fk,uploaded_date) values(:title,:url,:user_fk,:uploaded_date)');
                $stmt->bindValue(':title', $sTitle);
                $stmt->bindValue(':url', $newFileName);
                $stmt->bindValue(':user_fk', $iUserId);
                $stmt->bindValue(':uploaded_date', date('Y-m-d h:i:sa', time()));
                $stmt->execute();
                $iImageId = $db->lastInsertId();

                //here loop to insert tags
                foreach ($aTags as $tag) {

                    //select tag in db
                    $stmt = $db->prepare('SELECT * FROM tags where tag like :tag');
                    $stmt->bindValue(':tag', trim($tag));
                    $stmt->execute();
                    $aTagRow = $stmt->fetch();

                    //if rows = 0 then insert tag in db
                    //get tag id
                    //insert pair image_fk and tag_fk into image_has_tag

                    //if rows > 0
                    //get tag id
                    //insert pair image_fk and tag_fk into image_has_tag

                    if (!isset($aTagRow->tag)) {
                        $stmt = $db->prepare('insert into tags (tag)
                                                values(:tag)');

                        $stmt->bindValue(':tag', trim($tag));
                        $stmt->execute();
                        $iTagId = $db->lastInsertId();
                    } else {
                        $iTagId = $aTagRow->id;
                    }

                    $stmt = $db->prepare('insert into image_has_tag(fk_image, fk_tag) values(:imageid,:tagid)');
                    $stmt->bindValue(':imageid', $iImageId);
                    $stmt->bindValue(':tagid', $iTagId);
                    $stmt->execute();


                } //foreach insert tags end

                //transaction end
                $db->commit();


                header('Location: upload_success');
            } catch (PDOException $ex) {
                $db->rollback();
                echo $ex;
            }

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}

?>

<!DOCTYPE html>
<html>
<body>

<div class="undernav">
    <img src="" style="width: 200px; height: 200px;" alt="preview...">

    <form action="upload_image.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="previewImage()">
        <input type="text" id="txtTitle" name="txtTitle" placeholder="Title (optional)">
        <input type="text" id="txtTags" name="txtTags" placeholder="Tags (comma separated, optional)">
        <input type="submit" value="Upload Image" name="submit">
    </form>


    <script>
        function previewImage() {
            let preview = document.querySelector('img')
            let file = document.querySelector('input[type=file]').files[0]
            let reader = new FileReader()
            reader.onloadend = function () {
                preview.src = reader.result
            }
            reader.readAsDataURL(file)
        }
    </script>


</div>
</body>
</html>