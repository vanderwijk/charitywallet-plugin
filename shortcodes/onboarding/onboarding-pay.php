<?php // make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}

$user_id = get_current_user_id();
$user_wallet = get_user_meta($user_id, 'wallet', true);
$recurring = $user_wallet[0]['recurring'];
$amount = $user_wallet[0]['amount'];

$stripe_amount = number_format($amount, 0, '', '') * 100; // Stripe requires an amount in cents
$stripe_amount = $stripe_amount + 44; // Fixed transaction costs

$hostname = $_SERVER['HTTP_HOST'];
$path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

require_once CHAWA_PLUGIN_DIR_PATH . 'initialize-stripe.php';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

	if ( !isset( $_POST['pay_nonce'] ) || !wp_verify_nonce( $_POST['pay_nonce'], 'pay' )) {
		print __('Sorry, your nonce did not verify.', 'chawa');
		exit;
	} else {

		$transaction_id = 'chawa_' . bin2hex(random_bytes(10)); // unique transaction ID

		if ( $recurring === 'true' ) {
			// Subscription

			$customer = $stripe->customers->page(null, 1)[0];

			$mandate = $customer->createMandate([
				'method' => \Stripe\Api\Types\MandateMethod::DIRECTDEBIT,
				'consumerAccount' => 'NL34ABNA0243341423',
				'consumerName' => 'B. A. Example',
			]);

			echo '<p>Mandate created with id ' . $mandate->id . ' for customer ' . $customer->name . '</p>';
			
			/*
			$customer = $stripe->customers->create([
				'name' => 'Customer A',
				'email' => 'customer@example.org',
			]);

			$customer = $stripe->customers->get('cst_NQ4knepKKb');
			$customer->createSubscription([
			'amount' => [
					'currency' => 'EUR',
					'value' => $stripe_amount,
			],
			'interval' => '1 months',
			'description' => 'Maandelijkse opwaardering wallet',
			'webhookUrl' => 'https://{$hostname}{$path}/subscriptions/webhook.php',
			]);
			*/
			
		} else {
			// Create Stripe source for one-off iDEAL payment

			// Save transaction_id to database
			$wpdb->insert(
				CHAWA_TABLE_TRANSACTIONS,
				array(
					'time' => current_time('mysql'),
					'user_id' => sanitize_key($user_id),
					'amount' => sanitize_key($stripe_amount),
					'recurring' => FALSE,
					'transaction_type' => 'iDEAL',
					'transaction_id' => sanitize_key($transaction_id),
				)
			);

			$source = \Stripe\Source::create([
				'type' => 'ideal',
				'amount' => $stripe_amount,
				'currency' => 'eur',
				'ideal' => [
					'bank' => $_POST['bank']
				],
				'statement_descriptor' => __('Wallet top-up transaction', 'chawa') . ' ' . $transaction_id,
				'owner' => [
					'name' => 'CharityWallet',
					'email' => 'info@charitwallet.com'
				],
				'metadata' => [
					'transaction_id' => $transaction_id,
					'user_id' => $user_id
				],
				'redirect' => [
					'return_url' => 'https://' . $hostname . $path . '/pay/charge/'
				]
			]);

			if ($source['status'] === 'pending') {
				header('Location: ' . $source['redirect']['url']);
			} else {
				_e('Something went wrong, please try again later','chawa');
			}
		}
	}
} ?>

<?php get_header();
wp_enqueue_script( 'onboarding_pay' );
wp_localize_script( 'onboarding_pay', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url()), 'nonce' => wp_create_nonce( 'wp_rest' ), 'title' => ( current_time( 'H:i:s' )) ));
?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<div class="step" id="step-6">
				<h1><?php _e('Top-up wallet', 'chawa'); ?></h1>
				<p><strong><?php _e('We are now going to top-up your wallet', 'chawa'); ?>.</strong></p>
				<p><button class="button next" id="next-7"><?php _e('Next', 'chawa'); ?></button></p>
			</div>

			<div class="step" id="step-7" style="display: none;">
				<h1><?php _e('Top-up wallet', 'chawa'); ?></h1>
				<div class="notice" id="notice"></div>
				
				<form method="post" autocomplete="off" class="pay" id="pay" onSubmit="pay(<?php echo get_current_user_id(); ?>)">
					<p>
						<strong>
							<?php _e('You chose to', 'chawa');
							if ( $recurring === 'true' ) { 
								echo ' ' . _x('monthly add', 'comes after \'You chose to\'', 'chawa');
							} else {
								echo ' ' . _x('add', 'comes after \'You chose to\'', 'chawa');
							}
							echo ' € ' . $amount . ',00 ';
							printf(
								'(<a href="%s">%s</a>)',
								'/onboarding/wallet/',
								__( 'change', 'chawa' )
							);
							echo ' ' . __('to your wallet', 'chawa') . '.'; ?>
						</strong>
					</p>
					<span class="bank">
						<p><strong><?php _e('Choose your bank', 'chawa'); ?></strong></p>
						<p>
							<select name="bank">
								<option value=""><?php _e('Bank', 'chawa'); ?></option>
								<option value="abn_amro">ABN AMRO</option>
								<option value="asn_bank">ASN Bank</option>
								<option value="bunq">Bunq</option>
								<option value="handelsbanken">Handelsbanken</option>
								<option value="ing">ING</option>
								<option value="knab">Knab</option>
								<option value="moneyou">Moneyou</option>
								<option value="rabobank">Rabobank</option>
								<option value="regiobank">RegioBank</option>
								<option value="sns_bank">SNS Bank (De Volksbank)</option>
								<option value="triodos_bank">Triodos Bank</option>
								<option value="van_lanschot">Van Lanschot</option>
							</select>
						</p>
					</span>
					<p>
						<label><input type="checkbox" name="accept" value="accept">
							<?php _e('I agree with the', 'chawa');
							printf(
								' <a href="%s">%s</a> ',
								stripslashes('\/terms\/'),
								__( 'terms and conditions', 'chawa' )
							);
							_e('and the', 'chawa' );
							printf(
								' <a href="%s">%s</a> ',
								stripslashes('\/privacy\/'),
								__( 'privacy statement', 'chawa' )
							); ?>
						</label>
					</p>
					<p><small><?php _e('For each transaction we charge € 0.44 transaction costs on top of the donation money.', 'chawa'); ?></small></p>
					<p><input type="submit" id="submit" class="next" id="next-4" value="<?php _e('Agree & pay', 'chawa'); ?>"></p>
					<?php wp_nonce_field( 'pay', 'pay_nonce' ); ?>
				</form>
			</div>

		</div>
	</main>
</section>

<?php get_footer(); ?>