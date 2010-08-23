<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Common.php
	 *
	 * Contains a bunch of common functions that can be used globally.
	 * Also includes the __autoload(), to save time with most classes.
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

	/**
	 * write_log
	 * Log an error in the log file, ensure that logging is enabled.
	 *
	 * @param $level string the level of the error
	 * @param $message string the message to output the the log file.
	 * @return void
	 */
    function write_log($level = 'ERROR', $message){
		if(Config::read('General.enable_logging')){
			$log_file = 'LOG.' . date('Y-m-d') . '.php';
			$tmp = '';

			if($level == ''){
				$level = 'ERROR';
			}

			// Does todays log file exist?
			if(!file_exists(BASE_PATH . DS . TMP_PATH . DS . 'log' . DS . $log_file)){
				$tmp .= "<?php\n\tif(!defined('APP_PATH')){\n\t\tdie('Unauthorized direct access to file.');\n\t}\n?>\n\n";
			}

			$tmp .= strtoupper($level) . " [" . date('jS F Y, \a\\t H:i') . "] > " . $message . "\n";

			// Attempt to open the file, or create it if it doesn't exist.
			if(!$handle = @fopen(BASE_PATH . DS . TMP_PATH . DS . 'log' . DS . $log_file, 'a+')){
				return;
			}

			// Insert the message and close the pointer.
			fwrite($handle, $tmp);
			fclose($handle);

		}
    }

    /**
	 * load_config
	 *
	 * Load the config array and return it.
	 *
	 * @return array
	 */
    function load_config(){
		if(!file_exists(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS . 'config.php')){
			die("Failed to locate " . BASE_PATH . DS . CORE_PATH . DS . "config" . DS . "config.php");
		}
		
		$skip = array('router.php');

		// Load up the configuration files.
		foreach(glob(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS . '*.php') as $file){
			$file = basename($file);
			if(!in_array($file, $skip)){
				require(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS . $file);
			}
		}
    }

	/**
	 * array_clean
	 *
	 * Extend the array functions. Because PHP hasn't implemented
	 * any kind of array cleaning, that is, removing blank elements
	 * from an array, we'll make our own.
	 *
	 * This isn't like array_filter, because what if I wanted an
	 * array to have a value of -1 or false.
	 *
	 * @param $input array the array to clean up
	 * @param $update boolean reorder the keys, only applies to numerical arrays
	 * @param $string boolean whether or not to be extra strict on whitespace
	 * @return array
	 */
    function array_clean($input, $update = true, $strict = true){
		$tmp = array();
		foreach($input as $key => $value){
			if($strict){
				if(str_replace(' ', '', $value) != ''){
					if(is_numeric($key)){
						$tmp[] = $value;
					}else{
						$tmp[$key] = $value;
					}
				}
			}else{
				if($value != ''){
					if(is_numeric($key)){
						$tmp[] = $value;
					}else{
						$tmp[$key] = $value;
					}
				}
			}
		}

		// Return the nice clean array.
		return $tmp;
    }

	/**
	 * array_extract
	 *
	 * Allows dot arrays, such as Variable.name.
	 * Extracts the data for that variable.
	 *
	 * @param array $source
	 * @param string $path
	 * @return mixed
	 */
	function array_extract(& $source, $path, $return_null = true){
		$var =& $source;
		$path = explode('.', $path);
		foreach($path as $i => $key){
			if($i === count($path) - 1){
				if(isset($var[$key])){
					return $var[$key];
				}else{
					if($return_null === true){
						return null;
					}else{
						$var[$key] = array();
					}
				}
			}else{
				if(isset($var[$key])){
					$var = & $var[$key];
				}else{
					if($return_null === true){
						return null;
					}else{
						$var[$key] = array();
						$var = & $var[$key];
					}
				}
			}
		}

		return null;
	}

	/**
	 * array_inject
	 *
	 * Injects a value into a dot array.
	 *
	 * @param array $source
	 * @param string $path
	 * @param mixed $value
	 */
	function array_inject(& $source, $path, $value, $multiple = false){
		$var =& $source;
		$path = explode('.', $path);
		foreach($path as $i => $key){
			if($i === count($path) - 1){
				if($multiple === true){
					$var[$key][] = $value;
				}else{
					$var[$key] = $value;
				}
			}else{
				if(isset($var[$key])){
					$var = & $var[$key];
				}else{
					$var[$key] = array();
					$var = & $var[$key];
				}
			}
		}

		return null;
	}

	/**
	 * check_reserved_word
	 *
	 * Sometimes it may be the case to have a reserved word as a method name.
	 * Controller names are not supported, support may be added for them at a
	 * later release.
	 * This function checks to see if it has a reserved word as its name, and
	 * if so returns it with an underscore prepended.
	 *
	 * @param $method string	the method name
	 * @return string
	 */
    function check_reserved_word($method){
		$reserved_words = array(
			'new',
			'as',
			'break',
			'case',
			'class',
			'const',
			'continue',
			'declare',
			'default',
			'do',
			'extends',
			'global',
			'static',
			'switch',
			'use',
			'var',
			'interface',
			'implements',
			'instanceof',
			'public',
			'private',
			'protected',
			'abstract',
			'clone',
			'try',
			'catch',
			'throw',
			'this',
			'final',
			'namespace'
		);

		if(in_array($method, $reserved_words)){
			$method = '_' . $method;
		}

		return $method;
    }

?>