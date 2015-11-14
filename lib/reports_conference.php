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

/**
 * The reportsConference_specialUsers function display a report on the "special"
 * users, including TPCs, keynote speakers, session presenters and award
 * recipients.
 */
function reportsConference_specialUsers() {
    //Reference the global variables
    global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

   $array_keynoteSpeakers = (array) array(
        "Michael Clemons",
        "MaryLynn West-Moynes"
    );// end keynoteSpeakers array

   $array_awardRecipients = (array) array(
        "Katherine Wallis",
        "Tamara Fisher-Cullen",
        "Scott McCrindle",
        "Avinash Thadani",
        "Anne-Marie McAllister",
        "Suzie Addison-Toor",
        "Josh Barath",
        "Jill Dunlop",
        "Karen Bell"
    );// end awardRecipients array

   $array_teachPractCerts = (array) array(
        "Clement Bamikole",
        "Christine Fenech",
        "Vali Stone",
        "Mark Dorsey",
        "John McCluskey",
        "Rich Freeman",
        "Meredith Lowe",
        "Carol Meissner",
        "Anne Coulter",
        "Toni Cano",
        "Coralee Young",
        "Gay Ainsworth",
        "Catherine Vellinga",
        "Debra White",
        "Samantha Southorn",
        "Lisa Buchanan",
        "Emily Brett",
        "Allison Papenhuyzen",
        "Dominika Farrelly",
        "Dan Moreau",
        "Sarah Hunter",
        "Sarah Shellswell",
        "Josh Barath",
        "Jill Dunlop",
        "Lance Triskle",
        "Karen Burns"
    );// end teachPractCerts array

   $array_deansAssociateDeans = (array) array(
        "Cassandra Thompson",
        "Marie-Noelle Bonicalzi",
        "Barb Watts",
        "Sean Madorin",
        "Nina Koniuch",
        "Maryann Fifield",
        "Gabrielle Koopmans",
        "Kevin Weaver",
        "Jason Hunter",
        "Dan Brooks",
        "Aaron Gouin",
        "Baldev Pooni",
        "Catherine Drea",
        "Heather Raikou",
        "Marion Lougas",
        "Bonnie DeWitt",
        "Lynn Hynd",
        "Mary O'Farrell-Bowers",
        "Monique Vaillancourt",
        "Mac Greaves",
        "Angela Lockridge",
        "Lisa Banks",
        "MaryLynn West-Moynes"
    );// end deansAssociateDeans array

   $array_customUserList_amy = (array) array(
        'Steve Miller',
        'Jennifer Armstrong-Lehman',
        'Carol McLuhan',
        'Vanessa Doering',
        'Adam Leber',
        'Josiah Neice',
        'Catherine Hildebrandt',
        'Toni Cano',
        'Jaret Wright',
        'Jennifer Varcoe',
        'Stephanie McNamara',
        'Alanda Theriault',
        'Ross Bigelow',
        'Norma Hart',
        'Robert Northey',
        'Kimberly Thomas',
        'Joanne Fowlie',
        'Susan Hosein',
        'Cathy Neuss',
        'Kristen Borlan',
        'Jill Hynes',
        'Avinash Thadani',
        'Iain Robertson',
        'Amy Hutchinson',
        'Ashley Taylor'
    );// Amy's Custom List

    //Combine the Arrays
    $array_specialUsers = array_merge( $array_keynoteSpeakers, $array_awardRecipients, $array_teachPractCerts, $array_deansAssociateDeans, $array_customUserList_amy );

    //Delete duplicate values
    $array_specialUsers = array_unique( $array_specialUsers );

    //Cleanly Recreate the array
    $array_specialUsers = array_values( $array_specialUsers );

    //Connect to the database
    $dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

    //Set the character set, for use with mysqli_real_escape_string
    mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

    //Initialize the string to hold the query
    $str_specialUsers_query = (string) "SELECT DISTINCT CONCAT(CONCAT(u.firstname,' '),u.lastname) AS 'Special Users' FROM users u JOIN (SELECT DISTINCT SUBSTRING_INDEX(presenter,' ',2) AS presenterNames FROM workshops) presenters ON CONCAT(CONCAT(u.firstname,' '),u.lastname)=presenters.presenterNames OR CONCAT(CONCAT(u.firstname,' '),u.lastname) IN";

    $str_specialUsers_query .= "(";

    for( $i = 0; $i < count($array_specialUsers); $i++ ) {
        $str_specialUsers_query .= "'" . addslashes( $array_specialUsers[$i] ) . "'";

        if( $i != count($array_specialUsers)-1 ) {
            $str_specialUsers_query .= ',';
        }// end if statement

    }// end foreach loop

    $str_specialUsers_query .= ");";

    //Query the Database for list of "special users" registered in the system
    $mixed_specialUsers_result = mysqli_query( $dbConnectionObject, $str_specialUsers_query );

    //Disconnect from the Database
    mysqli_close( $dbConnectionObject );

    //Declare and initialize some accumulators
    $int_specialUsersFound = (int) 0;
    $int_totalSpecialUsers = (int) count( $array_specialUsers );

    if( is_object( $mixed_specialUsers_result ) ) {
        $int_specialUsersFound = mysqli_num_rows( $mixed_specialUsers_result );

        //Free memory associated with the ResultSet
        mysqli_free_result( $mixed_specialUsers_result );
    }//end if statemenet

    //
    //OUTPUT
    //
    echo "Number of <q>Special Users</q> registered in the system: <strong>{$int_specialUsersFound}</strong><br><br>\n";

}// end reportsConference_specialUsers() function

/**
 * The reportsConference_twitter function displays a small report on the Twitter
 * information related to the conference.
 */
function reportsConference_twitter() {
    global $str_confHashtag;

    $array_twitterTeam 	= (array) array(
        "Alissa Bigelow",
        "Ross Bigelow",
        "Anne-Marie McAllister",
        "Michael Spencer",
        "Terri Strawn",
        "Kim Stubbs",
        "Rob Theriault"
    );// end twitterTeam array

   $str_twitterMaster	= (string) "Iain Robertson";

   //OUTPUT
   echo "<strong>Twitter</strong><br>\n
        Hashtag: {$str_confHashtag}<br>\n
        Twitter Master: {$str_twitterMaster}<br>\n
        Twitter Team:<br>\n";

    foreach( $array_twitterTeam as $teamMember ) {
        echo "&nbsp;&nbsp;{$teamMember}<br>\n";
    }// end foreach loop

    echo "<br>\n";
}// end reportsConference_twitter() function

/**
 * The reportsConference_showReportOverview function generates and displays the
 * the initial report on the registration system.
 * 
 * TODO: Break this up into smaller chunks. Way too large!
 */
function reportsConference_showReportOverview() {
    //Include shared libraries
    require_once '../lib/countworkshops.php';
    require_once '../lib/fotcSeats.php';

    //Reference global variables
    global $int_fotcMax, $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

    //Connect to the Database
    $dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

    //Set the character set, for use with mysqli_real_escape_string
    mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

    //Query database for user information
    $usersQuery = "select users.lastname as lname, users.firstname as fname, users.department as department, users.otherdept as otherdept, mon_lunch, vegetarian, fotc, review, registered, techcafe from users";
    $usersResultObject = mysqli_query( $dbConnectionObject, $usersQuery );
    $total = mysqli_num_rows( $usersResultObject );

    $n = 1;
    $cafeCount = $reviewCount = $mon_lunchno = $vegetarians = $fotcCount = $regyes = $regyn = 0;
    $lastten = array();

    while( $row = mysqli_fetch_array($usersResultObject) ) {
        extract($row);

        if ($n > ($total - 10)) {
            if ($department == "None" || $department == "Other-" || $department == "Choose your department") {
                $department = "No department selected";
            } else {
                if ($department == "Other") {
                    $department = htmlentities( $otherdept );
                }// end if statement
            }// end if statement

            $department = mysqli_real_escape_string( $dbConnectionObject, $department );
            $lname = mysqli_real_escape_string( $dbConnectionObject, $lname );
            $fname = mysqli_real_escape_string( $dbConnectionObject, $fname );
            $lastten[$total - $n] = $lname .", " . $fname;
        }// end if statement

        if( $techcafe == "yes" ) {
            $cafeCount++;
        }// end if statement

        if( $review == "yes" ) {
            $reviewCount++;
        }// end if statement

        if ($mon_lunch == "yes") {
            $mon_lunchno += 1;
        }// end if statement

        if( $vegetarian == "yes" ) {
            $vegetarians += 1;
        }// end if statement

        if ($fotc == "yes") {
            $fotcCount += 1;
        }// end if statement

        if ($registered == "yes") {
            $regyes += 1;
        }// end if statement

        if ($fotc == "wl") {
            $regyn += 1;	// FoTC Waiting List counter
        }// end fotc waiting list count

        $int_fotcSeatsRemaining = (int) fotcSeatsRemaining();
        $n+=1;
    }// end while loop

    //Free the memory associated with the result object
    if( is_object( $usersResultObject ) ) {
        mysqli_free_result( $usersResultObject );
    }// end if statement
    unset( $usersResultObject );

    //Get the Keynote information
    $keynoteQuery = (string) "SELECT seats, max_seats, day FROM keynotes;";
    $keynoteResultObject = mysqli_query( $dbConnectionObject, $keynoteQuery );
    $keynoteAttendees = (array) Array();

    while( $row = mysqli_fetch_array( $keynoteResultObject ) ) {
        switch( $row['day'] ) {
            case "mon":
                $keynoteAttendees['monday'] = $row['max_seats'] - $row['seats'];
                break;
            case "tue":
                $keynoteAttendees['tuesday'] = $row['max_seats'] - $row['seats'];
                break;
            case "wed":
                $keynoteAttendees['wednesday'] = $row['max_seats'] - $row['seats'];
                break;
            case "thu":
                $keynoteAttendees['thursday'] = $row['max_seats'] - $row['seats'];
                break;
            default:
                //Nothing to do
                break;
        }// end switch case statement
     }// end while loop

    //Free the memory associated with the result object
    if( is_object( $keynoteResultObject ) ) {
        mysqli_free_result( $keynoteResultObject );
    }//end if statement
    unset( $keynoteResultObject );

    //Disconnect from the Database
    mysqli_close( $dbConnectionObject );
    unset( $dbConnectionObject );

    /**
     * Number of workshops
     */
    $int_confSessions = (int) countAllSessions();

    require_once '../lib/releaseDate.php';

    /**
     * Output
     */
    echo "<h3>PDWeek Report</h3>\n";
    echo " <strong class=\"reportTitle\">Users</strong><br>\n
        In the System: <strong>{$total}</strong><br>\n
        Reviewed their Profiles: <strong>{$reviewCount}</strong><br>\n
        Registered for a Session: <strong>{$regyes}</strong><br><br>\n";

    echo "<strong class=\"reportTitle\">Monday</strong><br>\n
        Lunches: <strong>{$mon_lunchno}</strong> (<strong>{$vegetarians}</strong> Vegetarians)<br>\n";
    if( isset( $keynoteAttendees['monday'] ) ) {
        echo "Keynote Attendees: <strong>{$keynoteAttendees['monday']}</strong>\n";
    }// end if statement
    $int_mondayDaysRemaining = daysRemaining('mon');
    if($int_mondayDaysRemaining == 0) {
        echo "<br>Users registered for a session: <strong>" . countSessionRegistrants( 'mon' ) . "</strong><br><br>\n";
    } else {
        echo "<br>Sessions will not be released for another <strong>{$int_mondayDaysRemaining} days</strong><br><br>\n";
    }// end if statement

    echo "<strong class=\"reportTitle\">Tuesday</strong> (Focus on Teaching Conference)<br>\n
            Maximum Seats allotted for the Conference: <strong>{$int_fotcMax}</strong><br>\n
            Users attending the Conference: <strong>{$fotcCount}</strong> (+<strong>{$regyn}</strong> Waitlisted with <strong>{$int_fotcSeatsRemaining} Seats</strong> remaining)<br>\n
            <span class=\"left-margin\"><em><strong>Please note </strong> the Conference includes Keynote, TechCafe, Human Library, Lunch and a Library Commons Open House</em></span><br>\n";
    $int_tuesdayDaysRemaining = daysRemaining('tue');
    if($int_tuesdayDaysRemaining == 0) {
        echo "Users registered for a session: <strong>" . countSessionRegistrants( 'tue' ) . "</strong><br><br>\n";
    } else {
        echo "Sessions will not be released for another <strong>{$int_tuesdayDaysRemaining} days</strong><br><br>\n";
    }// end if statement

    echo "<strong class=\"reportTitle\">Wednesday</strong><br>\n";
    if( isset( $keynoteAttendees['wednesday'] ) ) {
        echo( "Keynote Attendees: <strong>{$keynoteAttendees['wednesday']}</strong><br>\n" );
    }// end if statement
    $int_wednesdayDaysRemaining = daysRemaining('wed');
    if($int_wednesdayDaysRemaining == 0) {
        echo "Users registered for a session: <strong>" . countSessionRegistrants( 'wed' ) . "</strong><br><br>\n";
    } else {
        echo "Sessions will not be released for another <strong>{$int_wednesdayDaysRemaining} days</strong><br><br>\n";
    }// end if statement

    echo "<strong class=\"reportTitle\">Thursday</strong><br>\n";
    if( isset( $keynoteAttendees['thursday'] ) ) {
        echo( "College-wide Update Attendees: <strong>{$keynoteAttendees['thursday']}</strong>\n" );
    }// end if statement
    $int_thursdayDaysRemaining = daysRemaining('thu');
    if($int_thursdayDaysRemaining == 0) {
        echo "<br>Users registered for a session: <strong>" . countSessionRegistrants( 'thu' ) . "</strong><br><br>\n";
    } else {
        echo "<br>Sessions will not be released for another <strong>{$int_thursdayDaysRemaining} days</strong><br><br>\n";
    }// end if statement

    echo "<strong class=\"reportTitle\">Sessions</strong><br>\n
            Total number of Sessions: {$int_confSessions}<br>\n
            Total number of users registered Sessions: <strong>{$regyes}</strong><br>\n
            <span class=\"left-margin\">Users registered for Monday sessions: <strong>" . countSessionRegistrants( 'mon' ) . "</strong></span><br>\n
            <span class=\"left-margin\">Users registered for Tuesday sessions: <strong>" . countSessionRegistrants( 'tue' ) . "</strong></span><br>\n
            <span class=\"left-margin\">Users registered for Wednesday sessions: <strong>" . countSessionRegistrants( 'wed' ) . "</strong></span><br>\n
            <span class=\"left-margin\">Users registered for Thursday sessions: <strong>" . countSessionRegistrants( 'thu' ) . "</strong></span><br><br>\n";

    //Special Users
    echo "<strong class=\"reportTitle\">Special Users</strong><br>\n";
    reportsConference__specialUsers();

    //Last 10 Registrants
    echo "<strong class=\"reportTitle\">Last 10 Users</strong><br>\n";

    for ($i = 0; $i < 10; $i += 1) {
        echo htmlentities( $lastten[$i] ) . "<br>\n";
    }// end for loop
}// end reportsConference_showReportOverview function

/*
 * The reportsConference_showAllWorkshops function draws a JQuery-UI accordion
 * and populates it with all of the conference sessions
 */
function reportsConference_showAllWorkshops( $showRegistrants = true ) {
    //Reference global variables
    global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb;

    // Connect to the Database
    $dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

    //Set the character set, for use with mysqli_real_escape_string
    mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

    if( $showRegistrants == true ) {
        echo "<div id=\"workshopAccordion\">\n";
    } else {
        echo "<div>\n";
    }// end if statement

    echo "<h3>Monday</h3>\n<div>\n<p>\n";

    $showno = 0;
    $switchtoPM = 0;
    $workshopQuery = "SELECT * FROM workshops WHERE day='mon' ORDER BY STR_TO_DATE(start_time,'%l:%i%p');";
    $workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

    if( mysqli_num_rows( $workshopResultObject ) == 0 ) {
        echo( "<p>There are no sessions at this time.</p>\n" );
    }// end if statement

    while($row = mysqli_fetch_array($workshopResultObject)) {
        extract($row);

        if ($switchtoPM == 0 && $time == "PM") {
            $switchtoPM = 1;
        }// end if statement

        if ($time == "AM") {
            $usersQuery = "select users.lastname as lname, users.firstname as fname from registered, users where mon_amworkshop=$workshopid and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        } else {
            $usersQuery = "select users.lastname as lname, users.firstname as fname from registered, users where mon_pmworkshop=$workshopid and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        }// end if statement

        $usersResultObject = mysqli_query( $dbConnectionObject, $usersQuery );
        $reg = mysqli_num_rows( $usersResultObject );

        $title = stripslashes($title);

        if ($showno == "0" && ($workshopid == 100 || $workshopid == 101)) {

        } else {
            echo "<hr><span class=\"workshopTitle\">$title ($start_time)</span>\n";

            if( $showRegistrants === true ) {
                if (($seats - $reg) <= 0) {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . " <strong>FULL</strong></p>\n";		
                } else {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . "</p>\n";
                }//end if statement

                while( $rows = mysqli_fetch_array($usersResultObject) ) {
                    extract($rows);
                    $lname = htmlentities( stripslashes($lname) );
                    $fname = htmlentities( stripslashes($fname) );

                    echo "$lname, $fname <br>";
                }// end while loop
            }// end if statement

            if( $showRegistrants === false ) {
                echo "<p><strong>Presenter</strong>: {$presenter} <strong>Room</strong>: {$room}</p>\n";
            }// end if statement

            echo "<p style='font-size: 0.8em'>\n";
        }// end if statement
    }// end while loop

    //Free the memory associated with the result object
    mysqli_free_result( $usersResultObject );

    //Free the memory associated with the result object
    mysqli_free_result( $workshopResultObject );

    echo "</p>\n</div>\n";
    echo "<h3>Tuesday</h3>\n<div>\n<p>\n";
    
    $showno = 0;
    $workshopQuery = "SELECT * FROM workshops WHERE day='tue' ORDER BY STR_TO_DATE(start_time,'%l:%i%p');";
    $workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

    if( mysqli_num_rows( $workshopResultObject ) == 0 ) {
        echo( "<p>There are no sessions at this time.</p>\n" );
    }// end if statement

    while($row = mysqli_fetch_array($workshopResultObject)) {
        extract($row);

        if ($time == "AM") {
            $seatsQuery = "select users.lastname as lname, users.firstname as fname from registered, users where tue_amworkshop=$workshopid and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        } else {
            $seatsQuery = "select users.lastname as lname, users.firstname as fname from registered, users where tue_pmworkshop=$workshopid and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        }// end if statement

        $seatsResultObject = mysqli_query( $dbConnectionObject, $seatsQuery );
        $reg = mysqli_num_rows($seatsResultObject);

        $title = stripslashes($title);

        if ($showno == "0" && ($workshopid == 100 || $workshopid == 101)) {

        } else {
            echo "<hr><span class=\"workshopTitle\">$title ($start_time)</span>\n";

            if( $showRegistrants === true ) {
                if (($seats - $reg) <= 0) {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . " <strong>FULL</strong></p>\n";		
                } else {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . "</p>\n";
                }// end if statement

                while( $rows = mysqli_fetch_array($seatsResultObject) ) {
                    extract($rows);
                    $lname = htmlentities( stripslashes($lname) );
                    $fname = htmlentities( stripslashes($fname) );

                    echo "$lname, $fname <br>\n";
                }// end while loop
            }// end if statement

            if( $showRegistrants === false ) {
                echo "<p><strong>Presenter</strong>: {$presenter} <strong>Room</strong>: {$room}</p>\n";
            }// end if statement

            echo "<p style='font-size: 0.8em'>\n";
        }// end if statement
    }// end while loop

    //Free the memory associated with the result object
    mysqli_free_result( $seatsResultObject );

    //Free the memory associated with the result object
    mysqli_free_result( $workshopResultObject );

    echo "</p></div>\n<h3>Wednesday</h3>\n";
    echo "<div>\n<p>\n";

    $showno = $switchtoPM = $switchtoPM2 = 0;
    $workshopQuery = "SELECT * FROM workshops WHERE day='wed' ORDER BY STR_TO_DATE(start_time,'%l:%i%p');";
    $workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

    if( mysqli_num_rows( $workshopResultObject ) == 0 ) {
        echo( "<p>There are no sessions at this time.</p>\n" );
    }// end if statement

    while($row = mysqli_fetch_array($workshopResultObject)) {
        extract($row);

        if ($switchtoPM == 0 && $time == "PM") {
            $switchtoPM = 1;
        }// end if statement

        if( $switchtoPM == 1 && $switchtoPM2 == 0 && $start_time == "2:30pm" ) {
            $switchtoPM2 = 1;
        }// end if statement

        if ($time == "AM") {
            $seatsQuery = "select users.lastname as lname, users.firstname as fname from registered, users where wed_amworkshop={$workshopid} and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        } else {
            $seatsQuery = "select users.lastname as lname, users.firstname as fname from registered, users where (wed_pmworkshop={$workshopid} OR wed_pmworkshop2={$workshopid}) and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        }// end if statement

        $seatsResultObject = mysqli_query( $dbConnectionObject, $seatsQuery );
        $reg = mysqli_num_rows( $seatsResultObject );

        $title = mysqli_real_escape_string( $dbConnectionObject, $title );

        if ($showno == "0" && ($workshopid == 100 || $workshopid == 101)) {

        } else {		
            echo "<hr><span class=\"workshopTitle\">$title ($start_time)</span>\n";

            if( $showRegistrants === true ) {
                if (($seats - $reg) <= 0) {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . " <strong>FULL</strong></p>\n";		
                } else {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . "</p>\n";
                }// end if statement

                while( $rows = mysqli_fetch_array($seatsResultObject) ) {
                    extract($rows);
                    $lname = htmlentities( stripslashes($lname) );
                    $fname = htmlentities( stripslashes($fname) );

                    echo "$lname, $fname <br>\n";
                }// end while loop
            }// end if statement

            if( $showRegistrants === false ) {
                echo "<p><strong>Presenter</strong>: {$presenter} <strong>Room</strong>: {$room}</p>\n";
            }// end if statement

            echo "<p style='font-size: 0.8em'>\n";
        }// end if statement
    }// end while loop

    //Free the memory associated with the result object
    mysqli_free_result( $seatsResultObject );

    //Free the memory associated with the result object
    mysqli_free_result( $workshopResultObject );

    echo "</p>\n</div>\n";
    echo "<h3>Thursday</h3>\n<div>\n<p>\n";

    $showno = $switchtoPM = 0;
    $workshopQuery = "SELECT * FROM workshops WHERE day='thu' ORDER BY STR_TO_DATE(start_time,'%l:%i%p');";
    $workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

    if( mysqli_num_rows( $workshopResultObject ) == 0 ) {
        echo( "<p>There are no sessions at this time.</p>\n" );
    }// end if statement

    while($row = mysqli_fetch_array($workshopResultObject)) {
        extract($row);

        if ($switchtoPM == 0 && $time == "PM") {
            $switchtoPM = 1;
        }// end if statement

        if ($time == "AM") {
            $seatsQuery = "select users.lastname as lname, users.firstname as fname from registered, users where thur_amworkshop=$workshopid and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        } else {
            $seatsQuery = "select users.lastname as lname, users.firstname as fname from registered, users where thur_pmworkshop=$workshopid and registered.userid=users.userid and users.registered='yes' order by lname, fname";
        }// end if statement

        $seatsResultObject = mysqli_query( $dbConnectionObject, $seatsQuery );
        $reg = mysqli_num_rows( $seatsResultObject );
        $title = stripslashes($title);

        if ($showno == "0" && ($workshopid == 100 || $workshopid == 101)) {

        } else {
            echo "<hr><span class=\"workshopTitle\">$title ($start_time)</span>\n";

            if( $showRegistrants === true ) {
                if (($seats - $reg) <= 0) {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . " <strong>FULL</strong></p>\n";		
                } else {
                    echo "<p>Registered = $reg | Max = $seats | Remaining = " . ($seats - $reg) . "</p>\n";
                }// end if statement

                while( $rows = mysqli_fetch_array($seatsResultObject) ) {
                    extract($rows);
                    $lname = htmlentities( stripslashes($lname) );
                    $fname = htmlentities( stripslashes($fname) );

                    echo "$lname, $fname <br>\n";
                }// end while loop
            }// end if statement

            if( $showRegistrants === false ) {
                echo "<p><strong>Presenter</strong>: {$presenter} <strong>Room</strong>: {$room}</p>\n";
            }// end if statement

            echo "<p style='font-size: 0.8em'>\n";
        }// end if statement
    }// end while loop

    //Free the memory associated with the result object
    mysqli_free_result( $workshopResultObject );

    //Disconnect from the database
    mysqli_close( $dbConnectionObject );

    echo "</p>\n</div></div>\n";
}// end reportsConference_showAllWorkshops function