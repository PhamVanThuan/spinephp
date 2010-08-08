<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
    /**
     * Object.php
     *
     * The general base class. This serves a few purposes, one being I don't want
     * to create a new abstract class for every single thing, and two.. I don't know.
     * This is, as I said, a general abstract class. Basically implements the Registry,
     * and that's about it. Most libraries extend it.
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
	
    abstract class Object {

		/**
		 * request
		 *
		 * Loads up a controller and fires a method, uses the routers dispatcher.
		 *
		 * @param string $uri
		 * @return boolen
		 */
		public function request($uri){
			if(empty($uri)){
				return false;
			}

			// Check for params
			if(strpos($uri, ',') !== false){
				list($uri, $params) = explode(',', $uri);

				if(!empty($params)){
					$_params = array();
					$params = array_clean(explode('/', $params));
					foreach($params as $val){
						$tmp = explode(':', $val);
						$_params[$tmp[0]] = $tmp[1];
					}
					$params = $_params;
				}
			}

			$uri = array_clean(explode('/', $uri));
			if(count($uri) < 2){
				// There is no method, fail.
				return false;
			}

			// Call up the dispatcher
			$object = Request::instance('request', implode('/', $uri));
			$object = $object->dispatch(true);

			// Because the request handler makes sure the method exists, no need to check.
			$object->__params['request'] = true;

			// Set the other params that we have.
			if(isset($params) && is_array($params)){
				foreach($params as $key => $val){
					$object->__params[$key] = $val;
				}
			}

			return $object->{$uri[1]}();

		}

    }

?>