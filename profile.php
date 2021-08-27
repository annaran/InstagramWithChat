<?php
/*session_start();*/
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

$testmessage = 'test';

if (!isset($_GET["id"]) || $_GET["id"] == $_SESSION['iUserId']) {
   //we are in our own profile
    $iUserId = $_SESSION['iUserId']; //me
    $iChatUserId2 = 0;  //goes to public chat
    $bIsProfileOwner = 1;
} else {
    //we are in someone else's profile
    $iUserId = $_GET["id"]; //me
    $iUserId2 = $_GET["id"];
    $iChatUserId2 = $_GET["id"]; //goes to this person's private messages
    $bIsProfileOwner = 0;

}


//display profile info
try {
    $stmt = $db->prepare('SELECT * FROM users where id like :iUserId');



    if($bIsProfileOwner == 1) {
        $stmt->bindValue(':iUserId', $iUserId);
    }else{
        $stmt->bindValue(':iUserId', $iUserId2);
    }


    $stmt->execute();
    $aRow = $stmt->fetch();

} catch (PDOException $ex) {
    echo $ex;
}


?>

<?php

//messages

try {

    $stmt = $db->prepare('SELECT *
                                FROM v_recent_messages                                
                                order by timestamp asc');

    $stmt->execute();
    $aMessagesRows = $stmt->fetchAll();

} catch (PDOEXception $ex) {
    echo $ex;
}

?>


<?php

//private messages

try {

/*    $stmt = $db->prepare('SELECT *
                                FROM v_recent_private_messages                                
                                order by timestamp asc');*/


    $stmt = $db->prepare('CALL get_recent_private_messages(:iUserId, :iUserId2)');
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->bindValue(':iUserId2', $iChatUserId2);
    $stmt->execute();
    $aPrivateMessagesRows = $stmt->fetchAll();

} catch (PDOEXception $ex) {
    echo $ex;
}

?>




<div class="undernav">
    <div class="chatbox">
        <button class="open-button" onclick="openForm()">Show public chat</button>
        <div class="chat-popup" id="chatForm">
            <form action="profile.php" class="form-container">
                <h1>Public chat</h1>

                <label for="msg"><b>Recent messages</b></label>

                <div class="message" id="message-text">

                    <?php

                    if (count($aMessagesRows) != 0) {

                        foreach ($aMessagesRows as $aMessageRow) {
                            echo '<p><b>' . $aMessageRow->timestamp . '</b> ' . $aMessageRow->name . ': ' . $aMessageRow->message . '</p>';

                        }
                    }

                    ?>
                </div>


                <textarea placeholder="Type message..." id="msg" name="msg" required></textarea>

                <button type="button" class="btn" onclick=sendMessage()>Send</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Hide public chat</button>
            </form>
        </div>
    </div>




    <div class="chatbox_private">
       <!-- <button class="open-private-button" onclick="openPrivateForm()">Show private chat</button>-->
        <div class="private-chat-popup" id="privateChatForm">
            <form action="profile.php" class="form-container-private">
                <h1>Private chat</h1>

                <label for="private-msg"><b>Recent messages from </b></label>

                <div class="private-message" id="private-message-text">

                    <?php

                    if (count($aPrivateMessagesRows) != 0) {

                        foreach ($aPrivateMessagesRows as $aMessageRow) {
                            echo '<p><b>' . $aMessageRow->timestamp . '</b> ' . $aMessageRow->name . ': ' . $aMessageRow->message . '</p>';

                        }
                    }

                    ?>
                </div>


                <textarea placeholder="Type message..." id="private-msg" name="private-msg" required></textarea>

                <button type="button" class="btn" onclick=sendPrivateMessage()>Send</button>
                <button type="button" class="btn cancel" onclick="closePrivateForm()">Close private chat</button>
            </form>
        </div>
    </div>



<div  class="unread-buttons-block" id="unread-buttons-block">

</div>






    <h2>User profile</h2><br>
    <div class="profilepic"><?= '<img src="images/' . $aRow->picture . '">'; ?></div>
    <div style="display:none" id="profile-owner-id"><?= $iUserId;  ?></div>
    <div><b>Username: </b><?= $aRow->name; ?></div>
    <div><b>Email: </b><?= $aRow->email; ?> </div>
    <?php
    if ($bIsProfileOwner == 0) {
// display message button
        echo "<div><button type=\"button\" class=\"button\" onclick=\"openPrivateForm('$iChatUserId2')\">Chat with ". $aRow->name ."</button></div>";



        //display follow or unfollow button
        try {
            $stmt = $db->prepare('SELECT * FROM followed_users 
                                        where fk_followed_user like :iUserId and fk_user like :iFollowerId');
            $stmt->bindValue(':iUserId', $iUserId);
            $stmt->bindValue(':iFollowerId', $_SESSION['iUserId']);
            $stmt->execute();
            $aFollowersRow = $stmt->fetch();

        } catch (PDOException $ex) {
            echo $ex;
        }


        if (!isset($aFollowersRow->id)) {


            echo "<input class=\"button\" type=\"button\" value=\"Follow\" onclick=\"window.location.href='apis-public/api-follow-user.php?action=follow&userId=$iUserId2'\">";
        } else {
            echo "<input class=\"button\" type=\"button\" value=\"Unfollow\" onclick=\"window.location.href='apis-public/api-follow-user.php?action=unfollow&userId=$iUserId2'\">";
        }
    }
    ?>
    <br><br><br>


    <?php
    if ($bIsProfileOwner == 0) {
        echo '
         <a href=user_pictures?id=' . $iUserId . '>' . $aRow->name . '\'s pictures</a><br>
            <a href=user_pictures?follower=1&id=' . $iUserId . '>Pictures from users ' . $aRow->name . ' follows</a><br>
        ';
    } else {
        echo "
            <a href=\"user_pictures\">My pictures</a><br>
            <a href=\"user_pictures?follower=1\">Pictures from users I follow</a><br>           
            <a href=\"update_profile_pic\">Change profile picture</a> <br>
            <a href=\"upload_image.php\">Upload picture</a><br>
            <a href=\"delete_account\">Delete account</a><br>
            <a href=\"logout\">Log out</a><br>
        ";
    }


    ?>

</div>
<?php
$sLinkToScript = '<script src="js/chat.js"></script>';
require_once 'bottom.php';
?>

