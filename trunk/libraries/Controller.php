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

		protected $registry;
		protected $view;
		protected $session;
		protected $cookie;
		protected $helpers;
		public $params = array(
			'request' => false,
			'limit' => 10,
			'order' => 'desc',
			'sort' => 'id'
		);
		
		public function __construct(){
			$this->registry =& Registry::get_instance();

			/**
			 * Load the view library and pass the helpers in by reference.
			 */
			$this->View = $this->registry->is_library_loaded('View', true, true);
			$this->View->helpers = & $this->helpers;

			/**
			 * Session & Cookies
			 * Make them easier to access if they have been loaded.
			 */
			$this->Session = $this->registry->is_library_loaded('Session', false, true);
			$this->Cookie = $this->registry->is_library_loaded('Cookie', false, true);

			/**
			 * Load in any other libraries that may have been set, only if the config options
			 * has been enabled though.
			 */
			if(Config::read('Library.set_controller')){
				foreach($this->registry->libraries as $lib){
					if(!isset($this->{$lib}) && isset($this->registry->{$lib})){
						$this->{$lib} =& $this->registry->{$lib};
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
				$this->registry->is_library_loaded('Model', true, false, false);

				$this->load_model($this->name);
			}

			// Run any hooks on Controller.afterConstruct
			Hooks::run('Controller.afterConstruct');
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
		 * @param string $section the section to write to
		 * @param string $content the content to write to the section
		 * @param boolean $overwrite if section value is overwitten
		 */
		public function write($section, $content, $overwrite = false){
			$this->registry->Template->write($section, $content, $overwrite);
		}

		/**
		 * write_view
		 *
		 * Similar to write, except this allows you to write a view file to a section.
		 * Although possible with write, it requires a few extra lines.
		 *
		 * @param string $section name of section
		 * @param string $view name of view file to load
		 * @param array $variables array of variables to set in view file
		 * @param boolean $overwrite if section value is overwritten
		 */
		public function write_view($section, $view, $variables = array(), $overwrite = false){
			$this->registry->Template->write($section, $this->View->load($view, $variables), $overwrite);
		}

		/**
		 * set_header
		 *
		 * Alias of Template::set_header
		 *
		 * @param array $header the header array to be set
		 */
		public function set_header($action, $string = '', $replace = false){
			$this->registry->Template->set_header($action, $string, $replace);
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
			$this->registry->Router->dispatch($controller, false, isset($this->name) ? $this->name : '');
		}

		/**
		 * crumb
		 *
		 * Alias of Breadcrumbs::crumb
		 */
		public function crumb($name, $url = null){
			if($this->registry->is_library_loaded('Breadcrumbs')){
				$this->registry->Breadcrumbs->crumb($name, $url);
			}else{
				return null;
			}
		}

		/**
		 * load_plugin
		 *
		 * Alias of Plugin::load
		 */
		public function load_plugin($plugin){
			$this->registry->Plugin->load($plugin);
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
			if(is_array($url)){
				// The URL is formatted in an array.
				
				if(!isset($url['controller'])){
					return false;
				}
				if(!isset($url['action']) && count($url) > 1){
					// If no action was set, and there is something else in the URL
					// we need to manually add in an action if using query string, default to index.
					$url = array_slice($url, 0, 1, true) + array('action' => 'index') + array_slice($url, 1, null, true);
				}

				$replacers = array(
					'controller' => 'c',
					'action' => 'm'
				);
				
				if(Config::read('General.enable_query_string')){
					// Query String is enabled, let's build it.
					$tmp = array();
					foreach($url as $key => $value){
						$tmp[] = (isset($replacers[$key]) ? $replacers[$key] : $key) . '=' . $value;
					}
					$url = 'index.php?' . implode('&', $tmp);
				}else{
					// Pretty URLs, a lot nicer.
					$url = implode('/', $url);
				}

				if(isset($url)){
					header('Location: ' . Config::read('General.system_url') . $url);
					if($exit){
						exit;
					}
				}
			}else{
				// The URL is a string, determine if it is a relative or absolute url.
				$check = parse_url($url);
				if(isset($check['scheme']) || isset($check['host'])){
					// It's an absolute URL
					header('Location: ' . $url);
					if($exit){
						exit;
					}
				}else{
					// It's a relative URL
					$url = (substr($url, 0, 1) === '/' ? substr($url, 1) : $url);
					header('Location: ' . Config::read('General.system_url') . $url);
					if($exit){
						exit;
					}
				}
			}
		}

		/**
		* load_model
		*
		* Allows you to load a model into a controller, if it wasn't done
		* automatically.
		*
		* @param string $model name of model to load
		*/
		public function load_model($model){
			// Load in the Model class if needed
			$this->registry->is_library_loaded('Model', true, false, false);
			
			// Replace any hyphons with underscores
			$model = str_replace('-', '_', $model);

			// Attempt to locate the appropriate model, first look in the application/models
			if(file_exists(APP_PATH . 'models/' . $model . '.model.php')){
				require(APP_PATH . 'models/' . $model . '.model.php');
			}else{
			// Couldn't find it there, perhaps they have nested it inside a folder.
				if(file_exists(APP_PATH . 'models/' . $model . '/' . $model . '.model.php')){
					require(APP_PATH . 'models/' . $model . '/' . $model . 'model.php');
				}
			}

			// Check if we loaded the model and that the class exists, if it doesn't don't worry. Their fault.
			$class = ucfirst($model) . 'Model';
			$model = ucfirst($model);

			if(class_exists($class, false)){
				$this->{$model} = new $class();
				$this->{$model}->params =& $this->params;
			}else{
				return false;
			}
		}

    }
?>