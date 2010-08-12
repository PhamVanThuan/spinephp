<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Request.php
	 *
	 * This library handles incoming requests from the user. Uses the Router class
	 * to determine which controller to send the request too.
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
	
	class Request {

		/**
		 * @var string $method http request method
		 */
		protected static $method = 'GET';

		/**
		 * @var string $protocol server protocol
		 */
		protected static $protocol = 'http';

		/**
		 * @var array $agent user agent
		 */
		protected static $agent = '';

		/**
		 * @var boolean $ajax xmlHttpRequest
		 */
		protected static $ajax = false;

		/**
		 * @var string $referer http referer
		 */
		protected static $referer = '';

		/**
		 * @var string $ip client ip
		 */
		protected static $ip = '';

		/**
		 * @var array $instance array of request instance objects
		 */
		public static $instance;

		/**
		 * @var object $current controller object
		 */
		public static $current;

		/**
		 * @var array $special special requests
		 */
		public static $special = array(
			'clear-cache',
			'clear-sessions',
			'clear-cookies',
			'config',
			'template'
		);

		/**
		 * instance
		 *
		 * Create an instance for the current URI or a passed in URI.
		 * The instance is created under the set name.
		 *
		 * @param string $name
		 * @param mixed $uri
		 * @return object
		 */
		public static function instance($name, $uri = false){
			// Set the servers request method.
			if(isset($_SERVER['REQUEST_METHOD'])){
				Request::$method = $_SERVER['REQUEST_METHOD'];
			}

			// Set the servers protocol.
			if(isset($_SERVER['HTTPS'])){
				Request::$method = 'https';
			}

			// Was the request from AJAX.
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest'){
				Request::$ajax = true;
			}

			// Set the servers referer.
			if(isset($_SERVER['HTTP_REFERER'])){
				Request::$referer = $_SERVER['HTTP_REFERER'];
			}

			// Set the user agent.
			if(isset($_SERVER['HTTP_USER_AGENT'])){
				Request::$agent = $_SERVER['HTTP_USER_AGENT'];
			}

			// Set the users IP address.
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
				Request::$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
				Request::$ip = $_SERVER['HTTP_CLIENT_IP'];
			}elseif(isset($_SERVER['REMOTE_ADDR'])){
				Request::$ip = $_SERVER['REMOTE_ADDR'];
			}

			if($uri === false || !is_string($uri)){
				if(!empty($_SERVER['PATH_INFO'])){
					// If the server has set PATH_INFO, use that.
					$uri = $_SERVER['PATH_INFO'];
				}else{
					if(isset($_SERVER['REQUEST_URI'])){
						// First choice is REQUEST_URI, this also contains query string.
						$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
					}elseif(isset($_SERVER['PHP_SELF'])){
						// Second choice is PHP_SELF
						$uri = $_SERVER['PHP_SELF'];
					}else{
						trigger_error('Unable to detect URI based on PATH_INFO, REQUEST_URI or PHP_SELF.', E_USER_WARNING);
					}

					$script_name = array_pop(explode('/', $_SERVER['SCRIPT_NAME']));
					$folder_name = substr($_SERVER['SCRIPT_NAME'], 0, (strlen($script_name) * -1));

					// Remove directory from string.
					if(strpos($uri, $folder_name) !== false){
						$uri = substr($uri, strlen($folder_name));
					}

					// Remove script name from string.
					if(strpos($uri, $script_name) !== false){
						$uri = substr($uri, strlen($script_name));
					}
				}
			}

			// Replace double slashes with single
			$uri = preg_replace('#//+#', '/', $uri);

			// Trim any slashes
			$uri = trim($uri, '/');

			// Create the new request.
			Request::$instance[$name] = new Request($uri);
			if(is_object(Request::$instance[$name])){
				return Request::$instance[$name];
			}else{
				return false;
			}
		}

		/**
		 * check_special_requests
		 *
		 * Peform a regular expression check on a URI to match
		 * any special requests. Requests are dealt with.
		 *
		 * @param string $uri
		 * @return string
		 */
		public static function check_special_requests($uri){
			$patterns = array();
			foreach(Request::$special as $request){
				$patterns[] = '\b' . preg_quote($request) . '\b';
			}
			$patterns = '#(' . implode('|', $patterns) . ')(.*)#';
			
			if(preg_match($patterns, $uri, $match)){
				// Special Request found.
				$request = $match[1];
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
		 * build_uri
		 *
		 * Build a URI from either an array or from a string. If the name
		 * of a route is passed in, the URI will be constructed from the
		 * route. If no URI can be built from a route it will default to the
		 * standard URI.
		 *
		 * @param mixed $url
		 * @param string $route
		 * @return string
		 */
		public static function build_uri($uri, $route = null){
			if(is_array($uri)){
				// Constructing from a route?
				if(!empty($route)){
					if(($__route = Router::get($route)) !== false){
						// We found our route, attempt to build from the route.
						if(($__uri = $__route->build_uri($uri)) !== false){
							// This config settings determines if we should use index.php/ or not.
							if(Config::read('General.enable_friendly_urls')){
								return SYS_URL . $__uri;
							}else{
								return SYS_URL . 'index.php/' . $__uri;
							}
						}
					}
				}

				// No route, make sure we have a controller then.
				if(!isset($uri['controller'])){
					return false;
				}

				if(!isset($uri['action']) && count($uri) > 1){
					// If no action was set, and there is something else in the URL
					// we need to manually add in an action if using query string, default to index.
					$uri = array_slice($uri, 0, 1, true) + array('action' => 'index') + array_slice($uri, 1, null, true);
				}

				$uri = implode('/', $uri);
				if(!Config::read('General.enable_friendly_urls')){
					$uri = 'index.php/' . $uri;
				}

				if(isset($uri)){
					return SYS_URL . $uri;
				}
			}else{
				// It's a string, format of controller/method. Check if it's external.
				// No modifying should be required. Create a clean version basically.
				if(preg_match('#^(http|https|ftp|file)\:\/\/#i', $uri)){
					return $uri;
				}else{
					// Return in either query string format or pretty url.
					if(!Config::read('General.enable_friendly_urls')){
						if($uri === '/'){
							return SYS_URL . 'index.php';
						}else{
							return SYS_URL . 'index.php/' . implode('/', array_clean(explode('/', $uri)));
						}
					}else{
						return SYS_URL . implode('/', array_clean(explode('/', $uri)));
					}
				}
			}

			// Failed to build a URI from input.
			return false;
		}

		/**
		 * Methods below are for a request when it has been
		 * created via Request::instance.
		 */

		/**
		 * @var string $__constroller name of controlle
		 */
		public $__controller;

		/**
		 * @var string $__action name of action
		 */
		public $__action;

		/**
		 * @var string $__folder name of folder
		 */
		public $__folder;

		/**
		 * @var string $__file name of controller file
		 */
		public $__file;

		/**
		 * @var array $__route array of route information, will be unset
		 */
		public $__route;

		/**
		 * @var array $__params array of params to be passed into method
		 */
		public $__params;

		/**
		 * @var string $__uri uri used for request
		 */
		public $__uri;

		/**
		 * __construct
		 *
		 * Creates a new request instance, matching the requested URI
		 * against routes specified by the router.
		 *
		 * @param string $uri
		 * @return boolean
		 */
		public function __construct($uri){
			// Attempt to match it with a route.
			if(($this->__route = Router::match($uri)) !== false){
				// We got a route matched.
				$this->__controller = $this->__route['controller'];

				if(isset($this->__route['action'])){
					$this->__action = $this->__route['action'];
				}else{
					$this->__action = 'index';
				}

				// Check if a directory was passed.
				if(isset($this->__route['directory'])){
					$this->__folder = $this->__route['directory'] . (substr($this->__route['directory'], -1) === '/' ? '' : '/');
				}else{
					$this->__folder = $this->__route['directory'] = null;
				}
				
				unset($this->__route['controller'], $this->__route['action'], $this->__route['directory']);

				// The remaining variables go into the params property.
				$__params = $this->__route['params'];
				unset($this->__route['params']);

				// Place the params in the parent level array.
				$this->__params = $this->__route + $__params;
				unset($this->__route);

				// Store the URI.
				$this->__uri = $uri;

				// Do we have any special requests to run.
				if(isset($this->__params['special']) && !empty($this->__params['special'])){
					Request::check_special_requests($this->__params['special']);
				}

				// Return true, we found our route matching the URI.
				return true;
				
			}else{
				// No route was found for the URI.
				trigger_error('Could not find a route for the requested URI: ' . $uri, E_USER_ERROR);
			}

			// Return false, no route.
			return false;
		}

		/**
		 * dispatch
		 *
		 * This method dispatches the current request, loading up the controller
		 * and firing the action, passing in any params to the action. The object,
		 * once found, can be requested to be returned.
		 *
		 * @param boolean $object
		 * @return boolean
		 */
		public function dispatch($object = false){
			if(file_exists(APP_PATH . 'controllers/' . $this->__folder . Inflector::filename($this->__controller) . '.php')){
				// Found the controller in either the parent controllers directory or a specified directory from $params['directory']
				$this->__file = Inflector::filename($this->__controller);
				$this->__controller = Inflector::classname($this->__controller);
				$this->__action = Inflector::methodname($this->__action);
			}else{
				$directory = Inflector::filename($this->__controller) . '/';
				$file = Inflector::filename($this->__action);
				while(!file_exists(APP_PATH . 'controllers/' . $directory . $file . '.php')){
					if(empty($this->__params)){
						trigger_error('Could not locate requested controller file /' . APP_PATH . 'controllers/' . $this->__folder . Inflector::filename($this->__controller) . '.php', E_USER_ERROR);
						return;
					}

					// Set the directory to include the file as well.
					$directory .= $file . '/';
					
					// The new file is the next element in the array.
					$file = array_shift($this->__params);
				}

				// Found it in a subdirectory if we made it this far.
				$this->__folder = Inflector::filename($directory);
				$this->__file = Inflector::filename($file);
				$this->__controller = Inflector::classname($file);

				if(!empty($this->__params)){
					$this->__action = Inflector::methodname(array_shift($this->__params));
				}else{
					$this->__action = 'index';
				}
			}

			// Require the controller file and set the controller class name.
			require_once(APP_PATH . 'controllers/' . $this->__folder . $this->__file . '.php');
			$cn_controller = $this->__controller . 'Controller';

			// Ensure that the class exists.
			if(!class_exists($cn_controller, false)){
				// Invalid class name.
				trigger_error('Could not instantiate class ' . $cn_controller . ' in /' . APP_PATH . 'controllers/' . $this->__folder . $this->__file . '.php', E_USER_ERROR);
			}else{
				// Use Reflection so we can pass args.
				$reflection = new ReflectionClass($cn_controller);

				if($reflection->isAbstract()){
					trigger_error('Controller class you are instantiating is abstract, controllers cannot be abstract.', E_USER_ERROR);
					return;
				}

				// Run any hooks for Controller.before
				Hooks::run('Controller.before');

				// Create the new controller.
				$obj = new $cn_controller;

				if(!$object){
					// If not returning, set the current controller to this one.
					Request::$current = $obj;
				}

				// Run any hooks for Controller.afterConstruct
				Hooks::run('Controller.afterConstruct');

				// Class is valid and all, now are we returning the object.
				if($object){
					return $obj;
				}

				// Ensure that requested method exists in class.
				if(!$reflection->hasMethod($this->__action)){
					// Could not locate method.
					trigger_error('Could not locate ' . $this->__action . '() in ' . $cn_controller . '.', E_USER_ERROR);
				}else{
					// Method exists. Fire method with params.
					$reflection->getMethod($this->__action)->invokeArgs(Request::$current, $this->__params);

					// Run any hooks on Controller.after
					Hooks::run('Controller.after');
				}
			}
			
		}

		/**
		 * get_uri
		 *
		 * @return string
		 */
		public function get_uri(){
			return $this->__uri;
		}

		/**
		 * get_controller
		 *
		 * @return string
		 */
		public function get_controller(){
			return $this->__controller;
		}

		/**
		 * get_action
		 *
		 * @return string
		 */
		public function get_action(){
			return $this->__action;
		}

		/**
		 * get_folder
		 *
		 * @return string
		 */
		public function get_folder(){
			return $this->__folder;
		}

		/**
		 * get_params
		 *
		 * @return array
		 */
		public function get_params(){
			return $this->__params;
		}

	}

?>