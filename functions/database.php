<?php

global $cha_db_version;
$cha_db_version = '1.0';

// create database table for cha_sources
function cha_install() {
	global $wpdb;
	global $cha_db_version;

	$table_name = $wpdb->prefix . 'cha_sources';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        amount varchar(10) NOT NULL,
		source varchar(255) NOT NULL,
        status varchar(25) NOT NULL,
		description text NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'cha_db_version', $cha_db_version );
}

// load first database table row
function cha_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'cha_sources';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'source' => $welcome_name, 
			'description' => $welcome_text, 
		) 
	);
}


// update database tables
global $wpdb;
$installed_ver = get_option( "cha_db_version" );

if ( $installed_ver != $cha_db_version ) {

	$table_name = $wpdb->prefix . 'cha_sources';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		text text NOT NULL,
		url varchar(100) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	update_option( "cha_db_version", $cha_db_version );
}