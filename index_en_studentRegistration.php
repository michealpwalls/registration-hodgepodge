<?php
/*
 * index_studentRegistration.php	-	conference-registration
 */
include "{$str_appLocation}data/studentRegData.php";
include "{$str_appLocation}lib/logging.php";
include "{$str_appLocation}lib/prettyErrors.php";
include "{$str_appLocation}views/headToBody_jqueryUiDatepicker.php";
include "{$str_appLocation}views/georgianHeader.php";
include "{$str_appLocation}lib/students.php";
?>
        <div class="main ui-corner-bottom">
<?php
// Inputs from email links
$optionsStripAll = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
$studentToken = filter_input(INPUT_GET, 'stkn', FILTER_SANITIZE_STRING, $optionsStripAll);
$stage = filter_input(INPUT_GET, 'stage', FILTER_SANITIZE_NUMBER_INT);

if (empty($studentToken)) {
    // Inputs from forms_en_studentRegistration.php
    $georgianStudentId = filter_input(INPUT_POST, 'georgianid', FILTER_SANITIZE_NUMBER_INT);
    $fullName = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING, $optionsStripAll);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (empty($georgianStudentId)) {
        include "{$str_appLocation}views/forms_en_studentRegistration.php";
    } else {
        require "{$str_appLocation}lib/dbConnect.php";

        $georgianStudentId = mysqli_real_escape_string($dbConnectionObject, $georgianStudentId);
        $fullName = mysqli_real_escape_string($dbConnectionObject, $fullName);
        $email = mysqli_real_escape_string($dbConnectionObject, $email);
        
        // Generate a random 24 character alpha-numeric token
        $randomizingstring = (string) "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $studentToken = (string) "";
        for ($i = 1; $i <= 24; $i += 1) {
           $r = rand(0, strlen($randomizingstring) - 1);
           $studentToken .= substr($randomizingstring, $r, 1);
        }// end for loop
        
        $studentId = initStudent($dbConnectionObject, $studentToken, $georgianStudentId, $fullName, $email);
        
        if ($studentId === false) {
            $errorMessage = "Failed to initialize student account. Cannot continue!";
            showPrettyError($errorMessage, 'error', true, 'Failed to init student!');
        }

        if ($bln_sendWelcomeMail) { require "{$str_appLocation}lib/mails_students.php"; }
        
        if ($bln_isAzure && $bln_sendWelcomeMail) {
            $deliveryResult = sendWelcomeEmail($email, $fullName, $studentToken, $str_supportEmail, true);
        } else if ($bln_sendWelcomeMail) {
            $deliveryResult = sendWelcomeEmail($email, $fullName, $studentToken, $str_supportEmail, false);
        }
        
        if ($deliveryResult) {
            require "{$str_appLocation}views/infoMessages_en_tokenMailed.php";
            showPrettyError($infoMessage, 'info', true, $infoTitle);
        } else {
            $outputStyle = ($bln_sendWelcomeMail ? "error" : "info");
            require "{$str_appLocation}views/errorMessages_en_failedToMailToken.php";
            showPrettyError($errorMessage, $outputStyle, true);
        }

        //Disconnect from the Database
        if (is_object($dbConnectionObject)) {
            mysqli_close($dbConnectionObject);
        }

        // Redirect back to the main index and enter stage 1 of registration
        header('Location: ' . "{$str_appURL}index.php?stkn={$studentToken}", true, 301);
    }
} else {
   require "{$str_appLocation}lib/dbConnect.php";

   $studentToken = mysqli_real_escape_string($dbConnectionObject, $studentToken);

   // Does the student exist? If so store their Id
   $studentId = doesStudentExistByToken($dbConnectionObject, $studentToken);
   if ($studentId === false) {
       require "{$str_appLocation}views/errorMessages_en_studentDidNotExist.php";
       showPrettyError($errorMessage, 'error', true);
   }
   
   // Stage flow control
   switch ($stage) {
       case 2:
           require "{$str_appLocation}lib/sessions.php";
           require "{$str_appLocation}lib/registrations.php";
           
            // Get the student group and determine appropriate session id.
            $studentGroup = getStudentGroup($dbConnectionObject, $studentId);
            $targetSession = ($studentGroup == 'a' ? 1 : 11);

            // Make sure they're not already registered in the session.
            if (!isRegisteredInSession($dbConnectionObject, $targetSession, $studentId)) {
                // Clear existing selection for day 1
                clearRegistrations($dbConnectionObject, $studentId, 1);
                
                // Register student into their day 1 session
                $registrationResult = registerInSession($dbConnectionObject, $targetSession, $studentId, 1);
                if (!$registrationResult) {
                    require "{$str_appLocation}views/errorMessages_en_failedToAssignSession.php";
                    showPrettyError($errorMessage, 'error', true, 'Failed to assign session!');
                }
            }
            
           include "{$str_appLocation}views/forms_en_studentSession.php";
           break;
       default:
           include "{$str_appLocation}views/forms_en_studentProfile.php";
           break;
   }
}
?>
        </div>