<?php

// more information on using monthly balances for audits:
// https://stackoverflow.com/questions/29688982/derived-account-balance-vs-stored-account-balance-for-a-simple-bank-account

function get_wallet_balance( $user_id ) {
	global $wpdb;
	$user_id = get_current_user_id();

	$credit = $wpdb->get_results(
		'SELECT sum(amount) 
		AS credit 
		FROM ' . CHAWA_TABLE_TRANSACTIONS . ' 
		WHERE user_id = ' . $user_id . ' 
		AND transaction_type IN ("iDEAL")
		AND charge_status IN ("succeeded")'
	);

	$debit = $wpdb->get_results(
		'SELECT sum(amount) 
		AS debit 
		FROM ' . CHAWA_TABLE_TRANSACTIONS . ' 
		WHERE user_id = ' . $user_id . ' 
		AND transaction_type IN ("Donation", "Transaction costs")
		AND transaction_status IN ("succeeded")'
	);

	$wallet_balance = ( $credit[0]->credit ) - ( $debit[0]->debit );
	return number_format_i18n($wallet_balance/100, 2);
}