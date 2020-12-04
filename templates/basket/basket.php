<?php // make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}
$user_id = get_current_user_id();
$user_data = get_userdata($user_id);
$user_meta = get_user_meta($user_id);

get_header(); ?>

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

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<h1><?php _e('Your donations basket', 'chawa'); ?></h1>

			<table class="charity-basket" id="charity-basket">
				<tbody></tbody>
				<tfoot>
					<tr>
						<td style="display: none;">
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

			<script>
			jQuery(document).ready(function($) {
				showCart();

				$('#charity-basket').on('click', '.minus', function() {
					charity = {};
					charity.id = $(this).parents('tr').attr('data-charity-id');
					amount = $('[name=' + charity.id + ']').val();
					charity.amount = parseFloat(amount) - 1;
					if ( charity.amount > 0 ) {
						updateCart(charity);
					}
				});

				$('#charity-basket').on('click', '.plus', function() {
					charity = {};
					charity.id = $(this).parents('tr').attr('data-charity-id');
					amount = $('[name=' + charity.id + ']').val();
					charity.amount = parseFloat(amount) + 1;
					updateCart(charity);
				});

				$('#charity-basket').on('change', '.amount', function() {
					//console.log( jQuery( this ).val() );
					charity = {};
					charity.id = parseInt($(this).attr('name'));
					charity.amount = parseInt($(this).val());
					updateCart(charity);
				});

			});
			</script>

		</div>
	</main>
</section>

<?php get_footer(); ?>