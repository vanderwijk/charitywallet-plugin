<?php 

// Single charity functionality (Add/Remove/Process item from basket)

function chawa_single_account() {

	do_action('start_shortcode_account'); 

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

			ob_start(); ?>
		
			<div class="account">

				<h2><?php _e('Wallet', 'chawa'); ?></h2>

				<?php _e('Balance', 'chawa'); ?><div id="wallet-balance"></div>

				<?php _e('Recurring', 'chawa'); ?><div id="wallet-recurring"></div>

				<?php _e('Date', 'chawa'); ?><div id="wallet-date"></div>

				<h2><?php _e('Financial overview', 'chawa'); ?></h2>

				<table>
					<thead>
						<tr>
							<th><?php _e('Date', 'chawa'); ?></th>
							<th><?php _e('Description', 'chawa'); ?></th>
							<th><?php _e('Amount', 'chawa'); ?></th>
							<th></th>
						</tr>
					<thead>
					<tbody>
						<tr>
							<td>11-09-2019</td>
							<td><?php _e('Monthly wallet top-up', 'chawa'); ?></td>
							<td>€ 10,-</td>
							<td></td>
						</tr>
						<tr>
							<td>11-09-2019</td>
							<td><?php _e('Monthly donation', 'chawa'); ?></td>
							<td>€ 10,-</td>
							<td></td>
						</tr>
					</tbody>
				</table>

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
add_shortcode( 'account', 'chawa_single_account' );