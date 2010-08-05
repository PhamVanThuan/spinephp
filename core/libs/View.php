<?php

    /**
     * View.php
     *
     * The View class handles all things related to the view, such as aliasing template methods,
	 * loading view files, setting variables, loading helpers etc.
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

	class View extends Object {

		// Array of view files that have been loaded.
		protected static $views;

		// Array of helpesr that have been loaded.
		protected static $loaded_helpers = array();

		// Array of variables to make available in a view.
		protected static $variables;

		// Array of helpers to load.
		public static $helpers;

		/**
		 * load
		 *
		 * Load a view file, stores the loaded view in the $loaded property.
		 *
		 * @param string $view name of view file
		 */
		public static function load($view, $render = false){
			if(file_exists(APP_PATH . 'views/' . $view . '.php')){
				
				// If variables is passed, they want to set a few variables in the same line.
				if(!empty(View::$variables)){
					foreach(View::$variables as $var => $val){
						$$var = $val;
					}
				}

				// Load any helpers.
				$helpers = Helpers::load(View::$helpers);
				if(!empty($helpers)){
					foreach($helpers as $key => $val){
						$key = strtolower($key);
						$$key = $val;
					}
				}

				if(!isset(View::$views[$view])){
					// Start output buffering.
					ob_start();

					// Include the requested file.
					include(APP_PATH . 'views/' . $view . '.php');

					// Sets the contents in the loaded property.
					View::$views[$view] = ob_get_clean();
					
					if($render){
						// Render the view, send to Template render.
						Template::render(View::$views[$view]);
					}else{
						return View::$views[$view];
					}
				}else{
					if($render){
						// Render the view, send to Template render.
						Template::render(Views::$views[$view]);
					}else{
						return Views::$views[$view];
					}
				}

			}else{
				trigger_error('Failed to find the requested view file ' . APP_PATH . 'views/' . $view . '.php', E_USER_ERROR);
			}
		}

		/**
		 * set
		 *
		 * Set variables for the view file.
		 *
		 * @param string $variable
		 * @param string $value
		 */
		public static function set($variable, $value){
			View::$variables[$variable] = $value;
		}

		/**
		 * section
		 *
		 * Load a section or mini view file into the current view file. Sections are reusable view
		 * files that contain content that is used in more then one place throughout a website.
		 *
		 * @param string $name
		 * @param array $params
		 * @return string
		 */
		public static function section($name, $params = array()){
			$name = str_replace('-', '_', $name);
			if(file_exists(APP_PATH . 'views/sections/' . $name . '.php')){
				// Found the section file, extract the paramaters.
				extract($params);
				
				ob_start();
				include(APP_PATH . 'views/sections/' . $name . '.php');
				$output = ob_get_clean();

				return $output;
			}else{
				return false;
			}

		}

		/**
		 * cache
		 *
		 * Sets the cache timeout.
		 */
		public static function cache($timeout){
			if($timeout > 0){
				Template::$cache['enabled'] = true;
				Template::$cache['timeout'] = $timeout;
			}
		}

		/**
		 * set_template
		 *
		 * Alias of Template::set_template
		 */
		public static function set_template($template){
			Template::set_template($template);
		}

		/**
		 * plugin
		 *
		 * Alias of Plugin::execute
		 */
		public function plugin($plugin){
			if(Spine::loaded('Plugin')){
				Plugin::execute($plugin);
			}else{
				return false;
			}
		}

	}

?>