<?php

    /**
     * Model.php
     *
     * This is the abstract model class, the blueprints for every model
     * in the system.
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

    abstract class Model {

		// the spine registry
		protected $spine;

		// access to database is shortened
		protected $DB;

		public function setup(){
			$this->spine =& Spine::get_instance();
			if($this->spine->is_library_loaded('Database')){
				$this->DB = $this->spine->Database;
			}
		}

		public function set_param($param, $value){
			$this->params[$param] = $value;
		}

    }

?>