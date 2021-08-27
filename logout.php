<?php require_once 'top.php';

unset($_SESSION['iUserId']);
session_destroy();
header('Location: login.php');
?>

    <script type="text/javascript">
        window.location.href = "login.php";
    </script>

    <h3>You have been successfully logged out</h3>


<?php

require_once 'bottom.php';
?>