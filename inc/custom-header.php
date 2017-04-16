<?php
/**
 *	Customizing header for i-spirit
 *
 *	@since i-spirit 1.0
 *	nx_top_bar()
 *	add_loginout_link()
 *	nx_addbody_class() 
 *	nx_header()
 */


/*-----------------------------------------------------------------------------------*/
/* TOP BAR */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('nx_top_bar')) {
	function nx_top_bar() {
			
		global  $ispirit_data;
		
		$tb_left_option = $ispirit_data['tb-left-option']; //1 for phone/email 2 for custom text
		$tb_right_option = $ispirit_data['tb-right-option']; //1 for social links 2 for custom text
		$tb_phone = esc_attr($ispirit_data['tb-phone']);
		$tb_email = sanitize_email($ispirit_data['tb-email']);
		$tb_text = $ispirit_data['tb-text']; // validated within redux with limited html tags
		$tb_text2 = $ispirit_data['tb-text2']; // validated within redux with limited html tags
			
		$social['twitter'] = esc_attr($ispirit_data['twitter']);
		$social['facebook'] = esc_url($ispirit_data['facebook']);
		$social['skype'] = esc_attr($ispirit_data['skype']);
		$social['googleplus'] = esc_url($ispirit_data['googleplus']);
		$social['flickr'] = esc_url($ispirit_data['flickr']);
		$social['youtube'] = esc_url($ispirit_data['youtube']);
		$social['instagram'] = esc_attr($ispirit_data['instagram']);
		$social['pinterest'] = esc_attr($ispirit_data['pinterest']);
		$social['linkedin'] = esc_url($ispirit_data['linkedin']);
		

		
		$nx_topbar_output = '';
		
		if ( $tb_right_option == 1 ) {
			$nx_topbar_output .= '<div class="socialicons">';
			$nx_topbar_output .= '<ul class="social">';
			foreach ( $social as $service => $value ) :
				if(isset($value) && !empty($value))
				{
					if ($value == "twitter")
					{
						$nx_topbar_output .= '<li class="'.$service.'"><a href="http://www.twitter.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "skype")
					{
						$nx_topbar_output .= '<li class="'.$service.'"><a href="skype:'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-skype"></i></a></li> ';
					} elseif ($value == "instagram")
					{
						$nx_topbar_output .= '<li class="'.$service.'"><a href="http://instagram.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "pinterest")
					{
						$nx_topbar_output .= '<li class="'.$service.'"><a href="http://www.pinterest.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';										
					} else
					{
						$nx_topbar_output .= '<li class="'.$service.'"><a href="'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					}
				}
			endforeach;		
			$nx_topbar_output .= '</ul>';
			$nx_topbar_output .= '</div>';
		} elseif ($tb_right_option == 2)
		{
			$nx_topbar_output .= '<div class="custom-text tb-right">';
			$nx_topbar_output .= do_shortcode($tb_text2);
			$nx_topbar_output .= '</div>';			
		}
		
		if ( $tb_left_option == 1 )
		{	
			if(!empty($tb_phone))
			{
				$nx_topbar_output .= '<div class="topphone">';
				$nx_topbar_output .= '<i class="topbarico genericon genericon-phone"></i> ';
				$nx_topbar_output .= $tb_phone.' ';
				$nx_topbar_output .= '</div>';
			}
			if(!empty($tb_email))
			{
				$nx_topbar_output .= '<div class="topemail">';
				$nx_topbar_output .= '<i class="topbarico genericon genericon-mail"></i> ';
				$nx_topbar_output .= $tb_email.' ';
				$nx_topbar_output .= '</div>';
			}
		} elseif ($tb_left_option == 2)
		{
			$nx_topbar_output .= '<div class="custom-text">';
			$nx_topbar_output .= do_shortcode($tb_text);
			$nx_topbar_output .= '</div>';
		}

			
		return $nx_topbar_output;	
	}
}

/*-----------------------------------------------------------------------------------*/
/* Adding login logout menu item */
/*-----------------------------------------------------------------------------------*/
 
add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
	
	global  $ispirit_data;
	
	$eanble_login = $ispirit_data['nav-login-link'];
	
	if( $eanble_login == 1 )
		{
		if (is_user_logged_in() && $args->theme_location == 'primary') {
			$items .= '<li class="menu-item nx-mega-menu"><a href="'. wp_logout_url() .'">Log Out</a></li>';
		}
		elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
			$items .= '<li class="menu-item nx-mega-menu"><a href="'. site_url('wp-login.php') .'">Log In</a></li>';
		}
	}
	
    return $items;
}

/*-----------------------------------------------------------------------------------*/
/* add body class */
/*-----------------------------------------------------------------------------------*/
add_filter( 'body_class', 'nx_addbody_class' );
function nx_addbody_class( $classes ) {

	global  $ispirit_data;
	$header_style = $ispirit_data['header-style'];
	
	global $post;	
	$show_default_header = rwmb_meta('ispirit_default_header');	
	$custom_page_header = rwmb_meta('ispirit_page_header');	
	
	if( $custom_page_header != 0 )
	{
		$header_style = $custom_page_header;
	}
	
	if( $show_default_header || is_404() )
	{
		$header_style = 1;
	}
	
	if ( $header_style == 2 )
	{
		$classes[] = 'trans-header';
	} elseif ( $header_style == 3 )
	{
		$classes[] = 'centered-header';
	} elseif ( $header_style == 4 )
	{
		$classes[] = 'left-header';
	} elseif ( $header_style == 5 )
	{
		$classes[] = 'default-header i-max-header';
	} else
	{
		$classes[] = 'default-header';
	}

	return $classes;
}

/*-----------------------------------------------------------------------------------*/
/* Header */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('nx_header')) {
	function nx_header() {
		
		$nx_defaults = array(
			'theme_location'  => 'primary',
			'container'       => 'div',
			'container_class' => 'nav-container',
			'menu_class'      => 'nav-menu',
			'echo'            => false
		);
		$nx_ssigned = array(
			'theme_location'  => 'primary',
			'menu_class'      => 'nav-container',
			'echo'            => false
		);		
		
		$nx_alt_defaults = array(
			'theme_location'  => 'alt-primary',
			'container'       => 'div',
			'container_class' => 'nav-container',
			'menu_class'      => 'nav-menu',
			'echo'            => false
		);
		$nx_alt_ssigned = array(
			'theme_location'  => 'alt-primary',
			'menu_class'      => 'nav-container',
			'echo'            => false
		);			
		
			
		global $ispirit_data;
		global $woocommerce;
		
		global $post;	
		$alt_navigation = rwmb_meta('ispirit_alt_navigation');
		
		if ( $alt_navigation == 1 )
		{
			$nx_defaults = $nx_alt_defaults;
			$nx_ssigned = $nx_alt_ssigned;
		}		
		
		$retina_logo = $normal_logo = $reverse_logo = $logo_padding = "";

		$nav_cart_switch = $ispirit_data['nav-cart-link']; // turn on/off main nav cart link
		$nav_search = $ispirit_data['nav-search']; // turn on/off main nav search

		$header_style = $ispirit_data['header-style'];
		$custom_page_header = rwmb_meta('ispirit_page_header');	
		
		if( $custom_page_header != 0 )
		{
			$header_style = $custom_page_header;
		}		
		
		$sticky_header = $ispirit_data['sticky-header'];
		
		if(!empty($ispirit_data['logo-padding']))
		{
			$logo_padding = $ispirit_data['logo-padding'];
		}
		
		$nx_blog_name = esc_attr( get_bloginfo( 'name', 'display' ) );
		$nx_blog_desc = esc_attr( get_bloginfo( 'description', 'display' ) );
		$nx_home_url = esc_url( home_url( '/' ) );
		$nx_no_echo = false;
		
		$hd_left_option = $ispirit_data['hd-left-option']; //1 for phone/email 2 for custom text
		$hd_right_option = $ispirit_data['hd-right-option']; //1 for social links 2 for custom text
		$hd_phone = esc_attr( $ispirit_data['hd-phone'] );
		$hd_email = esc_attr( $ispirit_data['hd-email'] );
		$hd_text = $ispirit_data['hd-text']; // validated in redux
		$hd_text2 = $ispirit_data['hd-text2']; // validated in redux
		
		$hd_right_option2 = $ispirit_data['hd-right-option2']; //1 for social links 2 for custom text
		$hd_text_right2 = $ispirit_data['hd-text-right2'];		// validated in redux
			
		$social['twitter'] = esc_attr($ispirit_data['twitter']);
		$social['facebook'] = esc_url($ispirit_data['facebook']);
		$social['skype'] = esc_attr($ispirit_data['skype']);
		$social['googleplus'] = esc_url($ispirit_data['googleplus']);
		$social['flickr'] = esc_url($ispirit_data['flickr']);
		$social['youtube'] = esc_url($ispirit_data['youtube']);
		$social['instagram'] = esc_attr($ispirit_data['instagram']);
		$social['pinterest'] = esc_attr($ispirit_data['pinterest']);
		$social['linkedin'] = esc_url($ispirit_data['linkedin']);
				
		
		if (isset($ispirit_data['logo-normal']['url']) && $ispirit_data['logo-normal']['url']) {
			$normal_logo = esc_url($ispirit_data['logo-normal']['url']);
		}
		if (isset($ispirit_data['logo-retina']['url']) && $ispirit_data['logo-retina']['url']) {
			$retina_logo = esc_url($ispirit_data['logo-retina']['url']);
		}
		if (isset($ispirit_data['logo-reverse']['url']) && $ispirit_data['logo-reverse']['url']) {
			$reverse_logo = esc_url($ispirit_data['logo-reverse']['url']);
		}				
		
		$nx_header_output = '';
		
		if ( $header_style == 2 )
		{

			$nx_header_output .= '<div class="header-holder">';
			$nx_header_output .= '<div class="headerboxwrap" data-sticky-header="'.$sticky_header.'">';
			$nx_header_output .= '<header id="masthead" class="site-header" role="banner">';
			$nx_header_output .= '<div class="header-inwrap">';
			
			// header elements starts
			$nx_header_output .= '<a class="home-link" href="'.$nx_home_url.'" title="'.$nx_blog_name.'" rel="home" style="padding-top: '.$logo_padding.'px;padding-bottom: '.$logo_padding.'px;">';
			
			if( $reverse_logo != "" ) {
			$nx_header_output .= '<img src="'.$reverse_logo.'" alt="'.$nx_blog_name.'" class="reverse" />';
			}
				
			if( $normal_logo != "" && $retina_logo != "" ) {
	
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="normal" />';
			$nx_header_output .= '<img src="'.$retina_logo.'" alt="'.$nx_blog_name.'" class="retina" />';
	
			} elseif( $normal_logo != "" && $retina_logo == "" ) {
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="common" />';
								
			} else {
			$nx_header_output .= '<h1 class="site-title">'.$nx_blog_name.'</h1>';
			$nx_header_output .= '<h2 class="site-description">'.$nx_blog_desc.'</h2>';
			}               
			$nx_header_output .= '</a>';
			
			$nx_header_output .= '<span class="menu-toggle"><span class="genericon genericon-menu"></span></span>';		
			
			$nx_header_output .= '<div id="navbar" class="navbar">';
			$nx_header_output .= '<nav id="site-navigation" class="navigation main-navigation" role="navigation">';
			$nx_header_output .= '<a class="screen-reader-text skip-link" href="#content" title="'.esc_attr__( 'Skip to content', 'ispirit' ).'">'.__( 'Skip to content', 'ispirit' ).'</a>';
	
			if ( has_nav_menu(  'primary' ) ) {
			$nx_header_output .= wp_nav_menu( $nx_defaults );
			}
			else {
			$nx_header_output .= wp_nav_menu( $nx_ssigned );
			}
	
			$nx_header_output .= '</nav><!-- #site-navigation -->';
			
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				if ( $nav_cart_switch == 1 )
				{
					$nx_header_output .= '<div class="header-icons woocart"><a href="'. $woocommerce->cart->get_cart_url() .'" class="reversed" ><span class="show-sidr">Cart</span><span class="genericon genericon-cart"></span><span class="cart-counts">' . sprintf($woocommerce->cart->cart_contents_count) . '</span></a>'.nx_top_cart().'</div>' . "\n";
				}
			}
			
			if ( $nav_search == 1 )
			{
				$nx_header_output .= '<div class="headersearch">'.get_search_form($nx_no_echo).'</div>';
			}
			$nx_header_output .= '</div><!-- #navbar -->';	
			
			// header elements ends
			
			$nx_header_output .= '</div>';
			$nx_header_output .= '</header><!-- #masthead -->';
			$nx_header_output .= '</div><!-- .headerboxwrap -->';
			$nx_header_output .= '</div><!-- .header-holder -->';			
		} elseif ( $header_style == 3 )
		{
			$nx_header_output .= '<div class="header-holder">';
			$nx_header_output .= '<div class="headerboxwrap" data-sticky-header="'.$sticky_header.'">';
			$nx_header_output .= '<header id="masthead" class="site-header" role="banner">';
			$nx_header_output .= '<div class="header-inwrap">';
			
			// header elements starts
			$nx_header_output .= '<div class="logo-wrap">';

			$nx_header_output .= '<a class="home-link" href="'.$nx_home_url.'" title="'.$nx_blog_name.'" rel="home" style="padding-top: '.$logo_padding.'px;padding-bottom: '.$logo_padding.'px;">';
	
			if( $normal_logo != "" && $retina_logo != "" ) {
	
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="normal" />';
			$nx_header_output .= '<img src="'.$retina_logo.'" alt="'.$nx_blog_name.'" class="retina" />';
	
			} elseif( $normal_logo != "" && $retina_logo == "" ) {
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="common" />';
								
			} else {
			$nx_header_output .= '<h1 class="site-title">'.$nx_blog_name.'</h1>';
			$nx_header_output .= '<h2 class="site-description">'.$nx_blog_desc.'</h2>';
			}               
			$nx_header_output .= '</a>';
			
			
		$nx_header_output .= '<div class="header-right-wrap">';
		
		if ( $hd_right_option == 1 ) {
			$nx_header_output .= '<div class="socialicons">';
			$nx_header_output .= '<ul class="social">';
			foreach ( $social as $service => $value ) :
				if(isset($value) && !empty($value))
				{
					if ($value == "twitter")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="http://www.twitter.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "skype")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="skype:'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-skype"></i></a></li> ';
					} elseif ($value == "instagram")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="http://instagram.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "pinterest")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="http://www.pinterest.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';										
					} else
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					}
				}
			endforeach;		
			$nx_header_output .= '</ul>';
			$nx_header_output .= '</div>';
		} elseif ($hd_right_option == 2)
		{
			$nx_header_output .= '<div class="custom-text tb-right reversed-link">';
			$nx_header_output .= do_shortcode($hd_text2);
			$nx_header_output .= '</div>';			
		}

		$nx_header_output .= '</div> <!-- .header-right-wrap--> ';
		$nx_header_output .= '<div class="header-left-wrap">';
		
		if ( $hd_left_option == 1 )
		{	
			if(!empty($hd_phone))
			{
				$nx_header_output .= '<div class="topphone">';
				$nx_header_output .= '<i class="topbarico genericon genericon-phone"></i> ';
				$nx_header_output .= $hd_phone.' ';
				$nx_header_output .= '</div>';
			}
			if(!empty($hd_email))
			{
				$nx_header_output .= '<div class="topemail">';
				$nx_header_output .= '<i class="topbarico genericon genericon-mail"></i> ';
				$nx_header_output .= $hd_email.' ';
				$nx_header_output .= '</div>';
			}
		} elseif ($hd_left_option == 2)
		{
			$nx_header_output .= '<div class="custom-text reversed-link">';
			$nx_header_output .= do_shortcode($hd_text);
			$nx_header_output .= '</div>';
		}
		$nx_header_output .= '</div> <!-- .header-left-wrap--> ';
			
			$nx_header_output .= '</div>';			
			// header elements ends
			
			$nx_header_output .= '</div>';

			// centered header nav starts
			$nx_header_output .= '<div class="nav-wrap">';
			$nx_header_output .= '<span class="menu-toggle"><span class="genericon genericon-menu"></span></span>';		
			
			$nx_header_output .= '<div id="navbar" class="navbar clearfix">';
			$nx_header_output .= '<nav id="site-navigation" class="navigation main-navigation" role="navigation">';
			$nx_header_output .= '<a class="screen-reader-text skip-link" href="#content" title="'.esc_attr__( 'Skip to content', 'ispirit' ).'">'.__( 'Skip to content', 'ispirit' ).'</a>';
	
			if ( has_nav_menu(  'primary' ) ) {
			$nx_header_output .= wp_nav_menu( $nx_defaults );
			}
			else {
			$nx_header_output .= wp_nav_menu( $nx_ssigned );
			}
	
			$nx_header_output .= '</nav><!-- #site-navigation -->';
			
			$nx_header_output .= '<div class="header-iconwrap">';
			
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				if ( $nav_cart_switch == 1 )
				{
					$nx_header_output .= '<div class="header-icons woocart"><a href="'. $woocommerce->cart->get_cart_url() .'" class="reversed" ><span class="show-sidr">Cart</span><span class="genericon genericon-cart"></span><span class="cart-counts">' . sprintf($woocommerce->cart->cart_contents_count) . '</span></a>'.nx_top_cart().'</div>' . "\n";
				}
			}
			
			if ( $nav_search == 1 )
			{
				$nx_header_output .= '<div class="headersearch">'.get_search_form($nx_no_echo).'</div>';
			}
			
			$nx_header_output .= '</div>';
			
			$nx_header_output .= '</div><!-- #navbar -->';
			$nx_header_output .= '</div><!-- .nav-wrap -->';
			// centered header nav ends
			$nx_header_output .= '</header><!-- #masthead -->';
			$nx_header_output .= '</div><!-- .headerboxwrap -->';
			$nx_header_output .= '</div><!-- .header-holder -->';			
			
		} elseif ( $header_style == 4 )
		{
			$nx_header_output .= '<div class="header-holder">';
			$nx_header_output .= '<div class="headerboxwrap" data-sticky-header="'.$sticky_header.'">';
			$nx_header_output .= '<header id="masthead" class="site-header" role="banner">';
			$nx_header_output .= '<div class="header-inwrap">';
			
			// header elements starts
			$nx_header_output .= '<div class="logo-wrap">';
			
			$nx_header_output .= '<a class="home-link" href="'.$nx_home_url.'" title="'.$nx_blog_name.'" rel="home" style="padding-top: '.$logo_padding.'px;padding-bottom: '.$logo_padding.'px;">';
	
			if( $normal_logo != "" && $retina_logo != "" ) {
	
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="normal" />';
			$nx_header_output .= '<img src="'.$retina_logo.'" alt="'.$nx_blog_name.'" class="retina" />';
	
			} elseif( $normal_logo != "" && $retina_logo == "" ) {
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="common" />';
								
			} else {
			$nx_header_output .= '<h1 class="site-title">'.$nx_blog_name.'</h1>';
			$nx_header_output .= '<h2 class="site-description">'.$nx_blog_desc.'</h2>';
			}               
			$nx_header_output .= '</a>';
			
		if ( $hd_right_option2 == 1 ) {
			$nx_header_output .= '<div class="socialicons">';
			$nx_header_output .= '<ul class="social">';
			foreach ( $social as $service => $value ) :
				if(isset($value) && !empty($value))
				{
					if ($value == "twitter")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="http://www.twitter.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "skype")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="skype:'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-skype"></i></a></li> ';
					} elseif ($value == "instagram")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="http://instagram.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "pinterest")
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="http://www.pinterest.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';										
					} else
					{
						$nx_header_output .= '<li class="'.$service.'"><a href="'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					}
				}
			endforeach;		
			$nx_header_output .= '</ul>';
			$nx_header_output .= '</div>';
		} elseif ($hd_right_option2 == 2)
		{
			$nx_header_output .= '<div class="custom-text tb-right reversed-link">';
			$nx_header_output .= do_shortcode($hd_text_right2);
			$nx_header_output .= '</div>';			
		}				
			
			
			$nx_header_output .= '</div>';			
			// header elements ends
			
			$nx_header_output .= '</div>';

			
			// centered header nav starts
			$nx_header_output .= '<div class="nav-wrap">';
			$nx_header_output .= '<span class="menu-toggle"><span class="genericon genericon-menu"></span></span>';		
			
			$nx_header_output .= '<div id="navbar" class="navbar clearfix">';
			$nx_header_output .= '<nav id="site-navigation" class="navigation main-navigation" role="navigation">';
			$nx_header_output .= '<a class="screen-reader-text skip-link" href="#content" title="'.esc_attr__( 'Skip to content', 'ispirit' ).'">'.__( 'Skip to content', 'ispirit' ).'</a>';
	
			if ( has_nav_menu(  'primary' ) ) {
			$nx_header_output .= wp_nav_menu( $nx_defaults );
			}
			else {
			$nx_header_output .= wp_nav_menu( $nx_ssigned );
			}
	
			$nx_header_output .= '</nav><!-- #site-navigation -->';
			
			$nx_header_output .= '<div class="header-iconwrap">';
			
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				if ( $nav_cart_switch == 1 )
				{
					$nx_header_output .= '<div class="header-icons woocart"><a href="'. $woocommerce->cart->get_cart_url() .'" class="reversed" ><span class="show-sidr">Cart</span><span class="genericon genericon-cart"></span><span class="cart-counts">' . sprintf($woocommerce->cart->cart_contents_count) . '</span></a>'.nx_top_cart().'</div>' . "\n";
				}
			}
			
			if ( $nav_search == 1 )
			{
				$nx_header_output .= '<div class="headersearch">'.get_search_form($nx_no_echo).'</div>';
			}
			
			$nx_header_output .= '</div>';
			
			$nx_header_output .= '</div><!-- #navbar -->';
			$nx_header_output .= '</div><!-- .nav-wrap -->';
			// centered header nav ends
						
			
			$nx_header_output .= '</header><!-- #masthead -->';
			$nx_header_output .= '</div><!-- .headerboxwrap -->';
			$nx_header_output .= '</div><!-- .header-holder -->';		
			
		} else
		{
			$nx_header_output .= '<div class="header-holder">';
			$nx_header_output .= '<div class="headerboxwrap" data-sticky-header="'.$sticky_header.'">';
			$nx_header_output .= '<header id="masthead" class="site-header" role="banner">';
			$nx_header_output .= '<div class="header-inwrap">';
			
			// header elements starts
			$nx_header_output .= '<a class="home-link" href="'.$nx_home_url.'" title="'.$nx_blog_name.'" rel="home" style="padding-top: '.$logo_padding.'px;padding-bottom: '.$logo_padding.'px;">';
	
			if( $normal_logo != "" && $retina_logo != "" ) {
	
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="normal" />';
			$nx_header_output .= '<img src="'.$retina_logo.'" alt="'.$nx_blog_name.'" class="retina" />';
	
			} elseif( $normal_logo != "" && $retina_logo == "" ) {
			$nx_header_output .= '<img src="'.$normal_logo.'" alt="'.$nx_blog_name.'" class="common" />';
								
			} else {
			$nx_header_output .= '<h1 class="site-title">'.$nx_blog_name.'</h1>';
			$nx_header_output .= '<h2 class="site-description">'.$nx_blog_desc.'</h2>';
			}               
			$nx_header_output .= '</a>';
			
			$nx_header_output .= '<span class="menu-toggle"><span class="genericon genericon-menu"></span></span>';		
			
			$nx_header_output .= '<div id="navbar" class="navbar">';
			$nx_header_output .= '<nav id="site-navigation" class="navigation main-navigation" role="navigation">';
			$nx_header_output .= '<a class="screen-reader-text skip-link" href="#content" title="'.esc_attr__( 'Skip to content', 'ispirit' ).'">'.__( 'Skip to content', 'ispirit' ).'</a>';
	
			if ( has_nav_menu(  'primary' ) ) {
			$nx_header_output .= wp_nav_menu( $nx_defaults );
			}
			else {
			$nx_header_output .= wp_nav_menu( $nx_ssigned );
			}
	
			$nx_header_output .= '</nav><!-- #site-navigation -->';
			
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				if ( $nav_cart_switch == 1 )
				{
					$nx_header_output .= '<div class="header-icons woocart"><a href="'. $woocommerce->cart->get_cart_url() .'" class="reversed" ><span class="show-sidr">Cart</span><span class="genericon genericon-cart"></span><span class="cart-counts">' . sprintf($woocommerce->cart->cart_contents_count) . '</span></a>'.nx_top_cart().'</div>' . "\n";
				}
			}
			
			if ( $nav_search == 1 )
			{
				$nx_header_output .= '<div class="headersearch">'.get_search_form($nx_no_echo).'</div>';
			}
			$nx_header_output .= '</div><!-- #navbar -->';	
			
			// header elements ends
			
			$nx_header_output .= '</div>';
			$nx_header_output .= '</header><!-- #masthead -->';
			$nx_header_output .= '</div><!-- .headerboxwrap -->';
			$nx_header_output .= '</div><!-- .header-holder -->';			
			
		}
		
		return $nx_header_output;	
	}
}


?>
