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
		 * @var object $dbh database handle object
		 */
		protected $dbh;

		/**
		 * @var array $params array of parameters set with requests
		 */
		private $params = array();

		/**
		 * __construct
		 *
		 * Creates an instance of the model, loading the database.
		 */
		public function __construct(){
			if(Spine::loaded('Database')){
				$this->dbh = Database::get('default')->dbh;
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

		/**
		 * get_param
		 *
		 * Get a parameter.
		 *
		 * @param string $param
		 * @return mixed
		 */
		public function get_param($param){
			if(isset($this->params[$param])){
				return $this->params[$param];
			}
			return false;
		}

    }

?>