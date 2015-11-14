<?php
	/**
	 * 
	 * @param Object $dbConnectionObject
	 * @return boolean True on successful queries, False on failures
	 */
	function getKeynotes( &$dbConnectionObject ) {
		$keynoteQuery = (string) "SELECT * FROM keynotes;";
		$keynoteResultObject = mysqli_query( $dbConnectionObject, $keynoteQuery );

		if( is_object( $keynoteResultObject ) ) {
			while( $row = mysqli_fetch_array( $keynoteResultObject ) ) {

				switch( $row['day'] ) {
					case "mon":
						$GLOBALS['mon_keynote'] = (array) $row;
						break;
					case "tue":
						$GLOBALS['tue_keynote'] = (array) $row;
						break;
					case "wed":
						$GLOBALS['wed_keynote'] = (array) $row;
						break;
					case "thu":
						$GLOBALS['thur_keynote'] = (array) $row;
						break;
				}// end switch case statement
			}// end while loop

			mysqli_free_result( $keynoteResultObject );
			return true;
		} else {
			return false;
		}// end if statement
	}// end getKeynotes() function

	/**
	 * The showKeynotes() function populates the JQuery dialog boxes for the
	 * 'more info' buttons.
	 */
	function showKeynotes() {
?>
		<script>
			$(document).ready(function() {
				var $dialog1 = $('<div></div>')
					.html('Photos will be taken throughout the day and will be used in a conference slideshow and for other promotional purposes.')
					.dialog({
						autoOpen: false,
						title: 'Photo Disclaimer'
					});

				var $dialog2 = $('<div></div>')
					.html('The Tech Café is a collection of stations where you can informally experience many technologies that support learning. Experts at each station will share their knowledge. It will run from 10:30am - 12:15pm. Some of the stations will include: Clickers, Media Services, Library Research, Films on Demand Platform, Blackboard 9.1, Adaptive Technology.')
					.dialog({
						autoOpen: false,
						title: 'The Tech Cafe'
						});

				$('#photodialog').click(function() {
					$dialog1.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

				$('#tcdialog').click(function() {
					$dialog2.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

				var $dialog7 = $('<div></div>')
					.html('On Thursday from <strong>9:00am – 12:00pm</strong> we are excited to be offering various "Extraordinary Experience" opportunities at the Barrie Campus. The goal of these opportunities is to provide staff an opportunity to learn and explore the different academic areas from a "Students View" and learn more about Georgian College and some of the programs and experiences we offer our students.<br><br>If you are interested in attending please click here and you will be notified once registration opens for these sessions.')
					.dialog({
						autoOpen: false,
						title: 'Extraordinary Experiences'
						});

				$('#extraordinary-experiences-info').click(function() {
					$dialog7.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});
				
				$('#hldialog').click(function() {
					$dialog8.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});
				
				var $dialog8 = $('<div></div>')
					.html('The Human Library will take place at 10:30am as part of the Focus on Teaching Conference.<br><br><strong>What is the Human Library?</strong> (Definition adapted from <a href="http://humanlibrary.org">humanlibrary.org</a>)<br><br>The Human Library is an innovative experiential learning method designed to promote dialogue, reduce prejudices, build connections, and encourage understanding. It is set up as a space for dialogue and interaction. Visitors to a Human Library are given the opportunity to be "readers" though informal conversations with "people on loan". The people on loan or the "books" of the library are selected to represent student diversity. They have volunteered to share their experiences with library visitors through informal conversations. The human library has been proven to be a powerful event for breaking stereotypes and gaining insight into the rich and diverse lived experiences of the people in our classrooms.')
					.dialog({
						autoOpen: false,
						title: 'The Human Library'
						});

<?php
	if( isset( $GLOBALS['mon_keynote'] ) ) {
		$GLOBALS['mon_keynote']['speaker'] = stripslashes( $GLOBALS['mon_keynote']['speaker'] );
		$GLOBALS['mon_keynote']['description'] = stripslashes( $GLOBALS['mon_keynote']['description'] );
		$GLOBALS['mon_keynote']['time'] = stripslashes( $GLOBALS['mon_keynote']['time'] );
		$GLOBALS['mon_keynote']['seats'] = stripslashes( $GLOBALS['mon_keynote']['seats'] );
		$GLOBALS['mon_keynote']['location'] = stripslashes( $GLOBALS['mon_keynote']['location'] );

		echo <<<END

				var \$dialog3 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$GLOBALS['mon_keynote']['speaker']}<br><strong>Description:</strong> {$GLOBALS['mon_keynote']['description']}<br><strong>Time</strong>: {$GLOBALS['mon_keynote']['time']}<br><strong>Seats Remaining</strong>: {$GLOBALS['mon_keynote']['seats']}<br><strong>Location:</strong> {$GLOBALS['mon_keynote']['location']}")
					.dialog({
						autoOpen: false,
						title: '{$GLOBALS['mon_keynote']['name']}'
						});

				\$('#mon-keynote-info').click(function() {
					\$dialog3.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$GLOBALS['noMondayKeynote'] = (bool) true;
		echoToConsole( "No Monday Keynote found", false );
	}// end if statement

	/**
	 * Tuesday Keynote
	 */
	if( isset( $GLOBALS['tue_keynote'] ) ) {
		$GLOBALS['tue_keynote']['speaker'] = stripslashes( $GLOBALS['tue_keynote']['speaker'] );
		$GLOBALS['tue_keynote']['description'] = stripslashes( $GLOBALS['tue_keynote']['description'] );
		$GLOBALS['tue_keynote']['time'] = stripslashes( $GLOBALS['tue_keynote']['time'] );
		$GLOBALS['tue_keynote']['seats'] = stripslashes( $GLOBALS['tue_keynote']['seats'] );
		$GLOBALS['tue_keynote']['location'] = stripslashes( $GLOBALS['tue_keynote']['location'] );

		echo <<<END

				var \$dialog4 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$GLOBALS['tue_keynote']['speaker']}<br><strong>Description:</strong> {$GLOBALS['tue_keynote']['description']}<br><strong>Time</strong>: {$GLOBALS['tue_keynote']['time']}<br><strong>Seats Remaining</strong>: {$GLOBALS['tue_keynote']['seats']}<br><strong>Location:</strong> {$GLOBALS['tue_keynote']['location']}")
					.dialog({
						autoOpen: false,
						title: '{$GLOBALS['tue_keynote']['name']}'
						});

				\$('#tue-keynote-info').click(function() {
					\$dialog4.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$GLOBALS['noTuesdayKeynote'] = (bool) true;
		echoToConsole( "No Tuesday Keynote found", false );
	}// end if statement

	/**
	 * Wednesday Keynote
	 */
	if( isset( $GLOBALS['wed_keynote'] ) ) {
		$GLOBALS['wed_keynote']['speaker'] = stripslashes( $GLOBALS['wed_keynote']['speaker'] );
		$GLOBALS['wed_keynote']['description'] = stripslashes( $GLOBALS['wed_keynote']['description'] );
		$GLOBALS['wed_keynote']['time'] = stripslashes( $GLOBALS['wed_keynote']['time'] );
		$GLOBALS['wed_keynote']['seats'] = stripslashes( $GLOBALS['wed_keynote']['seats'] );
		$GLOBALS['wed_keynote']['location'] = stripslashes( $GLOBALS['wed_keynote']['location'] );
		
		echo <<<END

				var \$dialog5 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$GLOBALS['wed_keynote']['speaker']}<br><strong>Description:</strong> {$GLOBALS['wed_keynote']['description']}<br><strong>Time</strong>: {$GLOBALS['wed_keynote']['time']}<br><strong>Seats Remaining</strong>: {$GLOBALS['wed_keynote']['seats']}<br><strong>Location:</strong> {$GLOBALS['wed_keynote']['location']}")
					.dialog({
						autoOpen: false,
						title: '{$GLOBALS['wed_keynote']['name']}'
						});

				\$('#wed-keynote-info').click(function() {
					\$dialog5.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$GLOBALS['noWednesdayKeynote'] = (bool) true;
		echoToConsole( "No Wednesday Keynote found", false );
	}// end if statement
	
	/**
	 * Thursday Keynote
	 */
	if( isset( $GLOBALS['thur_keynote'] ) ) {
		$GLOBALS['thur_keynote']['speaker'] = stripslashes( $GLOBALS['thur_keynote']['speaker'] );
		$GLOBALS['thur_keynote']['description'] = stripslashes( $GLOBALS['thur_keynote']['description'] );
		$GLOBALS['thur_keynote']['time'] = stripslashes( $GLOBALS['thur_keynote']['time'] );
		$GLOBALS['thur_keynote']['seats'] = stripslashes( $GLOBALS['thur_keynote']['seats'] );
		$GLOBALS['thur_keynote']['location'] = stripslashes( $GLOBALS['thur_keynote']['location'] );
		
		/**
		 * Test if seats full
		 */
		$GLOBALS['thursdayKeynoteFull'] = (bool) false;
		if( $GLOBALS['thur_keynote']['seats'] < 1 ) {
			$GLOBALS['thursdayKeynoteFull'] = (bool) true;
		}// end if statement (Seats full)

		echo <<<END

				var \$dialog6 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$GLOBALS['thur_keynote']['speaker']}<br><strong>Description:</strong> {$GLOBALS['thur_keynote']['description']}<br><strong>Time</strong>: {$GLOBALS['thur_keynote']['time']}<br><strong>Seats Remaining</strong>: {$GLOBALS['thur_keynote']['seats']}<br><strong>Location:</strong> {$GLOBALS['thur_keynote']['location']}")
					.dialog({
						autoOpen: false,
						title: '{$GLOBALS['thur_keynote']['name']}'
						});

				\$('#thur-keynote-info').click(function() {
					\$dialog6.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$GLOBALS['noThursdayKeynote'] = (bool) true;
		echoToConsole( "No Thursday Keynote found", false );
	}// end if statement
?>
			});
		</script>
<?php
	}// end showKeynotes() function