<?php

	/**
	* Home Controller
	*
	* This is the default controller, when nothing is specified.
	* Shows a pretty welcome message, and that's about it.
	*/

	class AboutController extends Controller {

		/**
		 * Name of the controller, this is to allow auto-loading of models.
		 */
		public $name = 'About';

		/**
		 * An optional boolean to auto-load models.
		 */
		public $autoload_model = true;

		/**
		 * Overwrite the global fallback option. Useful only in the default controller, where
		 * you want to fallback to a method if the system could not find the controller.
		 */
		public $enable_method_fallback = true;

		/**
		 * Global helpers to load for every method of the controller, available to all views.
		 */
		public $helpers = array('Html');

		public function index(){
			$this->view->set('posts', 'We made it to the about page!');
			$this->write_view('content', 'home');
		}

		public function foo(){
			$this->view->set('posts', 'some different asfasfs');
			$this->write_view('content', 'home');
		}

	}

?>