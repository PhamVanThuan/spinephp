<?php

    /**
     * View.php
     *
     * The View class handles all things related to the view, such as aliasing template methods,
	 * loading view files, setting variables, loading helpers etc.
     *
	 * Copyright (c) 2010, Jason Lewis (http://www.spinephp.org)
	 *
	 * Licensed under the MIT License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis
	 * @link		(http://www.spinephp.org)
	 * @license		MIT License (http://www.opensource.org/licenses/mit-license.html)
	 */

	class View extends Object {
		
		protected $views;
		protected $loaded_helpers = array();
		protected $variables;
		public $helpers;

		/**
		 * load
		 *
		 * Load a view file, stores the loaded view in the $loaded property.
		 *
		 * @param string $view name of view file
		 */
		public function load($view, $render = false){
			if(file_exists(APP_PATH . 'views/' . $view . '.view.php')){
				
				// If variables is passed, they want to set a few variables in the same line.
				if(!empty($this->variables)){
					foreach($this->variables as $var => $val){
						$$var = $val;
					}
				}

				// Load any helpers.
				$helpers = $this->spine->Helpers->load($this->helpers);
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
					include(APP_PATH . 'views/' . $view . '.view.php');

					// Sets the contents in the loaded property.
					$this->views[$view] = ob_get_clean();

					if($render){
						// Render the view, send to Template render.
						$this->spine->Template->render($this->views[$view]);
					}else{
						return $this->views[$view];
					}
				}else{
					if($render){
						// Render the view, send to Template render.
						$this->spine->Template->render($this->views[$view]);
					}else{
						return $this->views[$view];
					}
				}

			}else{
				trigger_error('Could not locate requests view file in ' . APP_PATH . 'views/' . $view . '.view.php', E_USER_ERROR);
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
			$name = str_replace('-', '_', $name);
			if(file_exists(APP_PATH . 'views/sections/' . $name . '.section.php')){
				// Found the section file, extract the paramaters.
				extract($params);
				
				ob_start();
				include(APP_PATH . 'views/sections/' . $name . '.section.php');
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
				$this->spine->Template->cache['enabled'] = true;
				$this->spine->Template->cache['timeout'] = $timeout;
			}
		}

		/**
		 * set_template
		 *
		 * Alias of Template::set_template
		 */
		public function set_template($template){
			$this->spine->Template->set_template($template);
		}

		/**
		 * plugin
		 *
		 * Alias of Plugin::execute
		 */
		public function plugin($plugin){
			if($this->spine->is_library_loaded('Plugin')){
				$this->spine->Plugin->execute($plugin);
			}else{
				return false;
			}
		}

	}

?>