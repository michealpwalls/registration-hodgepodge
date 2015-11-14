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

if( !is_bool( $str_appURL ) ) {
	require_once( "../data/environment.php" );
}
?>
<h2><?=$str_appName;?> - UnRegistered Users</h2>
<?php
//Connect to the Database
$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

//Set the character set, for use with mysqli_real_escape_string
mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

$usersQuery = "SELECT DISTINCT users.userid as userid, users.lastname AS lname, users.firstname AS fname
	FROM users, registered
		WHERE users.registered='no' OR (users.userid=registered.userid
		AND registered.mon_amworkshop=100
		AND registered.mon_pmworkshop=101
		AND registered.tue_amworkshop=100
		AND registered.tue_pmworkshop=101
		AND registered.wed_amworkshop=100
		AND registered.wed_pmworkshop=101
		AND registered.thur_amworkshop=100
		AND registered.thur_pmworkshop=101)
	ORDER BY lname, fname;";

$usersResultObject = mysqli_query( $dbConnectionObject, $usersQuery );

if( is_object( $usersResultObject ) ) {

	$n = 1;

	while($row = mysqli_fetch_array($usersResultObject)) {
		extract($row);

		$lanme = mysqli_real_escape_string( $dbConnectionObject, $lname );
		$fname = mysqli_real_escape_string( $dbConnectionObject, $fname );

		echo "<p>" . htmlentities( $n, ENT_QUOTES ) . " - " . htmlentities( $lname, ENT_QUOTES ) . ", " . htmlentities( $fname, ENT_QUOTES ) . "</p>\n";

		$n+=1;
	}// end while loop

	//Free memory
	mysqli_free_result( $usersResultObject );
} else {
	echo( "<p>The query to fetch all UnRegistered users failed to return a valid Result Object.</p>\n" );
}// end if statement

//Close database connection
mysqli_close( $dbConnectionObject );
?>
