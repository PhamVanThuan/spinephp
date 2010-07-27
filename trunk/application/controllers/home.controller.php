<?php

	/**
	* Home Controller
	*
	* This is the default controller, when nothing is specified.
	* Shows a pretty welcome message, and that's about it.
	*/

	class HomeController extends Controller {

		/**
		 * Name of the controller, this is to allow autoloading of models.
		 */
		public $name = 'Home';

		/**
		 * An optional boolean to autoload models.
		 */
		public $enable_model_autoload = true;

		/**
		 * Overwrite the global fallback option. Useful only in the default controller, where
		 * you want to fallback to a method by the name of the requested controller.
		 */
		public $enable_method_fallback = true;

		/**
		 * Enable the system to use the index method as a means of dispatching. If you
		 * want to manually handle how a method is run, enabling this means that no matter what
		 * method is entered, the index method will always be called. This allows for complete
		 * control over which method is then fired.
		 */
		public $enable_method_overwrite = false;

		/**
		 * Global helpers to load for every method of the controller, available to all methods.
		 */
		public $helpers = array('Html');
		
		public function index(){
			$this->write('title', 'Welcome to Spine');
			$this->write_view('content', 'home');
		}

	}

?>