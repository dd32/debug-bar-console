jQuery( function($) {
	var submit = $('#debug-bar-console-submit'),
		input = $('#debug-bar-console-input'),
		output = $('#debug-bar-console-output'),
		nonce = $('#_wpnonce_debug_bar_console').val();

	submit.click( function(){
		$.post( ajaxurl, {
			action: 'debug_bar_console',
			data:   input.val(),
			nonce:  nonce
		}, function( data ) {
			output.text( data );
		});
		return false;
	});

	input.keydown( function( event ) {
		if ( event.which == 13 && event.shiftKey ) {
			submit.click();
			event.preventDefault();
		}
	});
});