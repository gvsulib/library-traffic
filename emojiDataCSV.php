<?php

include 'connection.php'; //database connection

$query="SELECT emotion_id, COUNT(emotion_id) CASE when emotion_id = 1 then  FROM feedback_response GROUP BY emotion_id";

$stmt = $con->prepare($query);

$stmt->execute();

$stmt->bind_result($emotion_id, $count);

$CSVFile = fopen("emojiData.csv", "w");

while ($stmt->fetch()) {
    fputcsv($CSVFile, array($emotion_id, $count));

}

fclose($CSVFile);

?>

<HTML>
<HEAD>
<TITLE>Download Emoji stats</TITLE>
</HEAD>
<BODY>

<?php echo "<a href='emojiData.csv?'>Download the Data</a><br>"; ?>

</BODY>
</HTML>