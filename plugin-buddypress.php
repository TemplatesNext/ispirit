<?php
/**
 * The template for displaying buddypress pages
 *
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
		
		$title_bg_css = "";
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
		
		
		$page_wrap_class = '';
		if($sidebar_stat == 0)
		{
			$page_wrap_class = 'has-no-sidebar';
		} else if ($sidebar_stat == 1 && is_active_sidebar( 'sidebar-1' ) )
		{
			$page_wrap_class = 'has-right-sidebar';
		} else if ($sidebar_stat == 2 && is_active_sidebar( 'sidebar-2' ) )
		{
			$page_wrap_class = 'has-left-sidebar';
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
					//removed breadcrumb
                ?>
            </div>
            <div class="titlebar-overlay"></div>
        </div>
	</div>
   
<?php } ?>    
    
	<div id="primary" class="content-area <?php echo $page_wrap_class; ?>">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
						<?php endif; ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'ispirit' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post -->

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