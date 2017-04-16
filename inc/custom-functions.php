<?php
	
	/*
	*
	*	nx Custom Functions
	*	------------------------------------------------
	*	nx Framework v 1.0
	*
	*	nx_custom_excerpt_length()
	*	isprit_excerpt()
	*	nx_folio_display_term_classes()
	*	nx_latest_tweet()
	*	nx_maintenance_mode()
	*	nx_tnext_slider()
	*	nx_footer_social)
	*	nx_category_custom_post()
	*
	*/
	
	
	/*
	*
	*
	*/

/*-----------------------------------------------------------------------------------*/	
/* custom excerpt length */
/*-----------------------------------------------------------------------------------*/
$nx_length = 60;
function nx_custom_excerpt_length( $length ) {
	global $nx_length;
	return $nx_length;
}
add_filter( 'excerpt_length', 'nx_custom_excerpt_length', 999 );

// add more link to excerpt
function nx_custom_excerpt_more($more) {
global $post;
	return '<div class="read-more"><a class="more-link" href="'. get_permalink($post->ID) . '">'. esc_html__('Read More ...', 'ispirit') .'</a></div>';
}
add_filter('excerpt_more', 'nx_custom_excerpt_more');


/*-----------------------------------------------------------------------------------*/
/*	changing default Excerpt length 
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'isprit_excerpt' ) ) {
	function isprit_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).' ...';
		} else {
			$excerpt = implode(" ",$excerpt);
		}
		//$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		return $excerpt;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Nx Display Term Classes */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'nx_folio_display_term_classes' ) ) {
	function nx_folio_display_term_classes( $taxonomy ) {
		global $post;

		$classes = array();

		if ( is_object_in_taxonomy( $post->post_type, $taxonomy ) ) {
			foreach ( (array) wp_get_post_terms( $post->ID, $taxonomy ) as $term ) {
				if ( empty( $term->slug ) )
					continue;
				$classes[] = 'nx-folio-filter-' . sanitize_html_class($term->slug, $term->term_id);
			}
		}

		return $classes;
	}
}



	
	
	/* LATEST TWEET FUNCTION
	================================================== */
	if (!function_exists('nx_latest_tweet')) {
		function nx_latest_tweet($count, $twitterID) {

			
			$content = "";
			
			if ($twitterID == "") {
				return __("Please provide your Twitter username", "ispirit");
			}
			
			if (function_exists('getTweets')) {
							
				//$options = array('trim_user'=>true, 'exclude_replies'=>false, 'include_rts'=>false);
							
				//$tweets = getTweets($count, $twitterID, $options);
				
				$tweets = getTweets($twitterID, $count);
			
				if(is_array($tweets)){
							
					foreach($tweets as $tweet){
											
						$content .= '<li>';
					
					    if($tweet['text']){
					    	
					    	$content .= '<div class="tweet-text">';
					    	
					        $the_tweet = $tweet['text'];
					        /*
					        Twitter Developer Display Requirements
					        https://dev.twitter.com/terms/display-requirements
					
					        2.b. Tweet Entities within the Tweet text must be properly linked to their appropriate home on Twitter. For example:
					          i. User_mentions must link to the mentioned user's profile.
					         ii. Hashtags must link to a twitter.com search with the hashtag as the query.
					        iii. Links in Tweet text must be displayed using the display_url
					             field in the URL entities API response, and link to the original t.co url field.
					        */
					
					        // i. User_mentions must link to the mentioned user's profile.
					        if(is_array($tweet['entities']['user_mentions'])){
					            foreach($tweet['entities']['user_mentions'] as $key => $user_mention){
					                $the_tweet = preg_replace(
					                    '/@'.$user_mention['screen_name'].'/i',
					                    '<a href="http://www.twitter.com/'.$user_mention['screen_name'].'" target="_blank">@'.$user_mention['screen_name'].'</a>',
					                    $the_tweet);
					            }
					        }
					
					        // ii. Hashtags must link to a twitter.com search with the hashtag as the query.
					        if(is_array($tweet['entities']['hashtags'])){
					            foreach($tweet['entities']['hashtags'] as $key => $hashtag){
					                $the_tweet = preg_replace(
					                    '/#'.$hashtag['text'].'/i',
					                    '<a href="https://twitter.com/search?q=%23'.$hashtag['text'].'&amp;src=hash" target="_blank">#'.$hashtag['text'].'</a>',
					                    $the_tweet);
					            }
					        }
							
							/**/
					        // iii. Links in Tweet text must be displayed using the display_url
					        //      field in the URL entities API response, and link to the original t.co url field.
					        if(is_array($tweet['entities']['urls'])){
					            foreach($tweet['entities']['urls'] as $key => $link){
					                $the_tweet = preg_replace(
					                    '`'.$link['url'].'`',
					                    '<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>',
					                    $the_tweet);
					            }
					        }
							
					
					        $content .= $the_tweet;
							
							$content .= '</div>';
					
					        // 3. Tweet Actions
					        //    Reply, Retweet, and Favorite action icons must always be visible for the user to interact with the Tweet. These actions must be implemented using Web Intents or with the authenticated Twitter API.
					        //    No other social or 3rd party actions similar to Follow, Reply, Retweet and Favorite may be attached to a Tweet.
					        // 4. Tweet Timestamp
					        //    The Tweet timestamp must always be visible and include the time and date. e.g., "3:00 PM - 31 May 12".
					        // 5. Tweet Permalink
					        //    The Tweet timestamp must always be linked to the Tweet permalink.
					        
					       	$content .= '<div class="twitter_intents">'. "\n";
					        $content .= '<a class="reply" href="https://twitter.com/intent/tweet?in_reply_to='.$tweet['id_str'].'"><i class="fa fa-reply"></i></a>'. "\n";
					        $content .= '<a class="retweet" href="https://twitter.com/intent/retweet?tweet_id='.$tweet['id_str'].'"><i class="fa fa-retweet"></i></a>'. "\n";
					        $content .= '<a class="favorite" href="https://twitter.com/intent/favorite?tweet_id='.$tweet['id_str'].'"><i class="fa fa-star"></i></a>'. "\n";
					        
					        $date = strtotime($tweet['created_at']); // retrives the tweets date and time in Unix Epoch terms
					        $blogtime = current_time('U'); // retrives the current browser client date and time in Unix Epoch terms
					        $dago = human_time_diff($date, $blogtime) . ' ' . sprintf(__('ago', 'ispirit')); // calculates and outputs the time past in human readable format
							$content .= '<a class="timestamp" href="https://twitter.com/'.$twitterID.'/status/'.$tweet['id_str'].'" target="_blank">'.$dago.'</a>'. "\n";
							$content .= '</div>'. "\n";
					    } else {
					        $content .= '<a href="http://twitter.com/'.$twitterID.'" target="_blank">@'.$twitterID.'</a>';
					    }
					    $content .= '</li>';
					}
				}
				return $content;
			} else {
				return '<li><div class="tweet-text">Please install the oAuth Twitter Feed Plugin and follow the theme documentation to set it up.</div></li>';
			}	
		}
	}

/*-----------------------------------------------------------------------------------*/	
/* maintanance mode */
/*-----------------------------------------------------------------------------------*/	
if ( ! function_exists( 'nx_maintenance_mode' ) ) {
	function nx_maintenance_mode( ) {
		global  $ispirit_data;
		
		$maintenance_mode_logo = '';
		
		if( !empty($ispirit_data['logo-normal']['url']) ) {
			$maintenance_mode_logo = '<div style="text-align: center;"><img src="'.esc_url($ispirit_data['logo-normal']['url']).'" alt="'. esc_attr( get_bloginfo( 'name', 'display' ) ).'" style="max-width: 240px;" /></div>';
		} else
		{
			$maintenance_mode_logo .= '<div style="text-align: center;"><h1>'.esc_attr( get_bloginfo( 'name', 'display' ) ).'</h1></div>';
			$maintenance_mode_logo .= '<div style="text-align: center;"><h3>'.esc_attr( get_bloginfo( 'description', 'display' ) ).'</h3></div>';
		}
		
		$maintenance_mode = $ispirit_data['maintenance_mode'];

		if($maintenance_mode == 1)
		{
			if ( !current_user_can( 'edit_themes' ) || !is_user_logged_in() ) {
				wp_die($maintenance_mode_logo.'<p style="text-align:center">'.esc_attr__('We are currently in maintenance mode, please check back shortly.', 'ispirit').'</p>', get_bloginfo( 'name' ));
			}			
		}
	}
	add_action('get_header', 'nx_maintenance_mode');
}	

/*-----------------------------------------------------------------------------------*/
/* i-spirit slider */
/*-----------------------------------------------------------------------------------*/

function nx_ispirit_slider() {

   	global  $ispirit_data;

	$slide_title = $slide_desc = $slide_linktext = $slide_linkurl = $slide_image = $itrans_sliderparallax = "";
	$itrans_slideroverlay = $itrans_alignment = $itrans_transition = $itrans_duration = $itrans_height = "";
		
	$arrslidestxt = array();
	
	$upload_dir = wp_upload_dir();
	$upload_base_dir = $upload_dir['basedir'];
	$upload_base_url = $upload_dir['baseurl'];	
	
	$itrans_sliderparallax = $ispirit_data['slider-parallax'];
	$itrans_slideroverlay = $ispirit_data['slider-overlay'];
	$itrans_alignment = $ispirit_data['slider-align'];
	$itrans_transition = $ispirit_data['slider-transition'];	
	$itrans_duration = $ispirit_data['slider-speed'];
	$itrans_height = $ispirit_data['slider-height'];
	
	$th_width = 1600;
	$th_height = $itrans_height;
	
	$itrans_duration = intval($itrans_duration)*1000;
	
    for( $slideno=1; $slideno<=6; $slideno++ ){
			
			$slide_image = $strret = '';
			
			$slide_title = esc_attr($ispirit_data['slide'.$slideno.'-title']);
			$slide_desc = esc_attr($ispirit_data['slide'.$slideno.'-subtitle']);
			$slide_linktext = esc_attr($ispirit_data['slide'.$slideno.'-linktext']);
			$slide_linkurl = esc_url($ispirit_data['slide'.$slideno.'-linkurl']);
			if( !empty($ispirit_data['slide'.$slideno.'-image']) ){
				$slide_image = esc_url($ispirit_data['slide'.$slideno.'-image']['url']);
			}
			
			if( $itrans_slideroverlay == true )
			{
				$overlayclass = "overlay";
			} else
			{
				$overlayclass = "";
			}
			
			
			if( !empty($ispirit_data['slide'.$slideno.'-image']) ){
				if( file_exists( str_replace($upload_base_url,$upload_base_dir,$slide_image) ) ){
					$slide_image = aq_resize( $slide_image, $th_width, $th_height, true );
				}
			}
							
			
			if ( $slide_image ) {

				
				$strret .= '<div class="ispirit-slider-item">';
				$strret .= '<div class="ispirit-slider-box">';
				
					$strret .= '<div class="ispirit-slider-img">';
					$strret .= '<img src="'.$slide_image.'" alt="" class="blog-image" />';
					$strret .= '</div>';

				$strret .= '<div class="ispirit-slide-content"><div class="ispirit-slide-content-inner align-'.$itrans_alignment.'" style="text-align:'.$itrans_alignment.';">';
				if ( $slide_title != "" )
				{
					$strret .= '<h3 class="ispirit-slide-title">'.$slide_title.'</h3>';
				}
				if ( $slide_desc != "" ) {
					$strret .= '<div class="ispirit-slide-details"><p>'.$slide_desc.'</p></div>';
				}
				if ( $slide_linktext != "" ) {	
					$strret .= '<div class="ispirit-slide-button"><a href="'.$slide_linkurl.'" class="button">'.$slide_linktext.'</a></div>';		
				}
				$strret .= '</div></div></div></div>';
				
			}
			
			if ( $strret != '' ){
				$arrslidestxt[$slideno] = $strret;
			}
			
					
	}
	
	if( count( $arrslidestxt) > 0 ){
		echo '<div class="ispirit-slider '.$overlayclass.'" data-delay="'.$itrans_duration.'" data-parallax="'.$itrans_sliderparallax.'" data-transition="'.$itrans_transition.'">';
		foreach ( $arrslidestxt as $slidetxt ) :
			echo	$slidetxt;
		endforeach;
		echo '</div>';
	}
	/*
	else
	{
        echo '<div class="iheader front">';
        echo '    <div class="titlebar">';
        echo '        <h1>';
		
		if ($banner_text) {
			echo $banner_text;
		} 
        echo '        </h1>';
		echo ' 		  <h2>';

		echo '		</h2>';
        echo '    </div>';
        echo '</div>';
	}
	*/
}

/*-----------------------------------------------------------------------------------*/
/* itrans slider */
/*-----------------------------------------------------------------------------------*/
function nx_tnext_slider() {

		global $data;
		global $post;
		
		$nx_slide_parallax = 1;
		
		$slide_category = rwmb_meta('ispirit_posts_slider_category');
		$total_slide = esc_attr(rwmb_meta('ispirit_nx_slide_count'));
		$slide_speed = esc_attr(rwmb_meta('ispirit_nx_slide_speed'));
		
		$nx_slide_height = rwmb_meta('ispirit_nx_slide_height');
		$nx_slide_transition = rwmb_meta('ispirit_nx_slide_transition');
		$nx_slide_alignment = rwmb_meta('ispirit_nx_slide_alignment');
		$nx_slide_textbg = rwmb_meta('ispirit_nx_slide_textbg');
		$nx_slide_parallax = rwmb_meta('ispirit_nx_slide_parallax');
	
		if( empty($slide_category) || $slide_category == "all" )
		{
			$slide_category = '';
		}
		
		if( $nx_slide_parallax == 0 )
		{
			$nx_slide_parallax = 'no';
		} else {
			$nx_slide_parallax = 'yes';
		}
		
		echo do_shortcode('[nx_itrans_slider category="'.$slide_category.'" transition="'.$nx_slide_transition.'" items="'.$total_slide.'" delay="'.$slide_speed.'" parallax="'.$nx_slide_parallax.'" align="'.$nx_slide_alignment.'" textbg="'.$nx_slide_textbg.'" height="'.$nx_slide_height.'"]');

}




/*-----------------------------------------------------------------------------------*/
/* LATEST TWEET FUNCTION */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('nx_footer_social')) {
	function nx_footer_social() {
		
		global  $ispirit_data;
		
		$social['twitter'] = esc_attr($ispirit_data['twitter']);
		$social['facebook'] = esc_url($ispirit_data['facebook']);
		$social['skype'] = esc_attr($ispirit_data['skype']);
		$social['googleplus'] = esc_url($ispirit_data['googleplus']);
		$social['flickr'] = esc_url($ispirit_data['flickr']);
		$social['youtube'] = esc_url($ispirit_data['youtube']);
		$social['instagram'] = esc_attr($ispirit_data['instagram']);
		$social['pinterest'] = esc_attr($ispirit_data['pinterest']);
		$social['linkedin'] = esc_url($ispirit_data['linkedin']);
		
		$nx_footer_social = '';	
		
			$nx_footer_social .= '<div class="socialicons">';
			$nx_footer_social .= '<ul class="social">';
			foreach ( $social as $service => $value ) :
				if(isset($value) && !empty($value))
				{
					if ($value == "twitter")
					{
						$nx_footer_social .= '<li><a href="http://www.twitter.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "skype")
					{
						$nx_footer_social .= '<li><a href="skype:'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-skype"></i></a></li> ';
					} elseif ($value == "instagram")
					{
						$nx_footer_social .= '<li><a href="http://instagram.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					} elseif ($value == "pinterest")
					{
						$nx_footer_social .= '<li><a href="http://www.pinterest.com/'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';										
					} else
					{
						$nx_footer_social .= '<li><a href="'.$value.'" class="tooltip" title="'.$service.'" target="_blank"><i class="socico genericon genericon-'.$service.'"></i></a></li> ';
					}
				}
			endforeach;		
			$nx_footer_social .= '</ul>';
			$nx_footer_social .= '</div>';
			
			return $nx_footer_social;
					
	}
}


/*-----------------------------------------------------------------------------------*/
/* Get Custom Post type categories */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('nx_category_custom_post')) {
	function nx_category_custom_post( $id = false, $tcat = 'category' ) {
		
		$categories = get_the_terms( $id, $tcat );
		if ( ! $categories )
			$categories = array();
	
		$categories = array_values( $categories );
	
		foreach ( array_keys( $categories ) as $key ) {
			_make_cat_compat( $categories[$key] );
		}
	
		return apply_filters( 'get_the_categories', $categories );		
		
	}
}


// ass tgm plugin activation
require_once( NX_INCLUDES_PATH . '/class-tgm-plugin-activation.php' );	

add_action( 'tgmpa_register', 'nx_theme_register_required_plugins' );
function nx_theme_register_required_plugins() {

    /**
* Array of plugin arrays. Required keys are name and slug.
* If the source is NOT from the .org repo, then source is also required.
*/
    $plugins = array(

        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'Revolution Slider', // The plugin name.
            'slug'               => 'revslider', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/inc/plugins/revslider.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),
		
        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'TemplatesNext Shortcode', // The plugin name.
            'slug'               => 'nx-shortcode', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/inc/plugins/nx-shortcode.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),	
		
        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'WPBakery Visual Composer', // The plugin name.
            'slug'               => 'js_composer', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/inc/plugins/js_composer.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),						
		
         // This is an example of how to include a plugin from a private repo in your theme.
        array(
            'name' => 'Breadcrumb NavXT', // The plugin name.
            'slug' => 'breadcrumb-navxt', // The plugin slug (typically the folder name).
            'required' => true, // If false, the plugin is only 'recommended' instead of required.
        ),
		array(
            'name' => 'Contact Form 7', // The plugin name.
            'slug' => 'contact-form-7', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),
		array(
            'name' => 'Max Mega Menu', // The plugin name.
            'slug' => 'megamenu', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),		
		array(
            'name' => 'oAuth Twitter Feed for Developers', // The plugin name.
            'slug' => 'oauth-twitter-feed-for-developers', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),		
		array(
            'name' => 'Widget Settings Importer/Exporter', // The plugin name.
            'slug' => 'widget-settings-importexport', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),		
		array(
            'name' => 'amr shortcode any widget', // The plugin name.
            'slug' => 'amr-shortcode-any-widget', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        )				

    );

    /**
* Array of configuration settings. Amend each line as needed.
* If you want the default strings to be available under your own theme domain,
* leave the strings uncommented.
* Some of the strings are added into a sprintf, so see the comments at the
* end of each line for what each argument will be.
*/
    $config = array(
        'id' => 'tgmpa', // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '', // Default absolute path to pre-packaged plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'has_notices' => true, // Show admin notices or not.
        'dismissable' => true, // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false, // Automatically activate plugins after installation or not.
        'message' => '', // Message to output right before the plugins table.
        'strings' => array(
            'page_title' => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title' => __( 'Install Plugins', 'tgmpa' ),
            'installing' => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops' => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required' => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'tgmpa' ), // %1$s = plugin name(s).
            'notice_can_install_recommended' => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'tgmpa' ), // %1$s = plugin name(s).
            'notice_cannot_install' => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'tgmpa' ), // %1$s = plugin name(s).
            'notice_can_activate_required' => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'tgmpa' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'tgmpa' ), // %1$s = plugin name(s).
            'notice_cannot_activate' => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'tgmpa' ), // %1$s = plugin name(s).
            'notice_ask_to_update' => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'tgmpa' ), // %1$s = plugin name(s).
            'notice_cannot_update' => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'tgmpa' ), // %1$s = plugin name(s).
            'install_link' => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'tgmpa' ),
            'activate_link' => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'tgmpa' ),
            'return' => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated' => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete' => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type' => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );

}

/*
* remove Unnecessery VC notifications
*/
add_action( 'vc_before_init', 'ispirit_vcSetAsTheme' );
function ispirit_vcSetAsTheme() {
    vc_set_as_theme();
}

/**
 * If we go beyond the last page and request a page that doesn't exist,
 * force WordPress to return a 404.
 * See http://core.trac.wordpress.org/ticket/15770
 */
function ispirit_custom_paged_404_fix( ) {
	global $wp_query;

	if ( is_404() || !is_paged() || 0 != count( $wp_query->posts ) )
		return;

	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
}
add_action( 'wp', 'ispirit_custom_paged_404_fix' );