<?php

	/**
	 * smarty.hook.php
	 *
	 * Sets up smarty settings.
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

	$spine = Spine::get_instance();
	$smarty = $spine->controller->parser;

	// Set directories.
	$smarty->template_dir = BASE_PATH . APP_PATH . 'views/';
	$smarty->compile_dir = BASE_PATH . APP_PATH . 'views/';

	// Set any other variabless
	$smarty->debugging = false;
	$smarty->force_compile = true;
	$smarty->caching = false;
	$smarty->compile_check = true;
	$smarty->cache_lifetime = -1;

?>
