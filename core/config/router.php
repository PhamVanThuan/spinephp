<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * router.php
	 *
	 * Router configuration file, this is where custom routes are specified.
	 * Any custom routes will take precedence over the default route which is:
	 * 
	 * Router::set(
	 *		'default',
	 *		'(:controller(/:action(/:id(/:any))))(:special)',
	 *		array('controller' => 'welcome', 'action' => 'index')
	 * );
	 *
	 * The above route allows URI's such as:
	 * http://www.example.com/welcome/index
	 * http://www.example.com/welcome/index/arg1/arg2 (unlimited args)
	 * http://www.example.com/welcome/index:special,request
	 * http://www.example.com/welcome/index/arg1/arg2:special,request
	 *
	 * Note the :id parameter, this allows controllers to be nested in subdirectories.
	 * Example: /application/controllers/admin/dashboard.php
	 *			http://www.example.com/admin/dashboard
	 *
	 * Above example would load the dashboard.php file in /admin and run index().
	 *
	 * For more information on routes see the Wiki.
	 * <http://www.spinephp.org/wiki/SpinePHP:Guide/Routes>
	 */
	
?>
