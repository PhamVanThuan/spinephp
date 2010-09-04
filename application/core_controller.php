<?php

	/**
	 * core_controller.php
	 *
	 * Core controller, extends controller library. Methods in here are application wide methods
	 * that can be accessed from any controller that extends the CoreController and not the
	 * standard Controller library.
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

	class CoreController extends Controller {

		/**
		 * __construct
		 *
		 * Constructor must run the parent constructor for it to
		 * work correctly. Any authentication can be placed in
		 * here as well.
		 */
		public function __construct($request){
			parent::__construct($request);
		}
		
	}

?>
