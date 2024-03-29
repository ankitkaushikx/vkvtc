<?php
global $wpdb;
$wp_users = $wpdb->prefix . 'users';

$search_term = isset($_GET['my_search_term']) ? sanitize_text_field($_GET['my_search_term']) : '';

$args = array(
    'search' => '*' . esc_sql($search_term) . '*',
    'search_columns' => array(
        'user_login',
        'user_email',
        'display_name',
    ),
);

$search_query = new WP_User_Query($args);
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Student List</h1>
      <div class="myform">
        <form action="<?php echo esc_url(admin_url('admin.php')); ?>" method="get" id="search-user-form">
            <input type="hidden" name="page" value="vkvtc_page">
            <input type="text" name="my_search_term" id="my-search-term" placeholder="Search For Student" value="<?php echo esc_attr($search_term); ?>">
            <input type="submit" value="Search" name="search">
        </form>
    </div>
    <table class="wp-list-table widefat fixed striped users" >
        <thead>
            <tr>
                <th >Id</th>
                <th class="manage-column column-primary">Enrollment</th>
                <th>Name</th>
                <th>Father's Name</th>
                <th>Course </th>
                <th>Father's Name</th>
            </tr>
        </thead>
        <tbody id="my-table-result">
            <?php if ($search_query->get_results()) : ?>
                <?php foreach ($search_query->get_results() as $user) : ?>
                    <tr>
                        <td class="username column-primary"><?php echo esc_html($user->user_login); ?></td>
                        <td class="email column-email"><?php echo esc_html($user->user_email); ?></td>
                        <td class="display-name column-display-name"><?php echo esc_html($user->display_name); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
  
</div>
