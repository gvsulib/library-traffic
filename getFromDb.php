<?php
/**
 * PHP Script containing functions to retrieve data from the MySQL database.
 */
/**
 * Function to retreive the areas from the database.
 * @return Array of areas in the MIP library.
 * 
 */	
function getAreasFromDb(){
	global $con;
	$area_query = "SELECT ID, name, collab, computers, whiteboard FROM spaces";
	$db_result = $con->query($area_query);

	while ($area = $db_result->fetch_row()) {
		$areas[$area[0]]['name'] = $area[1];
		$areas[$area[0]]['collab'] = $area[2];
		$areas[$area[0]]['computers'] = $area[3];
		$areas[$area[0]]['whiteboard'] = $area[4];
	}
	return $areas;
}
/**
 * Funciton to retreive the labels from the database.
 * Used to create <option>s for <select> elements in the input form.
 * @return Array of labels.
 */
function getLabelsFromDb(){
	global $con;

	$queries['traffic'] = "SELECT ID, name from traffic_labels";
	$queries['collab'] = "SELECT ID, name FROM collab_labels";
	$queries['noise'] = "SELECT ID, name FROM noise_labels";

	foreach ($queries as $name => $query){
		$db_result = mysqli_query($con, $query);
		while ($label = $db_result->fetch_row()) {
			$labels[$name][$label[0]] = $label[1];
		}
	}

	return $labels;
}
?>