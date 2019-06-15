<?php

/**
 * Cece
 * (c) 2019, Daniel James
 * 
 * @package Cece
 */

define( 'CECE', true );
define( 'CECEPATH', dirname( __FILE__ ) . '/' );
define( 'CECEAPP', CECEPATH . 'Cece/' );
define( 'CECECONTENT', CECEPATH . 'Content/' );
define( 'CECEEXTEND', CECECONTENT . 'Extensions/' );
define( 'CECETHEMES', CECECONTENT . 'Themes/' );
define( 'CECEDIR', DIRECTORY_SEPARATOR );
define( 'CECEEXT', '.php' );

// Load the app engine.
require_once( CECEAPP . 'App.php' );

// Start the application.
$App = new App;
$App->init();

