<?php
/**
 * Template for portfolio nav filters
 *
 * @package templatesnext Shortcode
 */
$args = array(
	'orderby' => 'name',
);
$taxonomy = $atts['taxonomy'];
if ( ! empty( $taxonomy ) ) {
	$terms = get_terms( $taxonomy, $args );

	if ( ! is_wp_error( $terms ) || empty( $terms ) ) {

		$links = array();
		$links[] = "<input type=\"button\" data-filter='*' value=\"All\" class=\"is-checked\" >";


		foreach ( $terms as $term ) {
			$term_link = get_term_link( $term );
			$links[] = "<input type=\"button\" data-filter='.nx-folio-filter-{$term->slug}' value=\"{$term->name}\" >";
		}
?>

		<?php if ( sizeof( $links ) > 2 ) : ?>
			<nav class="folio-filtering folio-nav-<?php echo $taxonomy; ?>" style="text-align: <?php echo $atts['pagnav_align']; ?>">
				<?php echo implode( "<span class='tag-divider'></span>", $links ); ?>
			</nav>
		<?php endif; ?>

		<?php
	}
}
