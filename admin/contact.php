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

/**
 * Uses PEAR::Mail v294747 with patches applied for modern PHP v5.x
 *		Mail.php patched on lines 253 and 254.
 *		Patch Details:
 *			line 253: Instantiate the Mail_RFC822 object.
 *			line 254: Call non-static method parseAddresses() of Mail_RFC822 object.
 * 
 *		RFC822.php patched on lines 210 and 211.
 *		Patch Details:
 *			line 210: Instantiate the PEAR object
 *			line 211: Call non-static method isError() as a child of PEAR object.
 * 
 * 
 *		Patched by: michealpwalls@gmail.com
 */
 
    require_once "../lib/listworkshops.php";
    require_once "../lib/logging.php";

if (defined("ADMTKN")) {
    $optionsStripAll = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
    $adminTokenInput = filter_input(INPUT_GET, 'admtkn', FILTER_SANITIZE_STRING, $optionsStripAll);

    if (!empty($adminTokenInput)) {
        if ($adminTokenInput !== ADMTKN) {
            $errorTitle = "Unauthorized Access";
            $errorMessage = "You must be part of the administrative staff in order to enter this section.";
            showPrettyError($errorMessage, 'error', false, $errorTitle);
        } else {
            $bln_isAdminUser = $bln_subComponent = (bool) true;
        }// if admtkn provided is wrong
    } else {
        $errorTitle = "Unauthorized Access";
        $errorMessage = "You must be part of the administrative staff in order to enter this section.";
        showPrettyError($errorMessage, 'error', false, $errorTitle);
    }// if admtkn not provided in url
} else {
    require "../data/environment.php";
    require "{$str_appLocation}lib/prettyErrors.php";
    require "{$str_appLocation}lib/logging.php";
    require "{$str_appLocation}views/errorMessages_en_directComponentAccess.php";

    showPrettyError($errorMessage, 'error', true, 'Direct Component Access');
}// If ADMTKN not defined

require_once "../lib/Mail-1.2.0/Mail.php";

/**
 * Input Validation
 */
	$errorMessages = (string) "";

	if( isset( $_POST['isPosting'] ) ) {

		if( !isset( $_POST['contactOptions_all'] ) ) {
			$errorMessages .= "Missing 'All Users' Choice\n";
		} else {
			if( $_POST['contactOptions_all'] == "no" ) {
				if( !isset( $_POST['contactOptions_waitlisted'] ) ) {
					$errorMessages .= "Missing 'Waitlisted Users' Choice\n";
				}

				if( !isset( $_POST['contactOptions_review'] ) ) {
					$errorMessages .= "Missing 'Has Reviewed' Choice\n";
				}

				if( !isset( $_POST['contactOptions_registered'] ) ) {
					$errorMessages .= "Missing 'Registered Status' Choice\n";
				}

				if( !isset( $_POST['contactOptions_lunch'] ) ) {
					$errorMessages .= "Missing 'Lunch Status' Choice\n";
				}

				if( !isset( $_POST['contactOptions_keynote'] ) ) {
					$errorMessages .= "Missing 'Keynote Status' Choice\n";
				}

				if( !isset( $_POST['contactOptions_fotc'] ) ) {
					$errorMessages .= "Missing 'FoTC Status' Choice\n";
				}
				
				if( !isset( $_POST['contactOptions_extraordinary'] ) ) {
					$errorMessages .= "Missing 'Extraordinary Status' Choice\n";
				}
			}
		}
		
		if( !isset( $_POST['contactOptions_subject'] ) ) {
			$errorMessages .= "Missing 'Subject' Line\n";
		} else {
			$subject = trim( $_POST['contactOptions_subject'] );
			if( empty( $subject ) ) {
				$errorMessages .= "Missing 'Subject' Line\n";
			}
		}

		if( !isset( $_POST['contactMessage'] ) ) {
			$errorMessages .= "Missing 'Message Content'\n";
		} else {
			$contactMessage = trim( $_POST['contactMessage'] );
			if( empty( $contactMessage ) ) {
				$errorMessages .= "Missing 'Message Content'\n";
			}
		}
		
		if( !isset( $_POST['contactOptions_from'] ) ) {
			$errorMessages .= "Missing 'From' Address\n";
		} else {
			$fromAddress = trim( $_POST['contactOptions_from'] );
			if( empty( $fromAddress ) ) {
				$errorMessages .= "Missing 'From' Address\n";
			}
		}

		if( !isset( $_POST['contactOptions_reply'] ) ) {
			$errorMessages .= "Missing 'Reply-To' Address\n";
		} else {
			$replyTo = trim( $_POST['contactOptions_reply'] );
			if( empty( $replyTo ) ) {
				$errorMessages .= "Missing 'Reply-To' Address\n";
			}
		}

		if( $errorMessages != "" ) {
			// Errors were collected
			echo( "Errors were collected. They are listed below:<br>\n" );
			echo( $errorMessages . "\n" );
		} else {
/**
 * Build the SELECT query
 */
			$usersQuery = (string) "SELECT DISTINCT users.userid as userid,
									users.firstname as firstname,
									users.email as email,
									users.validate2 as validate2,
									users.fotc as fotc,
									users.mon_lunch as mon_lunch,
									users.tue_lunch as tue_lunch,
									users.mon_keynote as mon_keynote,
									users.fotc as fotc,
									users.fotc as tue_keynote,
									users.extraordinary as extraordinary,
									users.thur_keynote as thur_keynote
									FROM users";

			if( $_POST['contactOptions_all'] == "yes" ) {
			/**
			 * All users	(Mutually Exclusive option)
			 */
				$usersQuery .= ";";
			} else if( $_POST['contactOptions_waitlisted'] == "yes" ) {
			/**
			 * Waitlisted users	(Mutually Exclusive option)
			 */
				$usersQuery = "SELECT id, firstname, email,validate2 FROM waitlist WHERE invited='n' OR invited='no';";
			} else {
				$whereClause = (bool) false;

				/**
				 * Registered
				 */
				if( $_POST['contactOptions_registered'] == "yes" ) {
					$usersQuery .= ", registered WHERE users.registered='yes'";
					$whereClause = true;
				}// end if statement

				if( $_POST['contactOptions_registered'] == "no" ) {
					$usersQuery .= ", registered WHERE users.registered='no'";
					$whereClause = true;
				}// end if statement

				/**
				 * Review
				 */
				if( $_POST['contactOptions_review'] == "yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.review='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.review='yes'";
					}// end if statement
				}// end if statement

				if( $_POST['contactOptions_review'] == "no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.review='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.review='no'";
					}// end if statement
				}// end if statement

				/**
				 * FoTC
				 */
				if( $_POST['contactOptions_fotc'] == "yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.fotc='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.fotc='yes'";
					}// end if statement
				}// end if statement

				if( $_POST['contactOptions_fotc'] == "no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.fotc='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.fotc='no'";
					}// end if statement
				}// end if statement

				/**
				 * Extraordinary Experiences
				 */
				if( $_POST['contactOptions_extraordinary'] == "yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.extraordinary='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.extraordinary='yes'";
					}// end if statement
				}// end if statement

				if( $_POST['contactOptions_extraordinary'] == "no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.extraordinary='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.extraordinary='no'";
					}// end if statement
				}// end if statement

				/**
				 * Lunch
				 */
				if( $_POST['contactOptions_lunch'] == "no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_lunch='no' AND users.tue_lunch='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_lunch='no' AND users.tue_lunch='no'";
					}// end if statement
				} else if( $_POST['contactOptions_lunch'] == "yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_lunch='yes' OR users.tue_lunch='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_lunch='yes' OR users.tue_lunch='yes'";
					}// end if statement
				} else if( $_POST['contactOptions_lunch'] == "mon-yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_lunch='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_lunch='yes'";
					}// end if statement
				} else if( $_POST['contactOptions_lunch'] == "mon-no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_lunch='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_lunch='no'";
					}// end if statement
				} else if( $_POST['contactOptions_lunch'] == "tue-yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.tue_lunch='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.tue_lunch='yes'";
					}// end if statement
				} else if( $_POST['contactOptions_lunch'] == "tue-no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.tue_lunch='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.tue_lunch='no'";
					}// end if statement
				}// end if statement
				
				/**
				 * Keynote
				 */
				if( $_POST['contactOptions_keynote'] == "no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_keynote='no' AND users.thur_keynote='no' AND users.fotc='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_keynote='no' AND users.thur_keynote='no' AND users.fotc='no'";
					}// end if statement
				} else if( $_POST['contactOptions_keynote'] == "yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_keynote='yes' OR users.thur_keynote='yes' OR users.fotc='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_keynote='yes' OR users.thur_keynote='yes' OR users.fotc='yes'";
					}// end if statement
				} else if( $_POST['contactOptions_keynote'] == "mon-yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_keynote='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_keynote='yes'";
					}// end if statement
				} else if( $_POST['contactOptions_keynote'] == "mon-no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.mon_keynote='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.mon_keynote='no'";
					}// end if statement
				} else if( $_POST['contactOptions_keynote'] == "tue-yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.fotc='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.fotc='yes'";
					}// end if statement
				} else if( $_POST['contactOptions_keynote'] == "tue-no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.fotc='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.fotc='no'";
					}// end if statement
				} else if( $_POST['contactOptions_keynote'] == "thur-yes" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.thur_keynote='yes'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.thur_keynote='yes'";
					}// end if statement
				} else if( $_POST['contactOptions_keynote'] == "thur-no" ) {
					if( $whereClause == false ) {
						$usersQuery .= " WHERE users.thur_keynote='no'";
						$whereClause = true;
					} else {
						$usersQuery .= " AND users.thur_keynote='no'";
					}// end if statement
				}// end if statement

				$usersQuery .= ";";
			}// end if statement

			echoToConsole( "Query: {$usersQuery}", true );
/**
 * Query the database
 */
			//Connect to the database
			$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

			//Set the character set, for use with mysqli_real_escape_string
			mysqli_set_charset( $dbConnectionObject, $str_dbCharset );
			
			$usersResultObject = mysqli_query( $dbConnectionObject, $usersQuery );
			
			//Disconnect from the Database
			mysqli_close( $dbConnectionObject );

			$idAccumulator = (array) array();
			$emailAccumulator = (array) array();
			$messageAccumulator = (array) array();
			$subjectAccumulator = (array) array();
			$matchesFound = $iterationCounter = $deliveryCount = (int) 0;

			if( is_object( $usersResultObject ) ) {
				$matchesFound = mysqli_num_rows( $usersResultObject );

				while( $row = mysqli_fetch_array($usersResultObject) ) {

					$emailAccumulator[$iterationCounter] = $row['email'];

					/**
					 * Remaps id to work around user's id discrepencies in the
					 * users, registered and waitlist tables.
					 */
					if( isset( $row['id'] ) ) {
						$idAccumulator[$iterationCounter] = $row['id'];
					} else {
						$idAccumulator[$iterationCounter] = $row['userid'];
					}// end if statement
/**
 * [NAME], [PROFILE] replacement
 */
					//Convert name
					$subjectAccumulator[$iterationCounter] = str_replace( "[NAME]", $row['firstname'], $subject );
					$messageAccumulator[$iterationCounter] = str_replace( "[NAME]", $row['firstname'], $contactMessage );

					//Convert validate2 code
					$messageAccumulator[$iterationCounter] = str_replace( "[PROFILE]", "{$str_appURL}registration2.php?r={$row['validate2']}", $messageAccumulator[$iterationCounter] );

					//Convert line-breaks to <br> elements
					$messageAccumulator[$iterationCounter] = str_replace( "\n", "<br>", $messageAccumulator[$iterationCounter] );

/**
 * [WORKSHOP] replacement
 */
					if( strpos( $messageAccumulator[$iterationCounter], "[WORKSHOPS]" ) > 0 ) {
						$messageAccumulator[$iterationCounter] = str_replace( "[WORKSHOPS]", listWorkshops($idAccumulator[$iterationCounter]), $messageAccumulator[$iterationCounter] );
					}// end if statement

					$iterationCounter++;
				}// end while loop

				//Construct option arrays
				//$array_smtpOptions = (array) array();
					//$array_smtpOptions["host"] = 'localhost';
					//$array_smtpOptions["port"] = 25;
					//$array_smtpOptions["auth"] = false;	//Default is FALSE.
					//$array_smtpOptions["username"];
					//$array_smtpOptions["password"];
					//$array_smtpOptions["persist"] = true;
					//$array_smtpOptions["pipelining"] = false;

				$array_sendmailOptions = (array) array();
					$array_sendmailOptions["sendmail_path"] = '/usr/sbin/sendmail';
					$array_sendmailOptions["sendmail_args"] = '-t -i';

				//Instantiate the PEAR Mail object
				$obj_pearMail = new Mail();
				$obj_mailFactory = $obj_pearMail->factory( 'sendmail', $array_sendmailOptions );

				//Send the mail
				//$deliveryResult = mail($email, $iteration_subject, $iteration_contactMessage, $contactHeaders);

				//Iterate through arrays
				for( $i = 0; $i < count($emailAccumulator); $i++ ) {
					$contactHeaders = array();
					$contactHeaders['Content-Type'] = "text/html; charset=iso-8859-1";
					$contactHeaders['To'] = $emailAccumulator[$i];
					$contactHeaders['From'] = $fromAddress;
					$contactHeaders['Subject'] = $subjectAccumulator[$i];
					$contactHeaders['Reply-To'] = $replyTo;
					$contactHeaders['Return-path'] = $replyTo;

					$dispatchResult = $obj_mailFactory->send( $emailAccumulator[$i], $contactHeaders, $messageAccumulator[$i] );
					$deliveryCount++;
				}// end for loop

				echo( "<div class=\"ui-state-info upper-space\">\n" );
				echo( "	<p>" . htmlentities( $deliveryCount ) . " mails sent to " . htmlentities( $matchesFound ) . " users matching the search criteria. Here's a list of recipients:<br>\n" );

				foreach( $emailAccumulator as $recipient ) {
					echo( htmlentities( $recipient ) . "<br></p>\n" );
				}// end foreach loop

				echo( "	<p><strong>Notice:</strong> Although a mail was successfully <em>sent</em> to the above address', there is no garuantee the individuals <em>recieved</em> the message..</p>\n</div>\n" );
			} else {
				echo( "<div class=\"ui-state-error upper-space\">\n	<strong>Alert: </strong>\n	<p>The query failed. The query:</p>\n" . htmlentities( $usersQuery ) . "<br>\n" );
				if( $usersResultObject == false ) {
					echo( "	<p>Query returned: false</p>\n" );
				} else {
					echo( "	<p>Query returned: true</p>\n" );
				}// end if statement

				echo( "</div>\n" );
			}// end if statement
			
			//Free memory associated with the result object
			if( is_object( $usersResultObject ) ) {
				mysqli_free_result( $usersResultObject );
			}// end if statement
		}// end if statement
	} else {
		// User is not posting data, so show them the form
?>
		<div class="ui-state-info upper-space lower-space">
			<p>
				<strong>Note</strong> The URL was just set to: <?php echo htmlentities( $str_appURL ); ?>.<br>All profile links will now use this address.
			</p>
		</div>
		<h3>Contact Users in the Registration System</h3>
		<script>
			var lastEntered = '';
		</script>
		<form method="post" action="index.php?action=contact">
			<fieldset class="regbox upper-space">
				<legend class="regboxtitle">User Criteria</legend><br>
				<div class="contactLabelContainer">
					<div class="lower-space"><label for="contactOptions_all">All in System:</label></div>
					<div class="lower-space"><label for="contactOptions_waitlisted">Waitlisted:</label></div>
					<div class="lower-space"><label for="contactOptions_lunch">Lunch Status:</label></div>
					<div class="lower-space"><label for="contactOptions_fotc">FoTC Status:</label></div>
					<div class="lower-space"><label for="contactOptions_keynote">Keynote Status:</label></div>
					<div class="lower-space"><label for="contactOptions_review">Reviewed Profile:</label></div>
					<div class="lower-space"><label for="contactOptions_registered">Registered in a Workshop:</label></div>
					<div class="lower-space"><label for="contactOptions_extraordinary">Extraordinary Experience:</label></div>
				</div>
				<div class="contactControlContainer">
					<div class="lower-space">
						<label><input type="radio" name="contactOptions_all" value="yes" onClick="javascript:disableControls();" checked>Yes</label>
						<label><input type="radio" name="contactOptions_all" value="no" onClick="javascript:enableControls();">No</label>
					</div>
					<div class="lower-space">
						<label><input type="radio" name="contactOptions_waitlisted" value="default" checked disabled>Doesn't Matter</label>
						<label><input type="radio" name="contactOptions_waitlisted" value="yes" disabled>Yes</label>
					</div>
					<select class="block input-text lower-space" name="contactOptions_lunch" disabled>
						<option value="default" selected>-- doesn't matter --</option>
						<optgroup label="Not Attending...">
							<option value="no">Any Lunch</option>
							<option value="mon-no">Monday's Lunch</option>
							<option value="tue-no">Tuesday's Lunch</option>
						</optgroup>
						<optgroup label="Attending...">
							<option value="yes">Any Lunch</option>
							<option value="mon-yes">Monday's Lunch</option>
							<option value="tue-yes">Tuesday's Lunch</option>
						</optgroup>
					</select>
					<select class="block input-text lower-space" name="contactOptions_fotc" disabled>
						<option value="default" selected>-- doesn't matter --</option>
						<option value="no">Not Attending FoTC</option>
						<option value="yes">Attending FoTC</option>
					</select>
					<select class="block input-text lower-space" name="contactOptions_keynote" disabled>
						<option value="default" selected>-- doesn't matter --</option>
						<optgroup label="Attending..">
							<option value="yes">Any Keynote</option>
							<option value="mon-yes">Monday's Keynote</option>
							<option value="tue-yes">Tuesday's Keynote</option>
							<option value="thur-yes">Thursday's Keynote</option>
						</optgroup>
						<optgroup label="Not Attending...">
							<option value="no">Any Keynotes</option>
							<option value="mon-no">Monday's Keynote</option>
							<option value="tue-no">Tuesday's Keynote</option>
							<option value="thur-no">Thursday's Keynote</option>
						</optgroup>
					</select>
					<select class="block input-text lower-space" name="contactOptions_review" disabled>
						<option value="default" selected>-- doesn't matter --</option>
						<option value="no">Haven't Reviewed Profile</option>
						<option value="yes">Reviewed Profile</option>
					</select>
					<select class="block input-text lower-space" name="contactOptions_registered" disabled>
						<option value="default" selected>-- doesn't matter --</option>
						<option value="yes">Registered for Workshops</option>
						<option value="no">Not Registered for Workshops</option>
					</select>
					<select class="block input-text lower-space" name="contactOptions_extraordinary" disabled>
						<option value="default" selected>-- doesn't matter --</option>
						<option value="yes">Registered for Extraordinary Experiences</option>
						<option value="no">NOT Registered for Extraordinary Experiences</option>
					</select>
				</div>
			</fieldset>
			<fieldset class="regbox upper-space">
				<legend class="regboxtitle">Message Options</legend><br>
				<div class="contactLabelContainer">
					<div class="lower-space"><label for="contactOptions_from">From Address:</label></div>
					<div class="lower-space"><label for="contactOptions_reply">Reply-To Address:</label></div>
					<div class="lower-space"><label for="contactOptions_subject">Subject:</label></div>
					<div class="lower-space"><label for="contactMessage">Message:</label></div>
				</div>
				<div class="contactControlContainer">
					<input class="input-text block lower-space" type="text" name="contactOptions_from" value="<?=$str_emailSender;?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
					<input class="input-text block lower-space" type="text" name="contactOptions_reply" value="<?=$str_emailReplyTo;?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
					<input class="input-text block lower-space" type="text" name="contactOptions_subject" value="Dear [NAME]" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
					<textarea class="input-text block lower-space" name="contactMessage" cols="45" rows="5">Hello, [NAME]
Here are your Workshops:
[WORKSHOPS]</textarea>
					<input type="hidden" name="isPosting" value="yes">
					<input type="submit" value="Dipatch!">
				</div>
			</fieldset>
		</form>
<?php
	}// end if statement
?>