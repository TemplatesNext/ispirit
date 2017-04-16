<?php 
/**
 * Template for portfolio standard style
 *
 * @package templatesnext Shortcode
 */
 
	$classes[] = 'nx-col-1-' . $atts['columns'];
	
	$post_thumb_type = rwmb_meta('ispirit_post_thumb_type', $post->ID);
	$post_thumb_image = rwmb_meta('ispirit_post_thumb_image', 'type=image&size=blog-image', $post->ID);
	$thumb_video_url = esc_url(rwmb_meta('ispirit_thumb_video_url', $post->ID));
	$thumb_slider_images = rwmb_meta('ispirit_thumb_slider_images', 'type=image&size=blog-image', $post->ID);
	$thumb_link_type = rwmb_meta('ispirit_thumb_link_type', $post->ID);
	$post_thumb_list = "";
	$thumb_full_url = "";
	$thumb_post_url = 1;
	$pop_height = 700;
	$pop_width = 1200;
	$image_placeholder = get_template_directory_uri() . '/images/placeholder-1200x1200.png';
	
	if( $atts['columns'] == 1 )
	{
		$th_width = 1200;
		$th_height = 800;		
	} else if( $atts['columns'] == 2 )
	{
		$th_width = 600;
		$th_height = 600;			
	} else if( $atts['columns'] == 3 )
	{
		$th_width = 600;
		$th_height = 600;		
	} else if( $atts['columns'] == 4 )
	{
		$th_width = 600;
		$th_height = 600;		
	}

	                
	if( has_post_thumbnail() )
	{
		$thumb_image_id = get_post_thumbnail_id();
		$thumb_img_url = wp_get_attachment_url( $thumb_image_id,'full' ); //get full URL to image (use "large" or "medium" if the images too big)
		$thumb_resized = aq_resize( $thumb_img_url, $th_width, $th_height, true ); //resize & crop the image
		
		$thumb_full_url = $thumb_img_url;
	}
					
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="nx-post-border">
        
        <div class="entry-thumbnail">
            	<?php
                    if($post_thumb_type == 0) 
					{
                    	if( has_post_thumbnail() && ! post_password_required() )
                        {
							echo "<img src=\"".$thumb_resized."\" class=\"post-th-image\" alt=\"\" >";
							$thumb_full_url = $thumb_img_url;
                        }
                    } else if ( $post_thumb_type == 1 && !empty($post_thumb_image) )
                    {
                        foreach ( $post_thumb_image as $post_thumb_image_item )
                        {
                        	$post_thumbnail_img = $post_thumb_image_item['url'];
                        }
                        $thumb_resized = aq_resize( $post_thumbnail_img, $th_width, $th_height, true ); //resize & crop the image
                        echo "<img src=\"".$thumb_resized."\" alt=\"\">";
						$thumb_full_url = $post_thumbnail_img;
                    } else if ($post_thumb_type == 3 && !empty($thumb_slider_images) )
                    {

						echo '<div class="nx-owl-carousel">';
						foreach ( $thumb_slider_images as $thumb_slider_image )
						{
							$post_thumb_slider_img = $thumb_slider_image['url'];
							if ( $thumb_link_type == 1 )
							{
								echo '<div><a href="'.$post_thumb_slider_img.'" class="mag-pop-img"><img src="'.aq_resize( $post_thumb_slider_img, $th_width, $th_height, true ).'" alt=""></a></div>';
							} else
							{
								echo '<div><a href="'.get_permalink().'"><img src="'.aq_resize( $post_thumb_slider_img, $th_width, $th_height, true ).'" alt=""></a></div>';
							}
						}
						echo '</div>';
						$thumb_full_url = "";
                    }
					/*	
                    else if ($post_thumb_type == 2)
                    {
                    	if (strpos($thumb_video_url,'youtube') || strpos($thumb_video_url,'youtu.be')){
                        	echo do_shortcode("[nx_youtube url=\"$thumb_video_url\" class=\"nx-vimeo-cudtom-class\"]");
                        } else {
                        	echo do_shortcode("[nx_vimeo url=\"$thumb_video_url\" class=\"nx-vimeo-cudtom-class\"]");								
                        }							
                    }
					*/
					else {
						echo "<div><img src=\"".$image_placeholder."\" alt=\"\"></div>";
						$thumb_full_url = "";
					}       
                ?>
				<?php if ( $post_thumb_type != 3 ): ?>
                    <div class="folio-overlay"></div>    
                    <div class="folioico">
                        
                            <?php if ( $thumb_full_url != "" ) : ?>
                            <a href="<?php echo aq_resize( $thumb_full_url, $pop_width, $pop_height, true ); ?>" class="nx-popup-link" title="<?php the_title(); ?>">
                                <i class="fa fa-search-plus"></i>
                            </a>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="folio-link"><i class="fa fa-link"></i></a>
                        
                        
                    </div>
            	<?php endif; ?>
                            
        </div>

		<div class="nx-post-content">

        	<div class="folio-content-wrap">
                <h1 class="entry-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                </h1>
                <?php
                    echo "<div class=\"foliocat\">" . ( nx_folio_term( $atts['taxonomy'] ) ) . "</div>";
                ?>                
                <?php if( $atts['layout']==2 ): ?>
                    <div class="entry-summary">
                        <?php
                            $exc_length = 20;
                            echo isprit_excerpt($exc_length);
                        ?>
                    </div><!-- .entry-summary -->
                <?php endif; ?>
                

            </div>

		</div><!-- .nx-post-content -->
	</div><!-- .nx-post-border -->
</div><!-- #post -->
