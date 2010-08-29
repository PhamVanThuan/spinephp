<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Template.php
	 *
	 * This little puppy is what makes it all pretty!
	 * Takes everything from the view and hay-presto, bam! It shows it all
	 * to the user.
	 *
	 * For help with templating, read up on it in the docs.
	 *
	 * Also handles page caching, to maximise performance, if you're that
	 * way inclined.
	 *
	 * Copyright (c) 2010, Jason Lewis, Spine PHP Team (http://www.spinephp.org)
	 *
	 * Licensed under the BSD License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis, Spine PHP Team
	 * @link		<http://www.spinephp.org>
	 * @license		BSD License <http://www.opensource.org/licenses/bsd-license.php>
	 */

    class Template extends Object {

		/**
		 * @var array $cache settings for caching
		 */
		public static $cache = array(
			'enabled' => false,
			'timeout' => 0
		);

		/**
		 * @var array $tpl array of template variables
		 */
		public static $tpl = array();

		/**
		 * @var array $user_set_template populated with a user set template
		 */
		public static $user_set_template = array();

		/**
		 * @var string $output final output to be rendered
		 */
		public static $output;

		/**
		 * prepare
		 *
		 * Contains all the logic for preparing the template for rendering.
		 * Handles any Hooks.
		 */
		public static function prepare(){
			list($folder, $template) = Template::get_template();
			
			if(!file_exists(BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $folder . DS . $template . '.php')){
				trigger_error('Could not find <strong>template.php</strong> in ' . BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $folder . DS, E_USER_ERROR);
			}else{

				// Set some default template variables.
				Template::$tpl['g_base_path'] = BASE_PATH . DS;
				Template::$tpl['g_base_url'] = SYS_URL;
				Template::$tpl['g_tpl_path'] = BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $folder . DS;
				Template::$tpl['g_tpl_url'] = SYS_URL . APP_PATH . DS . 'templates' . DS . $folder . DS;

				// Run any hooks for Display.before
				Hooks::run('Display.before');

				// Load any helpers that were specified in the config
				$helpers = Helpers::load(Config::read('Template.helpers'));
				if(!empty($helpers)){
					foreach($helpers as $key => $val){
						$key = strtolower($key);
						$$key = $val;
					}
				}

				// Template variables need to be set.
				$tpl = Template::$tpl;

				// Get the CSS and JS
				$css = Template::css();
				$js = Template::js();

				if(empty(Template::$output)){
					// Firstly, let's start output buffering, so we can capture the output and perform
					// any parsing on it.
					ob_start();

					include(BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $folder . DS . $template . '.php');

					// Using output buffering, we can get the contents of the buffer and clean it.
					Template::$output = ob_get_clean();
				}
			}
		}

		/**
		 * render
		 *
		 * Displays the output to the user, starts output buffering etc.
		 *
		 * @param string $output
		 */
		public static function render($output){
			// Cache the file if caching is enabled.
			if(Template::$cache['enabled']){
				if(Template::$cache['timeout'] > 0){
					Template::write_cache($output);
				}
			}

			// Run any hooks for Display.beforeRender, reference output as a parameter
			Hooks::run('Display.beforeRender', array('output' => &$output));

			// Get the compression type, like gzip.
			$compression = Template::get_compression_type();

			// Start output buffering with any compression if available.
			ob_start($compression);

			// Display the output
			if(Config::read('Template.strip_new_lines')){
				$output = trim(str_replace(array("\r\n","\n","\r","\t"), "", $output));
			}

			// Display the final output.
			echo $output;
			
			// Flush the buffer.
			ob_end_flush();
		}

		/**
		 * css
		 *
		 * Load up the CSS files, check the configuration to exclude any
		 * files or order the loading.
		 */
		public static function css(){
			if(Config::read('Template.ignore.css') === null){
				Config::write('Template.ignore.css', array());
			}else{
				$ignore = array();
				foreach(Config::read('Template.ignore.css') as $val){
					$ignore[] = strpos($val, '.css') !== false ? $val : $val . '.css';
				}
			}

			// Get the template folder and template file.
			list($folder, $template) = Template::get_template();

			$css = array();
			foreach(glob(BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $folder . DS . 'public' . DS . 'css' .DS . '*.css') as $file){
				if(!in_array(basename($file), $ignore)){
					$css[basename($file)] = '<link rel="stylesheet" type="text/css" href="' . SYS_URL . APP_PATH . DS . 'templates' . DS . 
						$folder . DS . 'public' . DS . 'css' . DS . basename($file) . '" />';
				}
			}

			if(count(array_clean(Config::read('Template.order.css'))) === 0){
				return implode("\n", $css);
			}else{
				$tmp = array();
				foreach(Config::read('Template.order.css') as $order){
					$order = strpos($order, '.css') !== false ? $order : $order . '.css';
					if(array_key_exists($order, $css)){
						$tmp[] = $css[$order];
					}
				}
				foreach($css as $key => $val){
					if(!in_array($val, $tmp)){
						$tmp[] = $val;
					}
				}
				return implode("\n", $tmp);
			}
		}

		/**
		 * js
		 *
		 * Load up the JS files, check the configuration to exclude any
		 * files or order the loading.
		 */
		public static function js(){
			if(Config::read('Template.ignore.js') === null){
				Config::write('Template.ignore.js', array());
			}else{
				$ignore = array();
				foreach(Config::read('Template.ignore.js') as $val){
					$ignore[] = strpos($val, '.js') !== false ? $val : $val . '.js';
				}
			}

			// Get the template folder and template file.
			list($folder, $template) = Template::get_template();

			$js = array();
			foreach(glob(BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $folder . DS . 'public' . DS . 'js' . DS . '*.js') as $file){
				if(!in_array(basename($file), $ignore)){
					$js[basename($file)] = '<script type="text/javascript" src="' . SYS_URL . APP_PATH . DS . 'templates' . DS . 
						$folder . DS . 'public' . DS . 'js' . DS . basename($file) . '"></script>';
				}
			}

			if(count(array_clean(Config::read('Template.order.js'))) === 0){
				return implode("\n", $js);
			}else{
				$tmp = array();
				foreach(Config::read('Template.order.js') as $order){
					$order = strpos($order, '.js') !== false ? $order : $order . '.js';
					if(array_key_exists($order, $js)){
						$tmp[] = $js[$order];
					}
				}
				foreach($js as $key => $val){
					if(!in_array($val, $tmp)){
						$tmp[] = $val;
					}
				}
				return implode("\n", $tmp);
			}
		}

		/**
		 * append_head_code
		 *
		 * Append HTML code to the <head> tag. This is generally prior to the template being rendered
		 * in the Display.before hook.
		 *
		 * @param string $code
		 * @param string $output
		 */
		public static function append_head_code($code, $output = null){
			if(empty($output)){
				$output = & Template::$output;
			}

			$output = preg_replace('/<\/head>/im', "{$code}\\0", $output);
		}

		/**
		 * write_cache
		 *
		 * Write the contents of a file to the cache.
		 *
		 * @param string $contents the contents that should be cached
		 */
		public static function write_cache($contents){
			$directory = BASE_PATH . DS . TMP_PATH . DS . 'cache';

			if(file_exists($directory) && is_writable($directory) && Config::read('Template.enable_caching') === true){
				$timeout = time() + (Template::$cache['timeout'] * 60);

				$cache_name = md5(Router::get_uri());

				// Only write to the cache file if it doesn't exist.
				if(!file_exists($directory . DS . $cache_name)){
					if($handle = @fopen($directory . DS . $cache_name, 'w')){
						fwrite($handle, '@CACHE_TIMEOUT:' . $timeout . ':CACHE_URI:' . Router::get_uri() . '>>>' . $contents);
						fclose($handle);
						@chmod($directory . $cache_name, 0755);
					}
				}
			}
		}

		/**
		 * render_cache
		 *
		 * Checks a URI to determine if it should render the requested page from a cached version
		 * or if it should load a new version of the page.
		 *
		 * @param string $uri the uri to check for cache file
		 * @return boolean
		 */
		public static function render_cache($uri){
			$uri = md5($uri);
			$directory = BASE_PATH . DS . TMP_PATH . DS . 'cache';

			if(file_exists($directory . DS . $uri) && Config::read('Template.enable_caching') === true){
				// Cache file exists, make sure it hasn't timed out.
				if($contents = file_get_contents($directory . DS . $uri)){
					preg_match("#\@CACHE_TIMEOUT\:(\d+)\:CACHE\_URI\:(.*?)\>\>\>#", $contents, $matches);

					if(empty($matches) || !isset($matches[1])){
						return false;
					}

					if(time() >= $matches[1]){
						// Cache has expired. Delete and request new.
						@unlink($directory . $uri);
						return false;
					}

					// Grab the stored URI.
					$uri = $matches[2];

					// Send the cached copy to the render method.
					Template::render(str_replace($matches[0], '', $contents));
					
				}
			}

			// Something happened. No cache then.
			return false;
		}

		/**
		 * delete_cache
		 *
		 * Delete a cached file, or all cached files.
		 *
		 * @param string $uri
		 * @return boolean
		 */
		public static function delete_cache($uri = null){
			$directory = BASE_PATH . DS . TMP_PATH .  DS . 'cache';
			
			if(empty($uri)){
				// Delete all.
				foreach(glob($directory . DS . '*') as $file){
					if(!is_dir($file)){
						@unlink($file);
					}
				}
				return true;
			}else{
				$uri = md5($uri);

				if(file_exists($directory . DS . $uri) && Config::read('Template.enable_caching') === true){
					if(@unlink($directory . DS . $uri)){
						return true;
					}else{
						return false;
					}
				}
			}
		}

		/**
		 * get_compression_type
		 *
		 * Return the type of compression to be used if any.
		 *
		 * @return string
		 */
		public static function get_compression_type(){
			$compression = '';
			if(Config::read('Template.enable_gzip_compression')){
				if(extension_loaded('zlib')){
					if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false){
						$compression = 'ob_gzhandler';
					}
				}
			}
			return $compression;
		}

		/**
		 * write
		 *
		 * This method allows you to write to sections of your template.
		 *
		 * @param string $section the name of the section as defined in the config
		 * @param string $content the content to write to the section
		 * @param boolean $overwrite overwrite the current section contents or append the content
		 */
		public static function write($section, $content, $overwrite = false){
			if(!$overwrite){
				if(isset(Template::$tpl[$section])){
					Template::$tpl[$section] .= $content;
				}else{
					Template::$tpl[$section] = $content;
				}
			}else{
				Template::$tpl[$section] = $content;
			}
		}

		/**
		 * set_header
		 *
		 * Allows headers to be set.
		 *
		 * @param string $action the action to take, such as Content-type
		 * @param string $string the value of the action, not always required
		 * @param boolean $replace
		 */
		public static function set_header($action, $string = '', $replace = false){
			if(empty($string)){
				header($action);
			}else{
				header($action . ':' . $string, $replace);
			}
		}

		/**
		 * get_template
		 *
		 * Get the current templates folder and file.
		 *
		 * @return array
		 */
		public static function get_template(){
			if(!empty(Template::$user_set_template)){
				list($folder, $template) = Template::$user_set_template;
			}else{
				list($folder, $template) = Config::read('Template.default_template');
			}

			return array($folder, $template);
		}

		/**
		 * set_template
		 *
		 * Set the default template to use on the fly, allows for changing between displaying
		 * a HTML file or outputting an image/file etc.
		 *
		 * @param string $template name of the template file to load
		 * @param bool $revert
		 */
		public static function set_template($template, $revert = false){
			if(strstr($template, '/') !== false){
				// The user passed in a different template to load, other then default.
				// Make sure that the template is defined.
				list($folder, $template) = explode('/', $template);

				// Set template to HTML if it's not set.
				$template = $template == '' ? 'html' : $template;

				if(!file_exists(BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $folder . DS . $template . '.php') && $revert === false){
					trigger_error('Failed to load the <strong>' . $template . '</strong> template in <strong>' . $folder . '</strong>', E_USER_ERROR);
				}else{
					Template::$user_set_template = array($folder, $template);
				}
			}else{
				list($default_folder, $default_template) = Config::read('Template.default_template');
				
				if(file_exists(BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $default_folder . DS . $template . '.php')){
					Template::$user_set_template = array($default_folder, $template);
				}else{
					trigger_error('Failed to locate template file at ' . BASE_PATH . DS . APP_PATH . DS . 'templates' . DS . $default_folder . DS . $template . '.php', E_USER_ERROR);
				}
			}
		}

    }

?>