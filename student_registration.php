<?php
/*
    Copyright 2014-2015 Micheal P. Walls <michealpwalls@gmail.com>

    This file is part of the International Student Registration System.

    International Student Registration System is free software: you can
    redistribute it and/or modify it under the terms of the GNU General
    Public License as published by the Free Software Foundation, either
    version 3 of the License, or (at your option) any later version.

    International Student Registration System is distributed in the hope
    that it will be useful, but WITHOUT ANY WARRANTY; without even the
    implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
    PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with International Student Registration System.
    If not, see <http://www.gnu.org/licenses/>.
 */

// Begin buffering output
ob_start();

echo '<!DOCTYPE html>';

require 'data/environment.php';
require "{$str_appLocation}data/db.php";
require "{$str_appLocation}data/studentRegData.php";
require "{$str_appLocation}lib/prettyErrors.php";
require "{$str_appLocation}views/headToBody.php";
include "{$str_appLocation}views/georgianHeader.php";
?>
        <div class="main">
<?php
/*
 * Check for and gather inputs
 */

$optionsStripAll = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
$studentToken = filter_input(INPUT_GET, 'stkn', FILTER_SANITIZE_STRING, $optionsStripAll);

if (empty($studentToken)) {
    $studentToken = filter_input(INPUT_POST, 'stkn', FILTER_SANITIZE_STRING, $optionsStripAll);
}

if (empty($studentToken)) {
    require "{$str_appLocation}views/errorMessages_en_missingStudentToken.php";
}// end if statement

// Make sure the studentNationality field exists
$input_studentNationality = filter_input(INPUT_POST, 'studentNationality', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentNationality)) {
    require "{$str_appLocation}views/errorMessages_en_missingStudentNationality.php";
}// end if statement

// Make sure the studentResidence field exists
$input_studentResidence = filter_input(INPUT_POST, 'studentResidence', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentResidence)) {
    require "{$str_appLocation}views/errorMessages_en_missingStudentResidence.php";
}// end if statement

// Make sure the studentProgram field exists
$studentProgram = filter_input(INPUT_POST, 'studentProgram', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($studentProgram)) {
    require "{$str_appLocation}views/errorMessages_en_missingStudentProgram.php";
}// end if statement

// Make sure the studentVisaApproved field exists
$input_studentVisaApproved = filter_input(INPUT_POST, 'studentVisaApproved', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentVisaApproved)) {
    require "{$str_appLocation}views/errorMessages_en_missingVisaApproved.php";
}// end if statement

// Make sure the studentHasPassport field exists
$input_studentPassport = filter_input(INPUT_POST, 'studentHasPassport', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentPassport)) {
    require "{$str_appLocation}views/errorMessages_en_missingPassport.php";
}// end if statement

// Make sure the studentLunch field exists
$input_studentLunch = filter_input(INPUT_POST, 'studentLunch', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentLunch)) {
    require "{$str_appLocation}views/errorMessages_en_missingStudentLunch.php";
}// end if statement

// Make sure the studentArrivingDate field exists
$input_studentArrivingDate = filter_input(INPUT_POST, 'studentArrivingDate', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentArrivingDate)) {
    require "{$str_appLocation}views/errorMessages_en_missingArrivingDate.php";
}// end if statement

// Set default values for the other missing fields
$input_studentAllergies = filter_input(INPUT_POST, 'studentAllergies', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentAllergies)) {
    $input_studentAllergies = '';
}// end if statement

$input_studentLunchSpecialReqs = filter_input(INPUT_POST, 'studentLunchSpecialReqs', FILTER_SANITIZE_STRING, $optionsStripAll);
if (empty($input_studentLunchSpecialReqs)) {
    $input_studentLunchSpecialReqs = '';
}// end if statement

if (isset($errorMessage)) {
    if (!empty($errorMessage)) {
        showPrettyError($errorMessage, 'error', true);
    }
}

/*
 * Encode inputs to protect from sql injection
 */
require "{$str_appLocation}lib/dbConnect.php";

$studentToken = mysqli_real_escape_string($dbConnectionObject, $studentToken);
$input_studentNationality = mysqli_real_escape_string($dbConnectionObject, $input_studentNationality);
$input_studentResidence = mysqli_real_escape_string($dbConnectionObject, $input_studentResidence);
$studentProgram = mysqli_real_escape_string($dbConnectionObject, $studentProgram);
$input_studentPassport = mysqli_real_escape_string($dbConnectionObject, $input_studentPassport);
$input_studentAllergies = mysqli_real_escape_string($dbConnectionObject, $input_studentAllergies);
$input_studentLunch = mysqli_real_escape_string($dbConnectionObject, $input_studentLunch);
$input_studentLunchSpecialReqs = mysqli_real_escape_string($dbConnectionObject, $input_studentLunchSpecialReqs);
$input_studentVisaApproved = mysqli_real_escape_string($dbConnectionObject, $input_studentVisaApproved);
$input_studentArrivingDate = mysqli_real_escape_string($dbConnectionObject, $input_studentArrivingDate);

/*
 * Verify the student token is real, exists in Db and pull student info
 */
// Query the database for the student's data
$str_query_getStudentData = "select * from `students` where `token_students`='$studentToken';";
$obj_query_getStudentDataResult = mysqli_query($dbConnectionObject, $str_query_getStudentData);

// If an error occurred and the app is in beta then show the error
if (mysqli_errno($dbConnectionObject)) {
    if($bln_isBeta) {
        echoToConsole('MySQLi error while selecting student: ' . mysqli_error($dbConnectionObject), true);
    }// Debug output

    // An unhandled error occurred while querying the database
    require "{$str_appLocation}views/errorMessages_en_queryFailures.php";
    showPrettyError($errorMessage, 'error', true);
} else if (mysqli_num_rows($obj_query_getStudentDataResult) != 1) {
    // The user was not found in the database
    require "{$str_appLocation}views/errorMessages_en_studentDidNotExist.php";
    showPrettyError($errorMessage, 'error', true);
} else {
     // Create an associative array from the result object
    $ary_query_getStudentDataResult = mysqli_fetch_assoc($obj_query_getStudentDataResult);

    // Free result object (Have an array now)
    mysqli_free_result($obj_query_getStudentDataResult);
    unset($obj_query_getStudentDataResult);

    if (array_key_exists($studentProgram, $ary_studentGroups_a)) {
        $studentGroup = "a";
    } else if (array_key_exists($studentProgram, $ary_studentGroups_b)) {
        $studentGroup = "b";
    }

    // Free result array (Have the program code now)
    unset($ary_query_getStudentDataResult);

    // Change the datepicker format
    $ary_studentArivingDate = explode('/', $input_studentArrivingDate);
    $str_arrivingDateMonth = $ary_studentArivingDate[0];
    $str_arrivingDateDay = $ary_studentArivingDate[1];
    $str_arrivingDateYear = $ary_studentArivingDate[2];
    unset($ary_studentArivingDate);
    
    $input_studentArrivingDate = $str_arrivingDateYear
        . '-' . $str_arrivingDateMonth
        . '-' . $str_arrivingDateDay;
    
    $str_query_updateStudentData = 'update `students` SET `regDate_students`=NOW()'
        . ', `isVisaApproved_students`=\'' . $input_studentVisaApproved . '\''
        . ', `hasPassport_students`=\'' . $input_studentPassport . '\''
        . ', `countryOfResidence_students`=\'' . $input_studentResidence . '\''
        . ', `countryOfNationality_students`=\'' . $input_studentNationality . '\''
        . ', `lunch_students`=\'' . $input_studentLunch . '\''
        . ', `lunch_alergies_students`=\'' . $input_studentAllergies . '\''
        . ', `lunch_specialReqs_students`=\'' . $input_studentLunchSpecialReqs . '\''
        . ', `dateArrivingToCanada_students`=\'' . $input_studentArrivingDate . '\''
        . ', `program_students`=\'' . $studentProgram . '\''
        . ', `group_students`=\'' . $studentGroup . '\''
        . ' where `token_students`=\'' . $studentToken . '\';';

    $obj_query_updateStudentDataResult = mysqli_query($dbConnectionObject, $str_query_updateStudentData);

    // If an error occurred and the app is in beta then show the error
    if (mysqli_errno($dbConnectionObject)) {
        echoToConsole('MySQLi error while updating student: ' . mysqli_error($dbConnectionObject), true);

        // Mysqli returned an error updating student record. Exiting...
        require "{$str_appLocation}views/errorMessages_en_failedToUpdateStudent.php";
        showPrettyError($errorMessage, 'error', true);
    } else {
        // Free any results
        if (is_object($obj_query_updateStudentDataResult)) {
            mysqli_free_result($obj_query_updateStudentDataResult);
        }

        //Disconnect from the Database
        mysqli_close($dbConnectionObject);
        
        // Redirect back to the main index and enter stage 2
        header('Location: ' . "{$str_appURL}index.php?stkn={$studentToken}&stage=2", true, 301);
    }// end if statement
}// end if statement
?>
        </div>
<?php
include "{$str_appLocation}views/georgianFooter.php";
?>
    </body>
</html>
<?php
die();

// Flush (send) the output buffer's contents and close it
ob_end_flush();
?>