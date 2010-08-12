<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
     * database.php
     *
     * Database specific configuration settings. These settings
	 * will only apply if a database is set to connect.
     */

	/**
     * Database Auto Connect
	 * 
     * Auto Connecting will automatically include the Database library,
     * and attempt to connect with the settings specified below.
	 *
	 * If you have specified the Database library to be auto loaded
	 * above, it will not auto connect unless this is set to true.
     */
    Config::write('Database.enable_auto_connect', true);

	/**
     * MySQL Host
     */
	Config::write('Database.host', 'localhost');

	/**
     * MySQL Username
     */
	Config::write('Database.username', 'root');

	/**
     * MySQL Password
     */
	Config::write('Database.password', '');

	/**
     * MySQL Database Name
     */
	Config::write('Database.dbname', 'mvc');

	/**
     * MySQL Prefix
     */
	Config::write('Database.prefix', '');

	/**
     * SQLite Database Path (only if using SQLite)
     */
	Config::write('Database.path', '');

	/**
     * MySQL Driver
     */
	Config::write('Database.driver', 'mysql');
?>