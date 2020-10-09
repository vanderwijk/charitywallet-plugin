<?php // make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}
get_header();
$user_id = get_current_user_id(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<h1><?php _e('Wallet transactions', 'chawa'); ?></h1>

			<p><?php echo '<h2>' . __('Your Wallet Balance is', 'chawa') . ' €' . get_wallet_balance($user_id) . '</h2>'; ?></p>

			<p><?php _e('These are your wallet transactions.', 'chawa'); ?></p>

			<table class="transactions">
				<thead>
					<tr>
						<th><?php _e('Date', 'chawa'); ?></th>
						<th><?php _e('Type', 'chawa'); ?></th>
						<th class="text-align-right"><?php _e('Amount', 'chawa'); ?> (EUR)</th>
						<th><?php _e('Status', 'chawa'); ?></th>
					</tr>
				</thead>
				<tbody>

					<?php
					$transactions = $wpdb->get_results (
						"SELECT * FROM " . CHAWA_TABLE_TRANSACTIONS . " where user_id =" . $user_id . " ORDER BY time DESC"
					);
					
					foreach ($transactions as $transaction) {
						//print_r($transaction);
						if ($transaction -> transaction_type === 'iDEAL' ) {
							echo '<tr>';
							echo '<td class="date">' . date_i18n(get_option('date_format'), strtotime($transaction -> time)) . '</td>';
							echo '<td class="type">' . __($transaction -> transaction_type, 'chawa') . '</td>';
							echo '<td class="amount">' . number_format_i18n($transaction -> amount/100, 2) . '</td>';
							if ($transaction -> charge_status) {
								echo '<td>' . _x($transaction -> charge_status, 'charge status', 'chawa') . '</td>';
							} else {
								echo '<td>' . _x($transaction -> source_status, 'charge status', 'chawa') . '</td>';
							}
							echo '</tr>';
						} else if ($transaction -> transaction_type === 'Transaction costs' ) {
							echo '<tr>';
							echo '<td class="date">' . date_i18n(get_option('date_format'), strtotime($transaction -> time)) . '</td>';
							echo '<td class="type">' . __($transaction -> transaction_type, 'chawa') . '</td>';
							echo '<td class="amount"> - ' . number_format_i18n($transaction -> amount/100, 2) . '</td>';
							echo '<td>' . _x($transaction -> transaction_status, 'charge status', 'chawa') . '</td>';
							echo '</tr>';
						} else if ($transaction -> transaction_type === 'Donation' ) {
							echo '<tr>';
							echo '<td class="date">' . date_i18n(get_option('date_format'), strtotime($transaction -> time)) . '</td>';
							echo '<td class="type">' . __($transaction -> transaction_type, 'chawa') . '<span id="details" class="details">' .  __('details', 'chawa')  . '</span></td>';
							echo '<td class="amount"> - ' . number_format_i18n($transaction -> amount/100, 2) . '</td>';
							echo '<td>' . _x($transaction -> transaction_status, 'charge status', 'chawa') . '</td>';
							echo '</tr>';

							$donations = $wpdb->get_results (
								"SELECT * FROM " . CHAWA_TABLE_DONATIONS . " where transaction_id ='" . $transaction -> transaction_id . "' ORDER BY time DESC"
							);

							foreach ($donations as $donation) {
								echo '<tr class="collapse">';
								echo '<td></td>';
								echo '<td class="organisation">';
								//print_r($donation);
								echo get_the_title($donation -> charity_id);
								echo '</td>';
								echo '<td class="text-align-right">' . number_format_i18n($donation -> amount/100, 2) . '</td>';
								echo '</td>';
								echo '<td></td>';
								echo '</tr>';
							}
						}
					} ?>

				</tbody>
			</table>

			<script>
				jQuery('document').ready(function($) {
					$('.details').on('click',function() {
						$(this).parent().parent().nextAll('.collapse').toggle();
					});


				});
				</script>

		</div>
	</main>
</section>

<?php get_footer(); ?>