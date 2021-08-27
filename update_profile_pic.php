<?php
require_once 'top.php';
require_once __DIR__ . '/connect.php';

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
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


if (isset($_FILES["fileToUpload"])) {
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }


// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {


        //delete user profile picture from disk
        try {


            $stmt = $db->prepare('SELECT * FROM users where id = :iUserId');
            $stmt->bindValue(':iUserId',$iUserId);
            //$sQuery = $db->prepare('select * from users');
            $stmt->execute();
            $aRowImageName = $stmt->fetch();
            echo json_encode($aRowImageName);

            if(isset($aRowImageName->picture) && $aRowImageName->picture != 'default.jpg') {
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


        //upload new picture

        $newFileName = uniqid('uploaded-', true) . '.' . $imageFileType;
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], 'images/' . $newFileName)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";


//update picture name in db
            $sUrl = basename($_FILES["fileToUpload"]["name"]);
            $stmt = $db->prepare('update users set picture=:url where id=:iUserId');
            $stmt->bindValue(':iUserId', $iUserId);
            $stmt->bindValue(':url', $newFileName);
            $stmt->execute();







            header('Location: profile');

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

    <form action="update_profile_pic.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="previewImage()">
        <input type="submit" value="Upload new profile picture" name="submit">
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