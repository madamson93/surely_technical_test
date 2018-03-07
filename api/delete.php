<?php
/**
 * Created by PhpStorm.
 * User: moniqueadamson
 * Date: 07/03/2018
 * Time: 15:45
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include_once('../app/DatabaseConnection.php');

//create database connection
$dbConnection = new DatabaseConnection();
$dbLink = $dbConnection->connectToDatabase();

//use DELETE query to delete an existing entry in the database
$taskToDeleteID = $_REQUEST['task_id'];
$deleteTaskQuery = "DELETE FROM tasks WHERE id=$taskToDeleteID";

if ($dbLink->query($deleteTaskQuery) === TRUE) {
	echo json_encode([
		"success" => "Record was deleted successfully."
	]);
} else {
	echo json_encode([
		"error" => "Query: " . $deleteTaskQuery . "could not be run. Error has occured: " . $dbLink->error
	]);
}

$dbLink->close();