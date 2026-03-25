<?php
/**
 * Plugin Name: Gala Power PC Configurator
 * Description: A custom React/JS configurator bridging with WooCommerce.
 * Version: 1.0
 * Author: Timothy itayi
 */

// Exit if accessed directly for security
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Enqueue the JavaScript file
function gala_enqueue_configurator_scripts() {
    // We only want to load this script if the shortcode is on the page
    global $post;
    if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'pc_configurator' ) ) {
        
        wp_enqueue_script(
            'gala-configurator-js',
            plugin_dir_url( __FILE__ ) . 'build/index.js',
            array('wp-element'), // 'wp-element' is WordPress's bundled version of React/ReactDOM
            '1.0',
            true // Load in the footer
        );

        // Pass the WooCommerce API URL and a Nonce (security token) to your JS
        wp_localize_script( 'gala-configurator-js', 'galaConfig', array(
            'apiUrl' => rest_url( 'wc/v3/' ),
            'nonce'  => wp_create_nonce( 'wp_rest' )
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'gala_enqueue_configurator_scripts' );

// 2. Register the Shortcode
function gala_render_configurator_shortcode() {
    // This creates the empty div where your React app will mount
    return '<div id="gala-configurator-root">Loading configurator...</div>';
}
add_shortcode( 'pc_configurator', 'gala_render_configurator_shortcode' );