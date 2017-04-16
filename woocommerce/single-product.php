<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

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
		
		$woo_layout_style = $ispirit_data['woo-archive-style'];	
		
		$title_bg_css = "";
		$header_text_class = "";
		$woo_layout_class = "nx-woo-default";
		
		if ( $header_text_color == 2 )
		{
			$header_text_class = "whitetext"; 
		} elseif ( $header_text_color == 1 ) {
			$header_text_class = "darktext";
		}
		
		$header_text_class .= " title-align-".$header_text_alignment;		
		
		// new codes starts
		$title_bg_color = rwmb_meta('ispirit_bg_color');
		$title_bg_attachment = rwmb_meta('ispirit_bg_attachment');
		// new codes ends
		
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
    
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>
        
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
										//get_the_title();
										 
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
            <div class="row woo-outerwrap <?php echo $page_wrap_class; ?>">
                <div class="woo-content <?php echo $woo_layout_class; ?>">
                
                    <?php wc_get_template_part( 'content', 'single-product' ); ?>
                    
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
            
		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		//do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );
	?>
     
<?php get_footer( 'shop' ); ?>