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
	}
?>
	<h2><?=$str_appName;?> - Waitlisted Users</h2>
	<?php
	//Connect to the Database
	mysql_connect($str_dbDomain, $str_dbUser, $str_dbPass);
	$db = mysql_select_db($str_dbDb);

	$selectw = "SELECT id,lastname,firstname,validate2,email FROM waitlist WHERE invited='n' OR invited='no';";

	$resultw = mysql_query($selectw);

	$errorMessages = (string) "";

	if( !is_object( $resultw ) ) {
		if( $resultw == false ) {
			$errorMessages .= "Could not query the waitlist table!<br>\n";
		}// end if statement
	}// end if statement

	//Disconnect from the Database
	mysql_close();

	//Connect to the Database
	mysql_connect($str_dbDomain, $str_dbUser, $str_dbPass);
	$db = mysql_select_db($str_dbDb);

	$n = 0;
	$o = 0;
	while($row = mysql_fetch_array($resultw)) {
		extract($row);

		$n += 1;			
		echo "<p>" . htmlentities( $n ) . " - " . htmlentities( $lastname ) . ", " . htmlentities( $firstname ) . ", " . htmlentities( $validate2 ) . ", " . htmlentities( $email ) . "</p>\n";
	}// end while loop
?>