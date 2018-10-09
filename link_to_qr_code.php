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

    // Current Post ID
    $post_id = get_the_ID();
    $post_title = get_the_title( $post_id );
    $post_url = rlencode(get_the_permalink( $post_id ));
    $image_source = sprintf( "https://api.qrserver.com/v1/create-qr-code/?size=185x185&ecc=L&qzone=1&data=%s", $post_url );
    $content .= sprintf( "<div class='qrcode_img'><img src='%s' alt='%s'></div>", $image_source, $post_title );

    return $content;
}
add_filter( "the_content", "link_to_qr_callback" );

?>