<?php 

// Single charity functionality (Add/Remove/Process item from basket)

function chawa_single_charity() {

	do_action('start_shortcode_charity'); 

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

			ob_start(); ?>

			<div class="basket">

				<style>
					.charity-list td:last-child {
						text-align: right;
					}
					.charity-basket td {
						text-align: right;
					}
					.charity-basket td:first-child {
						text-align: left;
					}
					.remove {
						background-color: #FE6C61;
					}
					.basket-icon {
						float: right;
						font-weight: 700;
					}
					button[disabled=disabled],
					button:disabled {
						background-color: #abb4bb;
					}
					.charity-basket .amount {
						display: none;
					}
					.charity-basket .value {
						color: #51a8b8;
						font-weight: 600;
						font-size: 1.2em;
						margin: 0 10px;
					}
					.charity-basket .minus,
					.charity-basket .plus {
						font-weight: 900;
						padding: 10px;
					}
					.charity-basket .minus:hover,
					.charity-basket .plus:hover {
						cursor: pointer;
					}
				</style>

				<table class="charity-list" id="charity-list">
					<tbody>
						<tr>
							<td>Greenpeace Nederland</td>
							<td><button class="donate" data-charity="159" data-amount="1" data-name="Greenpeace Nederland"><?php _e('Add'); ?></button></td>
						</tr>
					</tbody>
				</table>

				<table class="charity-basket" id="charity-basket"></table>

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
add_shortcode( 'charity', 'chawa_single_charity' );