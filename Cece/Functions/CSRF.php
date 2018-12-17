<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * CSRF functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Adds a CSRF token to a URL.
 * 
 * Adds the CSRF token to the user session but
 * won't modify the URL if the user is not logged
 * in as CSRF is unavailable to authenticated users.
 * 
 * @since 0.1.0
 * 
 * @param string $url The URL to modify.
 * 
 * @return string $url
 */
function csrfify_url( $url ) {

	// Bail if not logged in.
	if ( ! is_logged_in() ) {

		return $url;

	}

	// Get the CSRF token.
	$csrf = get_csrf();

	// Break the URL up.
	$parts = parse_url( $url );

	// Do we already have parameters?
	if ( isset( $parts['query'] ) ) {

		$glue = '&';

	} else {

		$glue = '?';

	}

	// Add the token to the URL.
	$url .= $glue . 'csrf_token=' . $csrf;

	return $url;

}

/**
 * Make a new CSRF token.
 * 
 * @since 0.1.0
 * 
 * @return boolean
 */
function create_csrf() {

	return Auth::create_csrf();

}

/**
 * Get the current CSRF token.
 * 
 * @since 0.1.0
 * 
 * @return boolean|string
 */
function get_csrf() {

	return Auth::get_csrf();

}

/**
 * Verify the CSRF token.
 * 
 * Regardless of whether the CSRF token check passed
 * or not, a new CSRF token is created and set for the
 * current user. Authentication is not required.
 * 
 * @since 0.1.0
 * 
 * @param string $csrf The CSRF token to verify.
 * 
 * @return boolean
 */
function verify_csrf( $csrf ) {

	return Auth::verify_csrf( $csrf );

}
