jQuery(document).ready(function($) {

	showCharity();
	
	$('.donate').click(function() {
		//debugger;
		charity = {};
		charity.id = parseInt($(this).attr('data-charity'));
		charity.amount = parseInt($(this).attr('data-amount'));
		charity.name = $(this).attr('data-name');
		addToCart(charity);
	});

	$('#charity').on('click', '.remove', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		removeFromCart(charity.id);
	});

	$('#charity').on('click', '.minus', function(e) {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		amount = $('button[data-charity="' + charity.id + '"]').attr('data-amount');
		charity.amount = parseFloat(amount) - 1;

		if ( charity.amount < 1 ) {
			var r = confirm(chawa_localize_charity.are_you_sure);
			if (r == true) {
				console.log('delete');
			} else {
				//stopImmediatePropagation();
				charity.amount = 1;
 
			}

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

// Show cart contents
function showCharity() {
	if (localStorage && localStorage.getItem('cart')) {

		var cart = JSON.parse(localStorage.getItem('cart'));
		for (var x = 0; x < cart.charities.length; x++) {
			if (cart.charities[x].id === 159 ) {
				jQuery('#the-value').html(cart.charities[x].amount);
				jQuery('button[data-charity="' + cart.charities[x].id + '"]').attr('data-amount', cart.charities[x].amount);
			}
		}

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
}

// Add charity to cart
function addToCart(charity) {
	if (localStorage && localStorage.getItem('cart')) {
		// Cart exists
		cart = localStorage.getItem('cart');
		var cart = JSON.parse(localStorage.getItem('cart'));
		
		// Check if charity is already in cart
		var charities = cart.charities;
		jQuery.each(charities, function() {
			if (this.id == charity.id) {
				this.amount = charity.amount;
			} else {
				cart.charities.push(charity);
			}
		});
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
//	showCart();
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