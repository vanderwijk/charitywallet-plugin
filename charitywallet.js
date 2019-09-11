jQuery(document).ready(function($) {
    var cart = JSON.parse(localStorage.getItem('cart'));
    jQuery('#site-navigation li:nth-last-child(3) a').append(' <sup></sup>');
    
    totalCart(cart);
});

// Update cart meta
function updateCartMeta(recurring) {
	var cart = JSON.parse(localStorage.getItem('cart'));
	cart.meta.recurring = recurring;
	localStorage.setItem('cart', JSON.stringify(cart));
	showCart();
}

// Count the total number of charities in basket
function countCart(cart) {
	var n = cart.length;
	if (n > 0) {
		jQuery('#basket-count').html(n);
	} else {
		jQuery('#basket-count').html('');
	}
}

// Show cart date
function dateCart(cart) {
	var timestamp = cart.meta.date;
	var date = new Date(timestamp);
	var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
	var cartDate = date.toLocaleDateString('nl-NL', options);

	if (cartDate) {
		jQuery('#basket-date').html( chawa_localize.date + ' ' + cartDate);
	} else {
		jQuery('#basket-date').html('');
	}
}

// Count the total donation amount
function totalCart(cart) {
	var total = 0;
	for (var x = 0; x < cart.charities.length; x++) {
		total += cart.charities[x].amount;
	}
	if (total > 0) {
        jQuery('#basket-total').html('€' + total + ',00');
        jQuery('#site-navigation li:nth-last-child(3) a sup').html('€' + total + ',00');

	} else {
		jQuery('#basket-total').html('');
	}
	console.log('Total: ' + total);
}

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

		var cart = JSON.parse(localStorage.getItem('cart'));
		var list = jQuery('.charity-basket');
		var parent = list.parent();
		
		list.empty().each(function(i) {
			for (var x = 0; x < cart.charities.length; x++) {
				jQuery(this).append('<tr data-charity-id="' + cart.charities[x].id + '"><td>' + cart.charities[x].name + '</td><td><span class="amount-wrap"><span class="plus">+</span><span class="value">€<span class="the-value">' + cart.charities[x].amount + '</span>,-</span><span class="minus">-</span></span><input class="amount" type="number" step="1" name="' + cart.charities[x].id + '" value="' + cart.charities[x].amount + '"></td><td><button class="remove" data-charity="' + cart.charities[x].id + '" data-amount="' + cart.charities[x].amount + '" >' + chawa_localize.remove + '</button></td></tr>');
				if (x == cart.length - 1) {
					jQuery(this).appendTo(parent);
				}
			}
		});
		countCart(cart);
		totalCart(cart);
		checkCart(cart);
		dateCart(cart);

		jQuery("#basket-recurring").prop( "checked", cart.meta.recurring );

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