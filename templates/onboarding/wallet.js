jQuery(document).ready(function($) {

	// Clearing inputs based on selection
	$("input[name='amountCustom']").on("change paste keyup", function() {
		$('input[name="amount"]').prop('checked', false);
	})
	$("input[name='amount']").on("change paste keyup", function() {
		$('input[name="amountCustom"]').val('');
	})

	$('#step-4').on('click', '.next', function() {
		$('#step-4').hide();
		$('#step-5').show();
	});

});

function updateUser( user_id ) {

	event.preventDefault();

	recurring = jQuery("input[name='recurring']:checked").val();
	amount = jQuery("input[name='amount']:checked").val();
	amountCustom = jQuery("input[name='amountCustom']").val();
	if ( !amountCustom ) {
		amount = amount;
	} else {
		amount = amountCustom;
	}

	// Validation
	jQuery('#notice').removeClass('error');
	if ( !amount ) {
		console.log('Geen bedrag');
		jQuery('#notice').html( chawa_localize_onboarding.choose_amount ).addClass('error');
	} else if (!recurring) {
		console.log('Geen frequentie');
		jQuery('#notice').html( chawa_localize_onboarding.choose_frequency ).addClass('error');;
	} else {
		jQuery.ajax({
			url: WP_API_Settings.root + 'wp/v2/users/' +  user_id,
			method: 'POST',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
			},
			data: {
				user_id: user_id,
				wallet: [{recurring: recurring, amount:amount}]
			}
		})
		.done(function(post) {
			console.dir(post.wallet);
			// Redirect naar volgende stap
			window.location.href = '/onboarding/pay/';
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log('textStatus: ' + textStatus);
			console.log('errorThrown: ' + errorThrown);
			console.log('jqXHR: ' + jqXHR);
		})
	}
}