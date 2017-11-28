<?php
include 'connection.php'; //database connection


function checkIP(){
    $db = getConnection();
    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $db->query("INSERT INTO `access_log`
            (accessid, system, ip, useragent, timestamp) VALUES
            (NULL, 'traffic-input', '$ip', '$ua', SYSDATE())");
    $ip = explode(".",$ip);
    if (!(
        ($ip[0] == "::1") ||
        ($ip[0] == "148" && $ip[1] == "61") ||
        ($ip[0] == "35" && $ip[1] == "40") ||
        ($ip[0] == "207" && $ip[1] == "72" &&
            ($ip[2] >= 160 && $ip[2] <= 191)
        ))

    ){
        die();
    }
}
checkIP();



if ($_GET['logout']){
	
	$_COOKIE['loggedIn'] = 'false';
}
if ($_COOKIE['loggedIn'] == 'false'){
	header('location: login.php');
}


include 'getFromDb.php'; //functions to retreive from database
include 'formDisplayFunctions.php'; //functions to display the form
date_default_timezone_set("America/Detroit"); //required for inserting the time into the database

?>
