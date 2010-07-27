<?php

    /**
     * Model.php
     *
     * This is the abstract model class, the blueprints for every model
     * in the system.
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