/*
*
*	Admin jQuery Functions
*	------------------------------------------------
*
*/

jQuery(function(jQuery) {
	
	// FIELD VARIABLES

	var $show_tnext_slider = jQuery('#ispirit_nx_slider'),
		$nx_slider_category = jQuery('#ispirit_nx_slider_category').parent().parent();
		$nx_slide_count = jQuery('#ispirit_nx_slide_count').parent().parent();
		$nx_slide_speed = jQuery('#ispirit_nx_slide_speed').parent().parent();
		$nx_slide_height = jQuery('#ispirit_nx_slide_height').parent().parent();
		
		$nx_slide_transition = jQuery('#ispirit_nx_slide_transition').parent().parent();
		$nx_slide_alignment = jQuery('#ispirit_nx_slide_alignment').parent().parent();
		$nx_slide_textbg = jQuery('#ispirit_nx_slide_textbg').parent().parent();
		$nx_slide_parallax = jQuery('#ispirit_nx_slide_parallax').parent().parent();		
		
		$rev_slider_alias = jQuery('#ispirit_rev_slider_alias').parent().parent();
		$layer_slider_id = jQuery('#ispirit_layer_slider_id').parent().parent();
		$nx_posts_slider_category = jQuery('#ispirit_posts_slider_category').parent().parent();	
		$nx_thumb_link_type = jQuery('#ispirit_thumb_link_type').parent().parent();
		$nx_other_slider = jQuery('#ispirit_other_slider').parent().parent();			
		
		$nx_post_thumb_type = jQuery('#ispirit_post_thumb_type');
		$nx_post_thumb_image = jQuery('.th-image');
		$nx_thumb_video_url = jQuery('#ispirit_thumb_video_url').parent().parent();
		$nx_thumb_slider_images = jQuery('.th-slider');
		
		$nx_thumb_link_type = jQuery('#ispirit_thumb_link_type').parent().parent();										 
		
	
	if (!$show_tnext_slider.is(':checked')) {

		$nx_slider_category.css('display', 'none');
		$nx_slide_count.css('display', 'none');
		$nx_slide_speed.css('display', 'none');
		$nx_slide_height.css('display', 'none');
		$nx_posts_slider_category.css('display', 'none');
		
		$nx_slide_transition.css('display', 'none');
		$nx_slide_alignment.css('display', 'none');
		$nx_slide_textbg.css('display', 'none');
		$nx_slide_parallax.css('display', 'none');
		
		$rev_slider_alias.css('display', 'block');
		$layer_slider_id.css('display', 'block');
		$nx_other_slider.css('display', 'block');				
	} else
	{
		$nx_slider_category.css('display', 'block');
		$nx_slide_count.css('display', 'block');
		$nx_slide_speed.css('display', 'block');
		$nx_slide_height.css('display', 'block');
		$nx_posts_slider_category.css('display', 'block');
		
		$nx_slide_transition.css('display', 'block');
		$nx_slide_alignment.css('display', 'block');
		$nx_slide_textbg.css('display', 'block');
		$nx_slide_parallax.css('display', 'block');
		
		$rev_slider_alias.css('display', 'none');
		$layer_slider_id.css('display', 'none');
		$nx_other_slider.css('display', 'none');							
	}
	
	$show_tnext_slider.change(function() {
		if ($show_tnext_slider.is(':checked')) {
			
			$nx_slider_category.css('display', 'block');
			$nx_slide_count.css('display', 'block');
			$nx_slide_speed.css('display', 'block');
			$nx_slide_height.css('display', 'block');
			$nx_posts_slider_category.css('display', 'block');
			
			$nx_slide_transition.css('display', 'block');
			$nx_slide_alignment.css('display', 'block');
			$nx_slide_textbg.css('display', 'block');
			$nx_slide_parallax.css('display', 'block');			
			
			$rev_slider_alias.css('display', 'none');
			$layer_slider_id.css('display', 'none');
			$nx_other_slider.css('display', 'none');
			
		} else {
			$nx_slider_category.css('display', 'none');
			$nx_slide_count.css('display', 'none');
			$nx_slide_speed.css('display', 'none');
			$nx_slide_height.css('display', 'none');
			$nx_posts_slider_category.css('display', 'none');
		
			$nx_slide_transition.css('display', 'none');
			$nx_slide_alignment.css('display', 'none');
			$nx_slide_textbg.css('display', 'none');
			$nx_slide_parallax.css('display', 'none');
					
			$rev_slider_alias.css('display', 'block');
			$layer_slider_id.css('display', 'block');
			$nx_other_slider.css('display', 'block');						
		}
	});
	
	
	/*
	Thumb type 
	*/


	if ($nx_post_thumb_type.val() == "0") {

		$nx_post_thumb_image.css('display', 'none');
		$nx_thumb_video_url.css('display', 'none');
		$nx_thumb_slider_images.css('display', 'none');
		$nx_thumb_link_type.css('display', 'none');
				
	} else if ($nx_post_thumb_type.val() == "1")
	{
		$nx_post_thumb_image.css('display', 'block');
		$nx_thumb_video_url.css('display', 'none');
		$nx_thumb_slider_images.css('display', 'none');	
		$nx_thumb_link_type.css('display', 'none');	
	} else if ($nx_post_thumb_type.val() == "2")
	{
		$nx_post_thumb_image.css('display', 'none');
		$nx_thumb_video_url.css('display', 'block');
		$nx_thumb_slider_images.css('display', 'none');	
		$nx_thumb_link_type.css('display', 'none');	
	} else if ($nx_post_thumb_type.val() == "3")
	{
		$nx_post_thumb_image.css('display', 'none');
		$nx_thumb_video_url.css('display', 'none');
		$nx_thumb_slider_images.css('display', 'block');
		$nx_thumb_link_type.css('display', 'block');		
	} else
	{
		console.log("No thumb type selected");
	}
	
	$nx_post_thumb_type.change(function() {
		if ($nx_post_thumb_type.val() == "0") {
			
			$nx_post_thumb_image.css('display', 'none');
			$nx_thumb_video_url.css('display', 'none');
			$nx_thumb_slider_images.css('display', 'none');
			$nx_thumb_link_type.css('display', 'none');			
		} else if ($nx_post_thumb_type.val() == "1")
		{
			$nx_post_thumb_image.css('display', 'block');
			$nx_thumb_video_url.css('display', 'none');
			$nx_thumb_slider_images.css('display', 'none');	
			$nx_thumb_link_type.css('display', 'none');		
			
		} else if ($nx_post_thumb_type.val() == "2")
		{
			$nx_post_thumb_image.css('display', 'none');
			$nx_thumb_video_url.css('display', 'block');
			$nx_thumb_slider_images.css('display', 'none');	
			$nx_thumb_link_type.css('display', 'none');		
			
		} else if ($nx_post_thumb_type.val() == "3")
		{
			$nx_post_thumb_image.css('display', 'none');
			$nx_thumb_video_url.css('display', 'none');
			$nx_thumb_slider_images.css('display', 'block');
			$nx_thumb_link_type.css('display', 'block');			
			
		}
	});	

});

jQuery(document).ready(function($) {
	
	/* redux modifications */
	
	if( $('.redux-section-field').length > 0 )
	{	
		$('#section-slide1-start h3').addClass( 'nxrdx-minus' );
		$('#section-slide1-start h3').parent().next('.form-table').addClass('show-slide');
		
		$('.redux-section-field h3').click(function(event) {
			$('.redux-section-field h3').not(this).removeClass( 'nxrdx-minus' );			
			$(this).toggleClass( 'nxrdx-minus' );
			
			$(this).parent().next('.form-table').toggleClass('show-slide');
			$('.redux-section-field h3').not(this).parent().next('.form-table').removeClass('show-slide');
		});		
	}
});	