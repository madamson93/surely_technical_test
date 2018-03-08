<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 07/03/2018
 * Time: 15:44
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once('../app/DatabaseConnection.php');

//create database connection
$dbConnection = new DatabaseConnection();
$dbLink = $dbConnection->connectToDatabase();

//retrieve value from request body in JSON format
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);
$taskDetailsText = $input['task_details'];

//use INSERT query to create a new task in the database
$insertNewTaskQuery = "INSERT INTO tasks (task_details) VALUES ('$taskDetailsText')";

if (!empty($taskDetailsText) || $taskDetailsText !== null) {
	if($dbLink->query($insertNewTaskQuery) === TRUE) {
		echo json_encode([
			"success" => "New record created successfully."
		]);
	} else {
		echo json_encode([
			"error" => "Query: " . $insertNewTaskQuery . "could not be run. Error has occured: " . $dbLink->error
		]);
	}
} else {
	echo json_encode([
		"error" => "Task cannot be blank, please try again."
	]);
}

$dbLink->close();