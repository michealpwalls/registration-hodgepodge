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