jQuery(document).ready(function($) {

	// select
	$("#charity-select").select2({
		placeholder: "Kies je goede doel",
		allowClear: true
	});

	$('#charity-select').on('select2:select', function (e) {
		var data = e.params.data;
		console.log(data);
	});

	showCart();
	
	$('.donate').click(function() {
		//debugger;
		charity = {};
		charity.id = parseInt($(this).attr('data-charity'));
		charity.amount = parseInt($(this).attr('data-amount'));
		charity.name = $(this).attr('data-name');
		addToCart(charity);
	});

	/*	$('#charity-basket').on('click', '.remove', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		removeFromCart(charity.id);
	});*/

	// Recurring monthly?
	$('#basket-recurring').change(function() {
		if ( $(this).is(":checked") ) {
			// Check if wallet has recurring payment
			if (localStorage && localStorage.getItem('wallet')) {
				var wallet = JSON.parse(localStorage.getItem('wallet'));
				recurring = wallet.recurring;
				if (recurring == true) {
					updateCartMeta(true);
				} else {
					alert('je moet automatisch opwaarderen inschakelen');
					$(this).removeAttr('checked');
				}
			}
		} else {
			updateCartMeta(false);
		}
	});

	$('#charity-basket').on('click', '.minus', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		amount = $('[name=' + charity.id + ']').val();
		charity.amount = parseFloat(amount) - 1;
		if ( charity.amount > 0 ) {
			updateCart(charity);
		}
	});

	$('#charity-basket').on('click', '.plus', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		amount = $('[name=' + charity.id + ']').val();
		charity.amount = parseFloat(amount) + 1;
		updateCart(charity);
	});

	$('#charity-basket').on('change', '.amount', function() {
		//console.log( jQuery( this ).val() );
		charity = {};
		charity.id = parseInt($(this).attr('name'));
		charity.amount = parseInt($(this).val());
		updateCart(charity);
	});

});
