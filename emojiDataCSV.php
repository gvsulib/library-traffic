<?php
include 'connection.php'; //database connection
$msg = "";

date_default_timezone_set('America/Detroit');
//convert date values to sanitize them
function convertTime($str) {
    $unixStamp = strtotime($str);
    return date("Y-m-d", $unixStamp);

}

if (isset($_POST["submit"])) {
    $date1 = convertTime($_POST["start"]) . " 00:00:00";
    $date2 = convertTime($_POST["end"]) . " 23:59:59";

    $query="SELECT emotion_id, COUNT(emotion_id), (COUNT(emotion_id) * 100 /(SELECT COUNT(*) FROM feedback_response WHERE timestamp >= \"$date1\" AND timestamp <= \"$date2\")) as percent FROM feedback_response WHERE timestamp >= \"$date1\" AND timestamp <= \"$date2\" GROUP BY emotion_id";
   
    $stmt = $con->prepare($query);

    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows <= 0 ) {
        $msg = "No results found for that date range";
    } else {

        $stmt->bind_result($emotion_id, $count, $percent);

        $CSVFile = fopen("emojiData.csv", "w");

        while ($stmt->fetch()) {
            switch ($emotion_id) {
                case 1: $emotion_id = "really bad"; break;
                case 2: $emotion_id = "bad"; break;
                case 3: $emotion_id = "neutral"; break;
                case 4: $emotion_id = "good"; break;
                case 5: $emotion_id = "really good"; break;



            }


            fputcsv($CSVFile, array($emotion_id, $count, $percent));

        }

        fclose($CSVFile);
    }


}




?>

<HTML>
<HEAD>
<TITLE>Download Emoji stats</TITLE>


</HEAD>
<BODY>
<h1>Download Emoji Stats</h1>
<form name="selectdates" method="POST" action="">
<P>
<input type="date" <?php if (isset($_POST["start"])) {echo "value=\"" . $_POST["start"] . "\"";} ?> name="start" /> Start Date
<P>
<input type="date" <?php if (isset($_POST["end"])) {echo "value=\"" . $_POST["end"] . "\"";} ?> name="end" /> End Date


<P><input type="submit" name="submit" value="submit"/P>
</form>

<?php if (isset($_POST["submit"]) && $msg === "") {echo "<a href='emojiData.csv?'>Download the Data</a><br>";} else {echo $msg;} ?>

</BODY>
</HTML>