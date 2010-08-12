<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }

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

	Spine::load('Inflector');

	class View extends Object {

		/**
		 * @var array $views array of loaded view files
		 */
		protected $views;

		/**
		 * @var array $loaded_helpers array of loaded helpers
		 */
		protected $loaded_helpers = array();

		/**
		 * @var array $variables array of variables set for a view
		 */
		protected $variables;

		/**
		 * @var array $helpers array of helpers required for view
		 */
		public $helpers;

		/**
		 * load
		 *
		 * Load a view file, stores the loaded view in the $loaded property.
		 *
		 * @param string $view name of view file
		 */
		public function load($view, $render = false){
			if(file_exists(APP_PATH . 'views/' . $view . '.php')){

				// If variables is passed, they want to set a few variables in the same line.
				if(!empty($this->variables)){
					foreach($this->variables as $var => $val){
						$$var = $val;
					}
				}
				// Now unset the variables, so they aren't passed into the next view.
				$this->variables = array();

				// Load any helpers.
				$helpers = Helpers::load($this->helpers);
				if(!empty($helpers)){
					foreach($helpers as $key => $val){
						$key = strtolower($key);
						$$key = $val;
					}
				}

				if(!isset($this->views[$view])){
					// Start output buffering.
					ob_start();

					// Include the requested file.
					include(APP_PATH . 'views/' . $view . '.php');

					// Sets the contents in the loaded property.
					$this->views[$view] = ob_get_clean();
					
					if($render){
						// Render the view, send to Template render.
						Template::render($this->views[$view]);
					}else{
						return $this->views[$view];
					}
				}else{
					if($render){
						// Render the view, send to Template render.
						Template::render($this->views[$view]);
					}else{
						return $this->views[$view];
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
		public function set($variable, $value){
			$this->variables[$variable] = $value;
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
		public function section($name, $params = array()){
			$name = Inflector::filename($name);
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
		public function cache($timeout){
			if($timeout > 0){
				Template::$cache['enabled'] = true;
				Template::$cache['timeout'] = $timeout;
			}
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