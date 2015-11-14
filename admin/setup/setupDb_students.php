<?php
/*
 * setupDbStudents.php	-	conference-registration
 */

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

require $str_appLocation . 'data/db.php';
require $str_appLocation . 'lib/dbConnect.php';

/**
 * Students table
 */
echo '<br><br><p>Creating the <strong>students</strong> table...</p>';

$createStudentsQuery = "CREATE TABLE `students` (
    `id_students` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `token_students` TINYTEXT,
    `georgianid_students` INT(10),
    `regDate_students` DATETIME,
    `firstname_students` VARCHAR(100),
    `lastname_students` VARCHAR(100),
    `fullname_students` VARCHAR(200),
    `sex_students` VARCHAR(15),
    `countryOfResidence_students` VARCHAR(175),
    `countryOfNationality_students` VARCHAR(175),
    `dateArrivingToCanada_students` DATE,
    `isVisaApproved_students` VARCHAR(3),
    `hasPassport_students` VARCHAR(3),
    `email_students` VARCHAR(300),
    `username_students` VARCHAR(20),
    `program_students` VARCHAR(4),
    `campus_students` VARCHAR(75),
    `lunch_students` VARCHAR(3),
    `lunch_alergies_students` VARCHAR(200),
    `lunch_specialReqs_students` VARCHAR(225),
    `academicArea_students` VARCHAR(125),
    `sessionNum_dayOne_students` INT(1),
    `sessionNum_dayTwo_students` INT(1),
    `group_students` CHAR(1)
)ENGINE=InnoDB;";

echo 'Querying the database';

$createStudentsResult = mysqli_query($dbConnectionObject, $createStudentsQuery);

if($createStudentsResult) {
    echo '<p style="color: green;">Finished creating the <strong>students</strong> table.</p>';
} else {
    echo '<p style="color: red;">Fiddle sticks! An internal DB error occured while creating students table.</p>';
    echo '<p>The error was: ' . mysqli_error($dbConnectionObject) . '</p><br>';
}// end if statement

// Free query object
if(is_object($createStudentsQuery)) {
    mysqli_free_result($createStudentsQuery);
}// end if statement

// Free result set object
if(is_object($createStudentsResult)) {
    mysqli_free_result($createStudentsResult);
}// end if statement

/**
 * Sessions table
 */
echo '<br><br><p>Creating the <strong>sessions</strong> table...</p>';

$createSessionsQuery = "CREATE TABLE `sessions` (
    `id_sessions` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `number_sessions` INT(1) NOT NULL,
    `mandatory_sessions` VARCHAR(3) NOT NULL DEFAULT 'no',
    `requirePassport_sessions` VARCHAR(3) NOT NULL DEFAULT 'no',
    `description_sessions` TEXT NOT NULL,
    `max_sessions` INT(3) NOT NULL,
    `day_sessions` INT(1) NOT NULL,
    `location_sessions` VARCHAR(25) NOT NULL
)ENGINE=InnoDB;";

echo 'Querying the database';

$createSessionsResult = mysqli_query($dbConnectionObject, $createSessionsQuery);

if($createSessionsResult) {
    echo '<p style="color: green;">Finished creating the <strong>sessions</strong> table.</p>';
} else {
    echo '<p style="color: red;">Fiddle sticks! An internal DB error occured while creating sessions table.</p>';
    echo '<p>The error was: ' . mysqli_error($dbConnectionObject) . '</p><br>';
}// end if statement

// Free query object
if(is_object($createSessionsQuery)) {
    mysqli_free_result($createSessionsQuery);
}// end if statement

// Free result set object
if(is_object($createSessionsResult)) {
    mysqli_free_result($createSessionsResult);
}// end if statement

/**
 * Registrations table
 */
echo '<br><br><p>Creating the <strong>registrations</strong> table...</p>';

$createRegistrationsQuery = "CREATE TABLE `registrations` (
  `id_registrations` INT(32) NOT NULL AUTO_INCREMENT,
  `sessionid_registrations` INT(2) NOT NULL,
  `studentid_registrations` INT(11) NOT NULL,
  `regDate_registrations` DATE NOT NULL,
  `day_registrations` INT(2) NOT NULL,
  `hasAttended_registrations` VARCHAR(3) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id_registrations`))ENGINE=InnoDB;";

echo 'Querying the database';

$createRegistrationsResult = mysqli_query($dbConnectionObject, $createRegistrationsQuery);

if($createRegistrationsResult) {
    echo '<p style="color: green;">Finished creating the <strong>registrations</strong> table.</p>';
} else {
    echo '<p style="color: red;">Fiddle sticks! An internal DB error occured while creating registrations table.</p>';
    echo '<p>The error was: ' . mysqli_error($dbConnectionObject) . '</p><br>';
}// end if statement

// Free query object
if(is_object($createRegistrationsQuery)) {
    mysqli_free_result($createRegistrationsQuery);
}// end if statement

// Free result set object
if(is_object($createRegistrationsResult)) {
    mysqli_free_result($createRegistrationsResult);
}// end if statement

// Disconnect from database
mysqli_close($dbConnectionObject);
unset($dbConnectionObject);