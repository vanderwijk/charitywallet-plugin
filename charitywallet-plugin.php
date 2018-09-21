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
 * Text Domain:       charitywallet-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require 'shortcode-wallet.php';