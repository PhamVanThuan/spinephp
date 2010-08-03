<?php

    /**
     * Database.php
     *
     * The MySQL Database Library. Allows connection to MySQL, as well as all other
     * MySQL functions. Simplifies a lot of the methods to allow for easier calling.
	 * Copyright (c) 2010, Jason Lewis, Spine PHP Team (http://www.spinephp.org)
	 *
	 * Licensed under the BSD License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis, Spine PHP Team
	 * @link		<http://www.spinephp.org>
	 * @license		BSD License <http://www.opensource.org/licenses/bsd-license.php>
     */
    class Database extends Object {

		public $connection;
		public $info = array(
			'insert_id' 	=> 0,
			'affected_rows' => 0
		);
		public $queries = 0;
		public $last_query;
		public $last_query_string;
		public $prepared_statement;
		public $prepared_statement_variables;
		public $last_fetch_query;
		public $last_fetch_data;

		/**
		* connect
		*
		* Connect to the specified MySQL Database.
		* If MySQLi is enabled and available, connect to MySQLi as well, allows for better performance.
		*
		* @return boolean
		*/
		public function connect(){
			if(!$this->connection = @mysql_connect(
				Config::read('Database.host'),
				Config::read('Database.username'),
				Config::read('Database.password'))){
				trigger_error('MySQL Configuration details failed to connect to MySQl. Please confirm your details in the configration.', E_USER_ERROR);
			}else{
				if(!@mysql_select_db(Config::read('Database.dbname'), $this->connection)){
					trigger_error('MySQL Configration details failed to connect to MySQL Database. Please confirm your details in the configuration.', E_USER_ERROR);
				}else{
					return true;
				}
			}
		}

		/**
		* query
		*
		* Run a MySQL query. This also adds a bit to the info property, like mysql_insert_id() and
		* mysql_affected_rows()
		*
		* @param string $query mysql query to be run
		* @return mysql resource
		*/
		public function query($query){
			$this->last_query_string = $query;
			$this->last_query = @mysql_query($query, $this->connection) or trigger_error(mysql_error(), E_USER_ERROR);

			// Set the information.
			$this->info = array(
				'insert_id' 	=> mysql_insert_id(),
				'affected_rows' => mysql_affected_rows()
			);

			return $this->last_query;
		}

		/**
		 * prepare
		 *
		 * Prepare a MySQL query string, allows for proper typecasting etc.
		 * Reduces MySQL Injection, attempts to mimic MySQLi's prepare statement.
		 *
		 * @param string $query the query string to prepare
		 * @param array $data an array of data types and data to bind to the query
		 * @return void
		 */
		public function prepare($query, $data = null){
			if(!empty($data)){
				$this->bind($data);
			}

			$this->prepared_statement = $query;
		}

		/**
		 * bind
		 *
		 * Bind data to the last prepared statement if it was not already done
		 * in the prepare call.
		 *
		 * @param array $data data types and data to bind to statment
		 */
		public function bind($data){
			// First let's make sure that provided the same amount of types as they did variables.
			if(strlen($data[0]) == count($data) - 1){
				$sanitized = array();

				// Type cast the variables and store in the sanitized data.
				for($i = 0; $i < strlen($data[0]); ++$i){
					switch($data[0][$i]){
						case 'i':
							$sanitized[] = (int) $data[$i + 1];
						break;
						case 'd':
						case 'f':
							$sanitized[] = (float) $data[$i + 1];
						break;
						case 'b':
						case 's':
							$sanitized[] = (string) $data[$i + 1];
						break;
						default:
							$sanitized[] = (string) $data[$i + 1];
						break;
					}
				}

				// Quickly go through and escape anything.
				for($i = 0; $i < count($sanitized); ++$i){
					$sanitized[$i] = mysql_real_escape_string($sanitized[$i]);
				}

				// Set the prepared statement variables.
				$this->prepared_statement_variables = $sanitized;
			}else{
				trigger_error('MySQL data types failed to match the amount of data provided.', E_USER_ERROR);
			}
		}

		/**
		 * execute
		 *
		 * Execute a prepared query, if no data was bound will die.
		 *
		 * @return void
		 */
		public function execute(){
			if(empty($this->prepared_statement)){
				trigger_error('Failed to find any variables or a statement to execute.', E_USER_ERROR);
			}else{
				// Count how many placeholders there are.
				$tmp = $this->prepared_statement;
				preg_match_all("#\=\s*\'?\s*\?{1}\s*\'?\s*#is", $tmp, $matches);

				if(count($this->prepared_statement_variables) == count($matches[0])){
					for($i = 0; $i < count($this->prepared_statement_variables); ++$i){
						$tmp = preg_replace("#(\s*\=\s*\'?\s*)(\?{1})(\s*\'?)#", '=\'' . $this->prepared_statement_variables[$i] . '\'', $tmp, 1);
					}

					$this->query($tmp);
				}else{
					trigger_error('Incorrect amount of MySQL placeholders for data types in prepared statement.', E_USER_ERROR);
				}
			}
		}

		/**
		* fetch
		*
		* Runs a MySQl Fetch Assoc, because we like associative arrays. If $resource isn't passed, use the last
		* query information.
		*
		* @param $resource	mysql resource		the mysql_resource to fetch the data from
		* @return array
		*/
		public function fetch(){
			$this->last_fetch_data = mysql_fetch_assoc($this->last_query);

			// Did they supply any arguments?
			if(func_num_args() > 0 && $this->last_fetch_data){
				// If they did, it means they want to return the data with their
				// own variable names. Make sure there are the correct number.
				if(func_num_args() != count($this->last_fetch_data)){
					trigger_error('Supplied number of arguments for fetch did not match number of returned data keys.', E_USER_ERROR);
				}else{
					$tmp = array();
					$args = func_get_args();
					$i = 0;
					foreach($this->last_fetch_data as $key => $value){
						$tmp[$args[$i]] = $value;
						++$i;
					}
					return $tmp;
				}
			}else{
				return $this->last_fetch_data;
			}
		}

		/**
		* fetch_all
		*
		* Similar to fetch() except this removes the need to use a loop.
		*
		* @param $resource	mysql resource the mysql_resource to fetch the data from
		* @return array
		*/
		public function fetch_all(){
			$return = array();
			
			while($this->last_fetch_data = mysql_fetch_assoc($this->last_query)){
				// Did they supply any arguments?
				if(func_num_args() > 0 && $this->last_fetch_data){
					// If they did, it means they want to return the data with their
					// own variable names. Make sure there are the correct number.
					if(func_num_args() != count($this->last_fetch_data)){
						trigger_error('Supplied number of arguments for fetch did not match number of returned data keys.', E_USER_ERROR);
					}else{
						$tmp = array();
						$args = func_get_args();

						$i = 0;
						foreach($this->last_fetch_data as $key => $value){
							$tmp[$args[$i]] = $value;
							++$i;
						}
						$return[] = $tmp;
					}
				}else{
					$return[] = $this->last_fetch_data;
				}
			}

			return $return;
		}

		/**
		* num_rows
		*
		* Return the number of rows from a specified query, if $resource isn't passed use the last query.
		*
		* @param mysql resource $resource the mysql_resrouce to fetch the number of rows from
		* @return int
		*/
		public function num_rows($resource = ''){
			$return = @mysql_num_rows(empty($resource) ? $this->last_query : $resource) or trigger_error(mysql_error(), E_USER_ERROR);
			return $return;
		}

		/**
		* result
		*
		* Runs a MySQL Result, can be useful if you only want to get 1 row out or something.
		*
		* @param mysql resource $resource the mysql_resource to fetch the data from
		* @param int $row the row number to fetch from
		* @param string $field the field to select, if none was supplied in the query
		* @return string
		*/
		public function result($resource = '', $row = 0, $field = ''){
			if($field == ''){
				$return = @mysql_result(empty($resource) ? $this->last_query : $resource, $row) or trigger_error(mysql_error(), E_USER_ERROR);
			}else{
				$return = @mysql_result(empty($resource) ? $this->last_query : $resource, $row, $field) or trigger_error(mysql_error(), E_USER_ERROR);
			}
			return $return;
		}

		/**
		* free_result
		*
		* Runs the MySQL Free Result, that's it.
		*/
		public function free_result(){
			@mysql_free_result($this->last_query);
		}

		/**
		* close
		*
		* Close any current connections.
		*/
		public function close(){
			@mysql_close($this->connection);
		}

    }

?>