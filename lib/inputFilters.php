<?php
/*
 * inputFilter.php v1.1.0	-	conference-registration
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