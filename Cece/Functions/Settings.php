<?php

/**
 * Cece
 * (c) 2024, Daniel James
 * 
 * Settings functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Register a new setting.
 * 
 * Accepts an array of parameters which are to be
 * used to register a setting which is then shown
 * on the settings page within the dashbaord.
 * 
 * @todo implement proper settings field API.
 * 
 * @since 0.1.0
 * 
 * @param array $opts The array of setting options.
 * 
 * @return boolean
 */
function register_setting( $opts = array() ) {

	// Bail if we get nothing.
	if ( empty( $opts ) ) {

		return false;

	}

	global $_reg_settings;

	// Set up the defaults.
	$defaults = array(
		'key' => '',
		'label' => '',
		'description' => '',
		'type' => '',
		'values' => '',
		'callback' => ''
	);

	// Merge the two together.
	$opts = array_merge( $defaults, $opts );

	// Does setting already exist?
	if ( isset( $_reg_settings[ $opts[ 'key' ] ] ) ) {

		return false;

	}

	// Check key is valid.
	if ( '' == $opts[ 'key' ] || false === $opts[ 'key' ] || 0 === $opts[ 'key' ] ) {

		return false;

	}

	// Add it.
	$_reg_settings[ $opts[ 'key' ] ] = $opts;

	return true;

}

/**
 * Unregisters a existing setting.
 * 
 * @todo implement proper settings field API.
 * 
 * @since 0.1.0
 * 
 * @param string $key The key for the setting.
 * 
 * @return boolean
 */
function unregister_setting( $key = '' ) {

	global $_reg_settings;

	// Does the setting exist?
	if ( ! isset( $_reg_settings[ $key ] ) ) {

		return false;

	}

	// Remove the setting.
	unset( $_reg_settings[ $key ] );

	return true;

}

/**
 * Get a blog setting value.
 * 
 * Returns the value of a setting in the form
 * of a string or returns false if an invalid
 * setting has been requested.
 * 
 * All settings values are cached unless the optional
 * cache busting parameter is set to anything but true.
 * 
 * @since 0.1.0
 * 
 * @param string  $setting The settings key to fetch.
 * @param boolean $cached  Return the cached version.
 * 
 * @return mixed
 */
function blog_setting( $key = '', $cached = true ) {

	global $_settings;

	// Has the application been installed?
	if ( ! is_app_installed() ) {

		return false;

	}

	// Bail if we're gettin' nothin'.
	if ( '' == $key ) {

		return false;

	}

	// Return a cached value if we have one.
	if ( true === $cached && isset( $_settings[ $key ] ) ) {

		return $_settings[ $key ];

	}

	// Create a new settings instance.
	$settings = new Setting;
	$settings = $settings->all();

	// Did we get anything?
	if ( empty( $settings ) ) {

		return false;

	}

	// Reset the cache as we're rebuilding anyway.
	$_settings = array();

	// Loop through each and build the cache.
	foreach ( $settings as $setting ) {

		// Add to the cache.
		$_settings[ $setting['setting_key'] ] = $setting['setting_value'];

	}

	// Is the value available now?
	if ( ! isset( $_settings[ $key ] ) ) {

		return false;

	}

	return $_settings[ $key ];

}

/**
 * Return the blog title.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function blog_name() {

	return blog_setting( 'name' );

}

/**
 * Return the blog domain.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function blog_domain() {

	return blog_setting( 'domain' );

}

/**
 * Return the blog email.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function blog_email() {

	return blog_setting( 'email' );

}

/**
 * Return the per page value.
 * 
 * @since 0.1.0
 * 
 * @return int
 */
function blog_per_page() {

	return (int) blog_setting( 'per_page' );

}

/**
 * Return the custom home page.
 * 
 * @since 0.1.0
 * 
 * @return object
 */
function blog_home_page() {

	// Get the custom home page.
	$home_page = (int) blog_setting( 'home_page' );

	// Create a post instance.
	$post = new Post;

	// Does the page exist?
	if ( $post->exists( $home_page ) ) {

		// Set the post (page) object.
		$post->fetch( $home_page );

		return $post;

	}

	return false;

}

/**
 * Return blog language type.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function blog_lang() {

	return blog_setting( 'language' );

}

/**
 * Checks if public registration is enabled.
 * 
 * @since 0.1.0
 * 
 * @return boolean
 */
function can_register() {

	// Is registration enabled?
	if ( 'on' == blog_setting( 'register' ) ) {

		return true;

	}

	return false;

}

/**
 * Checks if HTTPS connections are enabled.
 * 
 * @since 0.1.0
 * 
 * @return boolean
 */
function is_secure() {

	// Is registration enabled?
	if ( 'on' == blog_setting( 'https' ) ) {

		return true;

	}

	return false;

}

/**
 * Returns the last checked for updates timestamp.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function update_check() {

	return blog_setting( 'update_check' );

}

/**
 * Returns a boolean value based on whether an update
 * is available to download or not.
 * 
 * @since 0.1.0
 * 
 * @return boolean
 */
function update_available() {

	// Do we have an update?
	if ( '0' != blog_setting( 'update_available' ) ) {

		return true;

	}

	return false;

}

/**
 * Checks if auto update checks are on.
 * 
 * @since 0.1.0
 * 
 * @return boolean
 */
function auto_updates() {

	// Are automatic updates enabled?
	if ( 'on' == blog_setting( 'auto_check' ) ) {

		return true;

	}

	return false;

}

/**
 * Return the current timezone.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function blog_timezone() {

	return blog_setting( 'timezone' );

}

/**
 * Get the current blog version.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function blog_version() {

	// Create a new app instance.
	$App = new App;

	return $App->app_version;

}
