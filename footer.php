<?php
/**
 * The template for displaying the footer
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */
?>

<?php

	global  $ispirit_data;
	
	$footer_bottom_right = '3';
	$footer_copyright = '';
	$footer_widgets = '0';
	$show_footer_widgets = 1;	
	
	$footer_layout = $ispirit_data['footerlayout'];
	$footer_bottom_right = $ispirit_data['footer-bottom-right'];
	$footer_copyright = esc_attr($ispirit_data['copy-right-text']);
	
	
	$woo_product_page = false;
			
	if( is_plugin_active('woocommerce/woocommerce.php') )
	{
		$woo_product_page = is_product();
	}		
	
	if ( is_active_sidebar( 'sidebar-3' ) || is_active_sidebar( 'sidebar-4' ) || is_active_sidebar( 'sidebar-5' ) || is_active_sidebar( 'sidebar-6' ) )
	{
		$footer_widgets = '1';
	}
	
	if ( is_page() || $woo_product_page || is_single() ) 
	{
		global $post;
		$page_footer_widgets = rwmb_meta('ispirit_footer_widgets');
			
		if ( $page_footer_widgets == 1 )
		{
			$footer_widgets = '0';
		}
	}	

?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
        
        <?php
			if ($footer_widgets == '1')
			{
        ?>
        
			<div id="footer-widgets" class="row clearfix">
            	<div class="row-container">
                    <div class="fotter-inner-wrap">
						<?php if ($footer_layout == "1") { ?>
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-3'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-4'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-5'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-6'); ?>
                                <?php } ?>
                            </div>
                                
                        <?php } else if ($footer_layout == "2") { ?>
                                
                            <div class="col-fourtwo">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-3'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-4'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-5'); ?>
                                <?php } ?>
                            </div>
                                
                        <?php } else if ($footer_layout == "3") { ?>
                                
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-3'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-fourone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-4'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-fourtwo">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-5'); ?>
                                <?php } ?>
                            </div>
                                
                        <?php } else if ($footer_layout == "4") { ?>
                                
                            <div class="col-twoone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-3'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-twoone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-4'); ?>
                                <?php } ?>
                            </div>
                                
                        <?php } else if ($footer_layout == "5") { ?>
                                
                            <div class="col-threeone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-3'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-threeone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-4'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-threeone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-5'); ?>
                                <?php } ?>
                            </div>
                                
                        <?php } else { ?>
                            <div class="col-twoone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-3'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-twoone">
                                <?php if ( function_exists('dynamic_sidebar') ) { ?>
                                    <?php dynamic_sidebar('sidebar-4'); ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
				</div>		
			</div>
            
            <?php
			}
            ?>
			<div class="site-info-wrap">
                <div class="site-info clearfix">
                    <?php if ( $footer_bottom_right == '1' ) { ?>
                    <div class="footer-nav-wrap">
                        <?php
                            $nx_footer_args = array(
                                'theme_location'  => 'footer-menu',
                                'container'       => 'div',
                                'container_class' => 'footer-nav',
                                'menu_class'      => 'footer-menu',
                                'echo'            => true,
                                'depth'           => 1
                            );
                            wp_nav_menu( $nx_footer_args );				
                        ?>
                    </div>
                    <?php
                    } elseif ( $footer_bottom_right == '2' ) {
                        echo nx_footer_social();					
                    } else
                    {
                    }
                    ?>

                    <div class="footer-copyright">
                        <?php if ( $footer_copyright != '' )
                            {
                                echo $footer_copyright;
                            }
                        ?>
                    </div>
                    <div class="clearfix"></div>                    
                </div><!-- .site-info -->
            </div><!-- .site-info-wrap -->
            <?php
				//}
            ?>
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>