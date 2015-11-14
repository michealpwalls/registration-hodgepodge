<?php
/**
 * fileUploads.php	-	conference-registration
 * 
 * Copyright 2014 Micheal Walls <michealpwalls@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */

//Load echoToConsole function
if (!function_exists('echoToConsole')) {
    require "{$str_appLocation}lib/logging.php";
}// end if statement

/**
 * The getFileUpload function is a simple wrapper for file uploads.
 * Makes use of echoToConsole function in logging.php to log errors
 * to the browser's javascript debugging console.
 * 
 * @param (string)  $fileNameIn Name of input containing file.
 * @param (string)  $fileTypeIn An acceptable MIME type to accept.
 * @return (string) File contents will be returned as a string.
 * @return (bool)   Function will return false when displaying the input form
 *                  and when file contents are unreadable from tmp storage.
 * @global (string) $str_defaultStructure Used in the form to let the
 *                  user define the structure of Db table file contents will be
 *                  inserted into. Example:
                    $str_defaultStructure = (string) '(`col1`,`col2`)'
 * @global (string) $str_targetDb Used to control the target Db table
 *                  file contents will be inserted into.
 * @global (string) $str_appLocation Pathname used to easily find scripts.
 * @global (string) $adminTokenInput A string containing a 48 character security token used to authenticate administrative staff.
 * 
 */
function getFileUpload($fileNameIn, $fileTypeIn) {
    //Map local reference to global variable
    global $str_defaultStructure, $str_targetDb, $str_appLocation,$adminTokenInput;

    // Is a file incoming?
    if (isset($_FILES[$fileNameIn])) {
        // Incoming File
        echoToConsole("A file is incoming", true);
        
        // Error with incoming file
        if ($_FILES[$fileNameIn]["error"] > 0) {
            echoToConsole("There was an error with the incoming file: {$_FILES[$fileNameIn]['error']}", true);
            echo "<p style=\"color: red;\">There was an error with the incoming file: {$_FILES[$fileNameIn]['error']}</p>\n";
            return (bool) false;
        } else {
            // Good file received!
            echoToConsole("A file was received!", true);
            // If an 'uploads' directory does not exist, create one
            if (!file_exists("{$str_appLocation}admin/setup/uploads")) {
                echoToConsole("Upload directory does not exist, attempting to create it", true);

                $bln_mkdirResult = mkdir("{$str_appLocation}admin/setup/uploads");

                if ($bln_mkdirResult) {
                    echoToConsole("Upload directory created successfully.", true);
                } else {
                    echoToConsole("Failed to create upload directory!", true);
                }// end if statement
            }// end if statement

            /**
             * Move the file from /tmp/ area to permanent storage
             * in ./uploads area
             */
            if(isset($bln_mkdirResult) && $bln_mkdirResult) {
                $bln_moveResult = move_uploaded_file($_FILES[$fileNameIn]["tmp_name"], "{$str_appLocation}admin/setup/uploads/{$fileNameIn}");
            } else {
                $bln_moveResult = false;
            }// end if statement

            if ($bln_moveResult) {
                echoToConsole("File was successfully saved", true);

                $str_fileContents = (string) file_get_contents("{$str_appLocation}admin/setup/uploads/{$fileNameIn}");
            } else {
                echoToConsole("File was not saved successfully", true);
                $str_fileContents = (string) file_get_contents($_FILES[$fileNameIn]['tmp_name']);
            }// end if statment
            
            return (string) $str_fileContents;
        }// end if statement
    } else {
        // No incoming file, show Input Form
        echoToConsole("No incoming file, displaying input form", true);
        require "{$str_appLocation}views/forms_en_fileInput.php";
        return (bool) false;
    }// end if statement
}// end getFileUpload() function
?>