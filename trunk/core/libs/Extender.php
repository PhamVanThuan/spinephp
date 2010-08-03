<?php

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
	 * Copyright (c) 2010, Jason Lewis (http://www.spinephp.org)
	 *
	 * Licensed under the MIT License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis
	 * @link		(http://www.spinephp.org)
	 * @license		MIT License (http://www.opensource.org/licenses/mit-license.html)
	 */

	class Extender extends Object {

		// Array consisting of extenders that have been loaded.
		public static $extenders = array();

		/**
		 * load
		 *
		 * Loads an extender and executes the set_hooks method.
		 * Extenders that are loaded after their hook can be executed
		 * by setting $execute to true.
		 *
		 * @param mixed $extend
		 * @param boolean $execute
		 * @return boolean
		 */
		public static function load($extend, $execute = false){
			if(!is_array($extend)){
				return false;
			}
			
			list($file, $class) = $extend;
			
			if(file_exists(BASE_PATH . 'extenders/' . $file . '.php')){
				require_once(BASE_PATH . 'extenders/' . $file . '.php');

				// Extender class name
				$cn_extender = $class . 'Extender';

				// Does the class name exist.
				if(class_exists($cn_extender, false)){
					// Place the new extender instance in the loaded array.
					Extender::$extender[$class] = new $cn_extender;

					// Set the extenders hooks.
					if(method_exists(Extender::$extenders[$class], 'set_hooks')){
						// Set the relevavant hooks.
						Extender::$extenders[$class]->set_hooks();
					}else{
						// No hooks, bad extender.
						trigger_error('Failed to set hooks for extender ' . $cn_extender . '. This extender is deemed invalid and should not be used.', E_USER_ERROR);
					}

					// Should the extender be executed?
					if($execute === true){
						Extender::execute($class);
					}
				}
			}

			return false;
		}

		public static function unload($extend){
			
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
		public static function execute($extend){
			if(isset(Extender::$extenders[$extend])){
				// The extender has been loaded. Safe to execute.
				if(method_exists(Extender::$extenders[$extend], 'execute')){
					Extender::$extenders[$extend]->execute();
				}else{
					trigger_error('Failed to execute extender ' . $extend . 'Extender. Execute method was not found.', E_USER_ERROR);
				}
			}else{
				trigger_error('Failed to execute a non-loaded extender (<strong>' . $extend . '</strong>).', E_USER_ERROR);
			}
		}

	}

?>
