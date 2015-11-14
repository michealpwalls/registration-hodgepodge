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
 *  validateUserId inspects the contents of $_POST[$inputName]
 *  and verifies that it is a valid student ID for Georgian College.
 *  Valid studentIDs are integers in the range 9999 - 9999999999 (4 - 10 digits).
 * 
 *  @param (string) $inputName  Name of the POST field to work on.
 *  @return (boolean) True if $inputName validated; False otherwise.
 */
function validateUserId($inputName) {
    $array_filterOptions = (array) array('options' => (array) array("min_range" => 99999, "max_range" => 99999999999));
    $validatedInput = filter_input(INPUT_POST, $inputName, FILTER_VALIDATE_INT, $array_filterOptions);

    if($validatedInput) {
        return true;
    }// end if statement

    return false;
}// end validateUserId function

/*
 *  sanitizeSimpleStrings strips everything from $_POST[$inputName]
 *  that isn't in the standard ASCII character range.
 * 
 *  @param (string) $inputName  Name of the POST field to work on.
 *  @return (string) The sanitized $inputName
 */
function sanitizeSimpleStrings($inputName) {
    $array_filterFlags = (array) array('flags' => (array) array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
    $sanitizedInput = filter_input(INPUT_POST, $inputName, FILTER_VALIDATE_INT, $array_filterFlags);

    if($sanitizedInput !== false) {
        return $sanitizedInput;
    }
}// end sanitizeSimpleStrings function
?>