<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Database.php
	 *
	 * Manages database connections, we can have multiple instances of a database,
	 * although it's not always recommended. This library also manages loading
	 * of a database driver, or if PDO is enabled, setting up PDO to increase
	 * performance and make everything more portable.
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

	class Database {

		/**
		 * @var array $instances associative array of database instances
		 */
		private static $instances = array();

		/**
		 * @var array $supported array of supported database drivers if no pdo
		 */
		private static $supported = array('mysql','mysqli');

		/**
		 * instance
		 *
		 * Create a new instance, or if the instance is already created
		 * return the current instance of a database by its name.
		 *
		 * @param string $name
		 * @return object
		 */
		public static function instance($name = 'default'){
			if(isset(Database::$instances[$name])){
				return Database::get($name);
			}

			// Create a new database instance, then return it.
			Database::$instances[$name] = new Database;
			Database::get($name);
		}

		/**
		 * get
		 *
		 * Return an object of a database instance specified by $name, or
		 * false if no database object could be found.
		 *
		 * @param string $name
		 * @return mixed
		 */
		public static function get($name = 'default'){
			if(!isset(Database::$instances[$name])){
				return false;
			}else{
				return Database::$instances[$name];
			}
		}

		/**
		 * @var string $driver database driver
		 */
		private $driver;

		/**
		 * @var string $host database hostname
		 */
		private $host;

		/**
		 * @var string $username database username
		 */
		private $username;

		/**
		 * @var string $password database password
		 */
		private $password;

		/**
		 * @var string $dbname database name
		 */
		private $dbname;

		/**
		 * @var string $prefix database table prefix
		 */
		private $prefix;

		/**
		 * @var string $path sqlite database path
		 */
		private $path;

		/**
		 * @var object $dbh database handle object
		 */
		public $dbh;

		/**
		 * __construct
		 *
		 * Creates a new database instance.
		 *
		 * @return
		 */
		private function __construct(){
			// Set the configuration properties.
			$config = Config::read('Database');
			foreach($config as $key => $value){
				$this->{$key} = $value;
			}

			// Is PDO enabled, we'll use it if it is.
			if(extension_loaded('pdo') && $this->driver !== 'mysqli' && Config::read('Database.disable_pdo') === false){
				// PDO is enabled.
				if($this->driver === 'sqlite'){
					// Using SQLite, different connection method.
					try {
						$this->dbh = new PDO('sqlite:' . $this->path);
					}
					catch(Exception $e){
						throw new Exception('PDO failed to establish a connection. PDO said: ' . $e->getMessage() . '.');
					}
				}else{
					try {
						$this->dbh = new PDO($this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password);
					}
					catch(Exception $e){
						throw new Exception('PDO failed to establish a connection. PDO said: ' . $e->getMessage() . '.');
					}
				}
			}else{
				// No PDO, is the requested driver available.
				if(extension_loaded($this->driver) && in_array($this->driver, Database::$supported)){
					if(!file_exists(DB_PATH . $this->driver . DS . 'driver.php')){
						trigger_error('Could not load the requested database driver file: ' . $this->driver, E_USER_ERROR);
						return;
					}

					// We have our standard driver, not the best, but load it in.
					require_once(DB_PATH . $this->driver . DS . 'driver.php');
					if($this->driver === 'mysqli'){
						$this->dbh = new mysqli($this->host, $this->username, $this->password, $this->dbname);
					}else{
						$this->dbh = new DBDriver;
						$this->dbh->connect($this->host, $this->username, $this->password, $this->dbname);
					}
				}else{
					trigger_error('Configuration has been set to automatically connect to a database, however the attempt at finding a valid database driver failed. Attempted to load driver: ' . $this->driver, E_USER_ERROR);
				}
			}
		}

		/**
		 * get_driver
		 *
		 * Return the database driver.
		 *
		 * @return string
		 */
		public function get_driver(){
			return $this->driver;
		}

		/**
		 * get_host
		 *
		 * Return the database host.
		 *
		 * @return string
		 */
		public function get_host(){
			return $this->host;
		}

		/**
		 * get_username
		 *
		 * Return the database username.
		 *
		 * @return string
		 */
		public function get_username(){
			return $this->username;
		}

		/**
		 * get_password
		 *
		 * Return the database password.
		 *
		 * @return string
		 */
		public function get_password(){
			return $this->password;
		}

		/**
		 * get_dbname
		 *
		 * Return the database name.
		 *
		 * @return string
		 */
		public function get_dbname(){
			return $this->dbname;
		}

		/**
		 * get_prefix
		 *
		 * Return the database table prefix.
		 *
		 * @return string
		 */
		public function get_prefix(){
			return $this->prefix;
		}

		/**
		 * get_path
		 *
		 * Return the sqlite database path.
		 *
		 * @return string
		 */
		public function get_path(){
			return $this->path;
		}

	}

?>