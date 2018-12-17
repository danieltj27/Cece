<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Core functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Define cookie names.
 * 
 * @since 0.1.0
 */
define( 'TEST_COOKIE', '_cc_test' );
define( 'AUTH_COOKIE', '_cc_auth' );

/**
 * Define time based constants.
 * 
 * @since 0.1.0
 */
define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS );
define( 'DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS );
define( 'TWO_DAYS_IN_SECONDS', 2 * DAY_IN_SECONDS );
define( 'WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS );
define( 'TWO_WEEKS_IN_SECONDS', 2 * WEEK_IN_SECONDS );
define( 'MONTH_IN_SECONDS', 4 * WEEK_IN_SECONDS );
define( 'SIX_MONTHS_IN_SECONDS', 6 * MONTH_IN_SECONDS );
define( 'YEAR_IN_SECONDS', 12 * MONTH_IN_SECONDS );

/**
 * The application installed flag.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var boolean
 */
$_installed = false;

/**
 * The array of blog settings.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var array
 */
$_settings = array();

/**
 * The home URL default value.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var string
 */
$_home_url = '';

/**
 * The logged in default value.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var boolean
 */
$_logged_in = false;

/**
 * The current user default object.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var mixed
 */
$_current_user = false;

/**
 * The application post types.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var array
 */
$_post_types = array();

/**
 * Current app notices.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var array
 */
$_notices = array();

/**
 * The extension event listeners.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var array
 */
$_event_listeners = array();

/**
 * The currently active theme.
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var array
 */
$_active_theme = array();

/**
 * The total number of pages (for pagination).
 * 
 * @since 0.1.0
 * 
 * @access private
 * 
 * @var int
 */
$_total_pages = 1;

/**
 * Check if the app is installed.
 * 
 * @since 0.1.0
 * 
 * @param boolean $cached Return the cached version.
 * 
 * @return boolean
 */
function is_app_installed( $cached = true ) {

	global $_installed;

	// Are we using the cached value?
	if ( true === $cached ) {

		return $_installed;

	}

	// Do we have a config file?
	if ( ! file_exists( CECEPATH . 'config.php' ) ) {

		return false;

	}

	// Do we have valid database constants?
	if (
		! defined( 'DB_HOST' ) ||
		! defined( 'DB_PORT' ) ||
		! defined( 'DB_NAME' ) ||
		! defined( 'DB_USERNAME' ) ||
		! defined( 'DB_PASSWORD' ) ||
		! defined( 'DB_CHARSET' ) ||
		! defined( 'DB_PREFIX' )
	) {

		return false;

	}

	// Create a database instance.
	$db = new Database;

	// Did the connection work?
	if ( ! $db || is_null( $db->connection ) ) {

		return false;

	}

	// Get the total user count.
	$query = $db->connection->query( 'SELECT COUNT(*) FROM ' . $db->prefix . 'users' );
	$result = $query->fetch();

	// Did we get a result?
	if ( ! isset( $result[ 'count(*)' ] ) || '0' == $result[ 'count(*)' ] ) {

		return false;

	}

	// Set the cache value.
	$_installed = true;

	return $_installed;

}

/**
 * Get the requested path.
 * 
 * Returns a sanitised version of the requested
 * path part of the URL.
 * 
 * @since 0.1.0
 * 
 * @return string
 */
function get_path() {

	// Get the requested URL.
	$url = parse_url( $_SERVER['REQUEST_URI'] );

	// Convert to proper HTML entities.
	$url = htmlentities( $url['path'] );

	// Strip the invalid characters.
	$path = sanitise_text( $url, '~[^A-Za-z0-9-_\/]~' );

	// Trim and re-add slashes safely.
	$path = '/' . trim( $path, '/' ) . '/';

	// Did we get a blank path?
	if ( '//' == $path ) {

		$path = '/';

	}

	return $path;

}

/**
 * Creates a safe path for posts.
 * 
 * This function can end up being quite expensive on the
 * server if the unique flag is set to true. When set to
 * true, it'll append a number incremented by one to the
 * end of the path to make it unique.
 * 
 * For example, if a post exists with a path of `hello-world`,
 * then it'll append a number to the end of the path to make
 * `hello-world-2` (or 3, 4 etc) until a path is unique.
 * 
 * @since 0.1.0
 * 
 * @param string  $path    The path to filter.
 * @param boolean $unique  The flag to force a unique path.
 * @param int     $post_id The post ID the path is for.
 * 
 * @return string
 */
function create_path( $path, $unique = false, $post_id = 0 ) {

	// Convert to lower-case.
	$path = strtolower( $path );

	// Trim whitespace from the path.
	$path = trim( $path );

	// Strip inner white space.
	$path = str_replace( ' ', '-', $path );

	// Replace multiple hyphens with singular ones.
	$path = preg_replace( '~-+~', '-', $path );

	// Remove any other characters.
	$path = sanitise_text( $path, '~[^A-Za-z0-9-_]~' );

	// Should we create a unique path?
	if ( false !== $unique ) {

		// Create a new post instance.
		$post = new Post;

		// Is the current path unique?
		if ( false !== $post->fetch( $path, 'path' ) && $post->ID != $post_id ) {

			// Set the increment index.
			$index = 2;

			// Set the break point.
			$catch = false;

			// Loop through until we find a unique path.
			while ( $catch === false ) { 

				// Create the new path (with index).
				$temp_path = rtrim( $path, '-' ) . '-' . (string) $index;

				// Increment the index.
				$index++;

				// Does this path already exist?
				if ( false === $post->fetch( $temp_path, 'path' ) ) {

					// Set unique path.
					$path = $temp_path;

					// A unique path was found.
					$catch = true;

				}

			}

		}

	}

	return $path;

}

/**
 * Parse the Markdown of a posts content.
 * 
 * This function will convert the post content of a given post
 * from Markdown into HTML. It requires a Post object and allows
 * for certain options to be set to alter how the content will
 * be returned from it.
 * 
 * @uses Parsedown
 * 
 * @since 0.1.0
 * 
 * @param object  $post The post object containing post content.
 * @param boolean $opts The options for the Parsedown instance.
 * 
 * @return string
 */
function markify_content( $post, $opts = array() ) {

	// Do we have a post object?
	if ( ! $post instanceof Post ) {

		return '';

	}

	// Setup the Parsedown option defaults.
	$defaults = array(
		'safe_mode' => true,
		'inline_only' => false,
		'line_breaks' => true,
		'escape_html' => false,
		'convert_urls' => true
	);

	// Merge the defaults to the given options.
	$opts = array_merge( $defaults, $opts );

	// Create a new Parsedown object.
	$Parsedown = new Parsedown;

	// Set the Parsedown options.
	$Parsedown->setSafeMode( $opts[ 'safe_mode' ] );
	$Parsedown->setBreaksEnabled( $opts[ 'line_breaks' ] );
	$Parsedown->setMarkupEscaped( $opts[ 'escape_html' ] );
	$Parsedown->setUrlsLinked( $opts[ 'convert_urls' ] );

	// Are we parsing inline only?
	if ( true === $opts[ 'inline_only' ] ) {

		return $Parsedown->line( $post->post_content );

	} else {

		return $Parsedown->text( $post->post_content );

	}

}

/**
 * Return an excerpt of the post content.
 * 
 * @since 0.1.0
 * 
 * @param object $post   The post object containing post content.
 * @param int    $length The length of the excerpt. Default is 140.
 * 
 * @return string $content
 */
function content_excerpt( $post, $length = 140 ) {

	// Do we have a post object?
	if ( ! $post instanceof Post ) {

		return '';

	}

	// Get the post content.
	$content = markify_content( $post );

	// Check the content length.
	if ( strlen( $content ) > $length ) {

		// Strip any HTML.
		$content = strip_tags( $content );

		// Trim it.
		$content = substr( $content, 0, $length );

		// Add the dot, dot, dot.
		$content = $content . "&hellip;";

	}

	return $content;

}

/**
 * Convert password into a secure hash.
 * 
 * Takes the given password in plain text and converts
 * it into a cryptographically secure hash for storing
 * in a database using the `password_hash` function.
 * 
 * Uses the default hashing algorithm (currently Bcrypt)
 * unless the server is running PHP 7.2 or above and has
 * the Argon2 library installed, in which that is used
 * for the algorithm instead.
 * 
 * Returns the hashed password if successfully, otherwise
 * it'll return false if the password failed to be hashed
 * for some reason.
 * 
 * @since 0.1.0
 * 
 * @param string $password The password to hash.
 * @param array  $options  The algorithm options.
 * 
 * @return boolean|string
 */
function hash_password( $password, $options = array() ) {

	// Are we using PHP 7.2 or above?
	if ( version_compare( '7.2', PHP_VERSION, '<=' ) && defined( 'PASSWORD_ARGON2I' ) && 'argon2' == blog_setting( 'flag_pass_hash' ) ) {

		$algo = PASSWORD_ARGON2I;

		// Set default options for Argon2.
		$defaults = array(
			'memory_cost' => 2048,
			'time_cost' => 4,
			'threads' => 4
		);

	} else {

		$algo = PASSWORD_BCRYPT;

		// Set default options for Bcrypt.
		$defaults = array(
			'cost' => 11,
		);

	}

	// Merge the options into the defaults.
	$options = array_merge( $defaults, $options );

	return password_hash( $password, $algo, $options );

}

/**
 * Prepares and uploads a file to the system.
 * 
 * This function is used as a wrapper for the Media API to
 * make it easier for files to be uploaded from form submissions.
 * 
 * @since 0.1.0
 * 
 * @param array $files The $_FILES array from a form.
 * 
 * @return boolean|array
 */
function prepare_file_upload( $files ) {

	// Bail if we don't get a $_FILES array.
	if ( ! is_array( $files ) ) {

		return false;

	}

	// Loop through each file upload.
	foreach ( $files as $upload ) {

		/**
		 * Check if the uploaded file is in a multi file upload
		 * array and if it isn't, force it into one.
		 */
		if ( ! is_array( $upload[ 'name' ] ) ) {

			// Create a temporary uploads array.
			$temp_upload = $upload;

			// Restructure the actual upload array.
			$upload = array(
				'name' => array(
					$temp_upload[ 'name' ]
				),
				'type' => array(
					$temp_upload[ 'type' ]
				),
				'tmp_name' => array(
					$temp_upload[ 'tmp_name' ]
				),
				'error' => array(
					$temp_upload[ 'error' ]
				),
				'size' => array(
					$temp_upload[ 'size' ]
				)
			);

		}

		// Create an index counter.
		$upload_index = 0;

		// Loop through each upload.
		foreach ( $upload[ 'name' ] as $filename ) {

			// Create the file data array.
			$file = array(
				'name' => $filename,
				'type' => $upload[ 'type' ][ $upload_index ],
				'tmp_name' => $upload[ 'tmp_name' ][ $upload_index ],
				'error' => $upload[ 'error' ][ $upload_index ],
				'size' => $upload[ 'size' ][ $upload_index ]
			);

			// Create new media instance.
			$media = new Media;

			// Set the media value.
			$media->_files = $file;

			// Try and upload the image.
			$media->upload();

			// Update the file index.
			$upload_index++;

		}

	}

	return true;

}

/**
 * Return the current search query.
 * 
 * Returns the current value in the search query
 * via the GET parameter in a HTML-safe filtered
 * format or returns false if invalid.
 * 
 * @since 0.1.0
 * 
 * @return string|boolean
 */
function get_search_query() {

	// Is there a search query?
	if ( ! isset( $_GET[ 'query' ] ) || '' == $_GET[ 'query' ] ) {

		return false;

	}

	return filter_text( $_GET[ 'query' ] );

}

/**
 * Return true
 * 
 * @since 0.1.0
 * 
 * @return true
 */
function _return_true() {

	return true;

}

/**
 * Return false
 * 
 * @since 0.1.0
 * 
 * @return false
 */
function _return_false() {

	return false;

}

/**
 * Dump and die a variable.
 * 
 * Do note that this function will call `die`
 * which will stop further script execution.
 * 
 * @since 0.1.0
 * 
 * @param mixed $var The variable to dump.
 * 
 * @return mixed
 */
function dd( $var ) {

	var_dump( $var );

	die();

}

/**
 * Debugging mode handler.
 * 
 * Checks whether the current application
 * state is in debug mode or not before we
 * load everything else.
 * 
 * @since 0.1.0
 * 
 * @return boolean
 */
function is_debugging() {

	// Check for debug mode.
	if ( defined( 'APP_DEBUG' ) && true === APP_DEBUG ) {

		return true;

	}

	return false;

}

/**
 * Returns an error message.
 * 
 * @since 0.1.0
 * 
 * @param string $text The error message to be displayed.
 * @param const  $type The type of error message to return.
 * 
 * @return mixed
 */
function no_thank_you( $text, $type = E_USER_NOTICE ) {

	// Always log error messages.
	error_log( $text );

	// Is debug mode enabled?
	if ( is_debugging() ) {

		return trigger_error( $text, $type );

	}

	return true;

}