<?php
/*
 * listregistrants.php	-	pdweek v1.3
 */

require_once( "../data/environment.php" );
?>
<h2><?=$str_appName;?> - Registrants</h2>
<?php
//Connect to the Database
$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

//Set the character set, for use with mysqli_real_escape_string
mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

$userSelectQuery = "select users.userid as userid, users.firstname as fname, users.lastname as lname from registered, users where registered.userid=users.userid AND users.registered='yes';";

$userSelectResultObject = mysqli_query( $dbConnectionObject, $userSelectQuery );

//Disconnect from Database
mysqli_close( $dbConnectionObject );

if( is_object( $userSelectResultObject ) ) {
	$userCount = mysqli_num_rows( $userSelectResultObject );
	
	echoToConsole( "Users found: $userCount", true );

	if( $userCount > 0 ) {
		echo( "<div id=\"workshopAccordion\">\n" );

		while($row = mysqli_fetch_array( $userSelectResultObject )) {
			extract($row);

			$lname = stripslashes( $lname );
			$fname = stripslashes( $fname );

			echo( "	<h3>" . htmlentities( $lname ) . ", " . htmlentities( $fname ) . "</h3>\n");
			echo( "		<div>\n" );

			require_once( "../lib/listworkshops.php" );
			echo( listWorkshops( $userid ) );

			echo( "		</div>\n" );
		}// end while loop

		//Free memory associated with result object
		mysqli_free_result( $userSelectResultObject );

		echo( "</div>\n" );
	} else {
		echo( '<div class="ui-widget">
	<div class="ui-state-info ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		<strong>Notice:</strong> There are no users registered for a workshop. Nothing to list.</p>
	</div>
</div>' );
	}

	
} else {
	echo( "\n" );
	echo( "\n" );
	
	echo( '<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		<strong>Alert:</strong>The query to collect all registered users failed.<br>
		<p>The failed query was:<br>' . htmlentities( $userSelectQuery ) . '</p>
	</div>
</div>' );
}// end if statement
?>
