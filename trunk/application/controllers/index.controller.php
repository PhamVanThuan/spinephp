<?php

	/**
	* Home Controller
	*
	* This is the default controller, when nothing is specified.
	* Shows a pretty welcome message, and that's about it.
	*/

	class IndexController extends Controller {

		/**
		 * Name of the controller, this is to allow autoloading of models.
		 */
		public $name = 'Index';

		/**
		 * An optional boolean to autoload models.
		 */
		public $enable_model_autoload = true;

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
			$this->write_view('content', 'index');
		}

		public function docs(){
			$this->write('title', 'Spine Documentation');
			$this->write_view('content', 'docs');
		}

	}

?>