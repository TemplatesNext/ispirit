<?php
/**
 * The template for displaying all single portfolio
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */

get_header(); ?>

    <?php
		global $post;
		global $ispirit_data;
		
		if(!empty($ispirit_data['header-text-alignment'])) 	{ $header_text_alignment = $ispirit_data['header-text-alignment']; }
		if(!empty($ispirit_data['header-text-color'])) 	{ $header_text_color = $ispirit_data['header-text-color']; }		
		
		$post_thumb_list = "";
		$folio_layout_type = 0;
		
		$ispirit_template_url = get_template_directory_uri();
		$hide_page_title = rwmb_meta('ispirit_hidetitle');
		$custom_page_title = esc_attr( rwmb_meta('ispirit_customtitle') );
		$sidebar_stat = rwmb_meta('ispirit_sidebar');
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
		
		$post_thumb_type = rwmb_meta('ispirit_post_thumb_type');
		$post_thumb_image = rwmb_meta('ispirit_post_thumb_image', 'type=image&size=blog-image');
		$thumb_video_url = esc_url(rwmb_meta('ispirit_thumb_video_url'));
		$thumb_slider_images = rwmb_meta('ispirit_thumb_slider_images', 'type=image&size=blog-image');
		$thumb_link_type = rwmb_meta('ispirit_thumb_link_type');
		$folio_layout_type = rwmb_meta('ispirit_folio_disply_type');
		$folio_subtitle = esc_attr(rwmb_meta('ispirit_portfolio_subtitle'));
		$folio_url = esc_url(rwmb_meta('ispirit_portfolio_url'));

		$hide_post_thumb = rwmb_meta('ispirit_hide_thumb', $post->ID);
		if( $hide_post_thumb == 1 )
		{
			$post_thumb_type = 5;
		}
		
		$show_author_bio = rwmb_meta('ispirit_nx_show_bio');
		$show_related = rwmb_meta('ispirit_nx_show_related');
		$show_social = rwmb_meta('ispirit_nx_show_social');
		
		$title_bg_color = rwmb_meta('ispirit_bg_color');
		$title_bg_attachment = rwmb_meta('ispirit_bg_attachment');		
		
		$title_bg_css = "";
		$title_bg_type_css = "";
		$header_text_class = "";
		$th_width = 1200;
		$th_height = 600;		
		
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
		
		
		
		$page_wrap_class = '';
		if($sidebar_stat == 0)
		{
			$page_wrap_class = 'has-no-sidebar';
		} elseif ($sidebar_stat == 1 && is_active_sidebar( 'sidebar-1' ) )
		{
			$page_wrap_class = 'has-right-sidebar';
		} elseif ($sidebar_stat == 2 && is_active_sidebar( 'sidebar-2' ) )
		{
			$page_wrap_class = 'has-left-sidebar';
		}
		
		
        $post_format = get_post_format($post->ID);
				
		if ( $post_format == "" ) {
			$post_format = 'standard';
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
                    if (!$hide_breadcrumb) {
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

	<div id="primary" class="content-area <?php echo $page_wrap_class; ?>" >
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
			<?php
				$post_author = get_the_author_link();
				$post_date = get_the_date();
				$post_categories = get_the_category_list(', ');				
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            
            <!-- Column Starts -->
            <?php  if ( $folio_layout_type == '1' ) { echo '<div class="folio-col-1">'; } ?>
            
                <header class="entry-header">
                    <div class="entry-thumbnail">
						<?php //the_post_thumbnail(); 
                            
                            if($post_thumb_type == 0) {
                                if( has_post_thumbnail() && ! post_password_required() )
                                {
                                    the_post_thumbnail('blog-image');
                                }
                            } elseif ($post_thumb_type == 1 && !empty($post_thumb_image))
                            {
                                foreach ( $post_thumb_image as $post_thumb_image_item )
                                {
                                    $post_thumbnail_img = $post_thumb_image_item['url'];
                                }
                                echo '<a href="'.$post_thumbnail_img.'" class="mag-pop-img"><img src="'.aq_resize( $post_thumbnail_img, $th_width, $th_height, true ).'" alt=""></a>';
                            } elseif ( $post_thumb_type == 3 && !empty($thumb_slider_images) )
                            {
								echo '<div class="nx-header-slider" data-slide-speed="7000">';
                                foreach ( $thumb_slider_images as $thumb_slider_image )
                                {
                                    $post_thumb_slider_img = $thumb_slider_image['url'];
									echo '<div class="nx-slide">';
									echo '<a href="'.$post_thumb_slider_img.'" class="mag-pop-img"><img src="'.aq_resize( $post_thumb_slider_img, $th_width, $th_height, true ).'" alt=""></a>';
									echo '</div>';
                                }
								echo '</div>';
                                        
                            }	
                            elseif ($post_thumb_type == 2)
                            {
                                if (strpos($thumb_video_url,'youtube') || strpos($thumb_video_url,'youtu.be')){
                                    echo do_shortcode("[nx_youtube url=\"$thumb_video_url\" class=\"nx-vimeo-cudtom-class\"]");
                                } else {
                                    echo do_shortcode("[nx_vimeo url=\"$thumb_video_url\" class=\"nx-vimeo-cudtom-class\"]");								
                                }							
                           	}					
                        ?>

                    </div>
					
                    <?php
						if(!empty($folio_subtitle))
						{
							echo '<h3 class="folio-subtitle">'.$folio_subtitle.'</h3>';
						}
                    ?>
                	
                    <div class="entry-meta">
                        <?php 
							echo '<span class="date portfoliodate"><span class="genericon genericon-time"></span>' . the_date('M j, Y','','',false) . '</span>'; 
						?>
						<?php
                        $categories = nx_category_custom_post($post->ID, 'portfolio-category');
                        if ( !empty($categories) ) {
                            foreach ( $categories as $catt ) 
                            {
                                    $portfolio_categories[] = $catt->name;
                            }
							echo '<span class="portfolio-category"><span class="genericon genericon-category"></span>' . implode(', ', $portfolio_categories) . '</span>';
                        }
                        ?>
                        <?php edit_post_link( __( 'Edit', 'ispirit' ), '<span class="edit-link">', '</span>' ); ?>
						<?php if ( comments_open() ) { ?>
                            <div class="comments-wrapper">
                                <a href="#comments">
                                    <i class="ss-chat"></i>
                                    <span><?php comments_number(__('0 Comments', 'ispirit'), __('1 Comment', 'ispirit'), __('% Comments', 'ispirit')); ?></span>
                                </a>
                            </div>
                        <?php } ?>
                                            
                    </div><!-- .entry-meta -->
                    
                </header><!-- .entry-header -->
				
                
                <!-- Column Break -->
				<?php  if ( $folio_layout_type == '1' ) { echo '</div><div class="folio-col-2">'; } ?>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                    <?php 
						if(!empty($folio_url)) {
							echo '<div class="">'. __('Project URL', 'ispirit'). ' : <a href="' . $folio_url . '">' .$folio_url. '</a></div>';
						}
					?>
                </div><!-- .entry-content -->
				<?php if (has_tag()) { ?>
					<div class="tags-wrap"><?php _e('Tags: ', 'ispirit'); ?><span class="tags"><?php the_tags(''); ?></span></div>
				<?php } ?>
                
                
                <!-- Column End -->
                <?php  if ( $folio_layout_type == '1' ) { echo '</div><div class="clearfix folioend"></div>'; } ?>
                
                
                
                
				<?php if ($show_social) { ?>
					<div class="share-links clearfix">
						<div class="share-text"><?php _e("Share this article:", "ispirit"); ?></div>
						<ul class="social-icons">
						    <li class="facebook"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
						      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600');return false;"><i class="fa fa-facebook"></i></a></li>
						    <li class="twitter"><a href="https://twitter.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
						      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;"><i class="fa fa-twitter"></i></a></li>   
						    <li class="googleplus"><a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
						      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a></li>
						    <li class="pinterest"><a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id()); ?>&description=<?php echo get_the_title(); ?>"><i class="fa fa-pinterest"></i></a></li>
							<li class="mail"><a href="mailto:?subject=<?php the_title(); ?>&body=<?php echo strip_tags(get_the_excerpt()); ?> <?php the_permalink(); ?>"><i class="fa fa-envelope"></i></a></li>
						</ul>						
					</div>					
				<?php } ?>


			<?php if ($show_related == '1') { ?>
				
				<div class="related-wrap clearfix">
				<?php
					//$categories = get_the_category($post->ID);
					$categories = nx_category_custom_post($post->ID, 'portfolio-category');
					
					//echo '<h2>'.implode (", ", $categories).'</h2>';

					
					$args=array(
						'post__not_in' => array($post->ID),
						'post_type' => 'portfolio',
						'showposts'=> 4, // Number of related posts that will be shown.
						'ignore_sticky_posts'=> true,
						'orderby' => 'rand'
					);
					
					$related_posts_query = new wp_query($args);
					if( $related_posts_query->have_posts() ) {	
						_e('<h3 class="spb-heading"><span>'.__("Related Projects", "ispirit").'</span></h3>');
						echo '<ul class="related-items">';
						while ($related_posts_query->have_posts()) {
							
							$related_posts_query->the_post();
							
							$item_title = get_the_title();
							$thumb_img_url = $related_slider_images = $thumb_image = "";
							
							
							$related_slider_images = rwmb_meta('ispirit_thumb_slider_images', 'type=image&size=blog-image', $post->ID);
							$thumb_image =  rwmb_meta('ispirit_post_thumb_image', 'type=image', $post->ID);
							$thumb_placeholder = $ispirit_template_url."/images/related-blank.png";
							
							if( empty($thumb_image) && !empty($related_slider_images) )
							{
								$thumb_image = $related_slider_images;
							}
							
							
							if( has_post_thumbnail() )
							{
								$thumb_image = get_post_thumbnail_id();
								$thumb_img_url = wp_get_attachment_url( $thumb_image, 'full' );
							} elseif (!empty($thumb_image))
							{
                                foreach ( $thumb_image as $thumb_image_item )
                                {
                                    $thum_img = $thumb_image_item['ID'];
                                }
								
								$thumb_image = $thum_img;
								$thumb_img_url = wp_get_attachment_url( $thumb_image, 'full' );								
							}
							
							$image = aq_resize( $thumb_img_url, 300, 225, true, false);
							
							?>
							<li class="related-item">
								<span class="related-img">
									<?php if ($image) { ?>
									<img itemprop="image" src="<?php echo $image[0]; ?>" class="transit-all" alt="<?php echo $item_title; ?>" />
									<?php } else { ?>
									<div class="img-holder"><img src="<?php echo $thumb_placeholder; ?>" alt="" class="transit-all"></div>
									<?php } ?>
                                    <span class="related-overlay transit-all">
                                    </span>
                                    <span class="related-link transit-all">
                                    	<a href="<?php the_permalink(); ?>" class="transit-all"><i class="fa fa-link"></i></a>
                                    </span>
								</span>
                                <h5 class="transit-all">
                                    	<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo $item_title; ?></a>
                                </h5>								
							</li>
						<?php }
						echo '</ul>';
					}
												
					wp_reset_query();
				?>
				</div>
				
				<?php } ?>
            </article><!-- #post -->

			<?php //get_template_part( 'content', get_post_format() ); ?>
			<?php ispirit_post_nav(); ?>
			<?php comments_template(); ?>
                
		<?php endwhile; ?>

            

		</div><!-- #content -->
        
		<?php if ($sidebar_stat == 1) { ?>
            
            <aside class="sidebar right-sidebar">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </aside>
    
        <?php } else if ($sidebar_stat == 2) { ?>
            
            <aside class="sidebar left-sidebar">
                <?php dynamic_sidebar('sidebar-2'); ?>
            </aside>
            
        <?php } else if ($sidebar_stat == 3) { ?>
            
            <aside class="sidebar left-sidebar col-sm-3">
                <?php dynamic_sidebar('sidebar-2'); ?>
            </aside>
        
        <?php } ?>       
                
	</div><!-- #primary -->
    
<?php get_footer(); ?>