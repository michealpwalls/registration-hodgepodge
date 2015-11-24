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
require "{$str_appLocation}lib/logging.php";
require "{$str_appLocation}lib/prettyErrors.php";
require "{$str_appLocation}views/headToBody.php";
require "{$str_appLocation}views/georgianHeader.php";
require "{$str_appLocation}lib/students.php";
require "{$str_appLocation}lib/sessions.php";
require "{$str_appLocation}lib/registrations.php";
?>
        <div class="main">
<?php
/*
 * Check for and gather inputs
 */
$tokenOptions = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
$studentToken = filter_input(INPUT_POST, 'stkn', FILTER_SANITIZE_STRING, $tokenOptions);
$sessionTwoChoice = filter_input(INPUT_POST, 'day2-sessionId', FILTER_SANITIZE_NUMBER_INT);

if (empty($studentToken)) {
    require "{$str_appLocation}views/errorMessages_en_missingStudentToken.php";
    showPrettyError($errorMessage, 'error', true);
}// end if statement

if (empty($sessionTwoChoice)) {
    require "{$str_appLocation}views/errorMessages_en_missingSessionTwo.php";
    showPrettyError($errorMessage, 'error', true);
}// end if statement

/*
 * Encode inputs to protect from sql injection
 */
require "{$str_appLocation}lib/dbConnect.php";

$studentToken = mysqli_real_escape_string($dbConnectionObject, $studentToken);
$sessionTwoChoice = mysqli_real_escape_string($dbConnectionObject, $sessionTwoChoice);

/*
 * Verify the inputs exist. If student exists, store their Id
 */

$studentId = doesStudentExistByToken($dbConnectionObject, $studentToken);

if ($studentId === false) {
    require "{$str_appLocation}views/errorMessages_en_studentDidNotExist.php";
    showPrettyError($errorMessage, 'error', true);
}

if (!doesSessionExist($dbConnectionObject, $sessionTwoChoice)) {
    require "{$str_appLocation}views/errorMessages_en_sessionTwoNotFound.php";
    showPrettyError($errorMessage, 'error', true);
}

/*
 * Make sure there is an available spot in the session.
 */
if (seatsRemaining($sessionTwoChoice, $dbConnectionObject) < 1) {
    require "{$str_appLocation}views/errorMessages_en_sessionFull.php";
    showPrettyError($errorMessage, 'error', true, $errorTitle);
}

/*
 * Clear all the student's registrations for day 2
 */
clearRegistrations($dbConnectionObject, $studentId, 2);

/*
 * Register the student in the session
 */
if (!registerInSession($dbConnectionObject, $sessionTwoChoice, $studentId, 2)) {
    require "{$str_appLocation}views/errorMessages_en_failedToAssignSession.php";
    showPrettyError($errorMessage, 'error', true);
}

mysqli_close($dbConnectionObject);
unset($dbConnectionObject);

/*
 * Redirect to the student timetable
 */
header("Location: {$str_appURL}studentTimetable.php?stkn={$studentToken}", true, 301);

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