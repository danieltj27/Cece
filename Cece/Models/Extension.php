<?php

/**
 * Cece
 * (c) 2019, Daniel James
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * The extension model.
 * 
 * @package Cece
 * 
 * @since 0.1.0
 */
class Extension extends Model {

	/**
	 * The extension name.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_name = '';

	/**
	 * The extension description.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_description = '';

	/**
	 * The extension domain.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_domain = '';

	/**
	 * The extension function path.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_func_path = '';

	/**
	 * The extension version.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_version = '';

	/**
	 * The extension author name.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_author_name = '';

	/**
	 * The extension author URL.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_author_url = '';

	/**
	 * The extension licence name.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_licence_name = '';

	/**
	 * The extension licence URL.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $ext_licence_url = '';

	/**
	 * Create a new extension instance.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $domain The extension domain.
	 * 
	 * @return object
	 */
	public function __construct( $domain = 0 ) {

		if ( '' != $domain ) {

			$this->fetch( $domain );

		}

	}

	/**
	 * Fetch the selected extension.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $domain The extension domain to search for.
	 * 
	 * @return boolean
	 */
	public function fetch( $domain = '' ) {

		// Get all extensions.
		$extensions = get_extensions();

		// Does the extension domain exist?
		if ( ! isset( $extensions[ $domain ] ) ) {

			return false;

		}

		// Set up the extension object.
		$this->ext_name = $extensions[ $domain ]->ext_name;
		$this->ext_description = $extensions[ $domain ]->ext_description;
		$this->ext_domain = $extensions[ $domain ]->ext_domain;
		$this->ext_func_path = $extensions[ $domain ]->ext_func_path;
		$this->ext_version = $extensions[ $domain ]->ext_version;
		$this->ext_author_name = $extensions[ $domain ]->ext_author_name;
		$this->ext_author_url = $extensions[ $domain ]->ext_author_url;
		$this->ext_licence_name = $extensions[ $domain ]->ext_licence_name;
		$this->ext_licence_url = $extensions[ $domain ]->ext_licence_url;

		return true;

	}

	/**
	 * Check an extension exists.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $domain The extension domain to search for.
	 * 
	 * @return boolean
	 */
	public function exists( $domain = '' ) {

		// Get all extensions.
		$extensions = get_extensions();

		// Does the extension domain exist?
		if ( isset( $extensions[ $domain ] ) ) {

			return true;

		}

		return false;

	}

	/**
	 * Reset the current instance.
	 * 
	 * @since 0.1.0
	 * 
	 * @param boolean $history Flag to Keep previous ID history.
	 * 
	 * @return boolean
	 */
	public function reset( $history = false ) {

		// Should we keep the previous ID?
		if ( true === $history ) {

			$this->previous_ID = $this->ID;

		} else {

			$this->previous_ID = 0;

		}

		// Reset the object.
		$this->ext_name = '';
		$this->ext_description = '';
		$this->ext_domain = '';
		$this->ext_func_path = '';
		$this->ext_version = '';
		$this->ext_author_name = '';
		$this->ext_author_url = '';
		$this->ext_licence_name = '';
		$this->ext_licence_url = '';

		return true;

	}

	/**
	 * Checks if an extension is installed.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function is_installed() {

		// Create a settings instance.
		$setting = new Setting;

		// Fetch installed extension setting.
		$setting->fetch( 'active_extensions', 'setting_key' );

		// Get the settings contents.
		$active_extensions = explode( ',', $setting->setting_value );

		// Does it exist in the array?
		if ( array_search( $this->ext_domain, $active_extensions, true ) ) {

			return true;

		}

		return false;

	}

	/**
	 * Install an inactive extension.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function install() {

		// Create a settings instance.
		$setting = new Setting;

		// Fetch installed extension setting.
		$setting->fetch( 'active_extensions', 'setting_key' );

		// Get the settings contents.
		$setting->setting_value = explode( ',', $setting->setting_value );

		// Is the extension installed?
		if ( $this->is_installed() ) {

			return false;

		}

		// Add the extension.
		$setting->setting_value[] = $this->ext_domain;

		// Convert value back into a string.
		$setting->setting_value = implode( ',', $setting->setting_value );

		$setting->save();

		return true;

	}

	/**
	 * Uninstall an active extension.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function uninstall() {

		// Create a settings instance.
		$setting = new Setting;

		// Fetch installed extension setting.
		$setting->fetch( 'active_extensions', 'setting_key' );

		// Get the settings contents.
		$setting->setting_value = explode( ',', $setting->setting_value );

		// Is the extension installed?
		if ( ! $this->is_installed() ) {

			return false;

		}

		// Get the index in the array.
		$index = array_search( $this->ext_domain, $setting->setting_value, true );

		// Remove it.
		unset( $setting->setting_value[ $index ] );

		// Convert value back into a string.
		$setting->setting_value = implode( ',', $setting->setting_value );

		$setting->save();

		return true;

	}

	/**
	 * Return the path to extension.
	 * 
	 * @since 0.1.0
	 * 
	 * @return string
	 */
	public function ext_path() {

		return CECEEXTEND . $this->ext_domain . '/';

	}

	/**
	 * Return the functions file of the extension.
	 * 
	 * @since 0.1.0
	 * 
	 * @return string
	 */
	public function ext_func_file() {

		return $this->ext_path() . trim( $this->ext_func_path, '/' ) . '/' . $this->ext_domain . '.php';

	}

}
