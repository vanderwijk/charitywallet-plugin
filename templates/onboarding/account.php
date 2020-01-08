<?php // make sure user is logged in
if (is_user_logged_in()) {
	global $wp;
	$redirect = home_url('/onboarding/wallet/');
	wp_safe_redirect($redirect);
	exit;
}

get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<?php wp_enqueue_script( 'onboarding_account' ); ?>

			<div class="step" id="step-2">
				<h1><?php _e('Create account', 'chawa'); ?></h1>
				<p><?php _e('Great idea to start donating without the need for a doorbell or signature!', 'chawa'); ?></p>
				<p><strong><?php _e('We will first create an account for you.', 'chawa'); ?></strong></p>
				<p><button class="next" id="next-3"><?php _e('Next', 'chawa'); ?></button></p>
			</div>

			<div class="step" id="step-3" style="display: none;">
				<h1><?php _e('Create account', 'chawa'); ?></h1>
				<?php echo do_shortcode( '[gravityform id=1 title=false description=false ajax=true tabindex=49]' ); ?>
			</div>

		</div>
	</main>
</section>

<?php get_footer(); ?>