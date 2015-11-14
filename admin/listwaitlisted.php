<?php
	/*
	 * listWaitlisted.php v1.2.3	-	pdweek
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