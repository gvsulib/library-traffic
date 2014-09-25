<?php
date_default_timezone_set ("America/Detroit");
include 'connection.php'
if ($_GET) {
	$confirm = "";
	
	$query = "SELECT ID, name FROM space_labels";

	$result = mysqli_query ( $con , $query);
	
	$error = array();

	if ($result == FALSE) {
		exit("Error retrieving space data, traffic not logged, call Kyle.");
		}
	

	while ($row = $result->fetch_row()) {
		$splabels[$row[0]] = $row[1];
        
    }
    foreach ($splabels as $ID => $name) {
    if ($_GET[$ID] === "0") {
        	$error[] = "Did not fill out " . $name;
        }

	}
	
	if ($_GET["initials"] === "") {
		$error[] = "Did not fill out initials.";
	
	}
	
	if (empty($error)) {
	
		$time = date('Y-m-d h:i:s');
	
		$query = "INSERT INTO entries (time) VALUES ($time)";
		
		$result = mysqli_query ( $con , $query);
	
		if (!$result) { exit("Can't create line in entry table, Call Kyle. "  .  mysqli_error($con));}
	
		$entryID = mysqli_insert_id($con);
	
		foreach ($_GET as $ID=>$value) {
			if (array_key_exists($ID, $splabels)) {
		
				$valuestring = $value . "," . $entryID . "," . $ID;
				$query = "INSERT INTO traffic (level, entryID, space) VALUES ($valuestring)";
				$result = mysqli_query ( $con , $query);
				if (!$result) {  exit("Failed to input row:" . $valuestring );}
				}
		
		}
	$confirm = "Database updated!";
	}	
		
	
		

}
?>
<HTML>
	<HEAD>
		<link rel="stylesheet" type="text/css" href="http://gvsu.edu/cms3/assets/741ECAAE-BD54-A816-71DAF591D1D7955C/libui.css" />
	</HEAD>
	<BODY>

<?php



$query = "SELECT ID, name FROM space_labels";

$result = mysqli_query($con,$query);

if ($result == FALSE) {
	exit("Error retrieving space data, call Kyle.");
	
} else {

	while ($row = $result->fetch_row()) {
        $splabels[$row[0]] = $row[1];
        
    }

}

$query = "SELECT ID, name FROM traffic_labels";

$result = mysqli_query ( $con , $query);

if ($result == FALSE) {
	exit("Error retrieving traffic data, call Kyle.");
	
} else {

	while ($row = $result->fetch_row()) {
    	$trlabels[$row[0]] = $row[1];
    }

}

mysqli_close($con);
	
	echo date('Y-m-d h:i:s', $time);
	
if ($confirm) {
		echo "<P>" . $confirm . "</P>";
}
	
if (!empty($error)) {
	echo "<P color='red'> Form has errors.</P>";
	foreach ($error as $key=>$value) {
		echo "<P color='red'>" . $value . "</P>";
		
	}
		
}
	
echo <<<END

	<div class="lib-form">
	<form action="" method="get" name="traffic-form">
		
	
		<div class="line">
			<div class="span1 unit">
				<label for="initials">Initials</label>
END;
				
echo "<input name='initials' type='text' MaxLength='3' value=\"";
				
if ($_GET["initials"]) {echo $_GET["initials"];}
				
echo "\" required/>
			</div>
			
			<div class='span1 unit '>";
			


		
foreach ($splabels as $ID=>$name) {
	echo "<P><label class='lib-inline'>$name</label><select name=" . $ID . ">";
	echo "<option value=\"0\">----------</option>";
	foreach ($trlabels as $trID=>$trname) {					
		echo "<option value=\"" . $trID . "\" ";
		if ($_GET[$ID] && $_GET[$ID] == $trID) {echo "selected";}
		echo  ">" . $trname . "</option>";
		
		}
	echo "</select></P>";	
	}
			
echo <<<END
				<input class="lib-button" name="submit" type="submit" value="Submit" />
			</div>	
		</div>
	</form>	
	</div>


	</form>
</div>

END;

?>

</BODY>



</HTML>

