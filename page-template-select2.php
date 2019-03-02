<?php
/**
 * Plugin name: Page Template Select2
 * Description: adds a select2 selector for page template instead of basic dropdown menu
 * Author: Julien Maury
 * Version: 1.0
 */


/**
 * Dislaimer : for now it's meant only for pages not all template selectors in WordPress
 * TODO : Compat Gutenberg, i18n
 */
defined( 'ABSPATH' )
or die( '~Tryin' );

add_action( 'admin_enqueue_scripts', '_pts_enqueue_scripts' );
function _pts_enqueue_scripts( $hook_suffix ) {

	if ( ! _pts_is_edit_screen( $hook_suffix ) ) {
		return false;
	}

	wp_register_style( 'select2css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', false, '4.0.5', 'all' );
	wp_register_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js', [ 'jquery' ], '4.0.5', true );
	wp_enqueue_style( 'select2css' );
	wp_enqueue_script( 'select2' );

	$init = "(function($) { $('#page_template').select2(); })(jQuery);";

	wp_add_inline_script( 'select2', $init );

}

function _pts_is_edit_screen( $slug ) {
	return (bool) apply_filters( 'pts_is_edit_screen', in_array( $slug, [
			'post.php',
			'post-new.php'
		], true ) && 'page' === get_post_type() && ! _pts_is_wp_version_greater_than() );
}

function _pts_is_wp_version_greater_than( $version = '5.0' ) {
	global $wp_version;
	return version_compare( $wp_version, $version, '>' );
}
