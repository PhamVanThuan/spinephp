<?php

    /**
     * Registry.php
     *
     * This file is the front controller for the system.
     * Loads up all bare minimum requirements to run the system, if something
     * else is needed, will be loaded when needed.
     * Everything is stored in the $registry, so we don't pollute the global
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

    class Registry {

		public $controller;
		public $Errors;
		public $Router;
		public $Template;
		public $Helpers;
		public $libraries = array(
			'Errors',
			'Router',
			'Template',
			'Helpers'
		);
		public static $_this;

		public function __construct(){
			self::$_this =& $this;

			// Define SYS_URL
			define('SYS_URL', Config::read('General.system_url'));

			// Let's gather up the important libraries and start them up.
			// There libraries need the Registry passed to them.
			require_once(LIB_PATH . 'Controller.php');
			require_once(LIB_PATH . 'Object.php');

			$this->load_library('Router');
			$this->load_library('Template');
			$this->load_library('Helpers');
			$this->load_library('Plugin');

			// Set the error handler, before running any classes.
			set_error_handler(array('Errors', 'user_trigger'), E_ALL);

			// Autoload any plugins.
			$plugins = Config::read('Plugin.load');
			if(!empty($plugins)){
				foreach($plugins as $plugin){
					$this->Plugin->load($plugin);
				}
			}

			// Autoload any libraries.
			$libraries = Config::read('Library.autoload');
			if(!empty($libraries)){
				foreach($libraries as $library){
					$this->load_library($library);
				}
			}

			// Automatically connect to MySQL?
			if(Config::read('Database.enable_auto_connect')){
				if($this->is_database_loaded('DB', true)){

					// Connect if it's not MySQLi, MySQLi connects by default.
					if(Config::read('Database.driver') != 'mysqli'){
						$this->DB->connect();
					}
				}
			}

			// The router can now do its magic.
			$this->Router->route();

			// Check if we have a cached copy before dispatching.
			if($this->Template->render_cache($this->Router->uri()) === false){
				// No cached copy.
				// Dispatch to the controller.
				$this->Router->dispatch($this->Router->request['controller'], true);

				// Run any hooks on Controller.after
				Hooks::run('Controller.after');

				// All good to send to the renderer, not called in destruct because if
				// we encounter errors it'll still be called in the destruct.
				$this->Template->prepare_render();
			}

		}

		/**
		 * get_instance
		 *
		 * Static method to return insance of the registry object.
		 *
		 * @return object
		 */
		public static function get_instance(){
			return self::$_this;
		}

		/**
		 * is_database_loaded
		 *
		 * Detects if the database class has been loaded, if the first parameter is set to true
		 * the database class will be loaded if not found.
		 *
		 * @param string $class name of the class
		 * @param boolean $load if the database should be loaded
		 */
		public function is_database_loaded($class = 'Database', $load = false){
			$driver = Config::read('Database.driver');
			if(file_exists(DB_PATH . $driver . '/Database.php')){
				require(DB_PATH . $driver . '/Database.php');
				
				if(class_exists($class, false)){
					return true;
				}else{
					if($load){
						if(Config::read('Database.driver') == 'mysqli'){
							$this->{$class} = new Database(
								Config::read('Database.host'),
								Config::read('Database.username'),
								Config::read('Database.password'),
								Config::read('Database.dbname')
								);
						}else{
							$this->{$class} = new Database;
						}
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}

		/**
		 * is_library_loaded
		 *
		 * Checks to see if a library has been loaded, can also specify to load the library automatically
		 * if it has not been loaded or if it has you can return a new instance of the object.
		 *
		 * @param mixed $library the library to check
		 * @param boolean $load_library if the library should be loaded if it isn't already
		 * @param boolean $return_new_object return the library object
		 * @param boolean $instantiate_library if the library is to be instantiated, only runs if library isn't loaded
		 * @return mixed
		 */
		public function is_library_loaded($library, $load_library = false, $return_new_object = false, $instantiate_library = true){
			if(is_array($library)){
				$cn_library = $library[1];
				$fn_library = $library[0];
			}else{
				$cn_library = $library;
				$fn_library = $library;
			}

			if(in_array($fn_library, get_declared_classes())){
				// Library has been loaded before, so no need to include the file again.
				if($return_new_object === true){
					// Return a new object of the library.
					return $this->load_library($library, true, true);
				}else{
					return true;
				}
			}else{
				if($load_library === true){
					if($return_new_object === true){
						// They want to load the library and return the object
						return $this->load_library($library, true, false);
					}else{
						// They want to just load the library.
						$this->load_library($library, false, false, $instantiate_library);
					}
				}else{
					return false;
				}
			}
		}

		/**
		 * load_library
		 *
		 * Loads a given library and returns the object if set.
		 *
		 * @param mixed $library the library to load
		 * @param boolean $return_new_object return the new library object
		 * @param boolean $library_file_loaded if the file is already loaded
		 * @param boolean $instantiate_library if the library is to be instantiated
		 * @return mixed
		 */
		public function load_library($library, $return_new_object = false, $library_file_loaded = false, $instantiate_library = true){
			if(is_array($library)){
				$cn_library = $library[1];
				$fn_library = $library[0];
			}else{
				$cn_library = $library;
				$fn_library = $library;
			}

			if(!file_exists(LIB_PATH . $fn_library . '.php')){
				trigger_error('Could not find the requested library file ' . BASE_PATH . LIB_PATH . $fn_library . '.php', E_USER_ERROR);
			}else{
				// Only load the library file if it hasn't been loaded before.
				if($library_file_loaded === false){
					require(LIB_PATH . $fn_library . '.php');
				}

				if(!class_exists($fn_library, false)){
					trigger_error('Could not find the requested library class <strong>' . $fn_library . '</strong>.', E_USER_ERROR);
				}

				// Add it to the loaded libraries array.
				$this->libraries[] = $cn_library;

				if($return_new_object === false && $instantiate_library === true){
					// Create the new object then return true.
					$this->{$cn_library} = new $fn_library;
					return true;
				}elseif($instantiate_library === true){
					// Return a new object.
					return new $fn_library;
				}
			}
		}

		/**
		* Destruct
		*
		* Pretty much, when all is done. This will fire. Closes any
		* database connections.
		*/
		public function __destruct(){
			// Close any database connections, if any.
			if($this->is_database_loaded('DB')){
				$this->DB->close();
			}

			// Run any hooks on System.after
			Hooks::run('System.after');
		}

    }

    // Fire it up baby! Ooww!
    $registry = new Registry;
?>