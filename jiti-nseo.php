<?php
/**
 * Plugin Name: Jiti - NSEO
 * Description: Supprime les paramètres non autorisés sur toutes les pages du site.
 * Version: 3.0
 * Author: Jiti
 * Author URI: https://jiti.me
 * License: Copyleft
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', function() {
    if ( is_admin() ) {
        return;
    }

    $allowed_params = [ 'utm_source', 'utm_medium', 'utm_campaign', 's', 'p', 'preview' ];

    $current_query = $_GET;
    $clean_query = array_intersect_key( $current_query, array_flip( $allowed_params ) );

    $request_uri = isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
    $new_url = home_url( $request_uri );

    if ( !empty( $clean_query ) ) {
        $new_url .= '?' . http_build_query( $clean_query );
    }

    $current_url = home_url( $_SERVER['REQUEST_URI'] );

    if ( urldecode( $current_url ) !== urldecode( $new_url ) ) {
        wp_redirect( $new_url, 301 );
        exit;
    }
});
