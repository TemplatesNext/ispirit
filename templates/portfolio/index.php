<?php
$blog_use_excerpt = true;
$post_format = 'excerpt';

$classes = nx_folio_display_term_classes( $atts['taxonomy'] );

if( $atts['layout'] == 1 )
{
	include( 'content-gallery.php' );
} else
{
	include( 'content-standard.php' );	
}

?>
