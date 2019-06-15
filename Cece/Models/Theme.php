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
 * The theme model.
 * 
 * @package Cece
 * 
 * @since 0.1.0
 */
class Theme extends Model {

	/**
	 * The theme name.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_name = '';

	/**
	 * The theme description.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_description = '';

	/**
	 * The theme domain.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_domain = '';

	/**
	 * The theme template path.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_temp_path = '';

	/**
	 * The theme version.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_version = '';

	/**
	 * The theme author name.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_author_name = '';

	/**
	 * The theme author URL.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_author_url = '';

	/**
	 * The theme licence name.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_licence_name = '';

	/**
	 * The theme licence URL.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $theme_licence_url = '';

	/**
	 * Create a new theme instance.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $domain The theme domain.
	 * 
	 * @return object
	 */
	public function __construct( $domain = 0 ) {

		if ( '' != $domain ) {

			$this->fetch( $domain );

		}

	}

	/**
	 * Fetch the selected theme.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $domain The theme domain to search for.
	 * 
	 * @return boolean
	 */
	public function fetch( $domain = '' ) {

		// Get all themes.
		$themes = get_themes();

		// Does the theme domain exist?
		if ( ! isset( $themes[ $domain ] ) ) {

			return false;

		}

		// Set up the theme object.
		$this->theme_name = $themes[ $domain ]->theme_name;
		$this->theme_description = $themes[ $domain ]->theme_description;
		$this->theme_domain = $themes[ $domain ]->theme_domain;
		$this->theme_temp_path = $themes[ $domain ]->theme_temp_path;
		$this->theme_version = $themes[ $domain ]->theme_version;
		$this->theme_author_name = $themes[ $domain ]->theme_author_name;
		$this->theme_author_url = $themes[ $domain ]->theme_author_url;
		$this->theme_licence_name = $themes[ $domain ]->theme_licence_name;
		$this->theme_licence_url = $themes[ $domain ]->theme_licence_url;

		return true;

	}

	/**
	 * Check an theme exists.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $domain The theme domain to search for.
	 * 
	 * @return boolean
	 */
	public function exists( $domain = '' ) {

		// Get all themes.
		$themes = get_themes();

		// Does the theme domain exist?
		if ( isset( $themes[ $domain ] ) ) {

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
		$this->theme_name = '';
		$this->theme_description = '';
		$this->theme_domain = '';
		$this->theme_temp_path = '';
		$this->theme_version = '';
		$this->theme_author_name = '';
		$this->theme_author_url = '';
		$this->theme_licence_name = '';
		$this->theme_licence_url = '';

		return true;

	}

	/**
	 * Checks if the theme is activated.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function is_activated() {

		// Create a settings instance.
		$setting = new Setting;

		// Fetch current theme setting.
		$setting->fetch( 'theme', 'setting_key' );

		// Is this the activated theme?
		if ( $this->theme_domain == $setting->setting_value ) {

			return true;

		}

		return false;

	}

	/**
	 * Activates the theme on the blog.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function activate() {

		// Create a settings instance.
		$setting = new Setting;

		// Fetch current theme setting.
		$setting->fetch( 'theme', 'setting_key' );

		// Switch to the current theme.
		$setting->setting_value[] = $this->theme_domain;

		$setting->save();

		return true;

	}

	/**
	 * Return the path to theme.
	 * 
	 * @since 0.1.0
	 * 
	 * @return string
	 */
	public function theme_path() {

		return CECETHEMES . $this->theme_domain . '/';

	}

	/**
	 * Return the URL to theme.
	 * 
	 * @since 0.1.0
	 * 
	 * @return string
	 */
	public function theme_url() {

		return home_url( 'Content/Themes/' . $this->theme_domain ) . '/';

	}

	/**
	 * Return the theme template path.
	 * 
	 * @since 0.1.0
	 * 
	 * @return string
	 */
	public function theme_temp_path() {

		return $this->theme_path() . trim( $this->theme_temp_path, '/' ) . '/';

	}

}
