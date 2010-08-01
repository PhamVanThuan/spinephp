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
	$tmp_directory = 'tmp';

	/**
	 * Plugin Directory
	 */
	$plugin_directory = 'plugins';

	// Let's set a few constants.
	define('APP_PATH', $application_directory . (substr($application_directory, -1) === '/' ? '' : '/'));
	define('LIB_PATH', $library_directory . (substr($library_directory, -1) === '/' ? '' : '/'));
	define('TMP_PATH', $tmp_directory . (substr($tmp_directory, -1) === '/' ? '' : '/'));
	define('DB_PATH', APP_PATH . 'database/');
	define('BASE_PATH', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

	// The base path may need to be set to something else.
	// If this one fails, then who knows...
	if(!is_dir(BASE_PATH)){
		if(!is_dir(BASE_PATH)){
			die("Dang, couldn't set a base path. Please consult the manual for futher instructions.");
		}
	}

	// Do a quick check on them.
	if(!is_dir(APP_PATH)){
		die("What the hell happened? Can't find the application directory, is your head on straight!?");
	}

	if(!is_dir(LIB_PATH)){
		die("We found the application directory, but now we can't find the library directory! Damn!");
	}

	if(!is_dir(TMP_PATH)){
		die("Couldn't locate the temporary files path. Bugger.");
	}

	if(!is_dir(DB_PATH)){
		die("Can't find the Database directory, very unfortunate.");
	}

	// Disable Magic Quotes, if running less then PHP 5.3.0
	if(version_compare(PHP_VERSION, '5.3.0', '<')){
		set_magic_quotes_runtime(false);
	}

	if(file_exists('./install.php')){
		// The install file exists, let's require it then die.
		include_once('./install.php');
		exit;

		// This performs the environment tests.
	}

	require_once(LIB_PATH . 'Common.php');
	require_once(LIB_PATH . 'Config.php');
	Config::get_instance();
	
	// Define SYS_URL now that the config is available.
	define('SYS_URL', Config::read('General.system_url'));

	require_once(LIB_PATH . 'Errors.php');
	require_once(LIB_PATH . 'Hooks.php');

	// Autoload any hooks that are in the config.
	Hooks::autoload();

	// Run any hooks for System.before
	Hooks::run('System.before');

	require_once(LIB_PATH . 'Spine.php');
	Spine::run();
?>