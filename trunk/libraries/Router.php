<?php

	/**
     * Router.php
     *
     * Handles the requested URI. So it takes the input and determines
     * what the hell is going to happen. Turns all the jargon into
     * a nicely formatted array and such.
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

    class Router extends Object {

		// Current controller being used by system.
		public static $controller;

		// Stores the current URI request.
		public static $request;

		// Stores the name of the calling controller, prevents continuous loops.
		public static $controller_loop;

		// The available special requests.
		public static $special = array(
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
			// Blank array for request.
			Router::$request = array();

			// How is the page being requested.
			if(isset($_SERVER['QUERY_STRING']) && Config::read('General.enable_query_string') === false){
				// Using mod_rewrite for pretty urls.
				if(!isset($_GET['request'])){
					// Load default controller.
					$default = Config::read('General.default_controller');
					if(empty($default)){
						trigger_error('There was no default controller specified in the configuration file.', E_USER_ERROR);
					}else{
						Router::$request['controller'] = $default;
						Router::$request['method'] = 'index';
					}
				}else{
					// A request was sent to the browser.
					$uri = $this->check_special_requests($_GET['request']);
					$uri = array_clean(explode('/', $uri));

					if(count($uri) > 1){
						// We have a controller/method and possibly more.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = array_shift($uri);
						Router::$request['uri'] = $uri;
					}else{
						// Only a controller was specified.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = 'index';
					}
				}
			}elseif(Config::read('General.enable_query_string') === true){
				// Using index.php/controller/model
				if(isset($_SERVER['PATH_INFO'])){
					$uri = substr($_SERVER['PATH_INFO'], 0, 1) === '/' ? substr($_SERVER['PATH_INFO'], 1) : $_SERVER['PATH_INFO'];

					// Check for special requests.
					$uri = Router::check_special_requests($uri);

					// Explode the different parts of the URI and clean it.
					$uri = array_clean(explode('/', $uri));
					if(count($uri) > 1){
						// We have a controller/method and possibly more.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = array_shift($uri);
						Router::$request['uri'] = $uri;
					}else{
						// Only a controller was specified.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = 'index';
					}
				}elseif(isset($_SERVER['PHP_SELF'])){
					$uri = $_SERVER['PHP_SELF'];

					// Check for special requests.
					$uri = Router::check_special_requests($uri);

					// Grab folder that script is in.
					$folder = array_pop(array_clean(explode('/', BASE_PATH)));

					// Replace spine/index.php, explode and clean.
					$uri = str_replace($folder . '/index.php', '', $uri);
					$uri = array_clean(explode('/', $uri));

					if(count($uri) > 1){
						// We have a controller/method and possibly more.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = array_shift($uri);
						Router::$request['uri'] = $uri;
					}else{
						// Only a controller was specified.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = 'index';
					}
				}elseif(isset($_SERVER['REQUEST_URI'])){
					$uri = $_SERVER['REQUEST_URI'];

					// Check for special requests.
					$uri = Router::check_special_requests($uri);

					// Grab folder that script is in.
					$folder = array_pop(array_clean(explode('/', BASE_PATH)));

					// Replace spine/index.php, explode and clean.
					$uri = str_replace($folder . '/index.php', '', $uri);
					$uri = array_clean(explode('/', $uri));

					if(count($uri) > 1){
						// We have a controller/method and possibly more.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = array_shift($uri);
						Router::$request['uri'] = $uri;
					}else{
						// Only a controller was specified.
						Router::$request['controller'] = array_shift($uri);
						Router::$request['method'] = 'index';
					}
				}else{
					// This should not be reached, if it is please file a bug with any relevant info.
					trigger_error('Could not load a route using PATH_INFO, PHP_SELF or REQUEST_URI.', E_USER_ERROR);
				}

				// Make sure the controller isn't blank.
				if(empty(Router::$request['controller'])){
					// Set to default controller.
					Router::$request['controller'] = Config::read('General.default_controller');
				}

				if(!isset(Router::$request['controller']) || empty(Router::$request['controller'])){
					trigger_error('Router failed to load a controller.', E_USER_ERROR);
				}
			}else{
				// If this error is reached, the system was unable to find any method of building a route.
				trigger_error('Could not load a route using mod_rewrite or paths.', E_USER_ERROR);
			}

			// Check for a reserved word in the method.
			Router::$request['method'] = check_reserved_word(Router::$request['method']);
		}

		/**
		 * get_uri
		 *
		 * Return the URI of the original request.
		 *
		 * @return string
		 */
		public static function get_uri(){
			return Router::$request['controller'] . '/' . Router::$request['method'] . (!empty(Router::$request['uri']) ? '/' . implode('/', Router::$request['uri']) : '');
		}

		/**
		 * dispatch
		 *
		 * Dispatch the current controller to a new controller, 
		 *
		 * @param string $controller the name of the controller
		 * @param boolean $uri_controller if this is the URI controller being called from Spine.
		 * @param boolean $return_object return the new controller instead of running any methods
		 * @param string $caller the name of the controller that called dispatch
		 */
		public static function dispatch($controller, $uri_controller = true, $return_object = false, $caller = null){
			// Sometimes a controller may be in the form of folder/subfolder/Controller
			if(strstr($controller, '/') !== false){
				$tmp = explode('/', $controller);
				$controller = array_pop($tmp);
				$folder = implode('/', $tmp) . '/';
			}else{
				$folder = '';
			}

			// Now to find the controller.
			$controller = explode('-', strtolower($controller));
			$fn_controller = implode('_', $controller);
			$cn_controller = implode('', array_map('ucfirst', $controller)) . 'Controller';

			// Check to see if the current loaded controller is the same as the requesting controller.
			if(isset(Router::$controller) && get_class(Router::$controller) == $cn_controller){
				// It is the same, should we return it or perhaps they did something wrong.
				if($return_object === true){
					// Returning current controller.
					return Router::$controller;
				}else{
					// Abort if the controller is already loaded and not returning.
					return false;
				}
			}

			// If the controller is the last called controller the system is on a continuous loop.
			if($cn_controller == Router::$controller_loop){
				trigger_error('System prevented a continuous loop from occuring. You attempted to dispatch to a '
					. 'controller which was used to dispatch to the current controller.', E_USER_ERROR);
			}

			// Attempt to load the controller file.
			if(!file_exists(APP_PATH . 'controllers/' . $folder . $fn_controller . '.php')){
				// Could not locate a controller, perhaps try a method in the default controller?
				if(!file_exists(APP_PATH . 'controllers/' . Config::read('General.default_controller') . '.php') || Config::read('General.enable_method_fallback') === false){
						trigger_error("Could not find requested controller <strong>" . $cn_controller . "</strong>.<br />Location: " . BASE_PATH
							. APP_PATH . "controllers/" . $fn_controller . ".php", E_USER_ERROR);
				}else{
					// We found the default controller and we were allowed to fallback to it.
					if(!in_array($cn_controller, get_declared_classes())){
						require_once(APP_PATH . 'controllers/' . Config::read('General.default_controller') . '.php');
					}
					
					// Run any hooks on Controller.before
					Hooks::run('Controller.before');

					// Instantiate the default controller
					$cn_controller = explode('_', strtolower(Config::read('General.default_controller')));
					$cn_controller = implode('', array_map('ucfirst', $cn_controller)) . 'Controller';
					if(!class_exists($cn_controller, false)){
						trigger_error("Could not find the controller class <strong>" . $cn_controller . "</strong>.", E_USER_ERROR);
					}else{
						$controller = new $cn_controller;

						// Check if there is a method by the name of the controller, falling back.
						if(method_exists($controller, $fn_controller)){
							// The method is now the controller.
							Router::$request['method'] = $fn_controller;
							// And the controller is the default controller.
							Router::$request['controller'] = Config::read('General.default_controller');
						}else{
							// Failed to fallback to the default controller.
							trigger_error("Attempted to fallback to a method but failed to locate <strong>" . $fn_controller . "()</strong> in default controller.", E_USER_ERROR);
						}
					}
				}
			}else{
				// The requested controller exists.
				if(!in_array($cn_controller, get_declared_classes())){
					require_once(APP_PATH . 'controllers/' . $folder . $fn_controller . '.php');
				}
				
				if(!class_exists($cn_controller, false)){
					trigger_error("Could not find the controller class <strong>" . $cn_controller . "</strong> in " . APP_PATH . "controllers/" . $folder . $fn_controller . ".php.", E_USER_ERROR);
				}else{
					// Run any hooks on Controller.before
					Hooks::run('Controller.before');

					// Instantiate the controller
					$controller = new $cn_controller;
				}
			}

			/**
			 * If we have made it this far, the controller has been loaded successfully and stored in
			 * $controller. We can process the rest of the dispatch.
			 */

			// Set the $controller_loop property here so we can ensure we don't go on a never ending loop.
			if(!empty($caller)){
				Router::$controller_loop = (strstr($caller, 'Controller') ? $caller : $caller . 'Controller');
			}

			// Are we to set the controller property in Router?
			if($uri_controller){
				// Set the controller property to the controller instance.
				Router::$controller = $controller;
			}

			// Run any hooks on Controller.afterConstruct
			Hooks::run('Controller.afterConstruct');

			// Return this new object so that it can be used elswhere.
			if($return_object){
				return $controller;
			}else{
				// Have they enabled method overwriting in the controller.
				if(isset($controller->enable_method_overwrite) && $controller->enable_method_overwrite === true){
					// Method Overwriting is enabled, fire the index method.
					$controller->index();
				}else{
					// No overwriting, make sure that the method we want exists.
					if(method_exists($controller, Router::$request['method'])){
						// Found the method, fire it.
						$controller->{Router::$request['method']}();
					}else{
						// No method, all that for nothing.
						trigger_error('Invalid method supplied. Failed to find method <strong>' . Router::$request['method']
							. '()</strong> in <strong>' . $cn_controller . '</strong>.', E_USER_ERROR);
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
		public static function check_special_requests($url){
			$patterns = array();
			foreach(Router::$special as $request){
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
							Template::delete_cache();
						}else{
							Template::delete_cache($url);
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
						if(Spine::loaded('Session')){
							Session::destroy();
						}
					break;
					// clear-cookies
					case 'clear-cookies':
						if(Spine::loaded('Cookie')){
							Cookie::clear();
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
		public static function build_url($url){
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

				$url = implode('/', $url);
				if(Config::read('General.enable_query_string')){
					$url = 'index.php/' . $url;
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
					// Return in either query string format or pretty url.
					if(Config::read('General.enable_query_string')){
						if($url === '/'){
							return SYS_URL . 'index.php';
						}else{
							return SYS_URL . 'index.php/' . implode('/', array_clean(explode('/', $url)));
						}
					}else{
						return SYS_URL . implode('/', array_clean(explode('/', $url)));
					}
				}
			}

			return false;
		}
	
    }

?>