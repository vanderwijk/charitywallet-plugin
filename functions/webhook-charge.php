<?php

if (!empty($_GET['source'])) {

	require_once CHAWA_PLUGIN_DIR_PATH . 'initialize-stripe.php';

	$charge = \Stripe\Charge::create([
		'amount' => 1344,
		'currency' => 'eur',
		'source' => $_GET['source'],
	]);

	echo '<pre>';
	echo $charge;
	echo '</pre>';

	$charge_status = $charge['status'];

	$user_id = get_current_user_id();
	$user_wallet = get_user_meta($user_id, 'wallet', true);
	$recurring = $user_wallet[0]['recurring'];
	$amount = $user_wallet[0]['amount'];

	$stripe_amount = number_format($amount, 0, '', '') * 100; // Stripe requires an amount in cents
	$stripe_amount = $stripe_amount + 44; // Fixed transaction costs

	$hostname = $_SERVER['HTTP_HOST'];
    $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);
    
    status_header(200);
    return 'Webhook connected';

}