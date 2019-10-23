<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$user_id = get_current_user_id();
	$user_wallet = get_user_meta($user_id, 'wallet', true);
	$recurring = $user_wallet[0]['recurring'];
	$amount = $user_wallet[0]['amount'];
	
	$orderId = time();

	$protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
	$hostname = $_SERVER['HTTP_HOST'];
	$path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

	$amount = number_format($amount, 2, '.', ' '); // Add two decimals
	$amount = $amount + 0.44; // Fixed transaction costs
	settype($amount, "string"); // Convert to string for Mollie API

	/*
	* Payment parameters:
	*   amount        Amount in EUROs. This example creates a € 27.50 payment.
	*   method        Payment method "ideal".
	*   description   Description of the payment.
	*   redirectUrl   Redirect location. The customer will be redirected there after the payment.
	*   webhookUrl    Webhook location, used to report when the payment changes state.
	*   metadata      Custom metadata that is stored with the payment.
	*   issuer        The customer's bank. If empty the customer can select it later.
	*/
	$payment = $mollie->payments->create([
		"amount" => [
			"currency" => "EUR",
			"value" => $amount // You must send the correct number of decimals, thus we enforce the use of strings
		],
		"method" => \Mollie\Api\Types\PaymentMethod::IDEAL,
		"description" => 'Wallet ' . __('Transaction','chawa') . ' #{$orderId}',
		"redirectUrl" => "{$protocol}://{$hostname}{$path}/payments/return.php?order_id={$orderId}",
		"webhookUrl" => "{$protocol}://{$hostname}{$path}/payments/webhook.php",
		"metadata" => [
			"order_id" => $orderId,
		],
		"issuer" => !empty($_POST["issuer"]) ? $_POST["issuer"] : null
	]);
	/*
	* In this example we store the order with its payment status in a database.
	*/
	//save_transaction($orderId, $payment->status);
	/*
	* Send the customer off to complete the payment.
	* This request should always be a GET, thus we enforce 303 http response code
	*/
	header("Location: " . $payment->getCheckoutUrl(), true, 303);
} ?>

<?php get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

<?php // BEGIN Include ?>

<?php require_once CHAWA_PLUGIN_DIR_PATH . 'initialize-mollie.php'; ?>

<?php 
	wp_enqueue_script( 'onboarding_pay' );
	wp_localize_script( 'onboarding_pay', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'title' => ( current_time( 'H:i:s' ) ) ) );
?>

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

<?php 
	$user_id = get_current_user_id();
	$user_wallet = get_user_meta($user_id, 'wallet', true);
	$recurring = $user_wallet[0]['recurring'];
	$amount = $user_wallet[0]['amount'];
?>

<div class="step" id="step-6">
	<h1><?php _e('Wallet opwaarderen', 'chawa'); ?></h1>
	<p><strong><?php _e('We gaan nu je wallet opwaarderen.', 'chawa'); ?></strong></p>
	<p><button class="button next" id="next-7"><?php _e('Next', 'chawa'); ?></button></p>
</div>

<div class="step" id="step-7" style="display: none;">
	<h1><?php _e('Wallet opwaarderen', 'chawa'); ?></h1>
	<div class="notice" id="notice"></div>
	
<?php try {
	require_once CHAWA_PLUGIN_DIR_PATH . 'initialize-mollie.php';
	ob_start(); ?>

	<form method="post" class="pay" id="pay" enctype="multipart/form-data" action="" onSubmit="pay(<?php echo get_current_user_id(); ?>)">
		<p><strong><?php _e('Je wilt', 'chawa'); ?> 
		<?php if ( $recurring === 'true' ) {
			echo 'maandelijks';
		} 
		echo ' € ' . $amount . ',00'; ?> 
		
		<?php _e('(<a href="/onboarding/wallet/">wijzig</a>) donatiegeld storten op je wallet.', 'chawa'); ?></strong></p>
		<p><strong><?php _e('Hoe wil je betalen?', 'chawa'); ?></strong></p>
		<p>
			<label><input type="radio" name="payment-type" value="ideal">iDEAL</label>
			<label><input type="radio" name="payment-type" value="creditcard">Creditcard</label>
		</p>
		<span class="bank">
			<p><strong><?php _e('Kies je bank', 'chawa'); ?></strong></p>
			<p>
			<?php
				$method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);
				echo '<select name="issuer" id="issuer">';
				echo '<option value="">' . __('Choose your bank','chawa') . '</option>';
				foreach ($method->issuers() as $issuer) {
					echo '<option value="' . htmlspecialchars($issuer->id) . '">' . htmlspecialchars($issuer->name) . '</option>';
				}
				echo '</select>';
			?>
			</p>
		</span>
		<p>
			<label><input type="checkbox" name="accept" value="accept"><?php _e('Ik ga akkoord met de <a href="">algemene voorwaarden</a> en het <a href="">privacy statement</a>.', 'chawa'); ?></label>
		</p>
		<p><small><?php _e('Per transatie rekenen we € 0,44 transactiekosten bovenop het donatiegeld.', 'chawa'); ?></small></p>
		<p><input type="submit" id="submit" class="next" id="next-4" value="<?php _e('Akkoord & betalen', 'chawa'); ?>"></p>
	</form>

	<?php echo ob_get_clean(); // use return if shortcode
	} catch (\Mollie\Api\Exceptions\ApiException $e) {
		echo "API call failed: " . htmlspecialchars($e->getMessage());
	} ?>
</div>

<?php // END Include ?>

		</div>
	</main>
</section>

<?php get_footer(); ?>