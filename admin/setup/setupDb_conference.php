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
 * users table
 */
echo '<br><br><p>Creating the <strong>users</strong> table...</p>';

$createUserQuery = "CREATE TABLE `users` (
    `userid` int(11) not null auto_increment,
    `fotc` varchar(3) default 'no',
    `regdate` datetime,
    `firstname` varchar(40) not null,
    `lastname` varchar(40) not null,
    `email` varchar(60) not null,
    `department` varchar(60),
    `photo` varchar(3) not null default 'no',
    `techcafe` varchar(3) not null default 'no',
    `mon_lunch` varchar(3) default 'no',
    `tue_lunch` varchar(3) default 'no',
    `mon_keynote` varchar(3) default 'no',
    `wed_keynote` varchar(3) default 'no',
    `thur_keynote` varchar(3),
    `validate1` varchar(15) not null,
    `validate2` varchar(15),
    `registered` varchar(3) not null default 'no',
    `workshops` int(11),
    `role` varchar(3),
    `beta` int(11),
    `waitlist` varchar(3),
    `otherdept` varchar(60),
    `review` varchar(3) default 'no',
    `username` varchar(20) not null,
    `extension` varchar(20),
    `extraordinary` varchar(3) default 'no',
    `vegetarian` varchar(3) default 'no',
    PRIMARY KEY (`userid`)
);";

echo 'Querying the database';

$createUserResult = mysqli_query( $dbConnectionObject, $createUserQuery );

if( $createUserResult ) {
    echo '<p style="color: green;">Finished creating the <strong>users</strong> table.</p>';
} else {
    echo '<p style="color: red;">An internal DB error occured while creating users table! Fiddle sticks!</p>';
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . '</p><br>';
}// end if statement

//Free query object
if( is_object( $createUserQuery ) ) {
    mysqli_free_result( $createUserQuery );
}// end if statement

//Free result set object
if( is_object( $createUserResult ) ) {
    mysqli_free_result( $createUserResult );
}// end if statement

/**
* registered table
*/
echo '<br><br><p>Creating the <strong>registered</strong> table...</p>';

$createRegisteredQuery = "CREATE TABLE `registered` (
    `regid` int(11) 
    `userid` int(11) not null,
    `mon_amworkshop` int(11) default '100',
    `mon_pmworkshop` int(11) default '101',
    `tue_amworkshop` int(11) default '100',
    `tue_pmworkshop` int(11) default '101',
    `wed_amworkshop` int(11) default '100',
    `wed_pmworkshop` int(11) default '101',
    `thur_amworkshop` int(11) default '100',
    `thur_pmworkshop` int(11) default '101',
    `wed_pmworkshop2` int(11) default '101',
    `time` varchar(3),
    `regdate` datetime,
    PRIMARY KEY (`userid`),
    
);";

echo 'Querying the database';

$createRegisteredResult = mysqli_query( $dbConnectionObject, $createRegisteredQuery );

if( $createRegisteredResult ) {
    echo '<p style="color: green;">Finished creating the <strong>registered</strong> table.</p>';
} else {
    echo '<p style="color: red;">An internal DB error occured while creating registered table! Fiddle sticks!</p>';
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . '</p><br>';
}// end if statement

//Free query object
if( is_object( $createRegisteredQuery ) ) {
    mysqli_free_result( $createRegisteredQuery );
}// end if statement

//Free result set object
if( is_object( $createRegisteredResult ) ) {
    mysqli_free_result( $createRegisteredResult );
}// end if statement

/**
* keynotes table
*/
echo '<br><br><p>Creating the <strong>keynotes</strong> table...</p>';

$createKeynotesQuery = "CREATE TABLE `keynotes` (
    `keynoteid` int(3) not null auto_increment,
    `name` varchar(150) not null,
    `description` varchar(150) not null,
    `speaker` varchar(50) not null,
    `time` varchar(100) not null,
    `day` varchar(3) not null,
    `location` varchar(300) not null,
    `seats` int(4) not null,
    `max_seats` int(4) not null,
    PRIMARY KEY (`keynoteid`)
);";

echo 'Querying the database';

$createKeynotesResult = mysqli_query( $dbConnectionObject, $createKeynotesQuery );

if( $createKeynotesResult ) {
    echo '<p style="color: green;">Finished creating the <strong>keynotes</strong> table.</p>';
} else {
    echo '<p style="color: red;">An internal DB error occured while creating keynotes table! Fiddle sticks!</p>';
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . '</p><br>';
}// end if statement

//Free query object
if( is_object( $createKeynotesQuery ) ) {
    mysqli_free_result( $createKeynotesQuery );
}// end if statement

//Free result set object
if( is_object( $createKeynotesResult ) ) {
    mysqli_free_result( $createKeynotesResult );
}// end if statement

/**
* fotcAttendees table
*/
echo '<br><br><p>Creating the <strong>fotcAttendees</strong> table...</p>';

$createFotcAttendeesQuery = "CREATE TABLE `fotcAttendees` (
    `userid` int(11) not null,
    `choice` varchar(3) not null,
    PRIMARY KEY (`userid`)
);";

echo 'Querying the database';

$createFotcAttendeesResult = mysqli_query( $dbConnectionObject, $createFotcAttendeesQuery );

if( $createFotcAttendeesResult ) {
    echo '<p style="color: green;">Finished creating the <strong>fotcAttendees</strong> table.</p>';
} else {
    echo '<p style="color: red;">An internal DB error occured while creating fotcAttendees table! Fiddle sticks!</p>';
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . '</p><br>';
}// end if statement

//Free query object
if( is_object( $createFotcAttendeesQuery ) ) {
    mysqli_free_result( $createFotcAttendeesQuery );
}// end if statement

//Free result set object
if( is_object( $createFotcAttendeesResult ) ) {
    mysqli_free_result( $createFotcAttendeesResult );
}// end if statement

/**
* waitlist table
*/
echo '<br><br><p>Creating the <strong>waitlist</strong> table...</p>';

$createWaitlistQuery = "CREATE TABLE `waitlist` (
    `id` int(11) unsigned not null auto_increment,
    `lastname` varchar(30),
    `firstname` varchar(30),
    `email` varchar(60),
    `validate2` varchar(30),
    `invited` varchar(3),
    PRIMARY KEY (`id`)
);";

echo 'Querying the database';

$createWaitlistResult = mysqli_query( $dbConnectionObject, $createWaitlistQuery );

if( $createWaitlistResult ) {
    echo '<p style="color: green;">Finished creating the <strong>waitlist</strong> table.</p>';
} else {
    echo '<p style="color: red;">An internal DB error occured while creating waitlist table! Fiddle sticks!</p>';
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . '</p><br>';
}// end if statement

//Free query object
if( is_object( $createWaitlistQuery ) ) {
    mysqli_free_result( $createWaitlistQuery );
}// end if statement

//Free result set object
if( is_object( $createWaitlistResult ) ) {
    mysqli_free_result( $createWaitlistResult );
}// end if statement

/**
* workshops table
*/
echo '<br><br><p>Creating the <strong>workshops</strong> table...</p>';

$createWorkshopsQuery = "CREATE TABLE `workshops` (
    `workshopid` int(11) not null auto_increment,
    `title` varchar(150) not null,
    `room` varchar(150),
    `seats` int(11),
    `description` text,
    `presenter` varchar(150),
    `bio` text,
    `time` varchar(3),
    `userid` int(11),
    `bseats` int(11),
    `day` char(3),
    `release_date` datetime,
    `start_time` varchar(11),
    PRIMARY KEY (`workshopid`)
);";

echo 'Querying the database';

$createWorkshopsResult = mysqli_query( $dbConnectionObject, $createWorkshopsQuery );

if( $createWorkshopsResult ) {
    echo '<p style="color: green;">Finished creating the <strong>workshops</strong> table.</p>';
} else {
    echo '<p style="color: red;">An internal DB error occured while creating workshops table! Fiddle sticks!</p>';
    echo '<p>The error was: ' . mysqli_error( $dbConnectionObject ) . '</p><br>';
}// end if statement

//Free query object
if( is_object( $createWorkshopsQuery ) ) {
    mysqli_free_result( $createWorkshopsQuery );
}// end if statement

//Free result set object
if( is_object( $createWorkshopsResult ) ) {
    mysqli_free_result( $createWorkshopsResult );
}// end if statement

echo '<p style="color: green;">Finished setting up the database schema! We\'re ready to run :)</p>';

// Close the database connection
mysqli_close( $dbConnectionObject );
?>
    </body>
</html>
