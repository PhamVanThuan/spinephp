<?php

	/**
	 * Helpers.php
	 *
	 * The Helpers class give basic functionality and methods to helpers.
	 * Helpers are used in Views and Template files.
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

	 class Helpers {

		/**
		 * load
		 *
		 * Load any helpers
		 *
		 * @param array $helpers array of helpers to load
		 * @return array
		 */
		public function load($helpers){
			$rt_helpers = array();
			foreach($helpers as $helper){

				// Helpers may be in the format of folder/subfolder/Helper
				if(strstr($helper, '/') !== false){
					$tmp = explode('/', $helper);
					$helper = array_pop($tmp);
					$fn_helper = implode('/', $tmp) . '/' . strtolower($helper);
				}else{
					$fn_helper = strtolower($helper);
				}

				if(file_exists(APP_PATH . 'helpers/' . $fn_helper . '.helper.php')){
					$cn_helper = $helper . 'Helper';

					if(!class_exists($cn_helper, false)){
						require(APP_PATH . 'helpers/' . $fn_helper . '.helper.php');
						$rt_helpers[$helper] = new $cn_helper($this->spine);

						// Some Helpers may want to load there own Helpers, lets do that. Recursion!
						if(isset($rt_helpers[$helper]->helpers)){
							$hp_helpers = $this->load_helpers($rt_helpers[$helper]->helpers);
							foreach($hp_helpers as $key => $val){
								$rt_helpers[$helper]->{$key} = $val;
							}
						}
					}
				}else{
					Errors::trigger('Could not locate helper file <strong>' . $fn_helper . '.helper.php</strong> in ' .
						BASE_PATH . APP_PATH . 'helpers/', E_USER_ERROR);
				}
			}
			return $rt_helpers;
		}

		/**
		 * build_url
		 *
		 * Alias of Router::build_url
		 *
		 * @param mixed $url
		 * @return string
		 */
		public function build_url($url){
			if(is_array($url)){
				// It's an array of URL elements.
				if(!isset($url['controller'])){
					return false;
				}
				if(!isset($url['action']) && count($url) > 1){
					// If no action was set, and there is something else in the URL
					// we need to manually add in an action if using query string, default to index.
					$url = array_slice($url, 0, 1, true) + array('action' => 'index') + array_slice($url, 1, null, true);
				}

				$replacers = array(
					'controller' => 'c',
					'action' => 'm'
				);

				if(Config::read('General.enable_query_string')){
					// Query String is enabled, let's build it.
					$tmp = array();
					foreach($url as $key => $value){
						$tmp[] = (isset($replacers[$key]) ? $replacers[$key] : $key) . '=' . $value;
					}
					$url = 'index.php?' . implode('&', $tmp);
				}else{
					// Pretty URLs, a lot nicer.
					$url = implode('/', $url);
				}

				if(isset($url)){
					return SYS_URL . $url;
				}
			}else{
				// It's a string, format of controller/method. Check if it's external.
				// No modifying should be required. Create a clean version basically.
				if(preg_match('#^(http|https|ftp|file)\:\/\/#i', $url)){
					return $url;
				}else{
					return SYS_URL . implode('/', array_clean(explode('/', $url)));
				}
			}
		}

		public function parse_attributes($attr){
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