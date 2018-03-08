<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 07/03/2018
 * Time: 15:44
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");

include_once('../app/DatabaseConnection.php');

//create database connection
$dbConnection = new DatabaseConnection();
$dbLink = $dbConnection->connectToDatabase();

//retrieve value from request body in JSON format
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);
$taskToUpdateID = $input['task_id'];
$taskDetailsText = $input['task_details'];

//use UPDATE query to update an existing task in the database
$updateTaskQuery = "UPDATE tasks SET task_details ='$taskDetailsText' WHERE id=$taskToUpdateID";

if ($dbLink->query($updateTaskQuery) === TRUE) {
	echo json_encode([
		"success" => "Record was updated successfully."
	]);
} else {
	echo json_encode([
		"error" => "Query: " . $updateTaskQuery . "could not be run. Error has occured: " . $dbLink->error
	]);
}

$dbLink->close();