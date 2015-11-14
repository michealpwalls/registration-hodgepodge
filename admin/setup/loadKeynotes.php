<?php
/**
 * loadKeynotes.php 1.0.2		- conference-registration
 */

if(!function_exists( getFileUpload )) {
    require_once $str_appLocation . "../lib/fileUploads.php";
}// end if statement

if( !function_exists( parseLoadFile ) ) {
    require_once $str_appLocation . "../lib/loadParser.php";
}// end if statement

//Portable data
$str_loadTarget = (string) 'keynotes';
//$str_fileType = (string) 'text/plain';

/**
 * Get the data file from the user
 */
$str_fileName = (string) $str_loadTarget . 'File';
$uploadResult = getFileUpload($str_fileName, $str_fileType, $str_loadTarget);

if(is_bool( $uploadResult )) {
    if($uploadResult == true) {
        /**
         * Got a working file to work with!
         */
        echoToConsole("Upload function got a working file to work with!", true);

        $str_loadContent = (string) file_get_contents("{$str_appLocation}admin/setup/uploads/{$str_fileName}");
    } else {
        /**
         * There was an internal error that could not be recovered
         * from and the upload function failed.
         */
         echoToConsole( "Upload function returned boolean False", true );
    }// end if statement
} else if(is_string( $uploadResult )) {
    /**
     * The upload failed but the file data was parsed and returned
     * as a string instead.
     */
    echoToConsole("The upload function failed to store the file to disk. Attempting to continue. This may cause app to run out of memory.", true);

    $str_loadContent = (string) $uploadResult;

    //Free memory
    unset( $uploadResult );
}// end if statement

if(isset( $str_loadContent )) {
/**
 * Parse the string input
 */
    $ary_loadContent = (array) Array();
    $ary_loadContent = parseLoadFile( $str_loadContent );

    //Free memory
    unset($str_loadContent);

/**
 * Build the Query
 */
    $loadQuery = (string) "INSERT INTO keynotes VALUES\n";

    for( $i = 0; $i < count($ary_loadContent); ++$i ) {

        $loadQuery .= "\t( ";

        for( $y = 0; $y < count( $ary_loadContent[$i] ); ++$y ) {

            $loadQuery .= $ary_loadContent[$i][$y];

            if( $y < count($ary_loadContent[$i]) - 1 ) {
                $loadQuery .= ",";
            }// end if statement

        }// end inner for loop

        if( $i == count($ary_loadContent) - 1 ) {
            $loadQuery .= ")";
        } else {
            $loadQuery .= "),\n";
        }// end if statement

    }// end outer for loop

    $loadQuery .= ";";

/**
 * Connect to and Query the Database
 */
    // Connecto to the database
    require $str_appLocation . 'lib/dbConnect.php';

    //Query the Database
    $loadResult = mysqli_query( $dbConnectionObject, $loadQuery );

    if( $loadResult == false ) {
        echoToConsole( "The query returned False!", true );
    } else {
        echo "<div class=\"upper-space ui-state-info\">Successfully loaded " . mysqli_affected_rows( $dbConnectionObject ) . " records into the database.</div>\n";
    }// end if statement

    //Disconnect from the Database
    mysqli_close($dbConnectionObject);
    unset($dbConnectionObject);
}// end if statement
?>