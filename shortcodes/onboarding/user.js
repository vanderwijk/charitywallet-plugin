/* global jQuery */

/* To do:
- Make lowercase username without spaces
	- check if username exists
	- if exists add number
	- check if username exists (rinse repeat)
*/


/* To do:
- Gebruikers verwijderen werkt nog niet goed (verkeerde user_id)
*/

function registerParticipant(event) {

	event.preventDefault();

	// Lees waarden in van formulier
	firstname = jQuery('#first_name').val();
	lastname = jQuery('#last_name').val();
	email = jQuery('#user_email').val().toLowerCase();
	state = jQuery('#state').val();
	//group_id = jQuery('#group').val();
	//group_name = jQuery('#group_name').val();
	//organisation = jQuery('#organisation').val();


	// Genereer random username
	var min = 1000000;
	var max = 9999999;
	var num = Math.floor(Math.random() * (max - min + 1)) + min;

	// Maak nieuwe gebruiker aan
	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/users',
		method: 'POST',
		beforeSend: function (xhr) {
			jQuery('#submit').addClass('loading');
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
		},
		data: {
			username : 'u' + num,
			email: email,
			password: state,
			first_name: firstname,
			last_name: lastname,
			nickname: firstname + ' ' + lastname,
			//role: 'deelnemer',
			//group: [group_id]
		}
	})
	.done(function(data, textStatus, jqXHR) {
		// Als gebruiker is aangemaakt, leeg het formulier, voeg de gebruiker aan de lijst toe en toon melding
		jQuery('#add-user')[0].reset();
		jQuery('#submit').removeClass('loading');
		//jQuery('#notice').text('De deelnemer is toegevoegd');
		//jQuery('#notice').addClass('notice-success').show('slow').delay(7000).hide('slow');

		// Stuur email met specifiek Postmark template
		sendPostmarkEmail( 13937131, email, data.id );

		console.log('Nieuwe gebruiker toegevoegd.');
		console.log('textStatus: ' + textStatus);
		//console.dir(data);
        //console.dir(jqXHR);
        
        jQuery('#step-3').hide();
		jQuery('#step-4').show();
	})
	.fail(function(jqxhr) {
		var fail = JSON.parse(jqxhr.responseText);
		//console.log(fail.code);
		if (fail.code === 'existing_user_login') {
			// Hier is nog geen oplossing voor, beter is om eerder te checken of een gebruikersnaam al bestaat
			console.log('Gebruikersnaam bestaat!');
		}
		if (fail.code === 'existing_user_email') {
			// Voer updateExistingUser uit als gebruiker al bestaat
			console.log('Gebruiker met dit email adres bestaat al!');
		}
	})

}

// Stuur email naar gebruiker
function sendPostmarkEmail( template, email_address, user_id, participant_count ) {
	jQuery.ajax({
		url: '/wp-content/plugins/charitywallet-plugin/postmark-email-send.php',
		method: 'POST',
		data: {
			recipient: email_address,
			//naam_opleiding: group_name, 
			voornaam_deelnemer: firstname,
			achternaam_deelnemer: lastname,
			state: state,
			template: template,
			//aantal_deelnemers: participant_count,
			//klant: organisation,
			user_id: user_id
		},
		headers: [
			{ 
				"Content-Type": "application/json",
				"Accept": "application/json"
			}
		]
	})
	.done(function(data, textStatus, jqXHR) {
		userMetaMessageID( user_id, data );
		console.log('textStatus: ' + textStatus);
		console.log('Email ' + template + ' verstuurd.');
		console.log('data: ' + data);
		//console.log('jqXHR: ' + jqXHR);

	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log('textStatus: ' + textStatus);
		console.log('errorThrown: ' + errorThrown);
		//console.log('jqXHR: ' + jqXHR);
		alert('Er is iets misgegaan.');
	})
}

// Bewaar messageID verstuurde email
function userMetaMessageID( user_id, message_id ) {
	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/users/' +  user_id,
		method: 'POST',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
		},
		data: {
			user_id: user_id,
			sent_emails: message_id
		}
	})
	.done(function(post) {
		console.log('message_id: ' + message_id + ' opgeslagen voor ' + user_id );
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log('textStatus: ' + textStatus);
		console.log('errorThrown: ' + errorThrown);
		console.log('jqXHR: ' + jqXHR);
	})
}

// Check email status
function checkMessage(message_id, user_id) {
	if (message_id) {
		jQuery.ajax({
			url: '/wp-content/plugins/charitywallet-plugin/postmark-email-check.php',
			method: 'POST',
			pass_user_id: user_id,
			data: {
				message_id: message_id
			},
			headers: [
				{ 
					"Content-Type": "application/json",
					"Accept": "application/json",
				}
			]
		})
		.done(function(data, textStatus, jqXHR) {
			tdclass = '';
			if (jqXHR.responseText == 'opened') {
				tdclass = 'opened';
				status = 'De email is geopend.';
			} else if (jqXHR.responseText == 'bounced') {
				tdclass = 'bounced';
				the_group_id_onpage = jQuery('#group').val();
				status = 'Onjuist email adres. <span class="dashicons dashicons-trash" onclick="checkParticipant(' + this.pass_user_id + ', ' + the_group_id_onpage + ')"></span>';
			} else if (message_id == 'error') {
				tdclass = 'error';
				the_group_id_onpage = jQuery('#group').val();
				status = 'Email is niet verstuurd. <span class="dashicons dashicons-trash" onclick="checkParticipant(' + this.pass_user_id + ', ' + the_group_id_onpage + ')"></span>';
			}
			jQuery('tr[data-message-id=' + message_id + '] .email-address').addClass(tdclass);
			if (jQuery('tr[data-message-id=' + message_id + '] .status').html() == 'Groepsregistratie voltooid. ' ) {
				jQuery('tr[data-message-id=' + message_id + '] .status').append(status);
			} else {
				jQuery('tr[data-message-id=' + message_id + '] .status').html(status);
			}
			if (message_id != 'error') {
				console.log( message_id + ' ' + jqXHR.responseText);
			}
			console.log(data);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log('textStatus: ' + textStatus);
			console.log('errorThrown: ' + errorThrown);
			console.dir(jqXHR);
		})
	};
}
jQuery(document).ready(function() {
	aantal_deelnemers = 0;
	jQuery('table tbody').children('tr').each(function () {
		message_id = jQuery(this).data('message-id');
		user_id = jQuery(this).attr('id');
		checkMessage(message_id, user_id);

		// Volgnummer in tabel
		aantal_deelnemers = parseInt(jQuery('table tbody tr:nth-last-child(2) td:first').html());
		console.log('aantal_deelnemers: ' + aantal_deelnemers);
	});
});

// Controleer groep van gebruiker
function checkParticipant(user_id, group_id) {
	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/users/' + user_id,
		method: 'GET',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
		},
		pass_group_id: group_id,
		pass_user_id: user_id,
		data: {
			user_id: user_id,
		}
	})
	.done(function(data, textStatus, jqXHR) {
		if (jQuery.inArray(this.pass_group_id, data.group)) {
			var count = jQuery( data.group[0] ).length;
			if (count > 1 ) {
				alert('Deze gebruiker is al aan een andere groep gekoppeld en kan daarom niet worden verwijderd.');
			} else if (confirm('Weet u zeker dat u deze deelnemer wilt verwijderen?')) {
				deleteParticipant(this.pass_user_id, this.pass_group_id);
			}
		}
		
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log('textStatus: ' + textStatus);
		console.log('errorThrown: ' + errorThrown);
		console.dir('jqXHR: ' + jqXHR);
	})
}

// Verwijder gebruiker
function deleteParticipant(user_id, group_id) {
	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/users/' +  user_id,
		method: 'DELETE',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
		},
		data: {
			id: user_id,
			force: true,
			reassign: null
		}
	})
	.done(function(post) {
		console.log('Gebruiker verwijderd: ' + user_id);
		jQuery('#' + user_id).fadeToggle('slow');  
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log('textStatus: ' + textStatus);
		console.log('errorThrown: ' + errorThrown);
		console.log('jqXHR: ' + jqXHR);
	})
}