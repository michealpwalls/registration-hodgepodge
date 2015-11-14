<?php
	/**
	 * resetKeynotes.php	- 	pdweek v1.4
	 */
	if( !isset( $str_appURL ) ) {
		require_once( "../../data/environment.php" );
	}// end if statement

	if( !function_exists( echoToConsole ) ) {
		require_once( "../../lib/logging.php" );
	}// end if statement

	// Open the database connection
	$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb )
		or die( "Failed to connect to database. Impossible to continue." . mysqli_error( $dbConnectionObject  ) );

	// Set the character set, for use with mysqli_real_escape_string
	mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

	$resetQuery = "UPDATE keynotes SET seats=max_seats;";

	// Query the Database
	$resetResultObject = mysqli_query( $dbConnectionObject, $resetQuery );

	if( $resetResultObject == false ) {
		echoToConsole( "Failed to execute query", true );
	} else {
		$resetCount = mysqli_affected_rows( $dbConnectionObject );

		echo( "<p style=\"color: green;\">Successfully reset {$resetCount} Keynote Seat records</p>\n" );
	}// end if statement
?>