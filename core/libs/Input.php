<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Input.php
	 *
	 * Filters data input to prevent things such as XSS.
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

	class Input extends Object {

		/**
		 * sanitize_globals
		 *
		 * Unsets any $_GET global data as it is not used.
		 * Filters both POST and COOKIE data.
		 */
		public static function sanitize_globals(){
			$_GET = array(); // unset any GET data

			$_POST = Input::clean($_POST);
			$_COOKIe = Input::clean($_COOKIE);
		}

		/**
		 * clean
		 *
		 * Clean an array or string, replacing non-standard newlines.
		 *
		 * @param mixed $source
		 * @return string
		 */
		public static function clean($source){
			if(is_array($source)){
				$tmp = array();
				foreach($source as $key => $value){
					$tmp[Input::clean_array_key($key)] = Input::clean($value);
				}
				return $tmp;
			}

			// Stripslashes if magic quotes is enabled.
			if(get_magic_quotes_gpc()){
				$source = stripslashes($source);
			}

			// Replace any non-standard new lines with \n
			if(strpos($source, "\r") !== false){
				$source = str_replace(array("\r\n","\r"), "\n", $source);
			}

			// Return the cleaned string.
			return $source;
		}

		/**
		 * clean_array_key
		 *
		 * Takes an array key and cleans it, allowing only alphanumeric
		 * characters plus a few extras.
		 *
		 * @param string $source
		 * @return string
		 */
		public static function clean_array_key($source){
			if(!preg_match('#^[A-Za-z0-9:/\-_]+$#i', $source)){
				die('Could not process system as disallowed characters found in input data keys.');
			}
			return $source;
		}

		/**
		 * chars
		 *
		 * Convert HTML Special Characters.
		 *
		 * @param string $value
		 * @return string
		 */
		public static function chars($value){
			return htmlspecialchars((string) $value, ENT_QUOTES);
		}

		/* +---------------------------------------------------------------------------+
		 * | http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php |
		 * +---------------------------------------------------------------------------+
		 * | Copyright (c) 2001-2008 Liip AG                                           |
		 * +---------------------------------------------------------------------------+
		 * | Licensed under the Apache License, Version 2.0 (the "License");           |
		 * | you may not use this file except in compliance with the License.          |
		 * | You may obtain a copy of the License at                                   |
		 * | http://www.apache.org/licenses/LICENSE-2.0                                |
		 * | Unless required by applicable law or agreed to in writing, software       |
		 * | distributed under the License is distributed on an "AS IS" BASIS,         |
		 * | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or           |
		 * | implied. See the License for the specific language governing              |
		 * | permissions and limitations under the License.                            |
		 * +---------------------------------------------------------------------------+
		 * | Author: Christian Stocker <christian.stocker@liip.ch>                     |
		 * +---------------------------------------------------------------------------+
		 */
		public static function clean_xss($string){
			if(get_magic_quotes_gpc()){
				$string = stripslashes($string);
			}

			$string = str_replace(array("&amp;","&lt;","&gt;"), array("&amp;amp;","&amp;lt;","&amp;gt;"), $string);

			// fix &entitiy\n;
			$string = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"$1;", $string);
			$string = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"$1$2;", $string);
			$string = html_entity_decode($string, ENT_COMPAT, "UTF-8");

			// remove any attribute starting with "on" or xmlns
			$string = preg_replace('#(<[^>]+[\x00-\x20\"\'\/])(on|xmlns)[^>]*>#iUu', "$1>", $string);

			// remove javascript: and vbscript: protocol
			$string = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2nojavascript...', $string);
			$string = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2novbscript...', $string);
			$string = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*-moz-binding[\x00-\x20]*:#Uu', '$1=$2nomozbinding...', $string);
			$string = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*data[\x00-\x20]*:#Uu', '$1=$2nodata...', $string);

			// remove any style attributes, IE allows too much stupid things in them, eg.
			// <span style="width: expression(alert('Ping!'));"></span>
			// and in general you really don't want style declarations in your UGC

			$string = preg_replace('#(<[^>]+[\x00-\x20\"\'\/])style[^>]*>#iUu', "$1>", $string);

			// remove namespaced elements (we do not need them...)
			$string = preg_replace('#</*\w+:\w[^>]*>#i',"",$string);

			// remove really unwanted tags
			do {
				$oldstring = $string;
				$string = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i',"",$string);
			} while($oldstring != $string);
			
			return $string;
		}
	}

?>