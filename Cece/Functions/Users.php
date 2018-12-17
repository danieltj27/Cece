<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * User functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Check if the current user is logged in.
 * 
 * This function checks if the current user is logged
 * in or not. Will return true if they are or false if
 * they have not been authenticated.
 * 
 * The value becomes cached after it's first use but
 * setting the optional `cached` parameter to false
 * will refresh the value.
 * 
 * @since 0.1.0
 * 
 * @param boolean $cached Return the cached version.
 * 
 * @return boolean
 */
function is_logged_in( $cached = true ) {

	global $_logged_in;

	// Should we get the cached version?
	if ( $_logged_in && true === $cached ) {

		return $_logged_in;

	}

	// Set the default cached value.
	$_logged_in = false;

	// Do we have an active session and cookie set?
	if ( empty( $_SESSION ) || ! isset( $_SESSION['id'] ) || ! isset( $_COOKIE[ AUTH_COOKIE ] ) ) {

		return $_logged_in;

	}

	// Create new database connection.
	$db = new Database;

	// Bail if the database connection failed.
	if ( ! $db ) {

		return false;

	}

	// Prepare the select statement.
	$query = $db->connection->prepare( 'SELECT email FROM ' . $db->prefix . 'users WHERE ID = ? LIMIT 1' );

	// Bind the parameter to the query.
	$query->bindParam( 1, $_SESSION['id'] );

	// Execute the query.
	$query->execute();

	// Return the values.
	$result = $query->fetch();

	// Did we get a result?
	if ( false === $result ) {

		return $_logged_in;

	}

	// Does the hash match the cookie?
	if ( ! password_verify( $result['email'], $_COOKIE[ AUTH_COOKIE ] ) ) {

		return $_logged_in;

	}

	// Update the cache value.
	$_logged_in = true;

	return $_logged_in;

}

/**
 * Check if a user is an admin.
 * 
 * Check if the user based on the given user id is
 * an admin or not. If a user id is not provided then
 * the currently logged in user will be checked instead.
 * 
 * @since 0.1.0
 * 
 * @param int $user_id The user id to check.
 * 
 * @return boolean
 */
function is_admin( $user_id = 0 ) {

	// Did we get a valid ID?
	if ( 0 === $user_id ) {

		// Set to the current user.
		$user_id = current_user_id();

	}

	// Create a new user instance.
	$user = new User;

	// Fetch the selected user.
	$user->fetch( $user_id );

	// Is this user an admin?
	if ( 'admin' == $user->user_type ) {

		return true;

	}

	return false;

}

/**
 * Check if a user is an author.
 * 
 * Checks if the given user id belongs to a user account
 * that has the type of `author`. This function has a second
 * optional parameter to perform a strict check. If strict is
 * not set, admins will return true for this function.
 * 
 * @since 0.1.0
 * 
 * @param int     $user_id The user id to check.
 * @param boolean $strict  Strictly check the user type.
 * 
 * @return boolean
 */
function is_author( $user_id = 0, $strict = false ) {

	// Did we get a valid ID?
	if ( 0 === $user_id ) {

		// Set to the current user.
		$user_id = current_user_id();

	}

	// Create a new user instance.
	$user = new User;

	// Fetch the selected user.
	$user->fetch( $user_id );

	// Is this user an author (or an admin)?
	if ( ( false === $strict && in_array( $user->user_type, array( 'author', 'admin' ), true ) ) || ( false !== $strict && 'author' == $user->user_type ) ) {

		return true;

	}

	return false;

}

/**
 * Get the current user object.
 * 
 * The value becomes cached after it's first use but
 * setting the optional `cached` parameter to false
 * will refresh the value.
 * 
 * @since 0.1.0
 * 
 * @param boolean $cached Return the cached version.
 * 
 * @return object $user
 */
function current_user( $cached = true ) {

	global $_current_user;

	// Should we get the cached version?
	if ( $_current_user && true === $cached ) {

		return $_current_user;

	}

	// Setup the default user id.
	$user_id = 0;

	// Is the current user logged in?
	if ( is_logged_in() ) {

		$user_id = $_SESSION['id'];

	}

	// Create a new user instance.
	$user = new User;

	// Set the current user up.
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

	// Get the current user instance.
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
 * Is the given user me?
 * 
 * Checks if a given ID is the same one as the
 * current user or not. Returns true if it is
 * and false if not or the user is not logged in.
 * 
 * @since 0.1.0
 * 
 * @param int $user_id The user id to check.
 * 
 * @return boolean
 */
function is_me( $user_id = 0 ) {

	// Is the user logged in?
	if ( ! is_logged_in() ) {

		return false;

	}

	// Get the current user id.
	$current_user_id = current_user_id();

	// Do the IDs match?
	if ( $user_id === $current_user_id ) {

		return true;

	}

	return false;

}

/**
 * Checks if a user can perform an action.
 * 
 * This function checks if the selected user can
 * perform a certain action based on the permissions
 * set on their account.
 * 
 * If the second parameter (which is optional) is left
 * blank, the current user id will be used instead. Returns
 * a boolean value of true if they have permission or false
 * if the user doesn't.
 * 
 * @todo permissions need to be implemented
 *       properly before this can be used.
 * 
 * @since 0.1.0
 * 
 * @param string $action  The action to check permissions for.
 * @param int    $user_id The user id to check against.
 * 
 * @return boolean
 */
function user_can( $action, $user_id = 0 ) {

	return no_thank_you( 'The user_can function is incomplete and should not be used.' );

}
