<?php 

function chawa_display_basket() {

	do_action('start_shortcode_basket'); 

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

			ob_start(); ?>

			<div class="basket">
				<h2><?php _e('Charities'); ?></h2>
				
				<style>
					.charity-list li {
						display: flex;
						justify-content: space-between;
						width: 100%;
					}
				</style>

				<ul class="charity-list">
					<li>Greenpeace Nederland <button class="donate" data-charity="159"><?php _e('Donate'); ?></button></li>
				</ul>

				<h2><?php _e('Your donations basket'); ?></h2>
				<ul class="basket"></ul>

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
add_shortcode( 'basket', 'chawa_display_basket' );