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

		protected $registry;
		protected $DB;

		public function __construct(){
			$this->registry =& Registry::get_instance();
			if($this->registry->is_library_loaded(array('Database', 'DB'))){
				$this->DB = $this->registry->DB;
			}
		}

    }

?>