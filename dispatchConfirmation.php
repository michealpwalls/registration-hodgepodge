<!DOCTYPE html>

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

	session_start();
	
	require_once( "data/environment.php" );
        require_once( "data/db.php" );
	require_once( "lib/logging.php" );
?>

<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>Georgian College :: <?=$str_appName;?> <?=$str_currentYear;?></title>

		<!--
			JQuery-UI css definitions were manually implemented in gl.css
			to fix IE11 performance issue when full jquery-ui.css was
			loaded.
		-->
		<link rel="stylesheet" href="css/gl.css">

	</head>
	<body>
		<div class="main ui-corner-bottom">
			<div class="ui-widget">
<?php
	include 'pdweek.php';
?>
				<div class="ui-state-info ui-corner-all" style="padding: 0 .7em; background: none; border-size: 2px">
					
<?php
	$errorMessage = (string) '';

	if( !isset( $_SESSION['emailReference'] ) ) {
		$errorMessage .= 'Missing the Message,';
	}// end if statement

	if( !isset( $_SESSION['emailHeaders'] ) ) {
		$errorMessage .= 'Missing the Mail Headers,';
	}// end if statement

	if( !isset( $_SESSION['email'] ) ) {
		$errorMessage .= 'Missing the Address to send to,';
	}// end if statement

	if( $errorMessage == '' ) {
		$email = $_SESSION['email'];
		$emailReference = $_SESSION['emailReference'];
		$emailHeaders = $_SESSION['emailHeaders'];

		//Dispatch the mail
		$deliveryResult = mail($email, 'PD Week Registration Confirmation', $emailReference, $emailHeaders);

		if( !$deliveryResult ) {
			echoToConsole( 'Failed to dispatch the reference email!', true );
			
			echo <<<END

				<div class="upper-space lower-space ui-state-error ui-corner-all">
					<p>Failed to dispatch the reference email!</p>
				</div>

END;
		} else {
			echoToConsole( "Mail dispatched to {$email}", true );

			echo <<<END

				<div class="upper-space lower-space ui-state-info ui-corner-all">
					<p>Thank you for confirming your PD Week sessions. An email has been sent to {$email} which contains your profile and session information. It also contains a link that you can use to get back and review your PD Week Registration profile at any time.</p>
				</div>

END;
		}// end if statement
	} else {
		echoToConsole( "There was an error while processing your input. Here are the errors: {$errorMessage}", true );
		echo <<<END

				<div class="upper-space lower-space ui-state-error ui-corner-all">
					<p>
						There was an error while processing your input. Here are the errors:<br><br>
						
						{$errorMessage}
					</p>
				</div>

END;
	}// end if statement
?>
				</div>
			</div>
		</div>
    </body>
</html>

<?php
/**
 * Destroy the session data
 */

	$_SESSION['emailReference'];
	$_SESSION['emailHeaders'];
	$_SESSION['email'];
	

	/**
	 * On second thought, maybe destroying georgianc.on.ca's cookie
	 * is not a good idea? hehe!
	
	//Empty the entire global session array
	$_SESSION = (array) Array();
	
	//Recreate the session cookie
	$cookie_params = session_get_cookie_params();

	setcookie( session_name(), '', 0,
		$cookie_params['path'],
		$cookie_params['domain'],
		$cookie_params['secure'],
		$cookie_params['httponly']
	);//name, value, expireIn, path, domain, sslOnly, httpOnly
	
	 *
	 */

	session_destroy();
?>