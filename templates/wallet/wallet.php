<?php // make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}
get_header(); ?>

<?php 
	$user_id = get_current_user_id();
	$user_wallet = get_user_meta($user_id, 'wallet', true);
	if ( $user_wallet ) {
		$recurring = $user_wallet[0]['recurring'];
		$amount = $user_wallet[0]['amount'];
	}
?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<h1><?php _e('Wallet', 'chawa'); ?></h1>

			<?php if ( $user_wallet ) {

				echo '<p>' . __('Your Wallet Balance is', 'chawa') . ' €' . number_format_i18n( $wallet_balance / 100, 2 ) . '</p>'; 

				echo '<p>' . __('You chose to', 'chawa');
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
				</p>

			<?php } ?>

			<p><a href="/wallet/<?php echo _x('transactions','rewrite rule','chawa'); ?>"><?php _e('View your wallet transactions.', 'chawa'); ?></a></p>

		</div>
	</main>
</section>

<?php get_footer(); ?>