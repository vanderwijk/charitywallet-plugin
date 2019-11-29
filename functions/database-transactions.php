<?php

global $chawa_db_version;
$chawa_db_version = '1.0.2';

// create database table for chawa_transactions
function chawa_install() {
	global $wpdb;
	global $chawa_db_version;

	$table_name = $wpdb->prefix . 'chawa_transactions';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		user_id mediumint(9) NOT NULL,
		amount varchar(10) NOT NULL,
		recurring BOOLEAN,
		transaction_id varchar(255) NOT NULL,
		transaction_type varchar(255) NOT NULL,
		transaction_items varchar(255) NOT NULL,
		transaction_status varchar(25) NOT NULL,
		source_id varchar(255) NOT NULL,
		source_status varchar(25) NOT NULL,
		charge_id varchar(255) NOT NULL,
		charge_status varchar(25) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	add_option('chawa_db_version', $chawa_db_version);
}

// load first database table row
function chawa_install_data() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'chawa_transactions';
	
	$wpdb->insert(
		$table_name, 
		array(
			'time' => current_time('mysql'),
			'user_id' => 0,
			'amount' => 0,
			'recurring' => FALSE,
			'transaction_id' => 'chawa_000000000000000000000000',
			'transaction_type' => 'DEBIT',
			'transaction_items' => '',
			'transaction_status' => 'status',
			'source_id' => 'src_000000000000000000000000',
			'source_status' => 'status',
			'charge_id' => 'py_000000000000000000000000',
			'charge_status' => 'status'
		) 
	);
}

// update database tables
global $wpdb;
$installed_ver = get_option('chawa_db_version');

if ($installed_ver != $chawa_db_version) {

	$table_name = $wpdb->prefix . 'chawa_transactions';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		user_id mediumint(9) NOT NULL,
		amount varchar(10) NOT NULL,
		recurring BOOLEAN,
		transaction_id varchar(255) NOT NULL,
		transaction_type varchar(255) NOT NULL,
		transaction_items varchar(255) NOT NULL,
		transaction_status varchar(25) NOT NULL,
		source_id varchar(255) NOT NULL,
		source_status varchar(25) NOT NULL,
		charge_id varchar(255) NOT NULL,
		charge_status varchar(25) NOT NULL,
		PRIMARY KEY (id)
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	update_option('chawa_db_version', $chawa_db_version);
}