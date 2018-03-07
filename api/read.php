<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 07/03/2018
 * Time: 15:44
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once('../app/DatabaseConnection.php');

//create database connection
$dbConnection = new DatabaseConnection();
$dbLink = $dbConnection->connectToDatabase();

//use SELECT query to return all tasks in the database
$getAllTasksQuery = "SELECT * FROM tasks";
$tasksData = mysqli_query($dbLink, $getAllTasksQuery);

//return data in the JSON response
$tasksArray = [];

if ($tasksData->num_rows > 0) {
	while ($row = $tasksData->fetch_assoc()) {
		//create a new object to extract the data from the database
		$taskObj = new stdClass();
		$taskObj->id = $row['id'];
		$taskObj->task_details = $row['task_details'];

		$tasksArray[] = $taskObj;

		//return the JSON response
		echo json_encode($tasksArray);
	}
} else {
	echo "0 results found";
}

mysqli_close($dbLink);