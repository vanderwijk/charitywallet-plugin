jQuery(function ($) {
	$('#account-edit').parsley().on('form:submit', function() {

		$('#account-edit input[type="submit"]').addClass('saving');
		$('#account-edit input[type="submit"]').attr('disabled', true);

		formData = jQuery( '#account-edit' ).serializeArray();

		// get fields from formData array
		jQuery(formData).each(function(i, field){
			formData[field.name] = field.value;
		});

		updateUser(formData);

		return false; // don't submit form

	});
});

function updateUser(formData) {

	// use rest api to update the user profile
	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/users/' + formData['uid'],
		method: 'POST',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
			console.log('Updating user.');
		},
		data: {
			first_name: formData['firstname'],
			last_name: formData['lastname'],
			user_email: formData['email'],
			user_phone: formData['phone'],
			user_address_street: formData['user_address_street'],
			user_address_postcode: formData['user_address_postcode'],
			user_address_city: formData['user_address_city'],
			user_address_country: formData['user_address_country']
		}
	})
	.done(function(data, textStatus, jqXHR) {
		console.log('User updated.');
		//console.log(textStatus);
		//console.dir(data);
		//console.dir(jqXHR);

		jQuery('#account-edit input[type="submit"]').removeClass('saving');
		jQuery('#account-edit input[type="submit"]').attr('disabled', false);
	})
	.fail(function(jqxhr) {
		var fail = JSON.parse(jqxhr.responseText);
		//console.log(fail.code);
	})

}