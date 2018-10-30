jQuery(function ($) {

	function validateAmount() {
		if ( (amount == undefined) || (amount == '') || (isNaN(amount)) ) {
			$('#amount').addClass('error');
			$('.notice').html(chawa.choose_amount);
			$('#amount').focus();
			amountStatus = 'invalid';
		} else {
			if ( amount < donation ) {
				$('#amount').addClass('error');
				$('#amount').focus();
				$('.notice').html(chawa.top_up_amount_too_low);
				amountStatus = 'invalid';
			} else {
				$('#amount').removeClass('error');
				$('.notice').html('');
				amountStatus = 'valid';
			}
		}
	};

	$("#amount").on("change keyup paste", function(){
		$('input[name="top-up-amount"]').prop('checked', false);
		amount = parseInt($(this).val());
		validateAmount();
	});

	$('input[name="top-up-amount"]').change(function () {
		amount = parseInt($(this).val());
		$('#amount').val(amount);
		validateAmount();
	});

	$('#next').click(function (e) {
		validateAmount();
		if ( amountStatus != 'invalid' ) {
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

	$('[data-popup-open]').on('click', function (e) {
		e.preventDefault();

		donation = $(this).attr('data-popup-donation');
		monthly = $(this).attr('data-popup-monthly');

		var targeted_popup_class = $(this).attr('data-popup-open');

		$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

		$('#amount').val(donation);

		$('input:radio').each(function () {
			var set_amount = $(this).val();
			if (set_amount < donation) {
				$(this).attr('disabled', true);
			}
		});

		if (monthly == 'monthly') {
			$('#monthly').prop('checked', true);
			$('.pretty').hide();
			$('#monthly-text').show();
		}

	});

	$('[data-popup-close]').on('click', function (e) {
		e.preventDefault();

		var targeted_popup_class = jQuery(this).attr('data-popup-close');
		$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

	});

});