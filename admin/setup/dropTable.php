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
    require "../data/environment.php";
    require "{$str_appLocation}lib/prettyErrors.php";
    require "{$str_appLocation}lib/logging.php";
    require "{$str_appLocation}views/errorMessages_en_directComponentAccess.php";

    showPrettyError($errorMessage, 'error', true, 'Direct Component Access');
}// If ADMTKN not defined

// HTTP GET challenge
if( !isset( $_GET['target'] ) ) {
    require $str_appLocation . 'views/errorMessages_en_missingParam_target';
    showPrettyError($errorMessage, 'error', true);
}//end if statement

echo "<p>Dropping the <strong>" . htmlentities( $_GET['target'] ) . "</strong> table...</p>\n";

require $str_appLocation . 'lib/dbConnect.php';

$str_deleteQuery = "drop table " . mysqli_real_escape_string($dbConnectionObject, $_GET['target']) . " cascade";
$obj_deleteResult = mysqli_query( $dbConnectionObject, $str_deleteQuery );

if($obj_deleteResult) {
    echo "<p style=\"color: green;\">Finished dropping the <strong>" . htmlentities($_GET['target']) . "</strong> table</p>\n";
} else {
    echo "<p style=\"color: red;\">There was an internal issue with the database.</p>\n";
    echo "<p style=\"color: red;\">The error was: " . mysqli_error($dbConnectionObject) . "</p>\n";
}

// Close the database connection
mysqli_close($dbConnectionObject);
unset($dbConnectionObject);
?>