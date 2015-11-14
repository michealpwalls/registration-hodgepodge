<!DOCTYPE html>

<?php
	/*
	 * register3.php v1.3.7.2	-	conference-registration
	 */
	$flt_time_start = (float) microtime( true );	

	//Session is started for storing data later sent to dispatchConfirmation.php
	session_start();

	require_once "data/environment.php";
	require_once "data/db.php";
	require_once "lib/countworkshops.php";
	require_once "lib/listworkshops.php";
	require_once "lib/logging.php";
?>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>Georgian College :: <?=$str_appName;?></title>
		<!--
                    JQuery-UI css definitions were manually implemented in gl.css
                    to fix IE11 performance issue when full jquery-ui.css was
                    loaded.
		-->
		<link rel="stylesheet" href="css/gl.css">
	</head>
	<body>
<?php

/**
 * Input Validation
 */
$errormessage = (string) "";

if( isset( $_POST['lastname'] ) ) {
	$lastname = $_POST['lastname'];
} else {
	$errormessage .= "Last Name,\n";
}

if( isset( $_POST['firstname'] ) ) {
	$firstname = $_POST['firstname'];
} else {
	$errormessage .= "First Name,\n";
}

if( isset( $_POST['email'] ) ) {
	$email = $_POST['email'];
} else {
	$errormessage .= "E-Mail Address,\n";
}

if( isset( $_POST['department'] ) ) {
	$departmentIn = $_POST['department'];
} else {
	$errormessage .= "Department,\n";
}

if( isset( $_POST['otherdept'] ) ) {
	$otherdeptIn = $_POST['otherdept'];
} else {
	$errormessage .= "Other Department,\n";
}

if( isset( $_POST['mon_lunch'] ) ) {
	$mon_lunchIn = $_POST['mon_lunch'];
} else {
	$errormessage .= "Monday Lunch,\n";
}

if( isset( $_POST['mon_keynote'] ) ) {
	$mon_keynoteIn = $_POST['mon_keynote'];
} else {
	$mon_keynoteIn = '';
}

if( isset( $_POST['attendFotc'] ) ) {
	$fotcIn = $_POST['attendFotc'];
}

if( isset( $_POST['techcafe'] ) ) {
	$techcafe = $_POST['techcafe'];
} else {
	//$errormessage .= "Tech Cafe,\n";
	$techcafe = $fotcIn;
}

if (isset($_POST['mon_amworkshop'])) {
	$mon_amworkshop = $_POST['mon_amworkshop'];
} else {
	$mon_amworkshop = 100;
}
if (isset($_POST['mon_pmworkshop'])) {
	$mon_pmworkshop = $_POST['mon_pmworkshop'];
} else {
	$mon_pmworkshop = 101;	
}

if (isset($_POST['tue_amworkshop'])) {
	$tue_amworkshop = $_POST['tue_amworkshop'];
} else {
	$tue_amworkshop = 100;
}
if (isset($_POST['tue_pmworkshop'])) {
	$tue_pmworkshop = $_POST['tue_pmworkshop'];
} else {
	$tue_pmworkshop = 101;	
}

if( isset( $_POST['tue_keynote'] ) ) {
	$tue_keynoteIn = $_POST['tue_keynote'];
} else {
	$tue_keynoteIn = '';
}

if (isset($_POST['wed_amworkshop'])) {
	$wed_amworkshop = $_POST['wed_amworkshop'];
} else {
	$wed_amworkshop = 100;
}
if (isset($_POST['wed_pmworkshop'])) {
	$wed_pmworkshop = $_POST['wed_pmworkshop'];
} else {
	$wed_pmworkshop = 101;	
}
if (isset($_POST['wed_pmworkshop2'])) {
	$wed_pmworkshop2 = $_POST['wed_pmworkshop2'];
} else {
	$wed_pmworkshop2 = 101;	
}

if( isset( $_POST['wed_keynote'] ) ) {
	$wed_keynoteIn = $_POST['wed_keynote'];
} else {
	$wed_keynoteIn = '';
}

if (isset($_POST['thur_amworkshop'])) {
	$thur_amworkshop = $_POST['thur_amworkshop'];
} else {
	$thur_amworkshop = 100;
}

if (isset($_POST['thur_pmworkshop'])) {
	$thur_pmworkshop = $_POST['thur_pmworkshop'];
} else {
	$thur_pmworkshop = 101;	
}

if( isset( $_POST['thur_keynote'] ) ) {
	$thur_keynoteIn = $_POST['thur_keynote'];
} else {
	$thur_keynoteIn = '';
}

$userid = isset( $_POST['userid'] ) ? $_POST['userid'] : "";

if( $userid != "" ) {
	/**
	 * We've got a userID, lets check if it exists
	 */
	$prevfound = (int) 1;

	$checkforprev = "select userid from users where userid=$userid";

	//Connect to the database
	mysql_connect($str_dbDomain, $str_dbUser, $str_dbPass);
	$db = mysql_select_db($str_dbDb);

	$result = mysql_query($checkforprev);

	if( mysql_num_rows($result) == 0 ) {
		$prevfound = 0;
	}// end if statement

	$firstname = strtoupper(substr($firstname,0,1)) . substr($firstname,1);
	$lastname = strtoupper(substr($lastname,0,1)) . substr($lastname,1);

	/**
	 * Prepare user inputs for storage in the database
	 */
	$email = addslashes($email);
	$firstname = addslashes($firstname);
	$lastname = addslashes($lastname);
	$otherdeptIn = addslashes($otherdeptIn);
	$departmentIn = addslashes($departmentIn);
	$mon_keynoteIn = addslashes($mon_keynoteIn);
	$mon_lunchIn = addslashes($mon_lunchIn);
	$mon_amworkshop = addslashes($mon_amworkshop);
	$mon_pmworkshop = addslashes($mon_pmworkshop);
	$fotcIn = addslashes($fotcIn);
	$tue_amworkshop = addslashes($tue_amworkshop);
	$tue_pmworkshop = addslashes($tue_pmworkshop);
	$wed_amworkshop = addslashes($wed_amworkshop);
	$wed_pmworkshop = addslashes($wed_pmworkshop);
	$wed_pmworkshop2 = addslashes($wed_pmworkshop2);
	$thur_amworkshop = addslashes($thur_amworkshop);
	$thur_pmworkshop = addslashes($thur_pmworkshop);
	$thur_keynoteIn = addslashes($thur_keynoteIn);

	$noMAMSeats = $noMPMSeats = $noTAMSeats = $noTPMSeats = $noWAMSeats = $noWPMSeats = $noWPMSeats2 = $noTHAMSeats = $noTHPMSeats = 1;
	$mamw2 = $tamw2 = $wamw0 = $thamw2 = 100;
	$mpmw2 = $tpmw2 = $wpmw0 = $wpmw2 = $thpmw2 = 101;
}// end if statement

$noErrors = (bool) true;

if( $errormessage != "" ) {
	$noErrors = false;
} else {
	$update = "update users set firstname='$firstname', lastname='$lastname', " .
		"department='$departmentIn', otherdept='$otherdeptIn' where userid=$userid";

	$profileUpdateResult = mysql_query($update);

	if( $profileUpdateResult == false ) {
		echoToConsole( "The profile update query failed!", true );
	}// end if statement

	/**
	 * Monday Workshops
	 */
	$checkforprev2 = "select mon_amworkshop as mamw2, mon_pmworkshop as mpmw2 from registered where userid=$userid";
	$result2 = mysql_query($checkforprev2);

	if (mysql_num_rows($result2) > 0) {
		$row2 = mysql_fetch_array($result2);
		extract($row2);		
	} else {
		$mamw2 = -1;
		$mpmw2 = -1;
	}// end if statement

	$checkam = "select userid from registered where mon_amworkshop=$mon_amworkshop";
	$resultam = mysql_query($checkam);
	$noAM = mysql_num_rows($resultam);
	$getAMMax = "select seats,start_time AS monAM_start from workshops where workshopid=$mon_amworkshop";
	$resultammax = mysql_query($getAMMax);
	$row = mysql_fetch_array($resultammax);
	extract($row);
	$maxAM = $seats;

	if (($maxAM - $noAM) <= 0) {
		$noMAMSeats = 0;
	}// end if statement

	$checkpm = "select userid from registered where mon_pmworkshop=$mon_pmworkshop";	
	$resultpm = mysql_query($checkpm);	
	$noPM = mysql_num_rows($resultpm);	
	$getPMMax = "select seats,start_time AS monPM_start from workshops where workshopid=$mon_pmworkshop";	
	$resultpmmax = mysql_query($getPMMax);
	$row = mysql_fetch_array($resultpmmax);
	extract($row);
	$maxPM = $seats;

	if (($maxPM - $noPM) <= 0) {
		$noMPMSeats = 0;
	}// end if statement

	if ($mon_amworkshop == $mamw2) { // no change in workshop choice
		
	} elseif ($noMAMSeats == 0) {// workshop is full
		$errormessage .= "The Monday {$monAM_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		$workshopreg = "update registered set mon_amworkshop=$mon_amworkshop, regdate=now() where userid=$userid";
		mysql_query($workshopreg);
	}// end if statement
		
	if ($mon_pmworkshop == $mpmw2) { // no change in workshop choice
			
	} elseif ($noMPMSeats == 0) {// workshop is full
		$errormessage .= "The Monday {$monPM_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		$workshopreg = "update registered set mon_pmworkshop=$mon_pmworkshop, regdate=now() where userid=$userid";
		mysql_query($workshopreg);
	}// end if statement
	
	if( $mon_keynoteIn !== '' ) {
		$currentQuery = "SELECT mon_keynote FROM users WHERE userid=$userid;";
		$currentResult = mysql_query( $currentQuery );
		extract( mysql_fetch_array($currentResult) );

		if( $mon_keynoteIn == $mon_keynote ) {
			//No changes to make
		} else {
			//Update Keynote Seats
			$seatDirection = (string) $mon_keynoteIn == 'no' ? '+' : '-';

			$keynoteSeatsQuery = (string) "UPDATE keynotes SET seats=seats{$seatDirection}1 WHERE day='mon';";
			$keynoteSeatsResult = mysql_query( $keynoteSeatsQuery );

			$keynoteQuery = (string) "UPDATE users SET mon_keynote='$mon_keynoteIn' WHERE userid=$userid;";
			$keynoteResult = mysql_query( $keynoteQuery );
		}// end if statement
	}// end if statement

	if( isset( $_POST['vegetarian'] ) ) {
		$vegetarian = (string) addslashes( $_POST['vegetarian'] );

		$str_vegetarianClause = (string) ", vegetarian='{$vegetarian}'";
	} else {
		$str_vegetarianClause = (string) '';
	}// end if statement

	if( countUsersSessions( $userid ) == 0 ) {
		$notRegisteredQuery = "UPDATE users SET registered='no', mon_lunch='$mon_lunchIn'" . $str_vegetarianClause . " WHERE userid=$userid;";
		$notRegisteredResult = mysql_query( $notRegisteredQuery );
	} else {
		$notRegisteredQuery = "UPDATE users SET registered='yes', mon_lunch='$mon_lunchIn'" . $str_vegetarianClause . " WHERE userid=$userid;";
		$notRegisteredResult = mysql_query( $notRegisteredQuery );
	}//end if statement

	/**
	 * Tuesday Workshops
	 */
	$checkforprev2 = "select tue_amworkshop as tamw2, tue_pmworkshop as tpmw2 from registered where userid=$userid";
	$result2 = mysql_query($checkforprev2);
	
	if (mysql_num_rows($result2) > 0) {
		$row2 = mysql_fetch_array($result2);
		extract($row2);
	} else {
		$tamw2 = -1;
		$tpmw2 = -1;
	}//end if statement

	$checkam = "select userid from registered where tue_amworkshop=$tue_amworkshop";
	$resultam = mysql_query($checkam);
	$noAM = mysql_num_rows($resultam);
	$getAMMax = "select seats,start_time AS tueAM_start from workshops where workshopid=$tue_amworkshop";
	$resultammax = mysql_query($getAMMax);
	$row = mysql_fetch_array($resultammax);
	extract($row);
	$maxAM = $seats;
	
	if (($maxAM - $noAM) <= 0) {
		$noTAMSeats = 0;
	}//end if statement
	
	$checkpm = "select userid from registered where tue_pmworkshop=$tue_pmworkshop";	
	$resultpm = mysql_query($checkpm);	
	$noPM = mysql_num_rows($resultpm);	
	$getPMMax = "select seats,start_time AS tuePM_start from workshops where workshopid=$tue_pmworkshop";	
	$resultpmmax = mysql_query($getPMMax);
	$row = mysql_fetch_array($resultpmmax);
	extract($row);
	$maxPM = $seats;
	
	if (($maxPM - $noPM) <= 0) {
		$noTPMSeats = 0;
	}//end if statement

	if ($tue_amworkshop == $tamw2) { // no change in workshop choice
		if( $tue_amworkshop != 100 ) {
			
		} // No change keeps a real workshop selected
	} elseif ($noTAMSeats == 0) {// workshop is full
		$errormessage .= "The Tuesday {$tueAM_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		if( $tue_amworkshop == 100 ) {
			//Removing a Tuesday workshop
			$workshopreg = "update registered set tue_amworkshop=$tue_amworkshop, regdate='' where userid=$userid";
		} else {
			$workshopreg = "update registered set tue_amworkshop=$tue_amworkshop, regdate=now() where userid=$userid";
		}//end if statement
		mysql_query($workshopreg);
	}//end if statement

	if ($tue_pmworkshop == $tpmw2) { // no change in workshop choice
		if( $tue_pmworkshop != 101 ) {
			
		}// No change keeps a real workshop selected
	} elseif ($noTPMSeats == 0) {// workshop is full
		$errormessage .= "The Tuesday {$tuePM_start} session that you selected filled up after you viewed the page.<br>";
	} else { // ok to add to the workshop
		if( $tue_pmworkshop == 101 ) {
			//Removing a Tuesday workshop
			$workshopreg = "update registered set tue_pmworkshop=$tue_pmworkshop, regdate='' where userid=$userid";
		} else {
			$workshopreg = "update registered set tue_pmworkshop=$tue_pmworkshop, regdate=now() where userid=$userid";
		}//end if statement

		$workshopreg_result = mysql_query($workshopreg);

		if( $workshopreg_result == false ) {
			echoToConsole( "Failed to update registered table for Tuesday 2:30pm Session!" );
		}//end if statement
	}//end if statement

	/**
	 * FoTC Waiting List
	 */
	require_once( 'lib/fotcSeats.php' );

	if( fotcSeatsRemaining() == 0 && $fotcIn == 'yes' ) {
		//Get the user's info
		$userInfoQuery = (string) "SELECT firstname,lastname,email,validate2,invited FROM waitlist WHERE id={$userid};";
		$userInfoResultObject = mysql_query( $userInfoQuery );
		$userInfoResultArray = mysql_fetch_array( $userInfoResultObject );
		$waitlisted = mysql_num_rows( $userInfoResultObject );
		
		//Is the user *already* signed up for FoTC?
		$userFoTCQuery = (string) "SELECT fotc FROM users WHERE userid={$userid};";
		$userFoTCResultObject = mysql_query( $userFoTCQuery );

		$existingFoTCChoice = (string) '';

		if( !is_bool($userFoTCResultObject) ) {
			echoToConsole( 'userFoTCResult is Object path', true );
			$array_FoTCChoice = mysql_fetch_array( $userFoTCResultObject );
			$existingFoTCChoice = $array_FoTCChoice['fotc'];
		}// end if statement

		if( $waitlisted == 0 ) {

			// Only evaluate Waitlist logic when *existing* FoTC choice is 'no'
			if( $existingFoTCChoice == 'no' ) {
				//Add the user to the waiting list for the first time
				$fotcWaitlistQuery = (string) "INSERT INTO waitlist (id,lastname,firstname,email,invited) VALUES({$userid},'{$lastname}','{$firstname}','{$email}','no');";
				$fotcWaitlistResult = mysql_query( $fotcWaitlistQuery );

				$fotcIn = 'wl';		//Override user's fotc flag

				//Alert the user that the FoTC seats ran out
				$errormessage .= 'The Focus on Teaching Conference has run out of seats. Unfortunately, due to size restrictions the Alumni Hall can only hold a maximum of 250 people, which have already registered. You have been added to a waiting list in the event that seats become available. Sorry for the inconvenience.<br>';
			}// end if statement

		} else {

			//User is waitlisted but has already been invited
			if( $userInfoResultArray['invited'] == 'y' || $userInfoResultArray['invited'] == 'yes' ) {
				$fotcIn ='yes';	//Override user's fotc flag
			} else {
				$fotcIn = 'wl';
			}// end if statement

		}// end if statement

	}// end if statement

	/**
	 * Update the 'registered', 'fotc', 'tue_lunch', and 'techcafe' columns in users table
	 */
	if( isset( $_POST['vegetarian'] ) ) {
		$str_vegetarianClause = (string) ", vegetarian='{$_POST['vegetarian']}'";
	} else {
		$str_vegetarianClause = (string) '';
	}// end if statement

	if( countUsersSessions( $userid ) == 0 ) {
		$tueRegisteredQuery = "UPDATE users SET registered='no', fotc='{$fotcIn}', tue_lunch='{$fotcIn}', techcafe='{$fotcIn}'" . $str_vegetarianClause . " WHERE userid={$userid};";
	} else {
		$tueRegisteredQuery = "UPDATE users SET registered='yes', fotc='{$fotcIn}', tue_lunch='{$fotcIn}', techcafe='{$fotcIn}'" . $str_vegetarianClause . " WHERE userid={$userid};";
	}//end if statement

	$tueRegisteredResult = mysql_query( $tueRegisteredQuery );

	if( $tueRegisteredResult == false ) {
		echoToConsole( "Failed to update the user's table for the Tuesday selection!", true );
	}// end if statement

	/**
	 * FoTC Seats
	 */
	$fotcSeats_query = (string) "UPDATE fotcAttendees SET choice='{$fotcIn}' WHERE userid={$userid};";

	$fotcSeats_result = mysql_query( $fotcSeats_query );
	
	if( $fotcSeats_result == false ) {
		echoToConsole( "Failed to update the fotcAttendees table!", true );
	}// end if statement

	/**
	 * Begin Wednesday Workshops
	 */
	$checkforprev2 = "select wed_amworkshop as wamw0, wed_pmworkshop as wpmw0, wed_pmworkshop2 as wpmw2 from registered where userid=$userid";
	$result2 = mysql_query($checkforprev2);
	
	if (mysql_num_rows($result2) > 0) {
		$row2 = mysql_fetch_array($result2);
		extract($row2);
	} else {
		$wamw0 = -1;
		$wpmw0 = -1;
		$wpmw2 = -1;
	}

	$checkam = "select userid from registered where wed_amworkshop=$wed_amworkshop";
	$resultam = mysql_query($checkam);
	$noAM = mysql_num_rows($resultam);
	$getAMMax = "select seats,start_time as wedAM_start from workshops where workshopid=$wed_amworkshop";
	$resultammax = mysql_query($getAMMax);
	$row = mysql_fetch_array($resultammax);
	extract($row);
	$maxAM = $seats;
	
	if (($maxAM - $noAM) <= 0) {
		$noWAMSeats = 0;
	}
	
	$checkpm = "select userid from registered where wed_pmworkshop=$wed_pmworkshop";	
	$resultpm = mysql_query($checkpm);	
	$noPM = mysql_num_rows($resultpm);	
	$getPMMax = "select seats,start_time as wedPM_start from workshops where workshopid=$wed_pmworkshop";	
	$resultpmmax = mysql_query($getPMMax);
	$row = mysql_fetch_array($resultpmmax);
	extract($row);
	$maxPM = $seats;
	
	if (($maxPM - $noPM) <= 0) {
		$noWPMSeats = 0;
	}
	
	$checkpm2 = "select userid from registered where wed_pmworkshop2=$wed_pmworkshop2";	
	$resultpm2 = mysql_query($checkpm2);	
	$noPM2 = mysql_num_rows($resultpm2);	
	$getPMMax2 = "select seats,start_time as wedPM2_start from workshops where workshopid=$wed_pmworkshop2";	
	$resultpmmax2 = mysql_query($getPMMax2);
	$row2 = mysql_fetch_array($resultpmmax2);
	extract($row2);
	$maxPM2 = $seats;
	
	if (($maxPM2 - $noPM2) <= 0) {
		$noWPMSeats2 = 0;
	}
	
	if ($wed_amworkshop == $wamw0) { // no change in workshop choice
			
	} elseif ($noWAMSeats == 0) {// workshop is full
		$errormessage .= "The Wednesday {$wedAM_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		$workshopreg = "update registered set wed_amworkshop=$wed_amworkshop, regdate=now() where userid=$userid";
		mysql_query($workshopreg);
	}
		
	if ($wed_pmworkshop == $wpmw0) { // no change in workshop choice
			
	} elseif ($noWPMSeats == 0) {// workshop is full
		$errormessage .= "The Wednesday {$wedPM_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		$workshopreg = "update registered set wed_pmworkshop=$wed_pmworkshop, regdate=now() where userid=$userid";
		mysql_query($workshopreg);
	}
	
	if ($wed_pmworkshop2 == $wpmw2) { // no change in workshop choice
			
	} elseif ($noWPMSeats2 == 0) {// workshop is full
		$errormessage .= "The Wednesday {$wedPM2_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		$workshopreg = "update registered set wed_pmworkshop2=$wed_pmworkshop2, regdate=now() where userid=$userid";
		mysql_query($workshopreg);
	}

	if( $wed_keynoteIn !== '' ) {
		$currentQuery = "SELECT wed_keynote FROM users WHERE userid=$userid;";
		$currentResult = mysql_query( $currentQuery );
		extract( mysql_fetch_array($currentResult) );

		if( $wed_keynoteIn == $wed_keynote ) {
			//No changes to make
		} else {
			//Update Keynote Seats
			$seatDirection = (string) $wed_keynoteIn == 'no' ? '+' : '-';

			$keynoteSeatsQuery = (string) "UPDATE keynotes SET seats=seats{$seatDirection}1 WHERE day='wed';";
			$keynoteSeatsResult = mysql_query( $keynoteSeatsQuery );

			$keynoteQuery = (string) "UPDATE users SET wed_keynote='$wed_keynoteIn' WHERE userid=$userid;";
			$keynoteResult = mysql_query( $keynoteQuery );
		}// end if statement
	}// end if statement

	/**
	 * Update the 'registered' column in users table
	 */
	if( countUsersSessions( $userid ) == 0 ) {
		$notRegisteredQuery = "UPDATE users SET registered='no' WHERE userid=$userid;";
		$notRegisteredResult = mysql_query( $notRegisteredQuery );
	} else {
		$notRegisteredQuery = "UPDATE users SET registered='yes' WHERE userid=$userid;";
		$notRegisteredResult = mysql_query( $notRegisteredQuery );
	}//end if statement

	/**
	 * Begin Thursday Workshops
	 */
	$checkforprev2 = "select thur_amworkshop as thamw2, thur_pmworkshop as thpmw2 from registered where userid=$userid";
	$result2 = mysql_query($checkforprev2);
	
	if (mysql_num_rows($result2) > 0) {
		$row2 = mysql_fetch_array($result2);
		extract($row2);		
	} else {
		$thamw2 = -1;
		$thpmw2 = -1;
	}
	
	$checkam = "select userid from registered where thur_amworkshop=$thur_amworkshop";
	$resultam = mysql_query($checkam);
	$noAM = mysql_num_rows($resultam);
	$getAMMax = "select seats, start_time as tueAM_start from workshops where workshopid=$thur_amworkshop";
	$resultammax = mysql_query($getAMMax);
	$row = mysql_fetch_array($resultammax);
	extract($row);
	$maxAM = $seats;
	
	if (($maxAM - $noAM) <= 0) {
		$noTHAMSeats = 0;
	}
	
	$checkpm = "select userid from registered where thur_pmworkshop=$thur_pmworkshop";	
	$resultpm = mysql_query($checkpm);	
	$noPM = mysql_num_rows($resultpm);	
	$getPMMax = "select seats, start_time as tuePM_start from workshops where workshopid=$thur_pmworkshop";	
	$resultpmmax = mysql_query($getPMMax);
	$row = mysql_fetch_array($resultpmmax);
	extract($row);
	$maxPM = $seats;
	
	if (($maxPM - $noPM) <= 0) {
		$noTHPMSeats = 0;
	}
	
	if ($thur_amworkshop == $thamw2) { // no change in workshop choice
			
	} elseif ($noTHAMSeats == 0) {// workshop is full
		$errormessage .= "The Thursday {$tueAM_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		$workshopreg = "update registered set thur_amworkshop=$thur_amworkshop, regdate=now() where userid=$userid";
		mysql_query($workshopreg);
	}
		
	if ($thur_pmworkshop == $thpmw2) { // no change in workshop choice
			
	} elseif ($noTHPMSeats == 0) {// workshop is full
		$errormessage .= "The Thursday {$tuePM_start} session that you selected filled up after you viewed the page.<br>";			
	} else { // ok to add to the workshop
		$workshopreg = "update registered set thur_pmworkshop=$thur_pmworkshop, regdate=now() where userid=$userid";
		mysql_query($workshopreg);
	}

	/**
	 * Update the Keynote seats
	 */
	$currentQuery = "SELECT thur_keynote FROM users WHERE userid=$userid;";
	$currentResult = mysql_query( $currentQuery );
	extract( mysql_fetch_array($currentResult) );

	if( $thur_keynoteIn !== '' ) {
		$currentQuery = "SELECT thur_keynote FROM users WHERE userid=$userid;";
		$currentResult = mysql_query( $currentQuery );
		extract( mysql_fetch_array($currentResult) );

		if( $thur_keynoteIn == $thur_keynote ) {
			//No changes to make
		} else {
			//Update Keynote Seats
			$seatDirection = (string) $thur_keynoteIn == 'no' ? '+' : '-';

			$keynoteSeatsQuery = (string) "UPDATE keynotes SET seats=seats{$seatDirection}1 WHERE day='thu';";
			echoToConsole( 'Thur_keynote query: ' . $keynoteSeatsQuery, true );
			
			$keynoteSeatsResult = mysql_query( $keynoteSeatsQuery );

			$keynoteQuery = (string) "UPDATE users SET thur_keynote='$thur_keynoteIn' WHERE userid=$userid;";
			$keynoteResult = mysql_query( $keynoteQuery );
		}// end if statement
	}// end if statement

	/**
	 * Update the 'registered' columns in users table
	 */
	if( countUsersSessions( $userid ) == 0 ) {
		$notRegisteredQuery = "UPDATE users SET registered='no' WHERE userid=$userid;";
		$notRegisteredResult = mysql_query( $notRegisteredQuery );
	} else {
		$notRegisteredQuery = "UPDATE users SET registered='yes' WHERE userid=$userid;";
		$notRegisteredResult = mysql_query( $notRegisteredQuery );
	}//end if statement
	/**
	 * End Thursday Workshops
	 */
}// end if statement (errormessages)

?>
	<div class="main ui-corner-bottom">
<?php include "pdweek.php"; ?>

<?php
if( !$noErrors ) {
	echo '<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		<strong>Alert!:</strong> There was an error! Your registration could not be completed, as the following feilds were missing:
		' . $errormessage . '</p>
	</div>
</div>';
} else if ($prevfound == 1) {
?>
<div class="ui-widget">
	<div class="ui-state-info ui-corner-all" style="padding: 0 .7em; background: none; border-size: 2px"> 
		
		<p><strong>Thanks, <?php echo htmlentities(stripslashes($firstname), ENT_QUOTES) ; ?>, for choosing your sessions!</strong></p>
		
	<?php if ($errormessage != "") { ?>
		<div class="ui-widget">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
				<strong>Warning!:</strong> The following warnings were raised while processing your changes:<br>
				<?php echo "$errormessage<br><br><strong>Note: </strong>If a session filled up, you can use the link below to go back to the Registration Page and select another session from the many exciting sessions that are still available."; ?>
				</p>
			</div>
		</div>
	<?php } ?>
		<p>Here is what we recorded in our database:</p>

<?php
	$selectu = "select * from users where userid={$userid}";
	$selectw = "select * from registered where userid={$userid}";
	
	$resultu = mysql_query($selectu);
	$resultw = mysql_query($selectw);
	
	$rowu = mysql_fetch_array($resultu);
	$roww = mysql_fetch_array($resultw);
	
	extract($rowu);
	extract($roww);

	if ($departmentIn == "None" || $departmentIn == "Other-" || $departmentIn == "Choose your department") {
		$departmentIn = "No department selected";
	} else {
		if ($departmentIn == "Other") {
			$departmentIn = "" . htmlentities( $otherdeptIn ) . "";
		} else {
			$departmentIn = "" . htmlentities( $departmentIn ) . "";
		}
	}

	$mon_morningQuery = "select title as mon_morning, start_time as mon_morningTime from workshops where workshopid={$mon_amworkshop}";
	$mon_afternoonQuery = "select title as mon_afternoon, start_time as mon_afternoonTime from workshops where workshopid={$mon_pmworkshop}";
	$tue_morningQuery = "select title as tue_morning, start_time as tue_morningTime from workshops where workshopid={$tue_amworkshop}";
	$tue_afternoonQuery = "select title as tue_afternoon, start_time as tue_afternoonTime from workshops where workshopid={$tue_pmworkshop}";
	$wed_morningQuery = "select title as wed_morning, start_time as wed_morningTime from workshops where workshopid={$wed_amworkshop}";
	$wed_afternoonQuery = "select title as wed_afternoon, start_time as wed_afternoonTime from workshops where workshopid={$wed_pmworkshop}";
	$wed_afternoon2Query = "select title as wed_afternoon2, start_time as wed_afternoon2Time from workshops where workshopid={$wed_pmworkshop2}";
	$thur_morningQuery = "select title as thur_morning, start_time as thur_morningTime from workshops where workshopid={$thur_amworkshop}";
	$thur_afternoonQuery = "select title as thur_afternoon, start_time as thur_afternoonTime from workshops where workshopid={$thur_pmworkshop}";

	$resultObject_mam = mysql_query($mon_morningQuery);
	$resultObject_mpm = mysql_query($mon_afternoonQuery);
	$resultObject_tam = mysql_query($tue_morningQuery);
	$resultObject_tpm = mysql_query($tue_afternoonQuery);
	$resultObject_wam = mysql_query($wed_morningQuery);
	$resultObject_wpm = mysql_query($wed_afternoonQuery);
	$resultObject_wpm2 = mysql_query($wed_afternoon2Query);
	$resultObject_tham = mysql_query($thur_morningQuery);
	$resultObject_thpm = mysql_query($thur_afternoonQuery);

	//Disconnect from Database
	mysql_close();

	$resultArray_mam = mysql_fetch_array($resultObject_mam);
	$resultArray_mpm = mysql_fetch_array($resultObject_mpm);
	$resultArray_tam = mysql_fetch_array($resultObject_tam);
	$resultArray_tpm = mysql_fetch_array($resultObject_tpm);
	$resultArray_wam = mysql_fetch_array($resultObject_wam);
	$resultArray_wpm = mysql_fetch_array($resultObject_wpm);
	$resultArray_wpm2 = mysql_fetch_array($resultObject_wpm2);
	$resultArray_tham = mysql_fetch_array($resultObject_tham);
	$resultArray_thpm = mysql_fetch_array($resultObject_thpm);

	extract($resultArray_mam);
	extract($resultArray_mpm);
	extract($resultArray_tam);
	extract($resultArray_tpm);
	extract($resultArray_wam);
	extract($resultArray_wpm);
	extract($resultArray_wpm2);
	extract($resultArray_tham);
	extract($resultArray_thpm);

	if ($mon_morning == "[None]" || $mon_morning == "") {
		$mon_morning = "<strong>No session selected</strong>";
		$mon_morningTime = (string) '';
	} else {
		$mon_morning = "<strong>{$mon_morning}</strong>";
	}

	if ($mon_afternoon == "[None]" || $mon_afternoon == "") {
		$mon_afternoon = "<strong>No session selected</strong>";
		$mon_afternoonTime = (string) '';
	} else {
		$mon_afternoon = "<strong>{$mon_afternoon}</strong>";
	}

	$mon_morning = stripslashes($mon_morning);
	$mon_afternoon = stripslashes($mon_afternoon);

	if ($tue_morning == "[None]" || $tue_morning == "") {
		$tue_morning = "<strong>No session selected</strong>";
		$tue_morningTime = (string) '';
	} else {
		$tue_morning = "<strong>{$tue_morning}</strong>";
	}

	if ($tue_afternoon == "[None]" || $tue_afternoon == "") {
		$tue_afternoon = "<strong>No session selected</strong>";
		$tue_afternoonTime = (string) '';
	} else {
		$tue_afternoon = "<strong>{$tue_afternoon}</strong>";
	}

	$tue_morning = stripslashes($tue_morning);
	$tue_afternoon = stripslashes($tue_afternoon);
	
	if ($wed_morning == "[None]" || $wed_morning == "") {
		$wed_morning = "<strong>No session selected</strong>";
		$wed_morningTime = (string) '';
	} else {
		$wed_morning = "<strong>{$wed_morning}</strong>";
	}

	if ($wed_afternoon == "[None]" || $wed_afternoon == "") {
		$wed_afternoon = "<strong>No session selected</strong>";
		$wed_afternoonTime = (string) '';
	} else {
		$wed_afternoon = "<strong>{$wed_afternoon}</strong>";
	}
	
	if ($wed_afternoon2 == "[None]" || $wed_afternoon2 == "") {
		$wed_afternoon2 = "<strong>No session selected</strong>";
		$wed_afternoon2Time = (string) '';
	} else {
		$wed_afternoon2 = "<strong>{$wed_afternoon2}</strong>";
	}

	$wed_morning = stripslashes($wed_morning);
	$wed_afternoon = stripslashes($wed_afternoon);
	$wed_afternoon2 = stripslashes($wed_afternoon2);

	if ($thur_morning == "[None]" || $thur_morning == "") {
		$thur_morning = "<strong>No session selected</strong>";
		$thur_morningTime = (string) '';
	} else {
		$thur_morning = "<strong>{$thur_morning}</strong>";
	}

	if ($thur_afternoon == "[None]" || $thur_afternoon == "") {
		$thur_afternoon = "<strong>No session selected</strong>";
		$thur_afternoonTime = (string) '';
	} else {
		$thur_afternoon = "<strong>{$thur_afternoon}</strong>";
	}

	$thur_morning = stripslashes($thur_morning);
	$thur_afternoon = stripslashes($thur_afternoon);

	if ($mon_lunch == "nnn") {
		$mon_lunch = "No";
	}

	if ($tue_lunch == "nnn") {
		$tue_lunch = "No";
	}

	require_once( "lib/releaseDate.php" );

	$fotcReleased = (bool) false;

	if( daysRemaining( 'tue' ) == 0 ) {
		$fotcReleased = true;
	}// end if statement

	echo "
	<div class=\"regOverviewLabelContainer\">\n
		<span class=\"text-title-nosize lower-space block\">Profile</span>\n
		<span class=\"lower-space block\">Last name:</span>\n
		<span class=\"lower-space block\">First name:</span>\n
		<span class=\"lower-space block\">Department:</span><br>\n

		<span class=\"text-title-nosize lower-space block\">Monday</span>\n
		<span class=\"lower-space block\"><strong>9:00am</strong> Keynote:</span>\n
		<span class=\"lower-space block\"><strong>12:00pm</strong> Lunch:</span>\n
		<span class=\"lower-space block\"><strong>{$mon_morningTime}</strong> Session A:</span>\n
		<span class=\"lower-space block\"><strong>{$mon_afternoonTime}</strong> Session B:</span><br>\n

		<span class=\"text-title-nosize lower-space block\">Tuesday</span>\n
		<span class=\"lower-space block\">Focus on Teaching Conference:</span>\n" ;
	if( $fotc == "yes" ) {
		echo "		<span class=\"lower-space block\"><strong>9:00am</strong> Keynote</span>\n
		<span class=\"lower-space block\"><strong>10:30am</strong> Tech Cafe & Human Library</span>\n
		<span class=\"lower-space block\"><strong>12:00pm</strong> Lunch</span>\n
		" ;
	}// end if statement

	if( $fotcReleased ) {
		echo "		<span class=\"lower-space block\"><strong>{$tue_morningTime}</strong> Session A:</span>\n
	<span class=\"lower-space block\"><strong>{$tue_afternoonTime}</strong> Session B:</span>\n";
	} else {
		echo "		<span class=\"lower-space block\"><strong>{$tue_morningTime}</strong> Session A:</span>\n
	<span class=\"lower-space block\"><strong>{$tue_afternoonTime}</strong> Session B:</span>\n";
	}// end if statement

	if( $fotc == "yes" ) {
		echo "<span class=\"lower-space block\"><strong>3:45pm</strong> Open House - Library Commons</span><br>\n";
	} else {
		echo "<br>\n";
	}// end if statement

	echo "		<span class=\"text-title-nosize lower-space block\">Wednesday</span>\n
		<span class=\"lower-space block\"><strong>{$wed_morningTime}</strong> Session A:</span>\n
		<span class=\"lower-space block\"><strong>{$wed_afternoonTime}</strong> Session B:</span>\n
		<span class=\"lower-space block\"><strong>{$wed_afternoon2Time}</strong> Session C:</span><br>\n

		<span class=\"text-title-nosize lower-space block\">Thursday</span>\n
		<span class=\"lower-space block\"><strong>{$thur_morningTime}</strong> Session A:</span>\n
		<span class=\"lower-space block\"><strong>1:00pm</strong> College-wide Update:</span>\n
	</div>\n
	<div class=\"regOverviewControlContainer\">\n
		<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n
		<span class=\"lower-space block\"><strong>" . htmlentities( stripslashes($lastname) ) . "</strong></span>\n
		<span class=\"lower-space block\"><strong>" . htmlentities( stripslashes($firstname) ) . "</strong></span>\n
		<span class=\"lower-space block\"><strong>" . htmlentities( stripslashes($departmentIn) ) . "</strong></span><br>\n

		<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n
		<span class=\"lower-space block\"><strong>";
		if($mon_keynote == "yes") echo "Yes";
		if($mon_keynote == "no") echo "No";
		echo "</strong></span>\n
		<span class=\"lower-space block\"><strong>";
		if($mon_lunch == "yes") {
			echo "Yes";
			if( $vegetarian == "yes" ) {
				echo " (Vegetarian)";
			}
		} else {
			echo "No";
		}
		echo "</strong></span>\n
		<span class=\"lower-space block\">{$mon_morning}</span>\n
		<span class=\"lower-space block\">{$mon_afternoon}</span><br>\n
		
		<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n
		<span class=\"lower-space block\"><strong>";
                if($fotc == "yes") { echo "Yes"; }
                if($fotc == "no") { echo "No"; }
                if($fotc == "wl") { echo "Waitlisted"; }
		echo "</strong></span>\n";
		
		if( $fotc == "yes" ) {
			echo "		<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n
		<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n
		<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n";
		}// end if statement

		if( $fotcReleased ) {
			echo "\t\t<span class=\"lower-space block\">{$tue_morning}</span>\n
		<span class=\"lower-space block\">{$tue_afternoon}</span>\n";
		} else {
			echo "\t\t<span class=\"block lower-space\"><strong>(you will be notified when these are available for registration)</strong></span>\n";
			echo "\t\t<span class=\"block lower-space\"><strong>(you will be notified when these are available for registration)</strong></span>\n";
		}// end if statement
		
		if( $fotc == "yes" ) {
			echo "<span class=\"lower-space block\">&nbsp;&nbsp;</span><br>\n";
		} else {
			echo "<br>\n";
		}// end if statement
		
		echo "\t\t<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n
		<span class=\"lower-space block\">{$wed_morning}</span>\n
		<span class=\"lower-space block\">{$wed_afternoon}</span>\n
		<span class=\"lower-space block\">{$wed_afternoon2}</span><br>\n

		<span class=\"lower-space block\">&nbsp;&nbsp;</span>\n
		<span class=\"lower-space block\">{$thur_morning}</span>\n
		<span class=\"lower-space block\"><strong>";
                if($thur_keynote == "yes") { echo "Yes, Alumni Hall (Barrie Campus)"; }
                if($thur_keynote == "no") { echo "No"; }
		echo "</strong></span>\n
	</div>\n
	<br>\n";

/**
 * Confirmation eMail
 */

//Construct the reference message
$emailReference = (string) '';

$emailReference .= "Hello " . htmlentities( stripslashes( $firstname ) ) . " and thank you for registering for Georgian College's PD Week.<br><br>\r\n\r\n";
$emailReference .= "Here is what we recorded for your Profile:<br>\r\n";
$emailReference .= "<span style=\"margin-left: 10px;\">First name: " . htmlentities( stripslashes( $firstname ) ) . "</span><br>\r\n";
$emailReference .= "<span style=\"margin-left: 10px;\">Last name: " . htmlentities( stripslashes( $lastname ) ) . "</span><br>\r\n";
$emailReference .= "<span style=\"margin-left: 10px;\">Department: " . htmlentities( stripslashes( $departmentIn ) ) . "</span><br><br>\r\n\r\n";
$emailReference .= "Here is a list of your sessions:<br>\n" . listWorkshops($userid, false) . "<br><br>\n\n";
$emailReference .= "If there was a mistake made or you simply change your mind, you can <a href=\"{$str_appURL}registration2.php?r={$validate2}\">click here</a> to return to your Profile.<br><br>\r\n\r\n";
$emailReference .= "If the link does not work, copy & paste this address into your web-browser manually:<br>{$str_appURL}registration2.php?r={$validate2}\r\n";

//Construct the headers
$emailHeaders = "MIME-Version: 1.0\r\n";
$emailHeaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
$emailHeaders .= "From: {$str_emailSender}\r\n";
$emailHeaders .= "Reply-To: {$str_emailReplyTo}\r\n";
?>
		<div>
			<p>
				If you are satisfied with your selections please click the "Confirm Sessions" button.  You will receive an email outlining the dates and times of your sessions.  If you would like to make any changes please click the “Back to the Registration Page” button to return to the session selection page.<br><br>
				<a href="registration2.php?r=<?=$validate2;?>"><span class="button-green">&#60;&#45;&#45;Back to the registration page</span></a>
<?php
	$_SESSION['emailReference'] = $emailReference;
	$_SESSION['emailHeaders'] = $emailHeaders;
	$_SESSION['email'] = $email;	
?>
				<span class="button-green" onClick="javascript:window.location='dispatchConfirmation.php';"><a href="dispatchConfirmation.php">Confirm Sessions</a></span>
			</p>
			<p>
				If you have any questions, please email <a href="mailto:<?=$str_supportEmail;?>" style="color: blue;"><?=$str_supportEmail;?></a>.
			</p>
		</div>
	</div>
</div>
<?php
} else {
?>
<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
		<p>
			<strong>Alert:</strong> There was an error! We couldn't complete your registration. Please email <a href="mailto:<?=$str_supportEmail;?>" style="color: blue;"><?=$str_supportEmail;?></a> for assistance.
		</p>
	</div>
</div>

<?php	
}// end if statement

$flt_time_end = (float) microtime( true );
$flt_time_duration = (float) $flt_time_end - $flt_time_start;
echoToConsole( "Executed in: {$flt_time_duration} seconds.", true );
?>
	</div>
		
    </body>
</html>