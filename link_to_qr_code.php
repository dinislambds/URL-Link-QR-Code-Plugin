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

function wordcount_plugin_textdomain(){
    load_plugin_textdomain ( "link-to-qrcode", false, dirname(__FILE__)."/languages" );
}
add_action("plugins_loaded","wordcount_plugin_textdomain");

function link_to_qr_callback( $content ){

    // Current Post ID, title, permalink/URL
    $post_id = get_the_ID();
    $post_title = get_the_title( $post_id );
    $post_url = urlencode(get_the_permalink( $post_id ));

    // Current post type
    $post_type = get_post_type( $post_id );

    // Post type check
    $exclude_post_type = apply_filters( "url_to_qrcode_exclude_post_types", array() );
    if ( in_array ( $post_type, $exclude_post_type ) ) {
        return $content;
    }

    // Diemntions of the QR image
    $image_size = apply_filters( "url_to_qrcode_image_size", "200x200" );

    $image_source = sprintf( "https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s", $image_size, $post_url );
    $content .= sprintf( "<div class='qrcode_img'><img src='%s' alt='%s'></div>", $image_source, $post_title );

    return $content;
}
add_filter( "the_content", "link_to_qr_callback" );

?>