<?php
/**
 * Plugin name: Page Template Select2
 * Description: adds a select2 selector for page template instead of basic dropdown menu
 * Author: Julien Maury
 * Version: 2.0
 */

defined( 'ABSPATH' )
or die( '~Tryin' );

define ( 'PTS_VERSION', '2.0' );
define ( 'PTS_URL', plugin_dir_url( __FILE__ ) );

add_action( 'admin_enqueue_scripts', '_pts_enqueue_scripts' );
function _pts_enqueue_scripts( $hook_suffix ) {

	if ( ! _pts_is_edit_screen( $hook_suffix ) ) {
		return false;
	}

	if ( ! _pts_is_wp_version_greater_than() ) {
		return false; // no support for wp version under 5 beta
	}

	if ( ! _pts_gutenberg_is_enabled() ) {
		_pts_maybe_grab_select2_scripts();
		$init = "(function($) { $('#page_template').select2(); })(jQuery);";
		wp_add_inline_script( 'select2', $init );
	}
}

function _pts_maybe_grab_select2_scripts() {
	if ( ! wp_script_is( 'select2' ) ) {
		wp_register_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', [ 'jquery' ], '4.0.13', true );
		wp_enqueue_script( 'select2' );
	}

	if ( ! wp_style_is( 'select2' ) ) {
		wp_register_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', false, '4.0.13', 'all' );
		wp_enqueue_style( 'select2' );
	}
}

function _pts_is_edit_screen( $slug ) {
	return (bool) apply_filters( 'pts_is_edit_screen', in_array( $slug, [
			'post.php',
			'post-new.php'
		], true ) );
}

function _pts_is_wp_version_greater_than( $version = '5.0-beta' ) {
	global $wp_version;
	return version_compare( $wp_version, $version, '>' );
}

function _pts_gutenberg_is_enabled() {
	return (bool) apply_filters( 'pts_is_gutenberg_enabled', ! class_exists( 'Classic_Editor' ) && ! class_exists( 'DisableGutenberg') );
}