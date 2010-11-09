<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Session.php
	 *
	 * Handles all of the session methods. Is loaded into the Controller library,
	 * to allow for easy access to all the methods.
	 *
	 * Copyright (c) 2010, Jason Lewis, Spine PHP Team (http://www.spinephp.org)
	 *
	 * Licensed under the BSD License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis, Spine PHP Team
	 * @link		<http://www.spinephp.org>
	 * @license		BSD License <http://www.opensource.org/licenses/bsd-license.php>
	 */

	class Session extends Object {

		/**
		 * @var string $id id of current session
		 */
		protected static $id;

		/**
		 * instance
		 *
		 * Create a session instance, starting the session if not already started.
		 */
		public static function instance(){
			if(!Session::session_started()){
				Session::session_start();
			}
		}

		/**
		 * session_start
		 *
		 * Start a new session.
		 *
		 * @return boolean
		 */
		public static function session_start(){
			if(getenv('HTTPS')){
				// Use secure cookies if available.
				ini_set('session.cookie_secure', 1);
			}

			// Since we don't allow Sessions to be stored in the Database...
			ini_set('session.serialize_handler', 'php');
			ini_set('session.cookie_path', '/');

			// Attempt to save sessions in /tmp/sessions
			if(is_dir(TMP_PATH . 'sessions')){
				ini_set('session.save_path', TMP_PATH . DS . 'sessions');
			}
			
			ini_set('session.use_cookies', 1);
			ini_set('session.cookie_lifetime', Config::read('Session.timeout'));
			ini_set('session.use_trans_sid', 0);
			ini_set('url_rewriter.tags', '');

			if(!isset($_SESSION)){
				session_cache_limiter('must-revalidate');
				session_start();
			}
			
			return true;
		}

		/**
		 * session_started
		 *
		 * Check to see if the session has been started.
		 *
		 * @return boolean
		 */
		public static function session_started(){
			if(isset($_SESSION) && session_id()){
				return true;
			}

			return false;
		}

		/**
		 * write
		 *
		 * Set a session variable in the current session.
		 *
		 * @param string $name name of the session
		 * @param string $value value of the session
		 */
		public static function write($name, $value = ''){
			if(empty($name)){
				trigger_error('Failed to set a session because no session name was provided.', E_USER_ERROR);
			}
			array_inject($_SESSION, $name, $value);
		}

		/**
		 * read
		 *
		 * Return the value of a session.
		 *
		 * @param string $name name of session to get
		 * @return mixed
		 */
		public static function read($name = null){
			if(empty($name)){
				return $_SESSION;
			}else{
				return array_extract($_SESSION, $name);
			}
		}

		/**
		 * id
		 *
		 * Get and/or set the id of the current session.
		 *
		 * @param string $id session id
		 * @return string
		 */
		public static function id($id = null){
			if(!empty($id)){
				Session::$id = $id;
				session_id($id);
			}
			if($this->session_started()){
				return session_id();
			}else{
				return Session::$id;
			}
		}

		/**
		 * delete
		 *
		 * Delete a session variable
		 *
		 * @param string $name name of session to delete
		 * @return boolean
		 */
		public static function delete($name){
			if(Session::check($name)){
				$var = & $_SESSION;
				$path = explode('.', $name);
				foreach($path as $i => $key){
					if($i === count($path) - 1){
						unset($var[$key]);
					}else{
						if(!isset($var[$key])){
							return Session::check($name);
						}
						$var = & $var[$key];
					}
				}

				return !Session::check($name);
			}
			return false;
		}

		/**
		 * check
		 *
		 * Check to see if a session variable is set.
		 *
		 * @param string $name
		 * @return boolean
		 */
		public static function check($name){
			if(empty($name)){
				return false;
			}
			$check = Session::read($name);
			return isset($check);
		}

		/**
		 * reset
		 *
		 * Reset the current session, get a new ID
		 */
		public static function reset(){
			$old = session_id();
			if($old){
				if(isset($_COOKIE[session_name()])){
					set_cookie(session_name(), '', time() - 86400, '/');
				}

				session_regenerate_id(true);
				Session::$id = session_id();
			}
		}

		/**
		 * destroy
		 *
		 * Destroy the current session
		 *
		 * @return boolean
		 */
		public static function destroy(){
			if(Session::session_started()){
				if(ini_get('session.use_cookies')){
					$params = session_get_cookie_params();
					setcookie(session_name(), '', time() - 86400, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
				}
				
				session_destroy();
				
				Session::session_start();
				Session::reset();
				return true;
			}else{
				return false;
			}

		}

	}

?>
