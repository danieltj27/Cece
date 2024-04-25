<?php

/**
 * Cece
 * (c) 2024, Daniel James
 * 
 * User functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Checks if the current user is logged in.
 * 
 * The value from this function will be cached in a global variable after
 * the first use to prevent lots of database queries. The value can be refreshed
 * by passing the optional `$cached` parameter as false.
 * 
 * @since 0.1.0
 * 
 * @param boolean $cached Return the cached version.
 * 
 * @return boolean
 */
function is_logged_in( $cached = true ) {

	global $_logged_in;

	// Should we use the cached version?
	if ( $_logged_in && true === $cached ) {

		return $_logged_in;

	}

	// Set the default value.
	$_logged_in = false;

	// Do we have an active session and cookie set?
	if ( empty( $_SESSION ) || ! isset( $_SESSION[ 'id' ] ) || ! isset( $_COOKIE[ AUTH_COOKIE ] ) ) {

		return $_logged_in;

	}

	$db = new Database;

	// Bail if we fail.
	if ( ! $db ) {

		return false;

	}

	// Prepare the SQL.
	$query = $db->connection->prepare( 'SELECT email FROM ' . $db->prefix . 'users WHERE ID = ? LIMIT 1' );

	// Bind the parameter.
	$query->bindParam( 1, $_SESSION[ 'id' ] );

	$query->execute();

	$result = $query->fetch();

	if ( false === $result ) {

		return $_logged_in;

	}

	// Check the hash of the set cookie.
	if ( ! password_verify( $result['email'], $_COOKIE[ AUTH_COOKIE ] ) ) {

		return $_logged_in;

	}

	$_logged_in = true;

	return $_logged_in;

}

/**
 * Check if a user is an admin.
 * 
 * This is a wrapper function for the `is_admin` User method.
 * 
 * @since 0.1.0
 * 
 * @param int $user_id The user id to check.
 * 
 * @return boolean
 */
function is_admin( $user_id = 0 ) {

	if ( 0 === $user_id ) {

		$user_id = current_user_id();

	}

	$user = new User;

	$user->fetch( $user_id );

	return $user->is_admin();

}

/**
 * Check if a user is an author.
 * 
 * This is a wrapper function for the `is_author` User method.
 * 
 * @since 0.1.0
 * 
 * @param int     $user_id The user id to check.
 * @param boolean $strict  Strictly check the user type.
 * 
 * @return boolean
 */
function is_author( $user_id = 0, $strict = false ) {

	if ( 0 === $user_id ) {

		$user_id = current_user_id();

	}

	$user = new User;

	$user->fetch( $user_id );

	return $user->is_author( $strict );

}

/**
 * Get the current user object.
 * 
 * The value returned from this function will be cached in a global
 * variable for quicker use later on but can be refreshed by setting
 * the optional `$cached` value to false.
 * 
 * @since 0.1.0
 * 
 * @param boolean $cached Return the cached version.
 * 
 * @return object $user
 */
function current_user( $cached = true ) {

	global $_current_user;

	// Should we use the cached version?
	if ( $_current_user && true === $cached ) {

		return $_current_user;

	}

	$user_id = 0;

	if ( is_logged_in() ) {

		$user_id = $_SESSION['id'];

	}

	$user = new User;

	$user->fetch( $user_id );

	// Cache the value for later.
	$_current_user = $user;

	return $_current_user;

}

/**
 * Get the current user id.
 * 
 * @since 0.1.0
 * 
 * @return int
 */
function current_user_id() {

	$user = current_user();

	return $user->ID;

}

/**
 * Get the current user id.
 * 
 * This is a helper function for `current_user_id()`.
 * 
 * @since 0.1.0
 * 
 * @see current_user_id()
 * 
 * @return int
 */
function my_id() {

	return current_user_id();

}

/**
 * Is this user me?
 * 
 * @since 0.1.0
 * 
 * @param int $user_id The user id to check.
 * 
 * @return boolean
 */
function is_me( $user_id = 0 ) {

	if ( ! is_logged_in() ) {

		return false;

	}

	$current_user_id = current_user_id();

	if ( $user_id === $current_user_id ) {

		return true;

	}

	return false;

}
