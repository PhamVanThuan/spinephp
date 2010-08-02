<?php

	/**
	 * smarty.php
	 *
	 * Smarty Configuration Settings Example
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

	Config::write('Smarty.parse_template', false);
	Config::write('Smarty.compile_dir', TMP_PATH . 'smarty/compile/');
?>
