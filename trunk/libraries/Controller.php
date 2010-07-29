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

		protected $spine;
		protected $helpers;

		public $params = array(
			'request' => false,
			'limit' => 10,
			'order' => 'desc',
			'sort' => 'id'
		);

		public $parser;
		public $order;
		public $limit;
		public $sort;
		
		public function __construct(){
			$this->spine =& Spine::get_instance();
			
			/**
			 * Load the view library and pass the helpers in by reference, but what if we are using a parser?
			 */
			$parser = Config::read('Template.parser');
			if(!empty($parser)){
				if(file_exists(APP_PATH . 'templates/parser/' . $parser[0])){
					require(APP_PATH . 'templates/parser/' . $parser[0]);
					$this->parser = new $parser[1];
				}else{
					trigger_error('Could not locate requested parser <strong>' . $parser[0] . '</strong>.', E_USER_ERROR);
				}
			}
			
			$this->View = $this->spine->is_library_loaded('View', true, true);
			$this->View->helpers = & $this->helpers;

			/**
			 * Session & Cookies
			 * Make them easier to access if they have been loaded.
			 */
			$this->Session = $this->spine->is_library_loaded('Session', false, true);
			$this->Cookie = $this->spine->is_library_loaded('Cookie', false, true);

			/**
			 * Load in any other libraries that may have been set, only if the config options
			 * has been enabled though.
			 */
			if(Config::read('Library.set_controller')){
				foreach($this->spine->libraries as $lib){
					if(!isset($this->{$lib}) && isset($this->spine->{$lib})){
						$this->{$lib} =& $this->spine->{$lib};
					}
				}
			}

			/**
			* Model Autoloading
			*
			* In some cases, a controller may want it's associated model loaded. This is usually
			* the case when the naming is the same, so if they loaded the HomeController, they would
			* want to load the HomeModel.
			* To enable autoloading of models, in the controller the $autoload_model property can be
			* set to true.
			*/
			
			if(isset($this->enable_model_autoload) && $this->enable_model_autoload === true){
				// Load in the Model Abstract Library
				$this->spine->is_library_loaded('Model', true, false, false);
				if(isset($this->name)){
					$this->load($this->name);
				}
			}
		}

		/**
		* The index method is abstract, meaning classes that extends this
		* class must have this method, or errors will start popping up.
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
			$this->spine->Template->write($variable, $content, $overwrite);
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
			$this->spine->Template->write($variable, $this->View->load($view), $overwrite);
		}

		/**
		 * set_header
		 *
		 * Alias of Template::set_header
		 */
		public function set_header($action, $string = null, $replace = false){
			$this->spine->Template->set_header($action, $string, $replace);
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
			$this->spine->Router->dispatch($controller, false, isset($this->name) ? $this->name : '');
		}

		/**
		 * crumb
		 *
		 * Alias of Breadcrumbs::crumb
		 */
		public function crumb($name, $url = null){
			if($this->spine->is_library_loaded('Breadcrumbs')){
				$this->spine->Breadcrumbs->crumb($name, $url);
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
			$this->spine->Plugin->load($plugin);
		}

		/**
		 * prepare
		 *
		 * Alias of Template::prepare
		 */
		public function prepare(){
			$this->spine->Template->prepare();
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
			$url = $this->spine->Router->build_url($url);
			header("Location: " . $url);
			if($exit === true){
				exit;
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
		public function load($model){
			// Load in the Model class if needed
			$this->spine->is_library_loaded('Model', true, false, false);
			
			// Replace any hyphons with underscores
			$model = strtolower(str_replace('-', '_', $model));
			// Attempt to locate the appropriate model, first look in the application/models
			if(!in_array($model . '.model.php', array_map('basename', get_included_files()))){
				if(file_exists(APP_PATH . 'models/' . $model . '.model.php')){
					require(APP_PATH . 'models/' . $model . '.model.php');
				}else{
				// Couldn't find it there, perhaps they have nested it inside a folder but forgot to specify.
					if(file_exists(APP_PATH . 'models/' . $model . '/' . $model . '.model.php')){
						require(APP_PATH . 'models/' . $model . '/' . $model . 'model.php');
					}
				}
			}

			// Check if we loaded the model and that the class exists, if it doesn't don't worry. Their fault.
			$class = ucfirst($model) . 'Model';
			$model = ucfirst($model);

			if(class_exists($class, false)){
				$this->{$model} = new $class;
				$this->{$model}->name = $model;
				$this->{$model}->params =& $this->params;

				// Setup the model.
				$this->{$model}->setup();
			}else{
				return false;
			}
		}

    }
?>