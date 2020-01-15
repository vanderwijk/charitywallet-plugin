jQuery(document).ready(function($) {

	createWallet();

	// Empty <sup> element to show total cart 
	//jQuery('#site-navigation li:nth-last-child(3) a').append(' <sup></sup>');
	//jQuery('#site-navigation li:nth-child(2) a').append(' <sup></sup>');

	// Show cart total in navigation
	//totalCart();

	// Show wallet total in navigation
	//totalWallet();

	// Remove button on basket page
	$('#charity-basket').on('click', '.remove', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		removeFromCart(charity.id);
		showCart();
	});

	// Donate button on single charity page
	$('.donate').click(function() {
		charity = {};
		charity.id = parseInt($(this).attr('data-charity'));
		charity.amount = parseInt($(this).attr('data-amount'));
		charity.name = $(this).attr('data-name');

		// Check if wallet exists and find balance
		if (localStorage && localStorage.getItem('wallet')) {
			var wallet = JSON.parse(localStorage.getItem('wallet'));
			balance = wallet.balance;

			// Check if cart exists and calculate total
			if (localStorage && localStorage.getItem('cart')) {
				var cart = JSON.parse(localStorage.getItem('cart'));
				var total = 0;
				for (var x = 0; x < cart.charities.length; x++) {
					total += cart.charities[x].amount;
				}
			}

			donations = total + charity.amount;
			if ( balance < donations ) {
				alert('Je hebt onvoldoende wallet tegoed');
			} else {
				addToCart(charity);
				showMessage();
			}

		}

	});

	// Minus button on single charity page
	$('#charity').on('click', '.minus', function(e) {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		amount = $('button[data-charity="' + charity.id + '"]').attr('data-amount');
		charity.amount = parseFloat(amount) - 1;
		if ( charity.amount < 1 ) {
			charity.amount = 1;
		}

		$('button[data-charity="' + charity.id + '"]').attr('data-amount', charity.amount);
		$('.the-value').html(charity.amount);
	});

	$('#charity').on('click', '.plus', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		amount = $('button[data-charity="' + charity.id + '"]').attr('data-amount');
		charity.amount = parseFloat(amount) + 1;

		$('button[data-charity="' + charity.id + '"]').attr('data-amount', charity.amount);
		$('.the-value').html(charity.amount);
	});

});

// Show message on page
function showMessage() {
	jQuery('#notification').delay(500).fadeIn('slow').delay(5000).fadeOut('fast');
}

// Show cart contents on single charity page
function showCharity() {
	if (localStorage && localStorage.getItem('cart')) {
		var cart = JSON.parse(localStorage.getItem('cart'));
		for (var x = 0; x < cart.charities.length; x++) {
			charityid = jQuery('#charity tr').attr('data-charity-id');
			if (cart.charities[x].id == charityid) {
				jQuery('#the-value').html(cart.charities[x].amount);
				jQuery('button[data-charity="' + cart.charities[x].id + '"]').attr('data-amount', cart.charities[x].amount);
			}
		}
	}
}

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

// Count the total donation amount and show in main navigation
function totalCart() {
	if (localStorage && localStorage.getItem('cart')) {
		var cart = JSON.parse(localStorage.getItem('cart'));
		var total = 0;
		for (var x = 0; x < cart.charities.length; x++) {
			total += cart.charities[x].amount;
		}
		if (total > 0) {
			jQuery('#basket-total').html('€' + total + ',-');
			jQuery('#site-navigation li:nth-last-child(3) a sup').html('€' + total + ',-');
		} else {
			jQuery('#site-navigation li:nth-last-child(3) a sup').html('');
		}
	}
}

// Check the wallet balance and show in main navigation
function totalWallet() {
	if (localStorage && localStorage.getItem('wallet')) {
		var wallet = JSON.parse(localStorage.getItem('wallet'));
		balance = wallet.balance;
		if ( wallet.recurring == true ) {
			recurring = '<span class="dashicons dashicons-update"></span>';
		} else {
			recurring = '';
		}

		if (balance > 0) {
			jQuery('#basket-total').html('€' + balance + ',-');
			jQuery('#site-navigation li:nth-child(2) a sup').html('€' + balance + ',-' + recurring);
		} else {
			jQuery('#site-navigation li:nth-child(2) a sup').html('');
		}
	}
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
		var list = jQuery('.charity-basket tbody');
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

	} else {
		console.log ('leeg');
		jQuery("#charity-basket").html('<tr><td>' + chawa_localize.cart_is_empty + '</td></tr>');
	}
}


// Add charity to cart
function addToCart(charity) {
	if (localStorage && localStorage.getItem('cart')) {
		// Cart exists
		var cart = JSON.parse(localStorage.getItem('cart'));
		
		// Check if charity is already in cart
		var charities = cart.charities;
		if (charities && charities.length > 0) {
			jQuery.each(charities, function() {
				if (this.id == charity.id) {
					this.amount = this.amount + charity.amount;
				} else {
					cart.charities.push(charity);
				}
			});
		} else {
			cart.charities.push(charity);
		}

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
		//showCart();
	}
}

function createWallet() {
	var wallet = {};
	wallet.meta = {};
	wallet.balance = 12;
	wallet.recurring = false;
	wallet.meta.date = Date.now();
	//console.log('Cart after: ' + JSON.stringify(cart));
	localStorage.setItem('wallet', JSON.stringify(wallet));
}