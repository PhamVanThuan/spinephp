<?php

    /**
     * driver.php
     *
     * MySQL DBDriver class. If no PDO is available, and MySQL is the selected driver
	 * this is the class that is used. It uses standard method names to allow easy
	 * creation of other database drivers.
	 *
	 * Other classes are:
	 * 
	 * DBDriverQuery - creates a new query object
	 * DBDriverPrepare - creates a new prepared statement object
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
    class DBDriver {

		/**
		 * @var mysql link identifier $connection mysql connection
		 */
		public $connection;

		/**
		 * @var resource $lastQuery last mysql query resource
		 */
		private $lastQuery;

		/**
		 * @var string $lastQueryString last mysql query string
		 */
		private $lastQueryString;

		/**
		 * @var string $preparedStatement mysql prepared statement string
		 */
		private $preparedStatement;

		/**
		 * @var int $affected_rows affected rows by query
		 */
		public $affected_rows;

		/**
		 * @var int $insert_id last mysql insert id
		 */
		public $insert_id;
		
		/**
		* connect
		*
		* Connect to the specified MySQL Database.
		*
		* @return boolean
		*/
		public function connect($host, $user, $pass, $dbname){
			if(!$this->connection = @mysql_connect($host, $user, $pass)){
				trigger_error('MySQL Configuration details failed to connect to MySQL. Please confirm your details in the configration. MySQL said: ' . mysql_error(), E_USER_ERROR);
			}else{
				if(!@mysql_select_db($dbname, $this->connection)){
					trigger_error('MySQL Configration details failed to connect to MySQL Database. Please confirm your details in the configuration. MySQL said: ' . mysql_error(), E_USER_ERROR);
				}else{
					return true;
				}
			}

			return false;
		}

		/**
		* query
		*
		* Peform a MySQL query.
		*
		* @param string $query mysql query to be run
		* @return mixed
		*/
		public function query($query){
			$this->lastQueryString = $query;

			// What kind of query are we running.
			if(preg_match('#^(SELECT|SHOW|DESCRIBE|EXPLAIN)#i', $query)){
				// We need to run the query and return an object of results.
				if($query = new DBDriverQuery($query, $this)){
					return $query;
				}else{
					return false;
				}
			}else{
				// Run the query and return true or false.
				if($this->lastQuery = @mysql_query($query, $this->connection) or trigger_error(mysql_error(), E_USER_ERROR)){
					$this->affected_rows = mysql_affected_rows();
					$this->insert_id = mysql_insert_id();
					
					return true;
				}else{
					return false;
				}
			}
		}

		/**
		 * prepare
		 *
		 * Creates a new prepared statement and returns the statement object.
		 *
		 * @param string $query
		 * @return object
		 */
		public function prepare($query){
			$this->preparedStatement = new DBDriverPrepare($query, $this);
			return $this->preparedStatement;
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

	/**
	 * DBDriverPrepare
	 *
	 * Prepared statements are created via this class.
	 */
	class DBDriverPrepare {

		/**
		 * @var string $__query mysql query string
		 */
		private $__query;

		/**
		 * @var array $__params bound mysql params
		 */
		private $__params;


		/**
		 * @var array $__result bound mysql results
		 */
		private $__result;

		/**
		 * @var mysql result resource $__resource
		 */
		private $__resource;

		/**
		 * @var object $__dbh database handler object
		 */
		private $__dbh;

		/**
		 * __construct
		 *
		 * Create the new statement object, set the query and the
		 * database handler object.
		 *
		 * @param string $query
		 * @param object $dbh
		 */
		public function __construct($query, $dbh){
			$this->__query = $query;
			$this->__dbh = $dbh;
		}

		/**
		 * bind_param
		 *
		 * Accepts unlimited amount of params to bind to the prepared statement.
		 * First param must be parameter types, and must match the count of
		 * remaining parameters.
		 *
		 * @return
		 */
		public function bind_param(){
			if(func_num_args() < 2){
				trigger_error('Invalid amount of arguments supplied to DBDriverPrepare::bind_param, must be more than 1 argument supplied.', E_USER_ERROR);
				return;
			}

			// Set the args and arg types
			$args = func_get_args();
			$types = array_shift($args);

			if(strlen($types) == count($args)){
				$sanitized = array();

				// Quickly go through and escape anything.
				for($i = 0; $i < count($args); ++$i){
					$args[$i] = mysql_real_escape_string($args[$i]);
				}

				// Type cast the variables and store in sanitized data.
				for($i = 0; $i < strlen($types); ++$i){
					switch($types[$i]){
						case 'i':
							$sanitized[] = (int) $args[$i];
						break;
						case 'd':
						case 'f':
							$sanitized[] = (float) $args[$i];
						break;
						case 'b':
						case 's':
							$sanitized[] = (string) $args[$i];
						break;
						default:
							$sanitized[] = (string) $args[$i];
						break;
					}
				}

				// Set the params property.
				$this->__params = $sanitized;
			}else{
				trigger_error('MySQL data types failed to match the amount of data provided.', E_USER_ERROR);
			}
			
		}

		/**
		 * bind_result
		 *
		 * Bind variables to the result of a fetch. Variables will be used
		 * in array keys.
		 *
		 * @return
		 */
		public function bind_result(){
			if(func_num_args() == 0){
				trigger_error('Invalid amount of arguments supplied to DBDriverPrepare::bind_result.', E_USER_ERROR);
				return;
			}
			$this->__result = array();
			foreach(func_get_args() as $arg){
				$this->__result[] = $arg;
			}
		}

		/**
		 * fetch
		 *
		 * Fetch and return data from database, returning an array
		 * containing keys set by bind_result.
		 *
		 * @return array
		 */
		public function fetch(){
			if(empty($this->__resource)){
				return false;
			}elseif(!$__fetch = @mysql_fetch_assoc($this->__resource)){
				return false;
			}

			if(empty($this->__result)){
				trigger_error('No result variables bound to data, use DBDriverPrepare::bind_result.', E_USER_ERROR);
				return;
			}elseif(count($this->__result) !== count($__fetch)){
				trigger_error('Bound variables do not match the number of returned fields for prepared statement.', E_USER_ERROR);
				return;
			}

			$__return = array();
			$__fetch = array_values($__fetch);
			for($i = 0; $i < count($__fetch); $i++){
				$__return[$this->__result[$i]] = $__fetch[$i];
			}
			return $__return;
		}

		/**
		 * execute
		 *
		 * Execute the prepared query after data has been bound.
		 */
		public function execute(){
			// Count how many placeholders there are.
			preg_match_all("#\=['\"]?(?<!\s)(\?)(?!\s)['\"]?#i", $this->__query, $matches);

			if(count($this->__params) == count($matches[0])){
				for($i = 0; $i < count($this->__params); ++$i){
					$this->__query = preg_replace("#\=['\"]?(?<!\s)(\?)(?!\s)['\"]?#i", '=\'' . $this->__params[$i] . '\'', $this->__query, 1);
				}

				$this->__resource = @mysql_query($this->__query, $this->__dbh->connection) or trigger_error(mysql_error(), E_USER_ERROR);
			}else{
				trigger_error('Unmatched placeholders or data supplied for MySQL execution.', E_USER_ERROR);
			}
		}

		/**
		 * close
		 *
		 * Closes the current prepared statement object.
		 */
		public function close(){
			unset($this);
		}
	}

	/**
	 * DBDriverQuery
	 *
	 * When a query is run and it is either SELECT, SHOW, DESCRIBE or EXPLAIN
	 * a new object will be created where you can see the results of that query.
	 */
	class DBDriverQuery {

		/**
		 * @var mysql result resource $__query
		 */
		private $__query;

		/**
		 * @var object $__dbh database handler object
		 */
		private $__dbh;

		/**
		 * @var int $num_rows number of rows in result set
		 */
		public $num_rows;

		/**
		 * @var array $lengths length of fields in result set
		 */
		public $lengths;

		/**
		 * @var int $field_count number of fields in result set
		 */
		public $field_count;

		/**
		 * __construct
		 *
		 * Create a new query object.
		 *
		 * @param string $query
		 * @param object $dbh
		 * @return bool
		 */
		public function __construct($query, $dbh){
			if(!$query = @mysql_query($query, $dbh->connection)){
				return false;
			}

			$this->__query = $query;
			$this->__dbh = $dbh;

			// Set a few of the properties.
			$this->num_rows = mysql_num_rows($this->__query);
			$this->lengths = mysql_fetch_lengths($this->__query);
			$this->field_count = mysql_num_fields($this->__query);
		}

		/**
		 * fetch_all
		 *
		 * Returns all result rows as an associative array
		 *
		 * @return array
		 */
		public function fetch_all(){
			$return = array();
			while($row = $this->fetch_assoc()){
				$return[] = $row;
			}
			return $return;
		}

		/**
		 * fetch_assoc
		 *
		 * Returns a result row as an associative array
		 *
		 * @return array
		 */
		public function fetch_assoc(){
			if($return = mysql_fetch_assoc($this->__query)){
				return $return;
			}
			return false;
		}

		/**
		 * fetch_row
		 *
		 * Returns a result row as an enumerated array
		 *
		 * @return array
		 */
		public function fetch_row(){
			if($return = mysql_fetch_row($this->__query)){
				return $return;
			}
			return false;
		}

		/**
		 * fetch_assoc
		 *
		 * Returns a result row as an associative array
		 *
		 * @return array
		 */
		public function fetch_array($type = MYSQL_BOTH){
			if($return = mysql_fetch_array($this->__query)){
				return $return;
			}
			return false;
		}

		/**
		 * fetch_object
		 *
		 * Returns the current row of a result set as an object
		 *
		 * @return object
		 */
		public function fetch_object(){
			if($return = mysql_fetch_object($this->__query)){
				return $return;
			}
			return false;
		}

		/**
		 * fetch_field
		 *
		 * Returns the next field in the result set
		 *
		 * @return object
		 */
		public function fetch_field(){
			return mysql_fetch_field($this->__query);
		}

		/**
		 * fetch_fields
		 *
		 * Returns an array of objects representing the fields in a result set.
		 *
		 * @return array
		 */
		public function fetch_fields(){
			$return = array();
			while($row = $this->fetch_field()){
				$return[] = $row;
			}
			return $return;
		}

		/**
		 * data_seek
		 *
		 * Sets the result pointer to a specified offset.
		 */
		public function data_seek($offset){
			mysql_field_seek($this->__query, $offset);
		}

		/**
		 * free
		 *
		 * Free result memory.
		 *
		 * @return bool
		 */
		public function free(){
			return mysql_free_result($this->__query);
		}
		
	}

?>