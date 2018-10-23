jQuery(document).ready(function ($) {
/*	$('#top-up-form').submit(function(event) {
		event.preventDefault();
		window.location.href = "https://www.mollie.com/paymentscreen/issuer/select/ideal/DwSjwqCVy3";
	});*/

	$('#amount').focus(function () {
		$('input[name="top-up-amount"]').prop('checked', false);
	});

	$('input[name="top-up-amount"]').change(function () {
		$('#amount').val('');
	});

	$('#change-amount').click(function(event) {
		event.preventDefault();

		$('.step-2').hide();
		$('.step-1').show();
	});

	$('#next').click(function(event) {

		if ($('#amount').val() == '') {
			amount = $('input[name="top-up-amount"]:checked').val();
		} else {
			amount = $('#amount').val();
		}

		$('#pay-amount').html(amount);

		console.log(amount);
		$('.step-1').hide();
		$('.step-2').show();
	});

});