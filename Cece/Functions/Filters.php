<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Filter functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Sanitise a piece of textual input.
 * 
 * @since 0.1.0
 * 
 * @param string $text      The dirty text that needs cleaning.
 * @param string $regex     The regular expression to use for cleaning.
 * @param string $delimiter The delimiter of the regular expression.
 * 
 * @return string
 */
function sanitise_text( $text = '', $regex = '~[^A-Za-z]~' ) {

	// Make sure we a regular expression.
	if ( '' == $regex || ! is_string( $regex ) ) {

		no_thank_you( 'Invalid regular expression given for sanitisation.' );

	}

	return preg_replace( $regex, '', $text );

}

/**
 * Wrapper function to filter URL paths.
 * 
 * @since 0.1.0
 * 
 * @see sanitise_text()
 * 
 * @param string $path The URL path to clean.
 * 
 * @return string
 */
function sanitise_path( $path = '' ) {

	return sanitise_text( $path, '~[^A-Za-z0-9_[-]\/]~' );

}

/**
 * Filter a string of possible HTML content.
 * 
 * @since 0.1.0
 * 
 * @param string $text The text to filter from HTML.
 * 
 * @return string $text
 */
function filter_text( $text ) {

	return htmlspecialchars( $text );

}

/**
 * Reverse the filtering of htmlspecialchars.
 * 
 * @see filter_text()
 * 
 * @since 0.1.0
 * 
 * @param string $text The text to filter into HTML.
 * 
 * @return string $text
 */
function unfilter_text( $text ) {

	return htmlspecialchars_decode( $text );

}
