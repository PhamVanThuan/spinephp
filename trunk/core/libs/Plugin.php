<?php

	/**
	 * Plugin.php
	 *
	 * Handles plugin requests, loads plugins, executes plugins etc.
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

	class Plugin extends Object {

		// Array consisting of plugins that have been loaded.
		public static $plugins = array();

		/**
		 * load
		 *
		 * Loads a plugin from the /plugins folder.
		 * If the plugin has a set_hooks method we can set
		 * its hooks now. Otherwise it'll be called on later.
		 *
		 * @param mixed $plugin
		 * @param boolean $execute
		 * @return boolean
		 */
		public static function load($plugin, $execute = false){
			if(!is_array($plugin)){
				return false;
			}
			
			list($plugin, $class) = $plugin;
			
			if(file_exists(BASE_PATH . 'plugins/' . $plugin . '.php')){
				require_once(BASE_PATH . 'plugins/' . $plugin . '.php');

				// Plugin class name.
				$cn_plugin = $class . 'Plugin';

				// Does the class name exist.
				if(class_exists($cn_plugin, false)){
					// Place the new plugin instance into the loaded array.
					Plugin::$plugins[$class] = new $cn_plugin;

					// Does the plugin have any hooks?
					if(method_exists(Plugin::$plugins[$class], 'set_hooks')){
						// Set the relevavant hooks.
						Plugin::$plugins[$class]->set_hooks();
					}

					// Execute the plugin now?
					if($execute === true && method_exists(Plugin::$plugins[$class], 'execute')){
						Plugin::execute($class);
					}
				}
			}

			return false;
		}

		/**
		 * execute
		 *
		 * Execute a plugin.
		 *
		 * @param string $plugin
		 */
		public static function execute($plugin){
			if(isset(Plugin::$plugins[$plugin])){
				// The plugin has been loaded. Safe to execute.
				Plugin::$plugins[$plugin]->execute();
			}else{
				trigger_error('Failed to execute a non-loaded plugin (<strong>' . $plugin . '</strong>).', E_USER_ERROR);
			}
		}

	}

?>
