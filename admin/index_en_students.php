<?php
/*
 * index_en_students.php    -   conference-registration
 */

if (defined("ADMTKN")) {
    $optionsStripAll = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
    $adminTokenInput = filter_input(INPUT_GET, 'admtkn', FILTER_SANITIZE_STRING, $optionsStripAll);

    if (!empty($adminTokenInput)) {
        if ($adminTokenInput !== ADMTKN) {
            $errorTitle = "Unauthorized Access";
            $errorMessage = "You must be part of the administrative staff in order to enter this section.";
            showPrettyError($errorMessage, 'error', false, $errorTitle);
        } else {
            $bln_isAdminUser = $bln_subComponent = (bool) true;
        }// if admtkn provided is wrong
    } else {
        $errorTitle = "Unauthorized Access";
        $errorMessage = "You must be part of the administrative staff in order to enter this section.";
        showPrettyError($errorMessage, 'error', false, $errorTitle);
    }// if admtkn not provided in url
} else {
    require_once "../data/environment.php";
    require_once "{$str_appLocation}lib/prettyErrors.php";
    require_once "{$str_appLocation}lib/logging.php";
    require_once "{$str_appLocation}views/errorMessages_en_directComponentAccess.php";

    showPrettyError($errorMessage, 'error', true, 'Direct Component Access');
}// If ADMTKN not defined

if ($adminAction === "report-participants") {
    require_once "{$str_appLocation}lib/dbConnect.php";
    require_once "{$str_appLocation}lib/sessions.php";
    require_once "{$str_appLocation}lib/registrations.php";
    require_once "{$str_appLocation}lib/reports_students.php";

    $optionsStripAll = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
    $reportExport = filter_input(INPUT_GET, 'export', FILTER_SANITIZE_NUMBER_INT);
    $reportFormat = filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING, $optionsStripAll);

    if (empty($reportExport)) {
        reports_sessionParticipants($dbConnectionObject);
    } else {
        if (empty($reportFormat)) {
            $reportFormat = "csv";
        }

        $reportOutput = reports_sessionParticipants($dbConnectionObject, true, $reportFormat);
        echo "<form name=\"hiddenForm\" method=\"post\" action=\"{$str_appURL}admin/export.php?admtkn={$adminTokenInput}\">\n"
            . "<input type=\"hidden\" name=\"dataToExport\" value=\"" . htmlentities($reportOutput) . "\">\n"
            . "<input type=\"hidden\" name=\"dataFormat\" value=\"" . htmlentities($reportFormat) . "\">\n</form>\n";

        echo "<p>Exporting to " . htmlentities($reportFormat) . " format... Done!<br><br>"
            . "<a href=\"{$str_appURL}admin/index.php?action=report-participants&admtkn={$adminTokenInput}\">"
            . "Click here</a> to return to the report.</p></div><!-- main -->\n";

        require "{$str_appLocation}views/georgianFooter.php";

        if (isset($dbConnectionObject) && is_object($dbConnectionObject)) {
            mysqli_close($dbConnectionObject);
        }
        
        // This javascript will submit the form above with the hidden input
        // fields once the browser finishes parsing the page. Only way I could
        // think of moving this potentially large amount of data without using
        // Sessions. What do you think? :)
        echo "<script language=\"JavaScript\">document.hiddenForm.submit();</script>";
        
        exit();
    }
}// "report-participants"

if ($adminAction == "edit-session") {
    $idFromGet = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $idFromPost = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    if (empty($idFromGet) && empty($idFromPost)) {// No session id, display a form:
        require_once "{$str_appLocation}views/forms_en_sessionPicker.php";
    } else if (!empty($idFromGet)) {// We have a session id from GET collection, display an edit form
        require_once "{$str_appLocation}lib/dbConnect.php";
        require_once "{$str_appLocation}lib/sessions.php";
        
        $sessionExists = doesSessionExist($dbConnectionObject, $idFromGet);
        
        if (is_string($sessionExists)) {
            if ($bln_isBeta) {
                echoToConsole("mysqli error when checking if session exists: {$sessionExists}", true);
            }
        } else {
            $ary_sessionFields = getSessionById($idFromGet, $dbConnectionObject);
            
            if (is_string($ary_sessionFields)) {
                if ($bln_isBeta) {
                    echoToConsole("Mysqli error while getting session fields: {$ary_sessionFields}", true);
                }
            } else {
                require_once "{$str_appLocation}views/forms_en_sessionEditor.php";
            }
        }
    } else if (!empty($idFromPost)) {// We have a session id from POST collection, lets update it!
        require_once "{$str_appLocation}lib/dbConnect.php";
        require_once "{$str_appLocation}lib/sessions.php";
        
        $sessionExists = doesSessionExist($dbConnectionObject, $idFromPost);
        
        if (is_string($sessionExists)) {
            if ($bln_isBeta) {
                echoToConsole("mysqli error when checking if session exists: {$sessionExists}", true);
            }
        } else {
            $optionsStripAll = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
            $numberInput = filter_input(INPUT_POST, 'numberInput', FILTER_SANITIZE_STRING, $optionsStripAll);
            $mandatoryInput = filter_input(INPUT_POST, 'mandatoryInput', FILTER_SANITIZE_STRING, $optionsStripAll);
            $requirePassportInput = filter_input(INPUT_POST, 'requirePassportInput', FILTER_SANITIZE_STRING, $optionsStripAll);
            $descriptionInput = filter_input(INPUT_POST, 'descriptionInput', FILTER_UNSAFE_RAW);
            $dayInput = filter_input(INPUT_POST, 'dayInput', FILTER_SANITIZE_STRING, $optionsStripAll);
            $maxInput = filter_input(INPUT_POST, 'maxInput', FILTER_SANITIZE_STRING, $optionsStripAll);
            $locationInput = filter_input(INPUT_POST, 'locationInput', FILTER_SANITIZE_STRING, $optionsStripAll);
            
            $errorMessage = (string) "";
            
            if (!empty($numberInput)) {
                $numberInput = mysqli_real_escape_string($dbConnectionObject, $numberInput);
            } else {
                $errorMessage .= "You did not specify the session number.<br>";
            }
            
            if (!empty($mandatoryInput)) {
                $mandatoryInput = mysqli_real_escape_string($dbConnectionObject, $mandatoryInput);
            } else {
                $errorMessage .= "You did not specify if the session was mandatory.<br>";
            }
            
            if (!empty($requirePassportInput)) {
                $requirePassportInput = mysqli_real_escape_string($dbConnectionObject, $requirePassportInput);
            } else {
                $errorMessage .= "You did not specify if the session required a passport.<br>";
            }
            
            if (!empty($descriptionInput)) {
                $descriptionInput = mysqli_real_escape_string($dbConnectionObject, $descriptionInput);
            } else {
                $errorMessage .= "You did not specify the session description.<br>";
            }
            
            if (!empty($dayInput)) {
                $dayInput = mysqli_real_escape_string($dbConnectionObject, $dayInput);
            } else {
                $errorMessage .= "You did not specify the session day.<br>";
            }
            
            if (!empty($maxInput)) {
                $maxInput = mysqli_real_escape_string($dbConnectionObject, $maxInput);
            } else {
                $errorMessage .= "You did not specify the session maximum.<br>";
            }
            
            if (!empty($locationInput)) {
                $locationInput = mysqli_real_escape_string($dbConnectionObject, $locationInput);
            } else {
                $errorMessage .= "You did not specify the location of the session.<br>";
            }
            
            if ($errorMessage != "") {
                showPrettyError($errorMessage, 'error', true, 'There were missing fields!');
            }
            
            $updateResult = updateSession($dbConnectionObject, $idFromPost, $numberInput, $mandatoryInput, $requirePassportInput, $descriptionInput, $dayInput, $maxInput, $locationInput);
            
            if ($updateResult === true) {// Updated the session!
                $infoTitle = "Success!";
                $infoMessage = "Successfully updated the session with the new values.";
                showPrettyError($infoMessage, 'info', true, $infoTitle);
            } else {// Failed to update
                $errorMessage = "Failed to update the session! The error returned {$updateResult}";
                showPrettyError($errorMessage, 'error', true, "Error updating session");
            }
        }
    } else {
        // Don't know what to do here.
        echo "<p>Something went horribly wrong!</p>\n";
    }
}// "edit-session"
?>