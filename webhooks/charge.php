<?php

require_once CHAWA_PLUGIN_DIR_PATH . 'initialize-stripe.php';

$endpoint_secret = 'whsec_Osmz6cCN3tAXq54kmcdfn2kq2L2jVWBP';

$payload = @file_get_contents('php://input');

$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
	$event = \Stripe\Webhook::constructEvent(
		$payload, $sig_header, $endpoint_secret
	);
} catch(\UnexpectedValueException $e) {
	// Invalid payload
	http_response_code(400);
	exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
	// Invalid signature
	http_response_code(400);
	exit();
}

// Handle the event
switch ($event->type) {
	case 'charge.pending':
		$paymentIntent = $event->data->object;
		chargePending($paymentIntent);
		break;
	case 'charge.failed':
		$paymentIntent = $event->data->object;
		chargeFailed($paymentIntent);
		break;
	case 'charge.succeeded':
		$paymentIntent = $event->data->object; // contains a StripePaymentIntent
		chargeSucceeded($paymentIntent);
		break;
	default:
		// Unexpected event type
		http_response_code(400);
		exit();
}

http_response_code(200);

function chargePending($paymentIntent) {

	echo '<pre>';
	print_r($paymentIntent);
	echo '</pre>';

	$charge = \Stripe\Charge::create([
		'amount' => $paymentIntent['amount'],
		'currency' => 'eur',
		'source' => $paymentIntent['source']['id'],
		'metadata' => [
			'transaction_id' => $paymentIntent['metadata']['transaction_id'],
			'user_id' => $paymentIntent['metadata']['user_id']
		],
	]);
	global $wpdb;
	$wpdb->update( 
		CHAWA_TABLE_TRANSACTIONS, 
		array(
			'charge_id' => $charge['id'],
			'charge_status' => $charge['status']
		),
		['transaction_id' => $paymentIntent['metadata']['transaction_id']]
	);

	status_header(200);
}

function chargeFailed($paymentIntent) {
	global $wpdb;
	$wpdb->update( 
		CHAWA_TABLE_TRANSACTIONS, 
		array(
			'charge_id' => $paymentIntent['id'],
			'charge_status' => $paymentIntent['status']
		),
		['transaction_id' => $paymentIntent['metadata']['transaction_id']]
	);
	status_header(200);
}

function chargeSucceeded($paymentIntent) {
	global $wpdb;
	$wpdb->update( 
		CHAWA_TABLE_TRANSACTIONS, 
		array(
			'charge_id' => $paymentIntent['id'],
			'charge_status' => $paymentIntent['status'],
			'transaction_status' => 'succeeded'
		),
		['transaction_id' => $paymentIntent['metadata']['transaction_id']]
	);

	// Saving transaction for costs to database (does this need to be done earlier?)
	$transaction_id_costs = 'chawa_' . bin2hex(random_bytes(10)); // unique transaction ID for costs

	$wpdb->insert(
		CHAWA_TABLE_TRANSACTIONS,
		array(
			'time' => current_time('mysql'),
			'user_id' => $paymentIntent['metadata']['user_id'],
			'amount' => 44,
			'recurring' => FALSE,
			'transaction_id' => sanitize_key($transaction_id_costs),
			'transaction_type' => 'Transaction costs',
			'transaction_status' => 'succeeded',
			'charge_id' => $paymentIntent['id']
		)
	);

	status_header(200);
}