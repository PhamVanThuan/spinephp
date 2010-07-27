<?php

	/**
	 * Config.php
	 *
	 * Allows easy access to the Config variables.
	 * Access is done by: Config::read('Variable.name')
	 * Allows writing over configuration variables until script has executed.
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

	class Config {

		public static $config;

		/**
		 * get_instance
		 *
		 * Create an instance of the Config object, loading up the config
		 * variables from the config file.
		 */
		public static function get_instance(){
			load_config();
		}

		/**
		 * read
		 *
		 * Read the value of a config option by passing a path to it:
		 * Example: Config::read('Variable.name')
		 *
		 * @param string $path
		 * @return mixed
		 */
		public static function read($path){
			if(empty($path)){
				return null;
			}else{
				return array_extract(Config::$config, $path);
			}
		}

		/**
		 * write
		 *
		 * Write a value to a config option, applies only to the runtime of script.
		 * Values are reset at the end of the script execution.
		 *
		 * @param string $path
		 * @param mixed $value
		 * @return boolean
		 */
		public function write($path, $value){
			$path = explode('.', $path);
			$vars = & Config::$config;

			foreach($path as $i => $key){
				if($i === count($path) - 1){
					$vars[$key] = $value;
					return true;
				}else{
					if(!isset($vars[$key])){
						$vars[$key] = array();
					}

					$vars = & $vars[$key];
				}
			}

			return false;
		}

	}

?>
