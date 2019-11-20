<?php

$body = @file_get_contents('php://input');
$event_json = json_decode($body);

echo '<pre>';
print_r($event_json);
echo '</pre>';

if (!empty($_GET['source'])) {

	require_once CHAWA_PLUGIN_DIR_PATH . 'initialize-stripe.php';

	$source = \Stripe\Source::retrieve(
		$_GET['source']
	);

	$charge = \Stripe\Charge::create([
		'amount' => $source['amount'],
		'currency' => 'eur',
		'source' => $_GET['source'],
		'metadata' => [
			'transaction_id' => $source['metadata']['transaction_id'],
			'user_id' => $source['metadata']['user_id']
		],
	]);

	$wpdb->update( 
		CHAWA_TABLE_TRANSACTIONS, 
		array(
			'charge_id' => $charge['id'],
			'status' => $charge['status']
		),
		['transaction_id' => $source['metadata']['transaction_id']]
	);

	status_header(200);
	return 'Webhook connected';

}