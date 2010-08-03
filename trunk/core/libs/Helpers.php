<?php

	/**
	 * Helpers.php
	 *
	 * The Helpers class give basic functionality and methods to helpers.
	 * Helpers are used in Views and Template files.
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

	 class Helpers extends Object {

		 public static $loaded = array();

		/**
		 * load
		 *
		 * Load any helpers
		 *
		 * @param array $helpers array of helpers to load
		 * @return array
		 */
		public static function load($helpers){
			$rt_helpers = array();
			if(!empty($helpers)){
				foreach($helpers as $helper){
					// Helpers may be in the format of folder/sub/Helper
					if(strstr($helper, '/') !== false){
						$tmp = explode('/', $helper);
						$helper = array_pop($tmp);
						$fn_helper = implode('/', $tmp) . '/' . strtolower($helper);
					}else{
						$fn_helper = strtolower($helper);
					}

					if(file_exists(APP_PATH . 'views/helpers/' . $fn_helper . '.php')){
						$cn_helper = ucfirst($helper) . 'Helper';

							if(!in_array($cn_helper, Helpers::$loaded)){
								// Load in the helper file.
								require(APP_PATH . 'views/helpers/' . $fn_helper . '.php');
								Helpers::$loaded[] = $cn_helper;
							}

							// Set the new helper.
							$rt_helpers[$helper] = new $cn_helper;

							// Some helpers may want to load there own helpers.
							if(isset($rt_helpers[$helper]->helpers)){
								$hp_helpers = Helpers::load($rt_helpers[$helper]->helpers);
								foreach($hp_helpers as $key => $val){
									$rt_helpers[$helper]->{$key} = $val;
								}
							}
						
					}else{
						trigger_error('Could not locate helper file <strong>' . $fn_helper . '.php</strong> in ' .
							BASE_PATH . APP_PATH . 'views/helpers/', E_USER_ERROR);
					}
				}
			}

			return $rt_helpers;
		}

		/**
		 * parse_attributes
		 *
		 * Takes an array of attributes and parses them.
		 *
		 * @param array $attr
		 * @return string
		 */
		public static function parse_attributes($attr){
			if(!is_array($attr) || empty($attr)){
				return null;
			}

			$tmp = array();
			foreach($attr as $attribute => $value){
				$tmp[] = $attribute . '="' . $value . '"';
			}
			$string = implode(' ', $tmp);
			return $string;
		}

	}

?>