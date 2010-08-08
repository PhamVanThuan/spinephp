<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
    /**
     * Model.php
     *
     * This is the abstract model class, the blueprints for every model
     * in the system.
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

    abstract class Model {

		/**
		 * @var object $db database object
		 */
		protected $db;

		/**
		 * @var array $params array of parameters set with requests
		 */
		public $params = array();

		/**
		 * instance
		 *
		 * Creates an instance of the model, loading the database.
		 */
		public function instance(){
			if(Spine::loaded('Database')){
				$this->db = Database::get_instance();
			}
		}

		/**
		 * set_param
		 *
		 * Allows paramaters to be set.
		 *
		 * @param string $param
		 * @param string $value
		 */
		public function set_param($param, $value){
			$this->params[$param] = $value;
		}

    }

?>