<?php
	if(!defined('APP_PATH')){ die('Unauthorized direct access to file.'); }
	
    /**
     * Errors.php
     *
     * This class generally handles all errors. It will log the message,
     * and if required, display the error page.
     * Does other error related tasks.
     * Reason for write_log() not being in here is that messages needed
     * to be logged prior to this classes declaration.
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

    class Errors {

		/**
		 * @var array $levels array of php error levels
		 */
		public static $levels = array(
			  E_ERROR			=>	'Error',
			  E_WARNING			=>	'Warning',
			  E_PARSE			=>	'Parsing Error',
			  E_NOTICE			=>	'Notice',
			  E_CORE_ERROR		=>	'Core Error',
			  E_CORE_WARNING	=>	'Core Warning',
			  E_COMPILE_ERROR	=>	'Compile Error',
			  E_COMPILE_WARNING	=>	'Compile Warning',
			  E_USER_ERROR		=>	'User Error',
			  E_USER_WARNING	=>	'User Warning',
			  E_USER_NOTICE		=>	'User Notice',
			  E_STRICT			=>	'Runtime Notice'
		);

		/**
		 * @var array $run_errors array of errors found during run time
		 */
		private static $run_errors = array();

		/**
		 * @var int $recursive number of times attempted to check on errors
		 */
		private static $recursive = 0;

		/**
		 * @var bool $ignore if errors are ignored
		 */
		private static $ignore;

		/**
		 * trigger
		 *
		 * User trigger error method, writes error to log and stores error
		 * in errors array.
		 *
		 * @param string $message
		 * @param const $code
		 * @param string $file
		 * @param int $line
		 */
		public static function trigger($message, $code = E_USER_ERROR, $file = null, $line = null){
			if(Errors::$ignore){
				return false;
			}
			
			// Write to the log file.
			write_log(Errors::$levels[$code], $message);

			// Append error.
			Errors::$run_errors[] = array(
				'code' => Errors::$levels[$code],
				'message' => $message,
				'file' => $file,
				'line' => $line
			);
		}

		/**
		 * user_trigger
		 *
		 * Used by the set_error_handler. Just hands to Errors::trigger
		 */
		public static function user_trigger($code, $message, $file = null, $line = null){
			if(Errors::$ignore){
				return false;
			}
			
			Errors::trigger($message, $code, $file, $line);
		}

		/**
		 * user_exception_trigger
		 *
		 * Used by set_exception_handler, to allow exceptions to be thrown and caught by this
		 * handler. Because exceptions halt the code, we must manually call the checkup and
		 * the template render method to display the error.
		 *
		 * @param object $exception
		 */
		public static function user_exception_trigger($exception){
			if(Errors::$ignore){
				return false;
			}
			
			if(!isset(Errors::$levels[$exception->getCode()])){
				$code = E_WARNING;
			}else{
				$code = $exception->getCode();
			}
			Errors::trigger($exception->getMessage(), $code, $exception->getFile(), $exception->getLine());
			Errors::checkup();
			Template::render(Template::$output);
		}

		/**
		 * checkup
		 *
		 * Perform the errors checkup, called when the system is ready to display everything.
		 * Will check for any errors, but only run through 3 times incase it gets set on a
		 * continuous loop.
		 */
		public static function checkup(){
			if(!empty(Errors::$run_errors)){
				// Running over and over..? Hope not. :/
				if(Errors::$recursive > 3){
					die('Fatal recurrsion error. Generally occurs when an error has been found but the error handler is unable to process the error.<br /><br />' . self::$run_errors[0]['message'] . ' (' . self::$run_errors[0]['file'] . ' on ' . self::$run_errors[0]['line'] . ')');
				}else{
					Errors::$recursive++;
				}

				// Unset all hooks.
				Hooks::unregister();

				list($folder, $template) = Template::get_template();
				
				// Is there an errors template?
				if(file_exists(BASE_PATH . DS . APP_PATH . DS .  'templates' . DS . 'errors ' . DS . 'html.php')){
					// Set the new template.
					Template::set_template('errors/html');

					// Set output to nothing.
					Template::$output = null;

					// Write to the template variables.
					Template::write('code', Errors::$run_errors[0]['code'], true);
					Template::write('message', Errors::$run_errors[0]['message'], true);

					if(!empty(Errors::$run_errors[0]['line']) && !empty(Errors::$run_errors[0]['file'])){
						Template::write('details', 'Found in ' . Errors::$run_errors[0]['file'] . ' on line ' . Errors::$run_errors[0]['line'], true);
					}else{
						Template::write('details', '', true);
					}

					Template::write('errnum', count(Errors::$run_errors), true);

					if(count(Errors::$run_errors) > 1){
						Template::write('remainder', array_slice(Errors::$run_errors, 1), true);
					}

					// Reset the errors, so that this isn't run again.
					Errors::$run_errors = array();

					// Reset the render.
					Template::prepare();
				}else{
					// No template file, just die with the error message.
					// Not the prettiest, but...
					die(Errors::$run_errors[0]['message'] . '<br />' . 'Found in ' . Errors::$run_errors[0]['file'] . ' on line ' . Errors::$run_errors[0]['line']);
				}
			}
		}

		public static function ignore(){
			Errors::$ignore = true;
		}

		public static function unignore(){
			Errors::$ignore = false;
		}
    }
?>