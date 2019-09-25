<?php 

// Donation basket functionality (Add/Remove/Process items from basket)

function chawa_onboarding() {

	do_action('start_shortcode_onboarding'); 

	ob_start(); ?>

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
		.gfield_required {
			display: none;
		}
		#step-3 .gform_wrapper div.validation_error {
			display: none;
		}
		#step-3 .gform_wrapper li.gfield.gfield_error {
			background-color: transparent;
			border: none;
		}
		#step-3 .gform_wrapper .gfield_error .gfield_label,
		#step-3 .gform_wrapper .validation_message,
		#step-3 .gform_wrapper li.gfield_error div.ginput_complex.ginput_container label {
			color: #FE6C61;
		}
		#step-3 .gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]) {
			border-color: #FE6C61;
		}
	</style>

	<div class="step" id="step-1">
		<h1><?php _e('Leuk dat je er bent!', 'chawa'); ?></h1>
		<p><?php _e('Ben je klaar voor het nieuwe doneren of wil je eerst even rondkijken?', 'chawa'); ?></p>
		<p><button class="next" id="next-2"><?php _e('Ik wil doneren', 'chawa'); ?></button></p>
		<p><a href="/charity/"><?php _e('Ik kijk nog even rond', 'chawa'); ?></a></p>
	</div>

	<div class="step" id="step-2" style="display: none;">
		<h1><?php _e('Account maken', 'chawa'); ?></h1>
		<p><?php _e('Goed idee om vanaf vandaag te gaan doneren zonder deurbel of handtekening!', 'chawa'); ?></p>
		<p><strong><?php _e('We gaan eerst een account voor je aanmaken.', 'chawa'); ?></strong></p>
		<p><button class="next" id="next-3"><?php _e('Next', 'chawa'); ?></button></p>
	</div>

	<div class="step" id="step-3" style="display: none;">
		<h1><?php _e('Account maken', 'chawa'); ?></h1>

		<?php echo do_shortcode( '[gravityform id=1 title=false description=false ajax=true tabindex=49]' ); ?>

	</div>

	<div class="step" id="step-4" style="display: none;">
		<h1><?php _e('Wallet activeren', 'chawa'); ?></h1>
		<p><?php _e('Om te kunnen doneren via CharityWallet, gaan we nu eerst een digitale portemonnee (je Wallet) voor je maken waar je eenmalig of maandelijks donatiegeld naar kunt overmaken.', 'chawa'); ?></p>
		<p><strong><?php _e('We gaan nu je wallet activeren.', 'chawa'); ?></strong></p>
		<p><button class="next" id="next-5"><?php _e('Next', 'chawa'); ?></button></p>
	</div>

	<div class="step" id="step-5" style="display: none;">
		<h1><?php _e('Donatiebedrag kiezen', 'chawa'); ?></h1>
		<p><strong><?php _e('Wil je eenmalig of maandelijks donatiegeld overmaken naar je wallet?', 'chawa'); ?></strong></p>
		<p><input type="radio" name="recurring" value="false">eenmalig
		<input type="radio" name="recurring" value="true" selected>maandelijks</p>
		<p><strong><?php _e('Welk donatiebedrag wil je overmaken naar je wallet?', 'chawa'); ?></strong></p>
		<p><input type="radio" name="amount" value="10">€ 10,00
		<input type="radio" name="amount" value="20" selected>€ 20,00
		<input type="radio" name="amount" value="50" selected>€ 50,00
		<input type="number" name="amount" placeholder="<?php _e('Kies zelf een bedrag', 'chawa'); ?>"></p>
		<p><small><?php _e('Wat je ook kiest, je kunt het later altijd nog aanpassen.', 'chawa'); ?></small></p>
		<p><button class="next" id="next-6"><?php _e('Next', 'chawa'); ?></button></p>
	</div>

	<?php return ob_get_clean(); // use return if shortcode

}
add_shortcode( 'onboarding', 'chawa_onboarding' );