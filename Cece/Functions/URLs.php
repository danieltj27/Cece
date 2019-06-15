<?php

/**
 * Cece
 * (c) 2019, Daniel James
 * 
 * URL functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Returns the site URL.
 * 
 * Provides the blog homepage URL with an optional
 * parameter to append to the end of the URL.
 * 
 * @since 0.1.0
 * 
 * @param string  $path   The additional path to append.
 * @param boolean $cached Return the cached version.
 * 
 * @return string
 */
function home_url( $path = '', $cached = true ) {

	global $_home_url;

	// Should the cached version be returned?
	if ( '' == $_home_url || false === $cached ) {

		// Get the blog domain.
		$domain = blog_domain();

		// Is the URL saved in the database?
		if ( false === filter_var( $domain, FILTER_VALIDATE_URL ) ) {

			/**
			 * Default to the 'server name' if a blog
			 * domain isn't set in the database to use.
			 */
			$url = sanitise_text( $_SERVER['SERVER_NAME'], '~[^A-Za-z-_.]~' );

		} else {

			$url = $domain;

		}

		// Remove the protocol if there is one.
		$url = str_replace( 'http://', '', $url );
		$url = str_replace( 'https://', '', $url );

		// Should we use HTTPS?
		if ( is_secure() ) {

			$protocol = 'https';

		} else {

			$protocol = 'http';

		}

		// Build the URL and cache it.
		$_home_url = $protocol . '://' . $url . '/';

	}

	$url = $_home_url;

	// Check if we have a path.
	if ( '' != $path ) {

		$url = $url . trim( $path, '/' );

	}

	return $url;

}

/**
 * Returns the authentication URL.
 * 
 * @see home_url()
 * 
 * @since 0.1.0
 * 
 * @param string $path The additional path.
 * 
 * @return string
 */
function auth_url( $path = '' ) {

	return home_url( 'auth/' . $path );

}

/**
 * Returns the dashboard URL.
 * 
 * @see home_url()
 * 
 * @since 0.1.0
 * 
 * @param string $path The additional path.
 * 
 * @return string
 */
function dashboard_url( $path = '' ) {

	return home_url( 'dashboard/' . $path );

}

/**
 * Returns the API URL.
 * 
 * @see home_url()
 * 
 * @since 0.1.0
 * 
 * @param string $path The additional path.
 * 
 * @return string
 */
function api_url( $path = '' ) {

	return home_url( 'api/' . $path );

}

/**
 * Return the URL of system assets.
 * 
 * @see home_url()
 * 
 * @since 0.1.0
 * 
 * @param string $path The additional path.
 * 
 * @return string
 */
function assets_url( $path = '' ) {

	return home_url( 'Cece/Assets/' . $path );

}
