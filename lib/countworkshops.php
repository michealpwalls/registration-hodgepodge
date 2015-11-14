<?php
/*
 * countworkshops.php v2.0	-	pdweek
 */

	/**
	 * The countUsersSessions function will return the number of sessions
	 * a given user ID has been registered for.
	 * 
	 * @global String $str_dbDomain Database Domain (environment.php)
	 * @global String $str_dbUser Database Username (environment.php)
	 * @global String $str_dbPass Database Password (environment.php)
	 * @global String $str_dbDb Database DB Name (environment.php)
	 * @global String $str_dbCharset Database Character Set (environment.php)
	 * @param Integer $usersIdIn User ID
	 * @return Integer Number of sessions the user is registered for
	 */
	function countUsersSessions( $usersIdIn ) {
		//Reference the global variables
		global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

		//Connect to the database
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

		//Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

		//Get the user's workshop IDs
		$sessionIDQuery = (string) "SELECT mon_amworkshop,mon_pmworkshop,tue_amworkshop,tue_pmworkshop,wed_amworkshop,wed_pmworkshop,thur_amworkshop,thur_pmworkshop FROM registered WHERE userid=$usersIdIn;";

		$sessionIDResultObject = mysqli_query( $dbConnectionObject, $sessionIDQuery );

		$sessionCount = (int) 0;

		if( is_object( $sessionIDResultObject ) ) {
			$sessionIDResultArray = mysqli_fetch_array( $sessionIDResultObject );
			mysqli_free_result( $sessionIDResultObject );

			foreach( $sessionIDResultArray as $sessionID ) {
				if( $sessionID != 100 && $sessionID != 101 ) {
					$sessionCount++;
				}// end if statement
			}// end for loop

		}// end if statement

		//Close the database connection
		mysqli_close( $dbConnectionObject );

		return (int) $sessionCount;
	}// end of countUsersSessions() function

	/**
	 * The countAllSessions function returns the number of Sessions
	 * currently in the PD Week System.
	 * 
	 * @global String $str_dbDomain Database Domain (environment.php)
	 * @global String $str_dbUser Database Username (environment.php)
	 * @global String $str_dbPass Database Password (environment.php)
	 * @global String $str_dbDb Database DB Name (environment.php)
	 * @global String $str_dbCharset Database Character Set (environment.php)
	 * @return Integer Number of sessions currently in the system
	 */
	function countAllSessions() {
		//Reference the global variables
		global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

		//Connect to the database
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

		//Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

		$sessionsQuery = (string) "SELECT workshopid from workshops WHERE workshopid <> 100 AND workshopid <> 101;";
		$sessionResultObject = mysqli_query( $dbConnectionObject, $sessionsQuery );
		$int_confSessions = (int) 0;

		if( is_object( $sessionResultObject ) ) {
			$int_confSessions	= mysqli_num_rows( $sessionResultObject );

			// Free memory
			mysqli_free_result( $sessionResultObject );
		}
		unset( $sessionResultObject );

		// Disconnect from the database
		mysqli_close( $dbConnectionObject );

		return (int) $int_confSessions;
	}// end countAllSessions() function
	
	/**
	 * The countSessionRegistrants function returns the number of users
	 * registered for a day's sessions.
	 * @global String $str_dbDomain Database Domain (environment.php)
	 * @global String $str_dbUser Database Username (environment.php)
	 * @global String $str_dbPass Database Password (environment.php)
	 * @global String $str_dbDb Database DB Name (environment.php)
	 * @global String $str_dbCharset Database Character Set (environment.php)
	 * @param String $dayIn The day to check for registrants
	 * @return Integer Number of users registered for that day's sessions
	 */
	function countSessionRegistrants( $dayIn ) {
		//Reference the global variables
		global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

		//Connect to the database
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

		//Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );
		
		//Escape and transform input
		$dayIn = mysqli_real_escape_string( $dbConnectionObject, $dayIn );

		if( $dayIn == 'thu' ) {
			$dayIn = 'thur';
		}// end if statement

		$dayIn .= '_';

		if( $dayIn == 'wed_' ) {
			$sessionRegistrants_query = (string) "SELECT userid FROM registered WHERE {$dayIn}amworkshop<>100 OR {$dayIn}pmworkshop<>101 OR {$dayIn}pmworkshop2<>101;";
		} else {
			$sessionRegistrants_query = (string) "SELECT userid FROM registered WHERE {$dayIn}amworkshop<>100 OR {$dayIn}pmworkshop<>101;";
		}// end if statement

		$sessionRegistrants_result = mysqli_query( $dbConnectionObject, $sessionRegistrants_query );

		$int_registrants = (int) 0;

		if( is_object( $sessionRegistrants_result ) ) {
			$int_registrants = mysqli_num_rows( $sessionRegistrants_result );

			//Free resultSet
			mysqli_free_result( $sessionRegistrants_result );
		}// end if statement

		//Disconnect from the Database
		mysqli_close( $dbConnectionObject );

		return (int) $int_registrants;
	}// end countSessionRegistrants() function
?>