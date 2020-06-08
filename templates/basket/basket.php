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

		</div>
	</main>
</section>

<?php get_footer(); ?>