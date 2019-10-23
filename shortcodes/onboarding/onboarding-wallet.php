<?php get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

<?php // BEGIN Include ?>

<?php 
	wp_enqueue_script( 'onboarding_wallet' );
	wp_localize_script( 'onboarding_wallet', 'WP_API_Settings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'title' => ( current_time( 'H:i:s' ) ) ) );
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
	ul.parsley-errors-list li {
		margin-bottom: -10px;
	}
	.update-user input[type="radio"],
	.update-user input[type="checkbox"] {
		margin-right: 5px;
	}
	.update-user label {
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


<?php 
	$user_id = get_current_user_id();
	$user_wallet = get_user_meta($user_id, 'wallet', true);
	if ( $user_wallet ) {
		$recurring = $user_wallet[0]['recurring'];
		$amount = $user_wallet[0]['amount'];
	}
?>

<div class="step" id="step-4">
	<h1><?php _e('Wallet activeren', 'chawa'); ?></h1>
	<p><?php _e('Om te kunnen doneren via CharityWallet, gaan we nu eerst een digitale portemonnee (je Wallet) voor je maken waar je eenmalig of maandelijks donatiegeld naar kunt overmaken.', 'chawa'); ?></p>
	<p><strong><?php _e('We gaan nu je wallet activeren.', 'chawa'); ?></strong></p>
	<p><button class="button next" id="next-5"><?php _e('Next', 'chawa'); ?></button></p>
</div>

<div class="step" id="step-5" style="display: none;">
	<h1><?php _e('Donatiebedrag kiezen', 'chawa'); ?></h1>
	<div class="notice" id="notice"></div>
	<form method="post" class="update-user" id="update-user" enctype="multipart/form-data" action="" onSubmit="updateUser(<?php echo get_current_user_id(); ?>)">
		<p><strong><?php _e('Wil je eenmalig of maandelijks donatiegeld overmaken naar je wallet?', 'chawa'); ?></strong></p>
		<p>
			<label><input type="radio" name="recurring" value="false" <?php if ($recurring === 'false') { echo 'checked'; } ?>>eenmalig</label>
			<label><input type="radio" name="recurring" value="true" <?php if ($recurring === 'true') { echo 'checked'; } ?>>maandelijks</label>
		</p>
		<p><strong><?php _e('Welk donatiebedrag wil je overmaken naar je wallet?', 'chawa'); ?></strong></p>
		<p>
			<label><input type="radio" name="amount" value="10" <?php if ($amount === '10') { echo 'checked'; } ?>>€ 10,00</label>
			<label><input type="radio" name="amount" value="20" <?php if ($amount === '20') { echo 'checked'; } ?>>€ 20,00</label>
			<label><input type="radio" name="amount" value="50" <?php if ($amount === '50') { echo 'checked'; } ?>>€ 50,00</label>
			<input type="number" name="amountCustom" placeholder="<?php _e('Kies zelf een bedrag', 'chawa'); ?>" <?php if ( !in_array( $amount, array(10,20,50))) { echo ' value="' . $amount . '"'; } ?>>
		</p>
		<p><small><?php _e('Wat je ook kiest, je kunt het later altijd nog aanpassen.', 'chawa'); ?></small></p>
		<p><input type="submit" id="submit" class="next" id="next-4" value="<?php _e('Next', 'chawa'); ?>"></p>
	</form>
</div>

<?php // END Include ?>

		</div>
	</main>
</section>

<?php get_footer(); ?>