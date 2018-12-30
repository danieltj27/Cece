<?php

/**
 * Cece
 * (c) 2018, Daniel James
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
 * Returns the post URL.
 * 
 * @todo implement post URL writing functionality.
 * 
 * @since 0.1.0
 * 
 * @param object $post The object a Post instance.
 * 
 * @return string
 */
function post_url( $post ) {

	// Do we have a valid post instance?
	if ( ! $post instanceof Post ) {

		return false;

	}

	// Create new post type instance.
	$post_type = new PostTypes;

	// Get the actual post type.
	$post_type = $post_type->get( $post->post_type );

	// Did we get a valid post type?
	if ( false === $post_type ) {

		return false;

	}

	// Should the post type be in the URL?
	if ( $post_type[ 'show_in_url' ] ) {

		$path = $post_type[ 'path' ] . '/' . $post->post_path . '/';

	} else {

		$path = $post->post_path . '/';

	}

	return home_url( $path );

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
