<?php

global $chawa_table_ver_transactions;
$chawa_table_ver_transactions = CHAWA_DATABASE_VER;

// create database table for chawa_transactions
function chawa_db_transactions_install() {
	global $wpdb;
	global $chawa_table_ver_transactions;

	$table_name = $wpdb->prefix . 'chawa_transactions';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		transaction_id varchar(50) NOT NULL,
		transaction_type varchar(50) NOT NULL,
		transaction_status varchar(50) NOT NULL,
		source_id varchar(50) NOT NULL,
		source_status varchar(50) NOT NULL,
		charge_id varchar(50) NOT NULL,
		charge_status varchar(50) NOT NULL,
		user_id mediumint(9) NOT NULL,
		amount varchar(50) NOT NULL,
		recurring BOOLEAN,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (transaction_id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	add_option('chawa_table_ver_transactions', $chawa_table_ver_transactions);
}

// load first database table row
function chawa_db_transactions_data() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'chawa_transactions';
	
	$wpdb->insert(
		$table_name, 
		array(
			'transaction_id' => 'chawa_000000000000000000000000',
			'transaction_type' => 'DEBIT',
			'transaction_status' => 'status',
			'source_id' => 'src_000000000000000000000000',
			'source_status' => 'status',
			'charge_id' => 'py_000000000000000000000000',
			'charge_status' => 'status',
			'user_id' => 0,
			'amount' => 0,
			'recurring' => FALSE,
			'time' => current_time('mysql')
		) 
	);
}

// update database tables
global $wpdb;
$installed_ver = get_option('chawa_table_ver_transactions');

if ($installed_ver != $chawa_table_ver_transactions) {

	$table_name = $wpdb->prefix . 'chawa_transactions';

	$sql = "CREATE TABLE $table_name (
		transaction_id varchar(50) NOT NULL,
		transaction_type varchar(50) NOT NULL,
		transaction_status varchar(50) NOT NULL,
		source_id varchar(50) NOT NULL,
		source_status varchar(50) NOT NULL,
		charge_id varchar(50) NOT NULL,
		charge_status varchar(50) NOT NULL,
		user_id mediumint(9) NOT NULL,
		amount varchar(50) NOT NULL,
		recurring BOOLEAN,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	update_option('chawa_table_ver_transactions', $chawa_table_ver_transactions);
}