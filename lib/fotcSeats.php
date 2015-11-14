<?php
/**
 * fotcSeats.php v1.3	-	conference-registration
 */

/**
 * The fotcSeatsRemaining function returns the remaining number of FoTC
 * Seats or Boolean False if database query fails.
 * 
 * @return int Number of seats remaining for FoTC.
 * @return bool False on query failure.
 */
function fotcSeatsRemaining() {
    //Reference the global variables
    global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb;
    global $str_dbCharset, $int_fotcMax;

    //Connect to the database
    $dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

    //Set the character set, for use with mysqli_real_escape_string
    mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

    $fotcSeats_query = (string) "SELECT userid FROM fotcAttendees WHERE choice='yes';";
    $fotcSeats_result = mysqli_query( $dbConnectionObject, $fotcSeats_query );
    $fotcSeatsTaken = mysqli_num_rows( $fotcSeats_result );

    //Disconnect for the Database
    mysqli_close( $dbConnectionObject );

    if( is_bool( $fotcSeatsTaken ) ) {
       echoToConsole( "Failed to query for FoTC Seats Remaining!", true );
       return (bool) false;
    } else {
        $seatsRemaining = (int) $int_fotcMax - $fotcSeatsTaken;

        if( $seatsRemaining <= 0 ) {
                return (int) 0;
        } else {
                return (int) $seatsRemaining;
        }// end if statement

    }// end if statement
}// end fotcSeatsRemaining function