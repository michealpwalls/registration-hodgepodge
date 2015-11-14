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

if(!isset($bln_subComponent)) {
    // If $str_appLocation is not set, environment.php must not
    // have been run.
    if( !isset( $str_appLocation ) ) {
        $str_basePath = (string) realpath(dirname(__FILE__));
        $ary_basePath = (array) explode('/', $str_basePath);

        // Rebuild the path without the last 2 items. Last items on path
        // is the location of *this* script, in the ./admin/setup subdirectory.
        $str_basePath = '';
        for($i = (int) 0; $i < count($ary_basePath)-2; $i++) {
            $str_basePath .= $ary_basePath[$i] . '/';
        }// end for loop

        // Include the missing data files and continue normal flow
        require_once $str_basePath . 'data/environment.php';

        // Destroy the temporary variables
        unset($ary_basePath, $str_basePath);
    }// end if statement

    require_once $str_appLocation . 'lib/prettyErrors.php';
    require_once $str_appLocation . 'lib/logging.php';
    
    require $str_appLocation . 'views/errorMessages_en_directComponentAccess.php';
    showPrettyError($errorMessage, 'error', false);
}// end if statement

require_once $str_appLocation . 'data/db.php';
require $str_appLocation . 'lib/dbConnect.php';

/**
 * REGISTERED table
 */
echo "<br><br><p>Renaming and adding columns to the <strong>register</strong> table...</p>\n" ;

$alterRegisterQuery = "ALTER TABLE registered
        CHANGE amworkshop tue_amworkshop INT(11) DEFAULT 100,
        CHANGE pmworkshop tue_pmworkshop INT(11) DEFAULT 101,
        ADD mon_amworkshop INT(11) DEFAULT 100 AFTER userid,
        ADD mon_pmworkshop INT(11) DEFAULT 101 AFTER mon_amworkshop,
        ADD wed_amworkshop INT(11) DEFAULT 100 AFTER tue_pmworkshop,
        ADD wed_pmworkshop INT(11) DEFAULT 101 AFTER wed_amworkshop,
        ADD wed_pmworkshop2 INT(11) DEFAULT 101 AFTER wed_pmworkshop,
        ADD thur_amworkshop INT(11) DEFAULT 100 AFTER wed_pmworkshop,
        ADD thur_pmworkshop INT(11) DEFAULT 101 AFTER thur_amworkshop;";

$alterRegisterResult = mysqli_query( $dbConnectionObject, $alterRegisterQuery );

if( $alterRegisterResult ) {
    echo "<p style=\"color: green;\">Finished updating the <strong>registered</strong> table.</p>\n";
} else {
    echo "<p style=\"color: red;\">An internal DB error occured while updating Registered structure! Fiddle sticks!</p>\n";
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . "</p><br>\n";
}// end if statement

//Free result set
if( is_object( $alterRegisterQuery ) ) {
    mysqli_free_result( $alterRegisterQuery );
}// end if statement

/**
 * WORKSHOPS table
 */
$updateWorkshopsQuery = "ALTER TABLE workshops
        ADD day CHAR(3),
        ADD release_date DATETIME,
        CHANGE room room VARCHAR(150),
        CHANGE presenter presenter VARCHAR(150),
        ADD start-time VARCHAR(5);";

$updateWorkshopsResult = mysqli_query( $dbConnectionObject, $updateWorkshopsQuery );

if( $updateWorkshopsResult ) {
    $workshopUpdateCount = mysqli_affected_rows( $dbConnectionObject );

    echo "<p style=\"color: green;\">Successfully updated {$workshopUpdateCount}</p>\n";
} else {
    echo "<p style=\"color: red;\">An internal DB error occured while updating Workshop structure! Fiddle sticks!</p>\n";
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . "</p><br>\n";
}// end if statement

//Free result set
if( is_object( $updateWorkshopsResult ) ) {
    mysqli_free_result( $updateWorkshopsResult );
}// end if statement

/**
 * USERS table
 */
echo "<br><br><p>Adding 5 new columns, and renaming lunch in the <strong>users</strong> table...</p>\n";

$alterUsersQuery = "ALTER TABLE users
        CHANGE lunch tue_lunch VARCHAR(3) DEFAULT 'no',
        ADD fotc VARCHAR(3) DEFAULT 'no' AFTER userid,
        ADD mon_lunch VARCHAR(3) DEFAULT 'no' AFTER techcafe,
        ADD mon_keynote VARCHAR(3) DEFAULT 'no' AFTER tue_lunch,
        ADD tue_keynote VARCHAR(3) DEFAULT 'no' AFTER mon_keynote,
        ADD wed_keynote VARCHAR(3) DEFAULT 'no' AFTER tue_keynote,
        ADD thur_keynote VARCHAR(3) DEFAULT 'no' AFTER wed_keynote,
        ADD extraordinary VARCHAR(3) DEFAULT 'no';";

$alterUsersResult = mysqli_query( $dbConnectionObject, $alterUsersQuery );

if( $alterUsersResult ) {
    echo "<p style=\"color: green;\">Finished updating the <strong>users</strong> table.</p>\n";
} else {
    echo "<p style=\"color: red;\">An internal DB error occured while updating Users structure! Fiddle sticks!</p>\n";
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . "</p><br>\n";
}// end if statement

//Free result set
if( is_object( $alterUsersResult ) ) {
    mysqli_free_result( $alterUsersResult );
}// end if statement

/**
 * KEYNOTES table
 */
echo "<br><br><p>Adding the <strong>keynotes</strong> table...</p>\n";

$addKeynotesQuery = "CREATE TABLE keynotes(
        `keynoteid` INT(3) AUTO_INCREMENT,
        `name` VARCHAR(50) NOT NULL,
        `description` VARCHAR(150) NOT NULL,
        `speaker` VARCHAR(25) NOT NULL,
        `time` VARCHAR(50) NOT NULL,
        `day` VARCHAR(3) NOT NULL,
        `location` VARCHAR(100) NOT NULL,
        `seats` INT(4) NOT NULL,
        `max_seats` INT(4) NOT NULL,
        PRIMARY KEY (`keynoteid`)
);";

$addKeynotesResult = mysqli_query( $dbConnectionObject, $addKeynotesQuery );

if( $addKeynotesResult ) {
    echo "<p style=\"color: green;\">Finished adding the <strong>keynotes</strong> table.</p>\n";
} else {
    echo "<p style=\"color: red;\">An internal DB error occured while adding Keynote table! Fiddle sticks!</p>\n";
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . "</p><br>\n";
}// end if statement

//Free result set
if( is_object( $addKeynotesResult ) ) {
    mysqli_free_result( $addKeynotesResult );
}// end if statement

/**
 * FOTCATTENDEES table
 */
echo "<br><br><p>Adding the <strong>fotcAttendees</strong> table...</p>\n";

$addFotcQuery = "CREATE TABLE fotcAttendees(
        `userid` INT(11) NOT NULL,
        `choice` VARCHAR(3) NOT NULL,
        PRIMARY KEY (`userid`) );";

$addFotcResult = mysqli_query( $dbConnectionObject, $addFotcQuery );

if( $addFotcResult ) {
    echo "<p style=\"color: green;\">Finished adding the <strong>FoTC</strong> table.</p>\n";
} else {
    echo "<p style=\"color: red;\">An internal DB error occured while adding the fotcAttendees table! Fiddle sticks!</p>\n";
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . "</p><br>\n";
}// end if statement

//Free result set
if( is_object( $addFotcResult ) ) {
    mysqli_free_result( $addFotcResult );
}// end if statement

// Close the database connection
mysqli_close( $dbConnectionObject );
?>
    </body>
</html>