<?php 

require 'vendor/autoload.php';

function chawa_display_wallet() {

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();
			ob_start(); ?>

			<link rel="stylesheet" href="/wp-content/plugins/charitywallet-plugin/style.css" />
			
			<div class="top-up-modal">
				<button type="button" aria-label="<?php _e('Close','chawa'); ?>" class="close-modal">
					<svg viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
						<line x1="1" y1="11" x2="11" y2="1" stroke="black" stroke-width="2"/>
						<line x1="1" y1="1" x2="11" y2="11" stroke="black" stroke-width="2"/>
					</svg>
				</button>
				<header><h1><?php _e('Top up your wallet','chawa'); ?></h1></header>
				<section>
					<p><?php _e('Choose your monthly amount','chawa'); ?>:</p>
					<form novalidate="novalidate" class="top-up-form">
						<ul class="choose-amount">
							<li>
								<input id="top-up-1000" type="radio" name="top-up-amount" value="1000">
								<label for="top-up-1000">&euro; 10</label>
							</li>
							<li>
								<input id="top-up-2000" type="radio" name="top-up-amount" value="2000">
								<label for="top-up-2000">&euro; 20</label>
							</li>
							<li>
								<input id="top-up-5000" type="radio" name="top-up-amount" value="5000">
								<label for="top-up-5000">&euro; 50</label>
							</li>
						</ul>
						<label for="other-amount"><?php _e('Other amount','chawa'); ?></label>
						<input type="number" id="other-amount" placeholder="<?php _e('Type your amount','chawa'); ?>">
						<button type="submit" class="button-primary">
							<?php _e('Payment','chawa'); ?>
						</button>
					</form>
				</section>
			</div>
			
			<?php return ob_get_clean();
		} else { // not logged in
			ob_start(); ?>
		
			<p class="warning">
				<?php _e('You must be'); ?>
				<a href="<?php echo wp_login_url( get_permalink() ); ?>" title="<?php _e('Log in'); ?>"><?php _e('logged in','chawa'); ?></a> 
				<?php _e('to access your wallet.', 'chawa'); ?>
			</p>
	
			<?php return ob_get_clean();
		}

}
add_shortcode( 'wallet', 'chawa_display_wallet' );