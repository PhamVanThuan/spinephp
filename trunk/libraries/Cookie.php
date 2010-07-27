<?php

	/**
	 * Cookie.php
	 *
	 * Allows for easier use of the cookie methods and cookie features. If loaded,
	 * is placed into the Controller library to allow for easier access.
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

	class Cookie extends Object {
		
		/**
		 * write
		 *
		 * Write a Cookie.
		 *
		 * @param string $name The dot array of the cookie to write
		 * @param string $value The value of the cookie
		 * @param array $expire An array of when the cookie will expire
		 * @param string $path The cookie path
		 * @param string $domain The cookie domain
		 * @param boolean $secure Secure
		 * @param boolean $httponly HttpOnly
		 * @return boolean
		 */
		public function write($name, $value = '', $expire = array('year' => 0, 'month' => 0, 'day' => 0, 'hour' => 0, 'min' => 0, 'sec' => 0)){
			if(empty($name)){
				return false;
			}

			// Make sure no negatives were passed.
			foreach(array_values($expire) as $e){
				if($e < 0){
					return false;
				}
			}

			// Set values
			$path = Config::read('Cookie.path');
			$domain = Config::read('Cookie.domain');
			$secure = Config::read('Cookie.secure');
			$httponly = Config::read('Cookie.httponly');

			if($secure){
				if(!isset($_SERVER['HTTPS'])){
					$secure = false;
				}
			}

			// No build the proper expiration date.
			$tmp = 0;
			foreach($expire as $type => $amount){
				if($amount > 0){
					switch($type){
						case 'year':
							$tmp += (60 * 60 * 24 * 365 * $amount);
						break;
						case 'month':
							$tmp += (60 * 60 * 24 * 30 * $amount);
						break;
						case 'day':
							$tmp += (60 * 60 * 24 * $amount);
						break;
						case 'hour':
							$tmp += (60 * 60 * $amount);
						break;
						case 'min':
							$tmp += (60 * $amount);
						break;
						case 'sec':
							$tmp += $amount;
						break;
						default:
							return false;
					}
				}
			}

			$expire = $tmp;

			// Now set the cookie.
			$_path = explode('.', $name);
			$tmp = '';
			foreach($_path as $i => $key){
				if($i === 0){
					$tmp = $key;
				}else{
					$tmp .= '[' . $key . ']';
				}
			}

			if(@setcookie($tmp, $value, time() + $expire, $path, $domain, $secure, $httponly)){
				return true;
			}else{
				return false;
			}
		}

		/**
		 * read
		 *
		 * Read the value of a Cookie by its name.
		 *
		 * @param string $name
		 * @return mixed
		 */
		public function read($name = null){
			if(empty($name)){
				return $_COOKIE;
			}else{
				return array_extract($_COOKIE, $name);
			}
		}

		/**
		 * delete
		 *
		 * Delete a cookie from the user.
		 *
		 * @param string $name
		 * @return boolean
		 */
		public function delete($name){
			$path = Config::read('Cookie.path');
			$domain = Config::read('Cookie.domain');
			$secure = Config::read('Cookie.secure');
			$httponly = Config::read('Cookie.httponly');

			if($secure){
				if(!isset($_SERVER['HTTPS'])){
					$secure = false;
				}
			}
			
			if($this->check($name)){
				// Cookie exists, let's delete it.
				$path = explode('.', $name);
				$tmp = '';
				$var = & $_COOKIE;
				$type = '';
				foreach($path as $i => $key){
					if($i === 0){
						$tmp = $key;
					}else{
						$tmp .= '[' . $key . ']';
					}

					if($i === count($path)){
						$type = is_array($var[$key]) ? 'array' : 'string';
					}else{
						if(isset($var[$key])){
							$type = is_array($var[$key]) ? 'array' : 'string';
						}
						$var = & $var[$key];
					}
				}

				// Check to see if the cookie we are deleting is an array.
				if(!empty($type)){
					if($type == 'array'){
						// Deleting every element in the array
						foreach($var as $key => $val){
							$del = '[' . $key . ']';
							
							if(is_array($val)){
								foreach($val as $k => $v){
									$del .= '[' . $k . ']';
								}
							}
							
							@setcookie($tmp . $del, '', time() - (60 * 60 * 24 * 365), $path, $domain, $secure, $httponly);
						}
					}else{
						@setcookie($tmp, '', time() - (60 * 60 * 24 * 365), $path, $domain, $secure, $httponly);
					}

					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		/**
		 * clear
		 *
		 * Clear all cookies, can only be called via the Special Request if set.
		 */
		public function clear(){
			// Deleting all cookies.
			$path = Config::read('Cookie.path');
			$domain = Config::read('Cookie.domain');
			$secure = Config::read('Cookie.secure');
			$httponly = Config::read('Cookie.httponly');

			if($secure){
				if(!isset($_SERVER['HTTPS'])){
					$secure = false;
				}
			}
			
			foreach($_COOKIE as $key => $value){
				@setcookie($key, '', time() - (60 * 60 * 24 * 365), $path, $domain, $secure, $httponly);
			}
		}

		/**
		 * check
		 *
		 * Check if a cookie has been set
		 *
		 * @param string $name
		 * @return boolean
		 */
		public function check($name){
			if(empty($name)){
				return false;
			}
			$check = $this->read($name);
			return isset($check);
		}

	}

?>
