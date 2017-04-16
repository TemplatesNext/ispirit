<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 * */

if (!class_exists("ispirit_options_config")) {

    class ispirit_options_config {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if ( !class_exists("ReduxFramework" ) ) {
                return;
            }    

            // This is needed. Bah WordPress bugs.  ;)
            if ( defined('TEMPLATEPATH') && strpos( Redux_Helpers::cleanFilePath( __FILE__ ), Redux_Helpers::cleanFilePath( TEMPLATEPATH ) ) !== false) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);    
            }
        }

        public function initSettings() {
            
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2); 
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo "<h1>The compiler hook has run!";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            
              // Demo of how to use the dynamic CSS and write your own static CSS file
              //$filename = dirname(__FILE__) . '../custom-style' . '.css';
			 
			  $upload_dir = wp_upload_dir();				
			  $filename = $upload_dir['basedir']. '/custom-style' . '.css';
			  
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
              require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
              $wp_filesystem->put_contents(
              $filename,
              $css,
              FS_CHMOD_FILE // predefined mode settings for WP files
              );
              }
             /**/
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'nx-admin'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'nx-admin'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = "Testing filter hook!";

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);
                
                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
                
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path = ReduxFramework::$_dir . '../ispirit-options/patterns/';
            $sample_patterns_url = ReduxFramework::$_url . '../ispirit-options/patterns/';
            $sample_patterns = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode(".", $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[] = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct = wp_get_theme();
            $this->theme = $ct;
            $item_name = $this->theme->get('Name');
            $tags = $this->theme->Tags;
            $screenshot = $this->theme->get_screenshot();
            $class = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'nx-admin'), $this->theme->display('Name'));
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
            <?php endif; ?>

                <h4>
            <?php echo $this->theme->display('Name'); ?>
                </h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'nx-admin'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'nx-admin'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'nx-admin') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
                <?php
                if ($this->theme->parent()) {
                    printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'nx-admin'), $this->theme->parent()->display('Name'));
                }
                ?>

                </div>

            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }




            // ACTUAL DECLARATION OF SECTIONS

            $this->sections[] = array(
                'icon' => 'el-icon-cogs',
                'title' => __('General Settings', 'nx-admin'),
                'fields' => array(

					array(
						'id'       => 'maintenance_mode',
						'type'     => 'button_set',
						'title'    => __('Enable Maintenance Mode', 'nx-admin'),
						'subtitle' => __('Enable the themes maintenance mode', 'nx-admin'),
						'options' => array(
							'1' => 'on',
							'2' => 'off'
						 ),
						'default' => '2'
					),
					array(
						'id'       => 'back-to-top',
						'type'     => 'button_set',
						'title'    => __('Enable Back to Top Button', 'nx-admin'),
						'options' => array(
							'1' => 'Enable',
							'2' => 'Disable'
						 ),
						'default' => '1'
					),							
                    array(
                        'id' => 'site_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => __('Default Layout', 'nx-admin'),
                        'subtitle' => __('Select layout type. boxed or wide. Background options only visible when boxed type layout is selected', 'nx-admin'),
                        'options' => array(
                            '1' => array('alt' => 'Boxed', 'img' => ReduxFramework::$_url . 'assets/img/boxed.png'),
                            '2' => array('alt' => 'Wide', 'img' => ReduxFramework::$_url . 'assets/img/wide.png')
                        ),
                        'default' => '2'
                    ),
					array(
						'id'       => 'boxed_shadow',
						'type'     => 'button_set',
						'title'    => __('Use Shadows', 'nx-admin'),
						'subtitle' => __('Enable/disable shadows used in various places to give flat look (boxed page shadow, slider/titlebar inner shadow, dropdown shadows etc.)', 'nx-admin'),
						'options' => array(
							'1' => 'on',
							'2' => 'off'
						 ),
						'default' => '2'
					),
					
					array(
						'id'       => 'background_options',
						'type'     => 'button_set',
						'required' => array('site_layout', 'equals', '1'),
						'title'    => __('Choose background type', 'nx-admin'),
						'subtitle' => __('Choose between custom image or color as background or predefined', 'nx-admin'),
						'options' => array(
							'1' => 'Image Upload / Background Color',
							'2' => 'Predefined Pattern'
						 ),
						'default' => '1'
					),					
					
					array(         
						'id'       => 'opt-background',
						'type'     => 'background',
						'compiler' => array('body.boxed'),
						'title'    => __('Body Background', 'nx-admin'),
						'required' => array('background_options','equals','1'),
						'preview_media' => true,
						'subtitle' => __('Body background with image, color, etc (works only with boxed type layout).', 'nx-admin'),
						'desc'     => __('This is the description field, again good for additional info.', 'nx-admin'),
						'default'  => array(
							'background-color' => '#FFFFFF',
						)
					),
                    array(
                        'id' => 'pre-pattern',
                        'type' => 'image_select',
                        'compiler' => array('body.boxed'),
                        'title' => __('Select a Predefined Background', 'nx-admin'),
						'required' => array('background_options','equals','2'),
                        'subtitle' => __('Select a predefined background pattern, first one is transparent (works only with boxed type layout).', 'nx-admin'),
                        'options' => array(
                            ReduxFramework::$_url . 'assets/img/pattern2/patt0.png' => array('alt' => 'none', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat0.png'),						
                            ReduxFramework::$_url . 'assets/img/pattern2/patt1.png' => array('alt' => 'pattrn 1', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat1.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt2.png' => array('alt' => 'pattrn 2', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat2.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt3.png' => array('alt' => 'pattrn 3', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat3.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt4.png' => array('alt' => 'pattrn 4', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat4.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt5.png' => array('alt' => 'pattrn 5', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat5.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt6.png' => array('alt' => 'pattrn 6', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat6.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt7.png' => array('alt' => 'pattrn 1', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat7.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt8.png' => array('alt' => 'pattrn 2', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat8.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt9.png' => array('alt' => 'pattrn 3', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat9.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt10.png' => array('alt' => 'pattrn 4', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat10.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt11.png' => array('alt' => 'pattrn 5', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat11.png'),
                            ReduxFramework::$_url . 'assets/img/pattern2/patt12.png' => array('alt' => 'pattrn 6', 'img' => ReduxFramework::$_url . 'assets/img/pattern2/pat12.png')							
                        ),
                        'default' => ''
                    ),
                    array(
                        'id' => 'favicon',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Custom Favicon', 'nx-admin'),
                        'compiler' => 'true',
                        //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc' => __('Upload custom favicon', 'nx-admin'),
                        'subtitle' => __('Upload 16px by 16px GIF or PNG', 'nx-admin'),
                        //'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                    ),
                    array(
                        'id' => 'ios57x57',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Custom iOS 57x57 Icon', 'nx-admin'),
                        'compiler' => 'true',
                        //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        //'desc' => __('Upload a 57px x 57px Png image that will be your website bookmark on non-retina iOS devices.', 'nx-admin'),
                        'subtitle' => __('Upload a 57px x 57px Png image for bookmark on non-retina iOS devices.', 'nx-admin'),
                        //'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                    ),
                    array(
                        'id' => 'ios72x72',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Custom iOS 72x72 Icon', 'nx-admin'),
                        'compiler' => 'true',
                        //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        //'desc' => __('Upload a 57px x 57px Png image that will be your website bookmark on non-retina iOS devices.', 'nx-admin'),
                        'subtitle' => __('Upload a 72px x 72px Png image for bookmark on non-retina iOS devices.', 'nx-admin'),
                        //'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                    ),
                    array(
                        'id' => 'ios144x144',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Custom iOS 72x72 Icon', 'nx-admin'),
                        'compiler' => 'true',
                        //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        //'desc' => __('Upload a 57px x 57px Png image that will be your website bookmark on non-retina iOS devices.', 'nx-admin'),
                        'subtitle' => __('Upload a 72px x 72px Png image for bookmark on retina iOS devices.', 'nx-admin'),
                        //'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                    )
                )
            );
			
			
            $this->sections[] = array(
                'icon' => 'el-icon-website',
                'title' => __('Colors and Fonts', 'nx-admin'),
                'fields' => array(
                    array(
						'id'       		=> 'primary-color',
						'type'    		=> 'color',
						'transparent'	=> false,
						'title'    		=> __('Primary Color', 'nx-admin'),
						'subtitle' 		=> __('Pick a color for the theme (default: #77be32).', 'nx-admin'),
						'default'  		=> '#77be32',
						'validate' 		=> 'color',
					),
					array(
						'id'          => 'body-font',
						'type'        => 'typography', 
						'title'       => __('Body Font', 'nx-admin'),
						'google'      => true, 
						'font-backup' => true,
						'font-weight' => false,
						'font-style'  => false,
						'color'  => false,
						'subsets'     => false,
						'text-align'  => false,
						'font-size'   => false,
						'line-height' => false,
						'output'      => array('html,button,input,select,textarea'),
						'units'       =>'px',
						'subtitle'    => __('Select a font for all the text except title textx', 'nx-admin'),
						'default'     => array(
							'font-family' => 'Open Sans', 
							'google'      => true
						),
					),
					array(
						'id'       => 'body-font-size',
						'type'     => 'spinner',
						'title'    => __('Body Font Size', 'nx-admin'),
						'subtitle' => __('Increase body font size, minmum 10px maximum 18px (default 13)','nx-admin'),
						'default'  => '13',
						'min'      => '10',
						'step'     => '1',
						'max'      => '18',
					),
                    array(
						'id'       		=> 'primary-text-color',
						'type'    		=> 'color',
						'transparent'	=> false,
						'output'      => array('body'),						
						'title'    		=> __('Primary Text Color', 'nx-admin'),
						'subtitle' 		=> __('Pick a color for content text (default: #373737).', 'nx-admin'),
						'default'  		=> '#373737',
						'validate' 		=> 'color',
					),					
					array(
						'id'       => 'body-line-height',
						'type'     => 'spinner',
						'title'    => __('Body Text Line Height', 'nx-admin'),
						'subtitle' => __('Increase text line height minimum 16 maximum 32 (default 21)','nx-admin'),
						'default'  => '24',
						'min'      => '16',
						'step'     => '1',
						'max'      => '32',
					),
					array(
						'id'       => 'menu-font-size',
						'type'     => 'spinner',
						'title'    => __('Top navigation menu Font Size', 'nx-admin'),
						'subtitle' => __('Increase top navigation bar font size, minmum 10px maximum 16px (default 13)','nx-admin'),
						'default'  => '13',
						'min'      => '10',
						'step'     => '1',
						'max'      => '16',
					),													
					array(
						'id'          => 'title-font',
						'type'        => 'typography', 
						'title'       => __('Title Font', 'nx-admin'),
						'google'      => true, 
						'font-backup' => true,
						'font-weight' => false,
						'font-style'  => false,
						'color'  => false,
						'subsets'     => false,
						'text-align'  => false,
						'font-size'   => false,
						'line-height' => false,
						'output'      => array('h1,h2,h3,h4,h5,h6,.nx-heading,.nx-service-title'),
						'units'       =>'px',
						'subtitle'    => __('Select a font for the titles', 'nx-admin'),
						'default'     => array(
							'font-family' => 'Roboto', 
							'google'      => true
						),
						
					),
                    array(
						'id'       		=> 'title-text-color',
						'type'    		=> 'color',
						'transparent'	=> false,
						'output'      => array('h1,h2,h3,h4,h5,h6,.nx-heading,.nx-service-title'),						
						'title'    		=> __('Title Text Color', 'nx-admin'),
						'subtitle' 		=> __('Pick a color for text (default: #373737).', 'nx-admin'),
						'default'  		=> '#373737',
						'validate' 		=> 'color',
					),		
										
                )
            );
			
			
            $this->sections[] = array(
                'icon' => 'el-icon-screen-alt',
                'title' => __('Custom CSS/JS', 'nx-admin'),
                'fields' => array(
					array(
						'id'       => 'custom_css',
						'type'     => 'ace_editor',
						'title'    => __('CSS Code', 'nx-admin'),
						'subtitle' => __('Paste your CSS code here.', 'nx-admin'),
						'mode'     => 'css',
						'theme'    => 'monokai',
						'desc'     => 'Add your custom css here',
						'default'  => "#example{\nmargin: 0 auto;\n}"
					),
					array(
						'id'       => 'custom_js',
						'type'     => 'ace_editor',
						'title'    => __('JS Code', 'nx-admin'),
						'subtitle' => __('Paste your JS code here.', 'nx-admin'),
						'mode'     => 'javascript',
						'theme'    => 'monokai',
						'desc'     => 'Add your custom js here without &lt;script&gt; tag around',
						'default'  => ""
					)										
					
                )
			
            );							
			
			
            $this->sections[] = array(
                'icon' => 'el-icon-credit-card',
                'title' => __('Header Options', 'nx-admin'),
                'fields' => array(

                    array(
                        'id' => 'logo-normal',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Upload Logo', 'nx-admin'),
                        'compiler' => 'true',
                        //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc' => __('Upload your logo', 'nx-admin'),
                        'subtitle' => __('Recomanded size 64px height and 260px width', 'nx-admin'),
                        //'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                    ),
					/*
                    array(
                        'id' => 'logo-retina',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Upload Retina Logo', 'nx-admin'),
                        'compiler' => 'true',
                        //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc' => __('Upload your logo for ratina devices', 'nx-admin'),
                        'subtitle' => __('Normally twice as normal size', 'nx-admin'),
                        //'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                    ),	
					*/
					array(
						'id'       => 'logo-padding',
						'type'     => 'spinner', 
						'title'    => __('Logo Padding', 'nx-admino'),
						'subtitle' => __('Adjust logo top and bottom padding, default 12px','nx-admin'),
						'default'  => '12',
						'min'      => '6',
						'step'     => '2',
						'max'      => '32',
					),									
					
					array(
						'id'       => 'nav-login-link',
						'type'     => 'switch',
						'title'    => __('Enable Login/Logout', 'nx-admin'),
						'subtitle' => __('Enable Login/Logout link in primary nav', 'nx-admin'),
						//Must provide key => value pairs for options
						'default'  => false
					),
					
					array(
						'id'       => 'nav-cart-link',
						'type'     => 'switch',
						'title'    => __('Enable Shopping Cart', 'nx-admin'),
						'subtitle' => __('Enable Woocommerce Shopping Cart link (icon with cart drop down) in primary nav', 'nx-admin'),
						//Must provide key => value pairs for options
						'default'  => false
					),
					
					array(
						'id'       => 'nav-search',
						'type'     => 'switch',
						'title'    => __('Enable Search', 'nx-admin'),
						'subtitle' => __('Enable site search in primary nav', 'nx-admin'),
						'default'  => true
					),	

					array(
						'id'       => 'narrow-titlebar',
						'type'     => 'switch',
						'title'    => __('Enable Narrow Titlebar', 'nx-admin'),
						'subtitle' => __('Reduce height of the titlebar', 'nx-admin'),
						'default'  => false
					),															
                    array(
                        'id' => 'header-style',
                        'type' => 'image_select',
                        'title' => __('Select a Header Style', 'nx-admin'),
                        'subtitle' => __('Select a predefined header layout.', 'nx-admin'),
                        'options' => array(
                            '1' => array('alt' => 'Header 1', 'img' => ReduxFramework::$_url . 'assets/headers/header-1.jpg'),
                            '2' => array('alt' => 'Header 2', 'img' => ReduxFramework::$_url . 'assets/headers/header-2.jpg'),
                            '3' => array('alt' => 'Header 3', 'img' => ReduxFramework::$_url . 'assets/headers/header-3.jpg'),
                            '4' => array('alt' => 'Header 4', 'img' => ReduxFramework::$_url . 'assets/headers/header-4.jpg'),
                            '5' => array('alt' => 'Header 5', 'img' => ReduxFramework::$_url . 'assets/headers/header-5.jpg'),							

                        ),
                        'default' => '1'
                    ),
					array(
						'id'       => 'trans-link-color',
						'type'     => 'link_color',
						'required' => array('header-style','equals','2'),
						'active'     => false,
						'title'    => __('Link Color For Transparent Menu', 'nx-admin'),
						'desc'     => __('Link color for transparent menu, for mega menu link color use mega menu settings.', 'nx-admin'),
						'output'      => array('.trans-header .site-header:not(.fixeddiv) .nav-container > ul > li > a','.trans-header .site-header:not(.fixeddiv) .header-inwrap .navbar .woocart a .genericon'),						
						'default'  => array(
							'regular'  => '#373737', 
							'hover'    => nx_theme_color(), 
							'active'   => nx_theme_color(),  
							'visited'  => '#373737'  
						)
					),
                    array(
						'id'       		=> 'search-icon-color',
						'type'    		=> 'color',
						'transparent'	=> false,
						'title'    		=> __('Search Icon Color', 'nx-admin'),
						'required' => array('header-style','equals','2'),						
						'desc' 		=> __('Pick a color for the search icon', 'nx-admin'),
						'default'  		=> '#666666',
						'output'      => array('.trans-header .site-header:not(.fixeddiv) .header-inwrap .navbar .headersearch .search-form label:before'),							
						'validate' 		=> 'color',
					),
                    array(
						'id'       		=> 'lavalamp-color',
						'type'    		=> 'color',
						'transparent'	=> false,
						'title'    		=> __('Lava Lamp Indicator and Active Link Color', 'nx-admin'),
						'required' => array('header-style','equals','2'),						
						'desc' 		=> __('Pick a color for the lavalamp indicator(under line) and active link', 'nx-admin'),
						'default'  		=> nx_theme_color(),
						'output'    => array(
												'border-bottom-color' => '.trans-header .site-header:not(.fixeddiv) .lavalamp-object',
												'color' => '.trans-header .site-header:not(.fixeddiv) .nav-container > ul .current_page_item > a,.trans-header .site-header:not(.fixeddiv) .nav-container > ul .current_page_ancestor > a,.trans-header .site-header:not(.fixeddiv) .nav-container > ul .current-menu-item > a,.trans-header .site-header:not(.fixeddiv) .nav-container > ul .current-menu-ancestor > a'											
											),
						'validate' 		=> 'color',
					),
                    array(
                        'id' => 'logo-reverse',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Upload Transparent Logo', 'nx-admin'),
                        'compiler' => 'true',
                        'required' => array('header-style','equals','2'),
                        'desc' => __('Upload your transparent logo', 'nx-admin'),
                        'subtitle' => __('Recomanded size 64px height and 260px width', 'nx-admin'),

                    ),
					array( 
						'id'       => 'trans-header-bg',
						'type'     => 'color_rgba',
						'title'    		=> __('Semi Transparent Background', 'nx-admin'),
						'subtitle' => __('Gives you the RGBA color.', 'nx-admin'),
						'desc' 		=> __('Choose a  a color for semi transparent background', 'nx-admin'),
						'required' => array('header-style','equals','2'),
						'output'      => array('.trans-header .headerwrap .header-holder .headerboxwrap'),
						'default'  => array(
							'color' => '#FFFFFF', 
							'alpha' => '0.0'
						),
						'mode'     => 'background',
					),								
					
						array(
							'id'       => 'hd-left-option',
							'type'     => 'button_set',
							'title'    => __('Header Left End Content', 'nx-admin'),
							'subtitle' => __('Switch between content type for left end of the top bar', 'nx-admin'),
							'required' => array('header-style','equals','3'),
							'options' => array(
								'1' => 'Phone and Email',
								'2' => 'Custom Text'
							 ),
							'default' => '1'
						),
						array(
							'id'       => 'hd-phone',
							'type'     => 'text',
							'title'    => __('Phone Number', 'nx-admin'),
							'subtitle' => __('Enter phone number to be displayed', 'nx-admin'),
							'required' => array('hd-left-option','equals','1'),							
							'default' => ''
						),
						array(
							'id'       => 'hd-email',
							'type'     => 'text',
							'validate' => 'email',
							'title'    => __('Email ID', 'nx-admin'),
							'subtitle' => __('Enter email ID to be displayed', 'nx-admin'),
							'required' => array('hd-left-option','equals','1'),							
							'default' => ''
						),
						array(
							'id'       => 'hd-text',
							'type'     => 'textarea',
							'rows'     => 2,
							'validate' => 'html_custom',
							'allowed_html' => array(
								'a' => array(
									'href' => array()
								),
								'i' => array(
									'class' => array()
								),								
								'span' => array(
									'style' => array(),
									'class' => array()
								),								
								'em' => array(),
								'strong' => array()
							),							
							'title'    => __('Custom text', 'nx-admin'),
							'subtitle' => __('Enter text, allowed HTML tags are &lt;span&gt;(attributes allowed is "style"), &lt;a&gt;(attributes allowed are "href" and "title"), &lt;em&gt;, &lt;strong&gt;', 'nx-admin'),
							'required' => array('hd-left-option','equals','2'),							
							'default' => ''
						),
						array(
							'id'       => 'hd-right-option',
							'type'     => 'button_set',
							'title'    => __('Header Right End Content', 'nx-admin'),
							'subtitle' => __('Switch between content type for left end of the top bar', 'nx-admin'),
							//'required' => array( array('header-style','equals',array( 3 ))),
							'required' => array('header-style','equals','3'), // Multiple values

							'options' => array(
								'1' => 'Social Links',
								'2' => 'Custom Text'
							 ),
							'default' => '1'
						),
						array(
							'id'       => 'hd-text2',
							'type'     => 'textarea',
							'rows'     => 2,
							'validate' => 'html_custom',
							'allowed_html' => array(
								'a' => array(
									'href' => array()
								),
								'i' => array(
									'class' => array()
								),								
								'span' => array(
									'style' => array(),
									'class' => array()
								),								
								'em' => array(),
								'strong' => array()
							),							
							'title'    => __('Custom text', 'nx-admin'),
							'subtitle' => __('Enter text, allowed HTML tags are &lt;span&gt;(attributes allowed is "style"), &lt;a&gt;(attributes allowed are "href" and "title"), &lt;em&gt;, &lt;strong&gt;', 'nx-admin'),
							'required' => array('hd-right-option','equals','2'),							
							'default' => ''
						),
						array(
							'id'       => 'hd-right-option2',
							'type'     => 'button_set',
							'title'    => __('Header Right End Content', 'nx-admin'),
							'subtitle' => __('Switch between content type for left end of the top bar', 'nx-admin'),
							//'required' => array( array('header-style','equals',array( 3 ))),
							'required' => array('header-style','equals','4'), // Multiple values

							'options' => array(
								'1' => 'Social Links',
								'2' => 'Custom Text'
							 ),
							'default' => '1'
						),
						array(
							'id'       => 'hd-text-right2',
							'type'     => 'textarea',
							'rows'     => 2,
							'validate' => 'html_custom',
							'allowed_html' => array(
								'a' => array(
									'href' => array()
								),
								'i' => array(
									'class' => array()
								),								
								'span' => array(
									'style' => array(),
									'class' => array()
								),								
								'em' => array(),
								'strong' => array()
							),							
							'title'    => __('Custom text', 'nx-admin'),
							'subtitle' => __('Enter text, allowed HTML tags are &lt;span&gt;(attributes allowed is "style"), &lt;a&gt;(attributes allowed are "href" and "title"), &lt;em&gt;, &lt;strong&gt;', 'nx-admin'),
							'required' => array('hd-right-option2','equals','2'),							
							'default' => ''
						),																
					
					
																				
					array(
						'id'       => 'sticky-header',
						'type'     => 'switch',
						'title'    => __('Sticky Header', 'nx-admin'),
						'subtitle' => __('Enable/disable Sticky Header', 'nx-admin'),
						//Must provide key => value pairs for options
						'default'  => true
					),
					
					array(
						'id'       => 'responsive-menu',
						'type'     => 'button_set',
						'title'    => __('Responsive Menu Style', 'nx-admin'),
						'subtitle' => __('Switch between Classic and Slide', 'nx-admin'),
						//Must provide key => value pairs for options
						'options' => array(
							'1' => 'Slide',
							'2' => 'Classic'
						 ),
						'default' => '1'
					),
																												
					array(
					   'id' => 'top-bar-section-start',
					   'type' => 'section',
					   'title' => __('Top Bar Options', 'nx-admin'),
					   'indent' => true
					),
						array(
							'id'       => 'top-bar-switch',
							'type'     => 'switch',
							'title'    => __('Enable Top Bar', 'nx-admin'),
							'subtitle' => __('Enable the top bar containing social links/contact info/additional menu', 'nx-admin'),
							'default'  => false,
						),
						array(
							'id'       => 'tb-left-option',
							'type'     => 'button_set',
							'title'    => __('Left End Content', 'nx-admin'),
							'subtitle' => __('Switch between content type for left end of the top bar', 'nx-admin'),
							//Must provide key => value pairs for options
							'options' => array(
								'1' => 'Phone and Email',
								'2' => 'Custom Text'
							 ),
							'default' => '1'
						),
						array(
							'id'       => 'tb-phone',
							'type'     => 'text',
							'title'    => __('Phone Number', 'nx-admin'),
							'subtitle' => __('Enter phone number to be displayed', 'nx-admin'),
							'required' => array('tb-left-option','equals','1'),							
							'default' => ''
						),
						array(
							'id'       => 'tb-email',
							'type'     => 'text',
							'validate' => 'email',
							'title'    => __('Email ID', 'nx-admin'),
							'subtitle' => __('Enter email ID to be displayed', 'nx-admin'),
							'required' => array('tb-left-option','equals','1'),							
							'default' => ''
						),
						array(
							'id'       => 'tb-text',
							'type'     => 'textarea',
							'rows'     => 2,
							'validate' => 'html_custom',
							'allowed_html' => array(
								'a' => array(
									'href' => array()
								),
								'i' => array(
									'class' => array()
								),								
								'span' => array(
									'style' => array(),
									'class' => array()
								),								
								'em' => array(),
								'strong' => array()
							),							
							'title'    => __('Custom text', 'nx-admin'),
							'subtitle' => __('Enter text, allowed HTML tags are &lt;span&gt;(attributes allowed is "style"), &lt;a&gt;(attributes allowed are "href" and "title"), &lt;em&gt;, &lt;strong&gt;', 'nx-admin'),
							'required' => array('tb-left-option','equals','2'),							
							'default' => ''
						),

						array(
							'id'       => 'tb-right-option',
							'type'     => 'button_set',
							'title'    => __('Right End Content', 'nx-admin'),
							'subtitle' => __('Switch between content type for left end of the top bar', 'nx-admin'),
							//Must provide key => value pairs for options
							'options' => array(
								'1' => 'Social Links',
								'2' => 'Custom Text'
							 ),
							'default' => '1'
						),
						array(
							'id'       => 'tb-text2',
							'type'     => 'textarea',
							'rows'     => 2,
							'validate' => 'html_custom',
							'allowed_html' => array(
								'a' => array(
									'href' => array()
								),
								'i' => array(
									'class' => array()
								),								
								'span' => array(
									'style' => array(),
									'class' => array()
								),								
								'em' => array(),
								'strong' => array()
							),							
							'title'    => __('Custom text', 'nx-admin'),
							'subtitle' => __('Enter text, allowed HTML tags are &lt;span&gt;(attributes allowed is "style"), &lt;a&gt;(attributes allowed are "href" and "title"), &lt;em&gt;, &lt;strong&gt;', 'nx-admin'),
							'required' => array('tb-right-option','equals','2'),							
							'default' => ''
						),
						/*
						array(
							'id'       => 'top-bar-color',
							'type'     => 'switch',
							'title'    => __('Primary Colored Background', 'nx-admin'),
							'subtitle' => __('Reverse the background color to primary color and light text color', 'nx-admin'),
							'default'  => false,
						),
						*/
						array(
							'id'       => 'top-bar-color',
							'type'     => 'button_set',
							'title'    => __('Top Bar Background', 'nx-admin'),
							'subtitle' => __('Choose a background for top bar', 'nx-admin'),
							'options' => array(
								'1' => 'Light Background',
								'2' => 'Dark Background',
								'3' => 'Primary Colored Background'								
							 ),
							'default' => '1'
						),																																														
				 
					array(
						'id'     => 'top-bar-section-end',
						'type'   => 'section',
						'indent' => false,
					),
										
                )
            );			
			
			
            $this->sections[] = array(
                'icon' => 'el-icon-list',
                'title' => __('Archive/Category/Blog Options', 'nx-admin'),
                'fields' => array(
				
                    array(
                        'id' => 'togg-title-bar',
                        'type' => 'switch',
                        'title' => __('Show/Hide Title Bar', 'nx-admin'),
                        'default' => 1,
                        'on' => 'Show',
                        'off' => 'Hide',
                    ),				
				
                    array(
                        'id' => 'togg-breadcrumb',
                        'type' => 'switch',
                        'title' => __('Show/Hide Breadcrumb', 'nx-admin'),
                        'default' => 1,
                        'on' => 'Show',
                        'off' => 'Hide',
                    ),
					
                    array(
                        'id' => 'header-text-color',
                        'type' => 'button_set',
                        'title' => __('Title Text Color', 'nx-admin'),
						'options' => array(
							'1' => 'Dark', 
							'2' => 'Light'
						 ), 
						'default' => '2',
                    ),
                    array(
                        'id' => 'header-text-alignment',
                        'type' => 'button_set',
                        'title' => __('Titele text alignment', 'nx-admin'),
						'options' => array(
							'left' => 'Left', 
							'center' => 'Center'
						 ), 
						'default' => 'left',
                    ),											
					
                    array(
                        'id' => 'header-background-type',
                        'type' => 'button_set',
                        'title' => __('Title Background Type', 'nx-admin'),
						'options' => array(
							'1' => 'Background Pattern', 
							'2' => 'Solid Background Color/Background Image'
						 ), 
						'default' => '2',
                    ),						
					
                    array(
                        'id' => 'title-background',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => __('Select Title Background', 'nx-admin'),
                        'subtitle' => __('Select a background image for title bar', 'nx-admin'),
						'output'    => array('background-image' => '.page-heading'),
                        'options' => array(
                            ReduxFramework::$_url . 'assets/img/pattern/pat1.png' => array('alt' => 'pattern 1', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt1.png'),
                            ReduxFramework::$_url . 'assets/img/pattern/pat2.png' => array('alt' => 'pattern 2', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt2.png'),
                            ReduxFramework::$_url . 'assets/img/pattern/pat3.png' => array('alt' => 'pattern 3', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt3.png'),
                            ReduxFramework::$_url . 'assets/img/pattern/pat4.png' => array('alt' => 'pattern 4', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt4.png'),
                            ReduxFramework::$_url . 'assets/img/pattern/pat5.png' => array('alt' => 'pattern 5', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt5.png'),
                            ReduxFramework::$_url . 'assets/img/pattern/pat6.png' => array('alt' => 'pattern 6', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt6.png'),
                            ReduxFramework::$_url . 'assets/img/pattern/pat7.png' => array('alt' => 'pattern 7', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt7.png'),							
                        ),						
                        'default' => '1',
						'required' => array('header-background-type','equals','1')
                    ),
					/*
                    array(
                        'id' => 'title-bg-image',
                        'type' => 'media',
                        'url' => false,
                        'title' => __('Upload an image as background', 'nx-admin'),
                        'compiler' => 'true',
                        'desc' => __('Upload custom title bar background ', 'nx-admin'),
                        'subtitle' => __('Upload an image instead of selecting a pattern above', 'nx-admin'),
                        //'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
						'required' => array('header-background-type','equals','1')
                    ),										
                    array(
                        'id' => 'archive-title-bg-repeat',
                        'type' => 'button_set',
                        'title' => __('Background Size/Repeat', 'nx-admin'),
						'desc'  => __('For fullwidth images, choose Cover. For repeating patterns, choose Repeat..', 'nx-admin'),
                        'options' => array(
							'1' => 'Repeat', 
							'2' => 'Cover' 
						 ), 
						'default' => '1',
						'required' => array('header-background-type','equals','1')
                    ),
					
                    array(
                        'id' => 'archive-title-bg-attachment',
                        'type' => 'button_set',
                        'title' => __('Background Attachment', 'nx-admin'),
						'desc'  => __('Select background attachement, "fixed" for a fixed background, "scroll" for scrolling background', 'nx-admin'),
                        'options' => array(
							'1' => 'scroll', 
							'2' => 'fixed' 
						 ), 
						'default' => '1',
						'required' => array('header-background-type','equals','1')
                    ),
										
                    array(
                        'id' => 'archive-title-bg-color',
                        'type' => 'color',
                        'title' => __('Background Color', 'nx-admin'),
						'desc'  => __('Choose a background color', 'nx-admin'),
						'default' => '#77be32',
						'required' => array('header-background-type','equals','2')
                    ),					
					*/
					
					array(         
						'id'       => 'archive-title-bg-alt',
						'type'     => 'background',
						'title'    => __('Title Background', 'nx-admin'),
						'subtitle' => __('Title background with image, color, etc.', 'nx-admin'),
						'desc'     => __('Choose your Title Background options', 'nx-admin'),
						'preview_media' => true,
						'background-position' => false,
						'output'    => array('.page-heading'),
						'required' => array('header-background-type','equals','2'),
						'default'  => array(
							'background-color' => '#77be32',
						)
					),					
					
					array(
						'id'       => 'archive-sidebar',
						'type'     => 'select',
						'title'    => __('Sidebar Settings', 'nx-admin'), 
						// Must provide key => value pairs for select options
						'options'  => array(
							'0' => 'No Sidebar',
							'1' => 'Right Sidebar',
							'2' => 'Left Sidebar'
						),
						'default'  => '0',
					),
					array(
						'id'       => 'archive-layout-type',
						'type'     => 'select',
						'title'    => __('Layout Type', 'nx-admin'), 
						// Must provide key => value pairs for select options
						'options'  => array(
							'1' => 'Standard',
							'2' => 'Masonry',
							'3' => 'Masonry - Modern'
						),
						'default'  => '1',
					),
					array(
						'id'       => 'archive-total-columns',
						'type'     => 'select',
						'title'    => __('Number of column', 'nx-admin'), 
						// Must provide key => value pairs for select options
						'options'  => array(
							'2' => '2',
							'3' => '3',
							'4' => '4'							
						),
						'default'  => '2',
						'required'  => array('archive-layout-type', "!=", 1),
					),
                    array(
                        'id' => 'archive-show-categories',
                        'type' => 'button_set',
                        'title' => __('Show/Hide Categories', 'nx-admin'),
						'options' => array(
							'1' => 'Hide', 
							'2' => 'Show'
						 ), 
						'default' => '1',
                    ),	
					
                    array(
                        'id' => 'blog-slider',
                        'type' => 'button_set',
                        'title' => __('Default Blog Slider', 'nx-admin'),
                        'subtitle' => __('Only Appears on default blog page', 'nx-admin'),						
						'options' => array(
							'1' => 'No Slider', 
							'2' => 'i-spirit Slider',
							'3' => 'Slider Revolution',
							'4' => 'Other Slider'							
						 ), 
						'default' => '2',
                    ),
					
					//'required' => array('header-style','equals','2'),	
					array(
						'id'       => 'blog-rev-slider',
						'type'     => 'select',
						'required' => array('blog-slider','equals','3'),							
                        'title' => __('Revolution slider', 'nx-admin'),
						'subtitle' => __('Select revolution slider for default blog page. ', 'nx-admin'),
						'options'   => sp_revslider_list(),
						'default'  => '',
					),
					
				    array(
                        'id' => 'blog-other-slider',
                        'type' => 'text',
						'required' => array('blog-slider','equals','4'),							
                        'title' => __('Other Slider Shortcode', 'nx-admin'),
						'subtitle' => __('Enter other "itrans Slider" shortcode or 3rd party slider shortcode.', 'nx-admin'),
                        'default' => '',
                    ),																	
																		
                )
            );			



            $this->sections[] = array(
                'icon' => 'el-icon-shopping-cart',
                'title' => __('WooCommerce Options', 'nx-admin'),
                'fields' => array(

					array(
						'id'       => 'enable-woo-bar',
						'type'     => 'switch',
						'title'    => __('Enable Woocom Bar', 'nx-admin'),
						'subtitle' => __('Enable additional woo-commerece bar on top', 'nx-admin'),
						'default'  => false
					),
					array(
						'id'       => 'enable-compare',
						'type'     => 'switch',
						'required' => array('enable-woo-bar', 'equals', '1'),
						'title'    => __('Enable Compare Products Link', 'nx-admin'),
						'subtitle' => __('Enable/disable Compare Products Link, requires "YITH WooCommerce Compare" plugin', 'nx-admin'),
						'default'  => true
					),						

					array(
					   'id' => 'woocomm-arch-section-start',
					   'type' => 'section',
					   'title' => __('Archive/listing Pages Titlebar', 'nx-admin'),
					   'indent' => true
					),
						array(
							'id' => 'woo-hide-title',
							'type' => 'switch',
							'title' => __('Show/Hide title', 'nx-admin'),
							'subtitle' => __('Optional show/hide title (hide title if you are using a slider on listing pages)', 'nx-admin'),
							'default' => 1,
							'on' => 'Show',
							'off' => 'Hide',
						),
										
						array(
							'id' => 'woo-togg-breadcrumb',
							'type' => 'switch',
							'title' => __('Show/Hide Breadcrumb', 'nx-admin'),
							'default' => 1,
							'on' => 'Show',
							'off' => 'Hide',
						),
						
						array(
							'id' => 'woo-header-text-color',
							'type' => 'button_set',
							'title' => __('Header Text Color', 'nx-admin'),
							'options' => array(
								'1' => 'Dark', 
								'2' => 'Light'
							 ), 
							'default' => '1',
						),
						array(
							'id' => 'woo-header-text-alignment',
							'type' => 'button_set',
							'title' => __('Titele text alignment', 'nx-admin'),
							'options' => array(
								'left' => 'Left', 
								'center' => 'Center'
							 ), 
							'default' => 'left',
						),						
						array(
							'id' => 'woo-header-background-type',
							'type' => 'button_set',
							'title' => __('Title Background Type', 'nx-admin'),
							'options' => array(
								'1' => 'Background Pattern/Background Image', 
								'2' => 'Solid Background Color'
							 ), 
							'default' => '2',
						),											
						array(
							'id' => 'woo-title-background',
							'type' => 'image_select',
							'compiler' => true,
							'title' => __('Select Title Background', 'nx-admin'),
							'subtitle' => __('Select a background image for title bar', 'nx-admin'),
							'options' => array(
								'1' => array('alt' => 'pattern 1', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt1.png'),
								'2' => array('alt' => 'pattern 2', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt2.png'),
								'3' => array('alt' => 'pattern 3', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt3.png'),
								'4' => array('alt' => 'pattern 4', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt4.png'),
								'5' => array('alt' => 'pattern 5', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt5.png'),
								'6' => array('alt' => 'pattern 6', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt6.png'),
								'7' => array('alt' => 'pattern 7', 'img' => ReduxFramework::$_url . 'assets/img/pattern/patt7.png'),							
							),
							'default' => '1',
							'required' => array('woo-header-background-type','equals','1')
						),
						array(
							'id' => 'woo-title-bg-image',
							'type' => 'media',
							'url' => false,
							'title' => __('Upload an image as background', 'nx-admin'),
							'compiler' => 'true',
							//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
							'desc' => __('Upload custom title bar background ', 'nx-admin'),
							'subtitle' => __('Upload an image instead of selecting a pattern above', 'nx-admin'),
							//'default' => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
							'required' => array('woo-header-background-type','equals','1')
						),										
						array(
							'id' => 'woo-archive-title-bg-repeat',
							'type' => 'button_set',
							'title' => __('Background Size/Repeat', 'nx-admin'),
							'desc'  => __('For fullwidth images, choose Cover. For repeating patterns, choose Repeat..', 'nx-admin'),
							'options' => array(
								'1' => 'Repeat', 
								'2' => 'Cover' 
							 ), 
							'default' => '1',
							'required' => array('woo-header-background-type','equals','1')
						),
						array(
							'id' => 'woo-archive-title-bg-attachment',
							'type' => 'button_set',
							'title' => __('Background Attachment', 'nx-admin'),
							'desc'  => __('Select background attachement, "fixed" for a fixed background, "scroll" for scrolling background', 'nx-admin'),
							'options' => array(
								'1' => 'scroll', 
								'2' => 'fixed' 
							 ), 
							'default' => '1',
							'required' => array('woo-header-background-type','equals','1')
						),
											
						array(
							'id' => 'woo-archive-title-bg-color',
							'type' => 'color',
							'title' => __('Background Color', 'nx-admin'),
							'desc'  => __('Choose a background color', 'nx-admin'),
							'default' => '',
							'required' => array('woo-header-background-type','equals','2')
						),

					
					array(
						'id'     => 'woocomm-arch-section-end',
						'type'   => 'section',
						'indent' => false,
					),
					// Section ends											
					
					array(
						'id'       => 'woo-archive-sidebar',
						'type'     => 'select',
						'title'    => __('Sidebar Settings', 'nx-admin'), 
						'subtitle' => __('Default Shop page and Archive/Category pages sidebar settings', 'nx-admin'),
						'options'  => array(
							'0' => 'No Sidebar',
							'1' => 'Right Sidebar',
							'2' => 'Left Sidebar'
						),
						'default'  => '0',
					),

                    array(
                        'id' => 'woo-shop-hide-title',
                        'type' => 'switch',
                        'title' => __('Show/Hide default shop page title', 'nx-admin'),
						'subtitle' => __('Optional show/hide title (hide title if you are using a slider)', 'nx-admin'),
                        'default' => 1,
                        'on' => 'Show',
                        'off' => 'Hide',
                    ),
									
                    array(
                        'id' => 'woo-slider',
                        'type' => 'button_set',
                        'title' => __('Default Shop Page Slider', 'nx-admin'),
                        'subtitle' => __('Only Appears on WooCommerce default listing page', 'nx-admin'),						
						'options' => array(
							'1' => 'No Slider', 
							'2' => 'i-spirit Theme Slider',
							'3' => 'Slider Revolution',
							'4' => 'Other Slider'							
						 ), 
						'default' => '2',
                    ),
										
					array(
						'id'       => 'woo-rev-slider',
						'type'     => 'select',
						'required' => array('woo-slider','equals','3'),						
                        'title' => __('Revolution slider (for default shop page)', 'nx-admin'),
						'subtitle' => __('Select revolution slider for the default shop page. ', 'nx-admin'),
						'options'   => sp_revslider_list(),
						'default'  => '',
					),
					
				    array(
                        'id' => 'woo-layer-slider',
                        'type' => 'text',
						'required' => array('woo-slider','equals','4'),						
                        'title' => __('Other Slider Shortcode(for default shop page)', 'nx-admin'),
						'subtitle' => __('Enter other 3rd party slider shortcode.', 'nx-admin'),
                        'default' => '',
                    ),					

					array(
						'id'       => 'woo-archive-columns',
                        'type' => 'button_set',
						'title'    => __('Number of Column', 'nx-admin'),
						'subtitle' => __('Number column in the listing pages. ', 'nx-admin'),
						'desc' =>  __('Change of "Catalog Images" size is recomanded with change of column number in WooCommerece settings. <br/>recomanded sizes are, 2 column : 600x600, 3 column : 400x400, 4 column : 300x300', 'nx-admin'),
						// Must provide key => value pairs for select options
						'options'  => array(
							'2' => '2 Column',
							'3' => '3 Column',
							'4' => '4 Column'
						),
						'default'  => '4',
					),
					
                    array(
                        'id' => 'woo-archive-style',
                        'type' => 'button_set',
                        'title' => __('Layout Style', 'nx-admin'),
						'desc'  => __('Choose layout style for archive listing/product details page', 'nx-admin'),
                        'options' => array(
							'1' => 'Default', 
							'2' => 'Modern' 
						 ), 
						'default' => '1'
                    ),	
																
                    array(
                        'id' => 'woo-infi-scroll',
                        'type' => 'button_set',
                        'title' => __('Infinite Scroll', 'nx-admin'),
						'desc'  => __('Infinite Scroll for listing and search result page', 'nx-admin'),
                        'options' => array(
							'1' => 'On', 
							'2' => 'Off' 
						 ), 
						'default' => '2'
                    ),	
										
                    array(
                        'id' => 'woo-image-zoom',
                        'type' => 'button_set',
                        'title' => __('Product Image Zoom On Hover', 'nx-admin'),
						'desc'  => __('Not required for version WooCommerce V-3.0+', 'nx-admin'),
                        'options' => array(
							'1' => 'On', 
							'2' => 'Off' 
						 ), 
						'default' => '1'
                    ),				
                )
            );			
			
			
            $this->sections[] = array(
                'icon' => 'el-icon-credit-card',
                'title' => __('Footer Options', 'nx-admin'),
                'fields' => array(
                    array(
                        'id' => 'footerlayout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => __('Footer Layout', 'nx-admin'),
                        'subtitle' => __('Select the footer column layout.', 'nx-admin'),
                        'options' => array(
                            '1' => array('alt' => '1 Column', 'img' => ReduxFramework::$_url . 'assets/img/flt-1.png'),
                            '2' => array('alt' => '2 Column Left', 'img' => ReduxFramework::$_url . 'assets/img/flt-2.png'),
                            '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/flt-3.png'),
                            '4' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url . 'assets/img/flt-4.png'),
                            '5' => array('alt' => '3 Column Left', 'img' => ReduxFramework::$_url . 'assets/img/flt-5.png')
                            //'6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/flt-6.png'),
                            //'7' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url . 'assets/img/flt-7.png'),
                            //'8' => array('alt' => '3 Column Left', 'img' => ReduxFramework::$_url . 'assets/img/flt-8.png'),
                            //'9' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/flt-9.png')							
                        ),
                        'default' => '1'
                    ),
					
                    array(
                        'id' => 'footer-bottom-right',
                        'type' => 'button_set',
                        'title' => __('Footer Bottom Right', 'nx-admin'),
						'desc'  => __('Enable footer menu or Social links or keep blank', 'nx-admin'),
                        'options' => array(
							'1' => 'Footer Menu', 
							'2' => 'Social Links',
							'3' => 'Keep It Blank'
						 ), 
						'default' => '3'
                    ),
				    array(
                        'id' => 'copy-right-text',
                        'type' => 'text',
                        'title' => __('Footer Copyright text', 'nx-admin'),
						'subtitle' => __('Enter the copyright text for the footer', 'nx-admin'),
                        'default' => 'Proudly powered by WordPress',
                    ),								
                )
            );			

            $this->sections[] = array(
                'icon' => 'el-icon-network',
                'title' => __('Social Links', 'nx-admin'),
                'fields' => array(
                    array(
                        'id' => 'twitter',
                        'type' => 'text',
                        'title' => __('Twitter', 'nx-admin'),
                        'subtitle' => __('Enter your twitter username/ID (with out @)', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'facebook',
                        'type' => 'text',
                        'title' => __('Facebook', 'nx-admin'),
                        'subtitle' => __('Enter your facebook page/profile url', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'skype',
                        'type' => 'text',
                        'title' => __('skype', 'nx-admin'),
                        'subtitle' => __('Enter your skype username/handle', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'googleplus',
                        'type' => 'text',
                        'title' => __('Google+', 'nx-admin'),
                        'subtitle' => __('Enter your Google+ page/profile URL', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'flickr',
                        'type' => 'text',
                        'title' => __('Flickr', 'nx-admin'),
                        'subtitle' => __('Enter your flickr page URL', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'youtube',
                        'type' => 'text',
                        'title' => __('Youtube', 'nx-admin'),
                        'subtitle' => __('Enter your youtube page URL', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'instagram',
                        'type' => 'text',
                        'title' => __('Instagram', 'nx-admin'),
                        'subtitle' => __('Enter your instagram username', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'pinterest',
                        'type' => 'text',
                        'title' => __('Pinterest', 'nx-admin'),
                        'subtitle' => __('Enter your instagram username', 'nx-admin'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'linkedin',
                        'type' => 'text',
                        'title' => __('LinkedIn', 'nx-admin'),
                        'subtitle' => __('Enter your LinkedIn page/profile URL', 'nx-admin'),
                        'default' => ''
                    ),														

                )
            );
			
			/**/
            $this->sections[] = array(
                'icon' => 'el-icon-picture',
                'title' => __('i-spirit Slider', 'nx-admin'),
                'fields' => array(
				

					array(
						'id'       => 'slider-parallax',
						'type'     => 'switch', 
						'title'    => __('Parallax Effect', 'nx-admin'),
						'subtitle' => __('Turn ON/OFF parallax effect', 'nx-admin'),
						'default'  => true,
					),	
					array(
						'id'       => 'slider-overlay',
						'type'     => 'switch', 
						'title'    => __('Slider Pattern Overlay', 'nx-admin'),
						'subtitle' => __('Turn ON/OFF pattern overlay layer', 'nx-admin'),
						'default'  => true,
					),
					
					array(
						'id'        => 'slider-overlay-color',
						'type'      => 'color_rgba',
						'title'     => 'Slider Overlay Color',
						'subtitle'  => 'Select an overlay color with transparency.',
				 
						// See Notes below about these lines.
						'output'    => array('background-color' => '.ispirit-slider .ispirit-slider-box .ispirit-slide-content'),
						//'compiler'  => array('color' => '.site-header, .site-footer', 'background-color' => '.nav-bar'),
						'default'   => array(
							'color'     => '#323232',
							'alpha'     => .2
						),
					 
						// These options display a fully functional color palette.  Omit this argument
						// for the minimal color picker, and change as desired.
						'options'       => array(
							'show_input'                => true,
							'show_initial'              => true,
							'show_alpha'                => true,
							'show_palette'              => true,
							'show_palette_only'         => false,
							'show_selection_palette'    => true,
							'max_palette_size'          => 10,
							'allow_empty'               => true,
							'clickout_fires_change'     => false,
							'choose_text'               => 'Choose',
							'cancel_text'               => 'Cancel',
							'show_buttons'              => true,
							'use_extended_classes'      => true,
							'palette'                   => null,  // show default
							'input_text'                => 'Select Color'
						),                        
					),					
					
                    array(
                        'id' => 'slider-align',
                        'type' => 'button_set',
                        'title' => __('Text alignment', 'nx-admin'),
						'subtitle'  => __('Text/Content alignment on the slides', 'nx-admin'),
                        'options' => array(
							'left' => 'left', 
							'center' => 'center',
							'right' => 'right'
						 ), 
						'default' => 'left'
                    ),
					array(
						'id'       => 'slider-transition',
						'type'     => 'select',
						'title'    => __('Slide Transition', 'nx-admin'), 
						'subtitle' => __('Select a transition effect', 'nx-admin'),
						'options'  => array(
								'slide' => __( 'Slide', 'nx-admin' ),
								'fade' => __( 'Fade', 'nx-admin' ),
								'backSlide' => __( 'Back Slide', 'nx-admin' ),
								'goDown' => __( 'Go Down', 'nx-admin' ),
								'fadeUp' => __( 'Fade Up', 'nx-admin' ),
						),
						'default'  => 'slide',
					),					
					array(
						'id'        => 'slider-speed',
						'type'      => 'slider',
						'title'     => __('Slide Duration', 'nx-admin'),
						'subtitle'  => __('Slide visibility in seconds.', 'nx-admin'),
						"default"   => 8,
						"min"       => 1,
						"step"      => 1,
						"max"       => 20,
						'display_value' => 'label'
					),					
                    array(
						'id'        => 'slider-height',
						'type'      => 'slider',
						'title'     => __('Slider Height', 'nx-admin'),
						'subtitle'  => __('Slider height propertional to screen or slider width. min 200, max 1000', 'nx-admin'),
						"default"   => 480,
						"min"       => 200,
						"step"      => 10,
						"max"       => 1000,
						'display_value' => 'label'
					),															
									
					
					/*slide 1 starts */			
					array(
					   'id' => 'slide1-start',
					   'type' => 'section',
					   'title' => __('Slide 1', 'nx-admin'),
					   'indent' => true 
				   	),
					
						array(
							'id'       => 'slide1-image',
							'type'     => 'media', 
							'url'      => true,
							'title'    => __('Slide Image w/ URL', 'nx-admin'),
							'desc'     => __('Upload slide image, 1920x1080 recomendad', 'nx-admin'),
							'default'  => array(
								'url'=> get_template_directory_uri().'/images/slides/slide1.jpg'
							),
						),					
						array(
							'id' => 'slide1-title',
							'type' => 'text',
							'title' => __('Slide Title', 'nx-admin'),
							'default' => 'Welcome To i-spirit'
						),
						array(
							'id' => 'slide1-subtitle',
							'type' => 'text',
							'title' => __('Slide Sub-Title', 'nx-admin'),
							'default' => 'Start setting up your theme and this slider from menu "i-spirit Options"'
						),	
						array(
							'id' => 'slide1-linktext',
							'type' => 'text',
							'title' => __('Slide Link Text', 'nx-admin'),
							'default' => 'Know More..'
						),	
						array(
							'id' => 'slide1-linkurl',
							'type' => 'text',
							'title' => __('Slide Link URL', 'nx-admin'),
							'default' => 'http://templatesnext.org/ispirit/landing/start-up-guide/'
						),																		
					
					array(
						'id'     => 'slide1-end',
						'type'   => 'section',
						'indent' => false,
					),	
					
					/* Slide 2 starts */
					array(
					   'id' => 'slide2-start',
					   'type' => 'section',
					   'title' => __('Slide 2', 'nx-admin'),
					   'indent' => true 
				   	),
					
						array(
							'id'       => 'slide2-image',
							'type'     => 'media', 
							'url'      => true,
							'title'    => __('Slide Image w/ URL', 'nx-admin'),
							'desc'     => __('Upload slide image, 1920x1080 recomendad', 'nx-admin'),
							'default'  => array(
								'url'=> get_template_directory_uri().'/images/slides/slide2.jpg'
							),
						),					
						array(
							'id' => 'slide2-title',
							'type' => 'text',
							'title' => __('Slide Title', 'nx-admin'),
							'default' => 'Premium Plugin Slider Revolution and Visual Composer'
						),
						array(
							'id' => 'slide2-subtitle',
							'type' => 'text',
							'title' => __('Slide Sub-Title', 'nx-admin'),
							'default' => 'Install and activate the recommended pre-packaged plugins from menu "Appearance" - "Install Plugins"'
						),	
						array(
							'id' => 'slide2-linktext',
							'type' => 'text',
							'title' => __('Slide Link Text', 'nx-admin'),
							'default' => 'Know More..'
						),	
						array(
							'id' => 'slide2-linkurl',
							'type' => 'text',
							'title' => __('Slide Link URL', 'nx-admin'),
							'default' => 'http://templatesnext.org/ispirit/landing/start-up-guide/'
						),																		
					
					array(
						'id'     => 'slide2-end',
						'type'   => 'section',
						'indent' => false,
					),	
					
					/* Slide 3 starts */
					array(
					   'id' => 'slide3-start',
					   'type' => 'section',
					   'title' => __('Slide 3', 'nx-admin'),
					   'indent' => true 
				   	),
					
						array(
							'id'       => 'slide3-image',
							'type'     => 'media', 
							'url'      => true,
							'title'    => __('Slide Image w/ URL', 'nx-admin'),
							'desc'     => __('Upload slide image, 1920x1080 recomendad', 'nx-admin'),
							'default'  => array(
								'url'=> get_template_directory_uri().'/images/slides/slide3.jpg'
							),
						),					
						array(
							'id' => 'slide3-title',
							'type' => 'text',
							'title' => __('Slide Title', 'nx-admin'),
							'default' => 'Portfolio, Team, Testimonials, itrans Slider, etc.'
						),
						array(
							'id' => 'slide3-subtitle',
							'type' => 'text',
							'title' => __('Slide Sub-Title', 'nx-admin'),
							'default' => 'Use the "Insert Shortcode" button to create your sections on a page.'
						),	
						array(
							'id' => 'slide3-linktext',
							'type' => 'text',
							'title' => __('Slide Link Text', 'nx-admin'),
							'default' => 'Know More..'
						),	
						array(
							'id' => 'slide3-linkurl',
							'type' => 'text',
							'title' => __('Slide Link URL', 'nx-admin'),
							'default' => 'https://www.youtube.com/watch?v=BbuTqZS3MpI'
						),																		
					
					array(
						'id'     => 'slide3-end',
						'type'   => 'section',
						'indent' => false,
					),	
					
					/* Slide 4 starts */
					array(
					   'id' => 'slide4-start',
					   'type' => 'section',
					   'title' => __('Slide 4', 'nx-admin'),
					   'indent' => true 
				   	),
					
						array(
							'id'       => 'slide4-image',
							'type'     => 'media', 
							'url'      => true,
							'title'    => __('Slide Image w/ URL', 'nx-admin'),
							'desc'     => __('Upload slide image, 1920x1080 recomendad', 'nx-admin'),
							'default'  => array(
								'url'=> get_template_directory_uri().'/images/slides/slide4.jpg'
							),
						),					
						array(
							'id' => 'slide4-title',
							'type' => 'text',
							'title' => __('Slide Title', 'nx-admin'),
							'default' => 'Happy To Help'
						),
						array(
							'id' => 'slide4-subtitle',
							'type' => 'text',
							'title' => __('Slide Sub-Title', 'nx-admin'),
							'default' => 'Reach us for any help, we would love to help you out.'
						),	
						array(
							'id' => 'slide4-linktext',
							'type' => 'text',
							'title' => __('Slide Link Text', 'nx-admin'),
							'default' => ''
						),	
						array(
							'id' => 'slide4-linkurl',
							'type' => 'text',
							'title' => __('Slide Link URL', 'nx-admin'),
							'default' => ''
						),																		
					
					array(
						'id'     => 'slide4-end',
						'type'   => 'section',
						'indent' => false,
					),	
					
					/* Slide 5 starts */
					array(
					   'id' => 'slide5-start',
					   'type' => 'section',
					   'title' => __('Slide 5', 'nx-admin'),
					   'indent' => true 
				   	),
					
						array(
							'id'       => 'slide5-image',
							'type'     => 'media', 
							'url'      => true,
							'title'    => __('Slide Image w/ URL', 'nx-admin'),
							'desc'     => __('Upload slide image, 1920x1080 recomendad', 'nx-admin'),
						),					
						array(
							'id' => 'slide5-title',
							'type' => 'text',
							'title' => __('Slide Title', 'nx-admin'),
							'default' => ''
						),
						array(
							'id' => 'slide5-subtitle',
							'type' => 'text',
							'title' => __('Slide Sub-Title', 'nx-admin'),
							'default' => ''
						),	
						array(
							'id' => 'slide5-linktext',
							'type' => 'text',
							'title' => __('Slide Link Text', 'nx-admin'),
							'default' => ''
						),	
						array(
							'id' => 'slide5-linkurl',
							'type' => 'text',
							'title' => __('Slide Link URL', 'nx-admin'),
							'default' => ''
						),																		
					
					array(
						'id'     => 'slide5-end',
						'type'   => 'section',
						'indent' => false,
					),	
					
					/* Slide 6 starts */
					array(
					   'id' => 'slide6-start',
					   'type' => 'section',
					   'title' => __('Slide 6', 'nx-admin'),
					   'indent' => true 
				   	),
					
						array(
							'id'       => 'slide6-image',
							'type'     => 'media', 
							'url'      => true,
							'title'    => __('Slide Image w/ URL', 'nx-admin'),
							'desc'     => __('Upload slide image, 1920x1080 recomendad', 'nx-admin'),
						),					
						array(
							'id' => 'slide6-title',
							'type' => 'text',
							'title' => __('Slide Title', 'nx-admin'),
							'default' => ''
						),
						array(
							'id' => 'slide6-subtitle',
							'type' => 'text',
							'title' => __('Slide Sub-Title', 'nx-admin'),
							'default' => ''
						),	
						array(
							'id' => 'slide6-linktext',
							'type' => 'text',
							'title' => __('Slide Link Text', 'nx-admin'),
							'default' => ''
						),	
						array(
							'id' => 'slide6-linkurl',
							'type' => 'text',
							'title' => __('Slide Link URL', 'nx-admin'),
							'default' => ''
						),																		
					
					array(
						'id'     => 'slide6-end',
						'type'   => 'section',
						'indent' => false,
					),																														
					
                )
            );
			
			
			/*
			$this->sections[] = array(
				'title' => __('Repeater Field', 'nx-admin' ),
				'icon' => 'el-icon-thumbs-up',
				'fields' => array(
					array(
						'id'         => 'repeater-field-id',
						'type'       => 'repeater',
						'title'      => __( 'Title', 'nx-admin' ),
						'subtitle'   => __( '', 'nx-admin' ),
						'desc'       => __( '', 'nx-admin' ),
						'group_values' => true, // Group all fields below within the repeater ID
						//'item_name' => '', // Add a repeater block name to the Add and Delete buttons
						//'bind_title' => '', // Bind the repeater block title to this field ID
						//'static'     => 2, // Set the number of repeater blocks to be output
						//'limit' => 2, // Limit the number of repeater blocks a user can create
						//'sortable' => false, // Allow the users to sort the repeater blocks or not
						'fields'     => array(
							array(
								'id'          => 'title_field',
								'type'        => 'text',
								'placeholder' => __( 'Title', 'nx-admin' ),
							),
							array(
								'id'          => 'text_field',
								'type'        => 'text',
								'placeholder' => __( 'Text Field', 'nx-admin' ),
							),
							array(
								'id'          => 'select_field',
								'type'        => 'select',
								'title' => __( 'Select Field', 'nx-admin' ),
								'options'     => array(
									'1'             => __( 'Option 1', 'nx-admin' ),
									'2'             => __( 'Option 2', 'nx-admin' ),
									'3'             => __( 'Option 3', 'nx-admin' ),
								),
								'placeholder' => __( 'Listing Field', 'nx-admin' ),
							),
						)
					)
				)
			);							
			*/
            $this->sections[] = array(
                'icon' => 'el-icon-resize-small',
                'title' => __('i-spirit Settings Import/Export', 'nx-admin'),
                'fields' => array(
					array(
						'id'            => 'opt-import-export',
						'type'          => 'import_export',
						'title'         => 'Import Export',
						'subtitle'      => 'Save and restore your Redux options',
						'full_width'    => false,
					),

                )
            );


            $theme_info = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'nx-admin') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'nx-admin') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'nx-admin') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'nx-admin') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon' => 'el-icon-list-alt',
                    'title' => __('Documentation', 'nx-admin'),
                    'fields' => array(
                        array(
                            'id' => '17',
                            'type' => 'raw',
                            'markdown' => true,
                            'content' => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }//if


            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon' => 'el-icon-info-sign',
                'title' => __('Theme Information', 'nx-admin'),
                'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nx-admin'),
                'fields' => array(
                    array(
                        'id' => 'raw_new_info',
                        'type' => 'raw',
                        'content' => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon' => 'el-icon-book',
                    'title' => __('Documentation', 'nx-admin'),
                    'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-1',
                'title' => __('Theme Information 1', 'nx-admin'),
                'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'nx-admin')
            );

            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-2',
                'title' => __('Theme Information 2', 'nx-admin'),
                'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'nx-admin')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'nx-admin');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'ispirit_data', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name').' Options', // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'), // Version that appears at the top of your panel
                'menu_type' => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true, // Show the sections below the admin menu item or not
                'menu_title' => __('i-spirit Options', 'ispirit'),
                'page_title' => __('i-spirit Options', 'ispirit'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'AIzaSyBJvz4x4poyXkZVjZtdfiyc1p8WNOsFzIg', // Must be defined to add google fonts to the typography module
                //'admin_bar' => false, // Show the panel pages on the admin bar
                'global_variable' => '', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
                'customizer' => true, // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon' => '', // Specify a custom URL to an icon
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => true, // REMOVE
                'system_info' => false, // REMOVE
                'help_tabs' => array(),
                'help_sidebar' => '', // __( '', $this->args['domain'] );            
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.		
            $this->args['share_icons'][] = array(
                'url' => 'https://www.facebook.com/templatesnext',
                'title' => 'Like us on Facebook',
                'icon' => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://twitter.com/templatesnext',
                'title' => 'Follow us on Twitter',
                'icon' => 'el-icon-twitter'
            );



            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace("-", "_", $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('', 'ispirit'), $v);
            } else {
                $this->args['intro_text'] = __('', 'ispirit');
            }

            // Add content after the form.
            $this->args['footer_text'] = __('', 'ispirit');
        }

    }

    new ispirit_options_config();
}


/**

  Custom function for the callback referenced above

 */
if (!function_exists('redux_my_custom_field')):

    function redux_my_custom_field($field, $value) {
        print_r($field);
        print_r($value);
    }

endif;

/**

  Custom function for the callback validation referenced above

 * */
if (!function_exists('redux_validate_callback_function')):

    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';
        /*
          do your validation

          if(something) {
          $value = $value;
          } elseif(something else) {
          $error = true;
          $value = $existing_value;
          $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;



function sp_revslider_list ()
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


function sp_layerslider_list ()
{
	global $wpdb;
	$slides_array[0] = 'Select a slider';
	// Table name
	$table_name = $wpdb->prefix . "layerslider";
	
	// Get sliders
	$sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC" );
		
	if(!empty($sliders)):
		foreach($sliders as $key => $item):
			$slides[$item->id] = '#'.$item->id." - ".$item->name;
		endforeach;
	endif;
	return $slides;
}

function nx_theme_color() {
	global  $ispirit_data;
	
	$nx_primary_color = '#77bd32';
	
	if (!empty($ispirit_data['primary-color']))
	{
		$nx_primary_color = $ispirit_data['primary-color'];
	}
	
	return $nx_primary_color;
	
}