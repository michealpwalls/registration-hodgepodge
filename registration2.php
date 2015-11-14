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
$flt_time_start = (float) microtime( true );

require_once "data/environment.php";
require_once "data/db.php";
require_once "lib/logging.php";
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>Georgian College :: <?=$str_appName;?></title>

		<!--
			JQuery-UI css definitions were manually implemented in gl.css
			to fix IE v11 performance issue when full jquery-ui.css was
			loaded.
		-->
		<link rel="stylesheet" href="css/gl.css" type="text/css">

		<!--
			Legacy JQuery and JQuery-UI used for IE8 functionality.
		-->
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>

		<script src="js/accordion.js"></script>
		<script src="js/checkregfields.js"></script>
		<script src="js/formControlLogic.js"></script>
<?php
	// Connect to the Database
	require_once 'lib/dbConnect.php';

	// Keynote functions
	require_once 'lib/keynotes.php';

	// Pull keynote information from DB and initialize arrays to contain them
	getKeynotes( $dbConnectionObject );

	// Populate JQuery dialog boxes ("more info" buttons) using arrays
	showKeynotes();
?>
	</head>
	<body>
<?php
	require_once 'lib/init.php';

	if( isset( $userProfile ) ) {
		if( isset( $userProfile[0] ) ) {
			extract($userProfile[0]);	//user's profile
		}// end if statement
	} else {
		//user's sessions
		if( isset( $userProfile[1] ) ) {
			extract($userProfile[1]);
		} else {
			$mon_amworkshop = $tue_amworkshop = $wed_amworkshop = $thur_amworkshop = (int) 100;
			$mon_pmworkshop = $tue_pmworkshop = $wed_pmworkshop = $wed_pmworkshop2 = $thur_pmworkshop = (int) 101;
		}// end if statement
	}// end if statement
?>
		<div class="main ui-corner-bottom">
<?php
	include "pdweek.php";

	if ($prevfound == 0) {
		showPrettyError( '<strong>Alert:</strong> Our system has indicated that you have not completed Part 1 of the registration process. Please go back to the <a href="index.php" style="color: blue;">Registration Page</a> to begin the process. If you continue to get this error, please contact <a href="' . $str_supportEmail . '" style="color: blue;">' . $str_supportEmail . '</a> for technical support. Thank you.', 'error', true );
	} else {
?>		
			<h3>Session Registration</h3>
			<p>If you have any questions or problems regarding the session registrations, please contact <a href="mailto:<?=$str_supportEmail;?>"><?=$str_supportEmail;?></a></p>

			<div class="formbox2">
				<form name="registration" action="register3.php" method="post" accept-charset="utf-8" onsubmit="return checkFields();">
					<script>lastEntered = '';</script>
					<div class="ui-widget">
						<div class="ui-state-info ui-corner-all" style="padding: 0 .7em;"> 
							<p>Before choosing your sessions, please check your profile and make changes (if necessary).</p>
						</div><!-- ui-state-info -->
					</div><!-- ui-widget -->
					<p>Note that your first name, last name, and department on your profile specify what will appear on your conference name tag. Please click on the Submit button only after selecting your sessions.</p>
					<p>Reminder to Presenters: Please do not choose your own session - do not make a selection for that time slot.</p>
				<!-- Begin Profile -->
					<div class="regbox">
						<h3 class="regboxtitle">Profile</h3>
						<div class="regLabelContainer">
							<div class="lower-space"><label for="firstname">First name</label></div>
							<div class="lower-space"><label for="lastname">Last name</label></div>
							<div class="lower-space"><label for="email">Email</label></div>
							<div class="lower-space"><label for="department">Department</label></div>
							<div class="lower-space"><label for="otherdept">If Other, please specify</label></div>
							<div class="lower-space"><label>By attending, you consent to have your photo taken and used for the conference</label></div>
						</div><!-- regLabelContainer -->
						<div class="regControlContainer">
							<input class="regControlTop input-text" type="text" id="firstname" name="firstname" width="100" maxlength="40" value="<?php echo htmlentities( stripslashes($firstname) ); ?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
							<input class="regControl input-text" type="text" id="lastname" name="lastname" width="100" maxlength="40" value="<?php echo htmlentities( stripslashes($lastname) ); ?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
							<input class="regControl input-text" type="text" id="email" name="email" width="100" maxlength="40" value="<?php echo htmlentities( stripslashes($email) ); ?>" readonly>
							<select class="regControl input-text" name="department" id="department">
<?php
	$departmentListArray_bottom = array(
		"School of Business, Automotive, and Hospitality; OMVIC",
		"School of Technology and Visual Arts",
		"Centre for Applied Research",
		"Midland Campus",
		"Owen Sound Campus",
		"School of Health and Wellness",
		"Centre for Teaching and Learning",
		"School of Human Services and Community Safety (Orillia)",
		"Continuing Education and Workforce Development",
		"School of Liberal Arts and Access Programs",
		"Government and Employment Programs",
		"John Di Poce South Georgian Bay Campus",
		"Muskoka Campus",
		"Orangeville Campus",
		"School College Partnerships",
		"University Partnership Centre",
		"VP, Corporate Services and Innovation",
		"Physical Resources",
		"Accounting",
		"Financial Planning and Risk Management",
		"Kempenfelt Conference Centre",
		"Purchasing and Printing",
		"Human Resources  and Organizational Development",
		"Information Technology",
		"Institutional Research",
		"Campus Safety and Security",
		"VP, Communications, Marketing and External Relations",
		"Marketing and Communications",
		"Office of Development and Alumni Relations/Conference Services",
		"VP, Student Engagement and University Partnerships",
		"Athletics and Fitness Centre",
		"Student Life",
		"Coop Education and Career Services",
		"Georgian Stores",
		"International Recruitment and Partnerships",
		"Libraries and Learning Resources",
		"Office of the Registrar",
		"Student Centre Food/Beverage Operations",
		"Student Success Services",
		"Other"
	);
	asort( $departmentListArray_bottom );
	
	$departmentListArray = array(
		"Choose your department",
		"President's Office",
		"VP, Academic and University Programming"
	);

	$departmentListArray = array_merge( $departmentListArray, $departmentListArray_bottom );

	foreach ($departmentListArray as $departmentListItem) {
		if ($department == $departmentListItem) {
			echo "								<option value=\"$departmentListItem\" selected>$departmentListItem</option>\n";
		} else {
			echo "								<option value=\"$departmentListItem\">$departmentListItem</option>\n";
		}// end if statement
	}// end foreach loop
	?>
							</select>
							<input class="regControl input-text" type="text" id="otherdept" name="otherdept" width="150" maxlength="80" value="<?php echo htmlentities( stripslashes($otherdept) ); ?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
							<button class="regControl" id="photodialog">More info</button>
							<input type="hidden" name="userid" value="<?php echo $userid; ?>">
						</div><!-- regControlContainer -->
						<div class="clear"></div><!-- clear -->
					</div><!-- regbox -->
					<!-- End Profile -->

					<div class="ui-widget lower-space">
						<div class="ui-state-info ui-corner-all" style="padding: 0 .7em;">
							<p><strong>Please note</strong> that your selections are not guaranteed until you hit Submit at the bottom of this page. There could be many people registering at the same time, the seats available for each event/session could change before you click Submit.</p>
						</div><!-- ui-state-info -->
					</div><!-- ui-widget -->

					<div class="regbox">
						<h3 class="regboxtitle">Event Selection</h3>
						<span class="block upper-space lower-space center">Click on a date below to reveal the available options.</span>
						<!-- Start Workshop Accordion -->
						<div id="workshopAccordion">

							<!-- Begin Monday Workshops -->
							<h3>Monday, April 28th - Keynote, BoG Awards, PD Sessions</h3>
							<div class="subordinateRegbox">
								<div class="upper-space lower-space">
									<div class="ui-state-info">
<?php
if( !isset( $GLOBALS['noMondayKeynote'] ) ) {
?>
										<span class="block">Will you be attending the Board of Governor&#96;s Awards &#38; Keynote?</span>
		<?php if ($mon_keynote == "yes") { ?>
										<label><input id="mon_keynote" type="radio" name="mon_keynote" value="yes" checked>Yes</label> <label><input id="mon_keynote" type="radio" name="mon_keynote" value="no">No</label>
		<?php } else { ?>
										<label><input id="mon_keynote" type="radio" name="mon_keynote" value="yes">Yes</label> <label><input id="mon_keynote" type="radio" name="mon_keynote" value="no" checked>No</label>
		<?php } ?>
										<button id="mon-keynote-info">More Info</button>
<?php
}// end if statement
?>

										<span class="block">Will you attend the BBQ lunch? (<em>Barrie Campus&#96; TLC &#64; 12pm - 1pm</em>.)<br></span>										
		<?php if ($mon_lunch == "nnn") { ?>
										<span class="regControl">Lunch tickets are &#34;sold out&#34;</span>
										<input type="hidden" name="lunch" value="nnn">
		<?php } else {
							
			if ($mon_lunch == "yes") {
				echo "										<label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"yes\" checked onClick=\"javascript:showElement('regBbqVegetarian');enableOption('regBbqVegetarianYes');enableOption('regBbqVegetarianNo');\">Yes</label> <label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"no\" onClick=\"javascript:hideElement('regBbqVegetarian');disableOption('regBbqVegetarianYes');disableOption('regBbqVegetarianNo');\">No</label>\n";
				echo '										<div class="block left-margin" id="regBbqVegetarian">
											Would you prefer a Vegetarian lunch?&nbsp;';
				if ($vegetarian == "yes") {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\" checked>Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\">No</label>\n";
				} else {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\">Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\" checked>No</label>\n";
				}
				echo "										</div><!-- vegetarianOption -->\n";
			} else {
				echo "										<label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"yes\" onClick=\"javascript:showElement('regBbqVegetarian');enableOption('regBbqVegetarianYes');enableOption('regBbqVegetarianNo');\">Yes</label> <label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"no\" checked onClick=\"javascript:hideElement('regBbqVegetarian');disableOption('regBbqVegetarianYes');disableOption('regBbqVegetarianNo');\">No</label>\n";
				echo '										<div class="block left-margin" id="regBbqVegetarian" style="display: none;">
											Would you prefer a Vegetarian lunch?&nbsp;';
				if ($vegetarian == "yes") {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\" checked>Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\">No</label>\n";
				} else {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\">Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\" checked>No</label>\n";
				}
				echo "										</div><!-- vegetarianOption -->\n";
			}

		} ?>
									</div><!-- ui-state-info -->
								</div><!-- spacer -->

<?php require_once( 'lib/listworkshops.php' ); ?>

								<span class="regboxtitle">Concurrent Sessions (Starting in the Afternoon)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
	enumerateSessions( $dbConnectionObject, 'AM', 'mon', "(start_time='1:00pm')", 100, $userProfile[1]['mon_amworkshop'], 'mon_amworkshop' );
?>
									<span class="regboxtitle">Concurrent Sessions (Starting at: 2:30pm)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
	enumerateSessions( $dbConnectionObject, 'PM', 'mon', "(start_time='2:30pm')", 101, $userProfile[1]['mon_pmworkshop'], 'mon_pmworkshop' );
?>
							</div><!-- subordinateRegbox -->
							<!-- End Monday Workshops -->

							<!-- Begin Tuesday Workshops -->
							<h3>Tuesday, April 29th - Focus on Teaching Conference</h3>
							<div class="subordinateRegbox">
								<div class="upper-space lower-space">
<?php

	include( "fotc.php" );
	require_once( "lib/releaseDate.php" );

	$tueDaysRemaining = daysRemaining( 'tue' );

	require_once( "lib/fotcSeats.php" );
	$fotcSeatsRemaining = fotcSeatsRemaining();

	if( $tueDaysRemaining == 0 ) {
		echoToConsole( "FoTC workshops have been released!", true );

		if( $fotcSeatsRemaining == 0 ) {
			echo <<<END

								<div class="lower-space ui-state-info">
									<label>The Conference is currently full. Would you like to be added to a Waiting list in case a seat becomes available?</label>

END;
		} else {
			echo <<<END

								<div class="lower-space ui-state-info">
									<label>Will you attend the Focus on Teaching Conference? ({$fotcSeatsRemaining} seats remaining)</label>

END;
		}// end if statement

		if( $fotc == "yes" || $fotc == "wl" ) {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\" checked>Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\">No</label><br>\n" );
		} else {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\">Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\" checked>No</label><br>\n" );
		}// end if statement
?>
									<br><span class="block lower-space left-margin"><em>The Focus on Teaching Conference includes:
									<ul>
										<li>Keynote at 9:00am</li>
										<li><a href="#" id="tcdialog" style="color: blue;">TechCaf&eacute;</a> &amp; <a href="#" id="hldialog" style="color: blue">The Human Library</a> at 10:30am</li>
										<li>Lunch at 12:00pm</li>
									</ul>
									<strong>Note:</strong> If you choose Yes, you will receive an email once session registration is open.</em></span>
								</div><!-- ui-state-info: FoTC -->
								<span class="regboxtitle">Concurrent Sessions (From 1:15pm to 2:15pm)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
			enumerateSessions($dbConnectionObject, 'AM', 'tue', "(start_time='1:15pm')", 100, $userProfile[1]['tue_amworkshop'], 'tue_amworkshop' );
?>
									<span class="regboxtitle">Concurrent Sessions (From 2:30pm to 3:30pm)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
			enumerateSessions( $dbConnectionObject, 'PM', 'tue', "(start_time='2:30pm')", 101, $userProfile[1]['tue_pmworkshop'], 'tue_pmworkshop' );
	} else {
		echoToConsole( "FoTC workshops have not yet been released. Days remaining: {$tueDaysRemaining}", true );

		if( $fotcSeatsRemaining == 0 ) {
			echo <<<END

								<div class="lower-space ui-state-info">
									<p>Session registration for the conference will be ready soon. In the meantime, please let us know if you will be attending the conference so that we can email you when session registration becomes available.</p>
									<label>The Conference is currently full. Would you like to be added to a Waiting list in case a seat becomes available?</label>

END;
		} else {
			echo <<<END

								
								<div class="lower-space ui-state-info">
									<p>Session registration for the conference will be ready soon. In the meantime, please let us know if you will be attending the conference so that we can email you when session registration becomes available.</p>
									<label>Will you attend the Focus on Teaching Conference? ({$fotcSeatsRemaining} seats remaining)</label>

END;
		}// end if statement

		if( $fotc == "yes" || $fotc == "wl" ) {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\" checked>Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\">No</label><br>\n" );
		} else {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\">Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\" checked>No</label><br>\n" );
		}// end if statement

		echo "										<br><span class=\"block lower-space left-margin\"><em>The Focus on Teaching Conference includes:
														<ul>
															<li>Keynote at 9:00am</li>
															<li><a href=\"#\" id=\"tcdialog\" style=\"color: blue;\">TechCaf&eacute;</a> &amp; <a href=\"#\" id=\"hldialog\" style=\"color: blue\">The Human Library</a> at 10:30am</li>
															<li>Lunch at 12:00pm</li>
														</ul>
														<strong>Note:</strong> If you choose Yes, you will receive an email once session registration is open.</em>
													</span>\n
												</div><!-- ui-state-info: FoTC -->\n";
	}// end if statement
?>
								</div><!-- spacer -->
							</div><!-- subordinateRegbox -->
							<!-- End Tuesday Workshops -->

							<!-- Begin Wednesday Workshops -->
							<h3>Wednesday, April 30th  - PD Sessions</h3>
							<div class="subordinateRegbox">
<?php
if( !isset( $GLOBALS['noWednesdayKeynote'] ) ) {
?>
								<div class="lower-space ui-state-info">
									<span class="block">Will you be attending Wednesday's Keynote?</span>
		<?php if ($wed_keynote == "yes") { ?>
									<label><input id="wed_keynote" type="radio" name="wed_keynote" value="yes" checked>Yes</label> <label><input id="wed_keynote" type="radio" name="wed_keynote" value="no">No</label>
		<?php } else { ?>
									<label><input id="wed_keynote" type="radio" name="wed_keynote" value="yes">Yes</label> <label><input id="wed_keynote" type="radio" name="wed_keynote" value="no" checked>No</label>
		<?php } ?>
									<button id="wed-keynote-info">More Info</button>
								</div><!-- ui-state-info -->
<?php
}// end if statement
?>
								<span class="regboxtitle">Concurrent Sessions (Starting in the Morning)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
	enumerateSessions( $dbConnectionObject, 'AM', 'wed', "(start_time='9:00am' OR start_time='9:30am' OR start_time='10:00am' OR start_time='10:30am' OR start_time='11:00am' OR start_time='11:30am')", 100, $userProfile[1]['wed_amworkshop'], 'wed_amworkshop' );
?>
									<span class="regboxtitle">Concurrent Sessions (Starting in the Afternoon)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
	enumerateSessions( $dbConnectionObject, 'PM', 'wed', "(start_time='12:00pm' OR start_time='12:30pm' OR start_time='1:00pm' OR start_time='1:30pm' OR start_time='2:00pm')", 101, $userProfile[1]['wed_pmworkshop'], 'wed_pmworkshop' );
?>
									<span class="regboxtitle">Concurrent Sessions (Starting at: 2:30pm)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
	$select = "SELECT * FROM workshops WHERE time='PM' AND day='wed' AND datediff(now(), release_date)>=0 AND start_time='2:30pm' OR workshopid=101 ORDER BY title;";
	enumerateSessions( $dbConnectionObject, 'PM', 'wed', "start_time='2:30pm'", 101, $userProfile[1]['wed_pmworkshop2'], 'wed_pmworkshop2' )
?>
							</div><!-- subordinateRegbox -->
							<!-- End Wednesday Workshops -->

							<!-- Begin Thursday Workshops -->
							<h3>Thursday, May 1st - Extraordinary Experience, College-wide Update</h3>
							<div class="subordinateRegbox">
								<span class="regboxtitle">Concurrent Sessions (Starting in the Morning)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
	enumerateSessions( $dbConnectionObject, 'AM', 'thu', "(start_time='9:00am' OR start_time='9:30am' OR start_time='10:00am' OR start_time='10:30am' OR start_time='11:00am' OR start_time='11:30am')", 100, $userProfile[1]['thur_amworkshop'], 'thur_amworkshop' );

	if( !isset( $GLOBALS['noThursdayKeynote'] ) ) {
?>
								<span class="regboxtitle upper-space">Starting in the Afternoon (1:00pm)</span>
								<div class="upper-space lower-space">
									<div class="ui-state-info">
										<span class="block">Will you be attending the College-wide update?</span>
<?php
			if( !$GLOBALS['thursdayKeynoteFull'] ) {
				echo '<span class="left-margin">';
				if ($thur_keynote == "yes") {
					echo '										<label><input id="thur_keynote" type="radio" name="thur_keynote" value="yes" checked>Yes</label> <label><input id="thur_keynote" type="radio" name="thur_keynote" value="no">No</label>';
				} else {
					echo '										<label><input id="thur_keynote" type="radio" name="thur_keynote" value="yes">Yes</label> <label><input id="thur_keynote" type="radio" name="thur_keynote" value="no" checked>No</label>';
				}// end if statement
				echo '</span>';
			} else {
				echo '<span class="left-margin"><strong>(We\'re sorry, but the seats are FULL)</strong></span> ';
			}// end if statement
?>
										<button id="thur-keynote-info">More Info</button><br>
									</div><!-- ui-state-info -->
								</div><!-- spacer -->
							</div><!-- subordinateRegbox -->
							<!-- End Thursday Workshops -->
<?php
		}// end if statement (no thur_keynote)
?>
						</div><!-- workshopAccordion -->
						<!-- End Workshop Accordion -->
					</div><!-- regbox -->
					<p class="warning">Please click on the Submit button only after selecting your sessions.</p>
					<p style="text-align: right">
						<input type="submit" value="Submit" class="button-green">
					</p>
				</form>
			</div><!-- formbox2 -->
<?php
		
} // end if statement (prevfound=0)

	$flt_time_end = (float) microtime( true );
	$flt_time_duration = (float) $flt_time_end - $flt_time_start;
	echoToConsole( "Executed in: {$flt_time_duration} seconds.", true );
?>
		</div><!-- main -->
    </body>
</html>