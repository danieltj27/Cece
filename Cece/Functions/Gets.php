<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Get functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

/**
 * Get posts based on a set of query options.
 * 
 * @uses Query
 * 
 * @since 0.1.0
 * 
 * @param array   $opts      The options for the query.
 * @param boolean $set_pages Flag to set the total pages count.
 * 
 * @return array
 */
function get_posts( $opts = array(), $set_pages = false ) {

	// Force the table name.
	$opts[ 'table' ] = 'posts';

	// Get the posts.
	$items = new Query( $opts );

	$posts = array();

	if ( ! empty( $items->items ) ) {

		if ( false !== $set_pages ) {

			set_total_pages( $items->count );

		}

		// Format each item.
		foreach ( $items->items as $item ) {

			$post = new Post;

			$post->format_array( $item );

			$posts[] = $post;

		}

	}

	return $posts;

}

/**
 * Get menus based on a set of query options.
 * 
 * @uses Query
 * 
 * @since 0.1.0
 * 
 * @param array   $opts      The options for the query.
 * @param boolean $set_pages Flag to set the total pages count.
 * 
 * @return array
 */
function get_menus( $opts = array(), $set_pages = false ) {

	// Force the table name.
	$opts[ 'table' ] = 'menus';

	// Get the menus.
	$items = new Query( $opts );

	$menus = array();

	if ( ! empty( $items->items ) ) {

		if ( false !== $set_pages ) {

			set_total_pages( $items->count );

		}

		// Format each item.
		foreach ( $items->items as $item ) {

			$menu = new Menu;

			$menu->format_array( $item );

			$menus[] = $menu;

		}

	}

	return $menus;

}

/**
 * Get tags based on a set of query options.
 * 
 * @uses Query
 * 
 * @since 0.1.0
 * 
 * @param array   $opts      The options for the query.
 * @param boolean $set_pages Flag to set the total pages count.
 * 
 * @return array
 */
function get_tags( $opts = array(), $set_pages = false ) {

	// Force the table name.
	$opts[ 'table' ] = 'tags';

	// Get the tags.
	$items = new Query( $opts );

	$tags = array();

	if ( ! empty( $items->items ) ) {

		if ( false !== $set_pages ) {

			set_total_pages( $items->count );

		}

		// Format each item.
		foreach ( $items->items as $item ) {

			$tag = new Tag;

			$tag->format_array( $item );

			$tags[] = $tag;

		}

	}

	return $tags;

}

/**
 * Get media based on a set of query options.
 * 
 * @uses Query
 * 
 * @since 0.1.0
 * 
 * @param array   $opts      The options for the query.
 * @param boolean $set_pages Flag to set the total pages count.
 * 
 * @return array
 */
function get_media( $opts = array(), $set_pages = false ) {

	// Force the table name.
	$opts[ 'table' ] = 'media';

	// Get the media.
	$items = new Query( $opts );

	$files = array();

	if ( ! empty( $items->items ) ) {

		if ( false !== $set_pages ) {

			set_total_pages( $items->count );

		}

		// Format each item.
		foreach ( $items->items as $item ) {

			$media = new Media;

			$media->format_array( $item );

			$files[] = $media;

		}

	}

	return $files;

}

/**
 * Get users based on a set of query options.
 * 
 * @uses Query
 * 
 * @since 0.1.0
 * 
 * @param array   $opts      The options for the query.
 * @param boolean $set_pages Flag to set the total pages count.
 * 
 * @return array
 */
function get_users( $opts = array(), $set_pages = false ) {

	// Force the table name.
	$opts[ 'table' ] = 'users';

	// Get the users.
	$items = new Query( $opts );

	$users = array();

	if ( ! empty( $items->items ) ) {

		if ( false !== $set_pages ) {

			set_total_pages( $items->count );

		}

		// Format each item.
		foreach ( $items->items as $item ) {

			$user = new User;

			$user->format_array( $item );

			$users[] = $user;

		}

	}

	return $users;

}

/**
 * Get settings based on a set of query options.
 * 
 * @uses Query
 * 
 * @since 0.1.0
 * 
 * @param array   $opts      The options for the query.
 * @param boolean $set_pages Flag to set the total pages count.
 * 
 * @return array
 */
function get_settings( $opts = array(), $set_pages = false ) {

	// Force the table name.
	$opts[ 'table' ] = 'settings';

	// Get the settings.
	$items = new Query( $opts );

	$settings = array();

	if ( ! empty( $items->items ) ) {

		if ( false !== $set_pages ) {

			set_total_pages( $items->count );

		}

		// Format each item.
		foreach ( $items->items as $item ) {

			$setting = new Setting;

			$setting->format_array( $item );

			$settings[] = $setting;

		}

	}

	return $settings;

}

/**
 * Returns the list of menu items by location.
 * 
 * This function returns an array of menu items based
 * on the given location parameter. If multiple menus
 * have the same location id then the most recently
 * created menu will be used.
 * 
 * @since 0.1.0
 * 
 * @param string $location The menu location ID.
 * 
 * @return array
 */
function get_menu_links( $location = '' ) {

	// Get the menu items.
	$menu = get_menus(
		array(
			'where' => array(
				array(
					'key' => 'location',
					'value' => $location
				)
			),
			'limit' => 1,
			'offset' => 0
		)
	);

	// Did we get anything back?
	if ( empty( $menu ) ) {

		return array();

	}

	return $menu[ 0 ]->menu_list;
	
}

/**
 * Return the current page number.
 * 
 * @since 0.1.0
 * 
 * @return int $page
 */
function get_page_num() {

	// Is the page parameter set?
	if ( ! isset( $_GET[ 'page' ] ) ) {

		$page = 0;

	} else {

		$page = (int) $_GET[ 'page' ];

	}

	// Are we on page 0?
	if ( 0 === $page ) {

		// Default to 1.
		$page = 1;

	}

	return $page;

}

/**
 * Get the total number of pages.
 * 
 * @since 0.1.0
 * 
 * @return int
 */
function get_total_pages() {

	global $_total_pages;

	return (int) $_total_pages;

}

/**
 * Set the total number of pages.
 * 
 * Sets the total number of pages based on the amount
 * of items given and the value of the items per page.
 * 
 * @since 0.1.0
 * 
 * @param int $items The total number of items returned.
 * 
 * @return int
 */
function set_total_pages( $items = 0 ) {

	global $_total_pages;

	// Force the item count to an int.
	$items = (int) $items;

	// Falback if we get an invalid item count.
	if ( 0 == $items ) {

		$items = 1;

	}

	// Get the total number of pages.
	$_total_pages = ceil( $items / blog_per_page() );

	// Convert from float to int.
	$_total_pages = (int) $_total_pages;

	return $_total_pages;

}

/**
 * Return a link for pagination.
 * 
 * Returns the link for the next and previous buttons
 * based on the current page and the options set.
 * 
 * @since 0.1.0
 * 
 * @param string $url The URL to append the page number to.
 * @param string $dir The direction, accepts either 'next' or 'previous'.
 * 
 * @return boolean|string
 */
function get_pagination_link( $url = '', $dir = 'previous' ) {

	// Which direction are we going in?
	if ( 'previous' != $dir && 'next' != $dir ) {

		return false;

	}

	// Get the current page number offset.
	$page_num = ( 'previous' == $dir ) ? get_page_num() - 1 : get_page_num() + 1;

	// Bail on page one.
	if ( 'previous' == $dir && 1 >= get_page_num() ) {

		return false;

	}

	// Bail on the last page.
	if ( 'next' == $dir && get_total_pages() <= get_page_num() ) {

		return false;

	}

	// Parse the URL into bits.
	$parse = parse_url( $url );

	// Set the query separater.
	$sep = ( isset( $parse[ 'query' ] ) ) ? '&' : '?';

	// Do we already have a query set?
	if ( isset( $parse[ 'query' ] ) ) {

		$sep = '&';

	} else {

		$sep = '?';

	}

	// Built the queried URL.
	return $url . $sep . 'page=' . $page_num;

}

/**
 * Return the current page offset.
 * 
 * @since 0.1.0
 * 
 * @return int $offset
 */
function get_page_offset() {

	// Get the current page number.
	$page = get_page_num();

	// Are we on page 1?
	if ( 1 === $page ) {

		return 0;

	}

	// Get the per page value.
	$per_page = blog_per_page();

	// Create the offset value.
	$offset = ( $per_page * $page ) - $per_page;

	return $offset;

}
