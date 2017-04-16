<?php
/**
 * @package NX Search
 */
/*
	Plugin Name: NX Live Search
	Plugin URI: http://templatesNext.org
	Description: Transfer wordpress form into an advanced ajax search form the same as facebook live search, This version supports themes and can work with almost all themes without any modifications
	Version: 3.0.5
	Author: templatesNext
	Author URI: http://www.templatesNext.org
	License: GPLv2 or later
*/



define('NX_SF_VERSION', '3.0.5');
define('NX_SF_PLUGIN_URL', NX_LOCAL_PATH."/inc/nx-search-form/");
define('NX_SF_NO_IMAGE', NX_SF_PLUGIN_URL ."themes/default/images/no-image.gif");
	
class nxLiveSearch {


	public static $woocommerce_taxonomies = array('product_cat', 'product_tag');
	public static $woocommerce_post_types = array('product');
	
	private $noimage = '';
	
	function __construct(){
		$this->actions();
		//$this->filters();
		//$this->shortcodes();
	}
	function actions(){
		//ACTIONS
		if(class_exists('NX_SF_WIDGET')){
			add_action( 'widgets_init', create_function( '', 'return register_widget( "NX_SF_WIDGET" );' ) );
		}
		add_action( 'wp_enqueue_scripts', array(&$this, "enqueue_scripts"));
		add_action( 'wp_head', array(&$this, 'head'));
		add_action( 'wp_footer', array(&$this, 'footer'));
		
		add_action( 'wp_ajax_ajaxy_sf', array(&$this, 'get_search_results'));
		add_action( 'wp_ajax_nopriv_ajaxy_sf', array(&$this, 'get_search_results'));
	}


	function get_image_from_content($content, $width_max, $height_max){
		//return false;
		$theImageSrc = false;
		preg_match_all ('/<img[^>]+>/i', $content, $matches);
		$imageCount = count ($matches);
		if ($imageCount >= 1) {
			if (isset ($matches[0][0])) {
				preg_match_all('/src=("[^"]*")/i', $matches[0][0], $src);
				if (isset ($src[1][0])) {
					$theImageSrc = str_replace('"', '', $src[1][0]);
				}
			}
		}

		return array('src' => $theImageSrc, 'width' => '60' , 'height' => '60' );	
		return false;
	}
	function get_post_types()
	{
		//$post_types = get_post_types(array('_builtin' => false),'objects');
		//$post_types['post'] = get_post_type_object('post');
		//$post_types['page'] = get_post_type_object('page');
		$post_types['product'] = get_post_type_object('product');
		unset($post_types['wpsc-product-file']);
		return $post_types;
	}
	function get_excerpt_count()
	{
		return 10;
	}
	
	//get_search_objects(false, false, $arg_post_types, $arg_taxonomies);
	
	function get_search_objects($all = false, $objects = false, $specific_post_types = array(), $specific_taxonomies = array(), $specific_roles = array())
	{
		$search = array();
		//$scat = (array)$this->get_setting('category');
		
		//$arg_category_show = isset($_POST['show_category']) ? $_POST['show_category'] : 1;
		$arg_category_show = 1;
		//$scat['show'] = 1;
		
		$search_taxonomies = true;
		$arg_post_category_show = 1;
		$show_post_category = true;
		$arg_authors_show = 0;
		$show_authors = false;
		
		if(!$objects || $objects == 'post_type') {
			// get all post types that are ready for search
			$post_types = $this->get_post_types();
			
			foreach($post_types as $post_type)
			{		
				if(sizeof($specific_post_types) == 0) {	
					$search[] = array(
						'order' => 1, 
						'name' => $post_type->name, 
						'label' => 	__('Suggested Products','ispirit'), 
						'type' => 	'post_type'
					);
				}
				elseif(in_array($post_type->name, $specific_post_types)) {
					$search[] = array(
							'order' => 1, 
							'name' => $post_type->name, 
							'label' => 	__('Suggested Products','ispirit'), 
							'type' => 	'post_type'
					);
				}
			}
		}
		
		uasort($search, array(&$this, 'sort_search_objects'));

		return $search;
	}
	function sort_search_objects($a, $b) {
		if ($a['order'] == $b['order']) {
			return 0;
		}
		return ($a['order'] < $b['order']) ? -1 : 1;
	}

	function get_setting($name, $public = true)
	{
		$defaults = array(
						'title' => '', 
						'show' => 1,
						'ushow' => 0,
						'search_content' => 1,
						'limit' => 10,
						'order' => 0,
						'order_results' => false
						);
		if(!$public) {
			$defaults['show'] = 0;
		}
		return (object)$defaults;
	}

	function get_templates($template, $type='')
	{
		$template_post = "";
		switch($type) {
			case 'more':
				$template_post = '<a href="{search_url_escaped}"><span class="sf_text">See more results for "{search_value}"</span><span class="sf_small">Displaying top {total} results</span></a>';
				break;
			case 'taxonomy':
				$template_post = '<a href="{category_link}">{name}</a>';
				break;
			case 'post_type':
				$template_post = '<a href="{post_link}">{post_image_html}<span class="sf_text">{post_title}</span><span class="sf_small">{wp_prod_cat}</span><span class="prod-price">{wp_currency} {price}</span></a>';
				break;
			default:
				$template_post = '<a href="{post_link}">{post_image_html}<span class="sf_text">{post_title}</span><span class="sf_small">Posted by {post_author} on {post_date_formatted}</span></a>';
				break;
		}
		return $template_post;
	}
	
	function category($name, $taxonomy = 'category', $show_category_posts = true, $limit = 5)
	{
		global $wpdb;

		$categories = array();
		$setting = (object)$this->get_setting($taxonomy);

		$excludes = "";
		$excludes_array = array();
		if(isset($setting->excludes) && sizeof($setting->excludes) > 0 && is_array($setting->excludes)){
			$excludes = " AND $wpdb->terms.term_id NOT IN (".implode(',', $setting->excludes).")";
			$excludes_array = $setting->excludes;
		}
		$results = null;
		
		$query = "
			SELECT 
				distinct($wpdb->terms.name)
				, $wpdb->terms.term_id
				, $wpdb->term_taxonomy.taxonomy 
			FROM 
				$wpdb->terms
				, $wpdb->term_taxonomy 
			WHERE 
				name LIKE '%%%s%%' 
				AND $wpdb->term_taxonomy.taxonomy = '$taxonomy' 
				AND $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id 
			$excludes 
			LIMIT 0, %d";
			
		$query = apply_filters("nx_sf_category_query", $wpdb->prepare($query,  $name, $setting->limit), $name, $excludes_array, $setting->limit);

		$results = $wpdb->get_results($query);

		if(sizeof($results) > 0 && is_array($results) && !is_wp_error($results))
		{
			$unset_array = array('term_group', 'term_taxonomy_id', 'taxonomy', 'parent', 'count', 'cat_ID', 'cat_name', 'category_parent');
			foreach($results as $result)
			{
				$cat = get_term($result->term_id, $result->taxonomy);
				if($cat != null && !is_wp_error($cat))
				{	
					$cat_object = new stdclass();
					$category_link = get_term_link($cat);
					$cat_object->category_link = $category_link;
					
					$matches = array();
					$template = $this->get_templates( $taxonomy, 'taxonomy' );
					preg_match_all ("/\{.*?\}/", $template, $matches);
					
					foreach($matches[0] as $match){
						$match = str_replace(array('{', '}'), '', $match);
						if(isset($cat->{$match})) {
							$cat_object->{$match} = $cat->{$match};
						}
					}
					if($show_category_posts) {
						$limit = isset($setting->limit_posts) ? $setting->limit_posts : 5;
						$psts = $this->posts_by_term($cat->term_id, $taxonomy, $limit);
						if(sizeof($psts) > 0) {
							$categories[$cat->term_id] = array('name' => $cat->name,'posts' => $this->posts_by_term($cat->term_id, $limit)); 
						}
					}
					else {
						$categories[] = $cat_object; 
					}
				}
			}
		}
		return $categories;
	}	

	
	function posts($name, $post_type='post', $term_id = false)
	{
		global $wpdb;
		$posts = array();
		$setting = (object)$this->get_setting($post_type);
		$excludes = "";
		$excludes_array = array();
		if(isset($setting->excludes) && sizeof($setting->excludes) > 0 && is_array($setting->excludes)){
			$excludes = " AND ID NOT IN (".implode(',', $setting->excludes).")";
			$excludes_array = $setting->excludes;
		}
		
		$order_results = ($setting->order_results ? " ORDER BY ".$setting->order_results : "");
		$results = array();
		
		$query = "
			SELECT 
				$wpdb->posts.ID 
			FROM 
				$wpdb->posts
			WHERE 
				(post_title LIKE '%%%s%%' ".($setting->search_content == 1 ? "or post_content LIKE '%%%s%%')":")")." 
				AND post_status='publish' 
				AND post_type='".$post_type."' 
				$excludes 
				$order_results 
			LIMIT 0, %d";
			
		$query = apply_filters("nx_sf_posts_query", ($setting->search_content == 1 ? $wpdb->prepare($query, $name, $name, $setting->limit) :$wpdb->prepare($query, $name, $setting->limit)), $name, $post_type, $excludes_array, $setting->search_content, $order_results, $setting->limit);
		
		$results = $wpdb->get_results( $query );
		
		
		if(sizeof($results) > 0 && is_array($results) && !is_wp_error($results))
		{
			$template = $this->get_templates( $post_type, 'post_type' );
			$matches = array();
			preg_match_all ("/\{.*?\}/", $template, $matches);
			if(sizeof($matches) > 0) {
				foreach($results as $result)
				{
					$pst = $this->post_object($result->ID, $term_id, $matches[0]);
					if($pst){
						$posts[] = $pst; 
					}
				}
			}
		}
		return $posts;
	}
	/**/
	function posts_by_term($term_id, $taxonomy, $limit = 5){
		$posts = array();
		$args = array( 
				'showposts' => $limit,
				'tax_query' => array(
					array(
						'taxonomy' => $taxonomy,
						'terms' => $term_id,
						'field' => 'term_id',
					)
				),
				'orderby' => 'date',
				'order' => 'DESC' 
			);
		$term_query = new WP_Query( $args );
		if($term_query->have_posts()) :
			$psts = apply_filters('sf_pre_term_posts', $term_query->posts);
			if(sizeof($psts) > 0) {
				foreach($psts as $p) {
					$matches = array();
					$template = $this->get_templates( $p->post_type, 'post_type' );
					preg_match_all ("/\{.*?\}/", $template, $matches);
					$posts[] = $this->post_object($p->ID, false, $matches[0]);
				}
			}
			$posts = apply_filters('sf_term_posts', $posts);
		endif;
		return $posts;
	}
	function post_object($id, $term_id = false, $matches = false) {
		$unset_array = array('post_date_gmt', 'post_status', 'comment_status', 'ping_status', 'post_password', 'post_content_filtered', 'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_parent', 'guid', 'menu_order', 'post_mime_type', 'comment_count', 'ancestors', 'filter');
		global $post;
		$date_format = get_option( 'date_format' );
		$post = get_post($id);
		if($term_id) {	
			if(!in_category($term_id, $post->ID)){
				return false;
			}
		}
		$size = array('height' => '50', 'width' => '50');
		if($post != null)
		{
			$post_object = new stdclass();
			$post_link = get_permalink($post->ID);

			if(in_array('{post_image}', $matches) || in_array('{post_image_html}', $matches)) {
				$post_thumbnail_id = get_post_thumbnail_id( $post->ID);
				if( $post_thumbnail_id > 0)
				{
					$thumb = wp_get_attachment_image_src( $post_thumbnail_id, array($size['height'], $size['width']) );
					$post_object->post_image =  (trim($thumb[0]) == "" ? NX_SF_NO_IMAGE : $thumb[0]);
					if(in_array('{post_image_html}', $matches)) {
						$post_object->post_image_html = '<img src="'.$post_object->post_image.'" width="'.$size['width'].'" height="'.$size['height'].'"/>';
					}
				}
				else
				{
					if($src = $this->get_image_from_content($post->post_content, $size['height'], $size['width'])){
						$post_object->post_image = $src['src'] ? $src['src'] : NX_SF_NO_IMAGE;
						if(in_array('{post_image_html}', $matches)) {
							$post_object->post_image_html = '<img src="'.$post_object->post_image.'" width="'.$src['width'].'" height="'.$src['height'].'" />';
						}
					}
					else{
						$post_object->post_image = NX_SF_NO_IMAGE;
						if(in_array('{post_image_html}', $matches)) {
							$post_object->post_image_html = '';
						}
					}
				}
			}
			if($post->post_type == 'product' && class_exists('WC_Product_Factory')) {
				$product_factory = new WC_Product_Factory();
				global $product;
				$product = $product_factory->get_product($post);
				if($product->is_visible()) {
					
					foreach($matches as $match) {
						
						$match = str_replace(array('{', '}'), '', $match);
						if(in_array($match, array('categories', 'tags'))) {
							$method = "get_".$match;
							if(method_exists ($product, $method)){
								$term_list = call_user_func(array($product, $method), '');
								if($term_list){
									$post_object->{$match} = '<span class="sf_list sf_'.$match.'">'.$term_list.'</span>';
								}else{
									$post_object->{$match} = "";
								}
							}
						}elseif($match == 'add_to_cart_button'){
							ob_start();
							do_action( 'woocommerce_' . $product->product_type . '_add_to_cart'  );
							$post_object->{$match} = '<div class="product">'.ob_get_contents().'</div>';
							ob_end_clean();
						}else{
							$method = "get_".$match;
							if(method_exists ($product, $method)){
								$post_object->{$match} = call_user_func(array($product, $method));
							}elseif(method_exists ($product, $match)){
								$post_object->{$match} = call_user_func(array($product, $match));
							}
						}
					}
				}

			}
			$post_object->ID = $post->ID;
			$post_object->post_title = get_the_title($post->ID);	
			
			//$post_object->wp_prod_cat = get_the_terms( $post->ID, 'product_cat' );
			
			$prod_terms = get_the_terms($post->ID, 'product_cat' );
			if ($prod_terms && ! is_wp_error($prod_terms)) :
				$term_slugs_arr = array();
				foreach ($prod_terms as $prod_term) {
					$term_slugs_arr[] = $prod_term->slug;
				}
				$terms_slug_str = join( ", ", $term_slugs_arr);
			endif;
			
			$post_object->wp_prod_cat = $terms_slug_str;
		
			
			
			$post_object->wp_currency = get_woocommerce_currency_symbol();

			
			if(in_array('{post_excerpt}', $matches)) {
				$post_object->post_excerpt = $post->post_excerpt;
			}if(in_array('{post_author}', $matches)) {
				$post_object->post_author = get_the_author_meta('display_name', $post->post_author);
			}if(in_array('{post_link}', $matches)) {
				$post_object->post_link = $post_link;
			}if(in_array('{post_content}', $matches)) {
				$post_object->post_content = $this->get_text_words(apply_filters('the_content', $post->post_content) ,(int)$this->get_excerpt_count());
			}if(in_array('{post_date_formatted}', $matches)) {
				$post_object->post_date_formatted = date($date_format,  strtotime( $post->post_date) );
			}

			
			
			foreach($matches as $match) {
				$match = str_replace(array('{', '}'), '', $match);

				if(strpos($match, 'custom_field_') !== false){
					$key =  str_replace('custom_field_', '', $match);
					$custom_field = get_post_meta($post->ID, $key, true);
					if ( is_array($custom_field) ) {
						$cf_name = 'custom_field_'.$key;
						$post_object->{$cf_name} = apply_filters('sf_post_custom_field', $custom_field[0], $key, $post);
					}else{
						$cf_name = 'custom_field_'.$key;
						$post_object->{$cf_name} = apply_filters('sf_post_custom_field', $custom_field, $key, $post);
					}
				}
			}
	
			$post_object = apply_filters('sf_post', $post_object);
			return $post_object;
		}
		
		return false;
	}
	function get_text_words($text, $count)
	{
		$tr = explode(' ', strip_tags(strip_shortcodes($text)));
		$s = "";
		for($i = 0; $i < $count && $i < sizeof($tr); $i++)
		{
			$s[] = $tr[$i];
		}
		return implode(' ', $s);
	}
	function enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'ajaxy-sf-search', NX_SF_PLUGIN_URL."js/sf.js", array('jquery'), '1.0.1', true );
		wp_enqueue_script( 'ajaxy-sf-selective', NX_SF_PLUGIN_URL."js/sf_selective.js", array('jquery'), '1.0.0', true );
	}
	function head()
	{

		?>
		<!-- AJAXY SEARCH V 
		<?php echo NX_SF_VERSION; ?>-->
		
        <?php
		$settings = array(
			'label' => 'Search',
			'expand' => false
		);
		
		$live_search_settings = json_encode(
			array(
				'expand' => $settings['expand']
				,'searchUrl' => home_url().'/?s=%s'
				,'text' => $settings['label']
				,'delay' => 500
				,'iwidth' => 180 
				,'width' => 456 
				,'ajaxUrl' => $this->get_ajax_url()
				,'rtl' => 0
			)
		);
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
				var sf_position = '0';
				var sf_templates = <?php echo json_encode($this->get_templates('more', 'more')); ?>;
				var sf_input = '.sf_input';
				jQuery(document).ready(function(){
					jQuery(sf_input).ajaxyLiveSearch(<?php echo $live_search_settings; ?>);
					jQuery(".sf_ajaxy-selective-input").keyup(function() {
						var width = jQuery(this).val().length * 8;
						if(width < 50) {
							width = 50;
						}
						jQuery(this).width(width);
					});
					jQuery(".sf_ajaxy-selective-search").click(function() {
						jQuery(this).find(".sf_ajaxy-selective-input").focus();
					});
					jQuery(".sf_ajaxy-selective-close").click(function() {
						jQuery(this).parent().remove();
					});
				});
			/* ]]> */
		</script>
		<?php
	}
	function get_ajax_url(){
		if(defined('ICL_LANGUAGE_CODE')){
			return admin_url('admin-ajax.php').'?lang='.ICL_LANGUAGE_CODE;
		}
		if(function_exists('qtrans_getLanguage')){

			return admin_url('admin-ajax.php').'?lang='.qtrans_getLanguage();
		}
		return admin_url('admin-ajax.php');
	}
	function footer(){
		//echo $script;
	}

	function get_search_results()
	{
		$results = array();
		$sf_value = apply_filters('sf_value', $_POST['sf_value']);
		if(!empty($sf_value))
		{
			//filter taxonomies if set
			$arg_taxonomies = isset($_POST['taxonomies']) && trim($_POST['taxonomies']) != "" ? explode(',', trim($_POST['taxonomies'])) : array();
			// override post_types from input
			$arg_post_types = isset($_POST['post_types']) && trim($_POST['post_types']) != "" ? explode(',', trim($_POST['post_types'])) : array();
			
			$search = $this->get_search_objects(false, false, $arg_post_types, $arg_taxonomies);
			$author_searched = false;
			$authors = array();
			foreach($search as $key => $object)
			{
				if($object['type'] == 'post_type') {
					$posts_result = $this->posts($sf_value, $object['name']);
					if(sizeof($posts_result) > 0) {
						$results[$object['name']][0]['all'] = $posts_result;
						$results[$object['name']][0]['template'] = $this->get_templates($object['name'], 'post_type');
						$results[$object['name']][0]['title'] = $object['label'];
						$results[$object['name']][0]['class_name'] = 'sf_item'.(in_array($object['name'], self::$woocommerce_post_types) ? ' woocommerce': '');
					}
				}
				elseif($object['type'] == 'taxonomy') {
					if($object['show_posts']) {
						$taxonomy_result = $this->category($sf_value, $object['name'], $object['show_posts']);
						if(sizeof($taxonomy_result) > 0) {
							$cnt = 0;
							foreach($taxonomy_result as $key => $val) {
								if(sizeof($val['posts']) > 0) {
									$results[$object['name']][$cnt]['all'] = $val['posts'];
									$results[$object['name']][$cnt]['template'] = $this->get_templates($object['name'], 'taxonomy');
									$results[$object['name']][$cnt]['title'] = $object['label'];
									$results[$object['name']][$cnt]['class_name'] = 'sf_category';
									$cnt ++;
								}
							}
						}
					}else{
						$taxonomy_result = $this->category($sf_value, $object['name']);
						if(sizeof($taxonomy_result) > 0) {
							$results[$object['name']][0]['all'] = $taxonomy_result;
							$results[$object['name']][0]['template'] = $this->get_templates($object['name'], 'taxonomy');
							$results[$object['name']][0]['title'] = $object['label'];
							$results[$object['name']][0]['class_name'] = 'sf_category';
						}
					}
				}
			}
			$results = apply_filters('sf_results', $results);
			echo json_encode($results);
		}
		do_action( 'sf_value_results', $sf_value, $results);
		exit;
	}


	function form($settings)
	{
		$form = '<!-- nx Search Form v'.NX_SF_VERSION.' -->
		<div id="'.$settings['id'].'" class="sf_container">
			<form role="search" method="get" class="searchform" action="' . home_url( '/' ) . '" >
				<div class="nx-live-form">
					
					<label class="screen-reader-text">' . __('Search for:','ispirit') . '</label>
					<div class="sf_search">
						<span class="sf_block">
							<input class="sf_input" autocomplete="off" type="text" value="' . (get_search_query() == '' ? $settings['label'] : get_search_query()). '" name="s"/>
							<input type="hidden" value="product" name="post_type" />
							<button class="sf_button searchsubmit" type="submit"><span>'. __('Search','ispirit') .'</span></button>
						</span>
					</div>
				</div>
			</form>
		</div>';
		
		return $form;
	}	
	
	//<div class="catselect">'. $this->nx_cat_select() .'</div> //
	/* 
	function nx_cat_select() {
		
		$args = array( 
			'orderby' => 'name',
			'hide_empty' => 1,
			'hierarchical' => 1
		);
		$taxonomy = 'product_cat';
		$cat_select = '';
		if ( ! empty( $taxonomy ) ) {
			$terms = get_terms( $taxonomy, $args );
			//print_r('<pre>');
			//print_r($terms);
			//print_r('</pre>');	
			if ( ! is_wp_error( $terms ) || !empty( $terms ) ) {
		
				$links = array();
				$links[] = '<option value="">All Categories</option>';		
				foreach ( $terms as $term ) {
					
					if ($term->parent == 0)
					{
						$links[] = '<option value="'.$term->slug.'">'.$term->name.'</option>';
					} else
					{
						$links[] = '<option value="'.$term->slug.'">'.$term->name.'</option>';
					}
				}
				
				if ( sizeof( $links ) > 2 ) :
					$cat_select .= '<select name="product_cat" id="tax" class="nx-choosen-select">';
						$cat_select .= implode( "", $links ); 
					$cat_select .= '</select>';
				endif;
			}
		}


		return $cat_select;
		
	}
	*/

	function form_shortcode($atts = array()) {
		$m = uniqid();
		//$scat = (array)$this->get_setting('category');
		$settings = array(
			'id' => $m,
			'label' => __('Search Product','ispirit'),
			'expand' => 0,
			'width' => 456,
			'show_category' => 1,
			'show_post_category' => 1,
			'post_types' => ''
		);
		if($settings['expand'] == 1){
			$settings['width'] = $settings['expand'];
		}
		$settings = shortcode_atts( $settings, $atts, 'ajaxy-live-search-layout' ) ;
		$form = $this->form($settings);
		
		
		$live_search_settings = array(
			'expand' => $settings['expand']
			,'searchUrl' => home_url().'/?s=%s&post_type=product'
			,'text' => $settings['label']
			,'delay' => 500
			,'iwidth' => 180 
			,'width' => 456 
			,'ajaxUrl' => $this->get_ajax_url()
			,'ajaxData' => 'sf_custom_data_'.$m
			,'search' => false
			,'rtl' => 0
		);

		$live_search_settings = shortcode_atts( $live_search_settings, $atts, 'ajaxy-live-search' ) ;
		
		$script = '
		<script type="text/javascript">
			/* <![CDATA[ */
				function sf_custom_data_'.$m.'(data){ 
					data.show_category = "'.$settings['show_category'].'";
					data.show_post_category = "'.$settings['show_post_category'].'";
					data.post_types = "'.$settings['post_types'].'";
					return data;
				}
				jQuery(document).ready(function(){
					jQuery("#'.$m.' .sf_input").ajaxyLiveSearch('.json_encode($live_search_settings).');					
				});
			/* ]]> */
		</script>';
		return $form.$script;
	}	

}

add_filter('nx_sf_category_query', 'nx_sf_category_query', 4, 10);
function nx_sf_category_query($query, $search, $excludes, $limit){
	global $wpdb;
	$wpml_lang_code = (defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE: false);
	if(	$wpml_lang_code ) {
		if(sizeof($excludes) > 0){
			$excludes = " AND $wpdb->terms.term_id NOT IN (".implode(",", $excludes).")";
		}
		else{
			$excludes = "";
		}
		$query = "select * from (select distinct($wpdb->terms.name), $wpdb->terms.term_id,  $wpdb->term_taxonomy.taxonomy,  $wpdb->term_taxonomy.term_taxonomy_id from $wpdb->terms, $wpdb->term_taxonomy where name like '%%%s%%' and $wpdb->term_taxonomy.taxonomy<>'link_category' and $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id $excludes limit 0, ".$limit.") as c, ".$wpdb->prefix."icl_translations as i where c.term_taxonomy_id = i.element_id and i.language_code = %s and SUBSTR(i.element_type, 1, 4)='tax_' group by c.term_id";
		$query = $wpdb->prepare($query,  $search, $wpml_lang_code);
		return $query;
	}
	return $query;
}
add_filter('nx_sf_posts_query', 'nx_sf_posts_query', 5, 10);
function nx_sf_posts_query($query, $search, $post_type, $excludes, $search_content, $order_results, $limit){
	global $wpdb;
	$wpml_lang_code = (defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE: false);
	if(	$wpml_lang_code ) {
		if(sizeof($excludes) > 0){
			$excludes = " AND $wpdb->posts.ID NOT IN (".implode(",", $excludes).")";
		}
		else{
			$excludes = "";
		}
		//$order_results = (!empty($order_results) ? " order by ".$order_results : "");
		$query = $wpdb->prepare("select * from (select $wpdb->posts.ID from $wpdb->posts where (post_title like '%%%s%%' ".($search_content == true ? "or post_content like '%%%s%%')":")")." and post_status='publish' and post_type='".$post_type."' $excludes $order_results limit 0,".$limit.") as p, ".$wpdb->prefix."icl_translations as i where p.ID = i.element_id and i.language_code = %s group by p.ID",  ($search_content == true ? array($search, $search, $wpml_lang_code): array($search, $wpml_lang_code)));
		return $query;
	}
	return $query;
}
/**/
function nx_search_form($settings = array())
{
	global $nxLiveSearch;
	echo $nxLiveSearch->form_shortcode($settings);
}
global $nxLiveSearch;
$nxLiveSearch = new nxLiveSearch();


?>