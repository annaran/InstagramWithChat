<?php
session_start();
ini_set('display_errors', 0);
require_once 'connect.php';
$iUserId = $_SESSION['iUserId'];


//get username
if (isset($_SESSION['iUserId'])) {

    $stmt = $db->prepare('SELECT * FROM users where id like :iUserId');
    $stmt->bindValue(':iUserId', $iUserId);
    $stmt->execute();
    $aRow = $stmt->fetch();
    $sInjectUserProfileName = $aRow->name;
    $disabled = '';

} else {
    $sInjectUserProfileName = "Anonymous";
    $disabled = 'disabled="disabled"';
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <? /*= $sInjectCss ?? '' */ ?>

    <title>INSTAGRAM <?= $sInjectUserProfileName ?? '' ?></title>
</head>
<body>

<nav>
    <a href="user_pictures">INSTAGRAM</a>

    <form autocomplete="off">
        <div class="search-box" id="search-box">

            <input <?php echo $disabled; ?> type="text" placeholder="Search image by tag" id="searchword"
                                            name="searchData">
            <svg width="25" height="25" viewBox="0 0 1792 1792">
                <path d="M1216 832q0-185-131.5-316.5t-316.5-131.5-316.5 131.5-131.5 316.5 131.5 316.5 316.5 131.5 316.5-131.5 131.5-316.5zm512 832q0 52-38 90t-90 38q-54 0-90-38l-343-342q-179 124-399 124-143 0-273.5-55.5t-225-150-150-225-55.5-273.5 55.5-273.5 150-225 225-150 273.5-55.5 273.5 55.5 225 150 150 225 55.5 273.5q0 220-124 399l343 343q37 37 37 90z"/>
            </svg>
            <div class="search-result" id="search-result">
            </div>
        </div>

    </form>

    <div class="pullRight"><a href="profile?id=<?= $iUserId ?>"><?= $sInjectUserProfileName ?></a></div>

</nav>


<?php


?>
