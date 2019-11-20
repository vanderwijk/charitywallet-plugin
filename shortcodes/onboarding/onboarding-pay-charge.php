<?php 
// make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}

if (!empty($_GET['source'])) {
	// stripe source exists, retrieve and save to database
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

	// move this to webhook?
	/*
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

	echo '<pre>';
	echo $charge;
	echo '</pre>';

	$charge_status = $charge['status']; */
} ?>

<?php get_header(); ?>

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