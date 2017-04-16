<?php
/*
Plugin Name: WooCommerce Product Image Flipper
Plugin URI: http://jameskoster.co.uk/tag/product-image-flipper/
Version: 0.2.0
Description: Adds a secondary image on product archives that is revealed on hover. Perfect for displaying front/back shots of clothing and other products.
Author: jameskoster
Author URI: http://jameskoster.co.uk

	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


	/**
	 * New Badge class
	 **/
	if ( ! class_exists( 'NX_pif' ) ) {

		class NX_pif {

			public function __construct() {
				add_action( 'wp_enqueue_scripts', array( $this, 'nx_pif_scripts' ) );														// Enqueue the styles
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'nx_template_loop_second_product_thumbnail' ), 11 );
				add_filter( 'post_class', array( $this, 'nx_product_has_gallery' ) );
			}


	        /*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/

			// Setup styles
			function nx_pif_scripts() {
				wp_enqueue_style( 'pif-styles', get_template_directory_uri() . '/inc/nx-product-image-flipper/assets/css/style.css', array(), '1.0.1' );
				wp_enqueue_script( 'pif-script', get_template_directory_uri() . '/inc/nx-product-image-flipper/assets/js/script.js', array(), '1.0.1' );
			}

			// Add pif-has-gallery class to products that have a gallery
			function nx_product_has_gallery( $classes ) {
				global $product;

				$post_type = get_post_type( get_the_ID() );

				if ( ! is_admin() ) {

					if ( $post_type == 'product' ) {

						$attachment_ids = $product->get_gallery_image_ids();

						if ( $attachment_ids ) {
							$classes[] = 'pif-has-gallery';
						}
					}

				}

				return $classes;
			}


			/*-----------------------------------------------------------------------------------*/
			/* Frontend Functions */
			/*-----------------------------------------------------------------------------------*/

			// Display the second thumbnails
			function nx_template_loop_second_product_thumbnail() {
				global $product, $woocommerce;

				$attachment_ids = $product->get_gallery_image_ids();

				if ( $attachment_ids ) {
					$secondary_image_id = $attachment_ids['0'];
					echo wp_get_attachment_image( $secondary_image_id, 'shop_catalog', '', $attr = array( 'class' => 'secondary-image attachment-shop-catalog' ) );
				}
			}

		}


		$NX_pif = new NX_pif();
	}
}