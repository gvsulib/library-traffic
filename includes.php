<?php
include 'connection.php'; //database connection
include 'getFromDb.php'; //functions to retreive from database
include 'formDisplayFunctions.php'; //functions to display the form
date_default_timezone_set("America/Detroit"); //required for inserting the time into the database
function checkIP(){
    $message = "You are not authorized to access this page.";
    $validIP = [148,61];
    if ($_SERVER['REMOTE_ADDR'] == "::1") return; //localhost
    $userIP = explode('.',$_SERVER['REMOTE_ADDR']);
    if (!($userIP[0] == $validIP[0] && $userIP[1] == $validIP[1])){
        die ($message);
    }
}
?>