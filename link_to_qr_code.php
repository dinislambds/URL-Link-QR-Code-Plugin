<?php
/*
Plugin Name: Link to QR Code
Plugin URI: https://github.com/dinislambds/URL-Link-QR-Code-Plugin
Description: Any post or page link will show under content as a QR Bar. So, You can share the QR bar/image any where and user will scan the image to visit the link directly.
Version: 1.0
Author: Md Din Islam
Author URI: https://dinislambds.com/
License: GPLv2 or later
Text Domain: link-to-qrcode
Domain Path: /languages/
 */

/* function wordcount_plugin_activation(){}
register_activation_hook( __FILE__, "wordcount_plugin_activation" );

function wordcount_plugin_deactivation(){}
register_deactivation_hook( __FILE__, "wordcount_plugin_deactivation" ); */

function wordcount_plugin_textdomain()
{
    load_plugin_textdomain("link-to-qrcode", false, dirname(__FILE__) . "/languages");
}
add_action("plugins_loaded", "wordcount_plugin_textdomain");

function link_to_qr_callback($content)
{

    // Current Post ID, title, permalink/URL
    $post_id = get_the_ID();
    $post_title = get_the_title($post_id);
    $post_url = urlencode(get_the_permalink($post_id));

    // Current post type
    $post_type = get_post_type($post_id);

    // Post type check
    $exclude_post_type = apply_filters("url_to_qrcode_exclude_post_types", array());
    if (in_array($post_type, $exclude_post_type)) {
        return $content;
    }

    // Diemntions of the QR image
    $height = get_option("qrcode_height");
    $width = get_option("qrcode_width");
    $height = $height ? $height : 150;
    $width = $width ? $width : 150;
    $image_size = apply_filters("url_to_qrcode_image_size", "{$height}x{$width}");

    $image_source = sprintf("https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s", $image_size, $post_url);
    $content .= sprintf("<div class='qrcode_img'><img src='%s' alt='%s'></div>", $image_source, $post_title);

    return $content;
}
add_filter("the_content", "link_to_qr_callback");

function link_to_qr_setting_fields()
{
    // Section name that will show under "General" setting
    add_settings_section( "qrcode_section", __("QR Code Dimentions","link-to-qrcode"), "qrcode_section_callback", "general" );

    // Height & Width fields
    add_settings_field("qrcode_height", __("QR code image height", "link-to-qrcode"), "qrcode_height_callback", "general", "qrcode_section");
    add_settings_field("qrcode_width", __("QR code image width", "link-to-qrcode"), "qrcode_width_callback", "general", "qrcode_section");

     // Height & Width fields register
    register_setting("general", "qrcode_height", array("sanitize_callback" => "esc_attr"));
    register_setting("general", "qrcode_width", array("sanitize_callback" => "esc_attr"));

    // Section name description
    function qrcode_section_callback(){
        echo "<p>".__('Add QR code image details here','link-to-qrcode')."</p>";
    }

     // Height input field
    function qrcode_height_callback()
    {
        $height = get_option("qrcode_height");
        printf("<input type='text' name='%s' id='%s' value='%s' />", "qrcode_height", "qrcode_height", $height);
    }

    // Width input field
    function qrcode_width_callback()
    {
        $width = get_option("qrcode_width");
        printf("<input type='text' name='%s' id='%s' value='%s' />", "qrcode_width", "qrcode_width", $width);
    }
}
add_action("admin_init", "link_to_qr_setting_fields");