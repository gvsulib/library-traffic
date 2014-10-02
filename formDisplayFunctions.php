<?php

/**
 * Function to render the HTML form.
 */
function displayForm($whichForm){
	$areas = getAreasFromDb();
	echo "<div class='lib-form'>";
	echo "<form action='processForm.php' method='POST'>";

	foreach($areas as $spaceID => $space){
		echo "<div class='space-wrapper' id='" . $spaceID . "'>";
		echo "<h2>Zone: " . $space['name'] . "</h2>";
		echo "<div class='space-inputs'>";		

		echo getInputsForSpaceAndType($spaceID, 'traffic');
        if ($whichForm == "spaceUse") {
            echo getInputsForSpaceAndType($spaceID, 'noise');
            if ($areas[$spaceID]['computers']) {
                echo getInputsForComputer($spaceID);
            }
            if ($areas[$spaceID]['collab']) {
                echo getInputsForCollab($spaceID);
            }
            if ($areas[$spaceID]['whiteboard']) {
                echo getInputsForWhiteboard($spaceID);
            }
        }
		echo getInputForComments($spaceID);
		echo "</div>";
		echo "</div>";
	}
    echo "<input type='hidden' name='formType' value='" . $whichForm . "'/>" .
        "<h4>Initials</h4>" .
        "<input type='text' size='3' maxlength='3' name='initials' style='width:10%'>";
	echo "<input type='submit' value='Submit' />";
	echo "</form>";
	echo "</div>";
}

/**
 * Funciton to render <select> and <option> elements
 * @param Integer $spaceID ID of the space
 * @param String $question The question for the form e.g. noise, traffic
 * @return String Generated HTML code for a <select> element
 */
function getInputsForSpaceAndType($spaceID, $question){
	$questions = getQuestions();
	$labels = getLabelsFromDb();
	$html = "<h5>" . $questions[$question] . "</h5>";
	$html .= "<select name='" . $spaceID . "_" . $question . "' style='min-width: 200px;'>";
	$html .= "<option value='0' disabled='disabled' selected='selected'>Choose</option>";
	foreach ($labels[$question] as $valueID => $valueLabel){
		$html .= "<option value='" . $valueID . "'>" . $valueLabel . "</option>";
	}
	$html .= "</select>";
	return $html;
}

/**
 * Function to render select boxes for the collab questions
 * @param Integer $spaceID ID of the space
 * @return String Generated HTML code for a set of select boxes
 */
function getInputsForCollab($spaceID){
	$questions = getQuestions();
	$labels = getLabelsFromDb();
	$html = "<h4>" . $questions['collab_header'] . "</h4>";
	foreach ($questions['collab'] as $label => $question){
		$html .= "<h5>" . $questions['collab'][$label] . "</h5>";
		$html .= "<select name='" . $spaceID . "_collab_" . $label .  "' style='min-width: 200px' data-placeholder='Choose'>";
		$html .= "<option value='0' disabled='disabled' selected='selected'>Choose</option>";
		foreach ($labels['collab'] as $valueID => $valueLabel){
			$html .= "<option value='" . $valueID . "'>" . $valueLabel . "</option>";
		}
		$html .= "</select>";
	}
	return $html;
}

/**
 * Function to render radio buttons to indicate whether a space has computers in use or not.
 * @param Integer $spaceID ID of the space
 * @return String Generated HTML code for radio buttons
 */
function getInputsForComputer($spaceID){
	$questions = getQuestions();
	$value = $spaceID . "_comptuers";
	$html = "<h5>" . $questions['computers'] . "</h5>";
	$html .= "<label for='" . $value . "'>Yes</label><input type='radio' name='" . $value . "'' value='true'>"; 
	$html .= "<label for='" . $value . "'>No</label><input type='radio' name='" . $value . "'' value='false'>"; 
	$html .= "<label for='" . $value . "' class='error' style='display:none;'>* This field is required.</label>";
	return $html;
}
/**
 * Function to render text box for whiteboard use questions
 * @param Integer $spaceID ID of the space
 * @return String generated HTML for for whiteboard textbox
 */
function getInputsForWhiteboard($spaceID){
	$questions = getQuestions();
	$html = "<h5>" . $questions['whiteboard'] . "</h5>";
	$html .= "<input class='100 whiteboard' type='text' maxlength='3' size='3' name='" . $spaceID . "_whiteboard'>";
	return $html;
}

/**
 * Function to render textarea for comments
 * @param Integer $spaceID ID of the space
 * @return String Generated HTML for the comments textarea
 */
function getInputForComments($spaceID){
	$questions = getQuestions();
	$html = "<h5>" . $questions['comments'] . "</h5>";
	$html .= "<textarea rows='5' name='" . $spaceID . "_comments'></textarea>";
	return $html;
}
/**
 * Function to get question labels
 * @return Array questions 
 */
function getQuestions(){
	$questions['traffic'] = "Percentage full:";
	$questions['collab_header'] = "Collaboration & Engagement";
	$questions['collab']['groups'] = "How many people are collaborating in a group?";
	$questions['collab']['alone'] = "How many people are sitting with a group but are working alone?";
	$questions['collab']['individual'] = "How many people are sitting and working individually?";
	$questions['noise'] = "Sound level:";
	$questions['computers'] = "Are there groups using the computers?";
	$questions['whiteboard'] = "How many 'rooms-on-the-fly' have been created with mobile whiteboards in the zone?";
	$questions['comments'] = "Miscellaneous Comments:";
	return $questions;
}
?>