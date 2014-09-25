<?php
include 'includes.php';
$errorMessages['badForm'] = "Error retreiving data from form. Call Jon.";

$insertQueries['entry'] = "INSERT INTO entries (time, `use`, entryID, initials) VALUES (?, 1, NULL, ?);";
$insertQueries['spaceUse'] = "INSERT INTO spaceuse (entryID,spaceID, groups, alone, individual, whiteboard, computers) " .
                             "VALUES (?,?,?,?,?,?,?);";
$insertQueries['traffic'] = "INSERT INTO traffic (level, entryID, space, noise, comments) VALUES (?,?,?,?,?);";

//$errors will hold the errors to be displayed on page.
$errors;
$data;
if (!$_POST){
    $errors[] = $errorMessages['badForm'];
}
else{
    foreach($_POST as $222222field => $value){
        $fieldInfo = explode("_", $field);
        switch (count($fieldInfo)){
            case 1: //initials
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
function processFormData($data){
    global $con, $insertQueries;
    $entryID = getEntryID($data['initials']);
    for ($i = 1; $i < count($data); $i++){
        $space = getSpaceFromData($data, $i);
        if ($space['use'] === true){
            insertSpaceUse($space, $entryID);
        }
        insertSpaceTraffic($space, $entryID);
    }
}

function getSpaceFromData($data, $spaceID){
    
    if ($data[$spaceID]['collab']){
        $space['use'] = true;
    }
    $space['spaceID'] = $spaceID;
    $space['whiteboard'] = 0; //default
    $space['computers'] = 0; //default
    foreach($data[$spaceID] as $field => $value){
        echo $field . " -> " . $value . "\n";
        if (count($value) == 1){ //not collab
            if ($field == 'computers'){
                $value = $value ? 1 : 0;
            }
            $space[$field] = $value;
        } else {
            foreach($value as $collab => $collabValue){
                $space[$collab] = $collabValue;
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
function getEntryID($initials){
    global $con, $insertQueries;
    $time = date('Y-m-d h:i:s');
    $query = $con->prepare($insertQueries['entry']);
    print_r($con);
    $query->bind_param('ss', $time, $initials);
    $query->execute();
    echo "entryID = " . $con->insert_id . "\n";
    return $con->insert_id;
}

/**
 * Function to insert into the spaceuse table
 * @param Array $space representation of a space
 * @param Integer $entryID id of the entry
 */
function insertSpaceUse($space, $entryID){
    global $con, $insertQueries;
    $query = $con->prepare($insertQueries['spaceUse']);
    $query->bind_param('iiiiiii', $entryID, $space['spaceID'], 
        $space['groups'], $space['alone'], $space['individual'], $space['whiteboard'], $space['computers']);
    $query->execute();
}

/**
 * Function to insert into the traffic table
 * @param Array $space representation of a space
 * @param Integer $entryID id of the entry
 */
function insertSpaceTraffic($space, $entryID){
    global $con, $insertQueries;
    echo $space['traffic'] . "\n";
    echo $entryID . "\n";
    echo $space['spaceID'] . "\n";
    echo $space['noise'] . "\n";
    $query = $con->prepare($insertQueries['traffic']);
    print_r($con);
    $query->bind_param('iiiis', $space['traffic'], $entryID, $space['spaceID'], $space['noise'], $space['comments']);
    $query->execute();
}
?>