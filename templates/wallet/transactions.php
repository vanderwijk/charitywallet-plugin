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
						<th class="alignright"><?php _e('Amount', 'chawa'); ?></th>
						<th class="alignright"><?php _e('Status', 'chawa'); ?></th>
					</tr>
				</thead>
				<tbody>

					<?php
					$user_id = get_current_user_id();
					$transactions = $wpdb->get_results (
						"SELECT * FROM " . CHAWA_TABLE_TRANSACTIONS . " where user_id =" . $user_id . " ORDER BY time DESC"
					);
					$wallet_balance = 0;
					foreach ($transactions as $transaction) {
						//print_r($transaction);
						echo '<tr>';
						echo '<td>' . date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), strtotime($transaction -> time)) . '</td>';
						echo '<td>' . __($transaction -> transaction_type, 'chawa') . '</td>';
						echo '<td class="alignright">' . '€' . number_format_i18n($transaction -> amount/100, 2) . '</td>';
						echo '<td class="alignright">' . __($transaction -> charge_status, 'chawa') . '</td>';
						echo '</tr>';
						if ($transaction -> transaction_type === 'iDEAL') {
							$wallet_balance = $wallet_balance + $transaction -> amount;
						} else if ($transaction -> transaction_type === 'Transaction costs') {
							$wallet_balance = $wallet_balance - $transaction -> amount;
						}
					} ?>

				</tbody>
				</foot>
					<tr>
						<td colspan="4"><?php echo '<h2>' . __('Wallet balance', 'chawa') . ' €' . number_format_i18n($wallet_balance/100, 2) . '</h2>'; ?></td>
					</tr>
				</tfoot>
			</table>

		</div>
	</main>
</section>

<?php get_footer(); ?>