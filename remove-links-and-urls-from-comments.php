<?php
/*
Plugin Name: Remove links and URLs from comment text
Plugin URI: https://marc.tv
Description: A basic plugin that prevents Wordpress after 30 days from automatically creating links in the comments section.
Version: 1.1
Author: MarcDK
Author URI: https://marc.tv
*/


function filter_text( $comment_text, $comment = null ) {
	$commentdate = strtotime( $comment->comment_date );

	if ( $commentdate < strtotime( '-30 days' ) ) {
		$link_pattern = "/<a[^>]*>(.*)<\/a>/iU";
		$string       = preg_replace( $link_pattern, "$1", $comment_text );
		$string = preg_replace('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', ' ', $string);
	}

	return $string;
}

add_filter( 'comment_text', 'filter_text', 10, 2 );


?>