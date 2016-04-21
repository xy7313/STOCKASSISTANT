<?php

// written by: Mohammed Latif
// tested by: Mohammed Latif
// debugged by: Mohammed Latif

include_once 'dbConnection.php';
include_once 'query.php';

class accountHandler {
	
	private $dbConnection;
	private $query;
	
	public function __construct() {
		$this->dbConnection = new dbConnection();
		$this->query = new query();
	}
	
	public function createAccount($firstName, $lastName, $email, $password, $phone, $carrier) {
		//check if email already exists
		$this->dbConnection->connect();
		$this->dbConnection->prepare($this->query->getUserID());
		$this->dbConnection->bind(1, $email);
		$exists = $this->dbConnection->singleData();
		
		//if exists, return
		if ($exists) 
			return false;
			
		//otherwise create account
		else {
	
			$hash = password_hash($password, PASSWORD_BCRYPT);
			$this->dbConnection->prepare($this->query->insertUser());
			$this->dbConnection->bind(1, $firstName);
			$this->dbConnection->bind(2, $lastName);
			$this->dbConnection->bind(3, $email);
			$this->dbConnection->bind(4, $hash);
			$this->dbConnection->bind(5, $phone);
			$this->dbConnection->bind(6, $carrier);
			$this->dbConnection->execute();
			$this->dbConnection->disconnect();
			
			return true;
		}
	}
	
	public function authenticate($email, $password) {
		//check if username and password match
		$this->dbConnection->connect();
		$this->dbConnection->prepare($this->query->getPassword());
		$this->dbConnection->bind(1, $email);
		$hash = $this->dbConnection->singleData();
		
		if (password_verify($password, $hash)) {
			$this->dbConnection->prepare($this->query->getUserInfo());
			$this->dbConnection->bind(1, $email);
			$results = $this->dbConnection->resultSet();
			$this->dbConnection->disconnect();
			
			return $results[0];
		}
		else {
			$this->dbConnection->disconnect();
			return false;
		}
	}
}

?>