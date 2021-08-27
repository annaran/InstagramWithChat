<?php
require_once __DIR__ . '/top.php';
require_once 'connect.php';
ini_set('display_errors', 0);


if (!isset($_SESSION['iUserId'])) {
    //header('Location: login');
    echo "
      <script type=\"text/javascript\"> 
      window.location.href=\"login.php\";
      </script>
      ";
}

$sSearchMode = "user";
$sColorOfLove = "blue";
$sColorOfLike = "blue";
$sColorOfDislike = "blue";
$sColorOfPoop = "blue";

//get user id from session (logged user) or from get (user browsing specific user pictures)
if (!isset($_GET["id"]) || $_GET["id"] == $_SESSION['iUserId']) {
    $iUserId = $_SESSION['iUserId'];
    $bIsProfileOwner = 1;
    $sSearchMode = "user";
    $sSearchModeHeader = "Images uploaded by me";
} else {
    $iUserId = $_GET["id"];
    $bIsProfileOwner = 0;
    $sSearchMode = "user";

    //get username from db
    try {
        $stmt = $db->prepare('SELECT * from users 
                                    where id=:iUserId  
           ');
        $stmt->bindValue(':iUserId', $iUserId);
        $stmt->execute();
        $aRowName = $stmt->fetch();

    } catch (PDOException $ex) {
        echo $ex;
    }


    $sSearchModeHeader = "Images uploaded by " . $aRowName->name;
}

// get tag for filtering pictures
if (isset($_GET["tag"])) {
    $sTag = $_GET["tag"];
    $sSearchMode = "tag";
    $sSearchModeHeader = "Images tagged " . $sTag;
}


// get user id from session to show  pictures from people user follows
// for user viewing someone's profile
// and for logged user
if (isset($_GET["follower"])) {
    if (isset($_GET["id"]) && $_GET["id"] != $_SESSION['iUserId']) {
        $iUserId = $_GET["id"];
        $sSearchModeHeader = "Images from people " . $aRowName->name . " follows";
    } else {
        $iUserId = $_SESSION['iUserId'];
        $sSearchModeHeader = "Images from people you follow";
    }
    $sSearchMode = "follower";

}


?>
    <div class="search-mode-header"><?= $sSearchModeHeader; ?></div>
    <div id="index" class="undernav">

        <?php

        //displaying pictures from specific user -------------------------------------------
        if ($sSearchMode == "user") {
            $stmt = $db->prepare('SELECT * FROM v_images_with_emotions WHERE user_fk = :iUserId LIMIT 30');
            $stmt->bindValue(':iUserId', $iUserId);
        }
        // displaying pictures with specified tag -------------------------------------------
        if ($sSearchMode == "tag") {
            $stmt = $db->prepare('SELECT *
                                        FROM v_images_with_emotions i
                                        where i.id IN
                                        (select fk_image
                                         from image_has_tag iht
                                         left join tags t
                                         on iht.fk_tag = t.id
                                         where t.tag like :sTag)');
            $stmt->bindValue(':sTag', $sTag);
        }

        //displaying pictures of users I follow  -------------------------------------------
        if ($sSearchMode == "follower") {
            $stmt = $db->prepare('SELECT *
                                    FROM v_images_with_emotions 
                                    WHERE user_fk IN
                                    (select fk_followed_user
                                     from followed_users
                                     where fk_user = :iUserId    
                                        )');
            $stmt->bindValue(':iUserId', $iUserId);
        }


        $stmt->execute();
        $aRows = $stmt->fetchAll();
        foreach ($aRows as $jRow) {


            //check if user already has reaction to image
            $stmt2 = $db->prepare('SELECT *
                                    FROM emotions 
                                    WHERE user_fk = :iUserId and image_fk = :iImageIdx
                                    ');
            $stmt2->bindValue(':iUserId', $_SESSION['iUserId']);
            $stmt2->bindValue(':iImageIdx', $jRow->id);
            $stmt2->execute();
            $aEmotionRows = $stmt2->fetch();
            if (isset($aEmotionRows->emotion)) {

                switch ($aEmotionRows->emotion) {
                    case '0':
                        $sColorOfLove = "black";
                        $sColorOfLike = "black";
                        $sColorOfDislike = "black";
                        $sColorOfPoop = "green";
                        break;
                    case '1':
                        $sColorOfLove = "black";
                        $sColorOfLike = "black";
                        $sColorOfDislike = "green";
                        $sColorOfPoop = "black";
                        break;
                    case '2':
                        $sColorOfLove = "black";
                        $sColorOfLike = "green";
                        $sColorOfDislike = "black";
                        $sColorOfPoop = "black";
                        break;
                    case '3':
                        $sColorOfLove = "green";
                        $sColorOfLike = "black";
                        $sColorOfDislike = "black";
                        $sColorOfPoop = "black";
                        break;
                    default:
                        $sColorOfLove = "black";
                        $sColorOfLike = "black";
                        $sColorOfDislike = "black";
                        $sColorOfPoop = "black";
                        echo "Unidentified ";
                }

            } else {
                $sColorOfLove = "black";
                $sColorOfLike = "black";
                $sColorOfDislike = "black";
                $sColorOfPoop = "black";
            }


            echo '    
    <div class="image">
      <a href="fullimage?imageid=' . $jRow->id . '"><img src="images/' . $jRow->url . '"></a>
      <div class="emotions" data-image-id="' . $jRow->id . '">
        <span class="3">' . $jRow->number_of_loves . '</span> <svg onclick="setEmotion2(this, \'love\',3, \'' . $jRow->number_of_loves . '\')" style="fill:' . $sColorOfLove . '" width="25" height="25" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M896 1664q-26 0-44-18l-624-602q-10-8-27.5-26t-55.5-65.5-68-97.5-53.5-121-23.5-138q0-220 127-344t351-124q62 0 126.5 21.5t120 58 95.5 68.5 76 68q36-36 76-68t95.5-68.5 120-58 126.5-21.5q224 0 351 124t127 344q0 221-229 450l-623 600q-18 18-44 18z"/></svg>
        <span class="2">' . $jRow->number_of_likes . '</span> <svg onclick="setEmotion2(this, \'like\',2, \'' . $jRow->number_of_likes . '\')" style="fill:' . $sColorOfLike . '" width="25" height="25" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M320 1344q0-26-19-45t-45-19q-27 0-45.5 19t-18.5 45q0 27 18.5 45.5t45.5 18.5q26 0 45-18.5t19-45.5zm160-512v640q0 26-19 45t-45 19h-288q-26 0-45-19t-19-45v-640q0-26 19-45t45-19h288q26 0 45 19t19 45zm1184 0q0 86-55 149 15 44 15 76 3 76-43 137 17 56 0 117-15 57-54 94 9 112-49 181-64 76-197 78h-129q-66 0-144-15.5t-121.5-29-120.5-39.5q-123-43-158-44-26-1-45-19.5t-19-44.5v-641q0-25 18-43.5t43-20.5q24-2 76-59t101-121q68-87 101-120 18-18 31-48t17.5-48.5 13.5-60.5q7-39 12.5-61t19.5-52 34-50q19-19 45-19 46 0 82.5 10.5t60 26 40 40.5 24 45 12 50 5 45 .5 39q0 38-9.5 76t-19 60-27.5 56q-3 6-10 18t-11 22-8 24h277q78 0 135 57t57 135z"/></svg>
        <span class="1">' . $jRow->number_of_dislikes . '</span> <svg onclick="setEmotion2(this, \'dislike\',1, \'' . $jRow->number_of_dislikes . '\')" style="fill:' . $sColorOfDislike . '" width="25" height="25" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M320 576q0 26-19 45t-45 19q-27 0-45.5-19t-18.5-45q0-27 18.5-45.5t45.5-18.5q26 0 45 18.5t19 45.5zm160 512v-640q0-26-19-45t-45-19h-288q-26 0-45 19t-19 45v640q0 26 19 45t45 19h288q26 0 45-19t19-45zm1129-149q55 61 55 149-1 78-57.5 135t-134.5 57h-277q4 14 8 24t11 22 10 18q18 37 27 57t19 58.5 10 76.5q0 24-.5 39t-5 45-12 50-24 45-40 40.5-60 26-82.5 10.5q-26 0-45-19-20-20-34-50t-19.5-52-12.5-61q-9-42-13.5-60.5t-17.5-48.5-31-48q-33-33-101-120-49-64-101-121t-76-59q-25-2-43-20.5t-18-43.5v-641q0-26 19-44.5t45-19.5q35-1 158-44 77-26 120.5-39.5t121.5-29 144-15.5h129q133 2 197 78 58 69 49 181 39 37 54 94 17 61 0 117 46 61 43 137 0 32-15 76z"/></svg>
        <span class="0">' . $jRow->number_of_poops . '</span> <svg onclick="setEmotion2(this, \'poop\',0, \'' . $jRow->number_of_poops . '\')" style="fill:' . $sColorOfPoop . '" width="25" height="25" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M451.36 369.14C468.66 355.99 480 335.41 480 312c0-39.77-32.24-72-72-72h-14.07c13.42-11.73 22.07-28.78 22.07-48 0-35.35-28.65-64-64-64h-5.88c3.57-10.05 5.88-20.72 5.88-32 0-53.02-42.98-96-96-96-5.17 0-10.15.74-15.11 1.52C250.31 14.64 256 30.62 256 48c0 44.18-35.82 80-80 80h-16c-35.35 0-64 28.65-64 64 0 19.22 8.65 36.27 22.07 48H104c-39.76 0-72 32.23-72 72 0 23.41 11.34 43.99 28.64 57.14C26.31 374.62 0 404.12 0 440c0 39.76 32.24 72 72 72h368c39.76 0 72-32.24 72-72 0-35.88-26.31-65.38-60.64-70.86z"></path></svg>


      </div>
    </div>
    ';
        }


        ?>

    </div>


<?php
$sLinkToScript = '<script src="js/get-emotions.js"></script>';

require_once __DIR__ . '/bottom.php';