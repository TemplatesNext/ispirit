<?php
/**
 * The template for displaying bbpress pages
 *
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */

get_header(); ?>
	
    
    <?php
		global $post;
		global  $ispirit_data;
		
		$header_text_alignment = "left";
		
		$ispirit_template_url = get_template_directory_uri();
		$sidebar_stat = $ispirit_data['archive-sidebar'];
		$hide_breadcrumb = $ispirit_data['togg-breadcrumb'];
		$hide_cat_title = $ispirit_data['togg-title-bar'];
		$header_text_color = $ispirit_data['header-text-color'];
		$header_text_alignment = $ispirit_data['header-text-alignment'];		
		$title_background = $ispirit_data['title-background'];
    	$sidebar_stat = $ispirit_data['archive-sidebar'];
		
		
		$header_text_class = "";
		$title_bg_css = "";
		$title_bg_type_css = "";
		
		$extra_layout_css = "";
		
		if ( $header_text_color == 2 )
		{
			$header_text_class = "whitetext"; 
		} else {
			$header_text_class = "darktext";
		}			
		
		$header_text_class .= " title-align-".$header_text_alignment;
		
		$blog_template = "content";
		
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
    <?php if ($hide_cat_title == 1) { ?>
	<div class="row titlebarrow">
		<div class="page-heading clearfix" style="<?php echo $title_bg_css ?>">
        	<div class="titlebar <?php echo $header_text_class; ?>">
                <div class="heading-text">
                    <h1 class="entry-title">
						<?php 
							the_title(); 
						?>
                    </h1>
                </div>
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