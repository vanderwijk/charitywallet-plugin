<?php

// DIT BESTAND WORDT (NOG) NIET GEBRUIKT

require __DIR__ . '/vendor/autoload.php';

use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;

try {

	// Create Client
	$client = new PostmarkClient("f4bcdc72-6313-4ac5-aae5-3523a7b12ed9");

	// Make a request to send with a specific template
	$sendResult = $client->sendEmailWithTemplate(
		"info@charitywallet.com",
		$_POST['recipient'],
		(int)$_POST['template'],
			[
				"email_deelnemer" => $_POST['recipient'],
				"voornaam_deelnemer" => $_POST['voornaam_deelnemer'],
				"achternaam_deelnemer" => $_POST['achternaam_deelnemer'],
				"state" => $_POST['state']
			]
		);
	
	// Return results for AJAX processing
	//print_r( $sendResult );
	
	// Return the messageID
	echo $sendResult->messageid;

} catch(PostmarkException $PostmarkException) {

	// If client is able to communicate with the API in a timely fashion,
	// but the message data is invalid, or there's a server error,
	// a PostmarkException can be thrown.
	//print_r( $PostmarkException );

	echo $PostmarkException->message;

	//echo 'error';

} catch(Exception $generalException) {

	// A general exception is thrown if the API
	// was unreachable or times out.
	print_r( $generalException );

	//echo 'error';

} 