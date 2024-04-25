<?php

/**
 * Cece
 * (c) 2024, Daniel James
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Requests handler.
 * 
 * @package Cece
 * 
 * @since 0.1.0
 */
class Requests {

	/**
	 * The default query collection.
	 * 
	 * @since 0.1.0
	 * 
	 * @var array
	 */
	public $query = array();

	/**
	 * The current user id.
	 * 
	 * @since 0.1.0
	 * 
	 * @var int
	 */
	public $user_id = 0;

	/**
	 * The current post id.
	 * 
	 * @since 0.1.0
	 * 
	 * @var int
	 */
	public $post_id = 0;

	/**
	 * Is the current user an admin.
	 * 
	 * @since 0.1.0
	 * 
	 * @var boolean
	 */
	public $is_admin = false;

	/**
	 * Is the current user logged in.
	 * 
	 * @since 0.1.0
	 * 
	 * @var boolean
	 */
	public $is_logged_in = false;

	/**
	 * Front-end reuqest flag.
	 * 
	 * @since 0.1.0
	 * 
	 * @var boolean
	 */
	public $is_front = false;

	/**
	 * Dashboard request flag.
	 * 
	 * @since 0.1.0
	 * 
	 * @var boolean
	 */
	public $is_dashboard = false;

	/**
	 * System request flag.
	 * 
	 * @since 0.1.0
	 * 
	 * @var boolean
	 */
	public $is_system = false;

	/**
	 * Authentication request flag.
	 * 
	 * @since 0.1.0
	 * 
	 * @var boolean
	 */
	public $is_auth = false;

	/**
	 * API request flag.
	 * 
	 * @since 0.1.0
	 * 
	 * @var boolean
	 */
	public $is_api = false;

	/**
	 * Is file flag.
	 * 
	 * @since 0.1.1
	 * 
	 * @var boolean
	 */
	public $is_file = false;

	/**
	 * The current page slug.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $page = '';

	/**
	 * The current path request.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $path = '';

	/**
	 * The current timezone.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $timezone = '';

	/**
	 * Create the initial request data.
	 * 
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function __construct() {

		// Add the query variables.
		$this->user_id = 0;
		$this->post_id = 0;
		$this->is_admin = false;
		$this->is_logged_in = is_logged_in();
		$this->is_secure = is_secure();
		$this->is_file = $this->maybe_is_file();
		$this->is_front = $this->get_endpoint( 'front' );
		$this->is_dashboard = $this->get_endpoint( 'dashboard' );
		$this->is_system = $this->get_endpoint( 'system' );
		$this->is_auth = $this->get_endpoint( 'auth' );
		$this->is_api = $this->get_endpoint( 'api' );
		$this->page = $this->get_endpoint();
		$this->path = get_path();

		$this->set_timezone();

		$this->maybe_http_upgrade();

		$this->run_auto_update_checker();

		//var_dump( $this ); die();

	}

	/**
	 * Check for secure connections.
	 * 
	 * This functions checks whether the current connection is secure
	 * and if the HSTS security header should be applied to the current
	 * user session.
	 * 
	 * @todo redirect HTTP requests to HTTPS.
	 * 
	 * @since 0.1.0
	 * 
	 * @access private
	 * 
	 * @return void
	 */
	private function maybe_http_upgrade() {

		// Is HSTE requested?
		if ( 'on' == blog_setting( 'hsts' ) ) {

			// Set the HSTS header for a year.
			header( 'Strict-Transport-Security: max-age=31536000', true );

			return true;

		}

		return false;

	}

	/**
	 * Run an automatic system update check.
	 * 
	 * The automatic update check is triggered by any visit to the application
	 * as long as the daily timeout period has been reached. This could be done
	 * via a cron job but servers are hard.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function run_auto_update_checker() {

		// Has the application been installed yet?
		if ( ! is_app_installed() ) {

			return false;

		}

		// Create a settings instance.
		$settings = new Setting;

		// Get the automatic update check settings value.
		$settings->fetch( 'auto_check', 'setting_key' );

		// Are auto update checks enabled?
		if ( 'on' == $settings->setting_value ) {

			// Get the last check time.
			$settings->fetch( 'update_check', 'setting_key' );

			// Get the next update time.
			$next_check = strtotime( $settings->setting_value . ' +1 day' );

			// Get the current time.
			$current_time = strtotime( date( 'Y-m-d H:i:s' ) );

			// Are we due to check again?
			if ( $next_check <= $current_time ) {

				// Run the update check.
				App::check_system_update();

			}

		}

		return true;

	}

	/**
	 * Get the current endpoint.
	 * 
	 * Returns the current endpoint the user is viewing (or trying
	 * to view) unless a value is given for the endpoint parameter
	 * in which case, a check will be performed to see if the current
	 * endpoint matches the value given. If an endpoint value is given,
	 * a boolean value will be returned.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $endpoint The endpoint to check against if provided.
	 * 
	 * @return string|boolean
	 */
	public function get_endpoint( $endpoint = '' ) {

		// If it's a file default to front.
		if ( $this->is_file ) {

			return 'front';

		}

		$url = parse_url( $_SERVER[ 'REQUEST_URI' ] );

		// Split the path into parts.
		$dirty_parts = explode( '/', $url[ 'path' ] );

		$clean_parts = array();

		// Loop through and remove empty parts.
		foreach ( $dirty_parts as $part ) {

			// Remove any PHP file extensions.
			$part = str_replace( '.php', '', $part );

			// Remove the bad characters.
			$part = sanitise_text( $part, '~[^A-Za-z0-9_[-]]~' );

			// If it's blank, don't add it.
			if ( '' != $part ) {

				$clean_parts[] = $part;

			}

		}

		// Check if the endpoint isn't for the front-end.
		if ( ! empty( $clean_parts ) && in_array( $clean_parts[ 0 ], array( 'dashboard', 'system', 'auth', 'api' ), true ) ) {

			$selected = $clean_parts[ 0 ];

		} else {

			$selected = 'front';

		}

		// Are we checking an endpoint?
		if ( '' != $endpoint ) {

			// Does the selected endpoint match the check?
			if ( $endpoint == $selected ) {

				return true;

			} else {

				return false;

			}

		}

		return $selected;

	}

	/**
	 * Set the applications timezone.
	 * 
	 * @since 0.1.0
	 * 
	 * @return string
	 */
	public function set_timezone() {

		// Get the blog timezone.
		$timezone = blog_timezone();

		// Get all timezones.
		$timezones = DateTimeZone::listIdentifiers( DateTimeZone::ALL );

		// Did we get a valid timezone?
		if ( ! in_array( $timezone, $timezones, true ) ) {

			// Set the default timezone.
			$timezone = @date_default_timezone_get();

		}

		// Set the server timezone.
		return date_default_timezone_set( $timezone );

	}

	/**
	 * Check if the request is for a file.
	 * 
	 * This function checks whether the current request is for a file
	 * on the server rather than an endpoint within the application.
	 * 
	 * @since 0.1.1
	 * 
	 * @return boolean
	 */
	public function maybe_is_file() {

		// A list of common file types.
		$known_types = array(
			'html', 'htm', 'xml', 'json', 'txt', 'rtf', 'pdf', 'php',
			'js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'mov',
			'mp3', 'mp4', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
			'zip', 'tar', 'tar.gz'
		);

		$path = pathinfo( $_SERVER[ 'REQUEST_URI' ], PATHINFO_EXTENSION );

		// Is the file type present in the array?
		if ( in_array( $path, $known_types, true ) ) {

			return true;

		}

		return false;

	}

}

