<?php
/*
Plugin Name: Contact Form Easy - CFE
Plugin URI: https://phamhuuthanh.com
Description: Một Plugin đơn giản và hoàn toàn miễn phí giúp bạn tạo và tùy chỉnh mẫu liên hệ, quản lý danh sách liên hệ, cấu hình smtp để xử lý việc gửi mail qua website và nhiều chức năng hơn nữa.
Version: 2.0
Author: Phạm Hữu Thạnh
Author URI: https://phamhuuthanh.com
License: GPLv2 or later
Text Domain: cfe-contact-form
*/

defined( 'ABSPATH' ) or die( 'Không có quyền truy cập trực tiếp.' );

// Định nghĩa các hằng số
define( 'CFE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CFE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Tải các file chức năng của plugin.
 */
function cfe_load_plugin_files() {
    require_once CFE_PLUGIN_DIR . 'includes/cpt.php';
    require_once CFE_PLUGIN_DIR . 'includes/meta-boxes.php';
    require_once CFE_PLUGIN_DIR . 'includes/shortcode.php';
    require_once CFE_PLUGIN_DIR . 'includes/ajax-handler.php';
    require_once CFE_PLUGIN_DIR . 'includes/smtp-config.php';
    require_once CFE_PLUGIN_DIR . 'includes/contact-list.php';
}
add_action( 'plugins_loaded', 'cfe_load_plugin_files' );

/**
 * Thêm menu chính cho plugin.
 */
function cfe_add_main_menu_page() {
    add_menu_page(
        'Contact Form Easy',
        'Contact Form Easy',
        'manage_options',
        'cfe-main-menu',
        'cfe_render_main_menu_page',
        'dashicons-email-alt',
        25
    );
}
add_action( 'admin_menu', 'cfe_add_main_menu_page' );

/**
 * Trang chính của menu.
 */
function cfe_render_main_menu_page() {
    echo '<div class="wrap"><h1>Contact Form Easy - CFE</h1></div>';
}

/**
 * Đăng ký các cài đặt reCAPTCHA
 */
function cfe_recaptcha_settings_init() {
    register_setting( 'cfe_settings_group', 'cfe_recaptcha_site_key' );
    register_setting( 'cfe_settings_group', 'cfe_recaptcha_secret_key' );

    add_settings_section(
        'cfe_recaptcha_section',
        'Cài đặt reCAPTCHA',
        'cfe_recaptcha_section_callback',
        'cfe_settings_page'
    );

    add_settings_field(
        'cfe_recaptcha_site_key_field',
        'Site Key',
        'cfe_recaptcha_site_key_callback',
        'cfe_settings_page',
        'cfe_recaptcha_section'
    );

    add_settings_field(
        'cfe_recaptcha_secret_key_field',
        'Secret Key',
        'cfe_recaptcha_secret_key_callback',
        'cfe_settings_page',
        'cfe_recaptcha_section'
    );
}
add_action( 'admin_init', 'cfe_recaptcha_settings_init' );

function cfe_recaptcha_section_callback() {
    echo '<p>Nhập Site Key và Secret Key từ Google reCAPTCHA v2 (I\'m not a robot) để sử dụng.</p>';
}

function cfe_recaptcha_site_key_callback() {
    $site_key = get_option('cfe_recaptcha_site_key');
    echo '<input type="text" name="cfe_recaptcha_site_key" value="' . esc_attr($site_key) . '" class="regular-text" />';
}

function cfe_recaptcha_secret_key_callback() {
    $secret_key = get_option('cfe_recaptcha_secret_key');
    echo '<input type="text" name="cfe_recaptcha_secret_key" value="' . esc_attr($secret_key) . '" class="regular-text" />';
}

/**
 * Thêm trang cài đặt reCAPTCHA vào menu plugin.
 */
function cfe_add_recaptcha_settings_page() {
    add_submenu_page(
        'cfe-main-menu',
        'Cài đặt reCAPTCHA',
        'Cài đặt reCAPTCHA',
        'manage_options',
        'cfe-recaptcha-settings',
        'cfe_render_recaptcha_settings_page'
    );
}
add_action( 'admin_menu', 'cfe_add_recaptcha_settings_page' );

function cfe_render_recaptcha_settings_page() {
    ?>
    <div class="wrap">
        <h1>Cài đặt reCAPTCHA</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'cfe_settings_group' );
            do_settings_sections( 'cfe_settings_page' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}