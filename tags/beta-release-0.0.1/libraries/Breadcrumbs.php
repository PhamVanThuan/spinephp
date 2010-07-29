<?php

	/**
	 * Breadcrumbs.php
	 *
	 * The breadcrumbs library makes easy to configure breadcrumbs, with easy to use
	 * methods.
	 * Allows breadcrumbs to be created, linked or not.
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

	class Breadcrumbs extends Object {

		public $crumbs = array();

		/**
		 * build_breadcrumb_array
		 *
		 * Build the breadcrumb array, with hyperlinks if enabled.
		 *
		 * @param boolean $hyperlinked
		 * @param string $default_home_text
		 * @return array
		 */
		public function build_breadcrumb_array($hyperlinked = true, $default_home_text = 'Home'){
			array_unshift($this->crumbs, array(
				'name' => $default_home_text,
				'link' => SYS_URL
				));

			$tmp = array();
			foreach($this->crumbs as $key => $array){
				$string = '%s';
				if($hyperlinked === true && !empty($array['link'])){
					$string = '<a href="' . $array['link'] . '">%s</a>';
				}
				$tmp[] = sprintf($string, $array['name']);
			}

			return $tmp;
		}

		/**
		 * build_breadcrumb_text
		 *
		 * Build a text version of the breadcrumbs, takes a separator, just uses the array.
		 *
		 * @param boolean $hyperlinked
		 * @param string $default_home_text
		 * @param string $separator
		 * @return string
		 */
		public function build_breadcrumb_text($hyperlinked = true, $default_home_text = 'Home', $separator = ' &raquo; '){
			$tmp = $this->build_breadcrumb_array($default_home_text, $hyperlinked);
			$text = implode($separator, $tmp);
			return $text;
		}

		/**
		 * crumb
		 *
		 * Add a breadcrumb to the array, builds the URL if supplied.
		 *
		 * @param string $name
		 * @param mixed $url
		 */
		public function crumb($name, $url = null){
			$this->crumbs[] = array(
				'name' => $name,
				'link' => empty($url) ? null : $this->registry->Router->build_url($url)
			);
		}

	}

?>
