<?php

	/**
	 * forms.php
	 *
	 * Form Helper Class
	 * This class provides methods to create a form that is portable and easy to manage.
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

	class FormHelper {

		/**
		 * @var array $__inputs available input field types
		 */
		private $__inputs = array(
			'hidden',
			'text',
			'password',
			'file',
			'checkbox',
			'radio',
			'submit',
			'reset'
		);

		/**
		 * open
		 *
		 * Create a form opening tag, action can be set to mimic a route
		 * or follow a standard route. Can be set to allow files to be uploaded.
		 *
		 * @param mixed $action
		 * @param string $method
		 * @param boolean $files
		 * @param array $attr
		 * @return string
		 */
		public function open($action = null, $method = 'post', $files = false, $attr = array()){
			if(is_array($action)){
				list($uri, $route) = $action;
				// Attempt to create a URI based on the given route.
				$uri = Request::build_uri($uri, $route);
			}else{
				$uri = Request::build_uri($action);
			}

			// The method should be lowercase.
			$method = strtolower($method);

			// Ensure that it's a proper method.
			if(!in_array($method, array('post','get'))){
				$method = 'post';
			}

			if($files === true){
				// If files is true we need the enctype attribute.
				$attr['enctype'] = 'multipart/form-data';
			}

			$attr['action'] = $action;
			$attr['method'] = $method;

			// Parse any other attributes.
			$attr = Helpers::parse_attributes($attr);

			// Return the compiled form opening tag.
			return '<form' . $attr . '>';
		}

		/**
		 * hidden
		 *
		 * Create a hidden input field.
		 *
		 * @param string $name
		 * @param string $value
		 * @param array $attr
		 * @return string
		 */
		public function hidden($name, $value = null, $attr = array()){
			return $this->_input($name, 'hidden', $value, $attr);
		}

		/**
		 * input
		 *
		 * Create a text input field.
		 *
		 * @param string $name
		 * @param string $value
		 * @param array $attr
		 * @return string
		 */
		public function input($name, $value = null, $attr = array()){
			return $this->_input($name, 'text', $value, $attr);
		}

		/**
		 * password
		 *
		 * Create a password input field.
		 *
		 * @param string $name
		 * @param string $value
		 * @param array $attr
		 * @return string
		 */
		public function password($name, $value = null, $attr = array()){
			return $this->_input($name, 'password', $value, $attr);
		}

		/**
		 * file
		 *
		 * Create a file input field.
		 *
		 * @param string $name
		 * @param string $value
		 * @param array $attr
		 * @return string
		 */
		public function file($name, $value = null, $attr = array()){
			return $this->_input($name, 'file', $value, $attr);
		}

		/**
		 * select
		 *
		 * Create a select drop down box with options. Attribtues can be passed in,
		 * and individual option attributes can be as well. Supports multiple select
		 * lists.
		 *
		 * @param string $name
		 * @param array $options array of options
		 * @param mixed $selected single selected or multiple selected options
		 * @param array $attr select box specific attribtues
		 * @param array $options_attr individual option attributes
		 * @return string
		 */
		public function select($name, $options, $selected = array(), $attr = array(), $options_attr = array()){
			// Is it a multi select box?
			$multi = false;
			if(isset($attr['multiple']) && strtolower($attr['multiple']) === 'multiple'){
				$multi = true;
			}

			// Make sure our selected variable is an array.
			if(!is_array($selected)){
				if(empty($selected)){
					$selected = array();
				}else{
					$selected = array($selected);
				}
			}

			// It's not a multiple select and we have more than one selected, only use first selected.
			if($multi === false && count($selected) > 1){
				$selected = array(array_shift($selected));
			}
			$string = array();
			
			// Get some attributes for the select tag.
			$attr['name'] = $multi === true ? $name . '[]' : $name;
			$attr = Helpers::parse_attributes($attr);
			$string[] = '<select' . $attr . '>';

			// Now to loop through the options.
			foreach($options as $value => $text){
				// Do we have any attributes for this option?
				if(!isset($options_attr[$value])){
					$options_attr[$value] = array();
				}
				
				if(in_array($value, $selected)){
					$options_attr[$value]['selected'] = 'selected';
				}
				$options_attr[$value]['value'] = $value;
				$attr = Helpers::parse_attributes($options_attr[$value]);
				$string[] = '<option' . $attr . '>'. $text . '</option>';
			}
			$string[] = '</select>';

			// Return the imploded string to compile the select box.
			return implode('', $string);
		}

		/**
		 * checkbox
		 *
		 * Create an input checkbox which can be checked automatically. If name is an
		 * array then multiple checkboxes will be created and if names are the same an
		 * array will automatically be generated.
		 *
		 * @param mixed $name
		 * @param string $value
		 * @param boolean $checked
		 * @param array $attr
		 * @return string
		 */
		public function checkbox($name, $value = null, $checked = false, $attr = array()){
			if(!is_array($name)){
				if($checked === true){
					$attr['checked'] = 'checked';
				}else{
					if(isset($attr['checked'])){
						unset($attr['checked']);
					}
				}
			}else{
				for($i = 0; $i < count($name); ++$i){
					if(isset($name[$i][2]) && $name[$i][2] === true){
						$name[$i][2] = array();
						$name[$i][2]['checked'] = 'checked';
					}elseif(isset($name[$i][2]) && !is_array($name[$i][2])){
						$name[$i][2] = array();
					}

					if(isset($name[$i][3])){
						unset($name[$i][3]);
					}
				}
			}
			
			return $this->_input($name, 'checkbox', $value, $attr);
		}

		/**
		 * radio
		 *
		 * Create an input radio button, multiple radios can be created
		 * by passing an array of radios to the name parameter.
		 *
		 * @param mixed $name
		 * @param string $value
		 * @param boolean $checked
		 * @param array $attr
		 * @return string
		 */
		public function radio($name, $value = null, $checked = false, $attr = array()){
			if(!is_array($name)){
				if($checked === true){
					$attr['checked'] = 'checked';
				}else{
					if(isset($attr['checked'])){
						unset($attr['checked']);
					}
				}
			}else{
				for($i = 0; $i < count($name); ++$i){
					if(isset($name[$i][2]) && $name[$i][2] === true){
						$name[$i][2] = array();
						$name[$i][2]['checked'] = 'checked';
					}elseif(isset($name[$i][2]) && !is_array($name[$i][2])){
						$name[$i][2] = array();
					}

					if(isset($name[$i][3])){
						unset($name[$i][3]);
					}
				}
			}

			return $this->_input($name, 'radio', $value, $attr);
		}

		/**
		 * textarea
		 *
		 * Create an input textarea 
		 *
		 * @param <type> $name
		 * @param <type> $text
		 * @param <type> $rows
		 * @param <type> $cols
		 * @param <type> $attr
		 * @return <type>
		 */
		public function textarea($name, $text = null, $rows = 0, $cols = 0, $attr = array()){
			$string = array();
			if(!is_array($name)){
				$name = array(
					$name,
					$text,
					$rows,
					$cols,
					$attr
				);
			}
			for($i = 0; $i < count($name); ++$i){
				$name[$i][1] = !isset($name[$i][1]) ? null : $name[$i][1];
				$name[$i][2] = !isset($name[$i][2]) ? 0 : $name[$i][2];
				$name[$i][3] = !isset($name[$i][3]) ? 0 : $name[$i][3];
				$name[$i][4] = !isset($name[$i][4]) ? array() : $name[$i][4];
			}

			$arrays = array();
			$_last_name = null;
			foreach($name as $array){
				if(empty($_last_name)){
					$_last_name = $array[0];
					continue;
				}

				if($_last_name === $array[0]){
					if(!in_array($_last_name, $arrays)){
						$arrays[] = $_last_name;
					}
				}
				$_last_name = $array[0];
			}

			foreach($name as $set){
				list($name, $text, $rows, $cols, $attr) = $set;

				if(in_array($name, $arrays)){
					$name = $name . '[]';
				}

				$attr['rows'] = !isset($attr['rows']) ? $rows : $attr['rows'];
				$attr['cols'] = !isset($attr['cols']) ? $cols : $attr['cols'];
				$attr['name'] = $name;
				$attr = Helpers::parse_attributes($attr);

				$string[] = '<textarea' . $attr . '>';
				$string[] = Input::chars($text);
				$string[] = '</textarea>';
			}
			return implode('', $string);
		}

		/**
		 * button
		 *
		 * Create an input button.
		 *
		 * @param string $name
		 * @param string $text
		 * @param array $attr
		 * @return string
		 */
		public function button($name, $text, $attr = array()){
			if(!isset($attr['name'])){
				$attr['name'] = $text;
			}
			$attr = Helpers::parse_attributes($attr);
			return '<button' . $attr . '>' . $text . '</button>';
		}

		/**
		 * submit
		 *
		 * Create an input submit button.
		 *
		 * @param string $name
		 * @param string $value
		 * @param array $attr
		 * @return string
		 */
		public function submit($name, $value, $attr = array()){
			return $this->_input($name, 'submit', $value, $attr);
		}

		/**
		 * reset
		 *
		 * Create an input reset button.
		 *
		 * @param string $name
		 * @param string $value
		 * @param array $attr
		 * @return string
		 */
		public function reset($name, $value, $attr = array()){
			return $this->_input($name, 'reset', $value, $attr);
		}

		/**
		 * _input
		 *
		 * Method for creating all input fields, allows a type to be
		 * entered. Private access for this object only.
		 *
		 * @param string $name
		 * @param string $value
		 * @param array $attr
		 * @return string
		 */
		private function _input($name, $type, $value = null, $attr = array()){
			if(!in_array($type, $this->__inputs)){
				return false;
			}
			
			if(!is_array($name)){
				$name = array(
					array(
						$name,
						$value,
						$attr
					)
				);
			}

			for($i = 0; $i < count($name); ++$i){
				$name[$i][1] = !isset($name[$i][1]) ? null : $name[$i][1];
				$name[$i][2] = !isset($name[$i][2]) ? array() : $name[$i][2];
			}

			$arrays = array();
			$_last_name = null;
			foreach($name as $array){
				if(empty($_last_name)){
					$_last_name = $array[0];
					continue;
				}

				if($_last_name === $array[0]){
					if(!in_array($_last_name, $arrays)){
						$arrays[] = $_last_name;
					}
				}
				$_last_name = $array[0];
			}


			$string = array();
			foreach($name as $set){
				list($name, $value, $attr) = $set;
				// Make sure attr is an array.
				if(!is_array($attr)){
					$attr = array();
				}

				// Is it a array name.
				if(in_array($name, $arrays)){
					$name = $name . '[]';
				}

				$attr['name'] = $name;
				$attr['value'] = $value;
				$attr['type'] = $type;
				
				$attr = Helpers::parse_attributes($attr);
				$string[] = '<input' . $attr . ' />';
			}

			// Return compiled form field tag.
			return implode('', $string);
		}

		/**
		 * fieldset_open
		 *
		 * Open a fieldset with an optional legend.
		 *
		 * @param string $legend
		 * @param array $attr
		 * @return string
		 */
		public function fieldset_open($legend = null, $attr = array()){
			$attr = Helpers::parse_attributes($attr);
			$string = '<fieldset' . $attr . '>';

			if(!empty($legend)){
				$string .= '<legend>' . $legend . '</legend>';
			}
			return $string;
		}

		/**
		 * legend
		 *
		 * Create a fieldset legend.
		 *
		 * @param string $text
		 * @param array $attr
		 * @return string
		 */
		public function legend($text, $attr = array()){
			$attr = Helpers::parse_attributes($attr);
			return '<legend' . $attr . '>' . $text . '</legend>';
		}

		public function label($text, $for = null, $attr = array()){
			if(!empty($for)){
				$attr['for'] = $for;
			}
			$attr = Helpers::parse_attributes($attr);
			return '<label' . $attr . '>' . $text . '</label>';
		}

		/**
		 * fieldset_close
		 *
		 * Close an open fieldset tag.
		 *
		 * @return string
		 */
		public function fieldset_close(){
			return '</fieldset>';
		}

		/**
		 * close
		 *
		 * Create form closing tag.
		 *
		 * @return string
		 */
		public function close(){
			return "</form>";
		}

	}

?>