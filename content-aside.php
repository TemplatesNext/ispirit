<?php
/**
 * The default template for displaying aside post
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */
?>
<?php
	global  $ispirit_data;

	$post_thumb_type = rwmb_meta('ispirit_post_thumb_type', $post->ID);
	$post_thumb_image = rwmb_meta('ispirit_post_thumb_image', 'type=image&size=blog-image', $post->ID);
	$thumb_video_url = esc_url(rwmb_meta('ispirit_thumb_video_url', $post->ID));
	$thumb_slider_images = rwmb_meta('ispirit_thumb_slider_images', 'type=image&size=blog-image', $post->ID);
	$thumb_link_type = rwmb_meta('ispirit_thumb_link_type', $post->ID);
	
	$thumb_full_url = "";
	$thumb_class = "sp-entry-thumb";
	
	$archive_layout_type = $ispirit_data['archive-layout-type'];
	$hide_categories = $ispirit_data['archive-show-categories'];
		
	if( $archive_layout_type == 1 ) {
		$total_column = 1;
	} else {
		$total_column = $ispirit_data['archive-total-columns'];
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
		$thumb_resized = aq_resize( $thumb_img_url, $th_width, $th_height, true ); //resize & crop the image
		$thumb_full_url = $thumb_img_url;
	}
	
	if( ! has_post_thumbnail() && $post_thumb_type == 0  )
	{
		$thumb_class = "no-entry-thumb";
	}
		
?>		


<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="nx-post-border">
        <header class="entry-header">
            <div class="entry-thumbnail <?php echo $thumb_class; ?>">
            <?php
                if( $post_thumb_type == 0 ) {
                    if( has_post_thumbnail() && ! post_password_required() )
                    {
                        echo "<img src=\"".$thumb_resized."\" class=\"post-th-image\" alt=\"\" >";
                        $thumb_full_url = $thumb_img_url;
                    }
                } 
                elseif ($post_thumb_type == 1 && !empty($post_thumb_image))
                {
                    foreach ( $post_thumb_image as $post_thumb_image_item )
                    {
                        $post_thumbnail_img = $post_thumb_image_item['url'];
                    }
                    $thumb_resized = aq_resize( $post_thumbnail_img, $th_width, $th_height, true ); //resize & crop the image
                    echo "<img src=\"".$thumb_resized."\" alt=\"\">";
                    $thumb_full_url = $post_thumbnail_img;
                }
                elseif ($post_thumb_type == 3)
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
                elseif ($post_thumb_type == 2)
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
            	<div class="sp-blog-overlay"></div>
                <div class="sp-blog-icons">
                    <a href="<?php echo $thumb_full_url; ?>" class="mag-pop-img"><i class="fa fa-link"></i></a>
                    <a href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
                </div>
            <?php } ?>
            </div>
            
    		<?php if( $archive_layout_type=="3" ) { echo '<div class="meta-pad">'; } ?>
            <h1 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h1>
            <?php
				if ( is_sticky() && is_home() && ! is_paged() )
				{
					echo '<span class="sp-featured-post"><span class="genericon genericon-pinned"></span><span class="sp-sticky">' . __( 'Sticky', 'ispirit' ) . '</span></span>';
				}			
            ?>
            <span class="sp-metawrap">
            	<?php if( $archive_layout_type == 1 ) { ?>
            		<span class="sp-day"><?php echo get_the_date('j'); ?></span>
                	<span class="sp-year"><?php echo get_the_date('M y'); ?></span>
                <?php } else { ?>
            		<span class="sp-day"><?php echo get_the_date('j M, Y'); ?></span>                
                <?php } ?>                
            </span>

            <span class="entry-meta">
                <?php ispirit_entry_meta_blog_standard($hide_categories); ?>
            </span><!-- .entry-meta -->
            <?php if( $archive_layout_type=="3" ) { echo '</div>'; } ?>
        </header><!-- .entry-header -->
    
        <div class="entry-summary">
            <?php
                $exc_length = 32;
                echo isprit_excerpt($exc_length);
            ?>
        </div><!-- .entry-summary -->
        
        <div class="sp-readmore">
        	<a class="sp-continue" href="<?php the_permalink(); ?>"><?php _e('Read More &#8250;','ispirit'); ?></a>
        </div>
    	
        <div class="sp-tagncomm">
        	<span>
			<?php
                $tag_list = get_the_tag_list( '', __( ', ', 'ispirit' ) );
                if ( $tag_list ) {
                    echo '<span class="tags-links">' . $tag_list . '</span>';
                }		
            ?>
            </span>
            <?php if ( comments_open() ) { ?>
                <span class="comments-wrapper">
                    <a href="<?php the_permalink(); ?>#comments">
                        <span class="sp-blog-comment"><?php comments_number(__('0 Comments', 'ispirit'), __('1 Comment', 'ispirit'), __('% Comments', 'ispirit')); ?></span>
                    </a>
                </span>
            <?php } ?>             
        </div>
        
    </div>    
</div><!-- #post -->
