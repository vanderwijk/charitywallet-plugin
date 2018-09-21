<?php 
function chawa_display_wallet() {

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

			return '<p>' . __('Your wallet') . '</p>';

		} else { // not logged in
			ob_start(); ?>
		
			<p class="warning">
				<?php _e('You must be'); ?>
				<a href="<?php echo wp_login_url( get_permalink() ); ?>" title="<?php _e('Log in'); ?>"><?php _e('logged in'); ?></a> 
				<?php _e('to access your wallet.', 'chawa'); ?>
			</p>
	
			<?php return ob_get_clean();
		}

}
add_shortcode( 'wallet', 'chawa_display_wallet' );