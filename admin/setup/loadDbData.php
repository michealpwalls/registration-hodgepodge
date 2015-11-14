<?php
/**
 * loadDbData.php		- conference-registration
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

require "{$str_appLocation}lib/fileUploads.php";
require "{$str_appLocation}lib/loadParser.php";

$str_fileType = (string) 'text/plain';

/**
 * Get the data file from the user
 */
$str_fileName = (string) $str_targetDb . 'File';
$uploadResult = getFileUpload($str_fileName, $str_fileType);

if (is_string($uploadResult)) {
    // Get file delimiter. If invalid set a default
    $str_fileDelimiter = filter_input(INPUT_POST, 'fileDelimiter', FILTER_SANITIZE_STRING);
    if(!$str_fileDelimiter) { $str_fileDelimiter = ','; }// end if statement

    /**
     * Parse the string input
     */
    $ary_loadContent = parseLoadFile($uploadResult, $str_fileDelimiter);

    //Free memory
    unset($uploadResult);

    // Get data structure
    $array_filterFlags = (array) array('flags' => (array) array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
    $str_dataStructure = filter_input(INPUT_POST, 'dataStructure', FILTER_SANITIZE_STRING, $array_filterFlags);

    // Connect to the Db
    require "{$str_appLocation}lib/dbConnect.php";

    // Escape the input string
    $str_dataStructure = mysqli_real_escape_string($dbConnectionObject, $str_dataStructure);

    /*
    $array_filterOptions = (array) array('options' => (array) array("regexp" => '~^[(a-zA-Z0-9_)]+$~'));
    $str_dataStructure = filter_input(INPUT_POST, 'dataStructure', FILTER_VALIDATE_REGEXP, $array_filterOptions);

    if(!$str_dataStructure) {
        echoToConsole('dataStructure did not pass validation', true);
    }// Debug output
    */

    /**
    * Build the Query
    */
    $loadQuery = (string) "INSERT INTO {$str_targetDb} {$str_dataStructure} VALUES\n";

    // Outter loop
    for ($i = 0; $i < count($ary_loadContent); ++$i) {
        $loadQuery .= "\t( ";

        // Inner loop
        for ($y = 0; $y < count( $ary_loadContent[$i]); ++$y ) {
            $loadQuery .= $ary_loadContent[$i][$y];

            // Only append a comma when not the last item
            if ($y < count($ary_loadContent[$i]) - 1) {
                $loadQuery .= ",";
            }// end if statement
        }// end inner for loop

        if ($i == count($ary_loadContent) - 1) {
            $loadQuery .= ")";
        } else {
            $loadQuery .= "),\n";
        }// end if statement
    }// end outer for loop

    $loadQuery .= ";";

    /**
    * Query the Database
    */
    if(!isset($dbConnectionObject)) {
        require $str_appLocation . 'lib/dbConnect.php';
    }

    //Query the Database
    $loadResult = mysqli_query($dbConnectionObject, $loadQuery);

    if ($loadResult == false) {
        echoToConsole( "The query returned False!", true );

        if ($bln_isBeta) {
            echo "<p>The query string was: {$loadQuery}</p>\n";
        }// Debug output

        if ($bln_isBeta) {
            echo "<p>The error returned by MySQL was: " . mysqli_error($dbConnectionObject) . "</p>\n";
        }// Debug output
    } else {
        echo "<div class=\"upper-space ui-state-info\">Successfully loaded " . mysqli_affected_rows( $dbConnectionObject ) . " records into the database.</div>\n";
    }// end if statement

    //Disconnect from the Database
    mysqli_close($dbConnectionObject);
    unset($dbConnectionObject);
}// end if statement
?>