jQuery(document).ready(function($) {

	$('#step-1').on('click', '.next', function() {
		$('#step-1').hide();
		$('#step-2').show();
	});

	$('#step-2').on('click', '.next', function() {
		$('#step-2').hide();
		$('#step-3').show();
	});
	
});