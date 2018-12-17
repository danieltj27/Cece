<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Template functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Return the page title text.
 * 
 * @since 0.1.0
 * 
 * @param string  $title The title to prepend.
 * @param boolean $raw   THe flag to show the raw title or full title.
 * 
 * @return string $title
 */
function load_title( $title = '', $raw = false ) {

	// Force raw output if not installed.
	if ( ! is_app_installed() ) {

		$raw = true;

	}

	// Should we should the raw title only?
	if ( true === $raw ) {

		return $title;

	}

	return $title . ' &mdash; ' . blog_name();

}

/**
 * Load the selected template.
 * 
 * Loads the given template via the `view` method
 * within the core application controller.
 * 
 * @since 0.1.0
 * 
 * @param string $path The template to load.
 * 
 * @return mixed
 */
function load_template( $path = '' ) {

	// Bail silently if we get nothing.
	if ( ! is_string( $path ) || '' == $path ) {

		return false;

	}

	return Controller::view( $path );

}
