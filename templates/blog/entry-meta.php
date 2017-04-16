<?php 
/**
 * Template for entry meta
 *
 * @package templatesnext Shortcode
 */
 
if ( $atts['meta_all'] ) : ?>
	<div class="nx-footer-entry-meta nx-clearfix">
		<div class="nx-entry-meta-inner nx-clearfix">
			<?php if ( $atts['meta_comments'] ) : ?>
				<?php if ( comments_open() ) : ?>
					<span class="comments-link nx-comment-link">
						<?php comments_popup_link( '<span class="nx-leave-reply">' . __( '0', 'nx' ) . '</span>', __( '1', 'nx' ), __( '%', 'nx' ) ); ?>
					</span><!-- .comments-link -->
				<?php endif; // comments_open() ?>
			<?php endif; ?>

			<?php
			$meta = array();
			// Post author
			if ( $atts['meta_author'] ) {
				$meta[] = sprintf( '<span class="nx-author"><span class="nx-by">By</span> <a class="nx-url" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					get_the_author(),
					get_the_author()
				);
			}
			?>
            
			<?php
            if ( $atts['meta_cat'] ) {
                $categories_list = get_the_category_list( __( ', ', 'ispirit' ) );
                if ( $categories_list ) {
                    echo '<span class="categories-links">' . $categories_list . '</span>';
                }
            }
        
            ?>

			<?php
				if ( $atts['meta_date'] && ! has_post_format( 'link' ) ) {
					if( $atts['blog_layout'] == '1' )
					{
                    	$meta[] = sprintf( '<span class="nx-metawrap"><span class="nx-day">%1$s</span><span class="nx-year">%2$s</span></span><span class="nx-date">%3$s</span>',
							get_the_date('j'),
							get_the_date('M y'),
							get_the_date('j M, Y')
						);
					} else
					{
						$meta[] = sprintf( '<span class="nx-date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="nx-entry-date" datetime="%3$s">%4$s</time></a></span>',
							esc_url( get_permalink() ),
							esc_attr( sprintf( __( 'Permalink to %s', 'nx' ), the_title_attribute( 'echo=0' ) ) ),
							esc_attr( get_the_date( 'c' ) ),
							esc_html( sprintf( '%2$s', get_post_format_string( get_post_format() ), get_the_date('M j, Y') ) )
						);
					}
				}
			?>
            <?php
				if ( is_sticky() && $atts['ignore_sticky_posts'] == 0 && ! is_paged() )
				{
					$meta[] = sprintf( '<span class="nx-featured-post"><span class="genericon genericon-pinned"></span><span class="nx-sticky">' . __( 'Sticky', 'ispirit' ) . '</span></span>');
				}			
            ?>
			<?php echo implode( '<span class="nx-sep">|</span>', $meta ); ?>
		</div>
	</div><!-- .nx-footer-entry-meta -->
<?php endif; ?>
