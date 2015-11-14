<?php
/*
 * forms_studentSessions.php	-	conference-registration
 */

/*
 * displaySessionGroup Displays a group of sessions in a fieldset with markup.
 * 
 * @param (array-ref) A reference to the array of sessions to display
 * @param (object-ref) A reference to an open mysqli link.
 * @param (int) Id of Student so as to check if they are registered.
 * @return (bool) Try on successful display with spots remaining.
 * @return (string) Error returned by mysqli query for spots remaining.
 */
function displaySessionGroup(&$ary_sessions, &$dbConnectionObject, $studentIdIn) {
    $int_dayAccumulator = (int) 1;
    $int_indexPosition = $int_dayAccumulator-1;
    $str_errorAccumulator = (string) '';

    echo "<fieldset class=\"regbox upper-space\">\n<legend class=\"regboxtitle\">Day {$ary_sessions[$int_indexPosition]['day_sessions']} schedules</legend><br>\n";

    for ($i = 0; $i < count($ary_sessions); $i++) {
        // Get and save the number of available seats
        $mix_spotsRemaining = seatsRemaining($ary_sessions[$i]['id_sessions'], $dbConnectionObject);
        if (is_string($mix_spotsRemaining)) {
            $str_errorAccumulator .= $mix_spotsRemaining;
            echoToConsole($mix_spotsRemaining, true);
            $mix_spotsRemaining = 'ERR';
        }

        if ($ary_sessions[$i]['day_sessions'] != $ary_sessions[$int_indexPosition]['day_sessions']) {
            $int_dayAccumulator = $ary_sessions[$i]['day_sessions'];
            echo "</fieldset><fieldset class=\"regbox upper-space\">\n<legend class=\"regboxtitle\">Day {$ary_sessions[$i]['day_sessions']} schedules</legend><br>\n";
        }

        // Change style if session is full
        $boxClass = ($mix_spotsRemaining < 1 ? "formbox-max lower-space" : "formbox-avail lower-space");
        
        // Change style if already registered
        $isAlreadyRegistered = isRegisteredInSession($dbConnectionObject, $ary_sessions[$i]['id_sessions'], $studentIdIn);
        $boxClass = ($isAlreadyRegistered ? "formbox-registered lower-space" : $boxClass);
        $inputSelector = ($isAlreadyRegistered ? "checked" : "");
        $inputDisabler = ($mix_spotsRemaining < 1 ? "disabled" : "");
        
        $locationStatement = (strtolower($ary_sessions[$i]['location_sessions']) == "na" ? "" : " - (Location: {$ary_sessions[$i]['location_sessions']})");
        
        echo "<div class=\"{$boxClass}\">"
            . "<label for=\"sessionId\">"
            . "<input type=\"radio\" name=\"day{$ary_sessions[$i]['day_sessions']}-sessionId\" value=\"{$ary_sessions[$i]['id_sessions']}\" {$inputSelector} {$inputDisabler}>&nbsp;"
            . "<strong>Schedule {$ary_sessions[$i]['number_sessions']} (Spots remaining: {$mix_spotsRemaining}){$locationStatement}</strong><br>"
            . "<span class=\"sessionDescription\">{$ary_sessions[$i]['description_sessions']}</span>"
            . "</label>"
            . "</div>";
    }// end outer loop

    echo "</fieldset>\n";
    
    $endResult = ($str_errorAccumulator == '' ? (bool) true : (string) $str_errorAccumulator);
    return $endResult;
}// end of displaySessionsGroup function
?>
    <h3>Customize your orientation day timetable.</h3>
    <script>var lastEntered = '';</script>
    <form method="post" action="student_sessionSelector.php">
<?php
// Display the day 1 session.
$ary_dayOneSessions = (array) Array( getSessionById($targetSession, $dbConnectionObject) );
if (is_string($ary_dayOneSessions[0])) {
    echoToConsole("mysqli error when selecting day 1 sessions: {$ary_dayOneSessions[0]}",true);
    if ($bln_isBeta) {
        echo "mysqli error when selecting day 1 sessions: {$ary_dayOneSessions[0]}";
    }// Debug output
} else {
    displaySessionGroup($ary_dayOneSessions, $dbConnectionObject, $studentId);
}
unset($ary_dayOneSessions);

// Display the day 2 sessions.
$ary_dayTwoSessions = getSessionsByDay(2, $dbConnectionObject);
if (is_string($ary_dayTwoSessions)) {
    echoToConsole("mysqli error when selecting day 2 sessions: {$ary_dayTwoSessions}",true);
    if ($bln_isBeta) {
        echo "mysqli error when selecting day 2 sessions: {$ary_dayTwoSessions}";
    }// Debug output
} else {
    displaySessionGroup($ary_dayTwoSessions, $dbConnectionObject, $studentId);
}
unset($ary_dayTwoSessions);
?>
                <input type="hidden" name="stkn" value="<?=$studentToken;?>">
                <input type="submit" value="Submit">
            </form>