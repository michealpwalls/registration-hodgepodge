<?php

	if( !isset( $str_appURL ) ) {
		require_once( '../data/environment.php' );
	}// end if statement

	if( !function_exists( echoToConsole ) ) {
		require_once( 'logging.php' );
	}// end if statement

	/**
	 * The reports_getFoundUsers() function takes an array of user names and
	 * returns an array of usernames that were found in the PD Week system.
	 * 
	 * @global String $str_dbDomain	Database Domain
	 * @global String $str_dbUser Database Username
	 * @global String $str_dbPass Database Password
	 * @global String $str_dbDb Database Name
	 * @global String $str_dbCharset Database Character Encoding
	 * @param Array $array_userList Array of names to look for
	 * @return Array Array of names that were found.
	 */
	function reports_getFoundUsers( $array_userList ) {
		//Reference the global variables
		global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

		//Array to contain found users
		$array_foundUsers = (array) array();

		$str_customUsers_query = (string) "SELECT firstname,lastname FROM users WHERE CONCAT(CONCAT(firstname,' '),lastname) IN";

		$str_customUsers_query .= "(";

		for( $i = 0; $i < count($array_userList); $i++ ) {
			$str_customUsers_query .= "'" . addslashes( $array_userList[$i] ) . "'";

			if( $i != count($array_userList)-1 ) {
				$str_customUsers_query .= ',';
			}// end if statement

		}// end foreach loop

		$str_customUsers_query .= ");";

		//Connect to the database
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

		//Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

		$mx_customUsers_result = mysqli_query( $dbConnectionObject, $str_customUsers_query );
		
		if( is_object( $mx_customUsers_result ) ) {

			while( $foundUsers_row = mysqli_fetch_array( $mx_customUsers_result ) ) {
				$array_foundUsers[] = $foundUsers_row['firstname'] . ' ' . $foundUsers_row['lastname'];
			}// end while loop

			echo '<br>';

			mysqli_free_result( $mx_customUsers_result );

		} else {
			echoToConsole( 'reports_getFoundUsers(): Select query failed!', true );
			echoToConsole( $str_customUsers_query, true );
		}// end if statement

		mysqli_close( $dbConnectionObject );
		
		return (array) $array_foundUsers;
	}// end reports_getFoundUsers function

	/**
	 * The reports_getMissingUsers() function takes an array of names and returns
	 * an array of names that were NOT found in the PD Week system.
	 * 
	 * @global String $str_dbDomain	Database Domain
	 * @global String $str_dbUser Database Username
	 * @global String $str_dbPass Database Password
	 * @global String $str_dbDb Database Name
	 * @global String $str_dbCharset Database Character Encoding
	 * @param Array $array_userListIn Array of usernames to look for
	 * @return Array Array of names that were NOT found.
	 */
	function reports_getMissingUsers( $array_userListIn ) {
		//Reference the global variables
		global $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb, $str_dbCharset;

		//Declare an array to hold the missing users
		$array_missingUsers = (array) array();

		//Connect to the database
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

		//Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

		for( $i = 0; $i < count( $array_userListIn ); $i++ ) {
			$str_query = "SELECT * FROM users WHERE CONCAT(CONCAT(firstname,' '),lastname)='" . addslashes( $array_userListIn[$i] ) . "';";

			$mx_queryResult = mysqli_query( $dbConnectionObject, $str_query );

			if( is_object( $mx_queryResult ) ) {
				if( mysqli_num_rows( $mx_queryResult ) == 0 ) {
					$array_missingUsers[] = $array_userListIn[$i];
				} else {
					echoToConsole( 'reports_getMissingUsers(): Query returned a row', true );
				}// end if statement

				mysqli_free_result( $mx_queryResult );
			} else {
				echoToConsole( 'reports_getMissingUsers(): Select query failed!', true );
			}// end if statement
		}// end for loop

		mysqli_close( $dbConnectionObject );

		return (array) $array_missingUsers;
	}// end reports_getMissingUsers() function

	echo "<strong class=\"reportTitle\">Custom User List Tests</strong><br>\n";
	$array_customUserList_amy = (array) array(
									'Steve Miller',
									'Jennifer Armstrong-Lehman',
									'Carol McLuhan',
									'Vanessa Doering',
									'Adam Leber',
									'Josiah Neice',
									'Catherine Hildebrandt',
									'Toni Cano',
									'Jaret Wright',
									'Jennifer Varcoe',
									'Stephanie McNamara',
									'Alanda Theriault',
									'Ross Bigelow',
									'Norma Hart',
									'Robert Northey',
									'Kimberly Thomas',
									'Joanne Fowlie',
									'Susan Hosein',
									'Cathy Neuss',
									'Kristen Borlan',
									'Jill Hynes',
									'Avinash Thadani',
									'Iain Robertson',
									'Amy Hutchinson',
									'Ashley Taylor'
								);// Amy's custom list
	
	$array_keynoteSpeakers = (array) array(
								   "Michael Clemons",
								   "MaryLynn West-Moynes"
							   );// end keynoteSpeakers array

	   $array_awardRecipients = (array) array(
								   "Katherine Wallis",
								   "Tamara Fisher-Cullen",
								   "Scott McCrindle",
								   "Avinash Thadani",
								   "Anne-Marie McAllister",
								   "Suzie Addison-Toor",
								   "Josh Barath",
								   "Jill Dunlop",
								   "Karen Bell"
							   );// end awardRecipients array

	   $array_teachPractCerts = (array) array(
									"Clement Bamikole",
									"Christine Fenech",
									"Vali Stone",
									"Mark Dorsey",
									"John McCluskey",
									"Rich Freeman",
									"Meredith Lowe",
									"Carol Meissner",
									"Anne Coulter",
									"Toni Cano",
									"Coralee Young",
									"Gay Ainsworth",
									"Catherine Vellinga",
									"Debra White",
									"Samantha Southorn",
									"Lisa Buchanan",
									"Emily Brett",
									"Allison Papenhuyzen",
									"Dominika Farrelly",
									"Dan Moreau",
									"Sarah Hunter",
									"Sarah Shellswell",
									"Josh Barath",
									"Jill Dunlop",
									"Lance Triskle",
									"Karen Burns"
								);// end teachPractCerts array
	   
	   $array_deansAssociateDeans = (array) array(
										"Cassandra Thompson",
										"Marie-Noelle Bonicalzi",
										"Barb Watts",
										"Sean Madorin",
										"Nina Koniuch",
										"Maryann Fifield",
										"Gabrielle Koopmans",
										"Kevin Weaver",
										"Jason Hunter",
										"Dan Brooks",
										"Aaron Gouin",
										"Baldev Pooni",
										"Catherine Drea",
										"Heather Raikou",
										"Marion Lougas",
										"Bonnie DeWitt",
										"Lynn Hynd",
										"Mary O'Farrell-Bowers",
										"Monique Vaillancourt",
										"Mac Greaves",
										"Angela Lockridge",
										"Lisa Banks",
										"MaryLynn West-Moynes"
									);// end deansAssociateDeans array

/**
 * Test the functions
 */
	$array_foundUsers = reports_getFoundUsers( $array_deansAssociateDeans );
	echo '<p>There were ' . count( $array_foundUsers ) . ' users found in the system out of ' . count ( $array_deansAssociateDeans );

	if( count($array_foundUsers) >= 1 ) {
		echo '<br><strong>Found Users:</strong><br><br>';

		for( $i = 0; $i < count( $array_foundUsers ); $i++ ) {
			echo $array_foundUsers[$i] . '<br>';
		}// end for loop
	}// end if statement

	$array_missingUsers = reports_getMissingUsers( $array_deansAssociateDeans );
	echo '<p>There were ' . count( $array_missingUsers ) . ' users missing from the system out of ' . count( $array_deansAssociateDeans );

	if( count($array_missingUsers) >= 1 ) {
		echo '<br><strong>Missing Users:</strong><br><br>';

		for( $i = 0; $i < count( $array_missingUsers ); $i++ ) {
			echo $array_missingUsers[$i] . '<br>';
		}// end for loop
	}// end if statement
	
/**
 * Clean duplicate entries
 */
	$array_listOfMissingUsers = (array) array(
									"Michael Clemons",
									"MaryLynn West-Moynes",
									"Katherine Wallis",
									"Suzie Addison-Toor",
									"Josh Barath",
									"Karen Bell",
									"Clement Bamikole",
									"Christine Fenech",
									"Meredith Lowe",
									"Debra White",
									"Dan Moreau",
									"Josh Barath",
									"Karen Burns",
									"Marie-Noelle Bonicalzi",
									"Sean Madorin",
									"Maryann Fifield",
									"Jason Hunter",
									"Dan Brooks",
									"Catherine Drea",
									"Marion Lougas",
									"Mary O'Farrell-Bowers",
									"Mac Greaves",
									"Angela Lockridge",
									"Lisa Banks",
									"MaryLynn West-Moynes"
								);

	//Delete duplicate values
	$array_listOfMissingUsers = array_unique( $array_listOfMissingUsers );

	//Cleanly Recreate the array
	$array_listOfMissingUsers = array_values( $array_listOfMissingUsers );
	
	echo '<p>&nbsp;&nbsp;</p><strong>Clean list of missing users<br><br>:';
	foreach( $array_listOfMissingUsers AS $missingUser ) {
		echo $missingUser . '<br>';
	}// end foreach loop