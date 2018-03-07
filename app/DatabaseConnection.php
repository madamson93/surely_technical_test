<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 07/03/2018
 * Time: 15:26
 */

class DatabaseConnection {

	//database connection details
	private $dbhost = '127.0.0.1';
	private $dbuser = 'homestead';
	private $dbpassword = 'secret';
	private $dbname = 'surely_tasks';

	//variable to connect to the database
	public $connection;

	public function connectToDatabase(){
		try {
			$this->connection = mysqli_connect(
				$this->dbhost,
				$this->dbuser,
				$this->dbpassword,
				$this->dbname
			);
		} catch (mysqli_sql_exception $mysqli_sql_exception) {
			echo "Connection error: " . $mysqli_sql_exception->getMessage();
		}

		return $this->connection;
	}
}