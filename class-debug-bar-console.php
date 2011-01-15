<?php

class Debug_Bar_Console extends Debug_Bar_Panel {
	function init() {
		$this->title( 'Console' );
		add_action( 'wp_ajax_debug_bar_console', array( &$this, 'ajax' ) );
	}

	function prerender() {
		$this->set_visible( true );
	}

	function render() {
		wp_nonce_field( 'debug_bar_console', '_wpnonce_debug_bar_console' );
		?>
		<div id="debug-bar-console-wrap" class="debug-bar-console">
			<textarea id="debug-bar-console-input" class="debug-bar-console"></textarea>
		</div>
		<div id="debug-bar-console-output" class="debug-bar-console"></div>
		<a href="#" id="debug-bar-console-submit"><?php _e('Run'); ?></a>
		<?php
	}

	function ajax() {
		global $wpdb;

		check_admin_referer( 'debug_bar_console', 'nonce' );

		if ( ! is_super_admin() )
			die();

		$data = stripslashes( $_POST['data'] );
		if ( preg_match( '/^SELECT/i', $data ) )
			print_r( $wpdb->get_results( $data ) );
		else
			eval( $data );

		die();
	}
}

?>