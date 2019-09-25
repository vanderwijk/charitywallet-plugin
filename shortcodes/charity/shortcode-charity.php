<?php 

// Single charity functionality (Add/Remove/Process item from basket)

function chawa_single_charity() {

	do_action('start_shortcode_charity'); 

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

			ob_start(); ?>

			<style>
				.charity td:last-child {
					text-align: right;
				}
				.charity td {
					text-align: right;
				}
				.charity td:first-child {
					text-align: left;
				}
				.remove {
					background-color: #FE6C61;
				}
				button[disabled=disabled],
				button:disabled {
					background-color: #abb4bb;
				}
				.charity .amount {
					display: none;
				}
				.charity .value {
					color: #51a8b8;
					font-weight: 600;
					font-size: 1.2em;
					margin: 0 10px;
				}
				.charity .minus,
				.charity .plus {
					font-weight: 900;
					padding: 10px;
				}
				.charity .minus:hover,
				.charity .plus:hover {
					cursor: pointer;
				}
				#notification {
					display: none;
				}
				.amount-wrap::selection,
				.plus::selection,
				.minus::selection,
				.value::selection,
				.the-value::selection {
					background-color: transparent;
				}
			</style>

			<div id="notification" style="background-color: #fff198;padding: 0 .4em;">
				<?php _e('Your donation has been added to your', 'chawa'); ?>
				<a style="text-decoration: underline;" href="/basket/">
					<?php _e('basket', 'chawa'); ?>
				</a>
			</div>

			<table class="charity" id="charity">
				<tbody>
					<tr data-charity-id="159">
						<td>Greenpeace Nederland</td>
						<td>
							<span class="amount-wrap"><span class="plus">+</span>
							<span class="value">€<span class="the-value" id="the-value">1</span>,-</span>
							<span class="minus">-</span></span>
						</td>
						<td><button class="donate" data-charity="159" data-name="Greenpeace Nederland" data-amount="1"><?php _e('Add to basket', 'chawa'); ?></button></td>
					</tr>
				</tbody>
			</table>

			<?php return ob_get_clean(); // use return if shortcode

		} else { // not logged in
			ob_start(); ?>
		
			<p class="warning">
				<?php _e('You must be', 'chawa'); ?>
				<a href="<?php echo wp_login_url( get_permalink() ); ?>" title="<?php _e('Log in'); ?>"><?php _e('logged in','chawa'); ?></a> 
				<?php _e('to access your basket.','chawa'); ?>
			</p>
	
			<?php return ob_get_clean();
		}

}
add_shortcode( 'charity', 'chawa_single_charity' );