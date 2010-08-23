<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Config.php
	 *
	 * Allows easy access to the Config variables.
	 * Access is done by: Config::read('Variable.name')
	 * Allows writing over configuration variables until script has executed.
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

	class Config {

		/**
		 * @var array $config array of set config parameters
		 */
		public static $config = array();

		/**
		 * @var array $changes array holding config parameters that are changed and can be saved
		 */
		public static $changes = array();
		
		/**
		 * get_instance
		 *
		 * Create an instance of the Config object, loading up the config
		 * variables from the config file.
		 */
		public static function get_instance(){
			if(empty(Config::$config)){
				load_config();
			}
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
		 * Values are reset at the end of the script execution. If $config_allow_save
		 * is set to true and Config::save() is called, those settings will be saved
		 * to the config file.
		 *
		 * @param string $path
		 * @param mixed $value
		 * @param boolean $config_allow_save
		 * @return boolean
		 */
		public static function write($path, $value, $config_allow_save = false){
			$path = explode('.', $path);
			$vars = & Config::$config;

			foreach($path as $i => $key){
				if($i === count($path) - 1){
					$vars[$key] = $value;
					if($config_allow_save === true){
						Config::$changes[] = implode('.', $path);
					}

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

		/**
		 * save
		 *
		 * Allows any config variables that were written to with the save
		 * parameter to be saved to the config file.
		 *
		 * @return boolean
		 */
		public static function save(){
			if(!empty(Config::$changes)){
				// We have some changes to save.
				$config_file_contents = file_get_contents(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS . 'config.php');
				foreach(Config::$changes as $variable){
					if(preg_match("#Config\:\:write\(['\"]?" . $variable . "['\"]?,\s?['\"]?(.*?)['\"]?\)#msi", $config_file_contents, $match)){

						// Determine the value.
						$value = Config::read($variable);
						if(is_bool($value)){
							$value = $value === true ? 'true' : 'false';
						}elseif(is_array($value)){
							$value = Config::array_clean(var_export($value, true));
						}

						$config_file_contents = preg_replace("#(Config\:\:write\(['\"]?" . $variable . "['\"]?),\s?['\"]?(.*?)['\"]?\){1};#msi", "\\1, " . $value . ");", $config_file_contents);
					}
				}

				$handle = fopen(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS . 'config.php', 'w');
				if(!fwrite($handle, $config_file_contents)){
					return false;
				}
				fclose($handle);

				return true;
			}else{
				return false;
			}
		}

		/**
		 * array_clean
		 *
		 * Cleans an array converted to string via var_export.
		 *
		 * @param string $array
		 * @return string
		 */
		public static function array_clean($array){
			$array = str_replace(array("\r\n","\n","\t"), '', $array);
			$array = preg_replace("#\s\s+#", " ", $array);
			$array = str_replace(array("array ( ", ",)", ", )"), array("array(", ")", ")"), $array);
			return $array;
		}

	}

?>
