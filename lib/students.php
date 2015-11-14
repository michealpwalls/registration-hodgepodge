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
 * getStudent returns an array with given student id's data.
 * 
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int)   $studentIdIn ID of the student to lookup.
 * @return  (array) An asssociative array of student's data record.
 * @return  (string) Error obtained by mysqli.
 */
function getStudent(&$dbConnectionObject,$studentIdIn) {
    $str_query = (string) "SELECT * FROM `students` WHERE `id_students`={$studentIdIn};";

    $obj_queryResult = mysqli_query($dbConnectionObject, $str_query);

    if (mysqli_errno($dbConnectionObject) === 0) {
        $ary_queryResult = (array) mysqli_fetch_assoc($obj_queryResult);
        mysqli_free_result(($obj_queryResult));
        return (array) ($ary_queryResult);
    } else {
        return (string) (mysqli_error($dbConnectionObject));
    }

}

/*
 * Uses internal tokens to check if a given student exists or not. Returns the student id (int) if they
 * exist. Returns boolean false in all other cases. Note this function assumes
 * all inputs are valid and safe for use with MySQL.
 * 
 * @global  (bool) $bln_isBeta Flag to control debugging output.
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (string)   $studentTokenIn Token of the student to check for.
 * @return  (int)   Id of student if they exist. Id may eval to false with ==.
 * @return  (bool)  False Will return boolean False if student does not exist.
 */
function doesStudentExistByToken(&$dbConnectionObject,$studentTokenIn) {
    global $bln_isBeta;
    $returnValue = (bool) false;

    // Query the database for the student
    $str_query_getStudentData = "select `id_students` from `students` where `token_students`='$studentTokenIn';";
    $obj_query_getStudentDataResult = mysqli_query($dbConnectionObject, $str_query_getStudentData);

    // If an error occurred and the app is in beta then show the error
    if (mysqli_errno($dbConnectionObject)) {
        if ($bln_isBeta) { echoToConsole('mysqli error while selecting student: ' . mysqli_error($dbConnectionObject), true); }
    } else if (mysqli_num_rows($obj_query_getStudentDataResult) != 1) {
        if ($bln_isBeta) { echoToConsole('Student did not exist', true); }
    } else {
        $ary_query_getStudentDataResult = mysqli_fetch_assoc($obj_query_getStudentDataResult);

        mysqli_free_result($obj_query_getStudentDataResult);
        $returnValue = true;
    }

    if ($returnValue) {
        return (int) $ary_query_getStudentDataResult["id_students"];
    } else {
        return (bool) $returnValue;
    }
}

/*
 * Uses Georgian ID to check if a given student exists or not. Returns the student id (int) if they
 * exist. Returns boolean false in all other cases. Note this function assumes
 * all inputs are valid and safe for use with MySQL.
 * 
 * @global  (bool) $bln_isBeta Flag to control debugging output.
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int)   $georgianIdIn Georgian ID of the student to check for.
 * @return  (int)   Id of student if they exist. Id may eval to false with ==.
 * @return  (bool)  False Will return boolean False if student does not exist.
 */
function doesStudentExistByGeorgianId(&$dbConnectionObject,$georgianIdIn) {
    global $bln_isBeta;
    $returnValue = (bool) false;

    // Query the database for the student
    $str_query_getStudentData = "select `id_students` from `students` where `georgianid_students`='$georgianIdIn';";
    $obj_query_getStudentDataResult = mysqli_query($dbConnectionObject, $str_query_getStudentData);

    // If an error occurred and the app is in beta then show the error
    if (mysqli_errno($dbConnectionObject)) {
        if ($bln_isBeta) { echoToConsole('mysqli error while selecting student: ' . mysqli_error($dbConnectionObject), true); }
     } else if (mysqli_num_rows($obj_query_getStudentDataResult) != 1) {
        if ($bln_isBeta) { echoToConsole('Student did not exist', true); }
    } else {
        $ary_query_getStudentDataResult = mysqli_fetch_assoc($obj_query_getStudentDataResult);

        mysqli_free_result($obj_query_getStudentDataResult);
        $returnValue = true;
    }

    if ($returnValue) {
        return (int) $ary_query_getStudentDataResult["id_students"];
    } else {
        return (bool) $returnValue;
    }
}

/*
 * Verify that the student inputs match the student database records in order
 * to assume student is real. For obscurity, this function will not tell the
 * user what field caused the verification to fail.
 * 
 * @global  (bool) $bln_isBeta Flag to control debugging output.
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (string) $georgianIdIn Georgian ID of the student.
 * @param   (string) $fullNameIn First name of the student.
 * @return  (bool) False if student is not or could not be verified
 * @return  (string) Email address of verified student
 */
function verifyStudent(&$dbConnectionObject, $georgianIdIn, $fullNameIn) {
    global $bln_isBeta;
    $verificationResult = (bool) false;
    $str_query_verifyStudent = (string) "select `email_students`,`token_students` from `students` where `georgianid_students`={$georgianIdIn} and `fullname_students`='{$fullNameIn}';";
    $obj_query_verifyStudent = mysqli_query($dbConnectionObject, $str_query_verifyStudent);
    
    if (mysqli_errno($dbConnectionObject)) {
        if ($bln_isBeta) { echoToConsole('mysqli error while selecting verifying student: ' . mysqli_error($dbConnectionObject), true); }
    } else {
        $ary_query_verifyStudent = mysqli_fetch_array($obj_query_verifyStudent);
        mysqli_free_result($obj_query_verifyStudent);
        
        // Check to make sure the student hasn't already been verified
        if ($ary_query_verifyStudent[1] === NULL) {
            $verificationResult = true;
        } else if ($bln_isBeta) {
            echoToConsole("Student has already been verified!", true);
        }
    }
    
    if ($verificationResult) {
        return (string) $ary_query_verifyStudent[0];
    } else {
        return (bool) $verificationResult;
    }
}

/*
 * Returns the group a student is in based on student id.
 * 
 * @global  (bool) $bln_isBeta Flag to control debugging output.
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int)   $studentIdIn Id of the student to check for.
 * @return  (string) On successful get, returns student's group.
 * @return  (bool)  Will return boolean false on failures.
 */
function getStudentGroup(&$dbConnectionObject, $studentIdIn) {
    global $bln_isBeta;
    $selectResult = (bool) false;
    $studentGroupOut = (string) "";
    
    $str_select = "select `group_students` from `students` where `id_students`=$studentIdIn;";
    $obj_selectResult = mysqli_query($dbConnectionObject, $str_select);

    if (mysqli_errno($dbConnectionObject)) {
        if ($bln_isBeta) { echoToConsole('mysqli error while selecting student group: ' . mysqli_error($dbConnectionObject), true); }
    } else {
        $ary_selectResult = mysqli_fetch_assoc($obj_selectResult);
        mysqli_free_result($obj_selectResult);
        $studentGroupOut = $ary_selectResult["group_students"];
        unset($ary_selectResult);
        $selectResult = true;
    }
    
    if ($selectResult) {
        return $studentGroupOut;
    } else {
        return $selectResult;
    }
}

/*
 * Get an array of all the session ids that the student is assigned to. Note
 * that this function assumes all inputs are valid and safe for use with MySQL.
 * 
 * @global  (bool) $bln_isBeta Flag to control debugging output.
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (int)   $studentIdIn Id of the student to check for.
 * @return  (array) On successful get, returns an array of session ids (ints).
 * @return  (bool)  Will return boolean false on failures.
 */
function getStudentSessions(&$dbConnectionObject, $studentIdIn) {
    global $bln_isBeta;
    $getResult = (bool) false;
    $ary_query_getSessionsResult = (array) Array();

    $str_query_getSessions = "select `sessionid_registrations` from `registrations` where `studentid_registrations`=$studentIdIn;";
    $obj_query_getSessionsResult = mysqli_query($dbConnectionObject, $str_query_getSessions);

    if (mysqli_errno($dbConnectionObject)) {
        if ($bln_isBeta) { echoToConsole('mysqli error while selecting student sessions: ' . mysqli_error($dbConnectionObject), true); }
     } else if (mysqli_num_rows($obj_query_getSessionsResult) === 0) {
        if ($bln_isBeta) { echoToConsole('Student has no sessions', true); }
    } else {
        for ($i = 0; $i < mysqli_num_rows($obj_query_getSessionsResult); $i++) {
            $ary_query_getSessionsResult[$i] = mysqli_fetch_row($obj_query_getSessionsResult);
        }
        
        mysqli_free_result($obj_query_getSessionsResult);
        $getResult = true;
    }
    
    if ($getResult) {
        return $ary_query_getSessionsResult;
    } else {
        return $getResult;
    }
}

/*
 * Initializes a student record in the database. Note this function
 * assumes all inputs are valid and safe for use with MySQL.
 * 
 * @global  (bool) $bln_isBeta Flag to control debugging output.
 * @param   (object-ref) $dbConnectionObject A reference to an open mysqli link.
 * @param   (string) $studentTokenIn The security token for the student account.
 * @param   (int) $georgianIdIn Georgian ID of the student.
 * @param   (string) $studentNameIn Name of the student (Full).
 * @param   (string) $studentEmailIn Email address of the student.
 * @return  (int) Student ID after successful initialization.
 * @return  (bool) False on failures.
 */
function initStudent(&$dbConnectionObject,$studentTokenIn,$georgianIdIn,$studentNameIn,$studentEmailIn) {
    global $bln_isBeta;
    $returnValue = (bool) false;

    //
    //TODO: Check if georgianid already exists
    //
    if (doesStudentExistByGeorgianId($dbConnectionObject, $georgianIdIn) !== false) {
        $errorTitle = (string) "Student already exists";
        $errorMessage = (string) "A user with that Georgian student ID already exists in our systems. Check your mail for your access link.";
        showPrettyError($errorMessage, 'error', true, $errorTitle);
    }
    
    $str_insertUser = "INSERT INTO `students` (`token_students`,`georgianid_students`,`fullname_students`,`email_students`)"
        . " VALUES('{$studentTokenIn}',{$georgianIdIn},'{$studentNameIn}','{$studentEmailIn}');";

    mysqli_query($dbConnectionObject, $str_insertUser);
    
    // If an error occurred and the app is in beta then show the error
    if (mysqli_errno($dbConnectionObject)) {
        if ($bln_isBeta) { echoToConsole('mysqli error while initializing student: ' . mysqli_error($dbConnectionObject), true); }
        if ($bln_isBeta) { echoToConsole("Query used: {$str_insertUser}", true); }
    } else {
        $str_selectStudent = "SELECT `id_students` FROM `students` WHERE `georgianid_students`='{$georgianIdIn}';";
        
        $obj_selectStudentResult = mysqli_query($dbConnectionObject,$str_selectStudent);
        $ary_selectStudentResult = mysqli_fetch_assoc($obj_selectStudentResult);
        
        mysqli_free_result(($obj_selectStudentResult));
        $returnValue = true;
    }
    
    if ($returnValue) {
        return $ary_selectStudentResult['id_students'];
    } else {
        return $returnValue;
    }
}// end of initStudent
?>