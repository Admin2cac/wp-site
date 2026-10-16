<?php
/**
 * Chức năng của giao diện Trà Thái Nguyên.
 */

if (!defined('ABSPATH')) {
    exit;
}

function tra_thai_nguyen_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    register_nav_menus(array(
        'primary' => 'Menu chính'
    ));
}
add_action('after_setup_theme', 'tra_thai_nguyen_setup');

function tra_thai_nguyen_enqueue_styles() {
    wp_enqueue_style(
        'tra-thai-nguyen-style',
        get_stylesheet_uri(),
        array(),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'tra_thai_nguyen_enqueue_styles');
