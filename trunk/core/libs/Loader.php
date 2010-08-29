<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Loader.php
	 *
	 * Contains methods to load models and views.
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

	class Loader extends Object {

		/**
		 * @var array $__vars any set properties
		 */
		protected $__vars;

		/**
		 * @var object $__controller object controller
		 */
		public $__controller;

		/**
		 * view
		 *
		 * Loads a view file, sets an variables that were set in the controller and loads
		 * and helpers for the view file to use.
		 *
		 * @param string $view
		 * @param boolean $render
		 * @return mixed
		 */
		public function view($view, $render = false){
			$view = Inflector::filename($view);
			
			if(!file_exists(BASE_PATH . DS . APP_PATH . DS . 'views' . DS . $view . '.php')){
				trigger_error('Could not load requested view file ' . BASE_PATH . DS . APP_PATH . DS . 'views' . DS . $view . '.php', E_USER_ERROR);
			}else{
				// Set any variables for the view file, storing in a tmp array so we can unset them.
				$__tmp_vars = array();
				if(!empty($this->__controller->__variables)){
					foreach($this->__controller->__variables as $variable => $value){
						$__tmp_vars[] = $variable;

						if(strpos($variable, '.') !== false){
							$tmp = array(); array_inject($tmp, $variable, $value);
							$keys = array_keys($tmp);
							if(isset(${$keys[0]})){
								${$keys[0]} += $tmp[$keys[0]];
							}else{
								${$keys[0]} = $tmp[$keys[0]];
							}
						}else{
							${$variable} = $value;
						}
					}
				}

				// Set any helpers for the view file, storing in a tmp array so we can unset them.
				if(!empty($this->__controller->helpers)){
					$helpers = Helpers::load($this->__controller->helpers);
					foreach($helpers as $variable => $value){
						// Helper variables are lowercased, for easier use.
						$variable = strtolower($variable);
						
						$__tmp_vars[] = $variable;
						${$variable} = $value;
					}
				}

				// Start output buffering so we can capture the output.
				ob_start();
				include(BASE_PATH . DS . APP_PATH . DS . 'views' . DS . $view . '.php');
				$contents = ob_get_contents();
				ob_end_clean();

				// Unset all variables.
				foreach($__tmp_vars as $variable){
					unset($variable);
				}

				if($render === false){
					// Return the output to the user
					return $contents;
				}else{
					// Send the output to Template::render to process
					Template::render($contents);
				}
			}
		}

		/**
		 * section
		 *
		 * Load a section into the current view file. Sections are reusable view
		 * files that contain content that is used in more then one place throughout a website.
		 *
		 * @param string $name
		 * @param array $params
		 * @return mixed
		 */
		public function section($section, $params = array()){
			$section = Inflector::filename($section);
			if(file_exists(BASE_PATH . DS . APP_PATH . DS . 'views' . DS . 'sections' . DS . $section . '.php')){
				// Set any params as variables and store in a tmp array for unsetting.
				$__tmp_vars = array();
				if(!empty($params)){
					foreach($params as $variable => $value){
						$__tmp_vars[] = $variable;
						${$variable} = $value;
					}
				}

				ob_start();
				include(BASE_PATH . DS . APP_PATH . DS . 'views' . DS . 'sections' . DS . $section . '.php');
				$output = ob_get_clean();

				// Unset any variables.
				foreach($__tmp_vars as $variable){
					unset($variable);
				}

				// Returnt the output.
				return $output;
			}
			
			return false;
		}

		/**
		* model
		*
		* Allows you to load a model into a controller, if it wasn't done
		* automatically.
		*
		* @param string $model name of model to load
		*/
		public function model($model){
			// Load in the Model class if needed
			Spine::loaded('Model', true);

			$model = strtolower(Inflector::filename($model));
			$included = get_included_files();

			// Attempt to locate the appropriate model, first look in the application/models
			if(!in_array(BASE_PATH . DS . APP_PATH . DS . 'models' . DS . $model . '.php', $included)){
				if(file_exists(BASE_PATH . DS . APP_PATH . DS . 'models' . DS . $model . '.php')){
					require(BASE_PATH . DS . APP_PATH . DS . 'models' . DS . $model . '.php');
				}else{
					// Couldn't find it there, perhaps they have nested it inside a folder but forgot to specify.
					if(file_exists(BASE_PATH . DS . APP_PATH . DS . 'models' . DS . $model . DS . $model . '.php')){
						require(BASE_PATH . DS . APP_PATH . DS . 'models' . DS . $model . DS . $model . '.php');
					}
				}
			}

			// Check if we loaded the model and that the class exists, if it doesn't don't worry. Their fault.
			$class = Inflector::classname($model . 'Model');
			$model = Inflector::classname($model);

			if(class_exists($class, false)){
				// Excellent, pass in a few properties and set params to reference our params.
				$this->__controller->{$model} = new $class;
				$this->__controller->{$model}->name = $model;

				$params = null;
				$this->__controller->get_params($params);
				$this->__controller->{$model}->params = $params;
			}else{
				return false;
			}
		}

		/**
		 * plugin
		 *
		 * Alias of Plugin::load
		 */
		public function plugin($plugin){
			return Plugin::load($plugin, true);
		}

		/**
		 * __set
		 *
		 * Magic set method, to store any undefined set properties in an array.
		 *
		 * @param string $var
		 * @param mixed $val
		 */
		public function __set($var, $val){
			$this->__vars[$var] = $val;
		}

		/**
		 * __get
		 *
		 * Magic get method, retrieving of magic set variables.
		 *
		 * @param string $var
		 * @return mixed
		 */
		public function __get($var){
			if(isset($this->__vars[$var])){
				return $this->__vars[$var];
			}else{
				return false;
			}
		}

	}

?>