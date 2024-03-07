<?php
?>
 <div class="login-form">
 <form action="<?php echo  esc_url(get_permalink()); ?>" method="post">
Username: <input type="text" name="username" id="login-username">
Password: <input type="password" name="pass" id="login-pass">
<input type="submit" value="user-login" name="login">
</form>
    </div>