<?php
	/**
	 * Spine PHP: An Open Source MVC Framework written in PHP5.
	 *
	 * Copyright (c) 2010, Jason Lewis, Spine PHP Team (http://www.spinephp.org)
	 *
	 * Licensed under the BSD License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis
	 * @link		(http://www.spinephp.org)
	 * @license		BSD License (http://www.opensource.org/licenses/bsd-license.php)
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
	 * Core Path
	 *
	 * Name of the core directory, can just leave this as core.
	 */
	$core_path = 'core';

	/**
	 * Library Directory
	 *
	 * The name of the library directory. Shouldn't need
	 * to be changed. If you did, change it here.
	 */
	$library_directory = 'libs';

	/**
	 * Temp Directory
	 *
	 * The name of the tmp folder, by default this is tmp.
	 * If you have changed the name of the folder, change it here.
	 */
	$tmp_directory = 'temp';

	/**
	 * Nothing below here should be changed unless your a wacko!
	 * Hah! Nah, go nuts. If you bugger it, your fault not mine.
	 */
	define('DS', '/');
	define('USER_BASE_PATH', '');

	// Attempt to set a base path.
	$base_path = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']);
	if(!is_dir($base_path)){
		$base_path = $_SERVER['DOCUMENT_ROOT'] . str_replace('index.php', '', trim($_SERVER['SCRIPT_NAME'], DS));
		if(!is_dir($base_path)){
			die('Could not set a BASE_PATH based on the server variables. Please consult the manual for further instruction.');
		}
	}
	define('BASE_PATH', trim($base_path, DS));
	define('APP_PATH', $application_directory);
	define('TMP_PATH', $tmp_directory);
	define('CORE_PATH', $core_path);
	define('LIB_PATH', CORE_PATH . DS . $library_directory);
	define('DB_PATH', CORE_PATH . DS . 'database');

	if(!is_dir(APP_PATH)){
		die("Could not locate APP_PATH at " . APP_PATH);
	}

	if(!is_dir(LIB_PATH)){
		die("Could not locate LIB_PATH at " . LIB_PATH);
	}

	if(!is_dir(TMP_PATH)){
		die("Could not locate TMP_PATH at " . TMP_PATH);
	}

	if(!is_dir(DB_PATH)){
		die("Could not locate DB_PATH at " . DB_PATH);
	}

	// Disable Magic Quotes, if running less then PHP 5.3.0
	if(version_compare(PHP_VERSION, '5.3.0', '<')){
		set_magic_quotes_runtime(false);
	}

	if(file_exists(BASE_PATH . DS . 'install.php')){
		// The install file exists, let's require it then die.
		include_once(BASE_PATH . DS . 'install.php');
		exit;

		// This performs the environment tests.
	}

	require_once(BASE_PATH . DS . LIB_PATH . DS . 'Common.php');
	require_once(BASE_PATH . DS . LIB_PATH . DS . 'Config.php');
	Config::get_instance();
	
	// Define SYS_URL now that the config is available.
	define('SYS_URL', Config::read('General.system_url'));

	require_once(BASE_PATH . DS . LIB_PATH . DS . 'Errors.php');
	require_once(BASE_PATH . DS . LIB_PATH . DS . 'Hooks.php');

	// Autoload any hooks that are in the config.
	Hooks::autoload();

	// Run any hooks for System.before
	Hooks::run('System.before');

	require_once(BASE_PATH . DS . LIB_PATH . DS . 'Spine.php');
	Spine::instance();
?>