<?php
	/*
	 * listworkshops.php v1.3.101	-	pdweek
	 */

	if( !isset( $str_appURL ) ) {
		if( file_exists( '/data/environment.php' ) ) {
			require_once( '../data/environment.php' );
		} else if( file_exists( '../data/environment.php' ) ) {
			require_once( '../data/environment.php' );
		}// end if statement
	}// end if statement

	if( @!function_exists(daysRemaining) ) {
		if( file_exists( 'releaseDate.php' ) ) {
			require_once( 'releaseDate.php' );
		} else if( file_exists( 'lib/releaseDate.php' ) ) {
			require_once( 'lib/releaseDate.php' );
		}// end if statement
	}// end if statement

	if( @!function_exists(echoToConsole) ) {
		if( file_exists( 'logging.php' ) ) {
			require_once( 'logging.php' );
		} else if( file_exists( 'lib/logging.php' ) ) {
			require_once( 'lib/logging.php' );
		}// end if statement
	}// end if statement

	
	function enumerateSessions( &$dbConnectionObject, $str_timeIn, $str_dayIn, $str_startTimeClause, $int_defaultID, $int_selectedID, $str_columnName ) {
		global $mon_amworkshop, $mon_pmworkshop, $tue_amworkshop, $tue_pmworkshop, $wed_amworkshop, $wed_pmworkshop, $wed_pmworkshop2, $thur_amworkshop, $thur_pmworkshop;

		$select = (string) "SELECT * FROM workshops WHERE time='{$str_timeIn}' AND day='{$str_dayIn}' AND datediff(now(), release_date)>=0 AND {$str_startTimeClause} OR workshopid=$int_defaultID ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );

		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for workshops!", true );
		}// end if statement

		$workshopsStillOpen = 0;
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);

			$select2 = "select userid from registered where {$str_columnName}={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);
			
			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $int_selectedID) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $int_selectedID) {
					echo "										<input type=\"radio\" name=\"{$str_columnName}\" value=\"" . $workshopid . "\" checked> <strong>(FULL) " . $title . "</strong>\n";
				} else {
					echo "										<strong>(FULL) $title</strong>";
				}								
			} else {
				if ($workshopid == $int_selectedID) {
					echo "										<input type=\"radio\" name=\"{$str_columnName}\" value=\"" . $workshopid . "\" checked> <strong>" . $title . "</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"{$str_columnName}\" value=\"" . $workshopid . "\"> <strong>" . $title . "</strong>\n";
				}
			}
			if ($workshopid == $int_defaultID) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' .  $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><strong>Location = ' . $room . '</strong>
										<br><br>' . $description . "\n";
			}
			echo "									</div><!-- sessionContainer -->\n";
			$n += 1;
		}// end of while loop
	}// end enumerateSessions() function

	/**
	 * Given a userID, the listWorkshops function will print out the
	 * description of all workshops
	 * 
	 * @param String List of workshops the user has registered for.
	 */
	function listWorkshops( $usersIdIn, $combinedOutput = true ) {
		//Reference the global variables
		global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb;
		global $str_dbCharset, $str_appURL;

		$tue_daysRemaining = daysRemaining('tue');

		//Connect to the database
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

		//Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

		//Get the user's workshop IDs
		$workshopIDQuery = (string) "SELECT mon_amworkshop,
			mon_pmworkshop, tue_amworkshop, tue_pmworkshop,
			wed_amworkshop, wed_pmworkshop, wed_pmworkshop2,
			thur_amworkshop, thur_pmworkshop
		FROM registered
		WHERE userid=$usersIdIn;";

		$workshopIDResultObject = mysqli_query( $dbConnectionObject, $workshopIDQuery );

		$workshopOutput = (string) "";

		if( mysqli_num_rows( $workshopIDResultObject ) !== 1 ) {
			echoToConsole( 'The userID was not found in Registered table!', true );

			if( is_object( $workshopIDResultObject ) ) {
				mysqli_free_result( $workshopIDResultObject );
			}// end if statement

			/**
			 * Try to create a link for the user to get out of here
			 */
			$str_userProfileQuery = (string) "SELECT validate2 FROM users WHERE userid={$usersIdIn};";
			$mx_userProfileResult = mysqli_query( $dbConnectionObject, $str_userProfileQuery );

			if( mysqli_num_rows( $mx_userProfileResult ) == 1 ) {
				$userProfileResultRow = mysqli_fetch_array( $mx_userProfileResult );
				$validate2 = $userProfileResultRow[0];
				$workshopOutput .= '<p>You have no Session information to display, as you have not yet reviewed your Profile.</p>';
				$workshopOutput .= "<p>Thankfully, your Profile has been located here:<br><a href=\"{$str_appURL}registration2.php?r={$validate2}\">{$str_appURL}registration2.php?r={$validate2}</a></p>\n";
			} else {
				/**
				 * Link creation has failed, just give up already!
				 */
				$workshopOutput .= '<p>You have no Session information to display, as you have not yet reviewed your Profile.</p>';
				$workshopOutput .= '<p>Normally you would be provided with a link back to your Profile, however your Profile could not be found either!<br><br>Feel free to contact <a href="mailto:micheal.walls@georgiancollege.ca">Micheal.Walls@GeorgianCollege.ca</a> for further assistance.';
			}// end if statement

			if( is_object( $mx_userProfileResult ) ) {
				mysqli_free_result( $mx_userProfileResult );
			}// end if statement

			if( is_object( $dbConnectionObject ) ) {
				mysqli_close( $dbConnectionObject );
			}// end if statement

			return (string) $workshopOutput;
		} else if( is_object( $workshopIDResultObject ) ) {
			$workshopIDResultArray = mysqli_fetch_array( $workshopIDResultObject );

			mysqli_free_result( $workshopIDResultObject );

			extract( $workshopIDResultArray );

			//Start Monday
				$workshopQuery = "select title as mon_titleA, room as mon_roomA, start_time as mon_timeA from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $mon_amworkshop ) . " and day='mon'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$mon_roomA = " &#45; " . $mon_roomA;
					} else {
						echoToConsole( "Monday's AM workshopResultArray was null!", true );
						$mon_titleA = (string) "No session selected";
						$mon_roomA = (string) '';
						$mon_timeA = (string) '';
					} // end if statement
				}
				
				$workshopQuery = "select title as mon_titleB, room as mon_roomB, start_time as mon_timeB from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $mon_pmworkshop ) . " and day='mon'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );
				
				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$mon_roomB = " &#45; " . $mon_roomB;
					} else {
						echoToConsole( "Monday's PM workshopResultArray was null!", true );
						$mon_titleB = (string) "No session selected";
						$mon_roomB = (string) '';
						$mon_timeB = (string) '';
					} // end if statement
				}
				
				$mon_titleA = stripslashes($mon_titleA);
				$mon_titleB = stripslashes($mon_titleB);

				if ($mon_titleA == "[None]" || $mon_amworkshop == "100") {
					$mon_titleA = "No session selected";
				}

				if ($mon_titleB == "[None]" || $mon_pmworkshop == "101") {
					$mon_titleB = "No session selected";
				}
			//End Monday
			
			//Start Tuesday
				$workshopQuery = "select title as tue_titleA, room as tue_roomA, start_time as tue_timeA from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $tue_amworkshop ) . " and day='tue'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$tue_roomA = " &#45; " . $tue_roomA;
					} else {
						echoToConsole( "Tuesday's AM workshopResultArray was null!", true );
						if( $tue_daysRemaining > 0 ) {
							$tue_titleA = (string) "(you will be notified when these are available for registration)";
							$tue_timeA = (string) '';
							$tue_roomA = (string) '';
						} else {
							$tue_titleA = (string) "No session selected";
							$tue_timeA = (string) '';
							$tue_roomA = (string) '';
						}// end if statement
					} // end if statement
				}
				
				$workshopQuery = "select title as tue_titleB, room as tue_roomB, start_time as tue_timeB from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $tue_pmworkshop ) . " and day='tue'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );
				
				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$tue_roomB = " &#45; " . $tue_roomB;
					} else {
						echoToConsole( "Tuesday's PM workshopResultArray was null!", true );
						if( $tue_daysRemaining > 0 ) {
							$tue_titleB = (string) "(you will be notified when these are available for registration)";
							$tue_timeB = (string) '';
							$tue_roomB = (string) '';
						} else {
							$tue_titleB = (string) "No session selected";
							$tue_timeB = (string) '';
							$tue_roomB = (string) '';
						}// end if statement
						
					} // end if statement
				}
				
				$tue_titleA = stripslashes($tue_titleA);
				$tue_titleB = stripslashes($tue_titleB);

				if ($tue_titleA == "[None]" || $tue_amworkshop == "100") {
					if( $tue_daysRemaining > 0 ) {
							$tue_titleA = (string) "(you will be notified when these are available for registration)";
							$tue_timeA = (string) '';
							$tue_roomA = (string) '';
						} else {
							$tue_titleA = (string) "No session selected";
							$tue_timeA = (string) '';
							$tue_roomA = (string) '';
						}// end if statement
				}// end if statement

				if ($tue_titleB == "[None]" || $tue_pmworkshop == "101") {
					if( $tue_daysRemaining > 0 ) {
							$tue_titleB = (string) "(you will be notified when these are available for registration)";
							$tue_timeB = (string) '';
							$tue_roomB = (string) '';
						} else {
							$tue_titleB = (string) "No session selected";
							$tue_timeB = (string) '';
							$tue_roomB = (string) '';
						}// end if statement
				}// end if statement
			//End Tuesday
			
			//Start Wednesday
				$workshopQuery = "select title as wed_titleA, room as wed_roomA, start_time as wed_timeA from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $wed_amworkshop ) . " and day='wed'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$wed_roomA = " &#45; " . $wed_roomA;
					} else {
						echoToConsole( "Wednesday's AM workshopResultArray was null!", true );
						$wed_titleA = (string) "No session selected";
						$wed_roomA = (string) '';
						$wed_timeA = (string) '';
					} // end if statement
				}
				
				$workshopQuery = "select title as wed_titleB, room as wed_roomB, start_time as wed_timeB from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $wed_pmworkshop ) . " and day='wed'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );
				
				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$wed_roomB = " &#45; " . $wed_roomB;
					} else {
						echoToConsole( "Wednesday's PM workshopResultArray was null!", true );
						$wed_titleB = (string) "No session selected";
						$wed_roomB = (string) '';
						$wed_timeB = (string) '';
					} // end if statement
				}
				
				$workshopQuery = "select title as wed_titleC, room as wed_roomC, start_time as wed_timeC from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $wed_pmworkshop2 ) . " and day='wed'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );
				
				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$wed_roomC = " &#45; " . $wed_roomC;
					} else {
						echoToConsole( "Wednesday's PM2 workshopResultArray was null!", true );
						$wed_titleC = (string) "No session selected";
						$wed_roomC = (string) '';
						$wed_timeC = (string) '';
					} // end if statement
				}
				
				$wed_titleA = stripslashes($wed_titleA);
				$wed_titleB = stripslashes($wed_titleB);
				$wed_titleC = stripslashes($wed_titleC);
				

				if ($wed_titleA == "[None]" || $wed_amworkshop == "100") {
					$wed_titleA = "No session selected";
				}

				if ($wed_titleB == "[None]" || $wed_pmworkshop == "101") {
					$wed_titleB = "No session selected";
				}
				
				if ($wed_titleC == "[None]" || $wed_pmworkshop2 == "101") {
					$wed_titleC = "No session selected";
				}
			//End Wednesday
			
			//Start Thursday
				$workshopQuery = "select title as thur_titleA, room as thur_roomA, start_time as thur_timeA from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $thur_amworkshop ) . " and day='thu'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );

				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );
					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$thur_roomA = " &#45; " . $thur_roomA;
					} else {
						echoToConsole( "Thursday's AM workshopResultArray was null!", true );
						$thur_titleA = "No session selected";
						$thur_roomA = (string) '';
						$thur_timeA = (string) '';
					} // end if statement
				}
				
				$workshopQuery = "select title as thur_titleB, room as thur_roomB, start_time as thur_timeB from workshops where workshopid=" . mysqli_real_escape_string( $dbConnectionObject, $thur_pmworkshop ) . " and day='thu'";
				$workshopResultObject = mysqli_query( $dbConnectionObject, $workshopQuery );
				
				if( is_object( $workshopResultObject ) ) {
					$workshopResultArray = mysqli_fetch_array( $workshopResultObject );
					mysqli_free_result( $workshopResultObject );

					if( !is_null( $workshopResultArray ) ) {
						extract( $workshopResultArray );
						$thur_roomB = " &#45; " . $thur_roomB;
					} else {
						echoToConsole( "Thursday's PM workshopResultArray was null!", true );
						$thur_titleB = "No session selected";
						$thur_roomB = (string) '';
						$thur_timeB = (string) '';
					} // end if statement

				}// end if statement
				
				$thur_titleA = stripslashes($thur_titleA);
				$thur_titleB = stripslashes($thur_titleB);

				if ($thur_titleA == "[None]" || $thur_amworkshop == "100") {
					$thur_titleA = "No session selected";
				}

				if ($thur_titleB == "[None]" || $thur_pmworkshop == "101") {
					$thur_titleB = "No session selected";
				}
			//End Thursday

/**
 * Output string (customizeable markup)
 */
			$userInfoQuery = "SELECT fotc, mon_lunch,
				tue_lunch, mon_keynote, thur_keynote, techcafe, vegetarian
				FROM users WHERE userid=$usersIdIn;";

			$userInfoResultObject = mysqli_query( $dbConnectionObject, $userInfoQuery );

			//Close the database connection
			mysqli_close( $dbConnectionObject );

			if( is_object( $userInfoResultObject ) ) {
				$userInfoResultArray = mysqli_fetch_array( $userInfoResultObject );
				extract($userInfoResultArray);

				if( $fotc == 'yes' ) {
					$fotc = 'Yes';
				}// end if statement

				if( $fotc == 'no' ) {
					$fotc = 'No';
				}// end if statement

				if( $fotc == 'wl' ) {
					$fotc = 'Waitlisted';
				}// end if statement

				if( $vegetarian == "yes" ) {
					$str_vegetarianString = (string) ' (Vegetarian)';
				} else {
					$str_vegetarianString = (string) '';
				}// end if statement

				$workshopOutput = (string) <<<END

<span style=\"color: #005A8A; font-size: 1.1em;\"><strong>Monday</strong></span><br>\r\n\r\n
  9:00am Keynote <strong>(Georgian Theater)</strong> = <strong>{$mon_keynote}</strong><br>\r\n
  12:00pm Lunch <strong>(TLC)</strong> = <strong>{$mon_lunch}{$str_vegetarianString}</strong><br>\r\n
  {$mon_timeA} Session A = <strong>{$mon_titleA}{$mon_roomA}</strong><br>\r\n
  {$mon_timeB} Session B = <strong>{$mon_titleB}{$mon_roomB}</strong><br><br>\r\n\r\n
<span style=\"color: #005A8A; font-size: 1.1em;\"><strong>Tuesday (Focus on Teaching Conference)</strong></span><br>\r\n\r\n
  Attending FoTC = <strong>{$fotc}</strong><br>\r\n

END;

				if( $fotc === 'Yes' ) {
					$workshopOutput .= <<<END

  9:00am Keynote - <strong>Alumni Hall</strong><br>\r\n
  10:30am TechCafe &amp; The Human Library - <strong>K318 - K324</strong><br>\r\n
  12:00pm Lunch - <strong>TLC</strong><br>\r\n
  {$tue_timeA} Session A = <strong>{$tue_titleA}{$tue_roomA}</strong><br>\r\n
  {$tue_timeB} Session B = <strong>{$tue_titleB}{$tue_roomB}</strong><br>\r\n
  3:45pm Open House - <strong>Library Commons &amp; Academic Success Centre</strong><br><br>\r\n\r\n

END;
				} else {
					$workshopOutput .= <<<END

	{$tue_timeA} Session A = <strong>{$tue_titleA}{$tue_roomA}</strong><br>\r\n
	{$tue_timeB} Session B = <strong>{$tue_titleB}{$tue_roomB}</strong><br><br>\r\n\r\n

END;
				}// end if statement

				$workshopOutput .= <<<END

<span style=\"color: #005A8A; font-size: 1.1em;\"><strong>Wednesday</strong></span><br>\r\n\r\n
  {$wed_timeA} Session A = <strong>{$wed_titleA}{$wed_roomA}</strong>\r\n<br>
  {$wed_timeB} Session B = <strong>{$wed_titleB}{$wed_roomB}</strong>\r\n<br>
  {$wed_timeC} Session C = <strong>{$wed_titleC}{$wed_roomC}</strong>\r\n\r\n<br><br>
<span style=\"color: #005A8A; font-size: 1.1em;\"><strong>Thursday</strong></span><br>\r\n\r\n
  {$thur_timeA} Session A = <strong>{$thur_titleA}{$thur_roomA}</strong>\r\n<br>
END;

				if( $thur_keynote == 'Yes' || $thur_keynote == 'yes' ) {
				  $workshopOutput .= "1:00pm College-wide Update = <strong>{$thur_keynote}, Alumni Hall (Barrie Campus)</strong>\r\n\r\n<br><br>";
				} else {
				  $workshopOutput .= "1:00pm College-wide Update = <strong>{$thur_keynote}</strong>\r\n\r\n<br><br>";
				}// end if statement
			} else {
				echoToConsole( 'ResultObject was not an Object!', true );
			}// end if statement

		}// end if statement

		return (string) $workshopOutput;
	}// end of listWorkshops method