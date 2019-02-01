jQuery(document).ready(function($) {

	showCart();
	
	$('.donate').click(function(e) {
		//debugger;
		charity = {};
		charity.id = $(this).attr('data-charity');
		charity.amount = $(this).attr('data-amount');
		charity.name = $(this).attr('data-name');
		addToCart(charity);
	});

});

// Check welke items in het mandje zitten
function checkCart() {

}

// Pas bedrag donatie aan
// Als input waarde van amount wijzigt automatisch aanpassen
// PROBLEEM: HET LIJKT ALSOF DE CHANGE NIET WERKT OMDAT DE INPUT NIET ECHT OP DE PAGINA STAAT
// https://stackoverflow.com/questions/15090942/event-handler-not-working-on-dynamic-content
// DIT GELDT OOK VOOR KLIKKEN OP .remove
// Oplossing:
jQuery( '#charity-basket' ).on( 'click', '.amount', function() {
	//console.log( jQuery( this ).text() );
	// Update bedrag
});


function showCart() {
	if (localStorage && localStorage.getItem('cart')) {
		var cart = JSON.parse(localStorage.getItem('cart'));
		var list = jQuery(".charity-basket");
		var parent = list.parent();
		
		list.empty().each(function(i) {
			for (var x = 0; x < cart.length; x++) {
				jQuery(this).append('<li>' + cart[x].name + '<input class="amount" type="number" value="' + cart[x].amount + '"><button class="remove" data-charity="' + cart[x].id + '" data-amount="' + cart[x].amount + '" onClick="removeFromCart(' + cart[x].id + ');">' + chawa_donate.remove + '</button></li>');
				if (x == cart.length - 1) {
					jQuery(this).appendTo(parent);
				}
			}
		});
	}
}

function addToCart(charity) {
	if (localStorage && localStorage.getItem('cart')) {
		var cart = JSON.parse(localStorage.getItem('cart'));
		cart.push(charity);
		localStorage.setItem('cart', JSON.stringify(cart));
	} else {
		cart = [];
		cart.push(charity);
		localStorage.setItem('cart', JSON.stringify(cart));
	}
	showCart();
}

function removeFromCart(charity) {
	if (localStorage && localStorage.getItem('cart')) {
		var cart = JSON.parse(localStorage.getItem('cart'));
		jQuery.map(cart, function(value, key) {
			if ( value.id == charity ) {
				cart.splice(key,1);
				localStorage.setItem('cart', JSON.stringify(cart));
			}
		});
		showCart();
	}
}