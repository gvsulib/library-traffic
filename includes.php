<?php
session_start();
if ($_GET['logout']){
	$_SESSION = array();
	session_destroy();
}
if ($_SESSION['loggedIn'] != true){
	header('location: login.php');
}

include 'connection.php'; //database connection
include 'getFromDb.php'; //functions to retreive from database
include 'formDisplayFunctions.php'; //functions to display the form
date_default_timezone_set("America/Detroit"); //required for inserting the time into the database

?>