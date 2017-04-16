<?php
function aq_resize( $url, $width, $height = null, $crop = null, $single = true ) {

	//screen is 2x so double the size of images
	$width = $width * 2;
	$height = $height * 2;
		
	
	//validate inputs
	if(!$width OR !$url) return false;
	if($url == "default") {
		$url = get_template_directory_uri()."/images/placeholder-1200x800.png";
		$image = array (
			0 => $url,
			1 => '1600',
			2 => '1600'
		);
		return $image;
	}
	
	//define upload path & dir
	$upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];
	$upload_url = $upload_info['baseurl'];

	$http_prefix = "http://";
	$https_prefix = "https://";

	/* if the $url scheme differs from $upload_url scheme, make them match 
	   if the schemes differe, images don't show up. */
	if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
		$upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
	}
	elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
		$upload_url = str_replace($https_prefix,$http_prefix,$upload_url);		
	}
		
	//check if $img_url is local
	//if(strpos( $url, home_url() ) === false) return false;
	
	//define path of image
	$rel_path = str_replace( $upload_url, '', $url);
	$img_path = $upload_dir . $rel_path;
	
	//check if img path exists, and is an image indeed
	if( !file_exists($img_path) OR !getimagesize($img_path) ) return false;
	
	//get image info
	$info = pathinfo($img_path);
	$ext = $info['extension'];
	list($orig_w,$orig_h) = getimagesize($img_path);
	
	//if the image isn't big enough for 2x, put it back to 1x - philj
	if($width > $orig_w || $height > $orig_h) {
		$width = $width/2;
		$height = $height/2;
	}
	
	//get image size after cropping
	$dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
	$dst_w = $dims[4];
	$dst_h = $dims[5];
	
	//use this to check if cropped image already exists, so we can return that instead
	$suffix = "{$dst_w}x{$dst_h}";
	$dst_rel_path = str_replace( '.'.$ext, '', $rel_path);
	$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";
	
	//if orig size is smaller
	if($width >= $orig_w) {
		
		if(!$dst_h) :
			//can't resize, so return original url
			$img_url = $url;
			$dst_w = $orig_w;
			$dst_h = $orig_h;
			
		else :
			//else check if cache exists
			if(file_exists($destfilename) && getimagesize($destfilename)) {
				$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
			} 
			//else resize and return the new resized image url
			else {
				
				if(function_exists('wp_get_image_editor')) {
		
					$editor = wp_get_image_editor($img_path);
		
					if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
						return false;
		
					$resized_file = $editor->save();
		
					if(!is_wp_error($resized_file)) {
						$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path']);
						$img_url = $upload_url . $resized_rel_path;
					} else {
						return false;
					}
		
				}
			}
			
		endif;
		
	}
	//else check if cache exists
	elseif(file_exists($destfilename) && getimagesize($destfilename)) {
		$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
	} 
	//else, we resize the image and return the new resized image url
	else {

		if(function_exists('wp_get_image_editor')) {
			$editor = wp_get_image_editor($img_path);
			
			if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
				return false;

			$resized_file = $editor->save();

			if(!is_wp_error($resized_file)) {
				$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path']);
				$img_url = $upload_url . $resized_rel_path;
			} else {
				return false;
			}
		}
	}
	
	//return the output
	if($single) {
		//str return
		$image = $img_url;
	} else {
		//array return
		$image = array (
			0 => $img_url,
			1 => $dst_w,
			2 => $dst_h
		);
	}
	
	return $image;
}
?>