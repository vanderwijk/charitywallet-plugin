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
if ( ! defined('WPINC' )) {
	die;
}

define('CHAWA_PLUGIN_DIR', plugin_dir_url(__FILE__));
define('CHAWA_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__)); // Relative path
define('CHAWA_PLUGIN_VER', '1.0.1');

// Database
define('CHAWA_TABLE_TRANSACTIONS', $wpdb->prefix . 'chawa_transactions');
//define('CHAWA_TABLE_DONORS', $wpdb->prefix . 'donate_mollie_donors');
//define('CHAWA_TABLE_SUBSCRIPTIONS', $wpdb->prefix . 'donate_mollie_subscriptions');

function chawa_load_textdomain() {
	load_plugin_textdomain('chawa', false, basename( CHAWA_PLUGIN_DIR ) . '/languages' );
}
add_action('init', 'chawa_load_textdomain' );

require 'shortcodes/basket/shortcode-basket.php';
require 'shortcodes/donate/shortcode-donate.php';
//require 'shortcodes/wallet/shortcode-wallet.php';
require 'shortcodes/charity/shortcode-charity.php';
require 'shortcodes/account/shortcode-account.php';
require 'functions/database.php';

// create database tables
register_activation_hook( __FILE__, 'chawa_install' );

function chawa_enqueue_styles() {
	wp_enqueue_style('select2', CHAWA_PLUGIN_DIR . 'vendor/select2/select2/dist/css/select2.min.css', '', CHAWA_PLUGIN_VER );
	wp_enqueue_style('pretty-checkbox', '//cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css', '', CHAWA_PLUGIN_VER );
	wp_enqueue_style('chawa-plugin', CHAWA_PLUGIN_DIR . 'style.css', '', CHAWA_PLUGIN_VER );
}
add_action('wp_enqueue_scripts', 'chawa_enqueue_styles' );

function chawa_enqueue_scripts() {
	wp_enqueue_script('select2', CHAWA_PLUGIN_DIR . 'vendor/select2/select2/dist/js/select2.min.js', array('jquery' ), CHAWA_PLUGIN_VER );
	wp_enqueue_script('parsleyjs', CHAWA_PLUGIN_DIR . 'vendor/parsleyjs/parsley.min.js', array('jquery' ), CHAWA_PLUGIN_VER );

	wp_register_script('charitywallet', CHAWA_PLUGIN_DIR . 'charitywallet.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'date' => __('Date', 'chawa'),
		'remove' => __('Remove', 'chawa'),
		'would_you_like_to_remove' => __('Would you like to remove', 'chawa'),
		'from_your_basket' => __('from your basket?', 'chawa'),
		'cart_is_empty' => __('Your basket is empty', 'chawa')
	);
	wp_localize_script('charitywallet', 'chawa_localize', $translation_array );
	wp_enqueue_script('charitywallet' );

	wp_register_script('wallet', CHAWA_PLUGIN_DIR . 'shortcodes/wallet/wallet.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __('The top-up amount is too low.', 'chawa'),
		'choose_amount' => __('Please choose your amount', 'chawa'),
	);
	wp_localize_script('wallet', 'chawa_localize_wallet', $translation_array );

	wp_register_script('basket', CHAWA_PLUGIN_DIR . 'shortcodes/basket/basket.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __('The top-up amount is too low.', 'chawa'),
		'choose_amount' => __('Please choose your amount', 'chawa'),
	);
	wp_localize_script('basket', 'chawa_localize_basket', $translation_array );

	wp_register_script('onboarding_account', CHAWA_PLUGIN_DIR . 'shortcodes/onboarding/onboarding-account.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __('The top-up amount is too low.', 'chawa'),
		'choose_amount' => __('Please choose your amount', 'chawa'),
	);
	wp_localize_script('onboarding_account', 'chawa_localize_onboarding', $translation_array );

	wp_register_script('onboarding_pay', CHAWA_PLUGIN_DIR . 'shortcodes/onboarding/onboarding-pay.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'choose_payment_type' => __('Please choose a payment type.', 'chawa'),
		'choose_bank' => __('Please choose your bank.', 'chawa'),
		'accept' => __('Please accept the terms and privacy statement.', 'chawa'),
	);
	wp_localize_script('onboarding_pay', 'chawa_localize_onboarding', $translation_array );

	wp_register_script('onboarding_wallet', CHAWA_PLUGIN_DIR . 'shortcodes/onboarding/onboarding-wallet.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __('The top-up amount is too low.', 'chawa'),
		'choose_amount' => __('Please choose your amount', 'chawa'),
		'choose_frequency' => __('Please choose your frequency', 'chawa'),
	);
	wp_localize_script('onboarding_wallet', 'chawa_localize_onboarding', $translation_array );

	wp_register_script('user', CHAWA_PLUGIN_DIR . 'shortcodes/onboarding/user.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'top_up_amount_too_low' => __('The top-up amount is too low.', 'chawa'),
		'choose_amount' => __('Please choose your amount', 'chawa'),
	);
	wp_localize_script('user', 'chawa_localize_user', $translation_array );

	wp_register_script('donate', CHAWA_PLUGIN_DIR . 'shortcodes/donate/donate.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(

	);
	wp_localize_script('donate', 'chawa_localize_donate', $translation_array );

	wp_register_script('charity', CHAWA_PLUGIN_DIR . 'shortcodes/charity/charity.js', array('jquery' ), CHAWA_PLUGIN_VER );
	$translation_array = array(
		'are_you_sure' => __('Are you sure?', 'chawa'),
	);
	//wp_localize_script('charity', 'chawa_localize_charity', $translation_array );
}
add_action('wp_enqueue_scripts', 'chawa_enqueue_scripts' );

function script_basket(){
	wp_enqueue_script('basket' );
}
add_action('start_shortcode_basket', 'script_basket', 10);

function script_donate(){
	wp_enqueue_script('donate' );
}
add_action('start_shortcode_donate', 'script_donate', 10);

function script_charity(){
	//wp_enqueue_script('charity' );
}
add_action('start_shortcode_charity', 'script_charity', 10);

function nLbwuEa8_modify_create_user_route() {
	$users_controller = new WP_REST_Users_Controller();

	register_rest_route('wp/v2', '/users', array(
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
	));
};
//add_action('rest_api_init', 'nLbwuEa8_modify_create_user_route' );

add_action('gform_user_registered', 'wpc_gravity_registration_autologin',  10, 4 );
/**
 * Auto login after registration.
 */
function wpc_gravity_registration_autologin( $user_id, $user_config, $entry, $password ) {
	$user = get_userdata( $user_id );
	$user_login = $user->user_login;
	$user_password = $password;
	$user->set_role(get_option('default_role', 'subscriber'));

	wp_signon( array(
		'user_login' => $user_login,
		'user_password' =>  $user_password,
		'remember' => false

	));
}

// Remove admin bar
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
	show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'remove_admin_bar');


function page_templates( $template ) {

	if ( is_front_page()) {
		$new_template = locate_template( array('/shortcodes/onboarding/onboarding-account.php' ));
		if ( !empty( $new_template )) {
			return $new_template;
		}
	}

	return $template;
}
//add_filter('template_include', 'page_templates', 99 );

/* Rewrite Rules */

function chawa_rewrite_rules() {
	add_rewrite_rule('wallet/' . _x('transactions','rewrite rule','chawa') . '/?$', 'index.php?wallet=transactions', 'top' );
	add_rewrite_rule('onboarding/account/?$', 'index.php?onboarding=account', 'top' );
	add_rewrite_rule('onboarding/wallet/?$', 'index.php?onboarding=wallet', 'top' );
	add_rewrite_rule('onboarding/pay/?$', 'index.php?onboarding=pay', 'top' );
	add_rewrite_rule('onboarding/pay/charge/?$', 'index.php?onboarding=pay-charge', 'top' );
	add_rewrite_rule('webhook/charge/?$', 'index.php?webhook=charge', 'top' );
}
add_action('init', 'chawa_rewrite_rules');

/* Query Vars */
function chawa_register_query_var( $vars ) {
	$vars[] = 'wallet';
	$vars[] = 'account';
	$vars[] = 'webhook';
	$vars[] = 'onboarding';
	return $vars;
}
add_filter('query_vars', 'chawa_register_query_var' );

/* Template Include */
function chawa_onboarding_template_include($template) {
	global $wp_query;
	
	if ( isset($wp_query->query_vars['onboarding'])) {

		$query_var = $wp_query->query_vars['onboarding'];

		if ($query_var && $query_var === 'account') {
			return plugin_dir_path(__FILE__).'shortcodes/onboarding/onboarding-account.php';
		}
		
		if ($query_var && $query_var === 'wallet') {
			return plugin_dir_path(__FILE__).'shortcodes/onboarding/onboarding-wallet.php';
		}
		
		if ($query_var && $query_var === 'pay') {
			return plugin_dir_path(__FILE__).'shortcodes/onboarding/onboarding-pay.php';
		}
		
		if ($query_var && $query_var === 'pay-charge') {
			return plugin_dir_path(__FILE__).'shortcodes/onboarding/onboarding-pay-charge.php';
		}
		
		
	} else if ( isset($wp_query->query_vars['wallet'])) {

		$query_var = $wp_query->query_vars['wallet'];

		if ($query_var && $query_var === 'transactions') {
			return plugin_dir_path(__FILE__).'templates/wallet/transactions.php';
		}

	} else if ( isset($wp_query->query_vars['webhook'])) {

		$query_var = $wp_query->query_vars['webhook'];

		if ($query_var && $query_var === 'charge') {
			return plugin_dir_path(__FILE__).'functions/webhook-charge.php';
		}

	} else if ( isset($wp_query->query_vars['account'])) {

		$query_var = $wp_query->query_vars['account'];

		if ($query_var && $query_var === 'account') {
			return plugin_dir_path(__FILE__).'templates/account/account.php';
		}

	}

	return $template;
}
add_filter('template_include', 'chawa_onboarding_template_include', 1, 1); 


// Add rest api endpoint for 'wallet' meta field
function slug_register_wallet() {
	register_rest_field('user',
		'wallet',
		array(
			'get_callback'    => 'slug_get_wallet',
			'update_callback' => 'slug_update_wallet',
			'schema'          => null,
		)
	);
}
add_action('rest_api_init', 'slug_register_wallet' );

function slug_get_wallet( $data, $field_name, $request ) {
	return get_user_meta( $data['id'], $field_name, false );
}

function slug_update_wallet( $value, $object, $field_name ) {
	return update_user_meta( $object->id, $field_name, $value );
}