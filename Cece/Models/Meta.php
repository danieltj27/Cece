<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * The meta model.
 * 
 * @package Cece
 * 
 * @since 0.1.0
 */
class Meta extends Model {

	/**
	 * The meta data object ID.
	 * 
	 * @since 0.1.0
	 * 
	 * @var int
	 */
	public $object_ID = 0;

	/**
	 * The meta type.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $meta_type = '';

	/**
	 * The meta key.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $meta_key = '';

	/**
	 * The meta value.
	 * 
	 * @since 0.1.0
	 * 
	 * @var string
	 */
	public $meta_value = '';

	/**
	 * Create a new meta instance.
	 * 
	 * @since 0.1.0
	 * 
	 * @param int $id The meta ID.
	 * 
	 * @return void
	 */
	public function __construct( $id = 0 ) {

		if ( 0 !== $id ) {

			$this->fetch( $id );

		}

	}

	/**
	 * Fetch a piece of meta.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean|array
	 */
	public function fetch() {

		// Bail if the key/type is blank.
		if ( '' == $this->meta_key || '' == $this->meta_type ) {

			return false;

		}

		// Create new database connection.
		$db = new Database;

		// Prepare the select statement.
		$query = $db->connection->prepare( 'SELECT * FROM ' . $db->prefix . 'meta WHERE meta_type = ? AND meta_key = ? LIMIT 1' );

		// Bind the parameter to the query.
		$query->bindParam( 1, $this->meta_type );
		$query->bindParam( 2, $this->meta_key );

		// Execute the query.
		$query->execute();

		// Return the values.
		$result = $query->fetch();

		// Did we catch anything?
		if ( false === $result ) {

			return false;

		}

		// Set the meta values.
		$this->ID = $result[ 'id' ];
		$this->object_ID = $result[ 'object_id' ];
		$this->meta_type = $result[ 'meta_type' ];
		$this->meta_key = $result[ 'meta_key' ];
		$this->meta_value = $result[ 'meta_value' ];

		return $result;

	}

	/**
	 * Save a piece of meta.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function save() {

		// Bail if the key/type is blank.
		if ( '' == $this->meta_key || '' == $this->meta_type ) {

			return false;

		}

		// Create new database connection.
		$db = new Database;

		// Prepare the select statement.
		$query = $db->connection->prepare( 'SELECT * FROM ' . $db->prefix . 'meta WHERE meta_type = ? AND meta_key = ? LIMIT 1' );

		// Bind the parameter to the query.
		$query->bindParam( 1, $this->meta_type );
		$query->bindParam( 2, $this->meta_key );

		// Execute the query.
		$query->execute();

		// Get the result.
		$result = $query->fetch();

		// Does the meta data already exist?
		if ( false !== $result ) {

			// Prepare the update statement.
			$query = $db->connection->prepare( 'UPDATE ' . $db->prefix . 'meta SET object_id = ?, meta_type = ?, meta_key = ?, meta_value = ? WHERE ID = ?' );

			// Bind parameters to the query.
			$query->bindParam( 1, $this->object_ID );
			$query->bindParam( 2, $this->meta_type );
			$query->bindParam( 3, $this->meta_key );
			$query->bindParam( 4, $this->meta_value );
			$query->bindParam( 5, $this->ID );

		} else {

			// Prepare the insert statement.
			$query = $db->connection->prepare( 'INSERT INTO ' . $db->prefix . 'meta ( object_id, meta_type, meta_key, meta_value ) VALUES ( ?, ?, ?, ? )' );

			// Bind parameters to the query.
			$query->bindParam( 1, $this->object_ID );
			$query->bindParam( 2, $this->meta_type );
			$query->bindParam( 3, $this->meta_key );
			$query->bindParam( 4, $this->meta_value );

		}

		// Execute the query.
		$query->execute();

		return true;

	}

	/**
	 * Delete a piece of meta.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function delete() {

		// Create new database connection.
		$db = new Database;

		// Prepare the delete statement.
		$query = $db->connection->prepare( 'DELETE FROM ' . $db->prefix . 'meta WHERE meta_type = ? AND meta_key = ? LIMIT 1' );

		// Bind the parameter to the query.
		$query->bindParam( 1, $this->meta_type );
		$query->bindParam( 2, $this->meta_key );

		// Execute the query.
		$query->execute();

		return $this->reset();

	}

	/**
	 * Reset the meta instance.
	 * 
	 * @since 0.1.0
	 * 
	 * @return boolean
	 */
	public function reset() {

		// Reset all meta values.
		$this->ID = 0;
		$this->object_ID = 0;
		$this->meta_type = '';
		$this->meta_key = '';
		$this->meta_value = '';

		return true;

	}

}

