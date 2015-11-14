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
 * Checks if a given session exists or not
 * 
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int) $sessionIdIn Id of the session to check for.
 * @return  (bool)
 */
function doesSessionExist(&$dbConnectionObject,$sessionIdIn) {
    $returnValue = (bool) false;
    
    // Query the database for the session
    $str_query_getSessionData = "select * from `sessions` where `id_sessions`=$sessionIdIn;";
    $obj_query_getSessionDataResult = mysqli_query($dbConnectionObject, $str_query_getSessionData);

    // If an error occurred and the app is in beta then show the error
    if (mysqli_errno($dbConnectionObject)) {
        echoToConsole('mysqli error while selecting session: ' . mysqli_error($dbConnectionObject), true);
     } else if (mysqli_num_rows($obj_query_getSessionDataResult) != 1) {
        echoToConsole('Session did not exist!', true);
    } else {
        mysqli_free_result($obj_query_getSessionDataResult);
        $returnValue = true;
    }

    return $returnValue;
}

/*
 * Updates a session with new field values based on session Id. WARNING: This
 * function assumes all inputs have been escaped and are safe to use with MySQL.
 * 
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int) $idIn Id of the session to update.
 * @param   (int) $numberIn New Number for the session.
 * @param   (string) $mandatoryIn New Mandatory for the session.
 * @param   (string) $requirePassportIn New requirePassport for the session.
 * @param   (string) $descriptionIn New Description for the session.
 * @param   (int) $dayIn New Day for the session.
 * @param   (int) $maxIn New Maximum for the session.
 * @param   (string) $locationIn Room/Location for the session.
 * @return  (bool) True on successful update.
 * @return  (string) Error string obtained from mysqli.
 */
function updateSession(&$dbConnectionObject,$idIn,$numberIn,$mandatoryIn,$requirePassportIn,$descriptionIn,$dayIn,$maxIn,$locationIn) {
    $successfullUpdate = (bool) false;
    $errorMessage = (string) "";

    $str_update = (string) "UPDATE `sessions` SET"
        . " `number_sessions`={$numberIn},"
        . " `mandatory_sessions`='{$mandatoryIn}',"
        . " `requirePassport_sessions`='$requirePassportIn',"
        . " `description_sessions`='$descriptionIn',"
        . " `max_sessions`={$maxIn},"
        . " `day_sessions`={$dayIn},"
        . " `location_sessions`='{$locationIn}'"
        . " WHERE `id_sessions`={$idIn};";

    mysqli_query($dbConnectionObject, $str_update);

    if (mysqli_errno($dbConnectionObject) === 0) {
        if (mysqli_affected_rows($dbConnectionObject) > 0) {
            $successfullUpdate = true;
        } else {
            $errorMessage = "No rows affected by the update query while updating session.";
        }
    } else {
        $errorMessage = mysqli_error($dbConnectionObject);
    }
    
    if (!empty($errorMessage)) {
        return $errorMessage;
    } else {
        return $successfullUpdate;
    }
}

/*
 * getSessionById will return the contents of a session based on id.
 * 
 * @param (int) $sessionIdIn  Used to specify the session id.
 * @param (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @return (array) $session An associative (named keys) array of the session fields.
 * @return (string) $error If mysqli returns an error, this function returns it.
 */
function getSessionById($sessionIdIn,&$dbConnectionObject) {
    // Build the query
    $str_query = (string) "SELECT * FROM `sessions` WHERE `id_sessions`={$sessionIdIn};";
    
    // Query the db
    $obj_queryResult = mysqli_query($dbConnectionObject, $str_query);
    
    // Check for query errors
    if (mysqli_errno($dbConnectionObject) === 0) {
        $session = mysqli_fetch_assoc($obj_queryResult);
        mysqli_free_result($obj_queryResult);
    } else {
        $session = (string) mysqli_error($dbConnectionObject);
    }// end if query errors
    
    return $session;
}// end getSessionById function

/*
 * getSessionsByDay will return all of the sessions for a given day.
 * 
 * @param (int) $dayIn  Used to specify the day. Day 0 will show sessions from all of the days.
 * @param (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @return (array) $sessions An associative (named keys) array of the sessions.
 * @return (string) $error If mysqli returns an error, this function returns it.
 */
function getSessionsByDay($dayIn,&$dbConnectionObject) {
    // Build the query
    $str_query = (string) 'SELECT * FROM `sessions`';
    if ($dayIn != 0) {
        $str_query .= ' WHERE `day_sessions`=' . $dayIn;
    }// end if looking for specific day
    $str_query .= ';';
    
    // Query the db
    $obj_queryResult = mysqli_query($dbConnectionObject, $str_query);
    
    // Check for query errors
    if (mysqli_errno($dbConnectionObject) === 0) {
        $sessions = (array) Array();

        for ($i = 0; $i < mysqli_num_rows($obj_queryResult); $i++) {
            $sessions[$i] = mysqli_fetch_assoc($obj_queryResult);
        }// end foreach loop

        mysqli_free_result($obj_queryResult);
    } else {
        $sessions = (string) mysqli_error($dbConnectionObject);
    }// end if query errors
    
    return $sessions;
}// end getSessionsByDay function

/*
 * seatsRemaining Will return the number of seats left available for a session.
 * 
 * @param (int) $sessionId The ID of the session to inspect.
 * @param (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @return (int) The number of seats left available.
 * @return (string) The error returned by mysqli.
 */
function seatsRemaining($sessionId, &$dbConnectionObject) {
    // Declare and initialize a container for mysqli errors.
    $errorAccumulator = (string) '';

    /*
     * Get the maximum number of seats allowed
     */
    $str_getMaxQuery = (string) "SELECT `max_sessions` FROM `sessions`"
            . " WHERE `id_sessions`={$sessionId};";

    // Query the database
    $obj_getMaxResult = mysqli_query($dbConnectionObject, $str_getMaxQuery);
    
    if (mysqli_errno($dbConnectionObject) !== 0) {
        $errorAccumulator .= mysqli_error($dbConnectionObject) . ' | ';
    } else {
        $ary_getMaxResult = mysqli_fetch_assoc($obj_getMaxResult);

        // Free result object
        mysqli_free_result($obj_getMaxResult);

        $int_maxSeats = (int) $ary_getMaxResult["max_sessions"];
        unset($ary_getMaxResult, $obj_getMaxResult);

        /*
         * Get the number of registered students
         */
        $str_getStudentCountQuery = (string) "SELECT `id_registrations` FROM `registrations`"
                . " WHERE `sessionid_registrations`={$sessionId};";

        // Query the database
        $obj_getStudentCountResult = mysqli_query($dbConnectionObject, $str_getStudentCountQuery);
        
        if (mysqli_errno($dbConnectionObject) !== 0) {
            $errorAccumulator .= mysqli_error($dbConnectionObject) . ' | ';
        } else {
            $int_studentCount = (int) mysqli_num_rows($obj_getStudentCountResult);

            // Free result object
            mysqli_free_result($obj_getStudentCountResult);
        }// if query errors save error message

        /*
         * Two exit points
         */
        if ($errorAccumulator != '') {
            // Return the errors
            return $errorAccumulator;
        } else {
            // Return the number of available seats
            return $int_maxSeats - $int_studentCount;
        }// if query errors before completing
    }// if query errors save error message

}// end seatsRemaining function

/*
 * setSessionMax will update the maximum seats allowed for a session.
 * 
 * @param (int) $sessionIdIn    The id of session to update.
 * 
 * @return (bool) $setResult    True on success. False on failures.
 */
?>