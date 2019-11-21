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

echo '<pre>';
print_r($paymentIntent);
echo '</pre>';

function chargeSucceeded($paymentIntent) {

	$charge = \Stripe\Charge::create([
		'amount' => $paymentIntent['amount'],
		'currency' => 'eur',
		'source' => $paymentIntent['source']['id'],
		'metadata' => [
			'transaction_id' => $paymentIntent['metadata']['transaction_id'],
			'user_id' => $paymentIntent['metadata']['user_id']
		],
	]);

	$wpdb->update( 
		CHAWA_TABLE_TRANSACTIONS, 
		array(
			'charge_id' => $charge['id'],
			'status' => $charge['status']
		),
		['transaction_id' => $paymentIntent['metadata']['transaction_id']]
	);

	status_header(200);
	return 'Webhook connected';

}