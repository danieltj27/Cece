<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Notice functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Registers a new notice.
 * 
 * @since 0.1.0
 * 
 * @param string  $id         The unique nnotice id.
 * @param string  $type       The type of notice.
 * @param string  $text       The text for this notice.
 * @param boolean $dismiss    The dismiss notice flag.
 * @param boolean $cache_bust The notice cache bust flag.
 * 
 * @return boolean
 */
function register_notice( $id, $type, $text, $dismiss = true, $cache_bust = false ) {

	// Map the notice options.
	$opts = array(
		'id' => $id,
		'type' => $type,
		'text' => $text,
		'dismiss' => $dismiss,
	);

	// Clean up all the options.
	$opts[ 'id' ] = sanitise_text( $opts[ 'id' ], '~[^A-Za-z0-9]~' );
	$opts[ 'type' ] = ( in_array( $opts[ 'type' ], array( 'info', 'success', 'warning' ), true ) ) ? $opts[ 'type' ] : 'info';
	$opts[ 'text' ] = filter_text( $opts[ 'text' ] );
	$opts[ 'dismiss' ] = (boolean) $opts[ 'dismiss' ];

	// Does this notice exist?
	if ( isset( $_SESSION[ 'notices' ] ) && isset( $_SESSION[ 'notices' ][ $opts[ 'id' ] ] ) ) {

		return false;

	}

	// Add the notice to the session.
	$_SESSION[ 'notices' ][ $opts[ 'id' ] ] = $opts;

	// Bust the cache for this notice?
	if ( true === $cache_bust ) {

		update_notice_cache( $opts[ 'id' ] );

	}

	return true;

}

/**
 * Update session set notices.
 * 
 * This function can be used to update the global
 * notices cache if a notice has been added after
 * the application has been initialised but needs
 * outputting before shutdown.
 * 
 * @since 0.1.0
 * 
 * @param string $id The optional id of a notice to update.
 * 
 * @return boolean
 */
function update_notice_cache( $id = '' ) {

	global $_notices;

	// Do we have an id?
	if ( isset( $_SESSION[ 'notices' ] ) && is_array( $_notices ) ) {

		// Do we have an id?
		if ( '' != $id ) {

			// Does the notice exist?
			if ( isset( $_SESSION[ 'notices' ][ $id ] ) ) {

				// Add the specified notice.
				$_notices[ $id ] = $_SESSION[ 'notices' ][ $id ];

				// Remove this notice from the session.
				unset( $_SESSION[ 'notices' ][ $id ] );

				return true;

			}

		} else {

			// Merge the notice arrays together.
			$_notices = array_merge( $_notices, $_SESSION[ 'notices' ] );

			// Clear the session notices.
			$_SESSION[ 'notices' ] = array();

			return true;

		}

	}

	return false;

}

/**
 * Return all notice HTML.
 * 
 * @see register_notice()
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function do_notices() {

	global $_notices;

	// Notices HTML placeholder.
	$output = '';

	// Bail early if we need to.
	if ( empty( $_notices ) || ! is_array( $_notices ) ) {

		return '';

	}

	// Define available notice icons.
	$icons = array(
		'info' => 'info',
		'success' => 'thumbs-up',
		'warning' => 'exclamation-triangle',
	);

	// Loop through each notice and build it.
	foreach ( $_notices as $notice ) {

		// Check we have all required fields.
		if ( ! isset( $notice[ 'id' ] ) || ! isset( $notice[ 'type' ] ) || ! isset( $notice[ 'text' ] ) || ! isset( $notice[ 'dismiss' ] ) ) {

			continue;

		}

		// Can this be dismissed?
		if ( $notice[ 'dismiss' ] ) {

			$dismiss = '<a class="notice__close" role="link" tabindex="0"><i class="fas fa-times"></i></a>';

		} else {

			$dismiss = '';

		}

		$output .= '<div class="notice notice--' . $notice[ 'type' ] . ' ' . $notice[ 'id' ] . '"><div class="notice__icon"><i class="fas fa-' . $icons[ $notice[ 'type' ] ] . '"></i></div><div class="notice__text"><p>' . $notice[ 'text' ] . '</p>' . $dismiss . '</div></div>';

	}

	return $output;

}
