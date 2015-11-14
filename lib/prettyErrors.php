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

// If $str_appLocation is not set, environment.php must not
// have been run.
if (!isset($str_appLocation)) {
    $str_basePath = (string) realpath(dirname(__FILE__));
    $ary_basePath = (array) explode('/', $str_basePath);

    // Rebuild the path without the last item. Last item of path
    // is the location of *this* script, in the ./lib subdirectory.
    $str_basePath = '';
    for ($i = (int) 0; $i < count($ary_basePath)-1; $i++) {
        $str_basePath .= $ary_basePath[$i] . '/';
    }// end for loop

    // Include the missing data files and continue normal flow
    require "{$str_basePath}data/environment.php";

    // Destroy the temporary variables
    unset($ary_basePath, $str_basePath);
}// end if statement

function showPrettyError($str_messageIn,$str_errorLevel = 'info',$bln_isAlreadyWrapped = false,$str_titleIn = 'An error occurred!') {
    global $str_supportEmail,$str_appName,$str_appURL,
        $str_appLocation,$bln_isConference;

    if ($str_errorLevel == 'info' && $str_titleIn == 'An error occurred!') {
        $str_titleIn = 'Information';
    }
    
    if($bln_isAlreadyWrapped === false) {
        include "{$str_appLocation}views/headToBody.php";
        include "{$str_appLocation}views/georgianHeader.php";
        echo '<div class="main ui-corner-bottom">\n';

        if($bln_isConference) {
            include "{$str_appLocation}pdweek.php";
        }// end if statement
    }// end if statement

    echo "<div class=\"ui-widget\"><div class=\"ui-state-{$str_errorLevel} ui-corner-all\" style=\"padding: 0 .7em;\">\n"
        . "<h3>{$str_titleIn}</h3>\n"
        . "<p>{$str_messageIn}</p>\n"
        . "</div><!-- ui-state-error --></div><!-- ui-widget -->\n";

    // If the error level is "error" then close any db connections,
    // close off the main html tags and stop execution of the app.
    if($str_errorLevel == "error") {
        // If we're connected to a database, disconnect
        if(isset($dbConnectionObject)) {
            //Are we connected?
            if(is_object($dbConnectionObject)) {
                mysqli_close($dbConnectionObject);
            }// end if statement
            unset($dbConnectionObject);
        }// end if statement

        echo "</div><!-- main -->\n";
        include "{$str_appLocation}views/georgianFooter.php";
        echo "</body></html>\n";
        
        // If output buffering is enabled, flush the buffer
        if (ob_get_level() > 0) {
            ob_end_flush();
        }
        
        // Stop execution of script with status code other than 0 to show failure.
        exit(1);
    }// end if statement
}// end showPrettyError function