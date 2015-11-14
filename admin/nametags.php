<?php

/**
 * nametags.php v1.2	-	conference-registration
 */

if( !isset( $str_appURL ) ) {
	require_once('../data/environment.php');
        require_once('../data/db.php');
}// end if statement

mysql_connect("$str_dbDomain", "$str_dbUser", "$str_dbPass");
$db = mysql_select_db("$str_dbDb");

$selectw = "select users.fotc, users.lastname as lname, users.firstname as fname, registered.tue_amworkshop as amw, registered.tue_pmworkshop as pmw, users.department as department, " .
	"users.otherdept as otherdept, users.tue_lunch as lunch, users.registered as reg from registered, users where registered.userid=users.userid AND users.fotc='yes' " . 
	"order by reg desc, lname, fname ";

$resultw = mysql_query($selectw);
echo mysql_error();
$regd = 0;
$n = 1;

echo '<h2>Focus on Teaching Conference - Name Tag info</h2>
        <p>lastname|firstname|department|morningsession|morningroom|aftsession|aftroom|lunch</p><br>
        <hr><h3>Registered for FoTC and Tuesday workshops</h3>';

while($row = mysql_fetch_array($resultw)) {
	extract($row);
	
	if ($department == "None" || $department == "Other-" || $department == 'Choose your department') {
		$department = "";
	} else {
		if ($department == "Other") {
			$department = $otherdept;
		}
	}
	
	$department = stripslashes($department);
	$lanme = stripslashes($lname);
	$fname = stripslashes($fname);
	
	$selectam = "select title as morning, room as morningroom from workshops where workshopid=$amw";
	$resultam = mysql_query($selectam);
	$rowam = mysql_fetch_array($resultam);
	extract($rowam);
	
	$selectpm = "select title as afternoon, room as afternoonroom from workshops where workshopid=$pmw";
	$resultpm = mysql_query($selectpm);
	$rowpm = mysql_fetch_array($resultpm);
	extract($rowpm);
	
	$morning = stripslashes($morning);
	$afternoon = stripslashes($afternoon);
	
	if ($morning == "[None]" || $amw == "100") {
		$morning = "No workshop selected";
	}
	
	if ($afternoon == "[None]" || $pmw == "101") {
		$afternoon = "No workshop selected";
	}
	
	echo "$lname|$fname|$department|$morning|$morningroom|$afternoon|$afternoonroom|$lunch<br>";

	if ($reg == "no" && $regd == 0) {
		$regd = 1;
		echo "<hr><h3>NOT Registered for workshops</h3>";
	}
	$n+=1;
}//end while loop

/**
 * FoTC = 'no' but Tuesday sessions selected
 */
$selectw = "select users.fotc, users.lastname as lname, users.firstname as fname, registered.tue_amworkshop as amw, registered.tue_pmworkshop as pmw, users.department as department, " .
	"users.otherdept as otherdept, users.tue_lunch as lunch from registered, users where registered.userid=users.userid AND users.fotc='no' " . 
	"order by lname, fname ";

$resultw = mysql_query($selectw);
echo mysql_error();

echo "<hr><h3>Registered for Tuesday workshops but FoTC = no</h3>";

while($row = mysql_fetch_array($resultw)) {
	extract($row);
	
	if ($department == "None" || $department == "Other-" || $department == 'Choose your department') {
		$department = "";
	} else {
		if ($department == "Other") {
			$department = $otherdept;
		}
	}
	
	$department = stripslashes($department);
	$lanme = stripslashes($lname);
	$fname = stripslashes($fname);
	
	$selectam = "select title as morning, room as morningroom from workshops where workshopid=$amw";
	$resultam = mysql_query($selectam);
	$rowam = mysql_fetch_array($resultam);
	extract($rowam);
	
	$selectpm = "select title as afternoon, room as afternoonroom from workshops where workshopid=$pmw";
	$resultpm = mysql_query($selectpm);
	$rowpm = mysql_fetch_array($resultpm);
	extract($rowpm);
	
	$morning = stripslashes($morning);
	$afternoon = stripslashes($afternoon);
	
	if ($morning == "[None]" || $amw == "100") {
		$morning = "No workshop selected";
	}
	
	if ($afternoon == "[None]" || $pmw == "101") {
		$afternoon = "No workshop selected";
	}

	if ($amw != 100 || $pmw != 101) {
		echo "$lname|$fname|$department|$morning|$morningroom|$afternoon|$afternoonroom|$lunch<br>";
	}
}//end while loop