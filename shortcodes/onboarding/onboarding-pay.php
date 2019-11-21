<?php 
// Make sure user is logged in
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

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

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
			// Create Stripe source for one-off payment

			// Save transaction_id to database
			$wpdb->insert(
				CHAWA_TABLE_TRANSACTIONS,
				array(
					'time' => current_time('mysql'),
					'user_id' => sanitize_key($user_id),
					'amount' => sanitize_key($stripe_amount),
					'recurring' => FALSE,
					'transaction_type' => 'CREDIT',
					'transaction_id' => sanitize_key($transaction_id),
					'status' => 'initiated'
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

			<style>
				.entry-title { display: none; }
				.col-1,
				.col-2 {
					margin-bottom: 3%;
				}
				.col-2 {
					display: flex;
					justify-content: space-between;
				}
				.col-2 .col {
					width: 48%;
				}
				input[type="text"].parsley-error {
					border-color: #FE6C61;
				}
				ul.parsley-errors-list {
					list-style: none;
					margin: 0;
					color: #FE6C61;
					padding: 0;
				}
				.pay input[type="radio"],
				.pay input[type="checkbox"] {
					margin-right: 5px;
				}
				ul.parsley-errors-list li {
					margin-bottom: -10px;
				}
				#pay label {
					display: inline;
					margin-right: 5px;
				}
				.error {
					border: 1px solid #FE6C61;
					display: block;
					margin-bottom: 3%;
					padding: 1% 2%;
					border-radius: 3px;
					background-color: rgba(254,108,97,0.8);
					color: #fff;
					font-weight: 600;
				}
			</style>

			<div class="step" id="step-6">
				<h1><?php _e('Wallet opwaarderen', 'chawa'); ?></h1>
				<p><strong><?php _e('We gaan nu je wallet opwaarderen.', 'chawa'); ?></strong></p>
				<p><button class="button next" id="next-7"><?php _e('Next', 'chawa'); ?></button></p>
			</div>

			<div class="step" id="step-7" style="display: none;">
				<h1><?php _e('Wallet opwaarderen', 'chawa'); ?></h1>
				<div class="notice" id="notice"></div>
				
				<form method="post" class="pay" id="pay" onSubmit="pay(<?php echo get_current_user_id(); ?>)">
					<p>
						<strong>
							<?php _e('Je wilt', 'chawa');
							if ( $recurring === 'true' ) { echo 'maandelijks'; } 
							echo ' € ' . $amount . ',00' . __('(<a href="/onboarding/wallet/">wijzig</a>) donatiegeld storten op je wallet.', 'chawa'); ?>
						</strong>
					</p>
					<span class="bank">
						<p><strong><?php _e('Kies je bank', 'chawa'); ?></strong></p>
						<p>
							<select name="bank">
								<option value=""><?php _e('Kies je bank', 'chawa'); ?></option>
								<option value="abn_amro">ABN AMRO</option>
								<option value="asn_bank">ASN Bank</option>
							</select>
						</p>
					</span>
					<p>
						<label><input type="checkbox" name="accept" value="accept"><?php _e('Ik ga akkoord met de <a href="">algemene voorwaarden</a> en het <a href="">privacy statement</a>.', 'chawa'); ?></label>
					</p>
					<p><small><?php _e('Per transatie rekenen we € 0,44 transactiekosten bovenop het donatiegeld.', 'chawa'); ?></small></p>
					<p><input type="submit" id="submit" class="next" id="next-4" value="<?php _e('Akkoord & betalen', 'chawa'); ?>"></p>
					<?php wp_nonce_field( 'pay', 'pay_nonce' ); ?>
				</form>
			</div>

		</div>
	</main>
</section>

<?php get_footer(); ?>