<?php 

// Donation basket functionality (Add/Remove/Process items from basket)

function chawa_add_to_basket() {

	do_action('start_shortcode_donate'); 

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

			ob_start(); ?>

			<div class="basket">

				<style>
					ul.charity-list,
					ul.charity-basket {
						padding: 0;
					}
					.charity-list li,
					.charity-basket li {
						display: flex;
						justify-content: space-between;
						width: 100%;
					}
					.remove {
						background-color: #FE6C61;
					}
				</style>

				<h2><?php _e('Charities', 'chawa'); ?></h2>

				<ul class="charity-list" id="charity-list">
					<li>Greenpeace Nederland <button class="donate" data-charity="159" data-amount="40" data-name="Greenpeace Nederland"><?php _e('Add'); ?></button></li>
					<li>Wereld Natuurfonds - Nederland<button class="donate" data-charity="271" data-amount="45" data-name="Wereld Natuurfonds - Nederland"><?php _e('Add'); ?></button></li>
					<li>Amnesty International <button class="donate" data-charity="107" data-amount="50" data-name="Amnesty International"><?php _e('Add'); ?></button></li>
				</ul>

				<h2><?php _e('Basket', 'chawa'); ?></h2>

				<ul class="charity-basket" id="charity-basket">
				</ul>

			</div>

			<?php return ob_get_clean(); // use return if shortcode

		} else { // not logged in
			ob_start(); ?>
		
			<p class="warning">
				<?php _e('You must be'); ?>
				<a href="<?php echo wp_login_url( get_permalink() ); ?>" title="<?php _e('Log in'); ?>"><?php _e('logged in','chawa'); ?></a> 
				<?php _e('to access your basket.', 'chawa'); ?>
			</p>
	
			<?php return ob_get_clean();
		}

}
add_shortcode( 'donate', 'chawa_add_to_basket' );