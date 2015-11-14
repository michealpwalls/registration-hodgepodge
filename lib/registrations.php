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

/*
 * Clears all registrations for a student by id and day.
 * 
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int) $sessionIdIn Id of session to register in.
 * @param   (int) $studentIdIn Id of student to register.
 * @return  (bool)  Result of registration.
 */
function clearRegistrations(&$dbConnectionObject, $studentIdIn, $dayIn = 2) {
    $clearResult = (bool) false;
    
    $str_deleteQuery = "DELETE FROM `registrations` WHERE `studentid_registrations`={$studentIdIn} AND `day_registrations`={$dayIn};";
    
    mysqli_query($dbConnectionObject, $str_deleteQuery);
    
    if (mysqli_affected_rows($dbConnectionObject) === 0) {
        $clearResult = false;
    } else {
        $clearResult = true;
    }
    
    return $clearResult;
}

/*
 * Registers a student in a session. This function assumes inputs are both
 * valid and exist.
 * 
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int) $sessionIdIn Id of session to register in.
 * @param   (int) $studentIdIn Id of student to register.
 * @return  (bool)  Result of registration.
 */
function registerInSession(&$dbConnectionObject, $sessionIdIn, $studentIdIn, $dayIn) {
    $registrationResult = (bool) false;
    
    if (isRegisteredInSession($dbConnectionObject, $sessionIdIn, $studentIdIn)) {
        echoToConsole("Student is already registered in that session.", true);
        return false;
    }
    
    $str_query_registerInSession = "insert into `registrations`"
            . " (`sessionid_registrations`,`studentid_registrations`,`regDate_registrations`,`day_registrations`)"
            . " VALUES('{$sessionIdIn}','{$studentIdIn}',NOW(),{$dayIn});";
            
    mysqli_query($dbConnectionObject, $str_query_registerInSession);

    // If an error occurred and the app is in beta then show the error
    if (mysqli_errno($dbConnectionObject)) {
        echoToConsole('MySQLi error while assign student to a session: ' . mysqli_error($dbConnectionObject), true);
     } else if (mysqli_affected_rows($dbConnectionObject) != 1) {
        echoToConsole('No rows affected by session assignment!', true);
    } else {
        $registrationResult = true;
    }

    return $registrationResult;
}

/*
 * The isRegisteredInSession function will tell you if a student is registered
 * in a given session or not.
 * 
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int) $sessionIdIn Id of the session to check for.
 * @param   (int) $studentIdIn Id of the student to check for.
 * @return  (bool) True if the student is registered in the session.
 * @return  (bool) False if the student was not registered or in case of errors.
 */
function isRegisteredInSession(&$dbConnectionObject, $sessionIdIn, $studentIdIn) {
    $returnValue = (bool) false;
    $str_query = (string) "SELECT * FROM `registrations`"
            . " WHERE `studentid_registrations`={$studentIdIn}"
            . " AND `sessionid_registrations`={$sessionIdIn};";
    
    // Query the db
    $obj_queryResult = mysqli_query($dbConnectionObject, $str_query);
    
    // Check for query errors
    if (mysqli_errno($dbConnectionObject) === 0 && mysqli_num_rows($obj_queryResult) > 0) {
        $returnValue = true;
        mysqli_free_result($obj_queryResult);
    } else {
        $returnValue = mysqli_error($dbConnectionObject);
    }// end if query errors
    
    return $returnValue;
}

/*
 * The getRegisteredStudents function will return an array containing the Ids of
 * all the students who have registered for a session.
 * 
 * @param   (object-ref) &$dbConnectionObject A reference to an open mysqli link.
 * @param   (int) $sessionIdIn The session Id to inspect.
 * @return  (array) An associative array of student Ids.
 * @return  (string) Error message returned by mysqli query
 */
function getRegisteredStudents(&$dbConnectionObject,$sessionIdIn) {
    $flowControl = (bool) false;
    $str_mysqliError = (string) "";
    $ary_studentIds = (array) Array();
    $str_selectQuery = (string) "SELECT `studentid_registrations` FROM "
            . "`registrations` WHERE `sessionid_registrations`={$sessionIdIn};";

    $obj_selectResult = mysqli_query($dbConnectionObject, $str_selectQuery);

    if (mysqli_errno($dbConnectionObject) === 0) {
        for ($i = 0; $i < mysqli_num_rows($obj_selectResult); $i++) {
            $ary_studentIds[$i] = mysqli_fetch_assoc($obj_selectResult);
        }

        mysqli_free_result($obj_selectResult);
        $flowControl = true;
    } else {
        $str_mysqliError = mysqli_error($dbConnectionObject);
    }

    if ($flowControl) {
        return $ary_studentIds;
    } else {
        return $str_mysqliError;
    }
}