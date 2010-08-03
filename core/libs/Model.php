<?php

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

		// A property to allow quicker access to DB.
		protected $DB;

		// Parameters
		public $params = array();

		/**
		 * run
		 *
		 * Runs a model, setting the database and any other properties.
		 */
		public function run(){
			if(Spine::loaded('Database')){
				$this->DB = Database::get_instance();
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