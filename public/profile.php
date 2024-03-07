<?php
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$user_id = get_current_user_id();

global $wpdb;
$student_table = $wpdb->prefix . 'students';
$student = $wpdb->get_row($wpdb->prepare("SELECT * FROM $student_table WHERE student_id = %d", $user_id), ARRAY_A);

if (isset($_POST['update'])) {
    // Sanitize and update user information
    $new_phone = sanitize_text_field($_POST['phone']);

    // Handle image upload
    $file = $_FILES['user_img'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = $user_id . '_picture.' . $ext;

  // Delete existing image file
if ($student['picture']) {
   $old_picture = $student['picture'];
    wp_delete_file($old_picture);

    $image = wp_upload_bits($file_name, null, file_get_contents($file['tmp_name']));
} else {
    // Upload the new image
    $image = wp_upload_bits($file_name, null, file_get_contents($file['tmp_name']));
}
    if (!empty($image['error'])) {
        echo 'Error uploading image: ' . $image['error'];
        exit;
    }
    // Update student information
    $wpdb->update(
        $student_table,
        array('phone' => $new_phone, 'picture' => $image['url']),
        array('student_id' => $user_id),
        array('%s', '%s'),
        array('%d')
    );

    // Refresh the $student variable with the updated information
    $student = $wpdb->get_row($wpdb->prepare("SELECT * FROM $student_table WHERE student_id = %d", $user_id), ARRAY_A);
}
?>

<div>
    <img width="200" height="200" src="<?php echo esc_url($student['picture']); ?>" alt="">
    <a href="<?php echo esc_url($student['picture']); ?>" target="_blank">View PDF</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
            <?php foreach ($student as $field => $detail) : ?>
                <tr>
                    <th scope="row"><?php echo esc_html($field); ?></th>
                    <td><?php echo esc_html($detail); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="">
    <form action="<?php echo get_the_permalink(); ?>" method="post" enctype="multipart/form-data">
        Profile Image: <input type="file" name="user_img" id="user-img">
        Name: <input type="text" name="user_fname" id="user-fname" value="<?php echo esc_attr($current_user->display_name); ?>"><br/>
        Phone: <input type="number" name="phone" id="phone" value="<?php echo isset($student['phone']) ? esc_attr($student['phone']) : ''; ?>"><br/>
        <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
        <input type="submit" value="submit" id="update" name="update">
    </form>

    <p>Click Here to <a href="<?php echo wp_logout_url(); ?>">Logout</a></p>
</div>
