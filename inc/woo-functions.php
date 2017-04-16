<?php
	/*
	*
	*	nx woocommerce Functions
	*	------------------------------------------------
	*	nx Framework v 1.0
	*
	*	nx_woo_bar()
	*	nx_top_login_form()
	*	nx_top_cart()
	*	woocommerce_header_add_to_cart_fragment()
	*	ispirit_related_products_args()
	*	ispirit_taxonomy_add_new_meta_field()
	*	ispirit_taxonomy_edit_meta_field()
	*	ispirit_save_taxonomy_custom_meta()
	*	loop_columns()								
	*
	*/
	
 	/* TOP WOOCOMMERCE BAR
 	================================================== */
 	if (!function_exists('nx_woo_bar')) {
		function nx_woo_bar() {
			
			global $woocommerce;
			global $ispirit_data;
			
			$enable_compare = $ispirit_data['enable-compare'];

			$current_user = wp_get_current_user();
			$current_user_name = $current_user->user_firstname;
			
			$login_url = wp_login_url();
			$logout_url = wp_logout_url( home_url() );
			$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
			
			if ( $myaccount_page_id ) {
				$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );
			  	$login_url = get_permalink( $myaccount_page_id );
			  	if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) {
			    	$logout_url = str_replace( 'http:', 'https:', $logout_url );
					$login_url = str_replace( 'http:', 'https:', $login_url );
				}
			}			
						
			$woo_bar_output = '';

			//$woo_bar_output .= '<div class="woocombar">' . "\n";
			$woo_bar_output .= '<ul class="woocom">' . "\n";
			
			if ( is_user_logged_in() ) {
            	$woo_bar_output .= '<li class="top-login user-submenu"><a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '" class="admin-link">'.esc_attr__('Hi ', 'ispirit') .$current_user_name.'!</a>';
				
				$woo_bar_output .= '<ul>';
				$woo_bar_output .= '<li><a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '" class="admin-link">' . esc_attr__('My Account', 'ispirit') . '</a></li>';
				$woo_bar_output .= '<li><a href="' . $woocommerce->cart->get_checkout_url() . '" class="check-header">' . esc_attr__('Checkout', 'ispirit') . '</a></li>';
				$woo_bar_output .= '<li><a href="' . wc_customer_edit_account_url() . '" class="admin-link">' . esc_attr__('Edit Account', 'ispirit') . '</a></li>';
				
				$woo_bar_output .= '</ul>';
				
				$woo_bar_output .= '</li>' . "\n";
				
				if ( $myaccount_page_id ) {
            		$woo_bar_output .= '<li><a href="' . wp_logout_url(home_url()) . '">' . esc_attr__('Sign Out', 'ispirit') . '</a></li>' . "\n";
				} else {
                	$woo_bar_output .= '<li><a href="' . get_admin_url() .'" class="admin-link">' . esc_attr__('My Account', 'ispirit') . '</a></li>' . "\n";
				}
			
			} else {
				$woo_bar_output .= nx_top_login_form();
			}
			
			// wish list
			if(get_option( 'yith_wcwl_enabled' ) == "yes")
			{
				global $yith_wcwl;
				if(isset($yith_wcwl)){
					$woo_bar_output .= '<li><a href="' .  $yith_wcwl->get_wishlist_url() . '" class="admin-link">' . esc_attr__('My Wishlist', 'ispirit') . '</a></li>' . "\n";
				}
			}
			// compare
			if ( function_exists( 'yith_woocompare_constructor' ) && $enable_compare == 1 ) {		
  				$woo_bar_output .= '<li><a href="#" class="yith-woocompare-open" >' . esc_attr__('Compare', 'ispirit') . '</a></li>' . "\n";
			}
			
			$woo_bar_output .= '<li class="topcart"><a href="'. $woocommerce->cart->get_cart_url() .'" >' . esc_attr__('Cart', 'ispirit') . '<span class="cart-counts">' . sprintf($woocommerce->cart->cart_contents_count) . '</span></a>'.nx_top_cart().'</li>' . "\n";
			
			$woo_bar_output .= '</ul>' . "\n";
			//$woo_bar_output .= '</div>' . "\n";
			
			return $woo_bar_output;	
		}
	}
	
	
 	/* TOP BAR LOGIN Form
 	================================================== */
 	if (!function_exists('nx_top_login_form')) {
		
		function nx_top_login_form() {
			
			global $woocommerce;
			global $ispirit_data;			
			
			//$login_url = wp_login_url();
			
			$current_user = wp_get_current_user();
			$current_user_name = $current_user->user_firstname;
			
			$login_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
			$logout_url = wp_logout_url( home_url() );
			$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
			if ( $myaccount_page_id ) {
				$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );
			  	$login_url = get_permalink( $myaccount_page_id );
			  	if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) {
			    	$logout_url = str_replace( 'http:', 'https:', $logout_url );
					$login_url = str_replace( 'http:', 'https:', $login_url );
				}
			}				
			
			$top_bar_loginform = '';
			
			$top_bar_loginform .= '<li class="top-login"><a href="' . $login_url . '">' . esc_attr__('Login/Register', 'ispirit') . '</a>'. "\n";
			$top_bar_loginform .= '<form method="post" class="toplogin" action="' . $login_url . '">'. "\n";
			$top_bar_loginform .= '<ul>'. "\n";
			$top_bar_loginform .= '<li><label for="username">' . esc_attr__('Username', 'ispirit') . ' <span class="required">*</span></label></li>'. "\n";
			$top_bar_loginform .= '<li><input type="text" class="input-text" name="username" id="username" value="" /></li>'. "\n";
			$top_bar_loginform .= '<li><label for="password">' . esc_attr__('Password', 'ispirit') . ' <span class="required">*</span></label></li>'. "\n";
			$top_bar_loginform .= '<li><input class="input-text" type="password" name="password" id="password" /></li>'. "\n";
			$top_bar_loginform .= '<li><input type="submit" class="button" name="login" value="' . esc_attr__('Login', 'ispirit') . '" /></li>'. "\n";
			$top_bar_loginform .= '<li class="reg-link"><a href="' . $login_url . '" class="resig">' . esc_attr__('Register', 'ispirit') . '</a>';
			$top_bar_loginform .= '<a href="' . wp_lostpassword_url( $login_url ) . '" class="forgot">' . esc_attr__('Forgot password', 'ispirit') . '</a></li>' . "\n";
			$top_bar_loginform .= '</ul>'. "\n";
			$top_bar_loginform .= wp_nonce_field( 'woocommerce-login' ).do_action( 'woocommerce_login_form_end' ).'</form>'. "\n";
			$top_bar_loginform .= '</li>'. "\n";
			
			
			//$top_bar_loginform .= '<li><a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '" class="admin-link">My Account</a></li>' . "\n";
			
			
			return $top_bar_loginform;	
			
		}
	}
	

 	/* TOP BAR LOGIN Form
 	================================================== */
 	if (!function_exists('nx_top_cart')) {
		function nx_top_cart() {
				
			global $woocommerce;
			$nx_top_cart = '';
			
			$nx_top_cart .= '<div class="cartdrop widget_shopping_cart nx-animate">';
			$nx_top_cart .= '<div class="widget_shopping_cart_content">';
			$nx_top_cart .= '<ul class="cart_list product_list_widget">';
			$nx_top_cart .= '</ul>';
			$nx_top_cart .= '</div>';
			$nx_top_cart .= '</div>';
			
			
			return $nx_top_cart;
		}
	}


// Ensure cart contents update when products are added to the cart via AJAX
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
 
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	ob_start();
	
	?>
	<span class="cart-counts"><?php echo sprintf($woocommerce->cart->cart_contents_count);?></span>
	<?php
	
	$fragments['span.cart-counts'] = ob_get_clean();
	
	return $fragments;
	
}


/**
 * Hook in on activation
*/  

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'ispirit_woocommerce_image_dimensions', 1 );

/**
 * Define image sizes
 */
function ispirit_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '400',	// px
		'height'	=> '400',	// px
		'crop'		=> 1 		// true
	);
 
	$single = array(
		'width' 	=> '600',	// px
		'height'	=> '600',	// px
		'crop'		=> 1 		// true
	);
 
	$thumbnail = array(
		'width' 	=> '60',	// px
		'height'	=> '60',	// px
		'crop'		=> 0 		// false
	);
 
	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}



/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *

function woo_related_products_limit() {
  global $product;
	
	$args['posts_per_page'] = 4;
	return $args;
}
add_filter( 'woocommerce_related_products_args', 'woo_related_products_limit' );
 */
 
/**
* WooCommerce Extra Feature
* --------------------------
*
* Change number of related products on product page
* Set your own value for 'posts_per_page'
*
*/



add_filter( 'woocommerce_output_related_products_args', 'ispirit_related_products_args' );
function ispirit_related_products_args( $args ) {
	
	global  $ispirit_data;
		
	$woo_columns = 4;
		
	if ( !empty($ispirit_data['woo-archive-columns']) )
	{
		$woo_columns = $ispirit_data['woo-archive-columns'];
	}
			
	$args['posts_per_page'] = $woo_columns;
	$args['posts_per_page'] = 10;	 
	$args['columns'] = $woo_columns; 
	return $args;
}


function ispirit_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[custom_term_meta_3]"><?php _e( 'Hide Title Bar', 'ispirit' ); ?></label>
        <select name="term_meta[custom_term_meta_3]" id="term_meta[custom_term_meta_3]">
        	<option value="1" selected="selected" >Show</option>
            <option value="2">Hide</option>
        </select>         
		<p class="description"><?php _e( 'Hide Title bar in this category listing page','ispirit' ); ?></p>
	</div>    
	<div class="form-field">
		<label for="term_meta[custom_term_meta_1]"><?php _e( 'Revolution slider alias', 'ispirit' ); ?></label>
		<input type="text" name="term_meta[custom_term_meta_1]" id="term_meta[custom_term_meta_1]" value="">
		<p class="description"><?php _e( 'Enter the revolution slider alias to show in this category listng page','ispirit' ); ?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[custom_term_meta_2]"><?php _e( 'LayerSlider ID', 'ispirit' ); ?></label>
		<input type="text" name="term_meta[custom_term_meta_2]" id="term_meta[custom_term_meta_2]" value="">
		<p class="description"><?php _e( 'Enter the layerslider ID to show in this category listng page','ispirit' ); ?></p>
	</div>    
<?php
}
add_action( 'product_cat_add_form_fields', 'ispirit_taxonomy_add_new_meta_field', 10, 2 );



// Edit term page
function ispirit_taxonomy_edit_meta_field($term) {
 
    // put the term ID into a variable
    $t_id = $term->term_id;
 
    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option( "taxonomy_$t_id" ); ?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[custom_term_meta_3]"><?php _e( 'Hide Title Bar', 'ispirit' ); ?></label></th>
        <td>
            <?php
				if ( empty($term_meta['custom_term_meta_3']) ) { $term_meta['custom_term_meta_3'] = '1'; }
				if( $term_meta['custom_term_meta_3'] == '1' )
				{ 
			?>
                <select name="term_meta[custom_term_meta_3]" id="term_meta[custom_term_meta_3]">
                  <option value="1" selected="selected">Show</option>
                  <option value="2">Hide</option>
                </select> 
            <?php
				} else
				{ 
            ?>
                <select name="term_meta[custom_term_meta_3]" id="term_meta[custom_term_meta_3]">
                  <option value="1">Show</option>
                  <option value="2" selected="selected">Hide</option>
                </select> 
			<?php
				
				}
            ?>

            <p class="description"><?php _e( 'Hide Title bar in this category listing page','ispirit' ); ?></p>
        </td>
    </tr>    
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[custom_term_meta_1]"><?php _e( 'Revolution slider alias', 'ispirit' ); ?></label></th>
        <td>
        	<?php
				if ( empty($term_meta['custom_term_meta_1']) ) { $term_meta['custom_term_meta_1'] = ''; }
            ?>
            <input type="text" name="term_meta[custom_term_meta_1]" id="term_meta[custom_term_meta_1]" value="<?php echo esc_attr( $term_meta['custom_term_meta_1'] ) ? esc_attr( $term_meta['custom_term_meta_1'] ) : ''; ?>">
            <p class="description"><?php _e( 'Enter the revolution slider alias to show in this category listng page','ispirit' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[custom_term_meta_2]"><?php _e( 'Slider Shortcode', 'ispirit' ); ?></label></th>
        <td>
        	<?php
				if ( empty($term_meta['custom_term_meta_2']) ) { $term_meta['custom_term_meta_2'] = ''; }			
            ?>        
            <input type="text" name="term_meta[custom_term_meta_2]" id="term_meta[custom_term_meta_2]" value="<?php echo esc_attr( $term_meta['custom_term_meta_2'] ) ? esc_attr( $term_meta['custom_term_meta_2'] ) : ''; ?>">
            <p class="description"><?php _e( 'Enter other 3rd party slider shortcode.','ispirit' ); ?></p>
        </td>
    </tr>    
    
<?php
}
add_action( 'product_cat_edit_form_fields', 'ispirit_taxonomy_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function ispirit_save_taxonomy_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        // Save the option array.
        update_option( "taxonomy_$t_id", $term_meta );
    }
}  
add_action( 'edited_product_cat', 'ispirit_save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_product_cat', 'ispirit_save_taxonomy_custom_meta', 10, 2 );


// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		
		global  $ispirit_data;
		
		$woo_columns = 4;
		
		if ( !empty($ispirit_data['woo-archive-columns']) )
		{
			$woo_columns = $ispirit_data['woo-archive-columns'];
		}
		
		return $woo_columns; // 3 products per row
	}
}

// Display 12 products per page. Goes in functions.php
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

/**/

add_action( 'after_setup_theme', 'ispirit_woo_setup' );

function ispirit_woo_setup() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}