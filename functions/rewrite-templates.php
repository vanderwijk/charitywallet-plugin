<?php
// rewrite rules
function chawa_rewrite_rules() {

	add_rewrite_rule('basket/?$', 'index.php?basket=basket', 'top' );

	add_rewrite_rule('wallet/?$', 'index.php?wallet=wallet', 'top' );
	add_rewrite_rule('wallet/' . _x('transactions','rewrite rule','chawa') . '/?$', 'index.php?wallet=transactions', 'top' );

	add_rewrite_rule('account/?$', 'index.php?account=account', 'top' );
	add_rewrite_rule('account/edit/?$', 'index.php?account=account-edit', 'top' );

	add_rewrite_rule('webhook/charge/?$', 'index.php?webhook=charge', 'top' );

	add_rewrite_rule('onboarding/account/?$', 'index.php?onboarding=account', 'top' );
	add_rewrite_rule('onboarding/wallet/?$', 'index.php?onboarding=wallet', 'top' );
	add_rewrite_rule('onboarding/pay/?$', 'index.php?onboarding=pay', 'top' );
	add_rewrite_rule('onboarding/pay/charge/?$', 'index.php?onboarding=pay-charge', 'top' );
}
add_action('init', 'chawa_rewrite_rules');

// query vars
function chawa_register_query_var( $vars ) {
	$vars[] = 'basket';
	$vars[] = 'wallet';
	$vars[] = 'account';
	$vars[] = 'webhook';
	$vars[] = 'onboarding';
	return $vars;
}
add_filter('query_vars', 'chawa_register_query_var' );

// template include
function chawa_include_templates($template) {
	global $wp_query;
	
	if ( isset($wp_query->query_vars['onboarding'])) {

		$query_var = $wp_query->query_vars['onboarding'];

		if ($query_var && $query_var === 'account') {
			return CHAWA_PLUGIN_DIR_PATH .'templates/onboarding/account.php';
		}
		
		if ($query_var && $query_var === 'wallet') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/onboarding/wallet.php';
		}
		
		if ($query_var && $query_var === 'pay') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/onboarding/pay.php';
		}
		
		if ($query_var && $query_var === 'pay-charge') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/onboarding/pay-charge.php';
		}

	} else if ( isset($wp_query->query_vars['basket'])) {

		$query_var = $wp_query->query_vars['basket'];

		if ($query_var && $query_var === 'basket') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/basket/basket.php';
		}

	} else if ( isset($wp_query->query_vars['wallet'])) {

		$query_var = $wp_query->query_vars['wallet'];

		if ($query_var && $query_var === 'wallet') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/wallet/wallet.php';
		}

		if ($query_var && $query_var === 'transactions') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/wallet/transactions.php';
		}

	} else if ( isset($wp_query->query_vars['webhook'])) {

		$query_var = $wp_query->query_vars['webhook'];

		if ($query_var && $query_var === 'charge') {
			return CHAWA_PLUGIN_DIR_PATH . 'webhooks/charge.php';
		}

	} else if ( isset($wp_query->query_vars['account'])) {

		$query_var = $wp_query->query_vars['account'];

		if ($query_var && $query_var === 'account') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/account/account.php';
		}

		if ($query_var && $query_var === 'account-edit') {
			return CHAWA_PLUGIN_DIR_PATH . 'templates/account/account-edit.php';
		}

	}

	return $template;
}
add_filter('template_include', 'chawa_include_templates', 1, 1);
