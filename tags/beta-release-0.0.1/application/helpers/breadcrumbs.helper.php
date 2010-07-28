<?php

	/**
	 * breadcrumbs.helper.php
	 *
	 * Breadcrumbs Helper Class.
	 */
	class BreadcrumbsHelper extends Helpers {

		/**
		 * _text
		 *
		 * Return a text version of the breadcrumbs.
		 *
		 * @param boolean $hyperlinked
		 * @param string $default_home_text
		 * @param string $separator
		 * @return string
		 */
		public function _text($hyperlinked = true, $default_home_text = 'Home', $separator = ' &raquo; '){
			if($this->registry->is_library_loaded('Breadcrumbs')){
				return $this->registry->Breadcrumbs->build_breadcrumb_text($default_home_text, $separator, $hyperlinked);
			}else{
				return null;
			}
		}

		/**
		 * _array
		 *
		 * Return an array of breadcrumbs.
		 *
		 * @param boolean $hyperlinked
		 * @param string $default_home_text
		 * @return array
		 */
		public function _array($hyperlinked = true, $default_home_text = 'Home'){
			if($this->registry->is_library_loaded('Breadcrumbs')){
				return $this->registry->Breadcrumbs->build_breadcrumb_array($default_home_text, $hyperlinked);
			}else{
				return null;
			}
		}

	}

?>
