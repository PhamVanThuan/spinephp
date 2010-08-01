<?php

    /**
     * Errors.php
     *
     * This class generally handles all errors. It will log the message,
     * and if required, display the error page.
     * Does other error related tasks.
     * Reason for write_log() not being in here is that messages needed
     * to be logged prior to this classes declaration.
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

    class Errors {

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

		public static $run_errors = array();
		public static $recursive = 0;

		public static function trigger($message, $code = E_USER_ERROR, $file = null, $line = null){
			// Write to the log file.
			write_log(self::$levels[$code], $message);

			// Append error.
			self::$run_errors[] = array(
				'code' => self::$levels[$code],
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
			self::trigger($message, $code, $file, $line);
		}

		/**
		 * checkup
		 *
		 * Perform the errors checkup, called when the system is ready to display everything.
		 */
		public static function checkup(){
			if(!empty(self::$run_errors)){

				// Running over and over..? Hope not. :/
				if(self::$recursive > 3){
					die('Fatal recurrsion error. Generally occurs when an error has been found but the error handler is unable to process the error.<br /><br />' . self::$run_errors[0]['message'] . ' (' . self::$run_errors[0]['file'] . ' on ' . self::$run_errors[0]['line'] . ')');
				}else{
					self::$recursive++;
				}

				list($folder, $template) = Template::get_template();
				
				// Is there an errors template?
				if(file_exists(APP_PATH . 'templates/' . $folder . '/error.php')){
					// Set the new template.
					Template::set_template('error');

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
    }
?>