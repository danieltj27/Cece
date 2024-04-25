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
 * The API controller.
 * 
 * @package Cece
 * 
 * @since 0.1.0
 */
class API extends Controller {

	/**
	 * The class name reference.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $class = __CLASS__;

	/**
	 * Register routes for this controller.
	 * 
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function __construct() {

		// Fire before controller event.
		do_event( 'api/controller/before', array( 'class' => $this->class ) );

		$this->get( 'api/', array( $this->class, 'heartbeat' ) );
		$this->post( 'api/media-pagination/', array( $this->class, 'media_pagination' ), is_author() );

		// Fire after controller event.
		do_event( 'api/controller/after', array( 'class' => $this->class ) );

	}

	/**
	 * The API heartbeat.
	 * 
	 * @since 0.1.0
	 * 
	 * @return mixed
	 */
	public static function heartbeat() {

		// Create API response.
		$response = array(
			'url' => api_url(),
			'status' => 200,
			'message' => 'A connection to the API was established.',
			'data' => array()
		);

		return self::json( $response );

	}

	/**
	 * Media pagination
	 * 
	 * @since 0.1.0
	 * 
	 * @return mixed
	 */
	public static function media_pagination() {

		// Setup the return data.
		$data = array(
			'offset' => 0,
			'end' => false,
			'html' => ''
		);

		// Get the current offset.
		$offset = ( isset( $_POST[ 'count' ] ) ) ? (int) $_POST[ 'count' ] : 0;

		// Get the next set of media.
		$media = get_media(
			array(
				'orderby' => 'uploaded_at',
				'order' => 'DESC',
				'limit' => blog_per_page(),
				'offset' => $offset
			)
		);

		// Update the offset.
		$data[ 'offset' ] = $offset + count( $media );

		// Did we get anything back?
		if ( ! empty( $media ) ) {

			// Loop through each media item.
			foreach ( $media as $file ) {

				// Add the HTML to response.
				$data[ 'html' ] .= "<li class='media__item'><a href='#' class='insert-media' data-syntax='![" . $file->get_filename() . "](" . $file->get_url() . ")' style='background-image: url(" . $file->get_url() . ");' tabindex='0' role='link'><span>" . $file->get_filename() . "</span><br /><span>" . $file->get_size() . "</span></a></li>";

			}

		} else {

			// Set flag to hide load more.
			$data[ 'end' ] = true;

		}

		// See if we have any more media.
		$media = get_media(
			array(
				'orderby' => 'uploaded_at',
				'order' => 'DESC',
				'limit' => blog_per_page(),
				'offset' => $data[ 'offset' ]
			)
		);

		// Have we reached the end?
		if ( empty( $media ) ) {

			// No more media.
			$data[ 'end' ] = true;

		}

		// Return as a JSON object.
		echo json_encode( $data );

		// Stop file execution here.
		die();

	}

}
