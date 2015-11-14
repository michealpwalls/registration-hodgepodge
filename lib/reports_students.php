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

require_once "{$str_appLocation}lib/students.php";
require_once "{$str_appLocation}lib/sessions.php";

/**
 * The reports_sessionParticipants function generates and displays the
 * a report on all session's participants.
 * 
 * @global  (string) $str_appURL URL to the app. Used for building links.
 * @param   (object-ref) A reference to an open mysqli connection object.
 * @param   (bool) Flag to control export functionality.
 * @param   (string) String to define export format. (Currently: "csv" or "xls").
 * @return  (string) If csvOut flag set function returns a string in csv format.
 */
function reports_sessionParticipants(&$dbConnectionObject,$export = false,$format = "csv") {
    global $str_appURL,$adminTokenInput;
    $str_output = (string) "";
    
    if ($export) {
        $str_output .= ($format == "csv" ? "Day,Session Number,Georgian ID,Full Name,Program,Country of Origin,Country of Residence,Email Address,Vegetarian Lunch,Alergies,Special Lunch Requirements,Has Passport,Has Visa\n" : "Day\tSession Number\tGeorgian ID\tFull Name\tProgram\tCountry of Origin\tCountry of Residence\tEmail Addres\tVegetarian Lunch\tAlergies\tSpecial Lunch Requirements\tHas Passport\tHas Visa\n");
    } else {
        echo "<div class=\"regbox\">\n<span class=\"regboxtitle\">Session Participants</span><br><br>\n";
        echo "<p>Format:<br>Georgian ID | Full Name | Program | Country of Origin | Country of Residence | Email Address | Vegetarian Lunch | Alergies | Special Lunch Requirements | Has Passport | Has Visa</p>\n";
    }
    
    $ary_allDayOneSessions = getSessionsByDay(1, $dbConnectionObject);
    
    if ($export === false) { echo "<h3>Day 1 sessions</h3>\n"; }
    
    for ($i = 0; $i < count($ary_allDayOneSessions); $i++) {
        if ($export === false) { echo "<span class=\"sessionreport-title\">Participants in session {$ary_allDayOneSessions[$i]['number_sessions']}: </span><br>\n"; }
        
        $ary_sessionParticipantsIds = getRegisteredStudents($dbConnectionObject, $ary_allDayOneSessions[$i]['id_sessions']);
        for ($y = 0; $y < count($ary_sessionParticipantsIds); $y++) {
            $studentRecord = getStudent($dbConnectionObject,$ary_sessionParticipantsIds[$y]['studentid_registrations']);
            
            if ($export === false) {
                echo "<span class=\"sessionreport-sessionparticipant indent\" >{$studentRecord['georgianid_students']} | {$studentRecord['fullname_students']} | {$studentRecord['program_students']} | {$studentRecord['countryOfNationality_students']} | {$studentRecord['countryOfResidence_students']} | {$studentRecord['email_students']} | {$studentRecord['lunch_students']} | {$studentRecord['lunch_alergies_students']} | {$studentRecord['lunch_specialReqs_students']} | {$studentRecord['hasPassport_students']} | {$studentRecord['isVisaApproved_students']}</span><br>\n";
            } else {
                if ($format == "csv"){
                    $str_output .= "{$ary_allDayOneSessions[$i]['day_sessions']},{$ary_allDayOneSessions[$i]['number_sessions']},{$studentRecord['georgianid_students']},{$studentRecord['fullname_students']},{$studentRecord['program_students']},{$studentRecord['countryOfNationality_students']},{$studentRecord['countryOfResidence_students']},{$studentRecord['email_students']},{$studentRecord['lunch_students']},{$studentRecord['lunch_alergies_students']},{$studentRecord['lunch_specialReqs_students']},{$studentRecord['hasPassport_students']},{$studentRecord['isVisaApproved_students']}\n";
                } else {
                    $str_output .= "{$ary_allDayOneSessions[$i]['day_sessions']}\t{$ary_allDayOneSessions[$i]['number_sessions']}\t{$studentRecord['georgianid_students']}\t{$studentRecord['fullname_students']}\t{$studentRecord['program_students']}\t{$studentRecord['countryOfNationality_students']}\t{$studentRecord['countryOfResidence_students']}\t{$studentRecord['email_students']}\t{$studentRecord['lunch_students']}\t{$studentRecord['lunch_alergies_students']}\t{$studentRecord['lunch_specialReqs_students']}\t{$studentRecord['hasPassport_students']}\t{$studentRecord['isVisaApproved_students']}\n";
                }
            }
            unset($studentRecord);
        }
    }
    
    unset($ary_allDayOneSessions,$ary_sessionParticipantsIds);
    
    $ary_allDayTwoSessions = getSessionsByDay(2, $dbConnectionObject);
    
    if ($export === false) { echo "<br><h3>Day 2 sessions</h3>\n"; }
    
    for ($i = 0; $i < count($ary_allDayTwoSessions); $i++) {
        if ($export === false) { echo "<span class=\"sessionreport-title\">Participants in session {$ary_allDayTwoSessions[$i]['number_sessions']}: </span><br>\n"; }
        
        $ary_sessionParticipantsIds = getRegisteredStudents($dbConnectionObject, $ary_allDayTwoSessions[$i]['id_sessions']);
        for ($y = 0; $y < count($ary_sessionParticipantsIds); $y++) {
            $studentRecord = getStudent($dbConnectionObject,$ary_sessionParticipantsIds[$y]['studentid_registrations']);
            
            if ($export === false) {
                echo "<span class=\"sessionreport-sessionparticipant indent\">{$studentRecord['georgianid_students']} | {$studentRecord['fullname_students']} | {$studentRecord['program_students']} | {$studentRecord['countryOfNationality_students']} | {$studentRecord['countryOfResidence_students']} | {$studentRecord['email_students']} | {$studentRecord['lunch_students']} | {$studentRecord['lunch_alergies_students']} | {$studentRecord['lunch_specialReqs_students']} | {$studentRecord['hasPassport_students']} | {$studentRecord['isVisaApproved_students']}</span><br>\n";
            } else {
                if ($format == "csv"){
                    $str_output .= "{$ary_allDayTwoSessions[$i]['day_sessions']},{$ary_allDayTwoSessions[$i]['number_sessions']},{$studentRecord['georgianid_students']},{$studentRecord['fullname_students']},{$studentRecord['program_students']},{$studentRecord['countryOfNationality_students']},{$studentRecord['countryOfResidence_students']},{$studentRecord['email_students']},{$studentRecord['lunch_students']},{$studentRecord['lunch_alergies_students']},{$studentRecord['lunch_specialReqs_students']},{$studentRecord['hasPassport_students']},{$studentRecord['isVisaApproved_students']}\n";
                } else {
                    $str_output .= "{$ary_allDayTwoSessions[$i]['day_sessions']}\t{$ary_allDayTwoSessions[$i]['number_sessions']}\t{$studentRecord['georgianid_students']}\t{$studentRecord['fullname_students']}\t{$studentRecord['program_students']}\t{$studentRecord['countryOfNationality_students']}\t{$studentRecord['countryOfResidence_students']}\t{$studentRecord['email_students']}\t{$studentRecord['lunch_students']}\t{$studentRecord['lunch_alergies_students']}\t{$studentRecord['lunch_specialReqs_students']}\t{$studentRecord['hasPassport_students']}\t{$studentRecord['isVisaApproved_students']}\n";
                }
            }
            unset($studentRecord);
        }
    }
    
    unset($ary_allDayTwoSessions,$ary_sessionParticipantsIds);
    
    if ($export === false) {
        echo "<p><a href=\"{$str_appURL}admin/index.php?action=report-participants&export=1&format=csv&admtkn={$adminTokenInput}\">Export to CSV</a>&nbsp;|&nbsp;<a href=\"{$str_appURL}admin/index.php?action=report-participants&export=1&format=xls&admtkn={$adminTokenInput}\">Export to XLS</a></p>\n</div><!--reportbox-->\n";
    } else {
        return $str_output;
    }
}// end reports_sessionParticipants function

/*
 * reports_defaultReport shows an overview of the sessions and their remaining
 * spots available.
 * 
 * @param   (object-ref) A reference to an open mysqli connection object.
 */
function reports_defaultReport(&$dbConnectionObject) {
    
    $ary_allSessions = getSessionsByDay(0, $dbConnectionObject);
    
    echo "<div class=\"regbox\">\n<span class=\"regboxtitle\">Overview of sessions</span><br><br>\n";
    
    for ($i = 0; $i < count($ary_allSessions); $i++) {
        echo "<h3>Session {$ary_allSessions[$i]['id_sessions']}</h3>\n";
        echo "<span class=\"indent\">Day: {$ary_allSessions[$i]['day_sessions']}</span><br>\n";
        echo "<span class=\"indent\">Number: {$ary_allSessions[$i]['number_sessions']}</span><br>\n";
        echo "<span class=\"indent\">Maximum Seats: {$ary_allSessions[$i]['max_sessions']}</span><br>\n";
        echo "<span class=\"indent\">Seats remaining: " . seatsRemaining($ary_allSessions[$i]['id_sessions'], $dbConnectionObject) . "</span><br>\n";
    }
    
    echo "</div><br><br>\n";
}