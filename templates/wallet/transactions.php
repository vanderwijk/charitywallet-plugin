<?php // make sure user is logged in
if (!is_user_logged_in()) {
	global $wp;
	$redirect = home_url($wp->request);
	wp_redirect(wp_login_url($redirect));
}
get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">

			<h1><?php _e('Wallet transactions', 'chawa'); ?></h1>
			<p><?php _e('These are your wallet transactions.', 'chawa'); ?></p>

			<table>
				<thead>
					<tr>
						<th><?php _e('Date', 'chawa'); ?></th>
						<th><?php _e('Type', 'chawa'); ?></th>
						<th class="text-align-right"><?php _e('Amount', 'chawa'); ?></th>
						<th><?php _e('Status', 'chawa'); ?></th>
					</tr>
				</thead>
				<tbody>

					<?php
					$user_id = get_current_user_id();
					$wallet_balance = 0;
					$transactions = $wpdb->get_results (
						"SELECT * FROM " . CHAWA_TABLE_TRANSACTIONS . " where user_id =" . $user_id . " ORDER BY time DESC"
					);
					
					foreach ($transactions as $transaction) {
						//print_r($transaction);
						if ($transaction -> transaction_type === 'iDEAL' ) {
							echo '<tr>';
							echo '<td>' . date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), strtotime($transaction -> time)) . '</td>';
							echo '<td>' . __($transaction -> transaction_type, 'chawa') . '</td>';
							echo '<td class="text-align-right">' . '€' . number_format_i18n($transaction -> amount/100, 2) . '</td>';
							if ($transaction -> charge_status) {
								echo '<td>' . _x($transaction -> charge_status,'charge status', 'chawa') . '</td>';
							} else {
								echo '<td>' . _x($transaction -> source_status,'charge status', 'chawa') . '</td>';
							}
							echo '</tr>';
							if ($transaction -> charge_status === 'succeeded' ) {
								$wallet_balance = $wallet_balance + $transaction -> amount;
							}
						} else if ($transaction -> transaction_type === 'Transaction costs' ) {
							echo '<tr>';
							echo '<td>' . date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), strtotime($transaction -> time)) . '</td>';
							echo '<td>' . __($transaction -> transaction_type, 'chawa') . '</td>';
							echo '<td class="text-align-right">' . '€' . number_format_i18n($transaction -> amount/100, 2) . '</td>';
							echo '<td>' . _x($transaction -> transaction_status,'charge status', 'chawa') . '</td>';
							echo '</tr>';
							if ($transaction -> transaction_status === 'succeeded' ) {
								$wallet_balance = $wallet_balance - $transaction -> amount;
							}
						} else if ($transaction -> transaction_type === 'Donation' ) {
							echo '<tr>';
							echo '<td>' . date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), strtotime($transaction -> time)) . '</td>';
							echo '<td>' . __($transaction -> transaction_type, 'chawa') . '</td>';
							echo '<td class="text-align-right">' . '€' . number_format_i18n($transaction -> amount/100, 2) . '</td>';
							echo '<td>' . _x($transaction -> transaction_status,'charge status', 'chawa') . '</td>';
							echo '</tr>';
							if ($transaction -> transaction_status === 'succeeded' ) {
								$wallet_balance = $wallet_balance - $transaction -> amount;
							}
						}
					} ?>

				</tbody>
			</table>

			<?php echo '<h2>' . __('Your Wallet Balance', 'chawa') . ' €' . number_format_i18n($wallet_balance/100, 2) . '</h2>'; ?></td>

		</div>
	</main>
</section>

<?php get_footer(); ?>