<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


add_filter( 'rwmb_meta_boxes', 'ispirit_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function ispirit_register_meta_boxes( $meta_boxes )
{
	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'ispirit_';
	
	$ispirit_template_url = get_template_directory_uri();

	// 1st meta box
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'heading',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Page Heading Options', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post', 'page', 'portfolio', 'team', 'product' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			// Hide Title
			array(
				'name' => __( 'Hide Title', 'ispirit' ),
				'id'   => "{$prefix}hidetitle",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'class' => 'hide-ttl',
			),		
			// Custom Title
			array(
				// Field name - Will be used as label
				'name'  => __( 'Custom title', 'ispirit' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}customtitle",
				// Field description (optional)
				'desc'  => __( 'Enter custom title for the page', 'ispirit' ),
				'type'  => 'text',
				// Default value (optional)
				'std'   => __( '', 'ispirit' ),
				// CLONES: Add to make the field cloneable (i.e. have multiple value)
				//'clone' => true,
				'class' => 'cust-ttl',
			),
			
			// hide breadcrum
			array(
				'name' => __( 'Hide breadcrumb', 'ispirit' ),
				'id'   => "{$prefix}hide_breadcrumb",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
			),
			
			// Header title Color
			array(
				'name' => __('Titlebar Text Color', 'nx-admin'),
				'id'   => "{$prefix}header_text_color",
				'type' => 'select',
				'options' => array(
					'1'		=> __('Dark', 'nx-admin'),
					'2'		=> __('Light', 'nx-admin')
				),
				'multiple' => false,
				'std'  => '2',
			),
			// Header title Text alignment
			array(
				'name' => __('Titlebar Text Alignment', 'nx-admin'),
				'id'   => "{$prefix}header_text_alignment",
				'type' => 'select',
				'options' => array(
					'left'		=> __('Left', 'nx-admin'),
					'center'	=> __('Center', 'nx-admin')
				),
				'multiple' => false,
				'std'  => 'left',
			),			
			
			// Header title Background Color
			array(
				'name' => __( 'Title Background Color', 'nx-admin' ),
				'id'   => "{$prefix}bg_color",
				'type' => 'color',
				'desc' => __('Clear the value if you choose image or pattern as background', 'nx-admin'),
				'std'  => nx_default_color ()
			),			
			
			//page heading background
			array(
				'name'     => __( 'Title background', 'ispirit' ),
				'id'       => "{$prefix}title_bg",				
				'type'     => 'image_select',

				// Array of 'value' => 'Image Source' pairs
				'options'  => array(
					'pat1'  => $ispirit_template_url . '/images/patterns/patt1.png',
					'pat2' => $ispirit_template_url . '/images/patterns/patt2.png',
					'pat3'  => $ispirit_template_url . '/images/patterns/patt3.png',
					'pat4' => $ispirit_template_url . '/images/patterns/patt4.png',
					'pat5'  => $ispirit_template_url . '/images/patterns/patt5.png',
					'pat6' => $ispirit_template_url . '/images/patterns/patt6.png',
					'pat7'  => $ispirit_template_url . '/images/patterns/patt7.png',										
				),

				// Allow to select multiple values? Default is false
				// 'multiple' => true,
			),
			
			// optional background image
			array(
				'name'             => __( 'Custom background image', 'nx-admin' ),
				'id'               => "{$prefix}custom_title_bg",
				/*
				'type'             => 'plupload_image',
				'max_file_uploads' => 1,
				*/
				'type'             => 'file_advanced',
				'max_file_uploads' => 1,				
			),
			
			// SLIDER TYPE
			array(
				'name' => __('Custom Background Image Size', 'nx-admin'),
				'id'   => "{$prefix}bg_image_type",
				'type' => 'select',
				'options' => array(
					'0'		=> __('Repeat', 'nx-admin'),
					'1'	=> __('Cover', 'nx-admin')
				),
				'multiple' => false,
				'std'  => '0',
				'desc' => __('For fullscreen images, choose Cover. For repeating patterns, choose Repeat.', 'nx-admin'),
			),

			// Background Attachment
			array(
				'name' => __( 'Fixed Background Attachment', 'ispirit' ),
				'id'   => "{$prefix}bg_attachment",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
			),						
											

		)
	);
	
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'portfoliometa',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Portfolio Meta', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'portfolio' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			// Side bar

			// ITEM DETAILS OPTIONS SECTION
			array(
				'type' => 'heading',
				'name' => __( 'Portfolio Additinal Details', 'nx-admin' ),
				'id'   => 'fake_id_pf1', // Not used but needed for plugin
			),
			// Slide duration
			array(
				'name'  => __( 'Subtitle', 'nx-admin' ),
				'id'    => "{$prefix}portfolio_subtitle",
				'desc'  => __( 'Enter a subtitle for use within the portfolio item index (optional).', 'nx-admin' ),				
				'type'  => 'text',
			),
			
			array(
				'name'  => __( 'Portfolio Link(External)', 'nx-admin' ),
				'id'    => "{$prefix}portfolio_url",
				'desc'  => __( 'Enter an external link for the item (optional) (NOTE: INCLUDE HTTP://).', 'nx-admin' ),				
				'type'  => 'text',
			),
			// ITEM DETAILS OPTIONS SECTION
			array(
				'type' => 'heading',
				'name' => __( 'Portfolio Layout Options', 'nx-admin' ),
				'id'   => 'fake_id_pf2', // Not used but needed for plugin
			),
			//Media display option
			array(
				'name' => __('Portfolio Layout', 'nx-admin'),
				'id'   => "{$prefix}folio_disply_type",
				'type' => 'select',
				'options' => array(
					'0'	=> __('Default Single Column', 'nx-admin'),
					'1'	=> __('Two Column Side By Side', 'nx-admin'),
				),
				'multiple' => false,
				'std'  => '0',
				'desc' => __('Choose how you would like to have your portfolio details page', 'nx-admin'),
			),
			// Show Social Share
			array(
				'name' => __( 'Show Social Share', 'ispirit' ),
				'id'   => "{$prefix}nx_show_social",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0
			),
			// Show Related Posts
			array(
				'name' => __( 'Show Related Posts', 'ispirit' ),
				'id'   => "{$prefix}nx_show_related",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0
			),																	

		)
	);	
	
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'slidersetup',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Slider Options', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post', 'page', 'portfolio', 'product' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			// Side bar

			array(
				'name' => __( 'ispirit Theme Slider', 'ispirit' ),
				'id'   => "{$prefix}ispirit_slider",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'class' => 'group-slider',
				'desc' => __('Turn ON default theme Slider', 'nx-admin'),
			),
			array(
				'name' => __( 'itrans Slider', 'ispirit' ),
				'id'   => "{$prefix}nx_slider",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'class' => 'group-slider',
				'desc' => __('Turn ON itrans Slider, Make sure you have slides with featured image in itrans Slider', 'nx-admin'),
			),
			
			// t-next SLIDER TYPE
			array(
				'name' => __('itrans Slider Category', 'nx-admin'),
				'id'   => "{$prefix}posts_slider_category",
				'type' => 'select',
				'desc' => __('Select the category for which the t-next Slider should show posts from.', 'nx-admin'),
				//'options' => nx_get_category_list_key_array('category'),
				'options' => nx_get_category_list_itrans(),
				'std' => '',
			),			
						
			// Slide Count
			array(
				'name'  => __( 'Slide Count', 'nx-admin' ),
				'id'    => "{$prefix}nx_slide_count",
				'type'  => 'text',
				'std'   => __( '10', 'nx-admin' ),
			),
			// Slide duration
			array(
				'name'  => __( 'Slide Speed (in seconds)', 'nx-admin' ),
				'id'    => "{$prefix}nx_slide_speed",
				'type'  => 'text',
				'std'   => __( '8', 'nx-admin' ),
			),
			
			// Slide duration
			array(
				'name'  => __( 'Slide Height in PX', 'nx-admin' ),
				'id'    => "{$prefix}nx_slide_height",
				'type'  => 'text',
				'std'   => __( '520', 'nx-admin' ),
			),			
			
			// Slide Transition
			array(
				'name' => __('Slide Transition (animation)', 'nx-admin'),
				'id'   => "{$prefix}nx_slide_transition",
				'type' => 'select',
				'options' => array(
					'slide'	=> __('Slide', 'nx-admin'),
					'fade'	=> __('Fade', 'nx-admin'),
					'backSlide'	=> __('Back Slide', 'nx-admin'),
					'goDown'	=> __('Go Down', 'nx-admin'),
					'fadeUp'	=> __('Fade Up', 'nx-admin')
				),
				'multiple' => false,
				'std'  => '0',
				'desc' => __('Choose an animation type for the slides', 'nx-admin'),
			),
			
			// Slide Text Alignment
			array(
				'name' => __('Content Alignment', 'nx-admin'),
				'id'   => "{$prefix}nx_slide_alignment",
				'type' => 'select',
				'options' => array(
					'left'	=> __('Left', 'nx-admin'),
					'center'	=> __('Center', 'nx-admin'),
					'right'	=> __('Right', 'nx-admin')
				),
				'multiple' => false,
				'std'  => 'left',
				'desc' => __('Choose an alignment for texts in the slides', 'nx-admin'),
			),
			
			// Slide Text Alignment
			array(
				'name' => __('Text Background', 'nx-admin'),
				'id'   => "{$prefix}nx_slide_textbg",
				'type' => 'select',
				'options' => array(
					'shadow'	=> __('Shadowed', 'nx-admin'),				
					'transp'	=> __('Semi Transparent', 'nx-admin'),
					'noshad'	=> __('None', 'nx-admin')
				),
				'multiple' => false,
				'std'  => '0',
				'desc' => __('Choose a background style for the slide text', 'nx-admin'),
			),							
			
			// Slide parallax
			array(
				'name' => __( 'Parallax', 'ispirit' ),
				'id'   => "{$prefix}nx_slide_parallax",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 1,
				'desc' => __('Turn on/off parallax effect', 'nx-admin'),
			),
			
			array(
				'name' => __('Revolution slider', 'nx-admin'),
				//'id'   => "{$prefix}nx_rev_slider_alias",
				'id'    => "{$prefix}rev_slider_alias",
				'type' => 'select',
				'desc' => __('Select a Revolution slider for this page', 'nx-admin'),
				'options' => nx_revslider_list(),
				'std' => '',
			),
			// Slide duration
			array(
				'name'  => __( 'Other Slider Shortcode', 'nx-admin' ),
				'id'    => "{$prefix}other_slider",
				'desc'  => __( 'Enter other 3rd party slider shortcode. NOTE: If you have the t-next Slider or Revolution Slider enabled above, then this will be ignored.', 'nx-admin' ),				
				'type'  => 'text',
			),				
			
			/*
			// Slide duration
			array(
				'name'  => __( 'LayerSlider ID', 'nx-admin' ),
				'id'    => "{$prefix}layer_slider_id",
				'desc'  => __( 'Enter the LayerSlider ID for the slider that you want to show. NOTE: If you have the t-next Slider enabled above, then this will be ignored.', 'nx-admin' ),				
				'type'  => 'text',
			),			
				
			array(
				'name' => __('Slider check 2', 'nx-admin'),
				'id'   => "{$prefix}nx_layer_slider_id",
				'type' => 'select',
				'desc' => __('Select Layer Slider', 'nx-admin'),
				'options' => nx_layerslider_list(),
				'std' => '',
			),
			*/													

		)
	);
	
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'postthumb',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Thumbnails Options', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post', 'portfolio' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
		
			// Thumbnail Type
			array(
				'name' => __('Thumbnail Type', 'nx-admin'),
				'id'   => "{$prefix}post_thumb_type",
				'type' => 'select',
				'options' => array(
					'0'	=> __('None', 'nx-admin'),
					'1'	=> __('Image', 'nx-admin'),
					'2'	=> __('Video', 'nx-admin'),
					'3'	=> __('Slider', 'nx-admin')
				),
				'multiple' => false,
				'std'  => '0',
				'desc' => __('Choose what will be used for the item thumbnail. Keep it none to use default Featured Image as post thumb', 'nx-admin'),
			),
			
			// Thumbnail Image Upload
			array(
				'name'             => __( 'Thumbnail image', 'nx-admin' ),
				'id'               => "{$prefix}post_thumb_image",
				//'type'             => 'plupload_image',
				'type'             => 'file_advanced',
				'max_file_uploads' => 1,
				'class' => 'th-image',				
			),
			
			// Thumbnail as video
			array(
				'name'  => __( 'Thumbnail Video URL', 'nx-admin' ),
				'id'    => "{$prefix}thumb_video_url",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter Youtube.com or vimeo.com video url', 'nx-admin'),				
				
			),
			
			// Thumbnail as slider image upload
			array(
				'name'             => __( 'Thumbnail Slider images', 'nx-admin' ),
				'id'               => "{$prefix}thumb_slider_images",
				//'type'             => 'plupload_image',
				'type'             => 'file_advanced',
				'max_file_uploads' => 6,
				'class' => 'th-slider',
				'desc' => __('Upload multiple images for thumbnail slider', 'nx-admin'),
			),					
			
			// Thumbnail Link
			array(
				'name' => __('Thumbnail Link Type For Slider Thumbs', 'nx-admin'),
				'id'   => "{$prefix}thumb_link_type",
				'type' => 'select',
				'options' => array(
					'0'	=> __('Link to post', 'nx-admin'),
					'1'	=> __('Lightbox to the thumbnail image', 'nx-admin'),
				),
				'multiple' => false,
				'std'  => '0',
				'desc' => __('Choose what will be used for the item thumbnail.', 'nx-admin'),
			),			
			
			// Hide Thumbnails on single pages
			array(
				'name' => __( 'Hide Tumbnail/Fetured Image/slider', 'ispirit' ),
				'id'   => "{$prefix}hide_thumb",
				'type' => 'checkbox',
				'std'  => 0,
				'desc' => __('Hide Tumbnails/Featured Image on this single page keeping the thumbnails on listing/blog posts', 'nx-admin'),
			),						


		)
	);	


	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'otherpostoptions',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Other post Options', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
		
			// Show Author Bio
			array(
				'name' => __( 'Show Author Bio', 'ispirit' ),
				'id'   => "{$prefix}nx_show_bio",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0
			),
			
			// Show Related Posts
			array(
				'name' => __( 'Show Related Posts', 'ispirit' ),
				'id'   => "{$prefix}nx_show_related",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0
			),	
			
			// Show Social Share
			array(
				'name' => __( 'Show Social Share', 'ispirit' ),
				'id'   => "{$prefix}nx_show_social",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0
			),							

		)
		
	);	
		
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'sidebarsetup',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Sidebar Options', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post', 'page', 'product' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			// Side bar
			array(
				'name'     => __( 'Side bar Setting', 'ispirit' ),
				'id'       => "{$prefix}sidebar",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'0' => __( 'No Sidebar', 'ispirit' ),
					'1' => __( 'Right Sidebar', 'ispirit' ),
					'2' => __( 'Left Sidebar', 'ispirit' ),					
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '0',
				//'placeholder' => __( 'Select sidebar placement', 'ispirit' ),
			),	

		)
	);
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'itrans-slider',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'itrans Slide Meta', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'itrans-slider' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(

			// name
			array(
				'name'  => __( 'Slide Button Text', 'nx-admin' ),
				'id'    => "{$prefix}slide_link_text",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the slide link button text. Keep the field empty, if you do not have a link.', 'nx-admin'),
			),

			// designation
			array(
				'name'  => __( 'Lide Link URL', 'nx-admin' ),
				'id'    => "{$prefix}slide_link_url",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the slide link url. Keep the field empty, if you do not have a link.', 'nx-admin'),
			),
					
		)
	);		
	
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'teammember',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Team Member Details', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'team' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(

			// Designation
			array(
				'name'  => __( 'Position/Designation', 'nx-admin' ),
				'id'    => "{$prefix}designation",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s position within the team.', 'nx-admin'),
			),

			// Email
			array(
				'name'  => __( 'Email Address', 'nx-admin' ),
				'id'    => "{$prefix}team_email",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s Email Id.', 'nx-admin'),
			),
			// Phone
			array(
				'name'  => __( 'Phone Number', 'nx-admin' ),
				'id'    => "{$prefix}team_phone",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s Phone Number.', 'nx-admin'),
			),
			// Twitter
			array(
				'name'  => __( 'Twitter', 'nx-admin' ),
				'id'    => "{$prefix}team_twitter",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s Twitter URL.', 'nx-admin'),
			),
			
			// Facebook
			array(
				'name'  => __( 'Facebook', 'nx-admin' ),
				'id'    => "{$prefix}team_facebook",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s Facebook URL.', 'nx-admin'),
			),
			// Google+
			array(
				'name'  => __( 'Google+', 'nx-admin' ),
				'id'    => "{$prefix}team_gplus",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s Google+ URL.', 'nx-admin'),
			),
			// Skype
			array(
				'name'  => __( 'Skype', 'nx-admin' ),
				'id'    => "{$prefix}team_skype",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s Skype user name.', 'nx-admin'),
			),	
			// Linkedin
			array(
				'name'  => __( 'Linkedin', 'nx-admin' ),
				'id'    => "{$prefix}team_linkedin",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the team member\'s Linkedin user URL.', 'nx-admin'),
			),									
						
		)
	);	
	


	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'clientspages',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Clients Details', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'clients' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(

			// Designation
			array(
				'name'  => __( 'Clients URL', 'nx-admin' ),
				'id'    => "{$prefix}clients_link",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the clients website URL.', 'nx-admin'),
			)
						
		)
	);		
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'testimonialmeta',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Testimonial Meta', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'testimonials' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(

			// name
			array(
				'name'  => __( 'Testimonial Cite', 'nx-admin' ),
				'id'    => "{$prefix}testi_name",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the testimonial cite name.', 'nx-admin'),
			),

			// designation
			array(
				'name'  => __( 'Testimonial Cite Designation/position', 'nx-admin' ),
				'id'    => "{$prefix}testi_desig",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the cite designation or position', 'nx-admin'),
			),
			// company name
			array(
				'name'  => __( 'Company name', 'nx-admin' ),
				'id'    => "{$prefix}testi_company",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter the cite company name', 'nx-admin'),
			),

		
						
		)
	);	
	

	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'productmeta',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Product Meta', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'product' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(

			// Show Social Share
			array(
				'name' => __( 'Show Social Share', 'ispirit' ),
				'id'   => "{$prefix}nx_prod_social",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0
			),	

		
						
		)
	);				



	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'miscellaneous',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Miscellaneous Meta', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post', 'page', 'portfolio', 'team', 'product' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'low',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
		
			// Show Alternate main navigation
			array(
				'name' => __( 'Show Alternate Main Navigation', 'ispirit' ),
				'id'   => "{$prefix}alt_navigation",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Turn on the alternate main navigation', 'nx-admin'),
			),
			
			// Show Alternate main navigation
			array(
				'name' => __( 'Hide Header/Top Navigation', 'ispirit' ),
				'id'   => "{$prefix}hide_header",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Hide header along with the topbar', 'nx-admin'),
			),					

			// Show Foooter widget area
			array(
				'name' => __( 'Hide Footer Widget Area', 'ispirit' ),
				'id'   => "{$prefix}footer_widgets",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Hide footer widgets area', 'nx-admin'),
			),
			
			// Show Default Header style
			array(
				'name' => __( 'Show Default Header', 'ispirit' ),
				'id'   => "{$prefix}default_header",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('This will activate theme default header in perticular post/page.', 'nx-admin'),
			),
			
			// Show Social Share
			array(
				'name' => __( 'Show Top Bar', 'ispirit' ),
				'id'   => "{$prefix}page_topbar",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('This will activate the topbar in perticular post/page.', 'nx-admin'),
			),	
			
			// Remove top and bottom page padding/margin
			array(
				'name' => __( 'Remove Top and Bottom Padding/Margin', 'ispirit' ),
				'id'   => "{$prefix}page_nopad",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Remove the spaces/padding from top and bottom of the page/post', 'nx-admin'),
			),							
			
			// Custom page Header
			array(
				'name'     => __( 'Custom Page Header', 'ispirit' ),
				'id'       => "{$prefix}page_header",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'0' => __( 'Global', 'ispirit' ),
					'1' => __( 'Default', 'ispirit' ),
					'2' => __( 'Transparent', 'ispirit' ),	
					'3' => __( 'Centered', 'ispirit' ),
					'4' => __( 'Left', 'ispirit' ),
					'5' => __( 'i-MAX', 'ispirit' ),										
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '0',
				'desc' => __('Set a custom header. Change the header style settings in i-spirit options to use the transparent header.', 'nx-admin'),
			),	
						
			// additional page class			
			array(
				'name'  => __( 'Additional Page Class', 'nx-admin' ),
				'id'    => "{$prefix}page_class",
				'type'  => 'text',
				'std'   => __( '', 'nx-admin' ),
				'desc' => __('Enter an additional page class, will be added to body. "hide-page-header" for no header, "boxed" for boxed page for wide layout.', 'nx-admin'),
			),			

		
						
		)
	);				



	return $meta_boxes;
}


//list terms in a given taxonomy (useful as a widget for twentyten)
function nx_get_category_list_itrans() {
	
	$taxonomy = 'itrans-slider-category';
	$get_category = get_categories( array( 'taxonomy' => $taxonomy	));	
	$category_list = array( 'all' => 'Select Category');

	foreach( $get_category as $category ){
		if (isset($category->slug)) {
			$category_list[$category->slug] = $category->cat_name;
		}
	}

	return $category_list;
}


function nx_get_category_list_key_array($category_name) {
			
	$get_category = get_categories( array( 'taxonomy' => $category_name	));
	$category_list = array( 'all' => 'Select Category');
		
	foreach( $get_category as $category ){
		if (isset($category->slug)) {
			$category_list[$category->slug] = $category->cat_name;
		}
	}
		
	return $category_list;
}	


function nx_revslider_list ()
{
	global $wpdb;
	$revsliders[0] = 'Select a slider';
	if(function_exists('rev_slider_shortcode')) {
		$get_sliders = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_sliders');
		if($get_sliders) {
			foreach($get_sliders as $slider) {
				$revsliders[$slider->alias] = $slider->title;
			}
		}
	}
	return $revsliders;

}

function nx_layerslider_list ()
{
	global $wpdb;
	$slides_array[0] = 'Select a slider';
	// Table name
	$table_name = $wpdb->prefix . "layerslider";
	
	if(class_exists('RevSliderFunctions')) {
	// Get sliders
		$sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC" );
		
		if(!empty($sliders)):
			foreach($sliders as $key => $item):
				$slides[$item->id] = '#'.$item->id." - ".$item->name;
			endforeach;
		endif;		

	}
	
	return $slides;
}


function nx_layerslider_list2 ()
{
	global $wpdb;
	$slides_array[0] = 'Select a slider';
	// Table name
	$table_name = $wpdb->prefix . "layerslider";
	
	// Get sliders
	$sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC" );
	
	if(!empty($sliders)):
		foreach($sliders as $key => $item):
			$slides[$item->id] = '';
		endforeach;
	endif;
	
	if(isset($slides) && $slides){
		foreach($slides as $key => $val){
			$slides_array[$key] = 'LayerSlider #'.($key);
		}
	}
	
	return $slides_array;
}

function nx_default_color ()
{
	global  $ispirit_data;
	
	if (!empty($ispirit_data['primary-color']))
	{
		$primary_color = $ispirit_data['primary-color'];
	}
	
	return $primary_color;
}