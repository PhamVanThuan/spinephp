<?php

    /**
     * Controller.php
     *
     * This is the abstract controller class, the blueprints for every controller
     * in the system. It sets the required variables so that every controller has
     * it when loaded.
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

    abstract class Controller {

		// Array of helpers that are referenced by the View
		protected $helpers;

		// Default params used by models.
		public $params = array(
			'request' => false,
			'limit' => 10,
			'order' => 'desc',
			'sort' => 'id'
		);

		// Is set to an object if a template parser is being used instead of the default.
		public $parser;
		
		public function __construct(){
			// Make sure that the view library is loaded and that we reference the helpers.
			Spine::load('View');
			View::$helpers =& $this->helpers;

			// Is model autoloading enabled for the controller.
			if(isset($this->enable_model_autoload) && $this->enable_model_autoload === true){
				// Load the model class if available.
				Spine::load('Model');
				if(isset($this->name)){
					// Load the model.
					$this->model($this->name);
				}
			}

			// Autoload any libs.
			if(!empty($this->libs)){
				foreach($this->libs as $lib){
					Spine::load($lib);
				}
			}
		}

		/**
		 * abstract index
		 */
		abstract public function index();

		/**
		 * write
		 *
		 * Alias of Template::write
		 *
		 * @param string $variable the tpl variable to write to
		 * @param string $content the content to write to the section
		 * @param boolean $overwrite if section value is overwitten
		 */
		public function write($variable, $content, $overwrite = false){
			Template::write($variable, $content, $overwrite);
		}

		/**
		 * write_view
		 *
		 * Similar to write, except this allows you to write a view file to a section.
		 * Although possible with write, it requires a few extra lines.
		 *
		 * @param string $variable name of variable
		 * @param string $view name of view file to load
		 * @param array $params array of params to set in view file
		 * @param boolean $overwrite if section value is overwritten
		 */
		public function write_view($variable, $view, $params = array(), $overwrite = false){
			if(!empty($params)){
				foreach($params as $key => $val){
					$this->View->set($key, $val);
				}
			}
			Template::write($variable, View::load($view), $overwrite);
		}

		/**
		 * set_header
		 *
		 * Alias of Template::set_header
		 */
		public function set_header($action, $string = null, $replace = false){
			Template::set_header($action, $string, $replace);
		}

		/**
		 * set
		 *
		 * Alias of View::set
		 */
		public function set($variable, $value){
			View::set($variable, $value);
		}

		/**
		 * view
		 *
		 * Alias of View::load
		 */
		public function view($view, $render = false){
			return View::load($view, $render);
		}

		/**
		 * dispatch
		 *
		 * Allows a controller to dispatch to a new controller, overwriting the current controller
		 * and firing the method if supplied.
		 * Acts as an alias to Router::dispatch
		 *
		 * @param string $controller name of controller to dispatch too
		 */
		public function dispatch($controller){
			Router::dispatch($controller, true, false, isset($this->name) ? $this->name : null);
		}

		/**
		 * crumb
		 *
		 * Alias of Breadcrumbs::crumb
		 */
		public function crumb($name, $url = null){
			if(Spine::loaded('Breadcrumbs')){
				Breadcrumbs::crumb($name, $url);
			}else{
				return null;
			}
		}

		/**
		 * plugin
		 *
		 * Alias of Plugin::load
		 */
		public function plugin($plugin){
			Plugin::load($plugin);
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
		 * Allow easy re-direction. Can pass in an array, absolute or relative URL.
		 * Array version is useful if creating an application that may be placed on a server
		 * without mod_rewrite, so Query String is required. Format is as follows:
		 * array('controller' => 'example', 'action' => 'index')
		 * Action will automatically be set to index if not supplied and more arguements are passed.
		 *
		 * @param mixed $url the url to redirect too
		 * @param boolean $exit if script exists, defaults to true
		 * @return boolean on failure
		 */
		public function redirect($url, $exit = true){
			$url = Router::build_url($url);
			if($url){
				header("Location: " . $url);
				if($exit === true){
					exit;
				}
			}
		}

		/**
		* load
		*
		* Allows you to load a model into a controller, if it wasn't done
		* automatically.
		*
		* @param string $model name of model to load
		*/
		public function model($model){
			// Load in the Model class if needed
			Spine::loaded('Model', true);
			
			// Replace any hyphons with underscores
			$model = strtolower(str_replace('-', '_', $model));
			
			// Attempt to locate the appropriate model, first look in the application/models
			if(!in_array($model . '.php', array_map('basename', get_included_files()))){
				if(file_exists(APP_PATH . 'models/' . $model . '.php')){
					require(APP_PATH . 'models/' . $model . '.php');
				}else{
				// Couldn't find it there, perhaps they have nested it inside a folder but forgot to specify.
					if(file_exists(APP_PATH . 'models/' . $model . '/' . $model . '.php')){
						require(APP_PATH . 'models/' . $model . '/' . $model . '.php');
					}
				}
			}

			// Check if we loaded the model and that the class exists, if it doesn't don't worry. Their fault.
			$class = ucfirst($model) . 'Model';
			$model = ucfirst($model);

			if(class_exists($class, false)){
				// Excellent, pass in a few properties and set params to reference our params.
				$this->{$model} = new $class;
				$this->{$model}->name = $model;
				$this->{$model}->params =& $this->params;

				// Run the model.
				$this->{$model}->run();
			}else{
				return false;
			}
		}

    }
?>