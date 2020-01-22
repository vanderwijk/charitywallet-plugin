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

			<h1><?php _e('Account', 'chawa'); ?></h1>

			<?php 
				echo '<p><strong>' . __('Your name', 'chawa') . ':</strong><br />' . $user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0] . '</p>';
				echo '<p><strong>' . __('Your e-mail address', 'chawa') . ':</strong><br />' . $user_data->user_email . '</p>';
				echo '<p><strong>' . __('Your address', 'chawa') . ':</strong><br />' . $user_data->user_address_street . '<br />' . $user_data->user_address_postcode . ' ' . $user_data->user_address_city . '</p>';
				echo '<p><a href="/account/edit/" class="button">' . __('Edit account', 'chawa') . '</a></p>';
			?>

			<hr>

			<h2><?php _e('Your Interests', 'chawa'); ?></h2>

			<form id="user-interests" method="post">
				<div class="pretty p-default p-curve p-pulse">
				<input type="checkbox" id="wellbeing" <?php checked( $user_meta['interests_wellbeing'][0], 'wellbeing' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Wellbeing', 'chawa'); ?></label>
					</div>
				</div>
				<div class="pretty p-default p-curve p-pulse">
				<input type="checkbox" id="health" <?php checked( $user_meta['interests_health'][0], 'health' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Health', 'chawa'); ?></label>
					</div>
				</div>
				<div class="pretty p-default p-curve p-pulse">
				<input type="checkbox" id="animals" <?php checked( $user_meta['interests_animals'][0], 'animals' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Animals', 'chawa'); ?></label>
					</div>
				</div>
				<div class="pretty p-default p-curve p-pulse">
				<input type="checkbox" id="education" <?php checked( $user_meta['interests_education'][0], 'education' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Education', 'chawa'); ?></label>
					</div>
				</div>
				<div class="pretty p-default p-curve p-pulse">
				<input type="checkbox" id="religion" <?php checked( $user_meta['interests_religion'][0], 'religion' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Religion', 'chawa'); ?></label>
					</div>
				</div>
				<div class="pretty p-default p-curve p-pulse">
					<input type="checkbox" id="arts-culture" <?php checked( $user_meta['interests_arts_culture'][0], 'arts-culture' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Arts and culture', 'chawa'); ?></label>
					</div>
				</div>
				<div class="pretty p-default p-curve p-pulse">
					<input type="checkbox" id="aid-human-rights" <?php checked( $user_meta['interests_aid_human_rights'][0], 'aid-human-rights' ); ?> />
					<div class="state p-primary">
						<label><?php _e('International aid and human rights', 'chawa'); ?></label>
					</div>
				</div>
			</form>

			<hr>

			<h2><?php _e('Your Communication Preferences', 'chawa'); ?></h2>

			<form id="user-communications" method="post">
				<div class="pretty p-default p-curve p-pulse">
					<input type="checkbox" id="newsletter" <?php checked( $user_meta['communications_newsletter'][0], 'subscribed' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Newsletter', 'chawa'); ?></label>
					</div>
				</div>
				<div class="pretty p-default p-curve p-pulse">
					<input type="checkbox" id="post" <?php checked( $user_meta['communications_post'][0], 'post' ); ?> />
					<div class="state p-primary">
						<label><?php _e('Post', 'chawa'); ?></label>
					</div>
				</div>
			</form>

		</div>
	</main>
</section>

<?php get_footer(); ?>