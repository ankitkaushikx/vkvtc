<?php
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$user_id = get_current_user_id();

global $wpdb;
$student_table = $wpdb->prefix . 'students';

if (isset($_POST['update'])) {
    // Sanitize and update user information
    $new_phone = sanitize_text_field($_POST['phone']);

    // Update student information
    $wpdb->update(
        $student_table,
        array('phone' => $new_phone),
        array('student_id' => 4),
        array('%s'),
        array('%d')
    );

    // // Redirect to the current page to avoid resubmission on page refresh
    // wp_redirect(get_the_permalink());
    // exit;
}

$student = $wpdb->get_row($wpdb->prepare("SELECT * FROM $student_table WHERE student_id = %d", $user_id), ARRAY_A);

?>
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
    <form action="<?php echo get_the_permalink(); ?>" method="post">
        Name: <input type="text" name="user_fname" id="user-fname" value="<?php echo esc_attr($current_user->display_name); ?>"><br/>
        Phone: <input type="number" name="phone" id="phone" value="<?php echo isset($student['phone']) ? esc_attr($student['phone']) : ''; ?>"><br/>
        <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
        <input type="submit" value="submit" id="update" name="update">
    </form>
</div>

