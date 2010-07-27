<?php
	
	/**
	 * SpinePHP: An Open Source Framework developed in PHP5.
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

	/**
	 * Set Error Reporting.
	 *
	 * For a live website, it is recommended that this is not set to E_ALL.
	 */
	error_reporting(E_ALL);

	/**
	 * Application Directory
	 *
	 * Only reason you should change this is for security reasons.
	 * Generally, it can be left as application.
	 */
	$application_directory = 'application';

	/**
	 * Library Directory
	 *
	 * The name of the library directory. Shouldn't need
	 * to be changed. If you did, change it here.
	 */
	$library_directory = 'libraries';

	/**
	 * Temp Directory
	 *
	 * The name of the tmp folder, by default this is tmp.
	 * If you have changed the name of the folder, change it here.
	 */
	$tmp_directory = 'temp';

	/**
	* DB Directory
	*
	* The name of the database directory, by default this is database.
	*/
	$db_directory = 'database';

	/**
	 * Plugin Directory
	 */
	$plugin_directory = 'plugins';

	/**
	 * Base Path
	 *
	 * Leave this as blank, unless you know you need to change it.
	 */
	$base_path = '';

	// Let's set a few constants.
	define('APP_PATH', $application_directory . (substr($application_directory, -1) === '/' ? '' : '/'));
	define('LIB_PATH', $library_directory . (substr($library_directory, -1) === '/' ? '' : '/'));
	define('TMP_PATH', $tmp_directory . (substr($tmp_directory, -1) === '/' ? '' : '/'));
	define('DB_PATH', $db_directory . (substr($db_directory, -1) === '/' ? '' : '/'));
	define('PLUGIN_PATH', $plugin_directory . (substr($plugin_directory, -1) === '/' ? '' : '/'));
	define('BASE_PATH', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

	// The base path may need to be set to something else.
	// If this one fails, then who knows...
	if(!is_dir(BASE_PATH)){
		define('BASE_PATH', substr($_SERVER['DOCUMENT_ROOT'], 0, -1) .
		str_replace('index.php', '', ($_SERVER['SCRIPT_NAME'] !== '' ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF']))
		);

		if(!is_dir(BASE_PATH)){
			// Last option is to see if the variable has been set
			if(!empty($base_path)){
				define('BASE_PATH', $base_path);
			}

			if(!is_dir(BASE_PATH)){
				die("Dang, couldn't set a base path. Please consult the manual for futher instructions.");
			}
		}
	}

	// Do a quick check on them.
	if(!is_dir(APP_PATH)){
		die("What the hell happened? Can't find the application directory, is your head on straight!?");
	}

	if(!is_dir(LIB_PATH)){
		die("We found the application directory, but now we can't find the library directory! Damn!");
	}

	if(!is_dir(DB_PATH)){
		die("Can't find the Database directory, very unfortunate.");
	}

	if(!is_dir(PLUGIN_PATH)){
		die("No plugin directory, or you entered it wrong. Why!?");
	}

	// Disable Magic Quotes, if running less then PHP 5.3.0
	if(version_compare(PHP_VERSION, '5.3.0', '<')){
		set_magic_quotes_runtime(false);
	}

	require_once(LIB_PATH . 'Common.php');
	require_once(LIB_PATH . 'Config.php');
	Config::get_instance();

	require_once(LIB_PATH . 'Hooks.php');
	require_once(LIB_PATH . 'Errors.php');
	require_once(LIB_PATH . 'Registry.php');

?>