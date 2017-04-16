<?php
/**
 * Posts carousel index
 *
 * @package templatesnext Shortcode
 */
	$post_format = get_post_format();
	
	include( 'content.php' );
	
	$classes = nx_shortcodes_display_term_classes( $atts['taxonomy'] );



?>
