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

if( isset( $_POST['setupAction'] ) || isset( $_GET['setupAction'] ) ) {	// Are we performing an action?

    if(isset($_POST['setupAction'])) {
        $_GET['setupAction'] = $_POST['setupAction'];
    } else {
        $_POST['setupAction'] = $_GET['setupAction'];
    }// end if statement

    switch($_POST['setupAction']) {	// Which action to perform
        /**
         * Delete data from a Table
         */
        case "deleteDataFromTable":
            if(isset($_POST['deleteTarget'])) {
                //Set the 'target' variable for deleteDataFromTable.php
                $_GET['target'] = $_POST['deleteTarget'];

                require_once $str_appLocation . 'admin/setup/deleteDataFromTable.php';
            } else {
                echoToConsole( "Confirm choice was not set, displaying form", true );

                echo "<h3>Delete Records in a Table</h3>\n
                    <form method=\"post\" action=\"index.php?action=setup&admtkn={$adminTokenInput}\" onSubmit=\"return confirmDelete();\">\n
                    Select a table in the {$str_dbDb} database to empty:<br>\n
                    &nbsp;&nbsp;<select name=\"deleteTarget\" id=\"deleteTarget\">\n";
                
                require $str_appLocation . 'views/setup_en_dbTablesOptionList.php';
                
                echo "</select>\n
                    <input type=\"hidden\" name=\"setupAction\" value=\"deleteDataFromTable\" id=\"setupAction\">\n
                    <input type=\"submit\" value=\"submit\" id=\"deleteSubmit\">\n
                    </form>\n";
            }// end if statement
            break;
            
        /**
         * Drop a Table
         */
        case "dropTable":
            if(isset($_POST['dropTarget'])) {
                //Set the 'target' variable for delete.php
                $_GET['target'] = $_POST['dropTarget'];

                require_once $str_appLocation . 'admin/setup/dropTable.php';
            } else {
                echoToConsole( "Confirm choice was not set, displaying form", true );

                echo "<h3>Drop a Table</h3>\n
                    <form method=\"post\" action=\"index.php?action=setup&admtkn={$adminTokenInput}\" onSubmit=\"return confirmDelete();\">\n
                    Select a table in the {$str_dbDb} database to drop:<br>\n
                    &nbsp;&nbsp;<select name=\"dropTarget\" id=\"dropTarget\">\n";

                require $str_appLocation . 'views/setup_en_dbTablesOptionList.php';

                echo "</select>\n
                    <input type=\"hidden\" name=\"setupAction\" value=\"dropTable\" id=\"setupAction\">\n
                    <input type=\"submit\" value=\"submit\" id=\"deleteSubmit\">\n
                    </form>\n";
            }// end if statement
            break;

        /**
         * Reset Keynotes
         */
        case "reset-keynotes":

            if( !isset( $_GET['confirm'] ) ) {
                echoToConsole( "confirm choice was not set!", true );
                echo "<script>confirmReset();</script>\n";
            } else {

                if( $_GET['confirm'] == "yes" ) {
                    echoToConsole( "User chose Yes as confirm choice", true );
                    require_once $str_appLocation . "admin/setup/resetKeynotes.php";
                } else {
                    echoToConsole( "User chose No as confirm choice", true );
                }// end if statement

            }// end if statement

            break;

        /**
         * Load Keynotes
         */
        case "load-keynotes":
            if( !isset( $_GET['confirm'] ) ) {
                echoToConsole("confirm choice was not set!", true);
                echo "<script>confirmLoad(\"keynotes\");</script>\n";
            } else {
                if( $_GET['confirm'] == "yes" ) {
                    echoToConsole("User chose Yes as confirm choice", true);
                    require_once "{$str_appLocation}admin/setup/loadKeynotes.php";
                } else {
                    echoToConsole("User chose No as confirm choice", true);
                }// end if statement
            }// end if statement
            break;

        /**
         * Import Students
         */
        case "import-students":
            $str_targetDb = (string) 'students';
            $str_defaultStructure = (string) '(`georgianid_students`,`firstname_students`,`lastname_students`,`email_students`)';

            if($bln_isBeta) {
                echo "<h3>Running loadDbData.php with {$str_targetDb}"
                    . " set as the target Db.</h3>\n";
            }// Debug output

            require "{$str_appLocation}admin/setup/loadDbData.php";
            unset($str_targetDb, $str_defaultStructure);
            break;

        /**
         * Import Sessions
         */
        case "import-sessions":
            $str_targetDb = (string) 'sessions';
            $str_defaultStructure = (string) '(`number_sessions`,`mandatory_sessions`,`requirePassport_sessions`,`description_sessions`,`max_sessions`,`day_sessions`)';

            if($bln_isBeta) {
                echo "<h3>Running loadDbData.php with {$str_targetDb}"
                    . " set as the target Db.</h3>\n";
            }// Debug output
            
            require "{$str_appLocation}admin/setup/loadDbData.php";
            unset($str_targetDb, $str_defaultStructure);
            break;

        /**
         * Load Workshops
         */
        case "load-workshops":
            if($bln_isBeta) {
                echo "<h3>Running loadWorkshops.php</h3>\n";
            }// Debug output

            require_once "{$str_appLocation}admin/setup/loadWorkshops.php";
            break;

        /**
         * Setup Database Schema
         */
        case "setup-database":
            if($bln_isBeta) {
                echo "<h3>Running setupDatabase.php</h3>\n";
            }// Debug output

            require_once "{$str_appLocation}admin/setup/setupDatabase.php";
            break;

        /**
         * Default Action
         */
        default:
            //No default action
            break;
    }// end switch case

} else {
    // No action specified so display some options
    require $str_appLocation . 'views/index_en_setup.php';
}// end if statement
?>