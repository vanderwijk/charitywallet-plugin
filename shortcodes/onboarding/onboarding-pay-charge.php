<?php 
// make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}

if (!empty($_GET['source'])) {
	// stripe source exists, retrieve and save to database with the status
	require_once CHAWA_PLUGIN_DIR_PATH . 'initialize-stripe.php';

	$source = \Stripe\Source::retrieve(
		$_GET['source']
	);

	$transaction_id = $source['metadata']['transaction_id'];
	$user_id = $source['metadata']['user_id'];

	$wpdb->update( 
		CHAWA_TABLE_TRANSACTIONS, 
		array(
			'source_id' => sanitize_key($source['id']),
			'status' => sanitize_key($source['status'])
		),
		['transaction_id' => $transaction_id]
	);

	// if the source is chargable, make the charge and save it to the db
	if ($source['status'] === 'chargeable') { 
		$charge = \Stripe\Charge::create([
			'amount' => $source['amount'],
			'currency' => 'eur',
			'source' => $_GET['source'],
			'metadata' => [
				'transaction_id' => sanitize_key($transaction_id),
				'user_id' => sanitize_key($user_id)
			],
		]);

		$wpdb->update( 
			CHAWA_TABLE_TRANSACTIONS, 
			array(
				'charge_id' => $charge['id'],
				'status' => $charge['status']
			),
			['transaction_id' => sanitize_key($transaction_id)]
		);

	}

	// on fail there is no charge, so add a check for that TODO!
	echo '<pre>';
	echo $charge;
	echo '</pre>';

	// get the status of the charge and display the appropriate message -> THIS DOES NOT WORK ON FAIL
	$charge_status = $charge['status'];
}

get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<div class="step" id="step-8">
				<h1><?php _e('Pay Charge', 'chawa'); ?></h1>
				<div class="notice" id="notice">
					<?php _e('Payment', 'chawa');
					echo ' ';
					if (isset($charge_status)) {
						if ($charge_status === 'succeeded') {
							_e('succeeded', 'chawa');
						}
						if ($charge_status === 'failed') {
							_e('failed', 'chawa');
						}
						if ($charge_status === 'expired') {
							_e('expired', 'chawa');
						}
					} ?>
				</div>
			</div>

		</div>
	</main>
</section>

<?php get_footer(); ?>