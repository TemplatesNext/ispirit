<?php
/**
 * The default template for displaying content
 *
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */
?>

<?php

	$th_width = 120;
	$th_height = 80;
	$image_holder_res = get_template_directory_uri()."/images/placeholder-120x80.png";
	
	if ( has_post_thumbnail() && ! post_password_required() )
	{
		$thumb_image_id = get_post_thumbnail_id();
		$thumb_img_url = wp_get_attachment_url( $thumb_image_id,'full' ); //get full URL to image (use "large" or "medium" if the images too big)
		$thumb_resized = aq_resize( $thumb_img_url, $th_width, $th_height, true ); //resize & crop the image
	}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="position:relative;">
	<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
	<div class="entry-thumbnail">
		<?php echo '<img src="'.$thumb_resized.'" class="post-th-image" alt="" >'; ?>
	</div>
    <?php else : ?>
	<div class="entry-thumbnail">
		<?php echo '<img src="'.$image_holder_res.'" class="post-th-image" alt="" >'; ?>
	</div>    
	<?php endif; ?>
    
    <div class="post-result">
        <header class="entry-header">
            <h1 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h1>
            <div class="entry-meta">
                <?php ispirit_entry_date(); ?>
            </div><!-- .entry-meta -->
        </header><!-- .entry-header -->
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
    </div>
</article><!-- #post -->
