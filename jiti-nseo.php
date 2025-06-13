<?php
/**
 * Plugin Name: Jiti - NSEO
 * Description: Supprime les paramètres non autorisés sur toutes les pages du site.
 * Version: 4.0
 * Author: Jiti
 * Author URI: https://jiti.me
 * License: Copyleft
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', function() {
    // Ne rien faire dans l'administration. Bug : certains composers peuvent buguer, à corriger.
    if ( is_admin() ) {
        return;
    }

    // Ne traiter que les requêtes GET
    if ( $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
        return;
    }

    // Paramètres autorisés
    $allowed_params = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        's',
        'p',
        'preview',
        '_thumbnail_id',
        'preview_nonce',
        'preview_id'
    ];

    // Nettoyage des paramètres
    $current_query = $_GET;
    $clean_query = array_intersect_key( $current_query, array_flip( $allowed_params ) );

    // Ne rien faire si les paramètres sont déjà propres
    if ( $clean_query === $current_query ) {
        return;
    }

    // Nettoyage de l'URI
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( strtok( $_SERVER['REQUEST_URI'], '?' ) ) : '/';
    $new_url = home_url( $request_uri );

    // Ajouter les paramètres autorisés
    if ( !empty( $clean_query ) ) {
        $new_url .= '?' . http_build_query( $clean_query );
    }

    // Redirection permanente vers l'URL nettoyée
    wp_redirect( $new_url, 301 );
    exit;
});
