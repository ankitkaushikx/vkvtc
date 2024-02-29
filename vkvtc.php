<?php
/**
 * @package Akismet
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


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql_centers);//
    dbDelta($sql_students);//
    dbDelta($sql_transactions);//
    dbDelta($sql_student_courses);//
    dbDelta($sql_certificates);
    dbDelta($sql__marksheet);
}

// Call the activation function on plugin/theme activation
register_activation_hook(__FILE__, 'vkvtc_activation');

register_deactivation_hook(__FILE__, 'vkvtc_deactivation');

function vkvtc_deactivation(){
    echo "djfkdf";
}
// SHORTCODES
function vkvtc_site_name(){
   $ankit =  get_bloginfo("name");
   return $ankit;
}
add_shortcode('vkvtc_siteName', 'vkvtc_site_name');