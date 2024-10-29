<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Create settings page
function cdb_admin_menu() {
    add_menu_page(
        'Download Button Settings',
        'Download Button',
        'manage_options',
        'cdb-settings',
        'cdb_settings_page',
        'dashicons-download'
    );
}
add_action('admin_menu', 'cdb_admin_menu');

// Enqueue the custom admin CSS
function cdb_enqueue_admin_styles() {
    wp_enqueue_style('cdb_admin_styles', plugin_dir_url(__FILE__) . 'css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'cdb_enqueue_admin_styles');

// Enqueue the custom admin CSS and JavaScript
function cdb_admin_enqueue_scripts($hook) {
    // Only enqueue scripts for your settings page
    if ($hook != 'toplevel_page_cdb-settings') {
        return;
    }
    wp_enqueue_style('cdb-admin-styles', plugin_dir_url(__FILE__) . 'css/admin-style.css');
    wp_enqueue_script('cdb-admin-scripts', plugin_dir_url(__FILE__) . 'js/admin-scripts.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'cdb_admin_enqueue_scripts');

// Display settings page content
function cdb_settings_page() {
    ?>
    <div class="wrap">
        <h1>Image Download Button Settings</h1>
        <p><b>IMPORTANT:</b> To perform proper <b>button customization</b> I will recommend you to check out my complete <a href="https://raptorkit.com/auto-image-download-button/#documentation" target="_blank"><b>documentation</b></a> on how to use and customize the download button.</p>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('cdb_settings_group');
            do_settings_sections('cdb-settings');
            submit_button();
            ?>
        </form>
        
        <h2>Live Preview</h2>
        <div id="cdb_button_preview">
            <a href="#" id="preview-button" style="
                color: <?php echo esc_attr(get_option('cdb_button_text_color', '#fff')); ?>;
                background-color: <?php echo esc_attr(get_option('cdb_button_color', '#000')); ?>;
                font-size: <?php echo esc_attr(get_option('cdb_button_size', '14px')); ?>;
                border: <?php echo esc_attr(get_option('cdb_button_border', '1px solid #000')); ?>;
                border-radius: <?php echo esc_attr(get_option('cdb_button_radius', '5px')); ?>;
                padding: <?php echo esc_attr(get_option('cdb_button_padding', '10px 20px')); ?>;
                margin: <?php echo esc_attr(get_option('cdb_button_margin', '10px 0')); ?>;
                display: inline-block;
                text-decoration: none;">
                <?php echo esc_attr(get_option('cdb_button_text', 'Download')); ?>
            </a>
        </div>
   
    <style>
        #preview-button:hover {
            background-color: <?php echo esc_attr(get_option('cdb_button_hover_color', '#333')); ?>;
        }
    </style>
 
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Live preview elements
        const previewButton = document.getElementById('preview-button');
        const buttonTextInput = document.querySelector('input[name="cdb_button_text"]');
        const buttonTextColorInput = document.querySelector('input[name="cdb_button_text_color"]');
        const buttonColorInput = document.querySelector('input[name="cdb_button_color"]');
        const buttonSizeInput = document.querySelector('input[name="cdb_button_size"]');
        const buttonBorderInput = document.querySelector('input[name="cdb_button_border"]');
        const buttonRadiusInput = document.querySelector('input[name="cdb_button_radius"]');
        const buttonPaddingInput = document.querySelector('input[name="cdb_button_padding"]');
        const buttonMarginInput = document.querySelector('input[name="cdb_button_margin"]');

        // Function to update the live preview
        function updateButtonPreview() {
            previewButton.textContent = buttonTextInput.value;
            previewButton.style.color = buttonTextColorInput.value;
            previewButton.style.backgroundColor = buttonColorInput.value;
            previewButton.style.fontSize = buttonSizeInput.value;
            previewButton.style.border = buttonBorderInput.value;
            previewButton.style.borderRadius = buttonRadiusInput.value;
            previewButton.style.padding = buttonPaddingInput.value;
            previewButton.style.margin = buttonMarginInput.value;
        }

        // Event listeners for input changes
        buttonTextInput.addEventListener('input', updateButtonPreview);
        buttonTextColorInput.addEventListener('input', updateButtonPreview);
        buttonColorInput.addEventListener('input', updateButtonPreview);
        buttonSizeInput.addEventListener('input', updateButtonPreview);
        buttonBorderInput.addEventListener('input', updateButtonPreview);
        buttonRadiusInput.addEventListener('input', updateButtonPreview);
        buttonPaddingInput.addEventListener('input', updateButtonPreview);
        buttonMarginInput.addEventListener('input', updateButtonPreview);

        // Initialize the preview with current settings
        updateButtonPreview();
    });
    </script>
    <?php
}

// Register settings
function cdb_register_settings() {
    register_setting('cdb_settings_group', 'cdb_button_text', 'sanitize_text_field');
    register_setting('cdb_settings_group', 'cdb_button_text_color', 'sanitize_hex_color');
    register_setting('cdb_settings_group', 'cdb_button_color', 'sanitize_hex_color');
    register_setting('cdb_settings_group', 'cdb_button_size', 'sanitize_text_field');
    register_setting('cdb_settings_group', 'cdb_button_border', 'sanitize_text_field');
    register_setting('cdb_settings_group', 'cdb_button_radius', 'sanitize_text_field');
    register_setting('cdb_settings_group', 'cdb_button_padding', 'sanitize_text_field');
    register_setting('cdb_settings_group', 'cdb_button_margin', 'sanitize_text_field');

    add_settings_section('cdb_main_section', 'Button Customization', null, 'cdb-settings');

    add_settings_field('cdb_button_text', 'Button Text', 'cdb_button_text_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_button_text_color', 'Button Text Color', 'cdb_button_text_color_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_button_color', 'Button Color', 'cdb_button_color_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_button_size', 'Button Size', 'cdb_button_size_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_button_border', 'Button Border', 'cdb_button_border_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_button_radius', 'Button Radius', 'cdb_button_radius_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_button_padding', 'Button Padding', 'cdb_button_padding_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_button_margin', 'Button Margin', 'cdb_button_margin_callback', 'cdb-settings', 'cdb_main_section');
    add_settings_field('cdb_post_types', 'Post Types', 'cdb_post_types_callback', 'cdb-settings', 'cdb_main_section');
}
add_action('admin_init', 'cdb_register_settings');

// Callback functions for fields
function cdb_button_text_callback() {
    $value = get_option('cdb_button_text', 'Download');
    echo '<input type="text" name="cdb_button_text" value="' . esc_attr($value) . '">';
}

function cdb_button_text_color_callback() {
    $value = get_option('cdb_button_text_color', '#fff');
    echo '<input type="color" name="cdb_button_text_color" value="' . esc_attr($value) . '">';
}

function cdb_button_color_callback() {
    $value = get_option('cdb_button_color', '#000');
    echo '<input type="color" name="cdb_button_color" value="' . esc_attr($value) . '">';
}

function cdb_button_size_callback() {
    $value = get_option('cdb_button_size', '14px');
    echo '<input type="text" name="cdb_button_size" value="' . esc_attr($value) . '">';
}

function cdb_button_border_callback() {
    $value = get_option('cdb_button_border', '1px solid #000');
    echo '<input type="text" name="cdb_button_border" value="' . esc_attr($value) . '">';
}

function cdb_button_radius_callback() {
    $value = get_option('cdb_button_radius', '5px');
    echo '<input type="text" name="cdb_button_radius" value="' . esc_attr($value) . '">';
}

function cdb_button_padding_callback() {
    $value = get_option('cdb_button_padding', '10px 20px');
    echo '<input type="text" name="cdb_button_padding" value="' . esc_attr($value) . '">';
}

function cdb_button_margin_callback() {
    $value = get_option('cdb_button_margin', '10px 0');
    echo '<input type="text" name="cdb_button_margin" value="' . esc_attr($value) . '">';
}

function cdb_post_types_callback() {
    $post_types = get_option('cdb_post_types', ['post', 'page']);
    $all_post_types = get_post_types(['public' => true], 'objects');

    foreach ($all_post_types as $post_type) {
        $checked = in_array($post_type->name, $post_types) ? 'checked' : '';
        echo '<label><input type="checkbox" name="cdb_post_types[]" value="' . $post_type->name . '" ' . $checked . '> ' . $post_type->label . '</label><br>';
    }
}
