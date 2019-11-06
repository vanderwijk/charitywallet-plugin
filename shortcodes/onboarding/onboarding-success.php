<?php 
// Make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
} ?>

<?php get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

<?php 
	wp_enqueue_script( 'onboarding_pay' );
	wp_localize_script( 'onboarding_pay', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'title' => ( current_time( 'H:i:s' ) ) ) );
?>

<style>
	.entry-title { display: none; }
	.col-1,
	.col-2 {
		margin-bottom: 3%;
	}
	.col-2 {
		display: flex;
		justify-content: space-between;
	}
	.col-2 .col {
		width: 48%;
	}
	input[type="text"].parsley-error {
		border-color: #FE6C61;
	}
	ul.parsley-errors-list {
		list-style: none;
		margin: 0;
		color: #FE6C61;
		padding: 0;
	}
	.pay input[type="radio"],
	.pay input[type="checkbox"] {
		margin-right: 5px;
	}
	ul.parsley-errors-list li {
		margin-bottom: -10px;
	}
	#pay label {
		display: inline;
		margin-right: 5px;
	}
	.error {
		border: 1px solid #FE6C61;
		display: block;
		margin-bottom: 3%;
		padding: 1% 2%;
		border-radius: 3px;
		background-color: rgba(254,108,97,0.8);
		color: #fff;
		font-weight: 600;
	}
</style>

<div class="step" id="step-6">
	<h1><?php _e('Wallet opwaarderen', 'chawa'); ?></h1>
	<p><strong><?php _e('We gaan nu je wallet opwaarderen.', 'chawa'); ?></strong></p>
	<p><button class="button next" id="next-7"><?php _e('Next', 'chawa'); ?></button></p>
</div>

<div class="step" id="step-7" style="display: none;">
	<h1><?php _e('Wallet opwaarderen', 'chawa'); ?></h1>
	<div class="notice" id="notice"></div>
	
	<form method="post" class="pay" id="pay" enctype="multipart/form-data" action="" onSubmit="pay(<?php echo get_current_user_id(); ?>)">
		<p><strong><?php _e('Je wilt', 'chawa'); ?> 
		<?php if ( $recurring === 'true' ) {
			echo 'maandelijks';
		} 
		echo ' € ' . $amount . ',00'; ?> 
		
		<?php _e('(<a href="/onboarding/wallet/">wijzig</a>) donatiegeld storten op je wallet.', 'chawa'); ?></strong></p>
		<span class="bank">
			<p><strong><?php _e('Kies je bank', 'chawa'); ?></strong></p>
			<p>
			<?php
				$method = $stripe->methods->get(\Stripe\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);
				echo '<select name="issuer" id="issuer">';
				echo '<option value="">' . __('Choose your bank','chawa') . '</option>';
				foreach ($method->issuers() as $issuer) {
					echo '<option value="' . htmlspecialchars($issuer->id) . '">' . htmlspecialchars($issuer->name) . '</option>';
				}
				echo '</select>';
			?>
			</p>
		</span>
		<p>
			<label><input type="checkbox" name="accept" value="accept"><?php _e('Ik ga akkoord met de <a href="">algemene voorwaarden</a> en het <a href="">privacy statement</a>.', 'chawa'); ?></label>
		</p>
		<p><small><?php _e('Per transatie rekenen we € 0,44 transactiekosten bovenop het donatiegeld.', 'chawa'); ?></small></p>
		<p><input type="submit" id="submit" class="next" id="next-4" value="<?php _e('Akkoord & betalen', 'chawa'); ?>"></p>
	</form>
</div>

		</div>
	</main>
</section>

<?php } catch (\Stripe\Api\Exceptions\ApiException $e) {
	echo "API call failed: " . htmlspecialchars($e->getMessage());
} ?>

<?php get_footer(); ?>