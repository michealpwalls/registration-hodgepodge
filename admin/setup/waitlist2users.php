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
	}//
?>
<h2><?=$str_appName;?> - Inviting Waitlisted Users</h2>
<?php
//Connect to the Database
mysql_connect($str_dbDomain, $str_dbUser, $str_dbPass);
$db = mysql_select_db($str_dbDb);

if( isset( $_POST['userCount'] ) ) {
	$limit = addslashes( $_POST['userCount'] );

	$selectw = "SELECT id,lastname,firstname,validate2,email FROM waitlist WHERE invited='n' OR invited='no' LIMIT {$limit};";
} else {
	$selectw = "SELECT id,lastname,firstname,validate2,email FROM waitlist WHERE invited='n' OR invited='no';";
}// end if statement

$resultw = mysql_query($selectw);

$errorMessages = (string) "";

if( !is_object( $resultw ) ) {
	if( $resultw == false ) {
		$errorMessages .= "Could not query the waitlist table!<br>\n";
	}
}

//Disconnect from the Database
mysql_close();

if( $errorMessages == "" ) {
	//Connect to the Database
	mysql_connect($str_dbDomain, $str_dbUser, $str_dbPass);
	$db = mysql_select_db($str_dbDb);

	$n = 0;
	$o = 0;
	while($row = mysql_fetch_array($resultw)) {
		extract($row);

		$n += 1;

		/**
		 * Update the user into the fotcAttendees table
		 */
		$insertAttendee = "UPDATE fotcAttendees SET choice='yes' WHERE userid={$id};";
		$insertAttendeeResult = mysql_query($insertAttendee);

		if( $insertAttendeeResult == false ) {
			$errorMessages .= "Failed to insert the user into the FoTC Attendees table!\n";
		}// end if statement

		/**
		 * Mark the user in the waitlist table as having been invited..
		 */
		$updateWL = "UPDATE waitlist SET invited='yes' WHERE id={$id};";
		$updateU = "UPDATE users SET fotc='yes',techcafe='yes',tue_lunch='yes' WHERE userid={$id};";

		$updateWaitlistResultObject = mysql_query( $updateWL );

		if( $updateWaitlistResultObject == false ) {
			$errorMessages .= "Failed to remove user from the waitlist table\n";
		}// end if statement

		$updateUserResultObject = mysql_query( $updateU );

		if( $updateUserResultObject == false ) {
			$errorMessages .= "Failed to change user's FoTC choice from 'wl' to 'yes' in the users table\n";
		}// end if statement

		/**
		 * Email the user a notice
		 */
		$mailSubject = "Removed from " . $str_appName . " Waiting list";

		$mailMessage = "<p>Hello {$firstname}</p>\r\n
			<p>You are receiving this email because you have been taken off the Focus on Teaching Conference waiting list and added to our list of attendees.</p>\r\n
			<p>Your registration page is at <a href='{$str_appURL}registration2.php?r={$validate2}'>{$str_appURL}registration2.php?r={$validate2}</a></p>\r\n
			<p>You can use the list above to make any further customizations to your profile and Sessions. We are looking forward to seeing you on {$str_fotcDate}.</p>\r\n
			<p>If you have any questions, please do not hesitate to contact me.</p>\n
			<p>Thanks,</p>\r\n
			<p>The PD Week Team</p>\r\n
			<p>P.S. You can also follow us on Twitter! Our hashtag for the conference is {$str_confHashtag}.</p>\r\n";

		$mailHeaders = "MIME-Version: 1.0\r\n"; 
		$mailHeaders .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		$mailHeaders .= "From: {$str_emailSender}\r\n";
		$mailHeaders .= "Reply-To: {$str_emailReplyTo}\r\n";

		$mailResult = mail($email, 'PD Week Registration - Removed from FoTC Waiting List', $mailMessage, $mailHeaders);

		if( $mailResult ) {
			$o++;
		}// end if statement
	}// end while loop

	//Disconnect from the Database
	mysql_close();
}// end if statement

if( $errorMessages == "" ) {
	echo( '<div class="ui-widget">
	<div class="ui-state-info ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		<strong>Notice:</strong> Successfully transferred and contacted ' . $n . ' people.<br></p>
	</div>
</div>' );
} else if( $errorMessages == "The waitlist table is completely empty<br>\n" ) {
	echo( '<div class="ui-widget">
	<div class="ui-state-info ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		<strong>Notice:</strong> ' . $errorMessages . '</p>
	</div>
</div>' );
} else {
	echo( '<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		<strong>Alert:</strong> Errors occured!<br>
		Here\'s a list of the errors:</p>
		<p>' . $errorMessages . '</p>
	</div>
</div>' );
}
?>
