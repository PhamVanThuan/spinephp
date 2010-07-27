<?php

	/**
	 * Session.php
	 *
	 * Handles all of the session methods. Is loaded into the Controller library,
	 * to allow for easy access to all the methods.
	 */

	class Session extends Object {

		protected $registry;
		protected $id;

		public function __construct(){
			if(!$this->session_started()){
				$this->session_start();
			}

			parent::__construct();
		}

		/**
		 * session_start
		 *
		 * Start the session
		 *
		 * @return boolean
		 */
		public function session_start(){
			if(getenv('HTTPS')){
				// Use secure cookies if available.
				ini_set('session.cookie_secure', 1);
			}

			// Since we don't allow Sessions to be stored in the Database...
			ini_set('session.serialize_handler', 'php');
			ini_set('session.cookie_path', '/');
			ini_set('session.save_path', TMP_PATH . 'sessions');
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
		 * Check to see if the session has been started
		 *
		 * @return boolean
		 */
		public function session_started(){
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
		public function write($name, $value = ''){
			if(empty($name)){
				trigger_error('Failed to set a session because no session name was provided.', E_USER_ERROR);
			}

			$var = & $_SESSION;
			$path = explode('.', $name);
			foreach($path as $i => $key){
				if($i === count($path) - 1){
					$var[$key] = $value;
				}else{
					if(!isset($var[$key])){
						$var[$key] = array();
					}else{
						// Check if the value is different.
						if($var[$key] != $value){
							$var[$key] = array();
						}
					}
					$var = & $var[$key];
				}
			}
		}

		/**
		 * read
		 *
		 * Return the value of a session.
		 *
		 * @param string $name name of session to get
		 * @return mixed
		 */
		public function read($name = null){
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
		public function id($id = null){
			if(!empty($id)){
				$this->id = $id;
				session_id($id);
			}
			if($this->session_started()){
				return session_id();
			}else{
				return $this->id;
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
		public function delete($name){
			if($this->check($name)){
				$var = & $_SESSION;
				$path = explode('.', $name);
				foreach($path as $i => $key){
					if($i === count($path) - 1){
						unset($var[$key]);
					}else{
						if(!isset($var[$key])){
							return $this->check($name);
						}
						$var = & $var[$key];
					}
				}

				return !$this->check($name);
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
		public function check($name){
			if(empty($name)){
				return false;
			}
			$check = $this->read($name);
			return isset($check);
		}

		/**
		 * reset
		 *
		 * Reset the current session, get a new ID
		 */
		public function reset(){
			$old = session_id();
			if($old){
				if(isset($_COOKIE[session_name()])){
					set_cookie(session_name(), '', time() - 86400, '/');
				}

				session_regenerate_id(true);
			}
		}

		/**
		 * destroy
		 *
		 * Destroy the current session
		 *
		 * @return boolean
		 */
		public function destroy(){
			if($this->session_started()){
				if(ini_get('session.use_cookies')){
					$params = session_get_cookie_params();
					setcookie(session_name(), '', time() - 86400, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
				}
				
				session_destroy();
				
				$this->session_start();
				$this->reset();
				return true;
			}else{
				return false;
			}

		}

	}

?>
