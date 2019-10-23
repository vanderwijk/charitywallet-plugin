jQuery(document).ready(function($) {

	$('#step-6').on('click', '.next', function() {
		$('#step-6').hide();
		$('#step-7').show();
	});

	$("input[name='payment-type']").on("change paste keyup", function() {
		payment_type = jQuery("input[name='payment-type']:checked").val();
		if ( payment_type === 'ideal' ) {
			jQuery('.bank').toggleClass('up');
		} else {
			jQuery('.bank').toggleClass('up');
		}
	})

});

function pay( user_id ) {

	payment_type = jQuery("input[name='payment-type']:checked").val();
	bank = jQuery("#issuer :selected").val();
	accept = jQuery("input[name='accept']:checked").val();

	// Validation
	jQuery('#notice').removeClass('error');
	if ( !payment_type ) {
		event.preventDefault();
		console.log('Geen betaalmethode');
		jQuery('#notice').html( chawa_localize_onboarding.choose_payment_type ).addClass('error');
	} else if (!bank) {
		event.preventDefault();
		console.log('Geen bank');
		jQuery('#notice').html( chawa_localize_onboarding.choose_bank ).addClass('error');;
	} else if (!accept) {
		event.preventDefault();
		console.log('Geen bank');
		jQuery('#notice').html( chawa_localize_onboarding.accept ).addClass('error');;
	}

}