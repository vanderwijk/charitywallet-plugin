<?php 
// Make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
} ?>

<?php get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<?php // BEGIN Include ?>

			<?php 
				wp_enqueue_script( 'onboarding_wallet' );
				wp_localize_script( 'onboarding_wallet', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'title' => ( current_time( 'H:i:s' ) ) ) );
			?>

			<?php 
				$user_id = get_current_user_id();
				$user_wallet = get_user_meta($user_id, 'wallet', true);
				if ( $user_wallet ) {
					$recurring = $user_wallet[0]['recurring'];
					$amount = $user_wallet[0]['amount'];
				}
			?>

			<div class="step" id="step-4">
				<h1><?php _e('Activate wallet', 'chawa'); ?></h1>
				<p><?php _e('To be able to donate via CharityWallet, we will first make a digital wallet (your Wallet) for you to which you can transfer a one-off or monthly donation.', 'chawa'); ?></p>
				<p><strong><?php _e('We are now going to activate your wallet.', 'chawa'); ?></strong></p>
				<p><button class="button next" id="next-5"><?php _e('Next', 'chawa'); ?></button></p>
			</div>

			<div class="step" id="step-5" style="display: none;">
				<h1><?php _e('Choose top-up amount', 'chawa'); ?></h1>
				<div class="notice" id="notice"></div>
				<form method="post" autocomplete="off" class="update-user" id="update-user" enctype="multipart/form-data" action="" onSubmit="updateUser(<?php echo get_current_user_id(); ?>)">
					<p><strong><?php _e('Would you like to top-up your wallet montly or only one time?', 'chawa'); ?></strong></p>
					<p>
						<label><input type="radio" name="recurring" value="false" <?php if ($recurring === 'false') { echo 'checked'; } ?>><?php _e('once', 'chawa'); ?></label>
						<label><input type="radio" name="recurring" value="true" <?php if ($recurring === 'true') { echo 'checked'; } ?>><?php _e('monthly', 'chawa'); ?></label>
					</p>
					<p><strong><?php _e('Which amount would you like to transfer to your wallet?', 'chawa'); ?></strong></p>
					<p>
						<label><input type="radio" name="amount" value="10" <?php if ($amount === '10') { echo 'checked'; } ?>>€ 10,00</label>
						<label><input type="radio" name="amount" value="20" <?php if ($amount === '20') { echo 'checked'; } ?>>€ 20,00</label>
						<label><input type="radio" name="amount" value="50" <?php if ($amount === '50') { echo 'checked'; } ?>>€ 50,00</label>
						<input type="number" name="amountCustom" placeholder="<?php _e('Choose another amount', 'chawa'); ?>" <?php if ( !in_array( $amount, array(10,20,50))) { echo ' value="' . $amount . '"'; } ?>>
					</p>
					<p><small><?php _e('Whatever you choose, you can always adjust it later.', 'chawa'); ?></small></p>
					<p><input type="submit" id="submit" class="next" id="next-4" value="<?php _e('Next', 'chawa'); ?>"></p>
				</form>
			</div>

			<?php // END Include ?>

		</div>
	</main>
</section>

<?php get_footer(); ?>