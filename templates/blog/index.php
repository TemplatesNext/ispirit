<?php
/**
 * index
 *
 * @package templatesnext Shortcode
 */
 
	$post_format = get_post_format();
	
	if ( empty( $post_format ) ) 
	{
		$post_format = 'standard';
		include( 'content.php' );
	} elseif ( $post_format == 'aside' ) 
	{
		include( 'content-aside.php' );
	} elseif ( $post_format == 'audio' ) 
	{
		include( 'content-audio.php' );
	} elseif ( $post_format == 'chat' ) 
	{
		include( 'content-chat.php' );
	} elseif ( $post_format == 'gallery' ) 
	{
		include( 'content-gallery.php' );
	} elseif ( $post_format == 'image' ) 
	{
		include( 'content-image.php' );
	} elseif ( $post_format == 'link' ) 
	{
		include( 'content-link.php' );
	} elseif ( $post_format == 'quote' ) 
	{
		include( 'content-quote.php' );
	} elseif ( $post_format == 'video' ) 
	{
		include( 'content-video.php' );
	} else  
	{
		include( 'content.php' );
	}
	
	$classes = nx_shortcodes_display_term_classes( $atts['taxonomy'] );



?>
