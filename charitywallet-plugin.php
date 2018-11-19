<?php

/**
 * Plugin Name:       Charity Wallet
 * Plugin URI:        https://charitywallet.com
 * Description:       Charity Wallet WordPress plugin
 * Version:           1.0.0
 * Author:            Johan van der Wijk
 * Author URI:        https://thewebworks.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       chawa
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CHAWA_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
define( 'CHAWA_PLUGIN_VER', '1.0.1' );

require 'shortcode-wallet.php';
require 'shortcode-basket.php';

function chawa_load_textdomain() {
	load_plugin_textdomain( 'chawa', false, CHAWA_PLUGIN_DIR . '/languages' ); 
}
add_action( 'init', 'chawa_load_textdomain' );

function chawa_enqueue_styles() {
	wp_enqueue_style( 'chawa-plugin', CHAWA_PLUGIN_DIR . 'style.css', '', CHAWA_PLUGIN_VER );
	wp_enqueue_style( 'pretty-checkbox', 'https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css', '', CHAWA_PLUGIN_VER );
}
add_action( 'wp_enqueue_scripts', 'chawa_enqueue_styles' );

function chawa_enqueue_scripts() {
	wp_register_script( 'wallet-top-up', CHAWA_PLUGIN_DIR . 'script.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __( 'The top-up amount is too low.', 'chawa'),
		'choose_amount' => __( 'Please choose your amount', 'chawa'),
	);
	wp_localize_script( 'wallet-top-up', 'chawa', $translation_array );
	wp_enqueue_script( 'wallet-top-up' );
}
add_action( 'wp_enqueue_scripts', 'chawa_enqueue_scripts' );