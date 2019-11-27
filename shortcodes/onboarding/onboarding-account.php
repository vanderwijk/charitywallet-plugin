<?php get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<?php wp_enqueue_script( 'onboarding_account' ); ?>

			<div class="step" id="step-1">
				<h1><?php _e('Great to have you here!', 'chawa'); ?></h1>
				<p><?php _e('Are you ready to start donating or do you first like to look around?', 'chawa'); ?></p>
				<p><button class="next" id="next-2"><?php _e('I want to donate', 'chawa'); ?></button></p>
				<p><a href="/charity/"><?php _e('I would like to look around first.', 'chawa'); ?></a></p>
			</div>

			<div class="step" id="step-2" style="display: none;">
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