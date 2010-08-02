<?php

	/**
	 * smarty.plugin.php
	 *
	 * Smarty Plugin, allows you to use Smarty to parse your views.
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

	// Include the Smarty class.
	include('Smarty.class.php');

	class SmartyPlugin extends Smarty {

		public function set_hooks(){
			Hooks::register('smarty', 'Controller.afterConstruct', null, $this, 'set_smarty_object', array('unhook' => 'unset_smarty_object'));
		}

		public function execute(){
			$this->set_smarty_object();
		}

		public function unset_smarty_object(){
			// Get the controllers object and destroys the smarty object.
			$controller =& Router::$controller;
			unset($controller->Smarty);
		}

		public function set_smarty_object(){
			// Get the controllers object and create a smarty object.
			$controller =& Router::$controller;
			$controller->Smarty =& $this;

			// Array of required smarty settings
			$settings = $apply = array(
				'template_dir' => APP_PATH . 'views/',
				'compile_dir' => APP_PATH . 'views/',
				'cache_dir' => TMP_PATH . 'cache/',
				'caching' => 0,
				'parse_template' => false
			);
			
			// Check if we have some smarty settings in the Config.
			if(Config::read('Smarty')){
				$_config = Config::read('Smarty');
				foreach($settings as $key => $val){
					if(isset($_config[$key]) && !empty($_config[$key])){
						$controller->Smarty->{$key} = $_config[$key];

						// Remove from apply.
						unset($apply[$key]);
					}
				}
			}

			if(!empty($apply)){
				// Any variables left to apply, then set as default.
				foreach($apply as $key => $val){
					$controller->Smarty->{$key} = $val;
				}
			}
		}

	}

?>
