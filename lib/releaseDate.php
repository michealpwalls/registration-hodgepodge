<?php
/**
 * releaseDate.php v1.3	-	pdweek
 */

	/**
	 * The daysRemaining function returns number of days remaining until
	 * a day's sessions are released, or 0 if they have already been released.
	 * 
	 * @param string $dayIn The day to check release date of ('mon', 'tue', 'thu' etc.)
	 * @return int Days Remaining until release date, or 0 if already reached
	 */
	function daysRemaining( $dayIn ) {
		//Reference the global variables
		global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

		//Connect to the database
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

		//Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

		/**
		 * Check the release date
		 */
		$releaseDateQuery = (string) "SELECT release_date FROM workshops WHERE day='{$dayIn}' LIMIT 1;";

		$releaseDateResultObject = mysqli_query( $dbConnectionObject, $releaseDateQuery );

		if( is_bool( $releaseDateResultObject ) ) {
			echoToConsole( "Failed to query for {$dayIn} release dates!" );
		}// end if statement

		$releaseDateResultArray = mysqli_fetch_array( $releaseDateResultObject );
		mysqli_free_result( $releaseDateResultObject );

		//Disconnect from Database
		mysqli_close( $dbConnectionObject );

		// Set the timezone for use by date functions
		date_default_timezone_set( 'America/Toronto' );

		//String version of Tuesday release date
		$releaseDate = (string) $releaseDateResultArray[0];

		$releaseDateObject = date_create( $releaseDate );
		$currentDateObject = date_create( date( 'Y-m-d H:i:s' ) );

		$dateIntervalObject = date_diff( $releaseDateObject, $currentDateObject );
		$daysDifference = $dateIntervalObject->format( "%R%d" );	//%R is the sign (-/+) and %d is non-padded number of days (1 - 31)

		$dateIntervalObject = $currentDateObject = $releaseDateObject = null;
		unset( $dateIntervalObject );
		unset( $currentDateObject );
		unset( $releaseDateObject );
		unset( $releaseDate );

		$daysRemaining = (int) 0;

		if( $daysDifference >= 0 ) {
			$daysRemaining = 0;
		} else {
			$daysRemaining = abs( $daysDifference ) + 1;
		}// end if statement

		return (int) $daysRemaining;
	}// end function daysRemaining

?>