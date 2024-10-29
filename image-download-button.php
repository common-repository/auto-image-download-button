<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/*
Plugin Name: Auto Image Download Button
Plugin URI: https://raptorkit.com/auto-image-download-button/
Description: Automatically adds a customizable download button below every image in a post for visitors to download the image
Version: 2.2.0
Author: <a href="https://raptorkit.com" target="_blank">RaptorKit</a> | Developed by <a href="https://shreykajaria.com" target="_blank">Shrey Kajaria</a>
Author URI: https://raptorkit.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Enqueue styles and scripts
function cdb_enqueue_scripts() {
    wp_enqueue_style('cdb-styles', plugin_dir_url(__FILE__) . 'css/download-button-style.css');
    wp_enqueue_script('cdb-scripts', plugin_dir_url(__FILE__) . 'js/download-button-script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'cdb_enqueue_scripts');

// Include admin settings
require_once plugin_dir_path(__FILE__) . 'admin/admin-settings.php';

// Add the download button below each image
function cdb_add_download_button_below_images($content) {
    $button_text = get_option('cdb_button_text', 'Download');
    $content = preg_replace('/<img(.*?)>/', '<div class="image-container"><img$1><a href="#" class="custom-download-button">' . esc_html($button_text) . '</a></div>', $content);
    return $content;
}

// Modify button insertion logic based on meta box value
function cdb_filter_content($content) {
    global $post;

    $post_types = get_option('cdb_post_types', ['post', 'page']);
    if (is_singular($post_types)) {
        $enable_download_button = get_post_meta($post->ID, '_cdb_enable_download_button', true);
        if ($enable_download_button) {
            $content = cdb_add_download_button_below_images($content);
        }
    }
    return $content;
}
add_filter('the_content', 'cdb_filter_content');

// Output custom styles based on admin settings
function cdb_output_custom_styles() {
    $button_color = get_option('cdb_button_color', '#000');
    $button_text_color = get_option('cdb_button_text_color', '#fff');
    $button_size = get_option('cdb_button_size', '14px');
    $button_border = get_option('cdb_button_border', '1px solid #000');
    $button_radius = get_option('cdb_button_radius', '5px');
    $button_padding = get_option('cdb_button_padding', '8px 12px');
    $button_margin = get_option('cdb_button_margin', '10px 0');

    $custom_css = "
        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        .custom-download-button {
            display: inline-block;
            padding: $button_padding;
            margin: $button_margin;
            color: $button_text_color !important;
            text-decoration: none;
            background-color: $button_color;
            font-size: $button_size;
            border: $button_border;
            border-radius: $button_radius;
            transition: all 0.3s ease;
        }
        .custom-download-button:hover {
            opacity: 0.8;
        }
    ";

    wp_add_inline_style('cdb-styles', $custom_css);
}
add_action('wp_enqueue_scripts', 'cdb_output_custom_styles');

// Add custom admin styles for the icon
function cdb_admin_custom_styles() {
    $custom_admin_css = "
        #toplevel_page_cdb-settings .dashicons-admin-settings {
            color: #fcb600;
        }
    ";
    wp_add_inline_style('wp-admin', $custom_admin_css);
}
add_action('admin_enqueue_scripts', 'cdb_admin_custom_styles');

// Add meta box to post/page editor
function cdb_add_meta_box() {
    add_meta_box(
        'cdb_download_button_meta_box',
        'Download Button Settings',
        'cdb_meta_box_callback',
        ['post', 'page'],
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'cdb_add_meta_box');

// Meta box callback function
function cdb_meta_box_callback($post) {
    $enabled = get_post_meta($post->ID, '_cdb_enable_download_button', true);
    echo '<label for="cdb_enable_download_button">';
    echo '<input type="checkbox" id="cdb_enable_download_button" name="cdb_enable_download_button" value="1"' . checked(1, $enabled, false) . '> Enable Download Button';
    echo '</label>';
}

// Save meta box value when post is saved
function cdb_save_meta_box($post_id) {
    if (isset($_POST['cdb_enable_download_button'])) {
        update_post_meta($post_id, '_cdb_enable_download_button', 1);
    } else {
        delete_post_meta($post_id, '_cdb_enable_download_button');
    }
}
add_action('save_post', 'cdb_save_meta_box');

// Add a settings link to the plugin actions
function cdb_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=cdb-settings">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cdb_add_settings_link');
