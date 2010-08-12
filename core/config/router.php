<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * router.php
	 *
	 * Router configuration file, this is where custom routes are specified.
	 * Any custom routes will take precedence over the default route which is:
	 * 
	 * Router::register(
	 *		'default',
	 *		'(:controller(/:action(/:any)))(:special)',
	 *		array('controller' => 'welcome', 'action' => 'index')
	 * );
	 *
	 * The above route allows URI's such as:
	 * http://www.example.com/welcome/index
	 * http://www.example.com/welcome/index/arg1/arg2 (unlimited args)
	 * http://www.example.com/welcome/index:special,request
	 * http://www.example.com/welcome/index/arg1/arg2:special,request
	 *
	 * For more information on routes see the Wiki.
	 * <http://www.spinephp.org/wiki/SpinePHP:Guide/Routes>
	 */

?>
