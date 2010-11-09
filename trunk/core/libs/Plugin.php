<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Plugin.php
	 *
	 * Plugin Library manages plugins that are autoloaded or manually loaded.
	 * The library allows you to load plugins, unload plugins and retrieve plugin
	 * instances.
	 *
	 * Plugins differ from Extenders because plugins do not extend the core but
	 * simply plugin new methods and properties that can be used throughout
	 * applications. Plugins may also provide their own controllers, views and
	 * models.
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

	class Plugin {

		/**
		 * @var array $loaded array of loaded plugins
		 */
		public static $loaded = array();

		/**
		 * load
		 *
		 * Load a plugin into the system making it ready to
		 * use. If instatiate is set to true, a new instance of the
		 * plugin will be returned.
		 *
		 * @param mixed $plugin
		 * @param boolean $instantiate
		 * @return mixed
		 */
		public static function load($plugin, $instantiate = false){
			$class = Inflector::camelize(array_pop(explode('/', $plugin)));
			$file = $plugin;

			// Does the plugin exist, and has it been loaded already.
			if(file_exists(CORE_PATH . 'plugins' . DS . $file . '.php') && !array_key_exists($class, Plugin::$loaded)){
				require_once(CORE_PATH . 'plugins' . DS . $file . '.php');

				$cn_plugin = $class . 'Plugin';
				if(class_exists($cn_plugin, false)){
					// The plugin class exists. Store the class name in the $loaded property.
					Plugin::$loaded[$class] = $cn_plugin;

					if($instantiate === true){
						// User requested that a new instance be returned.
						return Plugin::request($class);
					}

					return true;
				}
			}elseif(array_key_exists($class, Plugin::$loaded)){
				// Already loaded, do they want a new instance.
				if($instantiate === true){
					return Plugin::request($class);
				}
			}

			return false;
		}

		/**
		 * request
		 *
		 * Request a new instance of a plugin via $name. The plugin must be
		 * loaded prior to requesting a new instance. Returns false if the
		 * plugin was not loaded.
		 * 
		 * @param string $name
		 * @return mixed
		 */
		public static function request($name){
			if(array_key_exists($name, Plugin::$loaded)){
				return new Plugin::$loaded[$name];
			}else{
				return false;
			}
		}

	}

?>
