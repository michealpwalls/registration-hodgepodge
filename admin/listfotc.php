<?php
/*
 * listfotc.php v1.2.1	-	pdweek
 */

if( !is_bool( $str_appURL ) ) {
	require_once( "../data/environment.php" );
}
?>

<h2><?=$str_appName;?> - All FoTC Attendees</h2>
<?php
//Connect to the Database
$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

//Set the character set, for use with mysqli_real_escape_string
mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

$usersQuery = "SELECT lastname AS lname, firstname AS fname, department, " .
	"otherdept, fotc FROM users WHERE fotc='yes' ORDER BY userid ASC;";

$usersResultObject = mysqli_query( $dbConnectionObject, $usersQuery );

//Disconnect from the database
mysqli_close( $dbConnectionObject );

if( is_object( $usersResultObject ) ) {
	$n = 1;
	while($row = mysqli_fetch_array( $usersResultObject )) {
		extract($row);

		if ($department == "None" || $department == "Other-" || $department == "Choose your department") {
			$department = "No department selected";
		} else {
			if ($department == "Other") {
				$department = $otherdept;
			}
		}

		$department = stripslashes($department);
		$lanme = stripslashes($lname);
		$fname = stripslashes($fname);

		echo "<p>" . htmlentities( $n ) . " - " . htmlentities( $lname) . ", " . htmlentities( $fname ) . ", " . htmlentities( $department ) . "</p>\n";

		$n+=1;
	}// end while loop

	//Free memory
	mysqli_free_result( $usersResultObject );
} else {
	echo( "<p>The query to retrieve all FoTC users failed to return a valid Result Object.</p>\n" );
}// end if statement
?>
