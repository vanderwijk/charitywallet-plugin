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

	$('#charity-basket').on( 'click', '.remove', function() {
		charityID = jQuery(this).attr('data-charity');
		removeFromCart(charityID);
	});

	$('#charity-basket').on( 'change', '.amount', function() {
		console.log( jQuery( this ).val() );
		// Update bedrag
		charity = {};
		charity.id = parseInt($(this).attr('name'));
		charity.amount = parseInt($(this).val());
		updateCart(charity);
	});

});

// Count the total number of charities in basket
function countCart(cart) {
	var n = cart.length;
	if (n > 0) {
		jQuery('#basket-count').html(n);
	} else {
		jQuery('#basket-count').html('');
	}
}

// Count the total donation amount
function totalCart(cart) {
	var total = 0;
	for (var x = 0; x < cart.length; x++) {
		total += cart[x].amount;
	}
	if (total > 0) {
		jQuery('#basket-total').html('(' + total + ')');
	} else {
		jQuery('#basket-total').html('');
	}
	console.log('Total: ' + total);
}

// Check which charities are in the cart and disable them
function checkCart() {
	var cart = JSON.parse(localStorage.getItem('cart'));
	var list = jQuery(".charity-list");
	if (cart.length > 0) {
		list.children('li').each(function() {
			jQuery(this).children( 'button' ).prop( "disabled", false );
			charityID = jQuery(this).children( 'button' ).attr('data-charity');
			for (var x = 0; x < cart.length; x++) {
				if (cart[x].id == charityID ) {
					jQuery(".charity-list button[data-charity='" + charityID +"']").prop( "disabled", true );
				}
			}
		})
	} else {
		list.children('li').each(function() {
			jQuery(this).children( 'button' ).prop( "disabled", false );
		})
	}
}

// Update donation amount
function updateCart(charity) {
	var cart = JSON.parse(localStorage.getItem('cart'));
	jQuery.each(cart, function() {
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
		var list = jQuery(".charity-basket");
		var parent = list.parent();
		
		list.empty().each(function(i) {
			for (var x = 0; x < cart.length; x++) {
				jQuery(this).append('<li>' + cart[x].name + '<input class="amount" type="number" name="' + cart[x].id + '" value="' + cart[x].amount + '"><button class="remove" data-charity="' + cart[x].id + '" data-amount="' + cart[x].amount + '" >' + chawa_donate.remove + '</button></li>');
				if (x == cart.length - 1) {
					jQuery(this).appendTo(parent);
				}
			}
		});
		countCart(cart);
		totalCart(cart);
		checkCart(cart);
	}
}

// Add charity to cart
function addToCart(charity) {
	if (localStorage && localStorage.getItem('cart')) {
		// Cart exists
		cart = localStorage.getItem('cart');
		console.log('Cart exists');
		console.log('Cart before: ' + cart);
		var cart = JSON.parse(localStorage.getItem('cart'));
		console.log('Charity: ' + JSON.stringify(charity));
		cart.push(charity);
		console.log('Cart after: ' + JSON.stringify(cart));
		localStorage.setItem('cart', JSON.stringify(cart));
	} else {
		// No cart exists
		console.log('No cart exists');
		cart = [];
		cart.push(charity);
		console.log('Cart after: ' + JSON.stringify(cart));
		localStorage.setItem('cart', JSON.stringify(cart));
	}
	showCart();
}

// Remove charity from cart
function removeFromCart(charityID) {
	if (localStorage && localStorage.getItem('cart')) {
		var cart = JSON.parse(localStorage.getItem('cart'));
		var filteredCart = cart.filter(item => item.id != charityID);
		localStorage.setItem('cart', JSON.stringify(filteredCart));
		showCart();
	}
}