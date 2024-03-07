<?php
/**
 * Plugin Name: Vkvtc
 * Plugin URI:
 * Description: Vkvtc
 * Author: Ankit Kaushik
 * Author URI: https://github.com/ankitkaushkix
 */

if (!defined('ABSPATH')) {
    header("Location: /");
    exit;
}

class VkvtcPlugin
{
    private $students_table;
    private $wallet_transactions_table;
    private $student_courses_table;
    private $centers_table;
    private $certificates_table;
    private $marksheet_table;

    public function __construct()
    {
        global $wpdb;

        $this->students_table = $wpdb->prefix . 'students';
        $this->wallet_transactions_table = $wpdb->prefix . 'wallet_transactions';
        $this->student_courses_table = $wpdb->prefix . 'student_courses';
        $this->centers_table = $wpdb->prefix . 'centers';
        $this->certificates_table = $wpdb->prefix . 'certificates';
        $this->marksheet_table = $wpdb->prefix . 'marks';

        // Register activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activation'));
        register_deactivation_hook(__FILE__, array($this, 'deactivation'));

        // Add actions and filters
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'count_the_visits'));
        add_action('template_redirect', array($this, 'login_handler'));
        add_action('template_redirect', array($this, 'check_redirect'));
        add_action('wp_logout', array($this, 'redirect_after_logout'));
        add_shortcode('my-register-form', array($this, 'register_form_shortcode'));
        add_shortcode('show_user_table', array($this, 'show_user_table_shortcode'));
        add_shortcode('show_table', array($this, 'show_table_data_shortcode'));
        add_shortcode('vkvtc_siteName', array($this, 'site_name_shortcode'));
        add_shortcode('vkvtc_my_posts', array($this, 'my_posts_shortcode'));
        add_shortcode('my-login-form', array($this, 'login_form_shortcode'));
        add_shortcode('post_views', array($this, 'post_views_shortcode'));
        add_shortcode('my-profile', array($this, 'profile_shortcode'));
    }

    public function activation()
    {
        // Create tables on plugin activation
        $this->create_tables();
    }

    public function deactivation()
    {
        // Drop tables on plugin deactivation
        $this->drop_tables();
    }

    private function create_tables()
    {
        // Your table creation SQL Queries here
    }

    private function drop_tables()
    {
        // Your table dropping SQL queries go here
    }

    public function add_admin_menu()
    {
        // Add your admin menu items here
    }

    public function enqueue_scripts()
    {
        // Enqueue your scripts and styles here
    }

    public function count_the_visits()
    {
        // Your count the visits logic goes here
    }

    public function login_handler()
    {
        // Your login handling logic goes here
    }

    public function check_redirect()
    {
        // Your redirect checking logic goes here
    }

    public function redirect_after_logout()
    {
        // Your logout redirect logic goes here
    }

    public function register_form_shortcode()
    {
        // Your register form shortcode logic goes here
    }

    public function show_user_table_shortcode()
    {
        // Your show user table shortcode logic goes here
    }

    public function show_table_data_shortcode()
    {
        // Your show table data shortcode logic goes here
    }

    public function site_name_shortcode($attributes)
    {
        // Your site name shortcode logic goes here
    }

    public function my_posts_shortcode()
    {
        // Your my posts shortcode logic goes here
    }

    public function login_form_shortcode()
    {
        // Your login form shortcode logic goes here
    }

    public function post_views_shortcode()
    {
        // Your post views shortcode logic goes here
    }

    public function profile_shortcode()
    {
        // Your profile shortcode logic goes here
    }
}

// Instantiate the plugin class
new VkvtcPlugin();
