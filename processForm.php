<?php
include 'includes.php';
$errorMessages['badForm'] = "Error retrieving data from form. Try reloading the form. If that does not fix the problem, report it on the library systems status page.";
$errorMessages['spaceUse'] = "Error with space use";
$errorMessages['traffic'] = "Error with traffic";

$insertQueries['entry'] = "INSERT INTO entries (time, `use`, entryID, initials) VALUES (?, ?, NULL, ?);";
$insertQueries['spaceUse'] = "INSERT INTO spaceuse (entryID,spaceID, groups, alone, individual, whiteboard, computers, noise) " .
    "VALUES (?,?,?,?,?,?,?,?);";
$insertQueries['traffic'] = "INSERT INTO traffic (level, entryID, space,  comments) VALUES (?,?,?,?);";

//$errors will hold the errors to be displayed on page.
$errors;
$data;
if (!$_POST) {
    $errors[] = $errorMessages['badForm'];
} else {
    foreach ($_POST as $field => $value) {
        $fieldInfo = explode("_", $field);
        switch (count($fieldInfo)) {
            case 1: //initials, formType
                $data[$field] = $value;
                break;
            case 2: //data that isn't collab
                $data[$fieldInfo[0]][$fieldInfo[1]] = $value;
                break;
            case 3: //collab
                $data[$fieldInfo[0]][$fieldInfo[1]][$fieldInfo[2]] = $value;
                break;
            default: //invalid
                $errors[] = $errorMessages['badForm'];
                break;
        }
    }
}

processFormData($data);
function processFormData($data)
{
    global $con, $insertQueries, $errors, $errorMessages;
    $formType = $data['formType'];
    $entryID = getEntryID($data['initials'], $formType == 'spaceUse');
    for ($i = 1; $i < count($data)-1; $i++) {
        $space = getSpaceFromData($data, $i);
        if ($formType == "spaceUse" && $space['use']) {
            insertSpaceUse($space, $entryID);
        }
        insertSpaceTraffic($space, $entryID);
    }

}

function getSpaceFromData($data, $spaceID)
{
    $space['spaceID'] = $spaceID;
    $space['whiteboard'] = 0; //default
    $space['computers'] = 0; //default
    if ($spaceID <= count($data) - 1) {
        foreach ($data[$spaceID] as $field => $value) {
            if (count($value) == 1) { //not collab
                if ($field == 'computers') {
                    $value = $value ? 1 : 0;
                }
                $space[$field] = $value;
            } else {
                $space['use'] = true;
                foreach ($value as $collab => $collabValue) {
                    $space[$collab] = $collabValue;
                }
            }
        }
    }
    return $space;
}

/**
 * Function to get a new entryID from the database
 * @param String $initials Initials of the user
 * @return Integer new entryID
 */
function getEntryID($initials, $use)
{
    global $con, $insertQueries;
    $use = $use ? 1 : 0;
    $time = date('Y-m-d H:i:s');
    $query = $con->prepare($insertQueries['entry']);
    $query->bind_param('sis', $time, $use, $initials);
    $query->execute();
    return $con->insert_id;
}

/**
 * Function to insert into the spaceuse table
 * @param Array $space representation of a space
 * @param Integer $entryID id of the entry
 */
function insertSpaceUse($space, $entryID)
{

    global $con, $insertQueries, $errors, $errorMessages;
    $query = $con->prepare($insertQueries['spaceUse']);
    $query->bind_param('iiiiiiii', $entryID, $space['spaceID'],
        $space['groups'], $space['alone'], $space['individual'], $space['whiteboard'], $space['computers'], $space['noise']);
    if (!$query->execute()){
        $errors[] = $errorMessages['spaceUse'];
        $errors[] = $con->error;
        $errors[] = var_dump($space);
    }
}

/**
 * Function to insert into the traffic table
 * @param Array $space representation of a space
 * @param Integer $entryID id of the entry
 */
function insertSpaceTraffic($space, $entryID)
{
    global $con, $insertQueries, $errors, $errorMessages;
    $query = $con->prepare($insertQueries['traffic']);
    $query->bind_param('iiis', $space['traffic'], $entryID, $space['spaceID'], $space['comments']);
    if (!$query->execute()){
        $errors[] = $errorMessages['traffic'];
        $errors[] = $con->error;
        $errors[] = var_dump($space);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>GVSU MIP Library Traffic</title>
    <!-- <link rel="stylesheet" type="text/css" href="http://gvsu.edu/cms3/assets/741ECAAE-BD54-A816-71DAF591D1D7955C/libui.css" /> -->
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>
<h1>GVSU MIP Library Traffic</h1>
<?php
if ($errors) {
    foreach ($errors as $error => $message) {
        echo "<div class='lib-error'><p>" . $message . "</p></div>";
    }
} else {
    echo "<h2>Succesfully added data to database.</h2>";
    echo "<h4><a href='index.php'>Go back</a></h4>";
}
?>
<script src="//code.jquery.com/jquery.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
