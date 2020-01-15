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

			<?php echo do_shortcode('[gravityform id="2" title="false" description="false" ajax="true"]'); ?>

			<p><a href="/account/"><span class="dashicons dashicons-arrow-left-alt2" style="line-height: inherit;"></span><?php _e('back', 'chawa'); ?></a></p>

		</div>
	</main>
</section>

<?php get_footer(); ?>

<script type="text/javascript">
	gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {
		if ( formId == 2 && fieldId == 3 ) {
			optionsObj.minDate = '-100 Y';
			optionsObj.maxDate = '-18 Y';
		}
		return optionsObj;
	});
</script>