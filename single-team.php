<?php
/**
 * The template for displaying all single team
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */

get_header(); ?>

    <?php
		global $post;
		global  $ispirit_data;
		
		if(!empty($ispirit_data['header-text-alignment'])) 	{ $header_text_alignment = $ispirit_data['header-text-alignment']; }
		if(!empty($ispirit_data['header-text-color'])) 	{ $header_text_color = $ispirit_data['header-text-color']; }		
		
		$post_thumb_list = "";
		$ispirit_template_url = get_template_directory_uri();
		$hide_page_title = rwmb_meta('ispirit_hidetitle');
		$custom_page_title = esc_attr( rwmb_meta('ispirit_customtitle') );
		$hide_breadcrumb = rwmb_meta('ispirit_hide_breadcrumb');
		if(rwmb_meta('ispirit_header_text_color')) {
			$header_text_color = rwmb_meta('ispirit_header_text_color');
		}
		if(rwmb_meta('ispirit_header_text_alignment')) {
			$header_text_alignment = rwmb_meta('ispirit_header_text_alignment');
		}	
		$title_background = rwmb_meta('ispirit_title_bg');
		$custom_title_background = rwmb_meta('ispirit_custom_title_bg', 'type=image');
		$bg_image_type = rwmb_meta('ispirit_bg_image_type');
		
		$designation = esc_attr(rwmb_meta('ispirit_designation'));
		$team_email = esc_attr(rwmb_meta('ispirit_team_email'));
		$team_phone = esc_attr(rwmb_meta('ispirit_team_phone'));
		$team_twitter = esc_attr(rwmb_meta('ispirit_team_twitter'));
		$team_facebook = esc_url(rwmb_meta('ispirit_team_facebook'));
		$team_gplus = esc_url(rwmb_meta('ispirit_team_gplus'));
		$team_skype = esc_attr(rwmb_meta('ispirit_team_skype'));
		$title_bg_color = rwmb_meta('ispirit_bg_color');
		$title_bg_attachment = rwmb_meta('ispirit_bg_attachment');		

		
		$title_bg_css = "";
		$title_bg_type_css = "";
		$th_width = 600;
		$th_height = 600;
		
		$header_text_class = "";
		
		if ( $header_text_color == 2 )
		{
			$header_text_class = "whitetext"; 
		} elseif ( $header_text_color == 1 ) {
			$header_text_class = "darktext";
		}
		
		$header_text_class .= " title-align-".$header_text_alignment;
		
		
		if(!empty($title_bg_color))
		{
			$title_bg_css = "background-color : ".$title_bg_color."; background-image: none;";
		} else
		{
			if(!empty($custom_title_background))
			{
				foreach ( $custom_title_background as $custom_background )
				{
					$title_background=esc_url($custom_background['url']);
				}
				
				if($bg_image_type==0)
				{
					$title_bg_type_css = " background-repeat:repeat; background-size: auto auto; ";
				} else if($bg_image_type==1)
				{
					$title_bg_type_css = " background-repeat:no-repeat; background-size: cover; ";
				}
				if( $title_bg_attachment == 1 )
				{
					$title_bg_type_css .=" background-attachment: fixed; ";
				} else
				{
					$title_bg_type_css .=" background-attachment: scroll; ";
				}
				
				$title_bg_css .= "background:transparent; background-image: URL(".$title_background.");".$title_bg_type_css;
			} else if(!empty($title_background))
			{
				$title_bg_css .= "background:transparent; background-image: URL(".$ispirit_template_url."/images/patterns/".$title_background.".png); background-repeat:repeat; background-size: auto auto; ";
				if( $title_bg_attachment == 1 )
				{
					$title_bg_css .=" background-attachment: fixed; ";
				} else
				{
					$title_bg_css .=" background-attachment: scroll; ";
				}			
			}
		}

	
	?>

    

    
<?php if ($hide_page_title == 0) { ?>	
	<div class="row titlebarrow">
		<div class="page-heading clearfix" style="<?php echo $title_bg_css ?>">
        	<div class="titlebar <?php echo $header_text_class; ?>">

                <div class="heading-text">
                    <h1 class="entry-title">
						<?php 
							if (!$custom_page_title)
							{
								the_title(); 
							} else
							{
								echo $custom_page_title;
							} 
						?>
                    </h1>
                </div>
                <?php 
                    if ( !$hide_breadcrumb ) {
						?><div id="breadcrumbs"><?php
                        	if(function_exists('bcn_display')) { echo bcn_display(true); }
						?></div><?php
                    }
                ?>
            </div>
            <div class="titlebar-overlay"></div>
        </div>
	</div>
<?php } ?>    

	<div id="primary" class="content-area" >
		<div id="content" class="site-content" role="main">


			<?php while ( have_posts() ) : the_post(); ?>
				<?php
                    $post_categories = get_the_category_list(', ');
					$thumb_image_id = get_post_thumbnail_id();
					$thumb_img_url = wp_get_attachment_url( $thumb_image_id,'full' );
					$thumb_resized = aq_resize( $thumb_img_url, $th_width, $th_height, true );
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-thumbnail">
                        <?php 
                            if( has_post_thumbnail() && ! post_password_required() )
                            {
                                echo "<div class=\"team-thumb\"><img src=\"".$thumb_resized."\" class=\"team-image\" alt=\"\" ></div>";
                            }
                        ?>
                    </div>
                  
                    <div class="entry-content">
                    	<div class="team-content">
                        	<h2><?php echo $designation; ?></h2>
                        	
							<?php the_content(); ?>
                            
                            <div class="other-content">
                            	<ul class="team-contact">
                                	<li><i class="fa fa-envelope-o"></i><span itemscope="email"><a href="mailto:<?php echo $team_email; ?>"><?php echo $team_email; ?></a></span></li>
                                	<li><i class="fa fa-phone"></i><span itemscope="phone"><?php echo $team_phone; ?></span></li>                                    
                                </ul>
                                
                                <div class="share-links clearfix">
                                    <div class="share-text"><?php _e("Social Network:", "ispirit"); ?></div>
                                    <ul class="social-icons">
                                        <?php if ($team_facebook) {?><li class="facebook"><a href="<?php echo $team_facebook; ?>"><i class="fa fa-facebook"></i></a></li><?php } ?>
                                        <?php if ($team_twitter) {?><li class="twitter"><a href="<?php echo $team_twitter; ?>"><i class="fa fa-twitter"></i></a></li><?php } ?>
                                        <?php if ($team_gplus) {?><li class="googleplus"><a href="<?php echo $team_gplus; ?>" ><i class="fa fa-google-plus"></i></a></li><?php } ?>
                                        <?php if ($team_skype) {?><li class="skype"><a href="skype:<?php echo $team_skype; ?>"><i class="fa fa-skype"></i></a></li><?php } ?>
                                    </ul>						
                                </div>	
                    
          
                    
                                                                                   
                            </div>
                        </div>
                    </div><!-- .entry-content -->
                    <div class="clearfix"></div>
                </article><!-- #post -->
    
                <?php ispirit_post_nav(); ?>
			<?php endwhile; ?>

            

		</div><!-- #content -->
	</div><!-- #primary -->
    
<?php get_footer(); ?>