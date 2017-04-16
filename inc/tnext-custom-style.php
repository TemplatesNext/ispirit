<?php
	
	/*
	*
	*	nx Theme Functions
	*	------------------------------------------------
	*	nx Framework v 1.0
	*
	*	nx_custom_styles()
	*	nx_custom_script()
	*	nx_go_to_top()		
	*
	*/

 	/* CUSTOM CSS OUTPUT
 	================================================== */
 	if (!function_exists('nx_custom_styles')) { 
		function nx_custom_styles() {
			
			global  $ispirit_data;
			$custom_css = "";
			$body_font_size = "13";
			$body_line_height = "24";
			$menu_font_size = "13";
			$primary_color = "#77be32";


			$primary_color = $ispirit_data['primary-color'];
			$custom_css = $ispirit_data['custom_css'];
			$body_font_size = $ispirit_data['body-font-size'];
			$body_line_height = $ispirit_data['body-line-height'];
			$menu_font_size = $ispirit_data['menu-font-size'];
			

			echo '<style type="text/css">'. "\n";
			
			echo 'body{font-size: '. $body_font_size .'px;line-height: '. $body_line_height .'px;}';
			
			echo '.nav-container > ul li a {font-size: '. $menu_font_size .'px;}';
			
			echo '.site-main .nx-posts .nx-entry-thumbnail .nx-blog-icons a, .ispirit-slider, .fixedwoobar .woocombar {background-color: '. $primary_color .';}';
			
			echo 'a.button,a.button:visited,button,input[type="submit"],input[type="button"],input[type="reset"], .ispirit-slide-content-inner h3:before {background-color: '. $primary_color .';}';

			echo '.ibutton:hover,button:hover,button:focus,a.button:hover,a.button:focus,input[type="submit"]:hover,input[type="button"]:hover,input[type="reset"]:hover,input[type="submit"]:focus,input[type="button"]:focus,input[type="reset"]:focus {background-color: #333;}';

			echo 'button:active,.ibutton:active,a.button:active,input[type="submit"]:active,input[type="button"]:active,input[type="reset"]:active {border: 1px solid '. $primary_color .';}';

			echo '.ibutton,.ibutton:visited,.entry-content .ibutton,.entry-content .ibutton:visited {color: '. $primary_color .'; border: 1px solid '. $primary_color .';}';

			echo '.ibutton:hover,.ibutton:active,.entry-content .ibutton:hover,.entry-content .ibutton:active {border: 1px solid '. $primary_color .';background-color: '. $primary_color .';text-decoration: none;color:#FFF;}';

			echo '.nav-container > ul li a {color: #141412;}';

			echo '.lavalamp-object {border-bottom: 2px solid '. $primary_color .';}';

			echo '.nav-container > ul li:hover > a,.nav-container > ul li a:hover {color: '. $primary_color .';}';

			echo '.nav-container > ul .sub-menu,.nav-container > ul .children {border-bottom: 2px solid '. $primary_color .';}';

			echo '.nav-container > ul ul a:hover {background-color: '. $primary_color .';color: #FFFFFF;}';

			echo '.nav-container > ul .current_page_item > a,.nav-container > ul .current_page_ancestor > a,.nav-container > ul .current-menu-item > a,.nav-container > ul .current-menu-ancestor > a {color: '. $primary_color .';}';

			echo '.site-main .iconbox .icon-wrap i { color: '. $primary_color .';	box-shadow: 0 0 0 2px '. $primary_color .';}';

			echo '.site-main .iconbox:hover .iconbox-content-wrap h3 {color: '. $primary_color .';	}';

			echo '.site-main .icon-wrap i:after {background: '. $primary_color .';}';

			echo '.site-main  .icon-wrap i {color: '. $primary_color .';}';

			echo '.site-main  .standard-arrow li i {color: '. $primary_color .';}';

			echo '.site-main  .folio-box .folio-link,.social-bar.tb-reversed, .classic-menu .menu-toggle {	background-color: '. $primary_color .';}';

			//echo '.rev_slider div.tp-caption a.ibutton {color: '. $primary_color .';}';

			//echo '.rev_slider div.tp-caption a.ibutton:hover {color: #fff;}';
			
			echo '.rev_slider div.tp-caption a.ibutton,.rev_slider div.tp-caption a.ibutton:visited {color: '. $primary_color .';}';

			echo '.rev_slider div.tp-caption a.ibutton:hover {background-color: '. $primary_color .'; color: #FFF; }';

			echo '.entry-content a:hover,.comment-content a:hover {color: '. $primary_color .';}';

			echo '.sp-posts .entry-thumbnail .sp-blog-icons a {background-color: '. $primary_color .'; color: #ffffff;}';

			echo '.site-main .nx-folio.nx-folio-layout-1 .portfolio .nx-post-content .folio-content-wrap {background-color: '. $primary_color .';}';

			echo '.site-main .nx-folio .portfolio .folioico a,.site-main .nx-folio .portfolio .folioico a:hover {background-color: '. $primary_color .';	color: #FFF;}';

			echo '.site-main .paging-navigation span.current,.site-main .paging-navigation a:hover,.site-main nav.folio-filtering input:hover,.site-main nav.folio-filtering input.is-checked {	background-color: '. $primary_color .';	color: #FFF;}';

			echo '.site-main .nx-folio.nx-folio-layout-2 .foliocat {color: '. $primary_color .';}';

			echo '@-webkit-keyframes mymove2 {0% {background-color: #000000;} 50% {background-color: #ffffff;} 100% {background-color: '. $primary_color .';}}';

			echo '@keyframes mymove2 {0% {background-color: #000000;}50% {background-color: #ffffff;}100% {background-color: '. $primary_color .';}}';

			echo '.nx-team .team .nx-post-border:hover div.nx-post-content {	background-color: '. $primary_color .';}';

			echo '.nx-team div.nx-post-content .team-social ul li a:hover {	color: '. $primary_color .';}';

			echo '.woocombar-wrap {	background-color: '. $primary_color .';}';

			echo '.woocommerce #content input.button,.woocommerce #respond input#submit, .woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce-page #content input.button, .woocommerce-page #respond input#submit,.woocommerce-page a.button,.woocommerce-page button.button,.woocommerce-page input.button {	background-color: '. $primary_color .';	border: 1px solid '. $primary_color .';}';

			echo '.woocommerce #content input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover,.woocommerce button.button:hover, .woocommerce input.button:hover,.woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover,.woocommerce-page a.button:hover, .woocommerce-page button.button:hover,.woocommerce-page input.button:hover {	background-color: #333333; 	color: #ffffff;}';

			echo '.sf_more,.sf_search .searchsubmit:hover,.woocommerce li.product:hover a.add_to_cart_button,.woocommerce-page li.product:hover a.add_to_cart_button {background-color: '. $primary_color .';}';

			echo '.product a:hover h3 {color: '. $primary_color .';}';

			echo '.woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale {	background-color: '. $primary_color .';}';

			echo 'ul.products div.triangle {border-bottom:40px solid '. $primary_color .';}';

			echo '.site-main .chosen-container-active .chosen-choices {border: 1px solid '. $primary_color .';}';

			echo '.woocommerce .site-main #content input.button.alt, .woocommerce .site-main #respond input#submit.alt, .woocommerce .site-main a.button.alt, .woocommerce .site-main button.button.alt, .woocommerce .site-main input.button.alt, .woocommerce-page .site-main #content input.button.alt, .woocommerce-page .site-main #respond input#submit.alt, .woocommerce-page .site-main a.button.alt, .woocommerce-page .site-main button.button.alt, .woocommerce-page .site-main input.button.alt {	background-color: '. $primary_color .';border: 1px solid '. $primary_color .'; }';

			echo '.woocommerce .site-main #content input.button.alt:hover, .woocommerce .site-main #respond input#submit.alt:hover, .woocommerce .site-main a.button.alt:hover, .woocommerce .site-main button.button.alt:hover, .woocommerce .site-main input.button.alt:hover, .woocommerce-page .site-main #content input.button.alt:hover, .woocommerce-page .site-main #respond input#submit.alt:hover, .woocommerce-page .site-main a.button.alt:hover, .woocommerce-page .site-main button.button.alt:hover, .woocommerce-page .site-main input.button.alt:hover {	background-color: #373737;}';

			echo '.woocommerce .entry-summary .compare:hover,.woocommerce div.yith-wcwl-add-button a:hover {color: '. $primary_color .';}';	
			
			echo '.nx-slider > div.owl-controls div.owl-buttons div.owl-next,.nx-slider > div.owl-controls div.owl-buttons div.owl-prev {background-color: '. $primary_color .';}';
			
			echo '.nx-heading.nx-heading-style-coloredline .nx-heading-inner:before {background-color: '. $primary_color .';}';
			
			echo 'a,.coloredtext {color: '. $primary_color .';}';

			echo 'a:visited {color: '. $primary_color .';}';

			echo 'a:active,a:hover {color: #373737;outline: 0;}';

			echo 'input:focus,textarea:focus {border: 1px solid '. $primary_color .';}';

			echo '.nx-iconbox .nx-iconbox-title i {color: '. $primary_color .';}';

			echo '.team-prof:hover div.team-details {background-color: '. $primary_color .';}';

			echo '.team-prof div.team-details ul li a:hover i { color: '. $primary_color .';}';

			echo '.site-main div.nx-tabs-nav { border-bottom: 1px solid '. $primary_color .';}';

			echo '.site-main .nx-tabs-nav span {border-bottom: 1px solid '. $primary_color .';}';

			echo '.site-main .nx-tabs-nav span.nx-tabs-current {border-left: 1px solid '. $primary_color .';border-right: 1px solid '. $primary_color .';border-top: 3px solid '. $primary_color .';}';

			echo '.site-main .nx-tabs-vertical .nx-tabs-nav {border-right: 1px solid '. $primary_color .';}';

			echo '.site-main .nx-tabs-vertical .nx-tabs-nav span.nx-tabs-current {border-left: 3px solid '. $primary_color .';}';

			echo '.nx-carousel.nx-carosel .nx-carousel-prev, .nx-carousel.nx-carosel .nx-carousel-next {background-color: '. $primary_color .';}';

			echo '.post a:hover,.post a:active {color: '. $primary_color .';}';

			echo 'div.nx-accordion div.nx-spoiler-title:hover {color: '. $primary_color .';}';

			echo '.calltoact-wrap {background-color: '. $primary_color .';}';

			//echo '.navigation a:hover {color: '. $primary_color .';}';

			echo '.widget a:hover {color: '. $primary_color .';}';

			echo '.widget_calendar table td#prev > a:hover,.widget_calendar table td#next > a:hover {	background-color: '. $primary_color .';}';

			echo '.social-bar .custom-text > ul ul a:hover,.widget_calendar table a:hover {background-color: '. $primary_color .';}';

			echo '.widget div.tagcloud ul li a:hover {background-color: '. $primary_color .';border: 1px solid '. $primary_color .';}';

			echo '.widget div.tagcloud ul li a:hover {background-color: '. $primary_color .';border: 1px solid '. $primary_color .';}';

			echo '.widget ul.twitter-widget > li > div.twitter_intents a:hover {color: '. $primary_color .';}';

			echo '.site-footer .widget ul li a:hover {color: '. $primary_color .';}';

			echo '.site-footer .widget_calendar td#prev a:hover,.site-footer .widget_calendar td a:hover{	background-color: '. $primary_color .';}';

			echo '.format-video .entry-content a,.format-video .entry-meta a,.format-video .entry-content a:hover,.format-video .entry-meta a:hover {color: '. $primary_color .';}';

			echo '.sp-posts.blog-standard .entry-header a:hover span,.sp-posts.blog-standard .entry-header .entry-meta a:hover {color: '. $primary_color .';}';

			echo '.sp-posts .sp-tagncomm span.sp-blog-comment:hover,.sp-posts.blog-standard .sp-tagncomm .tags-links a:hover {color: '. $primary_color .';}';

			echo '.sp-posts.blog-standard a.sp-continue {color: '. $primary_color .';}';

			echo '@media (min-width: 768px) {.sp-posts.blog-standard .sp-metawrap .sp-day {background-color: '. $primary_color .';}}';

			echo '.sp-posts.blog-masonry .entry-header a:hover span,.sp-posts.blog-masonry .entry-header .entry-meta a:hover {color: '. $primary_color .';}';

			echo '.sp-posts.blog-masonry .sp-tagncomm .tags-links a:hover {color: '. $primary_color .';}';

			echo '.sp-posts.blog-masonry .sp-readmore a.sp-continue {color: '. $primary_color .';}';

			echo '.single-post .related-img .related-link a,div.nx-testi div.owl-prev,div.nx-testi div.owl-next{background-color: '. $primary_color .';}';
			
			echo '.social-bar .custom-text a:hover { color: '. $primary_color .'; }';
			
			echo '.social-bar .custom-text > ul > li:hover > a,.social-bar .custom-text > ul > li > a:hover,.social-bar .custom-text > ul .current_page_item > a,.social-bar .custom-text > ul .current_page_ancestor > a,.social-bar .custom-text > ul .current-menu-item > a,.social-bar .custom-text > ul .current-menu-ancestor > a ,a.colored, a.colored:visited { color: '. $primary_color .'; }';

			echo 'a.colored:hover { color: #373737; }';

			echo '.reversed-link a:hover, a.reversed:hover {color: '. $primary_color .';}';
			
			echo '.social-bar .custom-text > ul .sub-menu,.social-bar .custom-text > ul .children,ul.woocom li.top-login ul,.cartdrop {border-bottom: 2px solid '. $primary_color .';}';
			
			echo '.sp-posts.blog-standard .format-aside.post.hentry,.nx-posts.nx-posts-layout-Standard .format-aside.post.hentry { border-bottom: 2px solid '. $primary_color .'; }';
			
			//echo '.woocommerce ul.products li.product:hover .woo-border-box,.woocommerce-page ul.products li.product:hover .woo-border-box { border: 1px solid '. $primary_color .'; }';
			
			echo '@media (min-width: 768px) {.sidebar.left-sidebar .widget ul.product-categories > li ul { border-bottom: 2px solid '. $primary_color .'; }	.sidebar.left-sidebar .widget ul.product-categories li:hover > a {background-color: '. $primary_color .';}}';
			
			echo '.site-main .nx-post-box h2.nx-entry-title a:hover,.nx-post-box h2.nx-entry-title a:active {color: '. $primary_color .';}';

			echo '.site-main .nx-posts.nx-posts-layout-Standard .entry-header a:hover span,.nx-posts.nx-posts-layout-Standard .entry-header .entry-meta a:hover {color: '. $primary_color .';}';

			echo '.site-main .nx-posts .nx-tagncomm span.nx-blog-comment:hover,.nx-posts.nx-posts-layout-Standard .nx-tagncomm .tags-links a:hover {color: '. $primary_color .';}';

			echo '.site-main .nx-posts.nx-posts-layout-Standard a.nx-continue {color: '. $primary_color .';}';

			echo '.site-main .nx-posts.blog-masonry .nx-entry-header a:hover span,.nx-posts.blog-masonry .nx-entry-header .entry-meta a:hover {color: '. $primary_color .';}';

			echo '.site-main .nx-posts.blog-masonry .nx-tagncomm .tags-links a:hover {color: '. $primary_color .';}';

			echo '.site-main .nx-posts.blog-masonry .nx-readmore a.nx-continue {color: '. $primary_color .';}';

			echo '.site-main .nx-posts.blog-masonry .nx-readmore a.nx-continue:hover {color: #373737;}';

			echo '.site-main .nx-posts .post div.nx-entry-meta-inner a:hover {color: '. $primary_color .';}';
			
			echo '.searchresults h1.entry-title a:hover {	color: '. $primary_color .'; }';

			echo '@media (min-width: 768px) {.site-main .nx-posts.nx-posts-layout-Standard .nx-metawrap .nx-day {background-color: '. $primary_color .';}}';
			
			echo '.nx-slider .owl-controls div.owl-page.active > span,#bbpress-forums li.bbp-footer,#bbpress-forums li.bbp-header {background-color: '. $primary_color .';}';
			
			
			echo '.woocommerce ul.products li.product-category:hover h3,.woocommerce-page ul.products li.product-category:hover h3 {border: 1px solid '. $primary_color .';background-color: '. $primary_color .';}';
			
			echo '.navbar .woocart span.cart-counts,.single-portfolio .related-img .related-link a,.site .nx-custom-carousel .owl-prev,.site .nx-custom-carousel .owl-next,.site .nx-posts-carousel div.owl-prev,.site .nx-posts-carousel div.owl-next {background-color: '. $primary_color .';}';
			
			echo '.navbar .mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item ul.menu > li.menu-item a:hover {	background-color: '. $primary_color .'!important;}';	
			
			echo '.nx-thcolor,.woocommerce .star-rating span {color: '. $primary_color .';}';
			
			echo '.nx-thbdrcolor {border-color: '. $primary_color .';}';
			
			echo '.nx-thbgcolor {background-color: '. $primary_color .';}';
			
			echo '.iconbox2.ibox-topcurved .icon-wrap i, .i-max-header .lavalamp-object {background-color: '. $primary_color .';}';
			
			echo '.iconbox2.ibox-topcurved:hover .icon-wrap i {background-color: #FFF; color: '. $primary_color .'; border-color: '. $primary_color .';}';
			
			// css bellow 999px screen
			
			echo '@media (max-width: 999px) {';
			
			echo '.classic-menu .nav-container > ul li > a:hover, .classic-menu .nav-container > ul ul li a:hover {color: #fff; background-color: '. $primary_color .';}';
			
			echo '}';						
			
			
			if ($custom_css) {
			echo "\n".'/* =============== user styling =============== */'."\n";
			echo $custom_css;
			}
			
			// CLOSE STYLE TAG
			echo "</style>". "\n";
		}
	
		add_action('wp_head', 'nx_custom_styles');
	}
	
	/* CUSTOM JS OUTPUT
	================================================== */
	function nx_custom_script() {
		
		global  $ispirit_data;
		
		$custom_js = $ispirit_data['custom_js'];
		
		if ($custom_js) {			
			echo "\n<script>\n".$custom_js."\n</script>\n";			
		}
	}
	
	add_action('wp_footer', 'nx_custom_script');
	
	
	/* Go TO TOP
	================================================== */
	function nx_go_to_top() {
		
		global  $ispirit_data;
		
		$goto_top = $ispirit_data['back-to-top'];
		
		if ( $goto_top == 1 ) {			
			echo '<a href="#" class="go-top animated"><span class="genericon genericon-collapse"></span></a>';			
		}
	}
	
	add_action('wp_footer', 'nx_go_to_top');		

?>