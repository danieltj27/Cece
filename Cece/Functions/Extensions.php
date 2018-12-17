<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Extension functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Fires a callback for event listeners.
 * 
 * @since 0.1.0
 * 
 * @param string $event The name of the event.
 * @param array  $args  The array of event arguments.
 * 
 * @return mixed
 */
function do_event( $event = '', $args = array() ) {

	global $_event_listeners;

	// Bail if safe mode is on.
	if ( 'on' == blog_setting( 'flag_ext_safe' ) ) {

		return false;

	}

	// Do we have any listeners?
	if ( ! isset( $_event_listeners[ $event ] ) ) {

		return false;

	}

	// Do we have any registered events?
	if ( empty( $_event_listeners[ $event ] ) ) {

		return false;

	}

	// Loop through each callback listener.
	foreach ( $_event_listeners[ $event ] as $listener ) {

		// Extract the arguments.
		extract( $args );

		// Perform the callback.
		call_user_func_array( $listener, $args );

	}

	return true;

}

/**
 * Adds a listener to an event.
 * 
 * @since 0.1.0
 * 
 * @param string $event    The name of the event to callback.
 * @param array  $callback The class/function to run as a callback.
 * 
 * @return boolean
 */
function add_listener( $event = '', $callback = array() ) {

	global $_event_listeners;

	// Did we get a valid event name?
	if ( '' == $event ) {

		return false;

	}

	// Can we perform a callback?
	if ( ! is_callable( $callback ) ) {

		return false;

	}

	// Add the event listener.
	$_event_listeners[ $event ][] = $callback;

	return true;

}

/**
 * Get all extensions.
 * 
 * @since 0.1.0
 * 
 * @return boolean|array
 */
function get_all_extensions() {

	// Get all extension directories.
	$dirs = array_diff( scandir( CECEEXTEND ), array( '.', '..', '.svn', '.git', '.DS_Store', 'Thumbs.db' ) );

	// Did we get anything?
	if ( empty( $dirs ) ) {

		return false;

	}

	$extensions = array();

	// Loop through each directory as a extension.
	foreach ( $dirs as $dir ) {

		// Does this directory have a JSON file?
		if ( ! file_exists( CECEEXTEND . $dir . '/extension.json' ) ) {

			continue;

		}

		// Get the extension JSON details.
		$data = file_get_contents( CECEEXTEND . $dir . '/extension.json' );

		// Convert to an array.
		$data = json_decode( $data, true );

		// Does the extension domain match the settings value?
		if ( ! isset( $data[ 0 ][ 'domain' ] ) ) {

			continue;

		}

		// Does the extension already exist?
		if ( isset( $extensions[ $data[ 0 ][ 'domain' ] ] ) ) {

			continue;

		}

		// Save the extension data.
		$extensions[ $data[ 0 ][ 'domain' ] ] = array(
			'name' => isset( $data[ 0 ][ 'name' ] ) ? $data[ 0 ][ 'name' ] : $data[ 0 ][ 'domain' ],
			'description' => isset( $data[ 0 ][ 'description' ] ) ? $data[ 0 ][ 'description' ] : '',
			'domain' => $data[ 0 ][ 'domain' ],
			'function_path' => isset( $data[ 0 ][ 'function_path' ] ) ? $data[ 0 ][ 'function_path' ] : '',
			'version' => isset( $data[ 0 ][ 'version' ] ) ? $data[ 0 ][ 'version' ] : '',
			'author_name' => isset( $data[ 0 ][ 'author_name' ] ) ? $data[ 0 ][ 'author_name' ] : '',
			'author_url' => isset( $data[ 0 ][ 'author_url' ] ) ? $data[ 0 ][ 'author_url' ] : '',
			'licence_name' => isset( $data[ 0 ][ 'licence_name' ] ) ? $data[ 0 ][ 'licence_name' ] : '',
			'licence_url' => isset( $data[ 0 ][ 'licence_url' ] ) ? $data[ 0 ][ 'licence_url' ] : ''
		);

	}

	// Did we get any extensions?
	if ( empty( $extensions ) ) {

		return false;

	}

	return $extensions;

}

/**
 * Returns all active extensions.
 * 
 * @since 0.1.0
 * 
 * @return array
 */
function active_extensions() {

	// Get the active extensions setting.
	$active_extensions = blog_setting( 'active_extensions' );

	// Convert to an array and decode it.
	$active_extensions = json_decode( unfilter_text( $active_extensions ), true );

	return $active_extensions;

}

/**
 * Check if an extension is installed.
 * 
 * @since 0.1.0
 * 
 * @param string $extension The domain of an extension.
 * 
 * @return boolean
 */
function is_extension_installed( $extension = '' ) {

	// Get all extensions.
	$extensions = get_all_extensions();

	// Does the extension exist?
	if ( ! isset( $extensions[ $extension ] ) ) {

		return false;

	}

	// Get the extension settings data.
	$active_extensions = active_extensions();

	// Do we have any extensions?
	if ( empty( $active_extensions ) ) {

		return false;

	}

	// Is the extension installed?
	if ( in_array( $extension, $active_extensions, true ) ) {

		return true;

	}

	return false;

}
