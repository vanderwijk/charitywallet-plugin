<?php 
// Make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}

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

} ?>

<?php get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

<style>

</style>

<div class="step" id="step-8">
	<h1><?php _e('Pay Charge', 'chawa'); ?></h1>
	<div class="notice" id="notice">
		<?php _e('Payment', 'chawa');
		echo ' ';
		if ( $charge_status === 'succeeded' ) {
			_e('succeeded', 'chawa');
		}
		if ( $charge_status === 'failed' ) {
			_e('failed', 'chawa');
		}
		if ( $charge_status === 'expired' ) {
			_e('expired', 'chawa');
		} ?>
	</div>
</div>

		</div>
	</main>
</section>

<?php get_footer(); ?>