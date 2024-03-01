<?php
/**
 *
 */
/*
Plugin Name: vkvtc
Plugin URI: 
Description: Vkvtc
Author: Ankit Kaushik
Author URI: https://github.com/ankitkaushkix

*/

if(!defined('ABSPATH')){
    header("Location: /");
    exit;
}
function vkvtc_activation() {
    global $wpdb;

    $students = $wpdb->prefix . 'students';
    $wallet_transactions = $wpdb->prefix . 'wallet_transactions';
    $student_courses = $wpdb->prefix . 'student_courses';
    $centers = $wpdb->prefix  . 'centers';
    $certificates = $wpdb->prefix . 'certificates';
    $marksheet = $wpdb->prefix . 'marks';
    $charset_collate = $wpdb->get_charset_collate();

    $sql_centers = "CREATE TABLE IF NOT EXISTS `$centers` (
        registration_number BIGINT(20) UNSIGNED AUTO_INCREMENT,
        center_ID BIGINT(20) UNSIGNED NOT NULL,
        center_code VARCHAR(15) NOT NULL UNIQUE,
        center_name VARCHAR(255) NOT NULL,
        address TEXT,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100),
        status ENUM('ACTIVE','INACTIVE','APPROVED','BLOCKED','HOLD', 'CERTIFIED') NOT NULL,
        center_director VARCHAR(100),
        type VARCHAR(20),
        picture VARCHAR(255),
        id_document VARCHAR(255),
        other_document VARCHAR(255),
        total_students INT NOT NULL,
        wallet_amount INT NOT NULL DEFAULT 0,
        PRIMARY KEY (registration_number),
        FOREIGN KEY(center_ID) REFERENCES vk_users(ID)
    );";

    $sql_students = "CREATE TABLE IF NOT EXISTS $students (
        enrollment_number BIGINT(20) NOT NULL AUTO_INCREMENT, 
        student_Id BIGINT(20) UNSIGNED NOT NULL UNIQUE, 
        center_Id BIGINT(20) UNSIGNED NOT NULL,
        student_name VARCHAR(120) NOT NULL, 
        father_name VARCHAR(120) NOT NULL, 
        mother_name VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL, 
        aadhar_number VARCHAR(12) NOT NULL UNIQUE,
        date_of_birth DATE NOT NULL,
        gender ENUM('male', 'female'),
        address VARCHAR(255) NOT NULL, 
        status ENUM('ACTIVE', 'INACTIVE', 'RESULTED', 'CERTIFIED', 'DROPPED', 'BLOCKED','HOLD'),
        picture VARCHAR(255),
        tenth_dmc VARCHAR(255),
        twelfth_dmc VARCHAR(255),
        date_enrolled DATE NOT NULL,
        student_code VARCHAR(25),
        PRIMARY KEY(enrollment_number),
        FOREIGN KEY (student_Id) REFERENCES vk_users(ID),
        FOREIGN KEY(center_Id) REFERENCES vk_users(ID)
    ) $charset_collate";

    $sql_certificates = "CREATE TABLE IF NOT EXISTS $certificates (
        certificate_ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        student_ID BIGINT(20) UNSIGNED NOT NULL,
        center_ID BIGINT(20) UNSIGNED NOT NULL,
        total_marks DECIMAL(7,2) UNSIGNED NOT NULL,
        maximum_marks SMALLINT UNSIGNED NOT NULL, 
        certificate_code VARCHAR(25) NOT NULL , 
        message VARCHAR(25),
        PRIMARY KEY (certificate_ID),
        FOREIGN KEY (student_ID) REFERENCES  vk_users(ID)
    ) $charset_collate";

    $sql_transactions = "CREATE TABLE IF NOT EXISTS $wallet_transactions (
        transaction_ID BIGINT(20) NOT NULL AUTO_INCREMENT,
        center_ID BIGINT(20) UNSIGNED NOT NULL,
        intiater BIGINT(20) UNSIGNED,
        transaction_amount INT NOT NULL,
        balance INT NOT NULL,
        reference TINYTEXT,
        message TEXT,
        transaction_date DATETIME NOT NULL,
        PRIMARY KEY (transaction_ID),
        FOREIGN KEY (center_ID) REFERENCES vk_users(ID)
    ) $charset_collate";

    $sql_student_courses = "CREATE TABLE IF NOT EXISTS $student_courses (
        ID INT AUTO_INCREMENT,
        student_ID BIGINT UNSIGNED NOT NULL,
        center_ID BIGINT UNSIGNED NOT NULL,
        course_ID BIGINT(20) UNSIGNED NOT NULL,
        enrollment_date DATE NOT NULL,
        PRIMARY KEY (ID),
        FOREIGN KEY (center_ID) REFERENCES vk_users(ID),
        FOREIGN KEY (student_ID) REFERENCES vk_users(ID),
        FOREIGN KEY (course_ID) REFERENCES vk_posts(ID)
    ) $charset_collate";

    $sql__marksheet = "CREATE TABLE IF NOT EXISTS `$marksheet` (
        marksheet_ID BIGINT(20) NOT NULL AUTO_INCREMENT,
        position TINYINT NOT NULL,
        certificate_ID BIGINT(20) UNSIGNED NOT NULL,
        subject_ID BIGINT(20) UNSIGNED NOT NULL,
        obtained_marks DECIMAL(6,2) UNSIGNED NOT NULL,
        practical_marks DECIMAL(5,3),
        PRIMARY KEY (marksheet_ID),
        FOREIGN KEY (certificate_ID) REFERENCES vk_users(ID),
        FOREIGN KEY (subject_ID) REFERENCES vk_posts(ID)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_centers);
    dbDelta($sql_students);
    dbDelta($sql_transactions);
    dbDelta($sql_student_courses);
    dbDelta($sql_certificates);
    dbDelta($sql__marksheet);
}

function vkvtc_deactivation() {
    global $wpdb;

    $students = $wpdb->prefix . 'students';
    $wallet_transactions = $wpdb->prefix . 'wallet_transactions';
    $student_courses = $wpdb->prefix . 'student_courses';
    $centers = $wpdb->prefix  . 'centers';
    $certificates = $wpdb->prefix . 'certificates';
    $marksheet = $wpdb->prefix . 'marks';

    // Drop the tables if they exist
    $wpdb->query("DROP TABLE IF EXISTS $centers");
    $wpdb->query("DROP TABLE IF EXISTS $students");
    $wpdb->query("DROP TABLE IF EXISTS $wallet_transactions");
    $wpdb->query("DROP TABLE IF EXISTS $student_courses");
    $wpdb->query("DROP TABLE IF EXISTS $certificates");
    $wpdb->query("DROP TABLE IF EXISTS $marksheet");
}

function vkvtc_site_name_shortcode($attributes) {
    // Set default values
    $defaults = array(
        'name' => 'Kaushik',
        'msg'=> "Message"
    );
    ob_start();
?>
<div><h1>AnkitKaushik</h1></div>

<?php
    $html = ob_get_clean();
    // Merge user attributes with default values
    $attributes = shortcode_atts($defaults, $attributes);

    // Retrieve the site name
    $site_name = get_bloginfo("name");

    // If 'name' attribute is present, concatenate with the site name
    if (!empty($attributes['name'])) {
        $site_name .= ' ' . $attributes['name'] . ' ' . $attributes['msg'];
    }

    return $html;
}

// Enqueue Scripts:
function vkvtc_js_file(){
$path_js = plugins_url('/js/main.js', __FILE__);
$path_css = plugins_url('/css/style.css', __FILE__);
$dep_js = array('jquery');
// $var = filemtime(plugin_dir_url(__FILE__. 'js/main.js'));
wp_enqueue_style('vkvtc_main_css', $path_css, '', 1.0);
wp_enqueue_script('vkvtc_main_js', $path_js, $dep_js,1.0 ,true);
wp_add_inline_script('vkvtc_main_js', 'var_is_user_login = ' . is_user_logged_in() . ';' );
}

// 
function vkvtc_show_table_data(){
    global $wpdb;
    $wp_users = $wpdb->prefix . 'users';
    $query = "SELECT * FROM $wp_users";
    $results =  $wpdb->get_results($query);
    
    ob_start();
    ?>
    <table class="table-border">
        <thead>
            <tr>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
            </tr>
            <tbody>
                <tr>
                 <?php 
                 foreach($results as $row):?>
               <td><?php echo  $row->user_login; ?></td>
               <td><?php echo  $row->user_email; ?></td>
<td><?php echo $row->user_pass ?></td>
                   <?php endforeach; ?> 
                </tr>
            </tbody>
        </thead>
    </table>
    <?php
    $html = ob_get_clean();
    return $html;
}function vkvtc_my_posts() {
    $args = array(
        'post_type' => 'post',
        'meta_query' => array(
            array(
                'key' => 'views',
            )
        ),
        'orderby' => 'meta_value_num',  // Order by the numeric value of the 'views' meta key
        'meta_key' => 'views',         // Specify the meta key for ordering
        'order' => 'DESC',             // Order in descending order
    );

    $query = new WP_Query($args);
    ob_start();
    
    if ($query->have_posts()) :
    ?>
        <ul>
            <?php
            while ($query->have_posts()) {
                $query->the_post();
                $views = get_post_meta(get_the_ID(), 'views', true);
                echo '<li><a href="' . get_the_permalink() . '">' . get_the_title() . ' - Views: ' . $views . '</a></li>';
            }
            ?>
        </ul>
    <?php
    endif;

    wp_reset_postdata();
    $html = ob_get_clean();
    return $html;
}

function count_the_visits() {
    if (is_single()) {
        global $post;
        $views = get_post_meta($post->ID, 'views', true);
// print_r($views);
        if ($views === '') {  // Use strict comparison to check for an empty string
            add_post_meta($post->ID, 'views', 1, false);
        } else {
            $views ++;
            update_post_meta($post->ID, 'views', $views);
        }

        echo get_post_meta($post->ID , 'views', true) . 'views';
    }
}

function post_views(){
    global $post;
    return 'Total Views->'. get_post_meta($post->ID, 'views', true);
}

add_action('wp_head', 'count_the_visits');
add_action('wp_enqueue_scripts', 'vkvtc_js_file');
// Add shortcode
add_shortcode('show_table', 'vkvtc_show_table_data');
add_shortcode('vkvtc_siteName', 'vkvtc_site_name_shortcode');
add_shortcode('vkvtc_my_posts', 'vkvtc_my_posts' );

add_shortcode('post_views', 'post_views');
// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'vkvtc_activation');
register_deactivation_hook(__FILE__, 'vkvtc_deactivation');
