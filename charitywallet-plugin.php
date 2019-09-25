<?php

/**
 * Plugin Name:       Charity Wallet
 * Plugin URI:        https://charitywallet.com
 * Description:       Charity Wallet WordPress plugin
 * Version:           1.0.1
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
define( 'CHAWA_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) ); // Relative path
define( 'CHAWA_PLUGIN_VER', '1.0.1' );

function chawa_load_textdomain() {
	load_plugin_textdomain( 'chawa', false, basename( CHAWA_PLUGIN_DIR ) . '/languages' );
}
add_action( 'init', 'chawa_load_textdomain' );

require 'shortcodes/basket/shortcode-basket.php';
require 'shortcodes/donate/shortcode-donate.php';
require 'shortcodes/wallet/shortcode-wallet.php';
require 'shortcodes/charity/shortcode-charity.php';
require 'shortcodes/account/shortcode-account.php';
require 'shortcodes/onboarding/shortcode-onboarding.php';

function chawa_enqueue_styles() {
	wp_enqueue_style( 'select2', CHAWA_PLUGIN_DIR . 'vendor/select2/select2/dist/css/select2.min.css', '', CHAWA_PLUGIN_VER );
	wp_enqueue_style( 'pretty-checkbox', '//cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css', '', CHAWA_PLUGIN_VER );
	wp_enqueue_style( 'chawa-plugin', CHAWA_PLUGIN_DIR . 'style.css', '', CHAWA_PLUGIN_VER );
}
add_action( 'wp_enqueue_scripts', 'chawa_enqueue_styles' );

function chawa_enqueue_scripts() {
	wp_enqueue_script( 'select2', CHAWA_PLUGIN_DIR . 'vendor/select2/select2/dist/js/select2.min.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	wp_enqueue_script( 'parsleyjs', CHAWA_PLUGIN_DIR . 'vendor/parsleyjs/parsley.min.js', array( 'jquery' ), CHAWA_PLUGIN_VER );

	wp_register_script( 'charitywallet', CHAWA_PLUGIN_DIR . 'charitywallet.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'date' => __( 'Date', 'chawa'),
		'remove' => __( 'Remove', 'chawa'),
		'would_you_like_to_remove' => __( 'Would you like to remove', 'chawa'),
		'from_your_basket' => __( 'from your basket?', 'chawa'),
		'cart_is_empty' => __( 'Je mandje is leeg', 'chawa')
	);
	wp_localize_script( 'charitywallet', 'chawa_localize', $translation_array );
	wp_enqueue_script( 'charitywallet' );

	wp_register_script( 'wallet', CHAWA_PLUGIN_DIR . 'shortcodes/wallet/wallet.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __( 'The top-up amount is too low.', 'chawa'),
		'choose_amount' => __( 'Please choose your amount', 'chawa'),
	);
	wp_localize_script( 'wallet', 'chawa_localize_wallet', $translation_array );

	wp_register_script( 'basket', CHAWA_PLUGIN_DIR . 'shortcodes/basket/basket.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __( 'The top-up amount is too low.', 'chawa'),
		'choose_amount' => __( 'Please choose your amount', 'chawa'),
	);
	wp_localize_script( 'basket', 'chawa_localize_basket', $translation_array );

	wp_register_script( 'onboarding', CHAWA_PLUGIN_DIR . 'shortcodes/onboarding/onboarding.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __( 'The top-up amount is too low.', 'chawa'),
		'choose_amount' => __( 'Please choose your amount', 'chawa'),
	);
	wp_localize_script( 'onboarding', 'chawa_localize_onboarding', $translation_array );

	wp_register_script( 'user', CHAWA_PLUGIN_DIR . 'shortcodes/onboarding/user.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __( 'The top-up amount is too low.', 'chawa'),
		'choose_amount' => __( 'Please choose your amount', 'chawa'),
	);
	wp_localize_script( 'user', 'chawa_localize_user', $translation_array );

	wp_register_script( 'donate', CHAWA_PLUGIN_DIR . 'shortcodes/donate/donate.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(

	);
	wp_localize_script( 'donate', 'chawa_localize_donate', $translation_array );

	wp_register_script( 'charity', CHAWA_PLUGIN_DIR . 'shortcodes/charity/charity.js', array( 'jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'are_you_sure' => __( 'Are you sure?', 'chawa'),
	);
	//wp_localize_script( 'charity', 'chawa_localize_charity', $translation_array );
}
add_action( 'wp_enqueue_scripts', 'chawa_enqueue_scripts' );

function script_wallet() {
	wp_enqueue_script( 'wallet' );
}
add_action('start_shortcode_wallet', 'script_wallet', 10);

function script_onboarding(){
	wp_enqueue_script( 'onboarding' );
	wp_enqueue_script( 'user' );
	wp_localize_script( 'user', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'title' => ( current_time( 'H:i:s' ) ) ) );
}
add_action('start_shortcode_onboarding', 'script_onboarding', 10);

function script_basket(){
	wp_enqueue_script( 'basket' );
}
add_action('start_shortcode_basket', 'script_basket', 10);

function script_donate(){
	wp_enqueue_script( 'donate' );
}
add_action('start_shortcode_donate', 'script_donate', 10);

function script_charity(){
	//wp_enqueue_script( 'charity' );
}
add_action('start_shortcode_charity', 'script_charity', 10);

function nLbwuEa8_modify_create_user_route() {
    $users_controller = new WP_REST_Users_Controller();

    register_rest_route( 'wp/v2', '/users', array(
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => array($users_controller, 'create_item'),
            'permission_callback' => function( $request ) {

                // METHOD 1: Silently force the role to be a subscriber
                $request->set_param('roles', array('subscriber'));

               return true;
            },
            'args' => $users_controller->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
        ),
    ) );
};
add_action( 'rest_api_init', 'nLbwuEa8_modify_create_user_route' );