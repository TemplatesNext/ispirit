<?php
function get_pixelratio(){
    if( isset($_COOKIE["pixel_ratio"]) ){
        $pixel_ratio = $_COOKIE["pixel_ratio"];
        if( $pixel_ratio >= 2 ){
           // echo "Is HiRes Device";
        	
			/**
			* Include AQ Resizer
			*/
			require_once( dirname(__FILE__) . '/aq_resizer-2x.php' );

		}else{
            //echo "Is NormalRes Device";
			
			/**
			* Include AQ Resizer
			*/
			require_once( dirname(__FILE__) . '/aq_resizer-1x.php' );
        }
    }else{
		require_once( dirname(__FILE__) . '/aq_resizer-1x.php' );
?>
    <script>
        writeCookie();
        function writeCookie()
        {
            the_cookie = document.cookie;
            if( the_cookie ){
                if( window.devicePixelRatio >= 2 ){
                    the_cookie = "pixel_ratio="+window.devicePixelRatio+";"+the_cookie;
                    document.cookie = the_cookie;
                    //location = '<?php $_SERVER['PHP_SELF'] ?>';
                }
            }
        }
    </script>
<?php
    }//isset($_COOKIE["pixel_ratio"]) 
}//get_pixelratio
add_action( 'wp_enqueue_scripts', 'get_pixelratio' );
?>
