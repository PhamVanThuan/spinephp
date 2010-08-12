<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
    /**
     * config.php
     *
     * The basic configuration file. Feel free to tamper with these
     * settings, making sure they are the right ones of course.
     *
     * For futher information on the settings, see the docs.
     */

    /**
     * System URL
     *
     * The URL to the system root folder, including a trailing slash.
     * Example: http://www.mywebsite.com/
     */
    Config::write('General.system_url', 'http://localhost/spine/');

    /**
     * Friendly URLs
     *
     * If you are using mod_rewrite to get friendly URLs, set this
	 * to true so that any URLs that are built will not contain
	 * index.php/
	 * See the wiki for more.
     */
    Config::write('General.enable_friendly_urls', false);

    /**
     * Logging
     *
     * By default this is set to true, to enable logging. If you do not
     * wish to allows logging (errors, notices, warnings etc), set this
     * variable to false.
     */
    Config::write('General.enable_logging', true);

    /**
     * Default Controller
     *
     * The default controller is called when no controller is specified
     * in the URI. For instance, the user access just index.php.
     */
    Config::write('General.default_controller', 'welcome');

	/**
	 * Session Cookie Name
	 *
	 * Name of the session cookie.
	 */
	Config::write('Session.cookie_name', 'SPINEPHP');

	/**
	 * Session Timeout
	 *
	 * Value in seconds.
	 * A value of 0 will be when the users browser is reset.
	 */
	Config::write('Session.timeout', 0);

	/**
	 * Cookie Path
	 *
	 * Taken from php.net/setcookie:
	 * The path on the server in which the cookie will be available on. If set to '/', the cookie will be
	 * available within the entire domain. If set to '/foo/', the cookie will only be available within
	 * the /foo/ directory and all sub-directories such as /foo/bar/ of domain. The default value is the
	 * current directory that the cookie is being set in.
	 */
	Config::write('Cookie.path', '/');

	/**
	 * Cookie Domain
	 *
	 * Taken from php.net/setcookie:
	 * The domain that the cookie is available. To make the cookie available on all subdomains of example.com
	 * then you'd set it to '.example.com'. The . is not required but makes it compatible with more browsers.
	 * Setting it to www.example.com  will make the cookie only available in the www subdomain.
	 */
	Config::write('Cookie.domain', '');

	/**
	 * Cookie Secure
	 *
	 * Refer to php.net/setcookie
	 */
	Config::write('Cookie.secure', false);

	/**
	 * Cookie HTTP Only
	 *
	 * Refer to php.net/setcookie
	 */
	Config::write('Cookie.httponly', false);

	/**
	 * Extenders Load
	 *
	 * An array of extenders to load.
	 * See <http://www.spinephp.org/wiki/SpinePHP:Guide/Extenders>
	 */
	Config::write('Extenders.load', array());

	/**
	 * Hooks Load
	 *
	 * An array of hooks to load.
	 * See <http://www.spinephp.org/wiki/SpinePHP:Guide/Hooks>
	 */
	Config::write('Hooks.load', array());

	/**
	 * Plugin Load
	 *
	 * An array of plugins to load.
	 * See <http://www.spinephp.org/wiki/SpinePHP:Guide/Plugins>
	 */
	Config::write('Plugin.load', array());

    /**
     * Library Load
     *
     * An array of libraries to load.
	 * See <http://www.spinephp.org/wiki/SpinePHP:Guide/Library>
     */
    Config::write('Library.load', array('Session','Cookie'));

    /**
     * Templating Configuration
     *
     * For general configuration of templates.
     * The default template is the template on which to load automatically.
	 * It is set in the form of array('folder_name','default_file')
	 * The folder name and default file are the default template and default template
	 * file to load.
     */
	Config::write('Template.default_template', array('default','html'));

	/**
	 * Template Helpers
	 *
	 * An array of helpers to be loaded for the template to display correctly.
	 */
	Config::write('Template.helpers', array('Html'));

	/**
	 * GZip Output Compression
	 *
	 * If the gzip extension is available and the browser supports gzip compression,
	 * the system will automatically compress output to increase performance.
	 */
	Config::write('Template.enable_gzip_compression', true);

	/**
	 * Strip Render New Lines
	 *
	 * When rendering the final output, to make the source file harder to read the
	 * system can strip new lines so that all output is on a single source line.
	 * Will also increase performance, although not as much as compression.
	 */
	Config::write('Template.strip_new_lines', false);

	/**
	 * Enable Caching
	 *
	 * A global caching setting, will overwrite any local cache values. If set to
	 * false, there will be no caching. If set to true, caching will apply to any
	 * files that make use of the caching features.
	 */
	Config::write('Template.enable_caching', true);

	/**
	 * Ignore CSS
	 *
	 * An array containing any CSS files to ignore when loading in CSS files.
	 */
	Config::write('Template.ignore.css', array());

	/**
	 * Order CSS
	 *
	 * An array containing the order of the CSS files, if empty they will be ordered alphabetically.
	 * This is handy if you have a certain file you wish to appear first. Example:
	 * If you have 2 files, main.css and links.css, by default links will be loaded in before main.
	 * However, if you create the order as: array('main')
	 * Then the main.css file will be loaded first, then the remaining files will be loaded alphabetically.
	 */
	Config::write('Template.order.css', array());

	/**
	 * Ignore JS
	 *
	 * An array containing any JS files to ignore when loading in JS files.
	 */
	Config::write('Template.ignore.js', array());

	/**
	 * Order JS
	 *
	 * See Order CSS above.
	 */
	Config::write('Template.order.js', array());

?>