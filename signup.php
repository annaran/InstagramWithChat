<?php
require_once 'top.php';

?>
    <div class="undernav">

        <h2>Sign up</h2>

        <form id="frmSignup" action="apis/api-signup" method="POST">
            <input name="txtSignupName" type="text" placeholder="display name" value="">
            <input name="txtSignupEmail" type="text" placeholder="email" value="">
            <input name="txtSignupPassword" type="password" placeholder="password" value="">
            <input name="txtSignupConfirmPassword" type="password" placeholder="confirm password" value="">
            <br><br>
            <button>Sign up</button>
        </form>
        <br><br>
        <p>Already have an account? <a href="login">Log in</a></p><br>
    </div>
<?php
$sLinkToScript = '<script src="js/signup.js"></script>';
require_once 'bottom.php';
?>