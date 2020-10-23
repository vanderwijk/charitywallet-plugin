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

			<h1><?php _e('Edit account', 'chawa'); ?></h1>

			<?php //echo do_shortcode('[gravityform id="2" title="false" description="false" ajax="true"]'); ?>

			<form>
				<p class="two-columns">
					<label for="firstname"><?php _e('First name', 'chawa'); ?>
						<input type="text" id="firstname" value="<?php echo $user_meta['first_name'][0]; ?>">
					</label>
					<label for="lastname"><?php _e('Last name', 'chawa'); ?>
						<input type="text" id="lastname" value="<?php echo $user_meta['last_name'][0]; ?>">
					</label>
				</p>
				<p class="two-columns">
					<label for="email"><?php _e('Email address', 'chawa'); ?>
						<input type="text" id="email" value="<?php echo $user_data->user_email; ?>">
					</label>
					<label for="phone"><?php _e('Phone', 'chawa'); ?>
						<input type="tel" id="phone" value="<?php if ( isset($user_meta['phone'][0]) ) { echo $user_meta['phone'][0]; } ?>">
					</label>
				</p>
				<p class="two-columns">
					<label for="user_address_street"><?php _e('Address', 'chawa'); ?>
						<input type="text" id="user_address_street" value="<?php if ( isset($user_meta['user_address_street'][0]) ) { echo $user_meta['user_address_street'][0]; } ?>">
					</label>
					<label for="user_address_postcode"><?php _e('Postcode', 'chawa'); ?>
						<input type="text" id="user_address_postcode" value="<?php if ( isset($user_meta['user_address_postcode'][0]) ) { echo $user_meta['user_address_postcode'][0]; } ?>">
					</label>
				</p>
				<p class="two-columns">
					<label for="user_address_city"><?php _e('City', 'chawa'); ?>
						<input type="text" id="user_address_city" value="<?php if ( isset($user_meta['user_address_city'][0]) ) { echo $user_meta['user_address_city'][0]; } ?>">
					</label>
					<label for="user_address_postcode"><?php _e('Country', 'chawa'); ?>
						<input type="text" id="user_address_postcode" value="<?php if ( isset($user_meta['user_address_postcode'][0]) ) { echo $user_meta['user_address_postcode'][0]; } ?>">
					</label>
				</p>
				<p><input type="submit" id="submit" value="<?php _e('Save', 'chawa'); ?>"></p>
			</form>

			<p><a href="/account/"><span class="dashicons dashicons-arrow-left-alt2" style="line-height: inherit;"></span><?php _e('back', 'chawa'); ?></a></p>

		</div>
	</main>
</section>

<?php get_footer(); ?>