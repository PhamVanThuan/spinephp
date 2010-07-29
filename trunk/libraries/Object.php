<?php

    /**
     * Object.php
     *
     * The general base class. This serves a few purposes, one being I don't want
     * to create a new abstract class for every single thing, and two.. I don't know.
     * This is, as I said, a general abstract class. Basically implements the Registry,
     * and that's about it. Most libraries extend it.
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
	
    abstract class Object {

		protected $spine;

		public function __construct(){
			$this->spine =& Spine::get_instance();
		}

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
			$object = $this->spine->Router->dispatch($uri[0], false, null, false, true);

			// Do a check on the method
			if(method_exists($object, $uri[1])){
				$object->params['request'] = true;

				// Set the other params
				if(isset($params) && is_array($params)){
					foreach($params as $key => $val){
						$object->params[$key] = $val;
					}
				}

				return $object->{$uri[1]}();
			}else{
				return false;
			}

		}

    }

?>