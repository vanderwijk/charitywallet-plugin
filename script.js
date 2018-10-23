jQuery(document).ready(function ($) {
	$('#top-up-form').submit(function(event) {
		if ($('#issuer option:selected').val() == '') {
			$('#issuer').addClass('error');
			return false;
		}
	});

	$('#amount').focus(function () {
		$('input[name="top-up-amount"]').prop('checked', false);
	});

	$('input[name="top-up-amount"]').change(function () {
		$('#amount').val('');
		$('#amount').removeClass('error');
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

		if (amount == undefined) {
			$('#amount').addClass('error');
			$('#amount').focus();
			return false;
		} else {
			$('#amount').removeClass('error');
		}

		$('#pay-amount').html(amount);

		$('.step-1').hide();
		$('.step-2').show();

		console.log(amount);
	});

});