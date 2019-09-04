jQuery(document).ready(function($) {

	showCart();
	
	$('.donate').click(function() {
		//debugger;
		charity = {};
		charity.id = parseInt($(this).attr('data-charity'));
		charity.amount = parseInt($(this).attr('data-amount'));
		charity.name = $(this).attr('data-name');
		addToCart(charity);
	});

	$('#charity-basket').on('click', '.remove', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		removeFromCart(charity.id);
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

// Check which charities are in the cart and disable them
function checkCart() {
	var cart = JSON.parse(localStorage.getItem('cart'));
	var charities = cart.charities;
	var list = jQuery('#charity-list > tbody');
	if (charities.length > 0) {
		list.children('tr').each(function() {
			jQuery(this).find('td:last button').prop('disabled', false );
			charityID = jQuery(this).find('td:last button').attr('data-charity');
			for (var x = 0; x < charities.length; x++) {
				if (charities[x].id == charityID ) {
					jQuery('.charity-list button[data-charity="' + charityID + '"]').prop('disabled', true );
				}
			}
		})
	} else {
		list.children('tr').each(function() {
			jQuery(this).find('td:last button').prop('disabled', false );
		})
	}
}

// Update donation amount
function updateCart(charity) {
	var cart = JSON.parse(localStorage.getItem('cart'));
	var charities = cart.charities;
	jQuery.each(charities, function() {
		if (this.id == charity.id) {
			this.amount = charity.amount;
		}
	});
	localStorage.setItem('cart', JSON.stringify(cart));
	showCart();
}

// Show cart contents
function showCart() {
	if (localStorage && localStorage.getItem('cart')) {

		// REMOVE THIS ON WORDPRESS
		var chawa_donate = {};
		chawa_donate.remove = 'Verwijderen';
		// END REMOVE THIS ON WORDPRESS

		var cart = JSON.parse(localStorage.getItem('cart'));
		var list = jQuery('.charity-basket');
		var parent = list.parent();
		
		list.empty().each(function(i) {
			for (var x = 0; x < cart.charities.length; x++) {
				if (cart.charities[x].id === 159 ) {
					jQuery(this).append('<tr data-charity-id="' + cart.charities[x].id + '"><td>' + cart.charities[x].name + '</td><td><span class="amount-wrap"><span class="plus">+</span><span class="value">€<span class="the-value">' + cart.charities[x].amount + '</span>,-</span><span class="minus">-</span></span><input class="amount" type="number" step="1" name="' + cart.charities[x].id + '" value="' + cart.charities[x].amount + '"></td><td><button class="remove" data-charity="' + cart.charities[x].id + '" data-amount="' + cart.charities[x].amount + '" >' + chawa_donate.remove + '</button></td></tr>');
					if (x == cart.length - 1) {
						jQuery(this).appendTo(parent);
					}
				}
			}
		});
		checkCart(cart);

	}
}

// Add charity to cart
function addToCart(charity) {
	if (localStorage && localStorage.getItem('cart')) {
		// Cart exists
		cart = localStorage.getItem('cart');
		//console.log('Cart exists');
		//console.log('Cart before: ' + cart);
		var cart = JSON.parse(localStorage.getItem('cart'));
		//console.log('Charity: ' + JSON.stringify(charity));
		cart.charities.push(charity);
		//console.log('Cart after: ' + JSON.stringify(cart));
		localStorage.setItem('cart', JSON.stringify(cart));
	} else {
		// No cart exists
		//console.log('No cart exists');
		var cart = {};
		cart.meta = {};
		cart.charities = [];
		cart.charities.push(charity);
		cart.meta.date = Date.now();
		//console.log('Cart after: ' + JSON.stringify(cart));
		localStorage.setItem('cart', JSON.stringify(cart));
	}
	showCart();
}

// Remove charity from cart
function removeFromCart(charityID) {
	if (localStorage && localStorage.getItem('cart')) {
		var cart = JSON.parse(localStorage.getItem('cart'));
		var charities = cart.charities;
		var filteredCart = charities.filter(item => item.id != charityID);
		cart.charities = filteredCart;
		localStorage.setItem('cart', JSON.stringify(cart));
		showCart();
	}
}