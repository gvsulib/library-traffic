<?php
require '../connection.php';
$query = 
"SELECT
	t.space as id,
	s.name,
	t.level,
	tl.name as label,
	e.time as lastUpdated
FROM
	spaces s,
	entries e,
	traffic t,
	traffic_labels tl
WHERE
	t.space = " . $_GET['id'] . "
	AND t.space = s.id
	AND t.level = tl.id
	AND t.entryId = e.entryId
ORDER BY
	e.entryId DESC
LIMIT 1
	";
$results = $con->query($query);
$r = $results->fetch_assoc();
$r['id'] = (int) $r['id'];
echo json_encode($r,64); //JSON_UNESCAPED_SLASHES
?>
