<?php
/*
Plugin Name: KRM WooCommerce Slick Slider
Plugin URI: http://kream.it
Description: Plugin to add slick product slider
Author: Emanuela Castorina
Version: 1.0.0
Author URI: http://kream.it
Text Domain: krm-woocommerce-slick-slider
*/



if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

// Define constants ________________________________________
if ( ! defined( 'WSLICKSLIDER_VERSION' ) ) {
	define( 'WSLICKSLIDER_VERSION', '1.0.0' );
}


if ( ! defined( 'WSLICKSLIDER_FILE' ) ) {
	define( 'WSLICKSLIDER_FILE', __FILE__ );
}

if ( ! defined( 'WSLICKSLIDER_DIR' ) ) {
	define( 'WSLICKSLIDER_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WSLICKSLIDER_URL' ) ) {
	define( 'WSLICKSLIDER_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'WSLICKSLIDER_ASSETS_URL' ) ) {
	define( 'WSLICKSLIDER_ASSETS_URL', WSLICKSLIDER_URL . 'assets' );
}



function krm_slick_slider_constructor() {


	// Load ywraq text domain ___________________________________
	load_plugin_textdomain( 'ywraq', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	require_once( WSLICKSLIDER_DIR . 'class.woocommerce-slick-slider.php' );

	KRM_WC_Slick_Slider();
}
add_action( 'plugins_loaded', 'krm_slick_slider_constructor' );