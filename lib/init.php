<?php
$check = filter_input( INPUT_GET, 'r', FILTER_SANITIZE_MAGIC_QUOTES );

if (strlen($check) == 16) {
	$check = substr($check, 0, 15);
}// end if statement

$lastname = $firstname = $email = $registered = $dept = $otherdept = $lunch = $techcafe = $amworkshop = $pmworkshop = $userid = '';

$prevfound = 1;
$userProfile = (array) array();

if ($check != "") {
	$checkforprev = "select * from users where validate2='" . $check . "'";

	$userProfile_resultObject = mysqli_query( $dbConnectionObject, $checkforprev );

	$alreadyregistered = 0;
	if (mysqli_num_rows($userProfile_resultObject) == 0) {
		$prevfound = 0;
	} else {
		$userProfile[] = mysqli_fetch_array( $userProfile_resultObject );
		initUserAccount( $dbConnectionObject );
	}// end if statement
} else {
	$prevfound = 0;
}// end if statement

function initUserAccount( &$dbConnectionObject ) {
	global $userProfile;

	$selectw = "select mon_amworkshop, mon_pmworkshop, tue_amworkshop, tue_pmworkshop, wed_amworkshop, wed_pmworkshop, wed_pmworkshop2, thur_amworkshop, thur_pmworkshop from registered where userid={$userProfile[0]['userid']}";
	$resultw = mysqli_query( $dbConnectionObject, $selectw );

	//Initialize 'registered' table if userid does not exist there.
	if (mysqli_num_rows($resultw) == 0) {
		$initializeRegistered_query = "insert into registered (userid, mon_amworkshop, mon_pmworkshop, tue_amworkshop, tue_pmworkshop, wed_amworkshop, wed_pmworkshop, wed_pmworkshop2, thur_amworkshop, thur_pmworkshop) values ({$userProfile[0]['userid']}, 100, 101, 100, 101, 100, 101, 101, 100, 101)";

		$initializeRegistered_result = mysqli_query( $dbConnectionObject, $initializeRegistered_query );

		if( $initializeRegistered_result == false ) {
			echoToConsole( "Failed to initialize registered table!", true );
		}// end if statement
	} else {
		$userProfile[] = mysqli_fetch_array($resultw);
	}// end if statement

	//Initialize 'fotcAttendees' table if userid does not exist there.
	$checkFoTC_query = "SELECT choice FROM fotcAttendees WHERE userid={$userProfile[0]['userid']};";
	$checkFoTC_result = mysqli_query( $dbConnectionObject, $checkFoTC_query );

	if( mysqli_num_rows( $checkFoTC_result ) == 0 ) {
		$initializeFotc_query = "INSERT INTO fotcAttendees(userid,choice) VALUES({$userProfile[0]['userid']},'{$userProfile[0]['fotc']}');";
		$initializeFotc_result = mysqli_query( $dbConnectionObject, $initializeFotc_query );

		if( $initializeFotc_result == false ) {
			echoToConsole( "Failed to initialize FoTC Attendees table!", true );
		}// end if statement
	}// end if statement

	//Set the 'review' flag in the users table
	$setReviewQuery = "UPDATE users SET review='yes' WHERE userid={$userProfile[0]['userid']};";
	$setReviewResult = mysqli_query( $dbConnectionObject, $setReviewQuery );

	if( $setReviewResult == false ) {
		echoToConsole( "Failed to set review flag.", true );
	}// end if statement

	return (array) $userProfile;
}// end initUserAccount() function