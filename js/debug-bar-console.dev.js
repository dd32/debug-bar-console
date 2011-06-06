(function($) {

var el = {},
	iframe = {},
	run, mode, nonce;

run = function() {
	var input = mode.inputs[ mode.mode ];

	if ( ! input )
		return;

	$.post( ajaxurl, {
		action: 'debug_bar_console',
		mode:   mode.mode,
		data:   input.val(),
		nonce:  nonce
	}, function( data ) {
		iframe.body.html( data );
	});
	return false;
};


mode =  {
	mode: 'php',
	tabs: {},
	inputs: {},
	change: function( to ) {
		if ( to == mode.mode )
			return;

		if ( mode.tabs[ mode.mode ] )
			mode.tabs[ mode.mode ].removeClass( 'debug-bar-console-tab-active' );

		el.form.removeClass( 'debug-bar-console-mode-' + mode.mode );
		el.form.addClass( 'debug-bar-console-mode-' + to );
		mode.mode = to;

		if ( mode.tabs[ mode.mode ] )
			mode.tabs[ mode.mode ].addClass( 'debug-bar-console-tab-active' );
	}
};

$(document).ready( function(){
	// Generate elements
	$.extend( el, {
		form:   $('#debug-bar-console'),
		submit: $('#debug-bar-console-submit'),
		output: $('#debug-bar-console-output')
	});

	nonce = $('#_wpnonce_debug_bar_console').val();

	iframe.css = $('#debug-bar-console-iframe-css').val();

	// Generate iframe variables
	iframe.container = $('iframe', el.output);
	iframe.contents  = iframe.container.contents();
	iframe.document  = iframe.contents[0];
	iframe.body      = $( iframe.document.body );

	// Add CSS to iframe
	$('head', iframe.contents).append('<link type="text/css" href="' + iframe.css + '" rel="stylesheet" />');

	// Bind run click handler
	el.submit.click( run );

	// Bind shift+enter keyboard shortcut
	$('.debug-bar-console-input').keydown( function( event ) {
		if ( event.which == 13 && event.shiftKey ) {
			run();
			event.preventDefault();
		}
	});

	// Bind tab switching
	$('.debug-bar-console-tab').each( function() {
		var t = $(this),
			slug = t.find('input').val(),
			input;

		if ( slug ) {
			mode.tabs[ slug ] = t;
			t.data( 'console-tab', slug );
			input = $( '#debug-bar-console-input-' + slug );
			if ( input.length )
				mode.inputs[ slug ] = input;
		}

	}).click( function() {
		mode.change( $(this).data( 'console-tab' ) );
	});
});


})(jQuery);