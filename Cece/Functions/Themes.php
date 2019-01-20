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
 * Get all themes.
 * 
 * @since 0.1.0
 * 
 * @return boolean|array
 */
function get_themes() {

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

		// Create an theme instance.
		$theme = new Theme;

		// Set up the theme object.
		$theme->theme_name = isset( $data[ 0 ][ 'name' ] ) ? $data[ 0 ][ 'name' ] : $data[ 0 ][ 'domain' ];
		$theme->theme_description = isset( $data[ 0 ][ 'description' ] ) ? $data[ 0 ][ 'description' ] : '';
		$theme->theme_domain = $data[ 0 ][ 'domain' ];
		$theme->theme_temp_path = isset( $data[ 0 ][ 'template_path' ] ) ? $data[ 0 ][ 'template_path' ] : '';
		$theme->theme_version = isset( $data[ 0 ][ 'version' ] ) ? $data[ 0 ][ 'version' ] : '';
		$theme->theme_author_name = isset( $data[ 0 ][ 'author_name' ] ) ? $data[ 0 ][ 'author_name' ] : '';
		$theme->theme_author_url = isset( $data[ 0 ][ 'author_url' ] ) ? $data[ 0 ][ 'author_url' ] : '';
		$theme->theme_licence_name = isset( $data[ 0 ][ 'licence_name' ] ) ? $data[ 0 ][ 'licence_name' ] : '';
		$theme->theme_licence_url = isset( $data[ 0 ][ 'licence_url' ] ) ? $data[ 0 ][ 'licence_url' ] : '';

		// Save the theme data.
		$themes[ $theme->theme_domain ] = $theme;

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
	if ( true === $cached && $_active_theme instanceof Theme ) {

		return $_active_theme;

	}

	// Get the current theme.
	$current_theme = blog_setting( 'theme' );

	// Get all themes.
	$themes = get_themes();

	// Does the theme exist?
	if ( ! isset( $themes[ $current_theme ] ) ) {

		return false;

	}

	// Set as the active theme.
	$_active_theme = $themes[ $current_theme ];

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

	// Does the theme exist?
	if ( ! $theme ) {

		return false;

	}

	return $theme->theme_domain;

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

	// Does the theme exist?
	if ( ! $theme ) {

		return false;

	}

	// Do we have a custom path?
	if ( '' != $path ) {

		return $theme->theme_path() . trim( $path, '/' );

	}

	return $theme->theme_path();

}

/**
 * Get the templates of the active theme.
 * 
 * @since 0.1.0
 * 
 * @return boolean|array
 */
function theme_templates() {

	// Get the active theme.
	$theme = active_theme();

	// Does the path exist?
	if ( ! file_exists( $theme->theme_temp_path() ) ) {

		return false;

	}

	// Get files in the template path.
	$files = array_diff( scandir( $theme->theme_temp_path() ), array( '.', '..', '.svn', '.git', '.DS_Store', 'Thumbs.db' ) );

	// Did we get anything?
	if ( empty( $files ) ) {

		return false;

	}

	// Find out which are PHP files.
	foreach ( $files as $key => $file ) {

		// Is this not a PHP file?
		if ( false === strpos( $file, '.php' ) ) {

			unset( $files[ $key ] );

		}

	}

	// Do we have any templates left?
	if ( empty( $files ) ) {

		return false;

	}

	// Build the templates array.
	$templates = array();

	// Find out which are custom templates.
	foreach ( $files as $key => $file ) {

		// Check if this template is marked as a template.
		if ( substr( $file, 0, strlen( 'template-' ) ) == 'template-' ) {

			// Remove template from the start and PHP from the end.
			$templates[ $file ] = substr( substr( $file, strlen( 'template-' ) ), 0, -4 );

		}

	}

	// Do we have anything?
	if ( empty( $templates ) ) {

		return false;

	}

	return $templates;

}

/**
 * Returns the active theme templates path.
 * 
 * @since 0.1.0
 * 
 * @param string $path The directory or file to append.
 * 
 * @return boolean|string
 */
function theme_template_path( $path = '' ) {

	// Get the active theme.
	$theme = active_theme();

	// Does the theme exist?
	if ( ! $theme ) {

		return false;

	}

	// Do we have a custom path?
	if ( '' != $path ) {

		return $theme->theme_temp_path() . trim( $path, '/' );

	}

	return $theme->theme_temp_path();

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

	// Does the theme exist?
	if ( ! $theme ) {

		return false;

	}

	// Do we have a custom path?
	if ( '' != $path ) {

		return $theme->theme_url() . trim( $path, '/' );

	}

	return $theme->theme_url();

}
