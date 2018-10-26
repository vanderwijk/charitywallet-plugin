jQuery(document).ready(function ($) {

	function validateAmount() {
		if (amount == undefined || amount == '') {
			$('#amount').addClass('error');
			$('#amount').focus();
		} else {
			$('#amount').removeClass('error');
			$('#amount').val(amount);
		}
	};

	$('#amount').keyup(function () {
		amount = $(this).val();
		validateAmount();
	});

	$('#amount').focus(function () {
		$('input[name="top-up-amount"]').prop('checked', false);
	});

	$('input[name="top-up-amount"]').change(function () {
		amount = $(this).val();
		validateAmount();
	});

	$('#next').click(function (e) {
		validateAmount();
		if ((amount != undefined) && (amount != '')) {
			$('#pay-amount').html(amount);
			$('.step-1').hide();
			$('.step-2').show();
		}
	});

	$('#change-amount').click(function (e) {
		e.preventDefault();
		$('.step-2').hide();
		$('.step-1').show();
	});

	$('#top-up-form').submit(function (e) {
		if ($('#issuer option:selected').val() == '') {
			$('#issuer').addClass('error');
			return false;
		}
	});

});

jQuery(function ($) {

	$('[data-popup-open]').on('click', function (e) {
		e.preventDefault();

		var targeted_popup_class = $(this).attr('data-popup-open');
		$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

		amount = $(this).attr('data-popup-amount');
		$('#amount').val(amount);

		$('input:radio').each(function () {
			var set_amount = $(this).val();
			if (set_amount < amount) {
				$(this).attr('disabled', true);
			}
		});

		monthly = $(this).attr('data-popup-monthly');
		if (monthly == 'monthly') {
			$('#monthly').prop('checked', true);
			$('.pretty').hide();
		}

	});

	$('[data-popup-close]').on('click', function (e) {
		e.preventDefault();

		var targeted_popup_class = jQuery(this).attr('data-popup-close');
		$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

	});

});