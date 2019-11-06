<?php
/*
 * Make sure to disable the display of errors in production code!
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";

/*
 * Initialize the Stripe API library with your API key.
 *
 */

\Stripe\Stripe::setApiKey('sk_test_mH8VTJrMCi3COhE9Nme0W9Vp00Adw1lBl6');
//$charge = \Stripe\Charge::create(['amount' => 2000, 'currency' => 'usd', 'source' => 'tok_189fqt2eZvKYlo2CTGBeg6Uq']);
//echo $charge;

$balance = \Stripe\Balance::retrieve();
echo $balance;