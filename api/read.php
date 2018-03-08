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
$getTasksResult = $dbLink->query($getAllTasksQuery);

//return data in the JSON response
$tasksArray = [];

if ($getTasksResult->num_rows > 0) {
	while ($row = $getTasksResult->fetch_assoc()) {
		//create a new object to extract the data from the database
		$taskObj = new stdClass();
		$taskObj->id = $row['id'];
		$taskObj->task_details = $row['task_details'];

		$tasksArray[] = $taskObj;
	}

	//return the JSON response
	echo json_encode($tasksArray);
}

$dbLink->close();