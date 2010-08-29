<?php

    /**
     * Spine.php
     *
     * The system front controller. Important libs are loaded, holds important
	 * methods regarding libs. Creates our request instance and determines the
	 * route. Hands off to Template for caching, then parsing.
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

    class Spine {

		/**
		 * @var array $libs array of loaded libs
		 */
		public static $libs = array();

		/**
		 * instance
		 *
		 * Create an instance of Spine. The method that handles it
		 * all and boots up the system.
		 */
		public static function instance(){
			// Load important libraries.
			Spine::load('Object', 'Controller', 'Request', 'Router', 'Template', 'Helpers', 'Extender', 'Plugin');

			// Set the error handler, before running any classes.
			set_error_handler(array('Errors', 'user_trigger'));

			// Also set the exception handler, used by PDO and eventually all of the system.
			set_exception_handler('Errors::user_exception_trigger');

			// Load the router config file to set user-defined routes.
			Router::load();

			// Set the default route.
			Router::register(
				'default',
				'(:controller(/:action(/:any)))(:special)',
				array('controller' => Config::read('General.default_controller'), 'action' => 'index')
			);

			// Autoload any libraries.
			$libraries = Config::read('Library.load');
			if(!empty($libraries)){
				foreach($libraries as $library){
					Spine::load($library);
				}
			}

			// Start sessions automatically.
			if(Spine::loaded('Session')){
				// Start the session.
				Session::instance();
			}

			// Autoload any extenders.
			$extenders = Config::read('Extenders.load');
			if(!empty($extenders)){
				foreach($extenders as $extender){
					Extender::load($extender);
				}
			}

			// Autoload any plugins.
			$plugins = Config::read('Plugins.load');
			if(!empty($plugins)){
				foreach($plugins as $plugin){
					Plugin::load($plugin);
				}
			}

			// Database connection. If auto-connecting is enabled, load the Database library.
			if(Config::read('Database.enable_auto_connect')){
				if(Spine::load('Database')){
					Database::instance();
				}
			}

			// Use Request to get a new request instance from the current URI.
			if(($request = Request::instance('default')) !== false){
				// Check if we have a cached copy before dispatching.
				if(Template::render_cache($request->get_uri()) === false){
					// Dispatch to the request instance.
					$request->dispatch();

					// Run any hooks on Controller.after
					Hooks::run('Controller.after');
				}
			}
			
			// Everything has run.
			Spine::destruct();
		}

		/**
		 * loaded
		 *
		 * Checks to see if a library has been loaded, can autoload.
		 *
		 * @param string $lib
		 * @param boolean $autoload
		 * @return boolean
		 */
		public static function loaded($lib, $autoload = false){
			if(in_array($lib, Spine::$libs)){
				return true;
			}else{
				// Library has not been loaded.
				if($autoload === true){
					// Load the library now.
					Spine::load($lib);
				}else{
					return false;
				}
			}
		}

		/**
		 * load
		 *
		 * Load a library file into Spine.
		 *
		 * @param string $lib
		 * @return boolean
		 */
		public static function load(){
			if(func_num_args() < 1){
				trigger_error('Invalid arguments supplied for Spine::load.', E_USER_ERROR);
				return false;
			}

			foreach(func_get_args() as $lib){
				// Does the library file exist?
				if(!file_exists(BASE_PATH . DS . LIB_PATH . DS . $lib . '.php')){
					trigger_error('Could not find the requested library file ' . BASE_PATH . DS . LIB_PATH . DS . $lib . '.php', E_USER_ERROR);
				}else{
					if(!in_array($lib, Spine::$libs)){
						require_once(BASE_PATH . DS . LIB_PATH . DS . $lib . '.php');

						// Also add it to the libs array, then return true.
						Spine::$libs[] = $lib;
						continue;
					}else{
						// Library has already been loaded.
						continue;
					}

					// Something failed, return false.
					return false;
				}
			}

			// All good.
			return true;
		}

		/**
		* Destruct
		*
		* Pretty much, when all is done. This will fire. Closes any
		* database connections.
		*/
		public static function destruct(){
			// Find any errors
			Errors::checkup();

			// Send to the render method, where the actually rendering occurs
			Template::render(Template::$output);

			// Run any hooks on Display.after
			Hooks::run('Display.after');

			// Run any hooks on System.after
			Hooks::run('System.after');
		}

    }
?>