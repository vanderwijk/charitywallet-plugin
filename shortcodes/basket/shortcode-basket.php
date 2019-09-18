<?php 

function chawa_display_basket() {

	do_action('start_shortcode_basket'); 

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
					.charity-list li {
						display: flex;
						justify-content: space-between;
						width: 100%;
					}
					.amount-wrap {
						white-space: nowrap;
					}
				</style>

				<h2><?php _e('Your donations basket', 'chawa'); ?></h2>
				<table class="charity-basket" id="charity-basket"><tbody></tbody>

				<tfoot>
					<tr>
						<td>				
							<div class="pretty p-switch p-fill">
								<input type="checkbox" class="basket-recurring" id="basket-recurring" />
								<div class="state">
									<label><?php _e('Donate monthly','chawa'); ?></label>
								</div>
							</div>
						</td>
						<td style="text-align: center;font-weight:600;font-size: 1.2em;">				
							<span class="basket-total" id="basket-total"></span>
						</td>
						<td><input type="submit" value="Doneer"></td>
					</tr>
				</tfoot>
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
add_shortcode( 'basket', 'chawa_display_basket' );