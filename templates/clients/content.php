<?php 
	
	$client_link = esc_url(rwmb_meta('ispirit_clients_link', $post->ID));

	if( $atts['columns'] == 1 )
	{
		$th_width = 1200;
		$th_height = 800;		
	} else if( $atts['columns'] == 2 )
	{
		$th_width = 600;
		$th_height = 600;			
	} else if( $atts['columns'] == 3 )
	{
		$th_width = 600;
		$th_height = 600;				
	} else if( $atts['columns'] == 4 )
	{
		$th_width = 600;
		$th_height = 600;		
	}

	                
	if( has_post_thumbnail() )
	{
		$thumb_image_id = get_post_thumbnail_id();
		$thumb_img_url = wp_get_attachment_url( $thumb_image_id,'full' ); //get full URL to image (use "large" or "medium" if the images too big)
		$thumb_resized = aq_resize( $thumb_img_url, $th_width, $th_height, true ); //resize & crop the image
		
		$thumb_full_url = $thumb_img_url;
	}
						
?>
	<div id="post-<?php the_ID(); ?>" class="clients">
       	<?php
              if( has_post_thumbnail() && ! post_password_required() )
              {
					echo '<div class="clients-inner"><a href="'.$client_link.'" class="client-link"><img src="'.$thumb_resized.'" class="clients-logo" alt="'.the_title('','',false).'" ></a></div>';
              }     
        ?>
	</div>
