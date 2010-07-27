<?php

    /**
    * Router.php
    *
    * Handles the requested URI. So it takes the input and determines
    * what the hell is going to happen. Turns all the jargon into
    * a nicely formatted array and such.
    */

    class Router extends Object {

		public $get;
		public $request = array();
		public $last_caller;
		public $special = array(
			'clear-cache',
			'clear-sessions',
			'clear-cookies',
			'config',
			'template'
		);

		/**
		 * route
		 *
		 * This is the method that determines where and what will happen.
		 * Sets the controller, the method to run and cleans up any
		 * remaining information for the queryString property.
		 */
		public function route(){
			if(!isset($_GET['c'])){
			// No URI information set by user.
				if(Config::read('General.default_controller') == ''){
					trigger_error('There was no default controller specified in the configuration.', E_USER_ERROR);
				}else{
					$this->request['controller'] = Config::read('General.default_controller');
					$this->request['method'] = 'index';
				}
			}else{
				if(Config::read('General.enable_query_string') === true){
					// Using query string in the URL
					// Check for Special Requests first.
					$url = $this->check_special_requests($_SERVER['QUERY_STRING']);
					$qs = explode('&', $url);

					if(isset($qs[0]) && strstr($qs[0], '/') === false){
						foreach($qs as $string){
							// Loop through each result, c and m become controller and model.
							$tmp = explode('=', $string);
							if(in_array($tmp[0], array('c','m'))){
								$this->request[($tmp[0] == 'c' ? 'controller' : 'method')] = str_replace('-', '_', $tmp[1]);
							}else{
								$this->request['uri'][] = $tmp[1];
							}
						}

						// Make sure a method was set.
						if(!isset($this->request['method'])){
							$this->request['method'] = 'index';
						}
					}
				}

				if(empty($this->request)){
					// Using pretty URLs, determine route to take.
					// Check for Special Requests first.
					$url = $this->check_special_requests($_GET['c']);
					$this->get = array_clean(explode('/', $url), true, true);

					// The first element in the array is, of course, the controller.
					$this->request['controller'] = array_shift($this->get);

					// Now, is there a method supplied?
					if(empty($this->get)){
						// Nope, index is the default.
						$this->request['method'] = 'index';
					}else{
						// Yep!
						$this->request['method'] = str_replace('-', '_', array_shift($this->get));
					}

					// Finally, even if the array is empty, set the URI element to the remaining.
					$this->request['uri'] = $this->get;
				}
			}

			// Check for a reserved word in the method.
			$this->request['method'] = check_reserved_word($this->request['method']);
		}

		/**
		 * uri
		 *
		 * Return the URI of the original request.
		 *
		 * @return string
		 */
		public function uri(){
			return $this->request['controller'] . '/' . $this->request['method'] . (!empty($this->request['uri']) ? '/' . implode('/', $this->request['uri']) : '');
		}

		/**
		 * dispatch
		 *
		 * Dispatch the current controller to a new controller, 
		 *
		 * @param string $controller the name of the controller
		 * @param boolean $allow_default if system can revert to default controller
		 * @param string $caller the name of the controller that called dispatch
		 * @param boolean $registry if this is to set the registry controller property
		 * @param boolean $return_object return the new controller instead of running any methods
		 */
		public function dispatch($controller, $allow_default = false, $caller = null, $registry = true, $return_object = false){
			// Sometimes a controller may be in the form of folder/subfolder/Controller
			if(strstr($controller, '/') !== false){
				$tmp = explode('/', $controller);
				$controller = array_pop($tmp);
				$folder = implode('/', $tmp) . '/';
			}else{
				$folder = '';
			}

			// Now to find the controller.
			$fn_controller = strtolower($controller);
			$cn_controller = ucfirst(strtolower($controller)) . 'Controller';

			// Sometimes the controller may already be loaded.
			// For instance if using 'request' in a section.
			if(isset($this->registry->controller) && get_class($this->registry->controller) == $cn_controller){
				if($return_object === true){
					return $this->registry->controller;
				}else{
					// Abort if the controller is already loaded and not returning.
					return false;
				}
			}

			// Make sure that the controller isn't the last caller, this is bad!
			if($cn_controller == $this->last_caller){
				trigger_error('System prevented a continuous loop from occuring. You attempted to dispatch to a '
					. 'controller which was used to dispatch to the current controller.', E_USER_ERROR);
			}

			if(!file_exists(APP_PATH . 'controllers/' . $folder . $fn_controller . '.controller.php')){

				// Could not locate a controller, perhaps try a method in the default controller?
				if(!file_exists(APP_PATH . 'controllers/' . Config::read('General.default_controller') . '.controller.php')
					|| $allow_default === false){
						trigger_error("Could not find requested controller <strong>" . $cn_controller . "</strong>.\nLocation: '" . BASE_PATH
							. APP_PATH . "controllers/" . $fn_controller . ".controller.php'", E_USER_ERROR);
				}else{
					require(APP_PATH . 'controllers/' . Config::read('General.default_controller') . '.controller.php');

					if(!class_exists(ucfirst(strtolower(Config::read('General.default_controller'))) . 'Controller', false)){
						trigger_error("Could not find the controller class <strong>" 
							. ucfirst(strtolower(Config::read('General.default_controller')))
							. "Controller</strong>.", E_USER_ERROR);
					}

					// Instantiate the default controller
					$cn_controller = ucfirst(strtolower(Config::read('General.default_controller'))) . 'Controller';
					$controller = new $cn_controller();

					// Set the fallback to the global fallback variable.
					$fallback = Config::read('General.enable_method_callback');

					// Check if the global value has been overwritten by the local value
					if(isset($controller->enable_method_fallback)){
						if($controller->enable_method_fallback === false){
							trigger_error("Could not find requested controller <strong>"
							. $cn_controller . "</strong>.\nLocation: '" . BASE_PATH
							. APP_PATH . "controllers/" . $fn_controller . ".controller.php'", E_USER_ERROR);
						}else{
							$fallback = true;
						}
					}

					// Check if there is a method by the name of the controller, falling back.
					if(method_exists($controller, $this->request['controller']) && $fallback === true){
						$this->request['method'] = $this->request['controller'];
						$this->request['controller'] = Config::read('General.default_controller');
					}else{
						trigger_error("Attempted to fall back to a method but failed to locate method in default controller", E_USER_ERROR);
					}
				}
			}else{

				require(APP_PATH . 'controllers/' . $folder . $fn_controller . '.controller.php');

				if(!class_exists($cn_controller, false)){
					trigger_error("Could not find the controller class <strong>" . $cn_controller . "</strong>.", E_USER_ERROR);
				}

				// Instantiate the controller
				$controller = new $cn_controller();
			}

			// Set the last caller property here so we can ensure we don't go on a never ending loop.
			if(!empty($caller)){
				$this->last_caller = (strstr($caller, 'Controller') ? $caller : $caller . 'Controller');
			}

			// If this is coming from the registry, set the controller.
			if($registry){
				$this->registry->controller =& $controller;
			}

			// Returning the new object?
			if($return_object === true){
				return $controller;
			}else{
				/**
				* Constructor
				*
				* Some controllers may want a custom constructor, and since we have used the
				* __construct() in the abstract class, we cannot redeclare it without the
				* system falling apart. So, to compensate, we incorporate the __constructor() method.
				* If a controller contains this method, let's run it.
				*/
				if(method_exists($controller, '__constructor')){
					$controller->__constructor();
				}

				/**
				* Overwriting
				*
				* Some controllers allow the overwriting of the index method.
				* For example, if the user accesses http://www.website.com/news/this-is-an-article the
				* system would look for 'this-is-an-article' as the method. However, in the controller
				* if the method_overwrite property is set to true, we can hand straight to the index
				* method.
				*/
				if(isset($controller->enable_method_overwrite) && $this->registry->controller->enable_method_overwrite === true){
					$controller->index();
				}else{
					// No overwriting, make sure that the method we want exists.
					if(method_exists($controller, $this->request['method'])){
						$controller->{$this->request['method']}();
					}else{
						trigger_error('Invalid method supplied. Failed to find method <strong>' . $this->request['method']
							. '()</strong> in <strong>' . ucfirst(strtolower($this->request['controller'])) . 'Controller</strong>.', E_USER_ERROR);
					}
				}
			}
		}

		/**
		 * check_special_requests
		 *
		 * Check a URL for special requests.
		 *
		 * @param string $url
		 */
		public function check_special_requests($url){
			$patterns = array();
			foreach($this->special as $request){
				$patterns[] = preg_quote(':' . $request);
			}
			$patterns = '#(' . implode('|', $patterns) . ')(.*)#';
			if(preg_match($patterns, $url, $match)){
				// Special Request found.
				$request = substr($match[1], 1);
				$match[2] = substr($match[2], 1);
				
				if($request != 'config'){
					// Because config options are different, we won't create any options yet.
					$options = array_clean(explode(',', stripslashes($match[2])));
				}
				$url = preg_replace('#' . preg_quote($match[0]) . '#', '', $url);
				
				switch($request){
					// clear-cache
					case 'clear-cache':
						/**
						 * Format:
						 * clear-cache
						 * clear-cache,all
						 */
						if(in_array('all', $options)){
							// Delete all cached files.
							$this->registry->Template->delete_cache();
						}else{
							$this->registry->Template->delete_cache($url);
						}
					break;
					// config
					case 'config':
						/**
						 * Format:
						 * config,[Variable.name,value],[Variable.name,value],[...]
						 * config,Variable.name,value
						 */
						if(preg_match_all('#\[(.*?)\,(.*?)\]#', $match[2], $variables)){
							// Loop over the variables, check if they exist then update them.
							foreach($variables[1] as $key => $name){
								if(Config::read($name) !== null){
									// Variable exists, update.
									$var = $variables[2][$key];
									if($var === 'true'){
										$var = true;
									}elseif($var === 'false'){
										$var = false;
									}
									Config::write($name, $var);
								}
							}
						}else{
							// Single variable
							if(isset($match[2]) && !empty($match[2])){
								preg_match('#(.+)\,(.+)#', $match[2], $tmp);
								
								list($full, $variable, $value) = $tmp;
								if(Config::read($variable) !== null){
									if($value === 'true'){
										$value = true;
									}elseif($value === 'false'){
										$value = false;
									}
									Config::write($variable, $value);
								}
							}
						}
					break;
					// template
					case 'template':
						/**
						 * Format:
						 * template,folder
						 * template,folder,type
						 */
						if(isset($options[0]) && !empty($options[0])){
							$folder = $options[0];

							if(file_exists(APP_PATH . 'templates/' . $folder . '/')){
								$type = Config::read('Template.default_template');
								$type = $type[1];

								if(isset($options[1]) && !empty($options[1])){
									if(file_exists(APP_PATH . 'templates/' . $folder . '/' . $options[1] . '.php')){
										$type = $options[1];
									}
								}

								Config::write('Template.default_template', array($folder, $type));
							}
						}
					break;
					// clear-sessions
					case 'clear-sessions':
						/**
						 * Format:
						 * clear-sessions
						 */
						if($this->registry->is_library_loaded('Session')){
							$this->registry->Session->destroy();
						}
					break;
					// clear-cookies
					case 'clear-cookies':
						if($this->registry->is_library_loaded('Cookie')){
							$this->registry->Cookie->clear();
						}
					break;
				}

				return $url;
			}else{
				return $url;
			}
		}

		/**
		 * build_url
		 *
		 * Build a URL from either an array or from a string.
		 *
		 * @param mixed $url
		 * @return string
		 */
		public function build_url($url){
			if(is_array($url)){
				// It's an array of URL elements.
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
					return SYS_URL . $url;
				}
			}else{
				// It's a string, format of controller/method. Check if it's external.
				// No modifying should be required. Create a clean version basically.
				if(preg_match('#^(http|https|ftp|file)\:\/\/#i', $url)){
					return $url;
				}else{
					return SYS_URL . implode('/', array_clean(explode('/', $url)));
				}
			}
		}
	
    }

?>