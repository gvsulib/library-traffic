<?

if(!isset($_COOKIE['loggedIn'])) {
    header('Location: index.php');
} else if ($_COOKIE['loggedIn'] === 'false') {
	header('Location: index.php');
}
	

include "password.php";

//get the traffic labels from the database

$labels = file_get_contents("https://prod.library.gvsu.edu/trafficapi/traffic");

$labels = json_decode($labels, TRUE);

if (is_null($labels)){
	echo "Cannot get traffic labels.";
	die();
}

$spaces = file_get_contents("https://prod.library.gvsu.edu/trafficapi/spaces");

$spaces = json_decode($spaces, TRUE);

if (is_null($spaces)){
	echo "Cannot get space information.";
	die();
}
$msg = "";
if (isset($_POST["submit"])) {
	
	 unset($_POST["submit"]);

	 $reformatted = array();

	 $reformatted["initials"] = $_POST["initials"];

	 unset($_POST["initials"]);

	 foreach ($_POST as $key => $value) {
		 $reformatted[] = array("space" => "$key", "level" => "$value");


	 }
	 

	$json = json_encode($reformatted);

	$curl = curl_init("https://prod.library.gvsu.edu/trafficapi/traffic/");

	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($curl, CURLOPT_USERPWD, ":$APIKey");
	curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json))); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 1);

	$response = curl_exec($curl);

	$httpcode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close ($curl);
	
	if ($httpcode != 200) {
		$msg = "There was an error posting data, http code: " . $httpcode;
	} else {
		header('Location: complete.php');
	}




}







?>



<!DOCTYPE html>
<html>
<head>
	<title>GVSU MIP Library Traffic</title>
	<!-- <link rel="stylesheet" type="text/css" href="http://gvsu.edu/cms3/assets/741ECAAE-BD54-A816-71DAF591D1D7955C/libui.css" /> -->
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
<h1>GVSU MIP Library Traffic Log Form</h1>

<?
if ($msg != "") {
	echo "<div style=\"font-weight:bold\">$msg</div>";

}
?>

<div class='lib-form'>
		<form action="" name="" method="POST">

		<h4><label for="initials">Your initials:</label></h4>
		<input type="text" name="initials" maxlength="3" size="3" style='width:10%' <? if (isset($_POST["initials"])) {echo "value=\"" . $_POST["initials"]. "\"";} ?>>

		<?

		foreach($spaces as $space) {
			echo "<div class='space-wrapper' id='" . $space["ID"] . "'>";
			echo "<h2><label for=\"" . $space["ID"] . "\">Zone: " . $space["name"] . "</label></h2>\n";
			echo "<div class='space-inputs'>";
			echo "<select name=\"" . $space["ID"] . "\" style='min-width: 200px;'>";
			echo "<option value=\"\" disabled='disabled'";
			
			if (!isset($_POST[$space["ID"]])) {echo "selected='selected'";}
			
			echo ">Choose</option>";
			foreach ($labels as $label) {
				if ($label["ID"] != "-1" || ($label["ID"] == "-1" && ($space["ID"] == 2 || $space["ID"] == 3))) {
					echo "<option value=\"" . $label["ID"] . "\"";
					if (isset($_POST[$space["ID"]])) {
						echo "selected='selected'";
					}
					echo ">" . $label["name"] . "</option>";
				}
			}
			echo "</select>";
			echo "</div>";
			echo "</div>";
		}

		echo "<P>";

		echo "<input type=\"submit\" name=\"submit\" value=\"Submit\">";
		?>


		</form>
	</div>



	<script src="//code.jquery.com/jquery.js"></script>
	<script src="js/jquery.validate.js"></script>
    <script src="js/jquery.swap.js"></script>
	<script src="js/scripts.js"></script>
</body>
</html>