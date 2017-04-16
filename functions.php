<?php
/**
 * i-spirit functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * @package i_spirit
 * @since i-spirit 1.0
 */

/*
 * Set up the content width value based on the theme's design.
 *
 * @see ispirit_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 604;




/**
 * i-spirit setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * i-spirit supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_setup() {
	/*
	 * Makes i-spirit available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on i-spirit, use a find and
	 * replace to change 'ispirit' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'ispirit', get_template_directory() . '/languages' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'css/fonts/genericons.css', ispirit_fonts_url() ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'video'
	) );

	// This theme uses wp_nav_menu() in two location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'ispirit' ) );	
	register_nav_menu( 'alt-primary', __( 'Alternate Navigation Menu', 'ispirit' ) );		
	register_nav_menu( 'footer-menu', __( 'Footer Menu', 'ispirit' ) );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );

	// add woocommerce support
	add_theme_support( 'woocommerce' );	
	
	
	set_post_thumbnail_size( 604, 270, true );
	
	add_image_size( 'widget-image', 96, 96, true);
	add_image_size( 'thumb-square', 250, 250, true);
	add_image_size( 'thumb-image', 600, 450, true);
	add_image_size( 'thumb-image-twocol', 900, 675, true);
	add_image_size( 'thumb-image-onecol', 1800, 1200, true);
	add_image_size( 'blog-image', 1280, 9999);
	add_image_size( 'full-width-image-gallery', 1280, 720, true);	

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'ispirit_setup' );


/* To check if plugin active using is_plugin_active($plugin)
	================================================== */ 
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


/* VARIABLE DEFINITIONS
	================================================== */ 
define('NX_TEMPLATE_PATH', get_template_directory());
define('NX_INCLUDES_PATH', NX_TEMPLATE_PATH . '/inc');
define('NX_WIDGETS_PATH', NX_INCLUDES_PATH . '/widgets');
define('NX_LOCAL_PATH', get_template_directory_uri());

/**
 * Return the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since i-spirit 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function ispirit_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Open Sans, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	 //fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300,600,700|Roboto:400,400italic,500italic,700italic'
	$open_sans = _x( 'on', 'Open Sans font: on or off', 'ispirit' );

	/* Translators: If there are characters in your language that are not
	 * supported by Roboto, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$roboto = _x( 'on', 'Roboto font: on or off', 'ispirit' );

	if ( 'off' !== $open_sans || 'off' !== $roboto ) {
		$font_families = array();

		if ( 'off' !== $open_sans )
			$font_families[] = 'Open Sans:300,400,700,300italic,400italic,700italic';

		if ( 'off' !== $roboto )
			$font_families[] = 'Roboto:300,400,700';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_scripts_styles() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	//++ Adds Masonry to handle blog layout. 
	//if ( is_active_sidebar( 'sidebar-1' ) )
		wp_enqueue_script( 'jquery-masonry' );

	
	// modernizer
	wp_enqueue_script( 'jquery-modernizer', get_template_directory_uri() . '/js/modernizr.custom.js', array( 'jquery' ), '2013-07-18', true );

	// Loads JavaScript file for tooltips
	wp_enqueue_script( 'jquery-easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array( 'jquery' ), '1.3.0', true );
	
	// Loads JavaScript file for tooltips
	wp_enqueue_script( 'ispirit-tooltips', get_template_directory_uri() . '/js/jquery.tooltipster.min.js', array( 'jquery' ), '3.1.0', true );
	
	// Loads JavaScript file for tooltips
	wp_enqueue_script( 'jquery-lavalamp', get_template_directory_uri() . '/js/jquery.lavalamp.min.js', array( 'jquery' ), '1.5.0', true );	
	
	// enque elvatezoom if woocommerce active
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_woocommerce() ) {
		wp_enqueue_script( 'jquery-chosen', get_template_directory_uri() . '/js/chosen.jquery.min.js', array( 'jquery' ), '3.0.8', true );

		// Loads infinite-scroll
		wp_enqueue_script( 'infinite-scroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array( 'jquery' ), '1.5.0', true );
		// image loaded
		wp_enqueue_script( 'imageloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '1.5.0', true );		
		
		
		wp_enqueue_style( 'jquery-chosen', get_template_directory_uri() . '/css/chosen.min.css', array(), '0.1' );	
	}	
	
	
	// Loads JavaScript file for tooltips
	wp_enqueue_script( 'inview', get_template_directory_uri() . '/js/jquery.inview.min.js', array( 'jquery' ), '1.5.0', true );
	
	// Loads JavaScript file for isotope
	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/isotope.pkgd.min.js', array( 'jquery' ), '2.0.0', true );	
	
	// Loads JavaScript file for tooltips
	wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ), '1.5.0', true );
	
	// owl carousel script
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array( 'jquery' ), '0.1', true );
	
	// magnific popup script
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/magnific-popup.js', array( 'jquery' ), '0.1', true );
	
	// sidr responsive menu
	wp_enqueue_script( 'sidr', get_template_directory_uri() . '/js/jquery.sidr.min.js', array( 'jquery' ), '1.2.1', true );	
	
	// touchswipe
	wp_enqueue_script( 'touchswipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array( 'jquery' ), '1.6.6', true );								

	// Loads JavaScript file with functionality specific to i-spirit.
	wp_enqueue_script( 'ispirit-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '2013-07-18', true );
	
	// Add Source Sans Pro and Bitter fonts, used in the main stylesheet.
	wp_enqueue_style( 'ispirit-fonts', ispirit_fonts_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/fonts/genericons.css', array(), '2.09' );

	// Add Font Awesome font, used in the main stylesheet.
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '2.09' );
	
	// Add Animate.csst, used in animate elements.
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '2.09' );	
	
	// tooltipster stylesheet.
	wp_enqueue_style( 'tooltipster', get_template_directory_uri() . '/css/tooltipster.css', array(), '3.1.0' );
	
	// tooltipster stylesheet.
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css', array(), '3.1.0' );	
	
	//++ slit slider stylesheet.
	wp_enqueue_style( 'ispririt-fx', get_template_directory_uri() . '/css/ispirit-fx.css', array(), '0.1' );
	
	// owl/nx slider stylesheet.
	wp_enqueue_style( 'nx-slider', get_template_directory_uri() . '/css/nx-slider-style.css', array(), '0.1' );	
	//wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/owl-carousel.css', array(), '0.1' );
	
	// sidr responsive menu.
	wp_enqueue_style( 'sidr', get_template_directory_uri() . '/css/jquery.sidr.dark.css', array(), '1.2.1' );						

	// Loads our main stylesheet.
	wp_enqueue_style( 'ispirit-style', get_stylesheet_uri(), array(), '2013-07-18' );
	
	// custom redux stylesheet.
	//wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/inc/ispirit-options/custom-style.css', array(), '0.1' );		
	$upload_dir = wp_upload_dir();
	wp_enqueue_style( 'custom-style', $upload_dir['baseurl'] . '/custom-style.css', array(), '0.1' );	

}
add_action( 'wp_enqueue_scripts', 'ispirit_scripts_styles' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since i-spirit 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function ispirit_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'ispirit' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'ispirit_wp_title', 10, 2 );

/**
 * Register 8 widget areas.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Right Sidebar (column)', 'ispirit' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears on the right.', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Left Sidebar (column)', 'ispirit' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears on the left.', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Column One', 'ispirit' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears on Bottom 1st position from left', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Column Two', 'ispirit' ),
		'id'            => 'sidebar-4',
		'description'   => __( 'Appears on Bottom 2nd position from left.', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Column Three', 'ispirit' ),
		'id'            => 'sidebar-5',
		'description'   => __( 'Appears on Bottom 3rd position from left.', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Column Four', 'ispirit' ),
		'id'            => 'sidebar-6',
		'description'   => __( 'Appears on Bottom 4th position from left.', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );	
	
	register_sidebar( array(
		'name'          => __( 'Woocommerce Right Sidebar', 'ispirit' ),
		'id'            => 'sidebar-7',
		'description'   => __( 'Appears at the right side of the woocommerece pages.', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );	
	
	register_sidebar( array(
		'name'          => __( 'Woocommerce Left Sidebar', 'ispirit' ),
		'id'            => 'sidebar-8',
		'description'   => __( 'Appears at the left side of the woocommerce pages.', 'ispirit' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );					
}
add_action( 'widgets_init', 'ispirit_widgets_init' );

if ( ! function_exists( 'ispirit_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_paging_nav() {
	
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
    <?php
		$big = 999999999; // need an unlikely integer
		$args = array(
			'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'type' => 'list',
			'prev_text' => '<span class="text">&laquo; ' . __( 'Previous', 'ispirit' ) . '</span>',
			'next_text' => '<span class="text">' . __( 'Next', 'ispirit' ) . ' &raquo;</span>'					
		);
	?>				    
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'ispirit' ); ?></h1>
		<div class="nav-links">
            <div id="posts-nav" class="navigation">
				<?php echo paginate_links( $args ); ?>
            </div><!-- #posts-nav -->
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'ispirit_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
*
* @since i-spirit 1.0
*
* @return void
*/
function ispirit_post_nav() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
	?>
	<nav class="navigation post-navigation reversed-link" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'ispirit' ); ?></h1>
		<div class="nav-links">

			<?php previous_post_link( '%link', _x( '<span class="meta-nav">&#8249;</span> %title', 'Previous post link', 'ispirit' ) ); ?>
			<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&#8250;</span>', 'Next post link', 'ispirit' ) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'ispirit_entry_meta' ) ) :
/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own ispirit_entry_meta() to override in a child theme.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . __( 'Sticky', 'ispirit' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		ispirit_entry_date();
		
	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'ispirit' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links">' . $tag_list . '</span>';
	}		

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'ispirit' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'ispirit' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;




if ( ! function_exists( 'ispirit_entry_meta_single' ) ) :
/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date for single articles posts.
 *
 * Create your own ispirit_entry_meta_sigle() to override in a child theme.
 *
 *
 * @return void
 */
function ispirit_entry_meta_single() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . __( 'Sticky', 'ispirit' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		ispirit_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'ispirit' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}
}
endif;



if ( ! function_exists( 'ispirit_entry_meta_blog' ) ) :
/**
 * Print HTML with meta information for blogs.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_entry_meta_blog() {
	if ( is_sticky() && is_home() && ! is_paged() )
	{
		echo '<span class="featured-post">' . __( 'Sticky', 'ispirit' ) . '</span>';
	}
	
	echo '<span class="author-1">' . sprintf(__('By <a href="%2$s" class="fn">%1$s</a>', 'ispirit'), get_the_author_link(), get_author_posts_url(get_the_author_meta( 'ID' ))) . '</span>'; 
    
                    
	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
	{
		echo '<span class="date">' . the_date('M j, Y') . '</span>';
	}
	$categories_list = get_the_category_list( __( ', ', 'ispirit' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}
}
endif;



if ( ! function_exists( 'ispirit_entry_date' ) ) :
/**
 * Print entry dates.
 *
 *
 * @since i-spirit 1.0
 */
function ispirit_entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'ispirit' );
	else
		$format_prefix = '%2$s';

	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'ispirit' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}
endif;


if ( ! function_exists( 'ispirit_entry_meta_blog_standard' ) ) :
/**
 * Print HTML with meta information for standard blog post.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_entry_meta_blog_standard( $showcat="2" ) {

	echo '<span class="author-1">' . sprintf(__('By <a href="%2$s" class="fn">%1$s</a>'), get_the_author_link(), get_author_posts_url(get_the_author_meta( 'ID' ))) . '</span>'; 
    
	$categories_list = get_the_category_list( __( ', ', 'ispirit' ) );
	if ( $categories_list && $showcat == "2" ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}
}
endif;


if ( ! function_exists( 'ispirit_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since i-spirit 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	$attachment_size     = apply_filters( 'ispirit_attachment_size', array( 724, 724 ) );
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since i-spirit 1.0
 *
 * @return string The Link format URL.
 */
function ispirit_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since i-spirit 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function ispirit_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';
	
	//Adding body class for WooCommerce infinite scroll	
	global  $ispirit_data;
	
	$infinite_scroll = 2;
	if( !empty($ispirit_data['woo-infi-scroll']) ) { $infinite_scroll = $ispirit_data['woo-infi-scroll']; }
	if( $infinite_scroll == 1 )
	{
		$classes[] = 'woo-infiscroll';
	}
		

	return $classes;
}
add_filter( 'body_class', 'ispirit_body_class' );

/**
 * Adjust content_width value for video post formats and attachment templates.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_content_width() {
	global $content_width;

	if ( is_attachment() )
		$content_width = 1200;
	elseif ( has_post_format( 'audio' ) )
		$content_width = 484;
}
add_action( 'template_redirect', 'ispirit_content_width' );

/**
 * Add a stylesheet for admin panels
 * @since i-spirit 1.0
 */
add_action('admin_init', 'ispirit_admin_css');
function ispirit_admin_css() {
   wp_register_style( 'ispirit-admin-css', get_template_directory_uri() . '/css/admin-style.css' );
   wp_enqueue_style( 'ispirit-admin-css' );
}

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since i-spirit 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function ispirit_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'ispirit_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 *
 * @since i-spirit 1.0
 *
 * @return void
 */
function ispirit_customize_preview_js() {
	wp_enqueue_script( 'ispirit-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130226', true );
}
add_action( 'customize_preview_init', 'ispirit_customize_preview_js' );


/**
 * Change Tag Clouds Font Size
 *
 * @since i-spirit 1.0
 *
 */

function ispirit_tag_cloud_args( $args ) {
	$args['largest'] = 12;
	$args['smallest'] = 12;
	$args['unit'] = 'px';
	$args['format'] = 'list';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'ispirit_tag_cloud_args' );


/**
 * woocommerce remove breadcrumb
 *
 * @since i-spirit 1.0
 *
 */

add_action( 'init', 'ispirit_remove_wc_hooks' );
function ispirit_remove_wc_hooks() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);	
}

add_theme_support( 'woocommerce' );


/*
*	check if woocommerece active and load woocommerece specific functions
*/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require_once( NX_INCLUDES_PATH . '/woo-functions.php');
	require_once( NX_INCLUDES_PATH . '/nx-search-form/nxs.php' );	
	require_once( NX_INCLUDES_PATH . '/nx-product-image-flipper/image-flipper.php' );
}

/*
 *
 * LOAD BACKEND SCRIPTS
 */
function ispirit_admin_scripts() {
    wp_register_script('admin-functions', get_template_directory_uri() . '/js/nx-admin.js', 'jquery', '1.0', TRUE);
	wp_enqueue_script('admin-functions');
}
add_action('admin_init', 'ispirit_admin_scripts');



/*
*	Loading Redux
*/
if ( !class_exists( 'ReduxFramework' ) && file_exists( get_template_directory().'/inc/redux/framework.php' ) ) {
    require_once( get_template_directory().'/inc/redux/framework.php' );
}
if ( !isset( $ispirit_data ) && file_exists( get_template_directory() . '/inc/ispirit-options/ispirit-config.php' ) ) {
    require_once( get_template_directory() . '/inc/ispirit-options/ispirit-config.php' );
}


/*
*	Loading Custom Functions
*/
include( get_template_directory() . '/inc/tnext-meta.php' );
require_once( get_template_directory() . '/inc/meta-box/meta-box.php' );
require_once( get_template_directory() . '/inc/custom-functions.php' );
require_once( get_template_directory() . '/inc/custom-header.php' );
require_once( get_template_directory() . '/inc/tnext-custom-style.php' );


/*
*	Loading Widgets
*/
include_once(NX_INCLUDES_PATH . '/widgets/widget-twitter.php');
include_once(NX_INCLUDES_PATH . '/widgets/widget-portfolio.php');
	
if ( !is_plugin_active( 'templatesnext-toolkit/tx-toolkit.php' ) ) {
	include_once(NX_INCLUDES_PATH . '/widgets/widget-posts.php');
	include_once(NX_INCLUDES_PATH . '/widgets/widget-portfolio-grid.php');
	include_once(NX_INCLUDES_PATH . '/widgets/widget-advertgrid.php');
	include_once(NX_INCLUDES_PATH . '/widgets/widget-comments.php');
	include_once(NX_INCLUDES_PATH . '/widgets/widget-image.php');
}
	
/*
*	Loading aq_resize
*/
if ( !is_plugin_active( 'templatesnext-toolkit/tx-toolkit.php' ) ) {	
	require_once( get_template_directory() . '/inc/plugins/aq_resizer.php' );
}

//Initialize the update checker.
require 'update/theme-update-checker.php';
$example_update_checker = new ThemeUpdateChecker(
    'ispirit',
    'http://templatesnext.org/downloads/update-check/i-spirit-update.json'
);


/*
*	Notifications
*/
require( 'inc/class-remote-notification-client.php' );
if ( function_exists( 'rdnc_add_notification' ) ) {
    rdnc_add_notification( 55, '23dbcf6e11811ad0', 'http://www.templatesnext.org/icreate/' );
}