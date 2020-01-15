
jQuery('document').ready(function($) {

	$('#user-interests').on('change',function() {

		if ($('#user-interests #wellbeing').is(':checked')) {
			var wellbeing = 'wellbeing';
		} else {
			var wellbeing = '';
		}

		if ($('#user-interests #health').is(':checked')) {
			var health = 'health';
		} else {
			var health = '';
		}

		if ($('#user-interests #animals').is(':checked')) {
			var animals = 'animals';
		} else {
			var animals = '';
		}

		if ($('#user-interests #education').is(':checked')) {
			var education = 'education';
		} else {
			var education = '';
		}

		if ($('#user-interests #religion').is(':checked')) {
			var religion = 'religion';
		} else {
			var religion = '';
		}

		if ($('#user-interests #arts-culture').is(':checked')) {
			var arts_culture = 'arts-culture';
		} else {
			var arts_culture = '';
		}

		if ($('#user-interests #aid-human-rights').is(':checked')) {
			var aid_human_rights = 'aid-human-rights';
		} else {
			var aid_human_rights = '';
		}

		$.ajax({
			url : ajax_url,
			type: 'POST',
			data: {
				action  : 'um_cb',
				'wellbeing': wellbeing,
				'health': health,
				'animals': animals,
				'education': education,
				'religion': religion,
				'arts_culture': arts_culture,
				'aid_human_rights': aid_human_rights
			}
		})
		.success( function() {
			console.log( 'Usermeta updated.' );
		})
		.fail( function( data ) {
			console.log( data );
			console.log( 'Request failed: ' + data.statusText );
		});

		return false;

	});

	$('#user-communications').on('change',function() {

		if ($('#user-communications #newsletter').is(':checked')) {
			var newsletter = 'subscribed';
		} else {
			var newsletter = 'unsubscribed';
		}

		if ($('#user-communications #post').is(':checked')) {
			var post = 'post';
		} else {
			var post = '';
		}

		$.ajax({
			url : ajax_url,
			type: 'POST',
			data: {
				action  : 'um_cb',
				'newsletter': newsletter,
				'post': post,
			}
		})
		.success( function() {
			console.log( 'Usermeta updated.' );
		})
		.fail( function( data ) {
			console.log( data );
			console.log( 'Request failed: ' + data.statusText );
		});

		return false;

	});

});