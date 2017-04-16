<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
?>

<?php
	global $product, $woocommerce_loop;
	if ( empty( $woocommerce_loop['columns'] ) )
	{
		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );	
	} else
	{
		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
	}


	global  $ispirit_data;

	$woo_layout_style = $ispirit_data['woo-archive-style'];


	$woo_layout_class = "nx-woo-default";
	
	if( $woo_layout_style == 2 )
	{
		$woo_layout_class = "nx-woo-modern";
	}	
?>

<span class="<?php echo $woo_layout_class; ?>">
<ul class="products woo-col-<?php echo $woocommerce_loop['columns']; ?> woo-isotope" data-column-count="<?php echo $woocommerce_loop['columns']; ?>">