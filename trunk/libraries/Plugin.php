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
		 * load_plugin
		 *
		 * Loads a plugin from the /plugins folder, calls the set_hooks method
		 * to set any hooks which are to run on the system.
		 *
		 * @param mixed $plugin
		 * @return boolean
		 */
		public function load_plugin($plugin){
			if(is_array($plugin)){
				list($folder, $plugin) = $plugin;
			}else{
				$folder = $plugin;
			}
			
			if(file_exists(PLUGIN_PATH . $folder . '/' . $plugin . '.plugin.php')){
				@require_once(PLUGIN_PATH . $folder . '/' . $plugin . '.plugin.php');

				$cn_plugin = ucfirst(strtolower($plugin)) . 'Plugin';
				if(class_exists($cn_plugin, false)){
					$loaded_plugins[$plugin] = new $cn_plugin;
					$loaded_plugins[$plugin]->set_hooks();
				}
			}

			return false;
		}

	}

?>
