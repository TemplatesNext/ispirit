<?php
/**
 * The template for displaying Archive pages
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
    	$archive_layout_type = $ispirit_data['archive-layout-type'];
		$total_column = $ispirit_data['archive-total-columns'];
		
		$title_bg_type = $ispirit_data['header-background-type'];
		
		$header_text_class = "";
		$title_bg_css = "";
		$title_bg_type_css = "";
		$title_background = "pat".$title_background;
		
		$extra_layout_css = "";
		
		if ( $header_text_color == 2 )
		{
			$header_text_class = "whitetext"; 
		} else {
			$header_text_class = "darktext";
		}			
		
		$header_text_class .= " title-align-".$header_text_alignment;
		
		$blog_layout_type = "masonry";
		if( $archive_layout_type == "2" || $archive_layout_type == "3" )
		{
			$blog_layout_type = "masonry";
		} else
		{
			$blog_layout_type = "standard";
		}
		
		if( $archive_layout_type == "3" )
		{
			$extra_layout_css = "masonry-modern";
		}		
		
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
		<div class="page-heading clearfix" style=" <?php echo $title_bg_css ?>">
        	<div class="titlebar <?php echo $header_text_class; ?>">
                <div class="heading-text">
                    <h1 class="entry-title">
						<?php
                            if ( is_tag() ) :
								printf( __( 'Posts tagged With: %s', 'ispirit' ), single_tag_title("", false) );
							elseif ( is_day() ) :
                                printf( __( 'Daily Archives: %s', 'ispirit' ), get_the_date() );
                            elseif ( is_month() ) :
                                printf( __( 'Monthly Archives: %s', 'ispirit' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'ispirit' ) ) );
                            elseif ( is_year() ) :
                                printf( __( 'Yearly Archives: %s', 'ispirit' ), get_the_date( _x( 'Y', 'yearly archives date format', 'ispirit' ) ) );
                            else :
                                _e( 'Archives', 'ispirit' );
                            endif;
                        ?>                    
                    </h1>
                </div>
                <?php 
                    if (!empty($hide_breadcrumb)) {
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

	<div id="primary" class="content-area <?php echo "$page_wrap_class"; ?>">
		<div id="content" class="site-content" role="main">
        
		<?php if ( have_posts() ) : ?>

			<?php if ( tag_description() ) : // Show an optional tag description ?>
				<div class="archive-meta"><?php echo tag_description(); ?></div>
			<?php endif; ?>   
                
			<?php /* The loop */ ?>
            <div class="blogwrap sp-posts blog-<?php echo $blog_layout_type; ?> sp-blog-column-<?php echo $total_column; ?> <?php echo $extra_layout_css; ?>">
			<?php while ( have_posts() ) : the_post(); ?>
            	
				<?php
					get_template_part( $blog_template, get_post_format() );
				?>
                
			<?php endwhile; ?>
			</div>
			<?php ispirit_paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

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