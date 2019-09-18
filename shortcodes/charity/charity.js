jQuery(document).ready(function($) {

	showCharity();

	$('#charity').on('click', '.remove', function() {
		charity = {};
		charity.id = $(this).parents('tr').attr('data-charity-id');
		removeFromCart(charity.id);
	});

});

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