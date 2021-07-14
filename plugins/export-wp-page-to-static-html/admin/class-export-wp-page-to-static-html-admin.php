<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/admin
 * @author     ReCorp <rayhankabir1000@gmail.com>
 */

ini_set('max_execution_time', 600);
ini_set('memory_limit','1024M');

class Export_Wp_Page_To_Static_Html_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


		add_action('admin_menu', array($this, 'register_export_wp_pages_menu') );
		add_action('wp_print_scripts', array( $this, 'rc_cdata_inlice_Script_for_export_html' ));


		add_action('wp_ajax_rc_export_wp_page_to_static_html', array( $this, 'rc_export_wp_page_to_static_html' ));
		add_action('wp_ajax_nopriv_rc_export_wp_page_to_static_html', array( $this, 'rc_export_wp_page_to_static_html' ));

		add_action('wp_ajax_get_exporting_logs', array( $this, 'get_exporting_logs' ));
		add_action('wp_ajax_nopriv_get_exporting_logs', array( $this, 'get_exporting_logs' ));


		add_action('wp_ajax_create_the_zip_file', array( $this, 'create_the_zip_file' ));
		add_action('wp_ajax_nopriv_create_the_zip_file', array( $this, 'create_the_zip_file' ));

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Export_Wp_Page_To_Static_Html_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Export_Wp_Page_To_Static_Html_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/export-wp-page-to-static-html-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'ewppth_select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), '4.0.5', 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Export_Wp_Page_To_Static_Html_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Export_Wp_Page_To_Static_Html_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/export-wp-page-to-static-html-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'ewppth_select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), '4.0.5', false );

	}


	public function register_export_wp_pages_menu(){

		add_submenu_page(        
			'options-general.php',
			'Export WP Page to Static HTML/CSS',
			__('Export WP Page to Static HTML/CSS', 'different-menus'),
			'manage_options',
			'export-wp-page-to-html',
			array(
				$this,
				'load_admin_dependencies'
			)
		);

		add_action('admin_init', array( $this,'register_export_wp_pages_settings') );
	}

	public function load_admin_dependencies(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/export-wp-page-to-static-html-admin-display.php';

	}

	public function register_export_wp_pages_settings(){
		register_setting('export_wp_pages_settings', 'recorp_ewpp_settings');
	}

	public function rc_cdata_inlice_Script_for_export_html() { 	
		?>
		<script>
			/* <![CDATA[ */
			var rcewpp = {
				"ajax_url":"<?php echo admin_url('admin-ajax.php'); ?>",
				"nonce": "<?php echo wp_create_nonce( 'rc-nonce' ); ?>",
				"home_url": "<?php echo home_url(); ?>"
			};
			/* ]]\> */
		</script>
		<?php
	}


	public function rc_export_wp_page_to_static_html(){
		$page_id = isset($_POST['page_id']) ? sanitize_key($_POST['page_id']) : "";
		$replace_urls = isset($_POST['replace_urls']) && sanitize_key($_POST['replace_urls']) == "true" ? true : false;
		$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}



		$response = $this->export_wp_page_as_static_html_by_page_id($page_id, $replace_urls);
		
		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));
	
		die();
	}

	public function get_string_between($string, $start, $end){
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0) return '';
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}

	public function rmdir_recursive($dir) {
	    foreach(scandir($dir) as $file) {
	        if ('.' === $file || '..' === $file) continue;
	        if (is_dir("$dir/$file")) $this->rmdir_recursive("$dir/$file");
	        else unlink("$dir/$file");
	    }
	    rmdir($dir);
	}


	public function export_wp_page_as_static_html_by_page_id($page_id = 0, $replace_urls = false){

		$main_url = get_permalink($page_id);

		$parse_url = parse_url($main_url);
		$scheme = $parse_url['scheme'];
		$host = $scheme . '://' . $parse_url['host'];


		if ($this->update_export_log($main_url, 'reading', '')) {
			$src = file_get_contents($main_url);
		}
		

		preg_match_all("/(?<=\<link rel='stylesheet|\<link rel=\"stylesheet).*?(?=\>)/",$src,$matches);
		preg_match_all("/(?<=\<link rel='shortcut icon'|\<link rel=\"shortcut icon\").*?(?=\>)/",$src,$matches_icons);
		preg_match_all("/(?<=\<meta name='thumbnail'|\<meta name=\"thumbnail\").*?(?=\>)/",$src,$meta_images);
		preg_match_all("/(?<=\<meta property='og:image'|\<meta property=\"og:image\").*?(?=\>)/",$src,$og_image);
		preg_match_all("/(?<=\<script).*?(?=\<\/script\>)/",$src,$matches_scripts);
		preg_match_all("/(?<=\<img).*?(?=\/\>)/",$src,$matches_images);

		$upload_dir = wp_upload_dir()['basedir'];

		if (!file_exists($upload_dir . '/exported_html_files')) {
			mkdir($upload_dir . '/exported_html_files');
		}

		if (!file_exists($upload_dir . '/exported_html_files/tmp_files')) {
			mkdir($upload_dir . '/exported_html_files/tmp_files');
		} else {
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');
			mkdir($upload_dir . '/exported_html_files/tmp_files');
		}
		
		$pathname_css = $upload_dir . '/exported_html_files/tmp_files/css/';
		$pathname_fonts = $upload_dir . '/exported_html_files/tmp_files/fonts/';
		$pathname_js = $upload_dir . '/exported_html_files/tmp_files/js/';
		$pathname_images = $upload_dir . '/exported_html_files/tmp_files/images/';


		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/css')) {

			if ($this->update_export_log('', 'creating', 'CSS Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/css');
			}
		}
		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/fonts')) {
			if ($this->update_export_log('', 'creating', 'Fonts Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/fonts');
			}
		}
		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/js')) {
			if ($this->update_export_log('', 'creating', 'JS Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/js');
			}
		}
		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/images')) {
			if ($this->update_export_log('', 'creating', 'Images Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/images');
			}
		}

		foreach ((array)$matches[0] as $key => $stylesheet) {

			if (strpos($stylesheet, 'href="') !== false) {
				$stylesheet_url = $this->get_string_between($stylesheet, 'href="', '"');
			} else {
				$stylesheet_url = $this->get_string_between($stylesheet, 'href=\'', '\'');
			}
			

			if (strpos( $stylesheet_url, '//') !== false) {
				if (strpos( $stylesheet_url, 'http') !== false) {	
					if ( /*!(strpos($stylesheet_url, 'gstatic') !== false)  && !(strpos($stylesheet_url, 'googleapis') !== false) && */$this->update_export_log($stylesheet_url, 'copying', '')) {
						$data = file_get_contents($stylesheet_url);
					}
					
				} else {	
					if ($this->update_export_log($scheme . ':' . $stylesheet_url, 'copying', '')) {
						$data = file_get_contents($scheme . ':' . $stylesheet_url);
					}
				}
				
			}
			else {
				if ($this->update_export_log($host . $stylesheet_url, 'copying', '')) {
					$data = file_get_contents($host . $stylesheet_url);
				}
			}
			

			preg_match_all("/(?<=url\().*?(?=\))/", $data, $matches_links);

			foreach ($matches_links as $key => $value) {
				foreach ($value as $key => $value2) {

					if ( strpos($value2, './') !== false || strpos($value2, '../') !== false ) {
						$item_url = $value2;

						$item_url_ = explode('/', $stylesheet_url);

						if (count($item_url_) > 0) {

							$item_url_value = explode('../', $value2);

							for ($i=0; $i < count($item_url_value); $i++) { 
								$last_key = count($item_url_)-1;
								unset($item_url_[$last_key]);
							}
							
						}
						$item_url_ = implode('/', $item_url_);
						$backend_file_url = str_replace(array('../', './'), array('', ''), $value2);
						$backend_file_url_full = $item_url_ . '/' . $backend_file_url;

						$url_basename = explode('?', basename($item_url));

						if ( (strpos($item_url, 'eot') !== false || strpos($item_url, 'woff') !== false || strpos($item_url, 'ttf') !== false) ) {
							$my_file = $pathname_fonts . $url_basename[0];
							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);
						} 

						if (strpos($item_url, 'png') !== false || strpos($item_url, 'jpg') !== false || strpos($item_url, 'jpeg') !== false || strpos($item_url, 'svg') !== false || strpos($item_url, 'gif') !== false || strpos($item_url, 'bmp') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}

						$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

						if ($this->update_export_log($backend_file_url_full)) {
							$item_data = file_get_contents($backend_file_url_full);
						}
						
						fwrite($handle, $item_data);
						fclose($handle);

						

					} elseif ( strpos($value2, '//') !== false && !(strpos($value2, 'http') !== false) && !(strpos($value2, 'data:') !== false) /*&& !(strpos($value2, 'gstatic') !== false)  && !(strpos($value2, 'googleapis') !== false)*/ ) {

						$item_url2 = $scheme . ':' . $value2;
						$url_basename = explode('?', basename($item_url2));

						if ( strpos($value2, 'eot') !== false || strpos($value2, 'woff') !== false || strpos($value2, 'ttf') !== false ) {
							$my_file = $pathname_fonts . $url_basename[0];
							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);
						} 

						if (strpos($value2, 'png') !== false || strpos($value2, 'jpg') !== false || strpos($value2, 'jpeg') !== false || strpos($value2, 'svg') !== false || strpos($value2, 'gif') !== false || strpos($value2, 'bmp') !== false || strpos($value2, 'ico') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}

						$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
						if ($this->update_export_log($item_url2)) {
							$item_data = file_get_contents($item_url2);
						}
						fwrite($handle, $item_data);
						fclose($handle);
					} 

					elseif ( (strpos($value2, 'http') !== false) && !(strpos($value2, 'data:') !== false) /* && !(strpos($value2, 'gstatic') !== false)  && !(strpos($value2, 'googleapis') !== false)*/ ) {

						$item_url2 = $value2;
						$url_basename = explode('?', basename($item_url2));

						if ( strpos($value2, 'eot') !== false || strpos($value2, 'woff') !== false || strpos($value2, 'ttf') !== false ) {
							$my_file = $pathname_fonts . $url_basename[0];
							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);

						} 

						if (strpos($value2, 'png') !== false || strpos($value2, 'jpg') !== false || strpos($value2, 'jpeg') !== false || strpos($value2, 'svg') !== false || strpos($value2, 'gif') !== false || strpos($value2, 'bmp') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}


						$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
						if ($this->update_export_log($item_url2)) {
							$item_data = file_get_contents($item_url2);
						}
						fwrite($handle, $item_data);
						fclose($handle);
					} 

					elseif ( !(strpos($value2, 'http') !== false) && !(strpos($value2, 'data:') !== false) /*&& !(strpos($value2, 'gstatic') !== false)  && !(strpos($value2, 'googleapis') !== false)*/ ) {


						$item_url = $value2;
						$url_basename = explode('?', basename($item_url));
						$url_basename = explode('#', $url_basename[0] );
						if (!file_exists($pathname_images . $url_basename[0])) {

						$item_url_ = explode('/', $stylesheet_url);
						$last = count($item_url_)-1;
						unset($item_url_[$last]);
						$last = count($item_url_)-1;
						//unset($item_url_[$last]);


						$item_url_ = implode('/', $item_url_);
						$backend_file_url = $value2;
						$backend_file_url_full = $item_url_ . '/' . $backend_file_url;


						if ( (strpos($item_url, 'eot') !== false || strpos($item_url, 'woff') !== false || strpos($item_url, 'ttf') !== false) ) {
							$my_file = $pathname_fonts . $url_basename[0];
							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);
						} 

						if (strpos($item_url, 'png') !== false || strpos($item_url, 'jpg') !== false || strpos($item_url, 'jpeg') !== false || strpos($item_url, 'svg') !== false || strpos($item_url, 'gif') !== false || strpos($item_url, 'bmp') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}

						$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

						if ($this->update_export_log($backend_file_url_full)) {
							$item_data = file_get_contents($backend_file_url_full);
						}
						
						fwrite($handle, $item_data);
						fclose($handle);

						}
						
					}
				}
			}



			$basename = explode('?', basename($stylesheet_url));

			if (strpos( $basename[0], ".css") == false) {
				$basename[0] = rand(5000, 9999) . ".css";
			}

			$my_file = $pathname_css . $basename[0];
			$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
			
			$data = $data . "\n/*This file was exported by \"Export WP Page to Static HTML\" plugin which created by ReCorp (https://myrecorp.com) */";
			fwrite($handle, $data);
			fclose($handle);


			if (strpos($stylesheet, 'href="') !== false) {
				$src = str_replace($stylesheet, '" href="css/' . $basename[0] . '"', $src);
			} else {
				$src = str_replace($stylesheet, '\' href=\'css/' . $basename[0] . '\'', $src);
			}
		}

		/*Export js*/
		foreach ((array) $matches_scripts[0] as $key => $script) {
			if ( strpos($script, 'src') !== false) {

				if (strpos($script, '"') !== false ) {
					$script_url = $this->get_string_between($script, 'src="', '"');
				} else {
					$script_url = $this->get_string_between($script, 'src=\'', '\'');
				}


				if (strpos( $script_url, '//') !== false) {
					if (strpos( $script_url, 'http') !== false) {
						if ($this->update_export_log($script_url)) {
							$data = file_get_contents($script_url);
						}
					} else {
						if ($this->update_export_log($scheme . ':' . $script_url)) {
							$data = file_get_contents( $scheme . ':' . $script_url );
						}
					}
					
				}
				else {
					if ($this->update_export_log($host . $script_url)) {
						$data = file_get_contents($host . $script_url);
					}
				}
			

				$basename = explode('?', basename($script_url));

				if ( !(strpos( $basename[0], ".") !== false )) {
					$basename[0] = rand(5000, 9999) . ".js";
				}

				$my_file = $pathname_js . $basename[0];
				$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

				$data = $data . "\n/*This file was exported by \"Export WP Page to Static HTML\" plugin which created by ReCorp (https://myrecorp.com) */";
				fwrite($handle, $data);
				fclose($handle);

				$src = str_replace($script_url, 'js/' . $basename[0], $src);

			}
		}


		/*Export images*/
		foreach ((array) $matches_images[0] as $key => $image) {
			if ( strpos($image, 'src') !== false) {
				$img_src = $this->get_string_between($image, 'src="', '"');
				$basename = explode('?', basename($img_src));

				if ( !(strpos( $img_src, 'data:') !== false) ) {
					if (strpos( $img_src, '//') !== false) {
						if (strpos( $img_src, 'http') !== false) {
							if ($this->update_export_log($img_src)) {
								$data = file_get_contents($img_src);
							}

						} else {		
							if ($this->update_export_log($scheme . ':' . $img_src)) {
								$data = file_get_contents( $scheme . ':' . $img_src );
							}
						}
						
					}
					else {		
						if ($this->update_export_log($host . $img_src)) {
							$data = file_get_contents($host . $img_src);
						}
					}
				}
			


				if (strpos( $basename[0], ".") == false) {
					$basename[0] = rand(5000, 9999) . ".jpg";
				}

				$my_file = $pathname_images . $basename[0];
				$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
				
				fwrite($handle, $data);
				fclose($handle);
				$src = str_replace($img_src, 'images/' . str_replace(' ', '', $basename[0]), $src);
			}
		}

		/*Export meta images*/
		foreach ((array) $matches_icons[0] as $key => $image) {
			if ( strpos($image, 'href') !== false) {
				$img_src = $this->get_string_between($image, 'href="', '"');
				$basename = explode('?', basename($img_src));

				if ( !(strpos( $img_src, 'data:') !== false) ) {
					if (strpos( $img_src, '//') !== false) {
						if (strpos( $img_src, 'http') !== false) {		
							if ($this->update_export_log($img_src)) {
								$data = file_get_contents($img_src);
							}
						} else {	
							if ($this->update_export_log($scheme . ':' . $img_src)) {
								$data = file_get_contents( $scheme . ':' . $img_src );
							}
						}
						
					}
					else {	
						if (is_file($host . $img_src) && $this->update_export_log($host . $img_src)) {
							$data = file_get_contents($host . $img_src);
						}
					}
				}
			


				if (strpos( $basename[0], ".") == false) {
					$basename[0] = rand(5000, 9999) . ".jpg";
				}

				$my_file = $pathname_images . $basename[0];
				$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
				
				fwrite($handle, $data);
				fclose($handle);
				$src = str_replace($img_src, 'images/' . str_replace(' ', '', $basename[0]), $src);
			}
		}


		foreach ((array) $meta_images[0] as $key => $image) {
			if ( strpos($image, 'content') !== false) {
				$img_src = $this->get_string_between($image, 'content="', '"');
				$basename = explode('?', basename($img_src));

				if ( !(strpos( $img_src, 'data:') !== false) ) {
					if (strpos( $img_src, '//') !== false) {
						if (strpos( $img_src, 'http') !== false) {	
							if ($this->update_export_log($img_src)) {
								$data = file_get_contents($img_src);
							}
						} else {
							if ($this->update_export_log($scheme . ':' . $img_src)) {
								$data = file_get_contents( $scheme . ':' . $img_src );
							}
						}
						
					}
					else {
						if ($this->update_export_log($host . $img_src)) {
							$data = file_get_contents($host . $img_src);
						}
					}
				}
			


				if (strpos( $basename[0], ".") == false) {
					$basename[0] = rand(5000, 9999) . ".jpg";
				}

				$my_file = $pathname_images . $basename[0];
				$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
				
				fwrite($handle, $data);
				fclose($handle);
				$src = str_replace($img_src, 'images/' . str_replace(' ', '', $basename[0]), $src);
			}
		}

		foreach ((array) $og_image[0] as $key => $image) {
			if ( strpos($image, 'content') !== false) {
				$img_src = $this->get_string_between($image, 'content="', '"');
				$basename = explode('?', basename($img_src));

				if ( !(strpos( $img_src, 'data:') !== false) ) {
					if (strpos( $img_src, '//') !== false) {
						if (strpos( $img_src, 'http') !== false) {
							if ($this->update_export_log($img_src)) {
								$data = file_get_contents($img_src);
							}
						} else {
							if ($this->update_export_log($scheme . ':' . $img_src)) {
								$data = file_get_contents( $scheme . ':' . $img_src );
							}
						}
						
					}
					else {
						if ($this->update_export_log($host . $img_src)) {
							$data = file_get_contents($host . $img_src);
						}
					}
				}
			


				if (strpos( $basename[0], ".") == false) {
					$basename[0] = rand(5000, 9999) . ".jpg";
				}

				$my_file = $pathname_images . $basename[0];
				$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
				
				fwrite($handle, $data);
				fclose($handle);
				$src = str_replace($img_src, 'images/' . str_replace(' ', '', $basename[0]), $src);
			}
		}

		$src = $src . "\n<!--This file was exported by \"Export WP Page to Static HTML\" plugin which created by ReCorp (https://myrecorp.com) -->";

		if ($replace_urls && $this->update_export_log('', 'replacing', 'all urls to #') ) {
			$src = preg_replace("/(?<=\<a href=\"|\<a href=\').*?(?=\'|\")/", '#', $src);
		}	

		if ($this->update_export_log('', 'creating_last_file', 'main html file')) {	


			$my_file = $upload_dir . '/exported_html_files/tmp_files/index.html';
			$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
			fwrite($handle, $src);
			fclose($handle);
		}


		return true;

	}

	public function update_export_log($path="", $type = "copying", $comment = ""){
		global $wpdb;

		$wpdb->insert( 
			$wpdb->prefix . 'export_page_to_html_logs', 
			array( 
				'path' => $path, 
				'type' => $type, 
				'comment' => $comment, 
			), 
			array( 
				'%s',
				'%s',
				'%s',
			) 
		);

		return true;
	}

	public function get_exporting_logs(){
		$id = isset($_POST['log_id']) ? sanitize_key($_POST['log_id']) : "";
		$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}
		$id = intval($id);
		$id = $id;

		$response = $this->get_export_log_by_id($id);

		
		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));
	
		die();
	}

	public function get_export_log_by_id($id=0){
		global $wpdb;

		//$id = intval($id);
		if ($id < 1) {
			$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs ORDER BY id ASC LIMIT 50");
			
			return $result;
		} else {
			
			$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs ORDER BY id ASC LIMIT 50 OFFSET {$id} ");

			return $result;
		}
	}

	public function create_zip($files = array(), $destination = '', $replace_path = "", $overwrite = true) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					if (is_file($file)) {
						$valid_files[] = $file;
					}
					
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {

			//create the archive
			$overwrite = file_exists($destination) ? true : false ;
			$zip = new ZipArchive();
			if($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}

			//add the files
			foreach($valid_files as $file) {
				$filename = str_replace( $replace_path, '', $file);
				$zip->addFile($file, $filename);
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination) ? 'created' : 'not' ;
		}
		else
		{
			return false;
		}
	}


	public function create_the_zip_file(){
		$page_id = isset($_POST['page_id']) ? sanitize_key($_POST['page_id']) : "";
		$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		global $wpdb;

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}

		$post = get_post($page_id);
		$post_name = $post->post_name;

		$upload_dir = wp_upload_dir()['basedir'];
		$upload_url = wp_upload_dir()['baseurl'] . '/exported_html_files';

		$all_files = $upload_dir . '/exported_html_files/tmp_files';
		$files = $this->get_all_files_as_array($all_files);

		$zip_file_name = $upload_dir . '/exported_html_files/'.$post_name.'-html.zip';

		ob_start();
		echo $this->create_zip($files, $zip_file_name, $all_files . '/');
		$create_zip = ob_get_clean();

		if ($create_zip == 'created') {
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");
		}

		$response = ($create_zip == 'created') ? $upload_url . '/'.$post_name.'-html.zip' : false;

		
		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));
	
		die();
	}

	public function get_all_files_as_array($all_files){

		function rc_get_sub_dir($dir) {
		    foreach(scandir($dir) as $file) {
		        if ('.' === $file || '..' === $file) continue;
		        if (is_dir("$dir/$file")) rc_get_sub_dir("$dir/$file");
		        echo "$dir/$file" . ',';
		    }
		}
		ob_start();
		rc_get_sub_dir($all_files);
		$files = ob_get_clean();
		$files = rtrim($files, ',');
		$files = explode(',', $files);

		return $files;
	}

}
