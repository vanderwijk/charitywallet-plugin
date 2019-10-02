jQuery(document).ready(function($) {

	$('#step-4').on('click', '.next', function() {
		$('#step-4').hide();
		$('#step-5').show();
	});

});

function updateUser( user_id ) {

	event.preventDefault();

	recurring = jQuery("input[name='recurring']:checked").val();
	amount = jQuery("input[name='amount']:checked").val();

	jQuery.ajax({
		url: WP_API_Settings.root + 'wp/v2/users/' +  user_id,
		method: 'POST',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
		},
		data: {
			user_id: user_id,
			wallet: [{recurring: recurring, amount:amount}]
		}
	})
	.done(function(post) {
		//console.log(post);
		console.dir(post.wallet);
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log('textStatus: ' + textStatus);
		console.log('errorThrown: ' + errorThrown);
		console.log('jqXHR: ' + jqXHR);
	})
}