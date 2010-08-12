<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Extender.php
	 *
	 * Extenders allow the core of Spine to be extended. Extenders make use of
	 * hooks to insert custom code at a certain part of the Spine execution.
	 * This library handles loading of extenders, unloading of extenders and
	 * execution of extenders.
	 *
	 * Extenders are similar to hook files, except extenders are required to
	 * contain certain methods. Hook files can be simple procedural code.
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

	class Extender {

		/**
		 * @var array $loaded array of loaded extenders
		 */
		public static $loaded = array();

		/**
		 * load
		 *
		 * Loads an extender and executes the set_hooks method.
		 * Extenders that are loaded after their hook can be executed
		 * by setting $execute to true.
		 *
		 * @param array $extend
		 * @param boolean $execute
		 * @return boolean
		 */
		public static function load($extend, $execute = false){
			if(!is_array($extend)){
				return false;
			}
			
			list($file, $class) = $extend;

			if(file_exists(BASE_PATH . 'extenders/' . $file . '.extend.php') && !array_key_exists($class, Extender::$loaded)){
				require_once(BASE_PATH . 'extenders/' . $file . '.extend.php');

				// Extender class name
				$cn_extender = Inflector::camelize($class . 'Extender');

				// Does the class name exist.
				if(class_exists($cn_extender, false)){
					// Place the new extender instance in the loaded array.
					Extender::$loaded[$class] = new $cn_extender;

					// Set the extenders hooks.
					if(method_exists(Extender::$loaded[$class], 'set_hooks')){
						// Set the relevavant hooks.
						Extender::$loaded[$class]->set_hooks();
					}else{
						// No hooks, bad extender.
						trigger_error('Failed to set hooks for extender ' . $cn_extender . '. This extender is deemed invalid and should not be used.', E_USER_ERROR);
					}

					// Should the extender be executed?
					if($execute === true){
						Extender::execute($class);
					}

					return true;
				}
			}elseif(array_key_exists($class, Extender::$loaded)){
				if($execute === true){
					Extender::execute($class);
					return true;
				}
			}

			return false;
		}

		/**
		 * unload
		 *
		 * Unload an extender from the loaded extenders.
		 *
		 * @param string $name
		 */
		public static function unload($name){
			if(isset(Extender::$loaded[$name])){
				// The extender has been loaded, unregister the hooks. This will call the unhook method supplied.
				Hooks::unregister(null, $name);

				// Remove from loaded extenders.
				unset(Extender::$loaded[$name]);
			}
		}

		/**
		 * execute
		 *
		 * Executes an extender, generally used if an extender is loaded after
		 * the system has started up. When extenders are loaded manually it is
		 * advised that the execute param be set to true to ensure the extender
		 * is fired.
		 *
		 * @param string $extend
		 */
		public static function execute($name){
			if(isset(Extender::$loaded[$name])){
				// The extender has been loaded. Safe to execute.
				if(method_exists(Extender::$loaded[$name], 'execute')){
					Extender::$loaded[$name]->execute();
				}else{
					trigger_error('Failed to execute extender ' . $name . 'Extender. Execute method was not found.', E_USER_ERROR);
				}
			}else{
				trigger_error('Failed to execute a non-loaded extender (<strong>' . $name . '</strong>).', E_USER_ERROR);
			}
		}

	}

?>
