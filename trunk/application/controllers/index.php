<?php

	/**
	* Index Controller
	*
	* This is the default controller, when nothing is specified.
	* Shows a pretty welcome message, and that's about it.
	*/

	class IndexController extends Controller {

		/**
		 * Name of the controller, this is to allow autoloading of models as well as databases.
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
		public $helpers = array();

		/**
		 * Array of libraries you want to make available for this controller. Individual libraries
		 * can be loaded via Spine::load() inside of a method. Application wide libraries can be
		 * loaded in Config.Library.load.
		 */
		public $libs = array();
		
		public function index(){
			$this->write_view('content', 'index');
			$this->prepare();
		}
	}

?>