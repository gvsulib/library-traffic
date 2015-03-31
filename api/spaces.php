<?php
require '../connection.php';
$query =
"SELECT
	id,
	name,
	description
FROM
	spaces
	";
$results = $con->query($query);
$spaces = array();
while ($space = $results->fetch_assoc()){
	$space['id'] = (int) $space['id'];
	$space['meta'] = array();
	$space['meta']['url'] = "space/" . $space['id'];
	$spaces[] = $space;
}
echo json_encode($spaces,64); //JSON_UNESCAPED_SLASHES
?>
