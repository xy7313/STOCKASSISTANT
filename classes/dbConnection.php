<?php

// written by: Peter Zhang
// tested by: Peter Zhang
// debugged by: Peter Zhang
	
	class dbConnection {
		private $connection; //database connection
		private $statement; //query preparede statement
		
		//necessary credentials
		private $host = "localhost";
		private $user = "root";
		private $password = "";
		private $db = "database";
				
		//connect to database
		public function connect() {
			$this->connection = new PDO('mysql:host='.$this->host.';dbname='.$this->db.';charset=utf8', $this->user, $this->password);
			//turn on errors and exceptions
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
		
		//prepare a satement (prevents SQL injection) where $query is the sql query
		public function prepare($query) {
			$this->statement = $this->connection->prepare($query);
		}
		
		//bind values to prepared statement where:
		//$param is a parameter of the statement
		//$value is the corresponding value
		//$type is the data type of the value
		public function bind($param, $value, $type = null) {
			//set type
			if (is_null($type)) {
				switch (true) {
					case is_int($value):
						$type = PDO::PARAM_INT; //integer
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL; //bool
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL; //null
						break;
					default:
						$type = PDO::PARAM_STR; //string
				}
			}
			
			//bind statement
			$this->statement->bindValue($param, $value, $type);
		}
		
		//execute query
		public function execute(){
			try {
				return $this->statement->execute();
			}
			catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); //user friendly error message
			}
		}
		
		//get results
		public function resultset(){
			$this->execute();
			return $this->statement->fetchAll(PDO::FETCH_ASSOC);
		}
		
		//get single row
		public function single(){
			$this->execute();
			return $this->statement->fetch(PDO::FETCH_ASSOC);
		}
		
		//get single data
		public function singleData(){
			$this->execute();
			$result = $this->statement->fetch(PDO::FETCH_NUM);
			return $result[0];
		}
		
		//get last insert ID
		public function lastInsertId(){
			return $this->connection->lastInsertId();
		}
		
		//get row count
		public function rowCount(){
			return $this->statement->rowCount();
		}
		
		//disconnect from database
		public function disconnect() {
			$this->connection = null;
		}
	}
	?>