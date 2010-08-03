<?php

    /**
     * Spine.php
     *
     * This file is the front controller for the system.
     * Loads up all bare minimum requirements to run the system, if something
     * else is needed, will be loaded when needed.
     * Everything is stored in the $spine, so we don't pollute the global
     * namespace. Makes it all nice and clean.
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

		// Array consisting of libraries that have been loaded.
		public static $libs = array();

		public static function init(){
			// Load important libraries.
			Spine::load('Controller');
			Spine::load('Object');
			Spine::load('Router');
			Spine::load('Template');
			Spine::load('Helpers');
			Spine::load('Extender');
			Spine::load('Plugin');
			Spine::load('Inflector');

			// Set the error handler, before running any classes.
			// Looking at changing to exceptions in the future.
			set_error_handler(array('Errors', 'user_trigger'), E_ALL);


			//Autoload any extenders.
			$extenders = Config::read('Extenders.load');
			if(!empty($extenders)){
				foreach($extenders as $extender){
					Extender::load($extender);
				}
			}

			// Autoload any libraries.
			$libraries = Config::read('Library.load');
			if(!empty($libraries)){
				foreach($libraries as $library){
					Spine::load($library);
				}
			}

			// Automatically connect to MySQL?
			if(Config::read('Database.enable_auto_connect')){
				if(Spine::database('Database', true)){
					// Connect if it's not MySQLi, MySQLi connects by default.
					Database::connect();
				}
			}

			if(Spine::loaded('Session')){
				// Start the session.
				Session::init();
			}

			// The router can now do its magic.
			Router::route();

			// Check if we have a cached copy before dispatching.
			if(Template::render_cache(Router::get_uri()) === false){
				// No cached copy.
				// Dispatch to the controller.
				Router::dispatch(Router::$request['controller'], true, null, true);

				// Run any hooks on Controller.after
				Hooks::run('Controller.after');
			}

			// Everything has run.
			Spine::destruct();

		}

		public static function database($class, $autoload = false){
			$driver = Config::read('Database.driver');
			if(file_exists(DB_PATH . $driver . '/Database.php')){

				if(in_array('Database', array_map('basename', get_included_files()))){
					return true;
				}elseif($autoload === true){
					require_once(DB_PATH . $driver . '/Database.php');
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
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
		public static function load($lib){
			// Does the library file exist?
			if(!file_exists(LIB_PATH . $lib . '.php')){
				trigger_error('Could not find the requested library file ' . BASE_PATH . LIB_PATH . $lib . '.php', E_USER_ERROR);
			}else{
				if(!in_array($lib, Spine::$libs)){
					require_once(LIB_PATH . $lib . '.php');

					// Also add it to the libs array, then return true.
					Spine::$libs[] = $lib;
					
					return true;
				}else{
					// Library has already been loaded.
					return true;
				}

				// Something failed, return false.
				return false;
			}
		}

		/**
		* Destruct
		*
		* Pretty much, when all is done. This will fire. Closes any
		* database connections.
		*/
		public function destruct(){
			// Find any errors
			Errors::checkup();

			// Run any hooks on System.after
			Hooks::run('System.after');
		}

    }
?>