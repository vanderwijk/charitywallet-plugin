jQuery(document).ready(function($) { 

	if (localStorage.getItem('cart') != null) {
		var items = JSON.parse(localStorage.getItem('cart')).items;
		for(var i=0; i<items.length; i++) {
			addItem(items[i]);
			$('#' + items[i]).toggleClass('btn-warning');
			toggleText(items[i]);
			cart.items.push(items[i]);
		}
		setBadge();
	}

	$('.donate').click(function(e) {
		$(this).toggleClass('active');
		setLocalStorage($(this).attr('data-charity'), 0);
		$('.donate.active').on('click', function() {
			setLocalStorage($(this).attr('data-charity'), 1);
		});
		console.log($(this).attr('data-charity'));
	});

	$('.btn-danger').on('click', function() {
		removeItem($(this).parent('li'));
		setLocalStorage($(this).data('id'), 1);
	});
});

function removeItem(item) {
	console.log(item);
	var id = item[0].attributes[1].value;
	jQuery(`#${id}`).removeClass('btn-warning');
	toggleText(id);
	item.remove();
	setBadge();
}

function addItem(item) {
	jQuery('ul.basket').append(
		`<li class='well' data-attribute='${item}'>
			${jQuery(`.${item}-container p`).text()} 
			<button class='btn btn-danger' data-id='${item}'>-</button>
		</li>`
	);
}

function setBadge() {
	jQuery('.badge').remove();
}

function toggleText(item) {
	if (jQuery('#' + item).hasClass('btn-warning')) {
		jQuery('#' + item).text('-');
	} else {
		jQuery('#' + item).text('+');
	}
}

var cart = {};
cart.items = [];

function setLocalStorage(id, flag) {
	if (flag) {
		cart.items.splice(cart.items.indexOf(id), 1);
	} else {
		cart.items.push(id);
	}
	console.log(JSON.stringify(cart));
	localStorage.setItem('cart', JSON.stringify(cart));
}