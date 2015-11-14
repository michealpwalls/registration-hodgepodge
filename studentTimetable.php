<?php
/* 
 * studentTimetable.php -   conference-registration
 */
echo '<!DOCTYPE html>';

require 'data/environment.php';
require "{$str_appLocation}data/db.php";
require "{$str_appLocation}data/studentRegData.php";
require "{$str_appLocation}lib/logging.php";
require "{$str_appLocation}lib/prettyErrors.php";

// Get printable flag token
$printableFlag = filter_input(INPUT_GET, 'printable', FILTER_SANITIZE_NUMBER_INT);

if (empty($printableFlag)) {
    require "{$str_appLocation}views/headToBody.php";
    require "{$str_appLocation}views/georgianHeader.php";
} else {
    require "{$str_appLocation}views/headToBody_printable.php";
    require "{$str_appLocation}views/georgianHeader_black.php";
}// end if statement
?>
        <div class="main">
<?php
// Get student token
$tokenOptions = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
$studentToken = filter_input(INPUT_GET, 'stkn', FILTER_SANITIZE_STRING, $tokenOptions);

if (empty($studentToken)) {
    require "{$str_appLocation}views/errorMessages_en_missingStudentToken.php";
    showPrettyError($errorMessage, 'error', true);
}// end if statement

/*
 * Encode inputs to protect from sql injection
 */
require "{$str_appLocation}lib/dbConnect.php";

$studentToken = mysqli_real_escape_string($dbConnectionObject, $studentToken);

/*
 * Verify the student exists. If student exists, store their Id
 */
require "{$str_appLocation}lib/students.php";
$studentId = doesStudentExistByToken($dbConnectionObject, $studentToken);

if ($studentId === false) {
    require "{$str_appLocation}views/errorMessages_en_studentDidNotExist.php";
    showPrettyError($errorMessage, 'error', true);
}

$ary_studentData = getStudent($dbConnectionObject, $studentId);

/*
 * Get and print the student's sessions
 */
require "{$str_appLocation}lib/sessions.php";
$mix_studentSessions = getStudentSessions($dbConnectionObject, $studentId);

if ($mix_studentSessions === false) {
    require "{$str_appLocation}views/infoMessages_en_noSessionsYet.php";
    showPrettyError($infoMessage, 'info', true, $infoTitle);
} else {
    $htmlMessage = (string) "";
    $htmlMessage .= "<h1>Schedule for international student orientation</h1>\n";
    echo "<h1>Schedule for {$ary_studentData['fullname_students']}</h1>\n";

    if (!$printableFlag) {
        $infoTitle = "Finished!";
        $infoMessage = "Congratulations, you're all set! Below is your complete schedule. If there are any mistakes, use the link at the bottom of the schedule to go back and make any necessary changes.";
        showPrettyError($infoMessage, 'info', true, $infoTitle);
    }

    $htmlMessage .= "<h2>Orientation day 1:</h2>\n";
    echo "<h2>Orientation day 1:</h2>\n";
    
    $ary_dayOneSession = getSessionById($mix_studentSessions[0][0], $dbConnectionObject);
    if (is_string($ary_dayOneSession)) {
        echoToConsole("<p>mysqli error fetching day 1 session: {$ary_dayOneSession}\n</p>", true);
    }
    
    $htmlMessage .= "<p>{$ary_dayOneSession['description_sessions']}</p>\n";
    echo "<p class=\"schedule-block indent\">{$ary_dayOneSession['description_sessions']}</p>\n";
    unset($ary_dayOneSession);

    $htmlMessage .= "<h2>Orientation day 2:</h2>\n";
    echo "<h2>Orientation day 2:</h2>\n";
    
    $ary_dayTwoSession = getSessionById($mix_studentSessions[1][0], $dbConnectionObject);
    if (is_string($ary_dayTwoSession)) {
        echoToConsole("<p>mysqli error fetching day 2 session: {$ary_dayTwoSession}\n</p>", true);
    }
    
    $htmlMessage .= "<p>Location: <strong>{$ary_dayTwoSession['location_sessions']}</strong><br>{$ary_dayTwoSession['description_sessions']}</p>\n";
    echo "<p class=\"schedule-block indent\">Room location <strong>{$ary_dayTwoSession['location_sessions']}</strong>:<br>\n"
        . "<span class=\"block hanging-indent\">{$ary_dayTwoSession['description_sessions']}</span>\n"
        . "</p>\n";
    unset($ary_dayTwoSession);
    
    mysqli_close($dbConnectionObject);
}

echo "<p><a href=\"{$str_appURL}studentTimetable.php?stkn={$studentToken}&printable=1\">Print-friendly version</a> | <a href=\"{$str_appURL}studentTimetable.php?stkn={$studentToken}\">Full-colour (Web) version</a></p>\n";
echo "<p><a href=\"{$str_appURL}index.php?stkn={$studentToken}&stage=2\">Go back and change schedule</a></p>\n";

// Email a copy of the schedule if in web-view
if (!$printableFlag) {
    if ($bln_useSendGrid) {
        require "{$str_appLocation}sendgrid-php/sendgrid-php.php";

        $obj_SendGrid = new SendGrid("azure_0a576068dd9b0e215730e46cc0d95746@azure.com", "0227XQSg5HEYVIK", array("turn_off_ssl_verification" => true));
        $obj_Email = new SendGrid\Email();

        $obj_Email->addTo($ary_studentData['email_students'])->
            setFrom($str_supportEmail)->
            setReplyTo($str_supportEmail)->
            setSubject($str_appName . " Schedule")->
            setHtml($htmlMessage)->
            addHeader("MIME-Version", "1.0")->
            addHeader("Content-Type","text/html; charset=iso-8859-1")->
            addHeader("X-Transport", "web");
        $SendGridResultObject = $obj_SendGrid->send($obj_Email);

        $deliveryResult = ($SendGridResultObject->code == 200 ? true : false);
    } else {
        $mailHeaders = "MIME-Version: 1.0" . "\r\n"; 
        $mailHeaders .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
        $mailHeaders .= "From: {$str_supportEmail}\r\n";
        $mailHeaders .= "Reply-To: {$str_supportEmail}\r\n";
        $mailSubject = "{$str_appName} Schedule";

        $deliveryResult = mail($ary_studentData['email_students'], $mailSubject, $htmlMessage, $mailHeaders);
    }
} else {
    // Bypass email-check when in print-mode (We never mailed anything so how can we check LOL)
    $deliveryResult = true;
}

if (!$deliveryResult) {
    $errorTitle = "Failed to email schedule!";
    $errorMessage = "Warning: The system failed while attempting to email a copy of your schedule!";
    showPrettyError($errorMessage, 'error', true, $errorTitle);
}

?>
        </div>
<?php
if (empty($printableFlag)) {
    require "{$str_appLocation}views/georgianFooter.php";
}
?>
    </body>
</html>