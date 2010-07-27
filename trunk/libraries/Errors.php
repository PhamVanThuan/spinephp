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

		public static function trigger($message, $code, $file = null, $line = null){
			// Write to the log file.
			write_log(self::$levels[$code], $message);

			// Append error.
			self::$run_errors[] = array(
				'code' => $code,
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
					die('Fatal recurrsion error. Generally occurs when an error has been found but the error handler is unable to process the error.<br /><br />' . self::$run_errors[0]['message']);
				}else{
					self::$recursive++;
				}

				// Great, found some errors along the way.
				$registry = Registry::get_instance();
				$tpl = $registry->Template->get_template();
				
				// Is there an errors template?
				if(file_exists(APP_PATH . 'templates/' . $tpl['folder'] . '/error.php')){
					// Set the new template.
					$registry->Template->set_template('error');

					// Write to the template variables.
					$registry->Template->write('code', self::$levels[self::$run_errors[0]['code']], true);
					$registry->Template->write('message', self::$run_errors[0]['message'], true);
					$registry->Template->write('errnum', count(self::$run_errors), true);

					// Reset the errors, so that this isn't run again.
					self::$run_errors = array();

					// Reset the render.
					$registry->Template->prepare_render();
				}else{
					// No template file, just die with the error message.
					// Not the prettiest, but...
					die(self::$run_errors[0]['message']);
				}
			}
		}

		/**
		* display_error
		*
		* Displays a nice error page, also sends the information to the
		* write_log function in Common.php. This method uses the :: operator,
		* mainly because it is set as the error handler, and you can't pass
		* a method to it because it takes a function string.
		*
		* @param $code int the error code
		* @param $message string the error message to display
		* @param $file string the file the error occurred in
		* @param $line int the line the error occurred on
		* @param $context string
		*/
		public function display_error($code, $message, $file = '', $line = '', $context = ''){
			write_log(self::$levels[$code], $message);

			if($file != ''
				&& $line != ''){
				$message = $message . (substr($message, -1) == '.' ? '' : '.') . '<br />Error found in <strong>' . $file . '</strong> on line <strong>' . $line . '</strong>.';
			}

			$output = "<style type=\"text/css\">
	  body {
		font-family: Tahoma;
		font-size: 10pt;
		color: #010101;
	  }

	  h1 {
		font-size: 1.4em;
		font-weight: bold;
		display: block;
		margin: 0 0 4px 0;
	  }

	  div.message {
		border: 1px solid #CCCC66;
		background-color: #FFFFBB;
		padding: 4px;
		width: 600px;
		margin: 0 auto;
	  }

	  ol {
		margin: 2px;
	  }
	</style>

	<div class=\"message\">
	  <h1>PHP:  " . self::$levels[$code] . "</h1>
	  <p>
		  " . nl2br($message, true) . "
	  </p>
	</div>";
			die($output);
		}

    }
?>