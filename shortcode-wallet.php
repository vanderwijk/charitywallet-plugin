<?php 
function chawa_display_wallet() {

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

			return '<p>' . __('Your wallet') . '</p>';

		} else { // not logged in
			ob_start(); ?>
		
			<p class="warning">
				<?php __('You must be'); ?>
				<a href="<?php wp_login_url( get_permalink() ); ?>" title="Log in">logged in</a> 
				<?php __('to access your wallet.', 'chawa'); ?>
			</p>
	
			<?php return ob_get_clean();
		}

}
add_shortcode( 'wallet', 'chawa_display_wallet' );