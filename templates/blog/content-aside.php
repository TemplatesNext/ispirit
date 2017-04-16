<?php 
/**
 * Template for aside posts
 *
 * @package templatesnext Shortcode
 */
$classes[] = 'nx-post-box'; 


	global  $ispirit_data;

	$post_thumb_type = rwmb_meta('ispirit_post_thumb_type', $post->ID);
	$post_thumb_image = rwmb_meta('ispirit_post_thumb_image', 'type=image&size=blog-image', $post->ID);
	$thumb_video_url = esc_url(rwmb_meta('ispirit_thumb_video_url', $post->ID));
	$thumb_slider_images = rwmb_meta('ispirit_thumb_slider_images', 'type=image&size=blog-image', $post->ID);
	$thumb_link_type = rwmb_meta('ispirit_thumb_link_type', $post->ID);
	
	$thumb_full_url = "";
	$thumb_class = "nx-entry-thumb";
	
	$archive_layout_type = $atts['blog_layout'];
	$hide_categories = $atts['meta_cat'];
		
	if( $atts['blog_layout'] == '1' ) {
		$total_column = 1;
	} else {
		$total_column = $atts["columns"];
	}
		
	$post_thumb_list = "";

	$th_width = 1200;
	$th_height = 600;
		
	if( $total_column == 1 )
	{
		$th_width = 1200;
		$th_height = 600;		
	} else if( $total_column == 2 )
	{
		$th_width = 600;
		$th_height = 360;			
	} else if( $total_column == 3 )
	{
		$th_width = 400;
		$th_height = 240;			
	} else if( $total_column == 4 )
	{
		$th_width = 300;
		$th_height = 180;		
	}
	
	if( has_post_thumbnail() )
	{
		$thumb_image_id = get_post_thumbnail_id();
		$thumb_img_url = wp_get_attachment_url( $thumb_image_id,'full' ); //get full URL to image (use "large" or "medium" if the images too big)
		$thumb_resized = nx_image_resize( $thumb_img_url, $th_width, $th_height, true, true );
		$thumb_full_url = $thumb_img_url;
	}
	
	if( ! has_post_thumbnail() && $post_thumb_type == 0  )
	{
		$thumb_class = "no-entry-thumb";
	}
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="nx-post-border">
    	<header class="entry-header">
            <div class="nx-entry-thumbnail <?php echo $thumb_class; ?>">
                <?php
                    if( $post_thumb_type == 0 ) {
                        if( has_post_thumbnail() && ! post_password_required() )
                        {
                            echo "<img src=\"".$thumb_resized['url']."\" class=\"post-th-image\" alt=\"\" >";
                            $thumb_full_url = $thumb_img_url;
                        }
                    } 
                    else if ($post_thumb_type == 1)
                    {
                        foreach ( $post_thumb_image as $post_thumb_image_item )
                        {
                            $post_thumbnail_img = $post_thumb_image_item['url'];
                        }
                        $thumb_resized = aq_resize( $post_thumbnail_img, $th_width, $th_height, true ); //resize & crop the image
                        echo "<img src=\"".$thumb_resized."\" alt=\"\">";
                        $thumb_full_url = $post_thumbnail_img;
                    }
                    else if ($post_thumb_type == 3)
                    {
                        echo '<div class="nx-owl-carousel">';
                        foreach ( $thumb_slider_images as $thumb_slider_image )
                        {
                            $post_thumb_slider_img = $thumb_slider_image['url'];
							if ( $thumb_link_type == 1 )
							{
								echo '<div><a href="'.$post_thumb_slider_img.'" class="mag-pop-img"><img src="'.aq_resize( $post_thumb_slider_img, $th_width, $th_height, true ).'"></a></div>';
							} else
							{
								echo '<div><a href="'.get_permalink().'"><img src="'.aq_resize( $post_thumb_slider_img, $th_width, $th_height, true ).'"></a></div>';
							}
													
                        }
                        echo '</div>';
                        $thumb_full_url = "";
                                                
                    }	
                    else if ($post_thumb_type == 2)
                    {
                        if (strpos($thumb_video_url,'youtube') || strpos($thumb_video_url,'youtu.be')){
                            echo do_shortcode("[nx_youtube url=\"$thumb_video_url\" class=\"nx-vimeo-cudtom-class\"]");
                        } else {
                            echo do_shortcode("[nx_vimeo url=\"$thumb_video_url\" class=\"nx-vimeo-cudtom-class\"]");								
                        }
                        $thumb_full_url = "";							
                    }	        
                ?>
                <?php if( $thumb_full_url != "" ) {?>    
                    <div class="nx-blog-overlay"></div>
                    <div class="nx-blog-icons">
                        <a href="<?php echo $thumb_full_url; ?>" class="mag-pop-img"><i class="fa fa-search-plus"></i></a>
                        <a href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
                    </div>
                <?php } ?>
            </div>
			<?php if ( $atts['title'] ) : ?>
				<h1 class="nx-entry-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h1>
			<?php endif; ?>
			<?php
				if ( is_sticky() && is_home() && ! is_paged() )
				{
					echo '<span class="nx-featured-post"><span class="genericon genericon-pinned"></span><span class="nx-sticky">' . __( 'Sticky', 'ispirit' ) . '</span></span>';
				}			
            ?>
                     
		</header>
        
		<div class="nx-post-content">
			<?php if ( $atts['content'] ) : ?>
			<div class="nx-entry-summary">
                <?php
					$exc_length = 32;
					echo nx_custom_excerpt($exc_length);            
					//the_excerpt(); 
				?>
			</div><!-- .entry-summary -->
                <?php if ( $atts['read_more'] ) {?>
                    <div class="sp-readmore">
                        <a class="sp-continue colored" href="<?php the_permalink(); ?>"><?php _e('Read More &#8250;','ispirit'); ?></a>
                    </div>
                <?php } ?>             
			<?php endif; ?>

			<?php include('entry-meta.php'); ?>

		</div>
	</div>
</div>
