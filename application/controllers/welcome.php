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
			$post = Validate::instance($_POST, true);
			if($post->is_ready()){
				if($post->execute()){
					$this->redirect('welcome/success');
				}
			}

			$this->set('errors', $post->errors())
				 ->set('post', $post)
				 ->write_view('content', 'index')
				 ->prepare();
		}

		public function success(){
			die("Awesome, form was submitted successfully.");
		}

		public function post(){
			$post = Validate::instance($_POST, true);
			if($post
				->set_filter('username', 'Input::clean_xss')
				
				->set_rule('username', 'min_length', array(15))
				->set_error('username', 'min_length', 'Username did not meet minimum length requirement.')
				
				->set_rule('username', array($this->Welcome, 'check_valid_username'))
				->set_error('username', 'check_valid_username', 'Username is already in database.')

				->set_rule('amount', 'decimal', array(-1, 3))
				->execute()){
				die('form executed successfully');
			}else{
				$fields = $post->error_fields();
				$errors = $post->errors();
				die(print_r($errors));
			}
		}

		public function _example($str){
			return ucfirst($str);
		}
	}

?>