<?php
/**
 * The default template for displaying content
 *
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */
?>
<?php
	global  $ispirit_data;

	$archive_layout_type = $ispirit_data['archive-layout-type'];
	$hide_categories = $ispirit_data['archive-show-categories'];
		
	if( $archive_layout_type == 1 ) {
		$total_column = 1;
	} else {
		$total_column = $ispirit_data['archive-total-columns'];
	}
		
?>		


<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="nx-post-border">
        <header class="entry-header">
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
            		<span class="sp-day"><span class="genericon genericon-gallery"></span></span>
                <?php } else { ?>
            		<span class="sp-day"><span class="genericon genericon-gallery"></span></span>                
                <?php } ?>                
            </span>

            <span class="entry-meta">
                <?php ispirit_entry_meta_blog_standard($hide_categories); ?>
            </span><!-- .entry-meta -->
            <?php if( $archive_layout_type=="3" ) { echo '</div>'; } ?>
        </header><!-- .entry-header -->
    
        <div class="entry-summary clearfix">
            <?php
				echo get_post_gallery();
            ?>
        </div><!-- .entry-summary -->
    	
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
