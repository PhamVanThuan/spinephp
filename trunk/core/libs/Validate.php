<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Validate.php
	 *
	 * Provides an easy to use validation for data by assigning rules to specific
	 * input keys.
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

	class Validate extends Object {

		/**
		 * instance
		 *
		 * Create a new validation instance for an array of data.
		 * Returns a validate object which can have rules set.
		 *
		 * @param array $array input array to perform validation.
		 * @param boolean $auto automatically create validation rules
		 * @return object Validate
		 */
		public static function instance($array, $auto = false){
			if(!is_array($array)){
				return false;
			}

			// Create a new validation object.
			$validate = new Validate($array, $auto);
			return $validate;
		}

		/**
		 * not_empty
		 *
		 * Checks if a value is not empty. If strict, whitespace characters
		 * are replaced to peform a strict search.
		 *
		 * @param string $value input value to check
		 * @param boolean $strict perform a strict search
		 * @return boolean
		 */
		public static function not_empty($value, $strict = false){
			if($strict === true){
				// Perform a strict search.
				$value = preg_replace('/\s+/s', '', $value);
			}
			return !empty($value);
		}

		/**
		 * min_length
		 *
		 * Check if a value is not less than a minimum length.
		 *
		 * @param string $value
		 * @param int $length
		 * @param boolean $whitespace
		 * @return mixed
		 */
		public static function min_length($value, $length, $whitespace = true){
			if($whitespace === false){
				// Strip whitespace
				$value = preg_replace('/\s+/s', '', $value);
			}

			return strlen($value) < $length ? array(false, 'Value did not meet the minimum length.') : true;
		}

		/**
		 * max_length
		 *
		 * Check if a value is not greater than a maximum length.
		 *
		 * @param string $value
		 * @param int $length
		 * @param boolean $whitespace
		 * @return boolean
		 */
		public static function max_length($value, $length, $whitespace = true){
			if($whitespace === false){
				// Strip whitespace
				$value = preg_replace('/\s+/s', '', $value);
			}

			return strlen($value) > $length ? array(false, 'Value entered was too long.') : true;
		}

		/**
		 * exact_length
		 *
		 * Check if a value is equal to a specified length.
		 *
		 * @param string $value
		 * @param int $length
		 * @param boolean $whitepsace
		 * @return boolean
		 */
		public static function exact_length($value, $length, $whitepsace = true){
			if($whitespace === false){
				// Strip whitespace
				$value = preg_replace('/\s+/s', '', $value);
			}

			return strlen($value) <> $length ? array(false, 'Value entered did not match required length.') : true;
		}

		/**
		 * alpha
		 *
		 * Checks if a string only contins alpha characters.
		 * Returns false if non-alpha characters found.
		 *
		 * @param string $value
		 * @return boolean
		 */
		public static function alpha($value){
			return (bool) preg_match('/^[a-z]+$/i', $value);
		}

		/**
		 * numeric
		 *
		 * Checks if a string only contins numeric characters.
		 * Returns false if non-numeric characters found.
		 *
		 * @param string $value
		 * @return boolean
		 */
		public static function numeric($value){
			return (bool) preg_match('/^[0-9]+$/', $value);
		}

		/**
		 * decimal
		 *
		 * Checks a value to see if it is a decimal, can include a check
		 * on meeting a certain amount of digits and where the decimal is placed.
		 *
		 * @param string $value
		 * @param int $digits
		 * @param int $places
		 * @return boolean
		 */
		public static function decimal($value, $digits = -1, $places = -1){
			if(($decimal = strpos($value, '.')) !== false){
				if($digits > 0 && (strlen($value) - 1) <> $digits){
					return false;
				}

				if($places > 0 && $places <> $decimal){
					return false;
				}

				return true;
			}else{
				return false;
			}
		}

		/**
		 * alpha_numeric
		 *
		 * Checks if a string only contins alpha-numeric characters.
		 * Returns false if non alpha-numeric characters found.
		 *
		 * @param string $value
		 * @return boolean
		 */
		public static function alpha_numeric($value){
			return (bool) preg_match('/^[a-z0-9]+$/i', $value);
		}

		/**
		 * alpha_dash
		 *
		 * Checks if a string only contins alpha-numeric and certain
		 * other characters.
		 * Returns false if non alpha-numeric characters and other
		 * characters found.
		 *
		 * @param string $value
		 * @return boolean
		 */
		public static function alpha_dash($value){
			return (bool) preg_match('/^[\w\d]+$/i', $value);
		}

		/**
		 * is_email
		 *
		 * Check if a value is a proper email.
		 *
		 * @param string $value
		 * @return boolean
		 */
		public static function is_email($value){
			return filter_var($value, FILTER_VALIDATE_EMAIL) === false ? false : true;
		}

		/**
		 * is_ip
		 *
		 * Check if a value is a proper IP address. Can set a type
		 * to filter by, such as IPv4 or IPv6.
		 *
		 * @param string $value
		 * @return boolean
		 */
		public static function is_ip($value, $type = null){
			if(empty($type)){
				return filter_var($value, FILTER_VALIDATE_IP) === false ? false : true;
			}else{
				return filter_var($value, FILTER_VALIDATE_IP, $type) === false ? false : true;
			}
		}

		/**
		 * is_url
		 *
		 * Check if a value is a proper URL.
		 *
		 * @param string $value
		 * @return boolean
		 */
		public static function is_url($value){
			return filter_var($value, FILTER_VALIDATE_URL) === false ? false : true;
		}

		/**
		 * creditcard
		 *
		 * Checks if a value is a valid credit card number denoted
		 * by the type of card it is.
		 *
		 * @param string $value
		 * @param string $card
		 * @return boolean
		 */
		public static function creditcard($value, $card){
			$cc = array(
				'visa'				=> '4[0-9]{12}(?:[0-9]{3})?',
				'mastercard'		=> '5[1-5][0-9]{14}',
				'american_express'	=> '3[47][0-9]{13}',
				'diners_club'		=> '3(?:0[0-5]|[68][0-9])[0-9]{11}',
				'discover'			=> '6(?:011|5[0-9]{2})[0-9]{12}',
				'jcb'				=> '(?:2131|1800|35\d{3})\d{11}'
			);

			$card = strtolower($card);
			if(!isset($cc[$card])){
				return false;
			}
			if(preg_match('/^' . $cc[$card] . '$/', $value)){
				return true;
			}else{
				return false;
			}
		}

		public static function regex($value, $expression){
			return (bool) preg_match($expression, $value);
		}

		/**
		 * hash
		 *
		 * Create an md5 hash of the value.
		 *
		 * @param string $value
		 * @return string
		 */
		private static function hash($value){
			return md5($value);
		}

		/**
		 * clean
		 *
		 * Clean an input array from any HTML Characters.
		 *
		 * @param array $array input array
		 * @param boolean $xss clean any possible xss
		 * @return array
		 */
		private static function clean($array, $xss = false){
			$tmp = array();
			foreach($array as $key => $val){
				if(is_array($val)){
					$tmp[$key] = Validate::clean($val, $xss);
				}else{
					$tmp[$key] = Input::chars($val);
					if($xss){
						$tmp[$key] = Input::clean_xss($tmp[$key]);
					}
				}
			}
			return $tmp;
		}

		/**
		 * @var array $__source input array data
		 */
		private $__source;

		/**
		 * @var array $__data filtered and checked data
		 */
		private $__data;

		/**
		 * @var array $__rules rules to apply to input data
		 */
		private $__rules = array();

		/**
		 * @var array $__filters filters to apply to input data
		 */
		private $__filters = array();

		/**
		 * @var array $__callbacks callbacks to apply to input data
		 */
		private $__callbacks = array();

		/**
		 * @var array $__errors errors encountered during validation
		 */
		private $__errors = array();

		/**
		 * @var array $__custom_errors array of custom error messages
		 */
		private $__custom_errors = array();

		/**
		 * @var boolean $executed if validation has been executed
		 */
		private $executed = false;

		/**
		 * __construct
		 *
		 * Creates a new Validate object, if auto is enabled the source
		 * array will be read and rules automatically assigned to typically
		 * named fields.
		 *
		 * @param array $array input data array
		 * @param boolean $auto auto assign rules
		 */
		public function __construct($array, $auto = false){
			$this->__source = Validate::clean($array);
			
			if($auto === true){
				// Create some rules for fields that you might generally find
				// in most forms. This automates the validation process quite
				// a bit if you have a simple form.
				$typical = array(
					// rules
					'rules' => array(
						'username' => array(
							'not_empty' => array(),
							'alpha_dash' => array()
						),
						'password' => array(
							'not_empty' => array(),
							'regex' => array('/^[^\s]+$/i')
						),
						'password_confirm' => array(
							'not_empty' => array(),
							'is_match' => array('password')
						),
						'email' => array(
							'not_empty' => array(),
							'is_email' => array()
						),
						'email_confirm' => array(
							'not_empty' => array(),
							'is_email' => array(),
							'is_match' => array('email')
						)
					),
					// callbacks
					'callbacks' => array(
						'password' => array(
							'hash' => array()
						)
					),
					// error messages
					'errors' => array(
						'username' => array(
							'not_empty' => 'You did not enter a username.',
							'alpha_dash' => 'Your username contains invalid characters.'
						),
						'password' => array(
							'not_empty' => 'You did not enter a password.',
							'regex' => 'Password cannot contain spaces.'
						),
						'password_confirm' => array(
							'not_empty' => 'You did not confirm your password.',
							'is_match' => 'Your passwords did not match.'
						),
						'email' => array(
							'not_empty' => 'You did not enter an e-mail.',
							'is_email' => 'The e-mail address you entered was invalid.'
						),
						'email_confirm' => array(
							'not_empty' => 'You did not confirm your e-mail.',
							'is_email' => 'The confirmed e-mail address you entered was invalid.',
							'is_match' => 'The e-mail addresses you entered did not match.'
						)
					)
				);

				// Begin to check for typical fields that we have in our form.
				// Set any filters, rules, callbacks and errors that we may
				// need to process the form correctly. Only if auto is on.
				if(isset($typical['filters'])){
					foreach($typical['filters'] as $field => $filters){
						if(isset($this->__source[$field])){
							foreach($filters as $filter => $params){
								$this->set_filter($field, $filter, $params);
							}
						}
					}
				}

				if(isset($typical['rules'])){
					foreach($typical['rules'] as $field => $rules){
						if(isset($this->__source[$field])){
							foreach($rules as $rule => $params){
								$this->set_rule($field, $rule, $params);
							}
						}
					}
				}

				if(isset($typical['callbacks'])){
					foreach($typical['callbacks'] as $field => $callbacks){
						if(isset($this->__source[$field])){
							foreach($callbacks as $callback => $params){
								$this->set_callback($field, $callback, $params);
							}
						}
					}
				}

				if(isset($typical['errors'])){
					foreach($typical['errors'] as $field => $errors){
						if(isset($this->__source[$field])){
							foreach($errors as $rule => $message){
								$this->set_error($field, $rule, $message);
							}
						}
					}
				}
			}

		}

		/**
		 * set_filter
		 *
		 * Sets a filter to the current validation object.
		 *
		 * @param string $field
		 * @param mixed $callback
		 * @param array $params
		 * @return object Validate
		 */
		public function set_filter($field, $callback, $params = array()){
			// Set the filter in the property.
			$this->__filters[$field][] = array(
				'callback' => $callback,
				'params' => $params
			);

			// Return the object to allow appending.
			return $this;
		}

		/**
		 * set_rule
		 *
		 * Sets a rule to the current validation object.
		 *
		 * @param string $field
		 * @param mixed $callback
		 * @param array $params
		 * @return object Validate
		 */
		public function set_rule($field, $callback, $params = array()){
			// Set the rule in the property.
			$this->__rules[$field][] = array(
				'callback' => $callback,
				'params' => $params
			);

			// Return the object to allow appending.
			return $this;
		}

		/**
		 * set_callback
		 *
		 * Sets a callback to the current validation object.
		 *
		 * @param string $field
		 * @param mixed $callback
		 * @param array $params
		 * @return object Validate
		 */
		public function set_callback($field, $callback, $params = array()){
			// Set the callback in the property.
			if(!in_array($callback, $this->__callbacks, true)){
				$this->__callbacks[$field][] = array(
					'callback' => $callback,
					'params' => $params
				);
			}

			// Return the object to allow appending.
			return $this;
		}

		/**
		 * set_error
		 *
		 * Set a custom error for a specific field and rule.
		 *
		 * @param string $field
		 * @param string $rule
		 * @param string $message
		 * @return object Validate
		 */
		public function set_error($field, $rule, $message){
			if(!isset($this->__custom_errors[$field])){
				$this->__custom_errors[$field] = array();
			}

			$this->__custom_errors[$field][$rule] = $message;

			// Return the object to allow appending.
			return $this;
		}

		/**
		 * execute
		 *
		 * Executes the current validation, applies filters, rules and
		 * callbacks to input data and returns true on success or false
		 * on failure.
		 *
		 * @return boolean
		 */
		public function execute(){
			// Execution has taken place.
			$this->executed = true;
			
			// Create local scope of filters and rules.
			$filters = $this->__filters;
			$rules = $this->__rules;
			$callbacks = $this->__callbacks;

			// Set the data property to our source.
			$this->__data = $this->__source;

			// Set validated data and errors array.
			$validated = $this->__errors = array();

			// Set errors and data local scope.
			$source = $this->__source;
			$data =& $this->__data;
			$errors =& $this->__errors;

			foreach($data as $field => $value){
				if(isset($filters[true])){
					if(!isset($filters[$field])){
						$filters[$field] = array();
					}
					$filters[$field] += $filters[true];
				}

				if(isset($rules[true])){
					if(!isset($rules[$field])){
						$rules[$field] = array();
					}
					$rules[$field] += $rules[true];
				}

				if(isset($callbacks[true])){
					if(!isset($callbacks[$field])){
						$callbacks[$field] = array();
					}
					$callbacks[$field] += $callbacks[true];
				}
			}

			// Process input filters.
			foreach($filters as $field => $filters){
				foreach($filters as $key => $filter){
					$_filter_cb = $filter['callback'];
					$_filter_params = $filter['params'];
					array_unshift($_filter_params, $source[$field]);
					
					if(is_array($_filter_cb)){
						// Assume filter is an object or static method.
						$method = new ReflectionMethod($_filter_cb[0], $_filter_cb[1]);
						$data[$field] = $method->invokeArgs($method->isStatic() ? null : $_filter_cb[0], $_filter_params);
					}else{
						// Check for class filter.
						if(strpos($_filter_cb, '::') === false){
							// Normal function.
							$func = new ReflectionFunction($_filter_cb);
							$data[$field] = $func->invokeArgs($_filter_params);
						}else{
							// It's a static filter.
							list($class, $method) = explode('::', $_filter_cb);
							$method = new ReflectionMethod($class, $method);
							$data[$field] = $method->invokeArgs(null, $_filter_params);
						}
					}
				}
			}

			// Process validation rules.
			foreach($rules as $field => $rules){
				if(empty($rules)){
					break;
				}

				foreach($rules as $key => $rule){
					$_rule_cb = $rule['callback'];
					$_rule_params = $rule['params'];
					array_unshift($_rule_params, $source[$field]);

					// Set some defaults.
					$result = false;
					$method = $_rule_cb;
					$reflect = null;

					if(is_array($_rule_cb)){
						// Assume rule is an object or static method.
						list($class, $method) = $_rule_cb;
						$reflect = new ReflectionMethod($class, $method);
						$obj = $class;
					}elseif(strpos($_rule_cb, '::') !== false){
						// Rule is a static method, createa a reflection.
						list($class, $method) = explode('::', $_rule_cb);
						$reflect = new ReflectionMethod($class, $method);
						$obj = $class;
					}elseif(method_exists('Validate', $_rule_cb)){
						// Static method for the validation class
						$reflect = new ReflectionMethod('Validate', $_rule_cb);
						$obj = $this;
					}else{
						if(function_exists($_rule_cb)){
							$result = call_user_func_array($_rule_cb, $_rule_params);
						}else{
							$result = false;
						}
					}
					if(is_object($reflect)){
						if($reflect->isPublic()){
							// Use reflection to invoke the static method.
							$result = $reflect->invokeArgs($reflect->isStatic() ? null : $obj, $_rule_params);
						}else{
							// Reflection can't be used on private/protected methods.
							$result = call_user_func_array(array('Validate', $_rule_cb), $_rule_params);
						}
					}
					
					if(is_array($result)){
						list($status, $message) = $result;
						if($status === false){
							if(!isset($errors[$field])){
								$errors[$field]['rule'][$method] = isset($this->__custom_errors[$field][$method]) ? $this->__custom_errors[$field][$method] : $message;
							}
						}
					}elseif($result === false){
						if(!isset($errors[$field])){
							$errors[$field]['rule'][$method] = isset($this->__custom_errors[$field][$method]) ? $this->__custom_errors[$field][$method] : 'Field \'' . $field . '\' did not validate against ' . $method . '().';
						}
					}
				}
			}

			// Process callback functions.
			foreach($callbacks as $field => $callbacks){
				if(empty($callbacks)){
					break;
				}

				foreach($callbacks as $key => $callback){
					$_callback = $callback['callback'];
					$_params = $callback['params'];

					if(!empty($errors[$field]['callback'][$_callback])){
						// error has been encountered on this field and callback, don't bother again.
						break;
					}

					// add the data array and field name to the params, don't reference the data array
					// as it throws errors.
					array_unshift($_params, $source[$field]);

					// Set some defaults.
					$passed = false;
					$reflect = null;
					$method = $_callback;

					if(is_array($_callback)){
						// Assume callback is an object or static method.
						list($class, $method) = $_callback;
						
						$reflect = new ReflectionMethod($class, $method);
						$obj = $class;
					}elseif(strpos($_callback, '::') !== false){
						// Callback is a static method.
						list($class, $method) = explode('::', $_callback);
						$reflect = new ReflectionMethod($class, $method);
						$obj = $class;
					}elseif(method_exists('Validate', $_callback)){
						// Callback is a static method in the Validate class.
						$reflect = new ReflectionMethod('Validate', $_callback);
						$obj = $this;
					}else{
						if(function_exists($_callback)){
							// Callback is a PHP function.
							$data[$field] = call_user_func_array($_callback, $_params);
						}else{
							die('Could not apply callback to ' . $field . ' using callback method ' . $_callback . '().');
						}
					}
					if(is_object($reflect)){
						$passed = true;
						if($reflect->isPublic()){
							// Use reflection to invoke the static method.
							$data[$field] = $reflect->invokeArgs($reflect->isStatic() ? null : $obj, $_params);
						}else{
							// Reflection can't be used on private/protected methods.
							$data[$field] = call_user_func_array(array('Validate', $_callback), $_params);
						}
					}
				}
			}

			// Return false if there is an error.
			return !$this->is_error();
		}

		/**
		 * data
		 *
		 * Return validated data once execution has been completed.
		 * Data can be returned for a single field or all fields.
		 *
		 * @param string $field
		 * @return mixed
		 */
		public function data($field = null){
			if($this->executed === false){
				return false;
			}
			
			if(!empty($field) && isset($this->__source[$field])){
				return $this->__data[$field];
			}else{
				return $this->__data;
			}
		}

		/**
		 * source
		 *
		 * Identical to above, except returns a source value.
		 */
		public function source($field = null){
			if($this->executed === false){
				return false;
			}

			if(!empty($field) && isset($this->__source[$field])){
				return $this->__source[$field];
			}else{
				return $this->__source;
			}
		}

		/**
		 * errors
		 *
		 * Return all errors or individual field errors.
		 *
		 * @param string $field name of field to get errors for
		 * @param boolean $simple return a simple error message instead of full
		 * @return array
		 */
		public function errors($field = null, $simple = true){
			$tmp = array();
			$types = array('rule','callback','filter');
			
			if(!empty($field)){
				if(isset($this->__errors[$field])){
					$errors = $this->__errors[$field];
					
					for($i = 0; $i < count($types); ++$i){
						if(isset($errors[$types[$i]])){
							// found errors for current type
							foreach($errors[$types[$i]] as $method => $message){
								$tmp[] = $simple ? $message : '[' . ucfirst($types[$i]) . ': ' . $method . '()]: ' . $message;
							}
						}
					}

					return $tmp;
				}
			}

			foreach($this->__errors as $field => $errors){
				
				for($i = 0; $i < count($types); ++$i){
					if(isset($errors[$types[$i]])){
						// found errors for current type
						foreach($errors[$types[$i]] as $method => $message){
							$tmp[] = $simple ? $message : '[' . ucfirst($types[$i]) . ': ' . $method . '()]: ' . $message;
						}
					}
				}
			}

			return $tmp;
		}

		/**
		 * error_fields
		 *
		 * Return an array of fields which have errors.
		 *
		 * @return array
		 */
		public function error_fields(){
			return array_keys($this->__errors);
		}

		/**
		 * is_error
		 *
		 * Checks if any errors were encountered after execution.
		 * Returns true if there are errors, false if no errors
		 * or validation has not been executed.
		 *
		 * @return boolean
		 */
		public function is_error(){
			if(!$this->executed){
				return false;
			}
			
			$errors = array_clean($this->__errors);
			return !empty($errors);
		}

		/**
		 * is_ready
		 *
		 * Checks if there is data available, useful when peforming validation
		 * on things like $_POST
		 *
		 * @return boolean
		 */
		public function is_ready(){
			return !empty($this->__source);
		}

		/**
		 * is_match
		 *
		 * Checks if a value matches the value of the other given key.
		 *
		 * @param mixed $value input value
		 * @param mixed $match key of input source to match against
		 */
		protected function is_match($value, $match){
			return $value === $this->__source[$match];
		}

	}
?>