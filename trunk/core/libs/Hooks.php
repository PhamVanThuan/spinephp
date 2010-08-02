<?php

	/**
	 * Hooks.php
	 *
	 * Manages all the systems hooks, which are called at various points
	 * during execution of the system.
	 *
	 * Hooks available are:
	 * System.before - Very early, after Hooks & Plugins have been loaded.
	 * Controller.before - Before a controller is instantiated.
	 * Controller.afterConstruct - After your controller has been instantiated but before any methods are called.
	 * Controller.after - After controller has been instantiated and methods called.
	 * Display.before - Before the final output is processed.
	 * System.after - After everything has been run.
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

	class Hooks {

		// Array of set hooks
		public static $set = array();

		// Array of registered hooks names.
		public static $registered = array();

		/**
		 * register
		 *
		 * Register a hook. Hooks can either be set from a Plugin, where the plugin is already
		 * included or they can be set as a file located in the application/hooks directory.
		 *
		 * @param string $name
		 * @param string $hook
		 * @param array $file
		 * @param object $object
		 * @param string $method
		 * @param array $params
		 * @return boolean
		 */
		public static function register($name, $hook, $file = array(), &$object = null, $method = null, $params = array()){
			// Check if the object and method are valid.
			if(is_object($object)){
				if(method_exists($object, $method)){
					// Build the value.
					$value = array(
						'name' => $name,
						'object' => $object,
						'method' => $method,
						'params' => $params
					);
					array_inject(self::$set, $hook, $value, true);
					self::$registered[$name] = $hook;
					return true;
				}
			}elseif(!empty($file)){
				// The hook they wish to call is a file located in application/hooks
				$value = array(
					'name' => $name,
					'file' => $file['file']
				);
				array_inject(self::$set, $hook, $value, true);
				self::$registered[$name] = $hook;
				return true;
			}

			return false;
		}

		/**
		 * unregister
		 *
		 * Unregister a Hook, either all hooks or a single hook by it's name.
		 *
		 * @param string $hook
		 * @param string $name
		 * @return boolean
		 */
		public static function unregister($hook = null, $name = null){
			if(empty($hook) && empty($name)){
				return false;
			}
			
			// Remove from registered.
			if(!empty($name)){
				if(empty($hook)){
					$hook = self::$registered[$name];
				}
				if(isset(self::$registered[$name])){
					unset(self::$registered[$name]);
				}
			}
			
			// Get the hook path.
			$path = explode('.', $hook);
			$var = & self::$set;
			foreach($path as $i => $key){
				if($i === count($path) - 1){
					if(empty($name)){
						unset($var);
					}else{
						// Because we allow multiple hooks we must find our hooks index.
						foreach($var[$key] as $index => $tmp){
							if($tmp['name'] == $name){
								// We found our hook, lets retain the $index and break.
								break;
							}
						}

						// Is there an unhook method in the object.
						if(isset($var[$key][$index]['params']['unhook'])){
							$obj = $var[$key][$index];

							// Are we using an object or a file?
							if(is_object($obj['object'])){
								// Object, let's make sure the method exists.
								if(method_exists($obj['object'], $obj['params']['unhook'])){
									// And fire.
									$obj['object']->{$obj['params']['unhook']}();
								}
							}elseif(isset($obj['file'])){
								// File, let's see if there is a function. Not the best, but hey.
								if(function_exists($obj['params']['unhook'])){
									// And fire.
									$obj['params']['unhook']();
								}
							}
						}

						unset($var[$key][$index]);

						if(empty($var[$key])){
							unset($var[$key]);
						}
					}
				}else{
					if(!isset($var[$key])){
						return false;
					}

					$var = & $var[$key];
				}
			}
		}

		/**
		 * autoload
		 *
		 * Autoloads any hooks that were set in the Config.
		 */
		public static function autoload(){
			$hooks = Config::read('Hooks.load');
			if(!empty($hooks)){
				// We have some hook files. We don't load them here though.
				// Let's just register them.
				foreach($hooks as $hook){
					if(!empty($hook)){
						self::register($hook['name'], $hook['hook'], array('file' => $hook['file']));
					}
				}
			}
		}

		/**
		 * run
		 *
		 * Run any hooks for a specific period during system loading.
		 *
		 * @param string $hook
		 */
		public static function run($hook){
			$hooks = array_extract(self::$set, $hook);
			if(!empty($hooks)){
				foreach($hooks as $hook){
					if(isset($hook['file'])){
						// The hook is a file hook.
						require(APP_PATH . 'views/hooks/' . $hook['file'] . '.php');
					}else{
						// The hook is a plugin.
						// Should be right to fire it away.
						$hook['object']->$hook['method']();
					}
				}
			}
		}

		/**
		 * get_registered
		 *
		 * Get list of registered hooks.
		 *
		 * @return array
		 */
		public static function get_registered(){
			$tmp = array();
			foreach(self::$registered as $key => $val){
				$tmp[] = $key;
			}
			return $tmp;
		}

	}

?>
