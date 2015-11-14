<!DOCTYPE html>
<?php
/*
 * register1-regopen.php v1.4.2	-	conference-registration
 */

require_once 'data/environment.php';
require_once 'data/db.php';
require_once 'lib/prettyErrors.php';
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Georgian College :: <?=$str_appName;?></title>
        <link rel="stylesheet" href="css/gl.css" type="text/css">
        <script type="text/javascript" src="js/checkregfields.js"></script>
    </head>
    <body>
<?php
if( !isset( $_POST['emailnew'] ) ) {
	$emailnew = "";
} else {
	$emailnew = $_POST['emailnew'];
	$ctlMember = (int) filter_input(INPUT_POST, 'ctlMember', FILTER_SANITIZE_NUMBER_INT);
	$firstname = '';
	$lastname = '';

	list($firstname, $lastname) = preg_split("/\./", $emailnew);

	$firstname = strtoupper(substr($firstname,0,1)) . substr($firstname,1);
	$lastname = strtoupper(substr($lastname,0,1)) . substr($lastname,1);

	$email = $firstname . '.' . $lastname . '@GeorgianCollege.ca';
	$email = strtolower($email);

	// Escape characters before sending to the Database
	$emailsl = addslashes($email);
	$firstnamesl = addslashes($firstname);
	$lastnamesl = addslashes($lastname);

	$checkforprev = "select email from users where email='" . $emailsl . "'";

	// Connect to the Database
	require_once 'lib/dbConnect.php';

	$result = mysqli_query( $dbConnectionObject, $checkforprev );
}// end if statement

$prevfound = 0;
if( $emailnew == "" ) {
	// Bypass email and record insert..
} else if (mysqli_num_rows($result) >0) {
	$prevfound = 1;
} else {
	$randomizingstring = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$checkingstring = "";
	for ($i = 1; $i <= 10; $i += 1) {
		$r = rand(0, strlen($randomizingstring) - 1);
		$checkingstring .= substr($randomizingstring, $r, 1);
	}// end for loop

	$subject = "{$str_appName} registration";

	if( !isset( $_POST['ctlMember'] ) ) {
		$message = "<p>Hello " . htmlentities( $firstname ) . "</p>\r\n
		<p>Please click on the link below to continue your registration process for {$str_appName} {$str_currentYear}.</p>\r\n
		<p><a href=\"{$str_appURL}registration2.php?r={$checkingstring}\">Continue with Registration</a></p>\r\n
		<p>If clicking the link above doesn't work, please copy and paste this URL: {$str_appURL}registration2.php?r={$checkingstring}\r\n
		in a new browser window instead.</p>\r\n
		<p>If you've received this mail in error, it's likely that another person entered your email address. Please forward this email to " . htmlentities( $str_emailReplyTo ) . " so that we can investigate this incident.</p>\r\n
		<p>Thank you.</p>\r\n
		<p>The PD Week Team</p>\r\n";
	} else {
		$message = "<p>Hello " . htmlentities( $firstname ) . "</p>\r\n
		<p>You are recieving this email because you have been manually added into the {$str_appName} Registration System.</p>\r\n
		<p>Please click on the link below to continue your registration process.</p>\r\n
		<p><a href='{$str_appURL}registration2.php?r={$checkingstring}'>Continue with Registration</a></p>\r\n
		<p>If the link above does not redirect to the registration page, please copy and paste the following URL into a new browser window: {$str_appURL}registration2.php?r={$checkingstring}</p>\n
		<p>If you've received this mail in error, it's likely that another person entered your email address by mistake. Please forward this email to " . htmlentities( $str_emailReplyTo ) . " so that we can investigate this incident.</p>\n
		<p>Thanks.</p>\r\n
		<p>The PD Week Team</p>\r\n";
	}// end if statement

	$headers = "MIME-Version: 1.0" . "\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
	$headers .= "From: {$str_emailSender}\r\n";
	$headers .= "Reply-To: {$str_emailReplyTo}\r\n";

	//Deliver mail
	$deliveryResult = mail($email, $subject, $message, $headers);

	if( isset( $_POST['ctlMember'] ) ) {
		//Initialize user's "user" table
		$insert = "insert into users (firstname, lastname, email, validate2, regdate, fotc, mon_lunch, tue_lunch, techcafe, mon_keynote, wed_keynote, thur_keynote, registered, review) values("
						. "'" . $firstnamesl . "','" . $lastnamesl . "',"
						. "'" . $emailsl . "','" . $checkingstring . "',"
						. "now(), 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no'"
					. ");";
		$insertResult = mysqli_query( $dbConnectionObject, $insert );

		if( is_bool( $insertResult ) ) {
			if( $insertResult === false ) {
				echo '<p>An error occurred while initializing the user\'s "user" table!<br>' . mysqli_error();
			}// end if statement
		}// end if statement

		$select = "select userid from users where email='$emailsl'";
		$result = mysqli_query( $dbConnectionObject, $select );
		$row = mysqli_fetch_array( $result );
		extract($row);

		//Initialize user's "registered" table
		$insertr = "insert into registered (userid, mon_amworkshop, mon_pmworkshop, tue_amworkshop, tue_pmworkshop, wed_amworkshop, wed_pmworkshop, wed_pmworkshop2, thur_amworkshop, thur_pmworkshop) values ($userid, 100, 101, 100, 101, 100, 101, 101, 100, 101)";
		mysqli_query( $dbConnectionObject, $insertr );

		//Initialize user's "fotcAttendees" table
		$insertFotc = "insert into fotcAttendees (userid, choice) values ($userid, 'yes')";
		mysqli_query( $dbConnectionObject, $insertFotc );

		//Close the Database connection
		mysqli_close( $dbConnectionObject );
	} else {
		//Initialize user's "user" table
		$insert = "insert into users (firstname, lastname, email, validate2, regdate) values("
					. "'" . $firstnamesl . "','" . $lastnamesl . "',"
					. "'" . $emailsl . "','" . $checkingstring . "',"
					. "now());";

		mysqli_query( $dbConnectionObject, $insert );

		//Close the Database connection
		mysqli_close( $dbConnectionObject );
	}// end if statement
}// end if statement
?>
		<div class="main ui-corner-bottom">

<?php include "pdweek.php"; ?>

<?php
if ($emailnew == "") {
    require 'views/errorMessages_missingEmail.php';
    showPrettyError($errormessage, 'error', true);
} else if ($prevfound == 0) {
?>
			<div class="ui-widget">
				<div class="ui-state-info ui-corner-all upper-space" style="padding: 0 .7em;"> 
					<p>
<?php
	if ($firstname != "" && $ctlMember !== 1) {
?>
						<strong>Thanks <?php echo htmlentities( $firstname ); ?>!</strong>
					</p>
<?php
	} else {
?>
						<strong>Thanks!</strong>
					</p>
<?php
	}// end if statement
?>
					<p>
						An email has been sent to <?php echo $email; ?>. <?php if( isset( $_POST['ctlMember'] ) ) { echo( "The email contains instructions for them to continue their registration. <a href=\"index.php?ctl=1\" style=\"color: blue;\">Click here</a> to pre-register another user." ); } else { echo( "Please click on the link provided to continue your registration process. If you don't receive our email within the hour please contact <a href=\"mailto:{$str_emailReplyTo}\" style=\"color: blue;\">{$str_emailReplyTo}</a>" ); } ?>
					</p>
				</div>
			</div>
<?php
} else {
    require 'views/errorMessages_userAlreadyExists.php';
    showPrettyError($errormessage, 'error', true);
}// end if statement
?>
		</div>
    </body>
</html>
