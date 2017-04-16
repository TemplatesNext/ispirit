<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>


    <?php
		global $post;
		global  $ispirit_data;
		
		$header_text_alignment = "left";
		$custom_title_background = "";
		
		$ispirit_template_url = get_template_directory_uri();
		$hide_breadcrumb = $ispirit_data['woo-togg-breadcrumb'];
		$header_text_color = $ispirit_data['woo-header-text-color'];		
		$title_background = $ispirit_data['woo-title-background'];
		if(!empty($ispirit_data['woo-title-bg-image']['url']))
		{
			$custom_title_background = esc_url($ispirit_data['woo-title-bg-image']['url']);
		}
		$bg_image_type = $ispirit_data['woo-archive-title-bg-repeat'];
		$sidebar_stat = $ispirit_data['woo-archive-sidebar'];
		$hide_page_title = $ispirit_data['woo-hide-title'];
		$hide_shop_page_title = $ispirit_data['woo-shop-hide-title'];		
		
		$woo_layout_style = $ispirit_data['woo-archive-style'];
		$title_bg_type = $ispirit_data['woo-header-background-type'];
		$title_bg_color = $ispirit_data['woo-archive-title-bg-color'];
		$header_text_alignment = $ispirit_data['woo-header-text-alignment'];
		$title_bg_attachment = $ispirit_data['woo-archive-title-bg-attachment'];		
		
		$header_text_class = "";
		$title_bg_css = "";
		$title_bg_type_css = "";
		$title_background = "pat".$title_background;		
		$woo_layout_class = "nx-woo-default";

		if ( $header_text_color == 2 )
		{
			$header_text_class = "whitetext"; 
		} else {
			$header_text_class = "darktext";
		}
		
		$header_text_class .= " title-align-".$header_text_alignment;	
		
		$title_bg_css = "";
		
		if(empty($title_bg_color) || $title_bg_color == 'transparent')
		{
			$title_bg_color = $ispirit_data['primary-color'];
		}		
		
		if ( $title_bg_type == 2 )
		{
			$title_bg_css = 'background-color: ' .$title_bg_color. ';';
		} else
		{		
			if(!empty($custom_title_background))
			{
				$title_background = $custom_title_background;
				
				if($bg_image_type==1)
				{
					$title_bg_type_css = " background-repeat:repeat; ";
				} else if($bg_image_type==2)
				{
					$title_bg_type_css = " background-repeat:no-repeat; background-size: cover; ";
				}
				if( $title_bg_attachment == 2)
				{
					$title_bg_type_css .= ' background-attachment: fixed; ';
				}
				$title_bg_css .= "background-image: URL(".$title_background.");".$title_bg_type_css;
			} else
			{
				if(!$title_background)
				{
					$title_background = "pat1";
				}
				$title_bg_css .= "background-image: URL(".$ispirit_template_url."/images/patterns/".$title_background.".png); ";
			}
		}
		
		
		$page_wrap_class = '';
		if($sidebar_stat == 0)
		{
			$page_wrap_class = 'has-no-sidebar';
		} else if ($sidebar_stat == 1 && is_active_sidebar( 'sidebar-7' ) )
		{
			$page_wrap_class = 'has-right-sidebar';
		} else if ($sidebar_stat == 2 && is_active_sidebar( 'sidebar-8' ) )
		{
			$page_wrap_class = 'has-left-sidebar';
		}
		
		if( $woo_layout_style == 2 )
		{
			$woo_layout_class = "nx-woo-modern";
		}
	
	?>
    
    <?php get_header( 'shop' ); ?>
    
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
    
    <?php
		
		if ( is_product_category() && !is_shop() )
		{
			$show_cat_title = "1";
			
			$cat_type = "product_cat";
			$cat_slug = get_query_var($cat_type);
				
			$thisCat = get_term_by( 'slug', $cat_slug, 'product_cat', false );
			$thisCatid = $thisCat->term_id;
				
			$term_meta = get_option( "taxonomy_$thisCatid" );
			
			if (!empty($term_meta['custom_term_meta_3'])) {				
				$show_cat_title = $term_meta['custom_term_meta_3'];
			}
			
			if ( $show_cat_title == '2' )
			{
				$hide_page_title = '0';
			} 

		} elseif ( is_shop() && !is_product_category() && !is_search() )
		{
			if ( $hide_shop_page_title == '0' )
			{
				$hide_page_title = '0';
			}			
		}
    ?>
    	
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) && $hide_page_title != '0' ) : ?>
            <div class="row titlebarrow">
                <div class="page-heading clearfix" style="<?php echo $title_bg_css ?>">
                    <div class="titlebar <?php echo $header_text_class; ?>">
                        <div class="heading-text">
                            <h1 class="entry-title">
                                <?php woocommerce_page_title(); ?>
                            </h1>
                        </div>
                        <?php 
                            if ($hide_breadcrumb != 0) {
                                ?><div id="breadcrumbs"><?php
                                    if(function_exists('bcn_display')) { echo bcn_display(true); }
                                ?></div><?php
                            }
                        ?>
                    </div>
                    <div class="titlebar-overlay"></div>
                </div>
            </div>            

		<?php endif; ?>
        
        
        <div class="row woo-outerwrap <?php echo $page_wrap_class; ?>">
			<div class="woo-content <?php //echo $woo_layout_class; ?>">

				<?php do_action( 'woocommerce_archive_description' ); ?>
        
                <?php if ( have_posts() ) : ?>
        
                    <?php
                        /**
                         * woocommerce_before_shop_loop hook
                         *
                         * @hooked woocommerce_result_count - 20
                         * @hooked woocommerce_catalog_ordering - 30
                         */
                        do_action( 'woocommerce_before_shop_loop' );
                    ?>
        			<div class="loop-list">
                    <?php woocommerce_product_loop_start(); ?>
        				
                        <?php woocommerce_product_subcategories(); ?>
        
                        <?php while ( have_posts() ) : the_post(); ?>
        
                            <?php wc_get_template_part( 'content', 'product' ); ?>
        
                        <?php endwhile; // end of the loop. ?>
        				
                    <?php woocommerce_product_loop_end(); ?>
        			</div>
                    <?php
                        /**
                         * woocommerce_after_shop_loop hook
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action( 'woocommerce_after_shop_loop' );
                    ?>
        
                <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
        
                    <?php wc_get_template( 'loop/no-products-found.php' ); ?>
        
                <?php endif; ?>
        
                <?php
                    /**
                     * woocommerce_after_main_content hook
                     *
                     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                     */
                    do_action( 'woocommerce_after_main_content' );
                ?>

				<?php
                    /**
                     * woocommerce_sidebar hook
                     *
                     * @hooked woocommerce_get_sidebar - 10
                     */
                    //do_action( 'woocommerce_sidebar' );
                ?>
            </div> <!-- woo-content -->
                
			<?php if ($sidebar_stat == 1) { ?>
                
                <aside class="sidebar right-sidebar">
                    <?php dynamic_sidebar('sidebar-7'); ?>
                </aside>
        
            <?php } else if ($sidebar_stat == 2) { ?>
                
                <aside class="sidebar left-sidebar">
                    <?php dynamic_sidebar('sidebar-8'); ?>
                </aside>
                
            <?php } else if ($sidebar_stat == 3) { ?>
                
                <aside class="sidebar left-sidebar">
                    <?php dynamic_sidebar('sidebar-8'); ?>
                </aside>
            
            <?php } ?>
            
        </div> <!-- woo-outerwrap -->
            
<?php get_footer( 'shop' ); ?>