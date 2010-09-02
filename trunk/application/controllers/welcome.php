<?php

	/**
	* Welcome Controller
	*
	* This is the default controller, when nothing is specified.
	* Shows a pretty welcome message, and that's about it.
	*/
	
	class WelcomeController extends CoreController {

		/**
		 * Name of the controller, this is to allow autoloading of models as well as databases.
		 */
		public $name = 'Welcome';

		/**
		 * An optional boolean to autoload models.
		 */
		public $enable_model_autoload = true;

		/**
		 * Global helpers to load for every method of the controller, available to all methods.
		 */
		public $helpers = array('Html', 'Form');

		/**
		 * Array of libraries you want to make available for this controller. Individual libraries
		 * can be loaded via Spine::load() inside of a method. Application wide libraries can be
		 * loaded in Config.Library.load.
		 */
		public $libs = array();
		
		public function index(){
			$this->write_view('content', 'index')
				 ->prepare();
		}
	}

?>