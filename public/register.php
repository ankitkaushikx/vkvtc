<?php
if (isset($_POST['register'])) {
    // Verify nonce
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'register_nonce')) {
        // Sanitize and validate input
        $fname = sanitize_text_field($_POST['user_fname']);
        $lname = sanitize_text_field($_POST['user_lname']);
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);  // Sanitize email
        $phone = sanitize_text_field($_POST['user_phone']);  // Sanitize phone as text
        $password = sanitize_text_field($_POST['user_pass']);
        $confirm_password = sanitize_text_field($_POST['user_con_pass']);

        // Validate and sanitize other fields if needed

        // Password validation
        if ($password === $confirm_password) {
            // Hash the password securely
            $hashed_password = wp_hash_password($password);

            // Create user
            $user_data = array(
                'user_login' => $username,
                'user_email' => $email,
                'first_name' => $fname,
                'last_name' => $lname,  // Corrected typo in the field name
                'user_pass' => $hashed_password,  // Use hashed password
                'display_name' => $fname . ' ' . $lname,  // Corrected typo in the variable name
            );
            $user_id = wp_insert_user($user_data);

            if (!is_wp_error($user_id)) {
                echo "User Created With ID " . $user_id;

                // Add phone as user meta
                update_user_meta($user_id, 'phone', $phone);

            } else {
                echo $user_id->get_error_message();  // Use $user_id instead of $result
            }
        } else {
            // Handle password mismatch
            echo 'Passwords do not match.';
        }
    } else {
        // Handle nonce verification failure
        echo 'Nonce verification failed.';
    }
}
echo '<br>' . get_user_meta(4,'phone',true);
?>

<div class="form-wrapper">
    <div class="login-form">
 <form action="<?php echo  esc_url(get_permalink()); ?>" method="post">
Username: <input type="text" name="username" id="login-username">
Password: <input type="password" name="pass" id="login-pass">
<input type="submit" value="user-login" name="login">
</form>
    </div>
    <div class="regi-form">

<form action="<?php echo esc_url(get_the_permalink()); ?>" method="post">
    <?php wp_nonce_field('register_nonce'); ?>
    First Name: <input type="text" name="user_fname" id="user-fname" required>
    Last Name: <input type="text" name="user_lname" id="user-lname" required>
    Phone: <input type="text" name="user_phone" id="user-phone" required>
    UserName: <input type="text" name="username" id="username" required>
    Email: <input type="email" name="email" id="email" required>
    Password: <input type="password" name="user_pass" id="user-pass" required>
    Confirm Password: <input type="password" name="user_con_pass" id="user-con-pass" required>
    <input type="submit" value="Register" class="submit" name="register">
</form>
    </div>
</div>