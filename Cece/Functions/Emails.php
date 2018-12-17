<?php

/**
 * Cece
 * (c) 2018, Daniel James
 * 
 * Email functions
 * 
 * @package Cece
 */

if ( ! defined( 'CECE' ) ) {

	die();

}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send an email from the blog.
 * 
 * @uses PHPMailer
 * 
 * @since 0.1.0
 * 
 * @param string $to      The address to send to.
 * @param string $subject The email subject line.
 * @param string $message The email message.
 * @param mixed  $html    The HTML email flag.
 * 
 * @return mixed
 */
function email( $to, $subject, $message, $html = false ) {

	// Create new PHPMailer instance.
	$mail = new PHPMailer( false );

	// Set the email options.
	$mail->setFrom( blog_email(), blog_name() );
	$mail->addAddress( $to );
	$mail->Subject = $subject;
	$mail->Body = $message;

	// Set default to HTML.
	$mail->isHTML( $html );

	return $mail->send();

}
