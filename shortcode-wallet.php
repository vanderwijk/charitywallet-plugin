<?php 

function chawa_display_wallet() {

	if ( is_user_logged_in() ) { 
		$current_user = wp_get_current_user();

		try {

			require 'initialize-mollie.php';

			ob_start(); ?>

			<div class="top-up-modal popup" data-popup="top-up">
				<div class="popup-inner">

				<button type="button" aria-label="<?php _e('Close','chawa'); ?>" class="close-modal" data-popup-close="top-up">
					<svg viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
						<line x1="1" y1="11" x2="11" y2="1" stroke="black" stroke-width="2"/>
						<line x1="1" y1="1" x2="11" y2="11" stroke="black" stroke-width="2"/>
					</svg>
				</button>
				<header><h1><?php _e('Add funds to your wallet','chawa'); ?></h1></header>

				<form novalidate="novalidate" id="top-up-form" method="post">
					<section class="step-1">
						<label for="amount"><?php _e('Amount','chawa'); ?></label>
						<input id="amount" type="number" min="1" name="amount" placeholder="<?php _e('Amount','chawa'); ?>">
						<span class="notice"></span>
						<p><label><?php _e('Other amount','chawa'); ?>:</label></p>
						<ul class="choose-amount">
							<li>
								<input id="top-up-1000" type="radio" name="top-up-amount" value="10">
								<label for="top-up-1000">&euro; 10</label>
							</li>
							<li>
								<input id="top-up-2000" type="radio" name="top-up-amount" value="20">
								<label for="top-up-2000">&euro; 20</label>
							</li>
							<li>
								<input id="top-up-5000" type="radio" name="top-up-amount" value="50">
								<label for="top-up-5000">&euro; 50</label>
							</li>
						</ul>
						<button type="button" class="button-primary" id="next">
							<?php _e('Pay','chawa'); ?>
						</button>
					</section>
					<section class="step-2">
						<p><?php _e('Add','chawa'); ?> <span id="monthly-text" class="monthly-text"><?php _e('monthly','chawa'); ?></span> &euro;<span id="pay-amount"></span>* <?php _e('to my wallet','chawa'); ?> <a href="#" id="change-amount"><?php _e('change amount','chawa'); ?></a> </p>
						<p>
							<div class="pretty p-default p-curve p-smooth">
								<input type="checkbox" value="monthly" id="monthly" />
								<div class="state p-primary-o">
									<label><?php _e('Please repeat this transaction every month.','chawa'); ?></label>
								</div>
							</div>
						</p>
						<label for="issuer"><?php _e('Your bank','chawa'); ?></label>
						<?php
							$method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);
							echo '<select name="issuer" id="issuer">';
							echo '<option value="">' . __('Choose your bank','chawa') . '</option>';
							foreach ($method->issuers() as $issuer) {
								echo '<option value="' . htmlspecialchars($issuer->id) . '">' . htmlspecialchars($issuer->name) . '</option>';
							}
							echo '</select>';
						?>
						<button type="submit" class="button-primary">
							<?php _e('Complete payment','chawa'); ?>
						</button>
						<p><small>*<?php _e('44 cent transaction costs will be added','chawa'); ?>.</small></p>
					</section>
				</form>
			</div>
						</div>

			<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$orderId = time();

				$protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
				$hostname = $_SERVER['HTTP_HOST'];
				$path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

				// Use either input or radio value
				if(!isset($_POST['top-up-amount'])) {
					$amount = ($_POST["amount"]);
				} else {
					$amount = ($_POST["top-up-amount"]);
				}

				$amount = number_format($amount, 2, '.', ' '); // Add two decimals
				$amount = $amount + 0.44; // Fixed transaction costs
				settype($amount, "string"); // Convert to string for Mollie API

				/*
				* Payment parameters:
				*   amount        Amount in EUROs. This example creates a € 27.50 payment.
				*   method        Payment method "ideal".
				*   description   Description of the payment.
				*   redirectUrl   Redirect location. The customer will be redirected there after the payment.
				*   webhookUrl    Webhook location, used to report when the payment changes state.
				*   metadata      Custom metadata that is stored with the payment.
				*   issuer        The customer's bank. If empty the customer can select it later.
				*/
				$payment = $mollie->payments->create([
					"amount" => [
						"currency" => "EUR",
						"value" => $amount // You must send the correct number of decimals, thus we enforce the use of strings
					],
					"method" => \Mollie\Api\Types\PaymentMethod::IDEAL,
					"description" => 'Wallet ' . __('Transaction','chawa') . ' #{$orderId}',
					"redirectUrl" => "{$protocol}://{$hostname}{$path}/payments/return.php?order_id={$orderId}",
					"webhookUrl" => "{$protocol}://{$hostname}{$path}/payments/webhook.php",
					"metadata" => [
						"order_id" => $orderId,
					],
					"issuer" => !empty($_POST["issuer"]) ? $_POST["issuer"] : null
				]);
				/*
				* In this example we store the order with its payment status in a database.
				*/
				//save_transaction($orderId, $payment->status);
				/*
				* Send the customer off to complete the payment.
				* This request should always be a GET, thus we enforce 303 http response code
				*/
				header("Location: " . $payment->getCheckoutUrl(), true, 303);
			} ?>

			<?php echo ob_get_clean(); // use return if shortcode
			} catch (\Mollie\Api\Exceptions\ApiException $e) {
				echo "API call failed: " . htmlspecialchars($e->getMessage());
			}

		} else { // not logged in
			ob_start(); ?>
		
			<p class="warning">
				<?php _e('You must be'); ?>
				<a href="<?php echo wp_login_url( get_permalink() ); ?>" title="<?php _e('Log in'); ?>"><?php _e('logged in','chawa'); ?></a> 
				<?php _e('to access your wallet.', 'chawa'); ?>
			</p>
	
			<?php return ob_get_clean();
		}

}
add_action('wp_footer', 'chawa_display_wallet');