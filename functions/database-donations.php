<?php

global $chawa_table_ver_donations;
$chawa_table_ver_donations = CHAWA_DATABASE_VER;

// create database table for chawa_donations
function chawa_db_donations_install() {
	global $wpdb;
	global $chawa_table_ver_donations;

	$table_name_transactions = $wpdb->prefix . 'chawa_transactions';
	$table_name_donations = $wpdb->prefix . 'chawa_donations';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name_donations (
		donation_id mediumint(9) NOT NULL AUTO_INCREMENT,
		donation_status varchar(50) NOT NULL,
		transaction_id varchar(50) NOT NULL,
		charity_id varchar(50) NOT NULL,
		amount varchar(50) NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (donation_id),
		FOREIGN KEY (transaction_id) 
			REFERENCES " . $table_name_transactions . " (transaction_id) 
			ON DELETE NULL
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	add_option('chawa_table_ver_donations', $chawa_table_ver_donations);
}

// load first database table row
function chawa_db_donations_data() {
	global $wpdb;
	
	$table_name_donations = $wpdb->prefix . 'chawa_donations';
	
	$wpdb->insert(
		$table_name_donations, 
		array(
			'transaction_id' => 'chawa_000000000000000000000000',
			'donation_status' => 'status',
			'charity_id' => '0',
			'amount' => 0, 
			'time' => current_time('mysql')
		) 
	);
}

// update database tables
global $wpdb;
$installed_ver = get_option('chawa_table_ver_donations');

if ($installed_ver != $chawa_table_ver_donations) {

	$table_name_donations = $wpdb->prefix . 'chawa_donations';

	$sql = "CREATE TABLE $table_name_donations (
		donation_id mediumint(9) NOT NULL AUTO_INCREMENT,
		donation_status varchar(50) NOT NULL,
		transaction_id varchar(50) NOT NULL,
		charity_id varchar(50) NOT NULL,
		amount varchar(50) NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (donation_id),
		FOREIGN KEY (transaction_id) 
			REFERENCES wp_chawa_transactions (transaction_id) 
			ON DELETE NULL
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	update_option('chawa_table_ver_donations', $chawa_table_ver_donations);
}