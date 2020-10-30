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

			<form method="post" class="account-edit" id="account-edit" enctype="multipart/form-data">
				<p class="two-columns">
					<label for="firstname"><?php _e('First name', 'chawa'); ?>
						<input type="text" required name="firstname" id="firstname" value="<?php echo esc_html( $user_meta['first_name'][0] ); ?>">
					</label>
					<label for="lastname"><?php _e('Last name', 'chawa'); ?>
						<input type="text" required name="lastname" id="lastname" value="<?php echo esc_html( $user_meta['last_name'][0] ); ?>">
					</label>
				</p>
				<p class="two-columns">
					<label for="email"><?php _e('Email address', 'chawa'); ?>
						<input type="text" required name="email" id="email" value="<?php echo esc_html( $user_data->user_email ); ?>">
					</label>
					<label for="phone"><?php _e('Phone', 'chawa'); ?>
						<input type="tel" name="phone" id="phone" value="<?php if ( isset($user_meta['user_phone'][0]) ) { echo esc_html( $user_meta['user_phone'][0] ); } ?>">
					</label>
				</p>
				<p>
					<label for="user_address_street"><?php _e('Address', 'chawa'); ?>
						<input type="text" required name="user_address_street" id="user_address_street" value="<?php if ( isset($user_meta['user_address_street'][0]) ) { echo esc_html( $user_meta['user_address_street'][0] ); } ?>">
					</label>
				</p>
				<p class="two-columns">
					<label for="user_address_postcode"><?php _e('Postcode', 'chawa'); ?>
						<input type="text" name="user_address_postcode" id="user_address_postcode" value="<?php if ( isset($user_meta['user_address_postcode'][0]) ) { echo esc_html( $user_meta['user_address_postcode'][0] ); } ?>">
					</label>
					<label for="user_address_city"><?php _e('City', 'chawa'); ?>
						<input type="text" name="user_address_city" id="user_address_city" value="<?php if ( isset($user_meta['user_address_city'][0]) ) { echo esc_html( $user_meta['user_address_city'][0] ); } ?>">
					</label>
				</p>
				<p>
					<label for="user_address_country"><?php _e('Country', 'chawa'); ?>
						<input type="text" name="user_address_country" id="user_address_country" value="<?php if ( isset($user_meta['user_address_country'][0]) ) { echo esc_html( $user_meta['user_address_country'][0] ); } ?>">
					</label>
				</p>
				<p><input type="submit" id="submit" value="<?php _e('Save', 'chawa'); ?>"></p>
				<input type="hidden" name="uid" value="<?php echo $user_id; ?>">
			</form>

			<p><a href="/account/"><span class="dashicons dashicons-arrow-left-alt2" style="line-height: inherit;"></span><?php _e('back', 'chawa'); ?></a></p>

		</div>
	</main>
</section>

<?php get_footer(); ?>