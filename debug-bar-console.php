<?php
/*
 Plugin Name: Debug Bar Console
 Plugin URI: http://wordpress.org/extend/plugins/debug-bar-console/
 Description: Adds a PHP/MySQL console to the debug bar. Requires the debug bar plugin.
 Author: koopersmith
 Version: 0.3-alpha
 Author URI: http://darylkoop.com/
 */

add_filter('debug_bar_panels', 'debug_bar_console_panel');
function debug_bar_console_panel( $panels ) {
	require_once 'class-debug-bar-console.php';
	$panels[] = new Debug_Bar_Console();
	return $panels;
}

add_action('debug_bar_enqueue_scripts', 'debug_bar_console_scripts');
function debug_bar_console_scripts() {
	$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';

	$php_recs = array( 'codemirror', 'codemirror-css', 'codemirror-javascript', 'codemirror-xml', 'codemirror-clike' );
	$css_recs = array_merge( array( 'debug-bar', 'codemirror-plsql' ), $php_recs );
	$js_recs  = array( 'debug-bar', 'codemirror-plsql', 'codemirror-php' );

	wp_enqueue_style( 'debug-bar-console', plugins_url( "css/debug-bar-console$suffix.css", __FILE__ ), $css_recs, '20110606b' );
	wp_enqueue_script( 'debug-bar-console', plugins_url( "js/debug-bar-console$suffix.js", __FILE__ ), $js_recs, '20110606b' );


	// Codemirror
	wp_enqueue_style( 'codemirror', plugins_url( "codemirror/lib/codemirror.css", __FILE__ ), array(), '2.01' );
	wp_enqueue_script( 'codemirror', plugins_url( "codemirror/lib/codemirror.js", __FILE__ ), array(), '2.01' );

	$modes = array( 'clike', 'css', 'javascript', 'plsql', 'xml' );

	foreach ( $modes as $mode ) {
		wp_enqueue_style( "codemirror-$mode", plugins_url( "codemirror/mode/$mode/$mode.css", __FILE__ ), array(), '2.01' );
		wp_enqueue_script( "codemirror-$mode", plugins_url( "codemirror/mode/$mode/$mode.js", __FILE__ ), array('codemirror'), '2.01' );
	}

	wp_enqueue_script( "codemirror-php", plugins_url( "codemirror/mode/php/php.js", __FILE__ ), $php_recs, '2.01' );

}

?>