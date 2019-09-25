<?php 

// Donation basket functionality (Add/Remove/Process items from basket)

function chawa_add_to_basket() {

	do_action('start_shortcode_donate'); 

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
					.amount-wrap {
						white-space: nowrap;
					}
				</style>

				<h2><?php _e('Charities', 'chawa'); ?></h2>

				<table class="charity-list" id="charity-list">
					<tbody>
						<tr>
							<td>Greenpeace Nederland</td>
							<td><button class="donate" data-charity="159" data-amount="1" data-name="Greenpeace Nederland"><?php _e('Add'); ?></button></td>
						</tr>
						<tr>
							<td>Wereld Natuurfonds - Nederland</td>
							<td><button class="donate" data-charity="271" data-amount="1" data-name="Wereld Natuurfonds - Nederland"><?php _e('Add'); ?></button></td>
						</tr>
						<tr>
							<td>Amnesty International</td>
							<td><button class="donate" data-charity="107" data-amount="1" data-name="Amnesty International"><?php _e('Add'); ?></button></td>
						</tr>

					</tbody>
				</table>

				<h2><?php _e('Charity Basket', 'chawa'); ?></h2>

				<table class="charity-basket" id="charity-basket"></table>

				<div class="basket-icon">
					<span class="basket-count" id="basket-count"></span>
					<span class="basket-total" id="basket-total"></span>
				</div>

				<div class="basket-date" id="basket-date"></div>

				<div class="pretty p-switch p-fill">
					<input type="checkbox" class="basket-recurring" id="basket-recurring" />
					<div class="state">
						<label><?php _e('Donate monthly','chawa'); ?></label>
					</div>
				</div>

			</div>

			<?php return ob_get_clean(); // use return if shortcode

		} else { // not logged in
			ob_start(); ?>
		
			<p class="warning">
				<?php _e('You must be', 'chawa'); ?>
				<a href="<?php echo wp_login_url( get_permalink() ); ?>" title="<?php _e('Log in'); ?>"><?php _e('logged in','chawa'); ?></a> 
				<?php _e('to access your basket.', 'chawa'); ?>
			</p>
	
			<?php return ob_get_clean();
		}

}
add_shortcode( 'donate', 'chawa_add_to_basket' );