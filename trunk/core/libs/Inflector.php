<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
	/**
	 * Inflector.php
	 *
	 * Pluralize and singularize english words. Used throughout Spine to comply with
	 * Spine's naming conventions.
	 *
	 * Original pluralize and singularize code Copyright 2007 Kuwamoto
	 * <http://kuwamoto.org/2007/12/17/improved-pluralizing-in-php-actionscript-and-ror/>
	 * Licensed under the MIT License.
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

	class Inflector {

		/**
		 * @var array $plural array of pluralized strings
		 */
		public static $plural = array(
			'/(quiz)$/i'               => "\\1zes",
			'/^(ox)$/i'                => "\\1en",
			'/([m|l])ouse$/i'          => "\\1ice",
			'/(matr|vert|ind)ix|ex$/i' => "\\1ices",
			'/(x|ch|ss|sh)$/i'         => "\\1es",
			'/([^aeiouy]|qu)y$/i'      => "\\1ies",
			'/(hive)$/i'               => "\\1s",
			'/(?:([^f])fe|([lr])f)$/i' => "\\1\\2ves",
			'/(shea|lea|loa|thie)f$/i' => "\\1ves",
			'/sis$/i'                  => "ses",
			'/([ti])um$/i'             => "\\1a",
			'/(tomat|potat|ech|her|vet)o$/i'=> "\\1oes",
			'/(bu)s$/i'                => "\\1ses",
			'/(alias)$/i'              => "\\1es",
			'/(octop)us$/i'            => "\\1i",
			'/(ax|test)is$/i'          => "\\1es",
			'/(us)$/i'                 => "\\1es",
			'/s$/i'                    => "s",
			'/$/'                      => "s"
		);

		/**
		 * @var array $singular array of singularized strings
		 */
		public static $singular = array(
			'/(quiz)zes$/i'             => "\\1",
			'/(matr)ices$/i'            => "\\1ix",
			'/(vert|ind)ices$/i'        => "\\1ex",
			'/^(ox)en$/i'               => "\\1",
			'/(alias)es$/i'             => "\\1",
			'/(octop|vir)i$/i'          => "\\1us",
			'/(cris|ax|test)es$/i'      => "\\1is",
			'/(shoe)s$/i'               => "\\1",
			'/(o)es$/i'                 => "\\1",
			'/(bus)es$/i'               => "\\1",
			'/([m|l])ice$/i'            => "\\1ouse",
			'/(x|ch|ss|sh)es$/i'        => "\\1",
			'/(m)ovies$/i'              => "\\1ovie",
			'/(s)eries$/i'              => "\\1eries",
			'/([^aeiouy]|qu)ies$/i'     => "\\1y",
			'/([lr])ves$/i'             => "\\1f",
			'/(tive)s$/i'               => "\\1",
			'/(hive)s$/i'               => "\\1",
			'/(li|wi|kni)ves$/i'        => "\\1fe",
			'/(shea|loa|lea|thie)ves$/i'=> "\\1f",
			'/(^analy)ses$/i'           => "\\1sis",
			'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "\\1\\2sis",
			'/([ti])a$/i'               => "\\1um",
			'/(n)ews$/i'                => "\\1ews",
			'/(h|bl)ouses$/i'           => "\\1ouse",
			'/(corpse)s$/i'             => "\\1",
			'/(us)es$/i'                => "\\1",
			'/s$/i'                     => ""
		);

		/**
		 * @var array $irregular array of irregular strings
		 */
		public static $irregular = array(
			'move'   => 'moves',
			'foot'   => 'feet',
			'goose'  => 'geese',
			'sex'    => 'sexes',
			'child'  => 'children',
			'man'    => 'men',
			'tooth'  => 'teeth',
			'person' => 'people'
		);

		/**
		 * @var array $uncountable array of uncountable strings
		 */
		public static $uncountable = array(
			'sheep',
			'fish',
			'deer',
			'series',
			'species',
			'money',
			'rice',
			'information',
			'equipment'
		);

		/**
		 * @var array $cached array of cached words and their inflected counterparts
		 */
		public static $cached = array();

		/**
		 * pluralize
		 *
		 * Turn a word into its plural form.
		 *
		 * @param string $string
		 * @return string
		 */
		public static function pluralize($string){
			// Save some time in the case that singular and plural are the same
			if(in_array(strtolower($string), Inflector::$uncountable)){
				return $string;
			}

			// check for irregular singular forms
			foreach(Inflector::$irregular as $pattern => $result){
				$pattern = '/' . $pattern . '$/i';

				if(preg_match($pattern, $string)){
					return preg_replace($pattern, $result, $string);
				}
			}

			// check for matches using regular expressions
			foreach(Inflector::$plural as $pattern => $result){
				if(preg_match($pattern, $string)){
					return preg_replace($pattern, $result, $string);
				}
			}

			return $string;
		}

		/**
		 * singularize
		 *
		 * Turn a word into its singular form.
		 *
		 * @param string $string
		 * @return string
		 */
		public static function singularize($string){
			// save some time in the case that singular and plural are the same
			if(in_array(strtolower($string), Inflector::$uncountable)){
				return $string;
			}

			// check for irregular plural forms
			foreach(Inflector::$irregular as $result => $pattern){
				$pattern = '/' . $pattern . '$/i';

				if(preg_match($pattern, $string)){
					return preg_replace($pattern, $result, $string);
				}
			}

			// check for matches using regular expressions
			foreach(Inflector::$singular as $pattern => $result){
				if(preg_match($pattern, $string)){
					return preg_replace($pattern, $result, $string);
				}
			}

			return $string;
		}

		/**
		 * is_cached
		 *
		 * Returns the cached transformed string if an identical string
		 * has already been transformed.
		 *
		 * @param string $form
		 * @param string $string
		 * @return string
		 */
		public static function is_cached($form, $string){
			if(isset(Inflector::$cached[$form][$string])){
				return Inflector::$cached[$form][$string];
			}
			return false;
		}

		/**
		 * cache
		 *
		 * Cache a copy of a transformed string.
		 *
		 * @param string $form
		 * @param string $string
		 * @param string $transformed
		 */
		public static function cache($form, $string, $transformed){
			Inflector::$cached[$form][$string] = $transformed;
		}

		/**
		 * camelize
		 *
		 * Transform this_is_a_string to ThisIsAString
		 *
		 * @param string $string
		 * @return string
		 */
		public static function camelize($string){
			if(!$transformed = Inflector::is_cached(__FUNCTION__, $string)){
				$transformed = str_replace(' ', '', Inflector::humanize($string));
				Inflector::cache(__FUNCTION__, $string, $transformed);
			}

			return $transformed;
		}

		/**
		 * humanize
		 *
		 * Transform this_is_a_string to This Is A String
		 *
		 * @param <type> $string
		 * @return <type>
		 */
		public static function humanize($string){
			if(!$transformed = Inflector::is_cached(__FUNCTION__, $string)){
				$transformed = ucwords(str_replace('_', ' ', $string));
				Inflector::cache(__FUNCTION__, $string, $transformed);
			}

			return $transformed;
		}

		/**
		 * underscore
		 *
		 * Transform ThisIsAString to this_is_a_string
		 *
		 * @param <type> $string
		 * @return <type>
		 */
		public static function underscore($string){
			if(!$transformed = Inflector::is_cached(__FUNCTION__, $string)){
				$transformed = strtolower(preg_replace('#(?<=\w)([A-Z])#', '_\\1', $string));
				Inflector::cache(__FUNCTION__, $string, $transformed);
			}

			return $transformed;
		}

		/**
		 * classname
		 *
		 * Transforms /path/to/my-controller to MyController
		 *
		 * @param string $string
		 * @return string
		 */
		public static function classname($string){
			if(!$transformed = Inflector::is_cached(__FUNCTION__, $string)){
				$transformed = Inflector::camelize(str_replace('-', '_', array_pop(explode('/', $string))));
				Inflector::cache(__FUNCTION__, $string, $transformed);
			}

			return $transformed;
		}

		/**
		 * methodname
		 *
		 * Transforms /path/to/my-controller to my_controller
		 *
		 * @param string $string
		 * @return string
		 */
		public static function methodname($string){
			if(!$transformed = Inflector::is_cached(__FUNCTION__, $string)){
				$transformed = str_replace('-', '_', array_pop(explode('/', $string)));
				Inflector::cache(__FUNCTION__, $string, $transformed);
			}

			return $transformed;
		}

		/**
		 * filename
		 *
		 * Transforms /path/to/my-controller to /path/to/my_controller
		 *
		 * @param string $string
		 * @return string
		 */
		public static function filename($string){
			if(!$transformed = Inflector::is_cached(__FUNCTION__, $string)){
				$transformed = str_replace('-', '_', $string);
				Inflector::cache(__FUNCTION__, $string, $transformed);
			}

			return $transformed;
		}

	}

?>
