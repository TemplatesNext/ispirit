<?php
/**
 * The Header template for our theme
 *
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */
?><!DOCTYPE html>
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

	<?php
	
    	global  $ispirit_data;
		
		$site_layout_class = $boxed_shadow_class = $page_class = $retina_logo = $normal_logo = $top_bar_color = $page_topbar = $page_nopad = $titlebar_class = $blog_slider = "";
		
		$site_layout = $ispirit_data['site_layout'];
		$page_shadow = $ispirit_data['boxed_shadow'];
		$top_bar_switch = $ispirit_data['top-bar-switch'];
		$top_bar_color = $ispirit_data['top-bar-color'];
		$enable_woo_bar = $ispirit_data['enable-woo-bar'];
		$resp_menu_style = $ispirit_data['responsive-menu'];
		$narrow_titlebar = $ispirit_data['narrow-titlebar'];
		$blog_slider = $ispirit_data['blog-slider'];					
					
		$woo_product_page = false;
		$top_bar_class = "";
		$responsive_menu_class = "";
		
		if( $narrow_titlebar == true )
		{
			$titlebar_class = "narrow-titlebar";
		}
		

		if( is_plugin_active('woocommerce/woocommerce.php') )
		{
			$woo_product_page = is_product();
		}		
		
		if ( is_page() || $woo_product_page || is_single() ) 
		{
			global $post;
			
			$page_class = esc_attr(rwmb_meta('ispirit_page_class'));
			$page_topbar = rwmb_meta('ispirit_page_topbar');
			$page_nopad = rwmb_meta('ispirit_page_nopad');
			$hide_header = rwmb_meta('ispirit_hide_header');		
			
			if($page_nopad)
			{
				$page_class .= " no-page-pad";
			}
			
			if( $hide_header == 1 )
			{
				$page_class .= " nx-noheader";
			}
		}

		
		if( $site_layout == 1 )
		{
			$site_layout_class = "boxed";
		}
		
		if( $page_shadow == 1 )
		{
			$boxed_shadow_class = "page-shadow";
		}
		
		if( $resp_menu_style == 2 )
		{
			$responsive_menu_class = "classic-menu";
		} else
		{
			$responsive_menu_class = "sidr-menu";			
		}
		
		if (isset($ispirit_data['logo-normal']['url']) && $ispirit_data['logo-normal']['url']) {
			$normal_logo = esc_url($ispirit_data['logo-normal']['url']);
		}
		if (isset($ispirit_data['logo-retina']['url']) && $ispirit_data['logo-retina']['url']) {
			$retina_logo = esc_url($ispirit_data['logo-retina']['url']);
		}
				
    ?>
        
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
    
	<title><?php wp_title( '|', true, 'right' ); ?></title>

    <?php if (isset($ispirit_data['favicon']['url']) && $ispirit_data['favicon']['url']) { ?><link rel="shortcut icon" href="<?php echo esc_url($ispirit_data['favicon']['url']); ?>" /><?php } ?>
	<?php if (isset($ispirit_data['ios57x57']['url']) && $ispirit_data['ios57x57']['url']) { ?><link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo esc_url($ispirit_data['ios57x57']['url']); ?>" /><?php } ?>    
	<?php if (isset($ispirit_data['ios72x72']['url']) && $ispirit_data['ios72x72']['url']) { ?><link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url($ispirit_data['ios72x72']['url']); ?>" /><?php } ?>    	
	<?php if (isset($ispirit_data['ios144x144']['url']) && $ispirit_data['ios144x144']['url']) { ?><link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo esc_url($ispirit_data['ios144x144']['url']); ?>" /><?php } ?>

   
    
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class($site_layout_class.' '.$responsive_menu_class.' '.$boxed_shadow_class.' '.$page_class); ?>>
	<div id="page" class="hfeed site <?php echo $titlebar_class; ?>">
    	<div class="headerwrap">
        	
            <?php
				if ( $enable_woo_bar==1 && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            ?>        	
        	<div class="woocombar-wrap">
                <div class="woocombar">
                <?php 
					if (function_exists('nx_woo_bar')) { echo nx_woo_bar(); }
					if (class_exists('nxLiveSearch')) { echo nx_search_form(); }						
                ?>
                </div>
            </div>
            <?php
				}			
            ?>             
            
            <!-- Top bar with social links, contact details etc --> 
            <?php
				if ($top_bar_color == 2)
				{
					$top_bar_class = "tb-reversed tb-dark";
				} elseif ($top_bar_color == 3)
				{
					$top_bar_class = "tb-reversed";
				}
            ?>

            <?php if($top_bar_switch == 1 || $page_topbar ) { ?> 
        	<div class="social-bar <?php echo $top_bar_class; ?>">
            	<div class="social-bar-inwrap">             
					<?php
						 echo nx_top_bar();
                    ?>
                </div>
            </div><!-- .social-bar  -->            
            <?php } ?>
			
			<!-- Header part containing Logo and Main nav -->
            <?php
                echo nx_header();
            ?>
		</div>

        
        <!-- sliders -->
		<?php
			if ( is_page() || $woo_product_page || is_single() ) 
			{
				global $post;
				
				$show_ispirit_slider = rwmb_meta('ispirit_ispirit_slider');
				$show_posts_slider = rwmb_meta('ispirit_nx_slider');
				$rev_slider_alias = esc_attr(rwmb_meta('ispirit_rev_slider_alias'));
				$other_front_slider = esc_attr(rwmb_meta('ispirit_other_slider'));
									
				if ($show_ispirit_slider) {
					?> <div class="slider-container"> <?php
						nx_ispirit_slider();
					?> </div> <?php
				} else if ($show_posts_slider) {
					?> <div class="slider-container"> <?php
						nx_tnext_slider();
					?> </div> <?php
				} else if ($rev_slider_alias != "" && $rev_slider_alias != "0" ) { 
					?> <div class="slider-container"> <?php
						putRevSlider($rev_slider_alias); 
					?> </div> <?php
				} else if ($other_front_slider != "") { 
					?> <div class="slider-container"> <?php
						echo do_shortcode( htmlspecialchars_decode($other_front_slider) );
					?> </div> <?php
				}
			}
		?>
            
		<?php 
			if( is_plugin_active('woocommerce/woocommerce.php') )
			{
				if (is_shop() && !is_search() ) 
				{
					$woo_slider = esc_attr($ispirit_data['woo-slider']);
					$layerSlider_ID = esc_attr($ispirit_data['woo-layer-slider']);
					$rev_slider_alias = esc_attr($ispirit_data['woo-rev-slider']);
							
					if ( $woo_slider == 2 ) {
						?> <div class="slider-container"> <?php
							nx_ispirit_slider();
						?> </div> <?php
					} else if ( $rev_slider_alias != "" && $woo_slider == 3 ) { 
						?> <div class="slider-container"> <?php
							putRevSlider($rev_slider_alias); 
						?> </div> <?php
					} else if ( $layerSlider_ID != "" && $woo_slider == 4 ) { 
						?> <div class="slider-container"> <?php
							// $layerSlider_ID changed to other slider shortcode
							echo do_shortcode( htmlspecialchars_decode($layerSlider_ID) );
						?> </div> <?php
					}
				}
				
				// product category page custom meta
				if (is_product_category()) 
				{
					$layerSlider_ID = "";
					$rev_slider_alias = "";
					
					$cat_type = "product_cat";
					$cat_slug = get_query_var($cat_type);
					
					$thisCat = get_term_by( 'slug', $cat_slug, 'product_cat', false );
					$thisCatid = $thisCat->term_id;
					
					$term_meta = get_option( "taxonomy_$thisCatid" );
					
					if (!empty($term_meta['custom_term_meta_2'])) {
						$layerSlider_ID = $term_meta['custom_term_meta_2'];
					}
					if (!empty($term_meta['custom_term_meta_1'])) {
						$rev_slider_alias = esc_attr($term_meta['custom_term_meta_1']);
					}

					if ( $rev_slider_alias != "") { 
						?> <div class="slider-container"> <?php
							putRevSlider($rev_slider_alias); 
						?> </div> <?php
					} else if ( $layerSlider_ID != "") { 
						?> <div class="slider-container"> <?php
							// $layerSlider_ID changed to other slider shortcode
							echo do_shortcode( stripslashes(htmlspecialchars_decode($layerSlider_ID)) );
						?> </div> <?php
					}
					
					/**/
				}
			}
		?>        
        
		<?php
			if ( is_home() && $blog_slider != 1 ) 
			{
				$blog_rev_slider = $blog_other_slide = "";
				$blog_rev_slider = $ispirit_data['blog-rev-slider'];
				$blog_other_slider = esc_attr($ispirit_data['blog-other-slider']);
									
				if ($blog_slider == 2) {
					?> <div class="slider-container"> <?php
						nx_ispirit_slider();
					?> </div> <?php
				} else if ( $blog_slider == 3 && $blog_rev_slider != "" ) { 
					?> <div class="slider-container"> <?php
						putRevSlider($blog_rev_slider); 
					?> </div> <?php
				} else if ( $blog_slider == 4  && $blog_other_slider != "" ) { 
					?> <div class="slider-container"> <?php
						echo do_shortcode( htmlspecialchars_decode($blog_other_slider) );
					?> </div> <?php
				}
			}
		?>        
                
		<div id="main" class="site-main">

