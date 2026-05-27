<?php
class DBController {
	private $host;
	private $user;
	private $password;
	private $database;
	private $conn;

	function __construct() {
		$this->host = getenv('DB_HOST') ?: 'localhost';
		$this->user = getenv('DB_USER') ?: 'root';
		$this->password = getenv('DB_PASS') ?: '';
		$this->database = getenv('DB_NAME') ?: 'firsthon_lhp';
		$this->conn = $this->connectDB();
	}
	
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	function runQuery($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
}
?>