<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Router.php
	 *
	 * Manages registered routes, including registering routes, retrieving routes
	 * and creating URIs based off a route. Router determines the controller and
	 * action.
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

	class Router {

		/**
		 * @var array $routes array of routes that have been registered
		 */
		public static $routes = array();

		/**
		 * register
		 *
		 * Register a route, must be called via this method to ensure correct registration
		 * of the route.
		 *
		 * @param string $name
		 * @param string $route
		 * @param array $defaults
		 * @param array $regex
		 * @return object
		 */
		public static function register($name, $route, $defaults, $regex = array()){
			return Router::$routes[$name] = new Router($route, $defaults, $regex);
		}

		/**
		 * get
		 *
		 * Return a route object by name of route.
		 *
		 * @param string $name
		 * @return object
		 */
		public static function get($name){
			if(isset(Router::$routes[$name])){
				return Router::$routes[$name];
			}
		}

		/**
		 * match
		 *
		 * Loops over all registered routes to see if a route matches
		 * the requested URI. Returns array of parameters if a match
		 * was found, or false if not found.
		 *
		 * @param string $uri
		 * @return mixed
		 */
		public static function match($uri){
			if(!empty(Router::$routes)){
				// We have some routes to match against.
				foreach(Router::$routes as $route){
					if($route->is_match($uri)){
						return $route->is_match($uri);
					}
				}
			}

			// No match was found for the specified URI.
			return false;
		}

		/**
		 * load
		 *
		 * Loads the routes from the router configuration file.
		 */
		public static function load(){
			if(file_exists(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS . 'router.php')){
				require_once(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS . 'router.php');
			}
		}

		/**
		 * When a route is registered a new object of the route is created.
		 * Everything below is related to when a new route is created.
		 * Methods include:
		 * __construct()
		 * is_match()
		 * compile()
		 */

		/**
		 * @var string $__route the compiled route regex
		 */
		public $__route;

		/**
		 * @var string $__uri the uncompiled route.
		 */
		public $__uri;

		/**
		 * @var array $__defaults array of default values for any named keys
		 */
		public $__defaults;

		/**
		 * @var array $__regex user defined regex for named keys in route regex.
		 */
		public $__regex;

		/**
		 * __construct
		 *
		 * Creates the new route, sets the default values and compiles the routes
		 * regex for use in a match.
		 *
		 * @param string $route
		 * @param array $defaults
		 * @param array $regex
		 */
		public function __construct($route, $defaults, $regex = array()){
			// Set the defaults and the uri.
			$this->__defaults = $defaults;
			$this->__uri = $route;
			$this->__regex = $regex;

			// Compile the route, handing over any user regex.
			$this->__route = $this->compile($route, $regex);
		}

		/**
		 * is_match
		 *
		 * Performs a regex match on a URI to see if it matches the given
		 * route. Returns false if no match, or an array of params if a
		 * match was found.
		 *
		 * @param string $uri
		 * @return array
		 */
		public function is_match($uri){
			if(!preg_match($this->__route, $uri, $match)){
				return false;
			}
			
			$params = array();

			// Remove the URI from the matching array.
			array_shift($match);

			// Match the keys so they can be set as params.
			foreach($match as $key => $value){
				if(!is_int($key)){
					// Params are associative.
					if(in_array($key, array('controller','action','directory')) && !isset($params[$key])){
						$params[$key] = $value;
					}else{
						$params['params'][$key] = $value;
					}
				}
			}

			// Now make sure all keys were set, otherwise use defaults.
			if(!empty($this->__defaults)){
				foreach($this->__defaults as $key => $value){
					if(!isset($params[$key]) || empty($params[$key])){
						if(in_array($key, array('controller','action','directory'))){
							$params[$key] = $value;
						}else{
							$params['params'][$key] = $value;
						}
					}
				}
			}

			// The last thing is to set any other params that will be passed into the resulting action.
			if(!isset($params['params'])){
				$params['params'] = array();
			}
			
			foreach($match as $key => $value){
				// Only add it to the params if it isn't already a parameter and if it isn't a controller or action.
				if(!in_array($value, $params['params']) && !in_array($value, $params)){
					// Explode it by the slash, so we can have unlimited amounts.
					$params['params'] += explode('/', $value);
				}
			}
			$params['params'] = array_clean($params['params']);
			
			// Return the matched parameters.
			return $params;
		}

		/**
		 * compile
		 *
		 * Compiles a routes regex, setting any user defined regex
		 * in the route.
		 *
		 * @param string $regex
		 * @param array $user
		 * @return string
		 */
		public function compile($regex, $user = array()){
			// Compile the regex from the route, making any parts optional if they aren't required.
			$regex = str_replace(array('(', ')'), array('(?:', ')?'), $regex);

			// Replace any modifiers that we have, :all, :num, :alpha
			$regex = str_replace(array(':any',':num',':alpha'), array('(.+)?','(\d+)?','([a-zA-Z]+)?'), $regex);

			// The most important subpattern is the :special subpattern, which allows special requests.
			$regex = preg_replace('#:special#', '\:(?P<special>[\w\d\-\[\],\.\:/\S]+)', $regex);

			// Replace any params with subpattern strings.
			$regex = preg_replace('#:(\w+)#', '(?P<\\1>[0-9a-zA-Z_\-]+)', $regex);

			// An action cannot start with an underscore.
			$regex = str_replace('<action>[0-9a-zA-Z_\-]+', '<action>[^\-_]{1}[0-9a-zA-Z_\-]+', $regex);

			// Any user provided regex?
			if(!empty($user)){
				foreach($user as $key => $value){
					$regex = str_replace('<' . $key . '>[0-9a-zA-Z_\-]+', '<' . $key . '>' . $value, $regex);
				}
			}

			// Replace any modifiers that we have, :all, :num, :alpha
			$regex = str_replace(array(':any',':num',':alpha'), array('(.+)?','(\d+)?','([a-zA-Z]+)?'), $regex);

			// Return the compiled regex.
			return '#^' . $regex . '$#';
		}

		/**
		 * uri
		 *
		 * Build a URI for the given route, where params can be an array
		 * of parameters relating to that route.
		 *
		 * @param array $params
		 * @return string
		 */
		public function uri($params = array()){
			if(empty($params)){
				$params = $this->__defaults;
			}else{
				$params += $this->__defaults;
			}

			if(strpos($this->__uri, ':') === false && strpos($this->__uri, '(')){
				// The uri contains no regex, nothing to replace.
				return $this->__uri;
			}

			$uri = $this->__uri;
			
			// Loop over the optional named keys that we have
			while(preg_match('#\(([^()]+)\)#', $uri, $match)){
				// Found our match, search contains the entire match which is (/:key)
				list($search, $replace) = $match;

				// Remove any parentheses from the replace variable.
				$replace = preg_replace('#[^/:\w]+#', '', $replace);

				// They may have used the key more then once. Loop over the key so we can replace it.
				while(preg_match('#:([^any|num|alpha]+\w+)#', $replace, $match)){
					// We found a match. The key is the whole named key, including colon, param is without colon.
					list($key, $param) = $match;

					// If we have a parameter with that key and its not a modifier.
					if(isset($params[$param]) && !in_array($params[$param], array(':any',':num',':alpha'))){
						// We set our replace variable to replace :key with its value in the params array.
						// Make sure our param matches any user regex.
						if(isset($this->__regex[$param])){
							// Replace any regex with custom user regex.
							$regex = str_replace(array(':any',':num',':alpha'), array('(.+)','(\d+)','([a-zA-Z]+)'), $this->__regex[$param]);
							if(preg_match('#' . $regex . '#', $params[$param])){
								$replace = str_replace($key, $params[$param], $replace);
							}else{
								$replace = null;
								break;
							}
						}else{
							// No default user regex. Replace it.
							$replace = str_replace($key, $params[$param], $replace);
						}
					}else{
						// There is no parameter set, because its optional, we set the replacement as null and break out of this loop.
						$replace = null;
						break;
					}
				}

				// We can now replace this group of named keys if there is more then one.
				// Replace (/:key) with /param_value
				$uri = str_replace($search, $replace, $uri);
			}

			// What about required named keys. Not surrounded by (). If we can't replace these, we have a major problem.
			while(preg_match('#:([^any|num|alpha]+\w+)#', $uri, $match)){
				list($key, $param) = $match;
				if(!isset($params[$param])){
					trigger_error('Could not find a match for a required route named key <strong>:' . $param . '</strong> in route ' . $this->__uri . '.', E_USER_ERROR);
					break;
				}else{

					if(isset($this->__regex[$param])){
						// Replace any regex with custom user regex.
						$regex = str_replace(array(':any',':num',':alpha'), array('(.+)','(\d+)','([a-zA-Z]+)'), $this->__regex[$param]);
						if(preg_match('#' . $regex . '#', $params[$param])){
							// The requested param matched the required user regex.
							$uri = str_replace($key, $params[$param], $uri);
						}else{
							// The param did not match user regex, invalid key.
							trigger_error('Could not find a match for a required route named key <strong>:' . $param . '</strong> in route ' . $this->__uri . '.', E_USER_ERROR);
							break;
						}
					}else{
						$uri = str_replace($key, $params[$param], $uri);
					}
				}
			}
			
			// Now we can use any params that do not have keys for any modifier keys.
			// Get only the numeric keys.
			$__params = array();
			foreach($params as $key => $value){
				if(is_int($key)){
					$__params[] = $value;
				}
			}
			// Lets match left-to-right the leftover keys if any with any leftover params.
			while(preg_match('#:(\w+)#', $uri, $match)){
				list($key, $param) = $match;

				if(!empty($__params)){
					// We have some params. Replacing left to right.
					$replace = array_shift($__params);
				}else{
					$replace = null;
				}

				$uri = str_replace($key, $replace, $uri);
			}

			// Clean up double slashes
			$uri = preg_replace('#//+#', '/', $uri);

			// Trim slashes on either end.
			$uri = trim($uri, '/');

			// Return our formatted URI
			return $uri;
		}

	}

?>
