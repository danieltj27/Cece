<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Theme functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Get all installed themes.
 * 
 * @since 0.1.0
 * 
 * @return boolean|array
 */
function get_all_themes() {

	// Get all theme directories.
	$dirs = array_diff( scandir( CECETHEMES ), array( '.', '..', '.svn', '.git', '.DS_Store', 'Thumbs.db' ) );

	// Did we get anything?
	if ( empty( $dirs ) ) {

		return false;

	}

	$themes = array();

	// Loop through each directory as a theme.
	foreach ( $dirs as $dir ) {

		// Does this directory have a JSON file?
		if ( ! file_exists( CECETHEMES . $dir . '/theme.json' ) ) {

			continue;

		}

		// Get the theme JSON details.
		$data = file_get_contents( CECETHEMES . $dir . '/theme.json' );

		// Convert to an array.
		$data = json_decode( $data, true );

		// Does the theme domain match the settings value?
		if ( ! isset( $data[ 0 ][ 'domain' ] ) ) {

			continue;

		}

		// Save the theme data.
		$themes[] = array(
			'name' => isset( $data[ 0 ][ 'name' ] ) ? $data[ 0 ][ 'name' ] : $data[ 0 ][ 'domain' ],
			'description' => isset( $data[ 0 ][ 'description' ] ) ? $data[ 0 ][ 'description' ] : '',
			'domain' => $data[ 0 ][ 'domain' ],
			'template_path' => isset( $data[ 0 ][ 'template_path' ] ) ? $data[ 0 ][ 'template_path' ] : '',
			'version' => isset( $data[ 0 ][ 'version' ] ) ? $data[ 0 ][ 'version' ] : '',
			'author_name' => isset( $data[ 0 ][ 'author_name' ] ) ? $data[ 0 ][ 'author_name' ] : '',
			'author_url' => isset( $data[ 0 ][ 'author_url' ] ) ? $data[ 0 ][ 'author_url' ] : '',
			'licence_name' => isset( $data[ 0 ][ 'licence_name' ] ) ? $data[ 0 ][ 'licence_name' ] : '',
			'licence_url' => isset( $data[ 0 ][ 'licence_url' ] ) ? $data[ 0 ][ 'licence_url' ] : ''
		);

	}

	// Did we get any themes?
	if ( empty( $themes ) ) {

		return false;

	}

	return $themes;

}

/**
 * Return the current theme information.
 * 
 * Returns the information for the current theme in an
 * array format or returns a boolean value of false if
 * an invalid name is specified or the theme isn't found.
 * 
 * @since 0.1.0
 * 
 * @param boolean $cached Return the cached version.
 * 
 * @return boolean|array
 */
function active_theme( $cached = true ) {

	global $_active_theme;

	// Should we use the cached version?
	if ( true === $cached && is_array( $_active_theme ) && ! empty( $_active_theme ) ) {

		return $_active_theme;

	}

	// Get the current theme.
	$theme = blog_setting( 'theme' );

	// Does the theme exist?
	if ( ! file_exists( CECETHEMES . $theme . '/theme.json' ) ) {

		return false;

	}

	// Get the theme JSON details.
	$data = file_get_contents( CECETHEMES . $theme . '/theme.json' );

	// Convert to an array.
	$data = json_decode( $data, true );

	// Does the theme domain match the settings value?
	if ( ! isset( $data[ 0 ][ 'domain' ] ) || $data[ 0 ][ 'domain' ] != $theme ) {

		return false;

	}

	// Save the theme data.
	$_active_theme = array(
		'name' => isset( $data[ 0 ][ 'name' ] ) ? $data[ 0 ][ 'name' ] : $data[ 0 ][ 'domain' ],
		'description' => isset( $data[ 0 ][ 'description' ] ) ? $data[ 0 ][ 'description' ] : '',
		'domain' => $data[ 0 ][ 'domain' ],
		'template_path' => isset( $data[ 0 ][ 'template_path' ] ) ? $data[ 0 ][ 'template_path' ] : '',
		'version' => isset( $data[ 0 ][ 'version' ] ) ? $data[ 0 ][ 'version' ] : '',
		'author_name' => isset( $data[ 0 ][ 'author_name' ] ) ? $data[ 0 ][ 'author_name' ] : '',
		'author_url' => isset( $data[ 0 ][ 'author_url' ] ) ? $data[ 0 ][ 'author_url' ] : '',
		'licence_name' => isset( $data[ 0 ][ 'licence_name' ] ) ? $data[ 0 ][ 'licence_name' ] : '',
		'licence_url' => isset( $data[ 0 ][ 'licence_url' ] ) ? $data[ 0 ][ 'licence_url' ] : ''
	);

	return $_active_theme;

}

/**
 * Returns the active theme domain.
 * 
 * @since 0.1.0
 * 
 * @return boolean|string
 */
function theme_domain() {

	// Get the active theme.
	$theme = active_theme();

	// Does the file path exist?
	if ( ! $theme ) {

		return false;

	}

	return $theme[ 'domain' ];

}

/**
 * Returns the active theme path.
 * 
 * @since 0.1.0
 * 
 * @param string $path The directory or file to append.
 * 
 * @return boolean|string
 */
function theme_path( $path = '' ) {

	// Get the active theme.
	$theme = active_theme();

	// Does the file path exist?
	if ( ! $theme || ! file_exists( CECETHEMES . $theme[ 'domain' ] . '/' . $theme[ 'template_path' ] ) ) {

		return false;

	}

	// Create the path.
	$_path = CECETHEMES . $theme[ 'domain' ] . '/' . $theme[ 'template_path' ];

	// Do we have a path to append?
	if ( '' != $path ) {

		$_path = $_path . '/' . ltrim( $path, '/' );

	}

	return $_path;

}

/**
 * Returns the active theme URL.
 * 
 * @since 0.1.0
 * 
 * @param string $path The directory or file to append.
 * 
 * @return boolean|string
 */
function theme_url( $path = '' ) {

	// Get the active theme.
	$theme = active_theme();

	// Does the file path exist?
	if ( ! $theme ) {

		return false;

	}

	// Create the path.
	$_url = 'Content/Themes/' . $theme[ 'domain' ];

	// Do we have a path to append?
	if ( '' != $path ) {

		$_url = $_url . '/' . ltrim( $path, '/' );

	}

	return home_url( $_url );

}
