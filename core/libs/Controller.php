<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
    /**
     * Controller.php
     *
     * This is the controller class, the blueprints for every controller
     * in the system. It sets the required variables so that every controller has
     * it when loaded.
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
	
	Spine::load('Inflector', 'Loader', 'Input', 'Validate');
	
    class Controller {

		/**
		 * @var array $helpers array of helpers to be loaded in view
		 */
		public $helpers;

		/**
		 * @var object $load the loader object
		 */
		public $load;

		/**
		 * @var object $input the input object
		 */
		public $input;

		/**
		 * @var array $__variables array of view variables
		 */
		public $__variables;

		/**
		 * @var array $__params default params set for models
		 */
		protected $__params = array(
			'request' => false,
			'limit' => 10,
			'order' => 'desc',
			'sort' => 'id'
		);

		/**
		 * __construct
		 *
		 * When a new controller is requested via the Request library, a new instance
		 * of this is created since all controllers must extend this. Sets a few properties
		 * and peforms any autoloading that is required.
		 */
		public function __construct(){
			// Create a new loader object.
			$this->load = new Loader;
			$this->load->__controller =& $this;

			// Create a new input object.
			Input::sanitize_globals();
			$this->input = new Input;

			// Is model autoloading enabled for the controller.
			if(isset($this->enable_model_autoload) && $this->enable_model_autoload === true){
				Spine::load('Model');
				if(isset($this->name) && !empty($this->name)){
					$name = $this->name;
				}else{
					// Attempt to create the name based on get_class() and removing last 10 characters.
					$name = substr(get_class($this), 0, -10);
				}
				$this->load->model($name);
			}

			// Autoload any libs.
			if(!empty($this->libs)){
				foreach($this->libs as $lib){
					Spine::load($lib);
				}
			}

			// Start output buffering to capture basic output.
			ob_start();
		}

		/**
		 * get_param
		 *
		 * Get a param from the parameters array.
		 *
		 * @param string $param
		 * @return mixed
		 */
		public function get_param($param = null){
			if(empty($param)){
				return $this->__params;
			}
			
			if(isset($this->__params[$param])){
				return $this->__params[$param];
			}
			return false;
		}

		/**
		 * get_params
		 *
		 * Set a referenced variable to all of the params
		 * in the paramater array.
		 *
		 * @param referenced variable $ref
		 */
		public function get_params(&$ref){
			$ref = $this->__params;
		}

		/**
		 * set_param
		 *
		 * Set a parameter in the parameters array.
		 *
		 * @param string $param
		 * @param mixed $value
		 */
		public function set_param($param, $value){
			$this->__params[$param] = $value;
			return $this;
		}

		/**
		 * set
		 *
		 * Set variables for the view file.
		 *
		 * @param string $variable
		 * @param string $value
		 */
		protected function set($variable, $value){
			$this->__variables[$variable] = $value;
			return $this;
		}

		/**
		 * write
		 *
		 * Alias of Template::write
		 *
		 * @param string $variable the tpl variable to write to
		 * @param string $content the content to write to the section
		 * @param boolean $overwrite if section value is overwitten
		 */
		protected function write($variable, $content, $overwrite = false){
			Template::write($variable, $content, $overwrite);
			return $this;
		}

		/**
		 * write_view
		 *
		 * Similar to write, except this allows you to write a view file to a section.
		 * Although possible with write, it requires a few extra lines.
		 *
		 * @param string $variable name of variable
		 * @param string $view name of view file to load
		 * @param boolean $overwrite if section value is overwritten
		 */
		protected function write_view($variable, $view, $overwrite = false){
			$this->write($variable, $this->load->view($view), $overwrite);
			return $this;
		}

		/**
		 * set_header
		 *
		 * Alias of Template::set_header
		 */
		protected function set_header($action, $string = null, $replace = false){
			Template::set_header($action, $string, $replace);
			return $this;
		}

		/**
		 * set_template
		 *
		 * Alias of Template::set_template
		 */
		protected function set_template($template, $revert = false){
			Template::set_template($template, $revert);
			return $this;
		}

		/**
		 * dispatch
		 *
		 * Allows a controller to dispatch to a new controller, overwriting the current controller
		 * and firing the method if supplied.
		 * Acts as an alias to Request::instance($uri)->dispatch();
		 *
		 * @param string $controller name of controller to dispatch too
		 */
		protected function dispatch($uri){
			$request = Request::instance('dispatcher', $uri);
			if($request){
				$request->dispatch();
			}
		}

		/**
		 * cache
		 *
		 * Sets the cache timeout.
		 *
		 * @param int $timeout length of timeout
		 */
		protected function cache($timeout){
			if($timeout > 0){
				Template::$cache['enabled'] = true;
				Template::$cache['timeout'] = $timeout;
			}
		}

		/**
		 * prepare
		 *
		 * Alias of Template::prepare
		 */
		public function prepare(){
			Template::prepare();
		}

		/**
		 * redirect
		 *
		 * Allows easier redirection.
		 *
		 * @param mixed $url the url to redirect too
		 * @param boolean $exit if script exists, defaults to true
		 * @return boolean on failure
		 */
		public function redirect($url, $exit = true){
			$url = Request::build_uri($url);
			if($url){
				header("Location: " . $url);
				if($exit === true){
					exit;
				}
			}
		}
    }
?>