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

//use UPDATE query to update an existing task in the database
$taskDetailsText = $_REQUEST['task_details'];
$taskToEditID = $_REQUEST['task_id'];
$updateTaskQuery = "UPDATE tasks SET task_details ='$taskDetailsText' WHERE id=$taskToEditID";

if (!empty($taskDetailsText) || $taskDetailsText !== null) {
	$dbLink->query($updateTaskQuery);
} else {
	echo json_encode([
		"error" => "Task cannot be blank, please try again."
	]);
}

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