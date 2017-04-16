<?php 
/**
 * Template for team
 *
 * @package templatesnext Shortcode
 */
	$classes[] = 'nx-col-1-' . $atts['columns'];
	
	$designation = esc_attr(rwmb_meta('ispirit_designation'));
	$team_email = esc_url(rwmb_meta('ispirit_team_email'));
	$team_phone = esc_attr(rwmb_meta('ispirit_team_phone'));
	$team_twitter = esc_url(rwmb_meta('ispirit_team_twitter'));
	$team_facebook = esc_url(rwmb_meta('ispirit_team_facebook'));
	$team_gplus = esc_url(rwmb_meta('ispirit_team_gplus'));
	$team_skype = esc_attr(rwmb_meta('ispirit_team_skype'));
	$team_linkedin = esc_attr(rwmb_meta('ispirit_team_linkedin'));		
	
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
        
        <div class="team-thumbnail">
            <?php

               	if( has_post_thumbnail() && ! post_password_required() )
                {
					echo "<img src=\"".$thumb_resized."\" class=\"post-th-image\" alt=\"\" >";
                }   
            ?>

        </div>

		<div class="nx-post-content">
        	<div class="team-content-wrap">
                <h4 class="entry-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                </h4>
                <?php if($designation) {?>
                <h5>
                	<?php echo $designation; ?>
                </h5>
                <?php } ?>
                
                <div class="team-social">
                    <ul>
						<?php if ($team_twitter) {?><li><a href="<?php echo $team_twitter; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li><?php } ?>
                        <?php if ($team_facebook) {?><li><a href="<?php echo $team_facebook; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li><?php } ?>
                        <?php if ($team_gplus) {?><li><a href="<?php echo $team_gplus; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li><?php } ?>
                        <?php if ($team_skype) {?><li><a href="skype:<?php echo $team_skype; ?>" target="_blank"><i class="fa fa-skype"></i></a></li><?php } ?> 
                        <?php if ($team_linkedin) {?><li><a href="<?php echo $team_linkedin; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li><?php } ?>                                                
                    </ul>
                </div>                

            </div>
		</div><!-- .nx-post-content -->
        
	</div><!-- .nx-post-border -->
    
</div><!-- #post -->
