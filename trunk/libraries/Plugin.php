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

		public $loaded_plugins = array();

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
		public function load($plugin){
			if(file_exists(PLUGIN_PATH . $plugin . '.plugin.php')){
				@require_once(PLUGIN_PATH . $plugin . '.plugin.php');

				$cn_plugin = array_pop(explode('/', $plugin));
				$cn_plugin = ucfirst(strtolower($cn_plugin)) . 'Plugin';
				if(class_exists($cn_plugin, false)){
					$this->loaded_plugins[array_pop(explode('/', $plugin))] = new $cn_plugin;

					// Does the plugin have any hooks?
					if(method_exists($this->loaded_plugins[array_pop(explode('/', $plugin))], 'set_hooks')){
						$this->loaded_plugins[array_pop(explode('/', $plugin))]->set_hooks();
					}
				}
			}

			return false;
		}

		public function execute($plugin){
			if(isset($this->loaded_plugins[$plugin])){
				// The plugin has been loaded.
				// Execute the plugin.
				$this->loaded_plugins[$plugin]->execute();
			}else{
				trigger_error('Failed to execute a non-loaded plugin (<strong>' . $plugin . '</strong>).', E_USER_ERROR);
			}
		}

	}

?>
