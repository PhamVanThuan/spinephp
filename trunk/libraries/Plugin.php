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
		 * @return boolean
		 */
		public static function load($plugin){
			if(file_exists(APP_PATH . 'plugins/' . $plugin . '.plugin.php')){
				require_once(APP_PATH . 'plugins/' . $plugin . '.plugin.php');

				// The plugin class name.
				$plugin = array_pop(explode('/', $plugin));
				$cn_plugin = ucfirst(strtolower($plugin)) . 'Plugin';

				// Does the class name exist.
				if(class_exists($cn_plugin, false)){
					// Place the new plugin instance into the loaded array.
					Plugin::$plugins[$plugin] = new $cn_plugin;

					// Does the plugin have any hooks?
					if(method_exists(Plugin::$plugins[$plugin], 'set_hooks')){
						// Set the relevavant hooks.
						Plugin::$plugins[$plugin]->set_hooks();
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
