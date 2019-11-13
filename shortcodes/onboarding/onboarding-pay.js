jQuery(document).ready(function($) {

	$('#step-6').on('click', '.next', function() {
		$('#step-6').hide();
		$('#step-7').show();
	});

});

function pay( user_id ) {

	bank = jQuery('select[name=bank]').val();
	accept = jQuery('input[name=accept]:checked').val();

	// Validation
	jQuery('#notice').removeClass('error');
	if (bank == '') {
		event.preventDefault();
		console.log('Geen bank');
		jQuery('#notice').html( chawa_localize_onboarding.choose_bank ).addClass('error');;
	} 
	if (!accept) {
		event.preventDefault();
		console.log('Voorwaarden niet geaccepteerd');
		jQuery('#notice').html( chawa_localize_onboarding.accept ).addClass('error');;
	}

}