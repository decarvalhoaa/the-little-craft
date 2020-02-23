<?php

/**
 * Loads the StoreFront parent theme stylesheet.
 */

function sf_child_theme_enqueue_styles() {
    wp_enqueue_style( 'storefront-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'storefront-style' ) );
}
add_action( 'wp_enqueue_scripts', 'sf_child_theme_enqueue_styles' );

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

require 'includes/tlc-functions.php';
require 'includes/tlc-template-functions.php';
require 'includes/tlc-template-hooks.php';

/**
 * Load custom text domain and overrides to woocommerce plugin and parent theme
 * translations
 */
add_action( 'after_setup_theme', function () {
    // load custom translation file for woocommerce plugin
    //load_theme_textdomain( 'woocommerce', get_stylesheet_directory() . '/languages/woocommerce' );
    // load custom translation file for parent theme
    //load_theme_textdomain( 'storefront', get_stylesheet_directory() . '/languages/storefront' );
    // load translation file for the child theme
    load_child_theme_textdomain( 'thelittlecraft', get_stylesheet_directory() . '/languages' );
} );

/**
 * Load javascript scripts
 */
function tlc_load_javascript_files() {
    wp_enqueue_script( 'tlc_custom_js', get_stylesheet_directory_uri() . '/js/tlc.js', array('jquery'), '2.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'tlc_load_javascript_files' );

function add_fontawesome_kit() {
    wp_register_script( 'fa-kit', 'https://kit.fontawesome.com/4890460809.js', array( 'jquery' ) , '5.9.0', true ); // -- From an External URL
	// Javascript - Enqueue Scripts
    wp_enqueue_script( 'fa-kit' );
}
add_action( 'wp_enqueue_scripts', 'add_fontawesome_kit', 100 );