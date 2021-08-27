<?php require_once 'top.php';

?>
    <div class="undernav">
        <h2>Log in</h2>

        <form id="frmLogin">
            <input type="text" name="txtLoginEmail" placeholder="email">
            <input type="password" name="txtLoginPassword" placeholder="password">
            <br><br>
            <button class="button" type="submit">Log in</button>
            <br><br>
            <p>Don't have an account yet? <a href="signup">Sign up</a></p><br>
        </form>

    </div>
<?php
$sLinkToScript = '<script src="js/login.js"></script>';
require_once 'bottom.php';
?>