<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 08/03/2018
 * Time: 11:46
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once('../app/DatabaseConnection.php');

//create database connection
$dbConnection = new DatabaseConnection();
$dbLink = $dbConnection->connectToDatabase();

//retrieve GET value
$taskToFindID = $_REQUEST['task_id'];

//use SELECT query to return a task in the database
$getTaskQuery = "SELECT * FROM tasks WHERE id = $taskToFindID";
$getTaskQueryResult = $dbLink->query($getTaskQuery);

//return data in the JSON response
$tasksArray = [];

if ($getTaskQueryResult->num_rows > 0) {
	while ($row = $getTaskQueryResult->fetch_assoc()) {
		//create a new object to extract the data from the database
		$taskObj = new stdClass();
		$taskObj->id = $row['id'];
		$taskObj->task_details = $row['task_details'];

		$tasksArray[] = $taskObj;
	}

	//return the JSON response
	echo json_encode($tasksArray);
} else {
	echo json_encode([
		"error" => "No task with the requested ID could be found"
	]);
}

$dbLink->close();