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
	 * Display.after - After the final output is processed.
	 * System.after - After everything has been run, just after Display.after.
	 */

	class Hooks {

		public static $set = array();

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
					return true;
				}
			}elseif(!empty($file)){
				// The hook they wish to call is a file located in application/hooks
				$value = array(
					'name' => $name,
					'file' => $file['name'],
					'object' => $file['object'],
					'method' => $file['method'],
					'params' => $file['params']
				);
				array_inject(self::$set, $hook, $value, true);
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
		public static function unregister($hook, $name = null){
			// Get the hook path.
			$path = explode('.', $hook);
			$var = & self::$set;
			foreach($path as $i => $key){
				if($i === count($path) - 1){
					if(empty($name)){
						unset($var);
					}else{
						foreach($var[$key] as $index => $tmp){
							if($tmp['name'] == $name){
								break;
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

		public static function run($hook){
			$hooks = array_extract(self::$set, $hook);
			foreach($hooks as $hook){
				if(isset($hook['file'])){
					// The hook is a file hook.
				}else{
					// The hook is a plugin.
					// Should be right to fire it away.
					$hook['object']->$hook['method']();
				}
			}
		}

	}

?>
