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

// If $str_appLocation is not set, environment.php must not
// have been run.
if(!isset($str_appLocation)) {
    $str_basePath = (string) realpath(dirname(__FILE__));
    $ary_basePath = (array) explode('/', $str_basePath);

    // Rebuild the path without the last item. Last item of path
    // is the location of *this* script, in the ./lib subdirectory.
    $str_basePath = '';
    for($i = (int) 0; $i < count($ary_basePath)-1; $i++) {
        $str_basePath .= $ary_basePath[$i] . '/';
    }// end for loop

    // Include the missing data files and continue normal flow
    require "{$str_basePath}data/environment.php";

    // Destroy the temporary variables
    unset($ary_basePath, $str_basePath);
}// end if statement

require_once "{$str_appLocation}data/db.php";
require_once "{$str_appLocation}lib/prettyErrors.php";

// Open the database connection
if ($bln_isSecure) {
    $dbConnectionObject = @mysqli_init();
    mysqli_ssl_set($dbConnectionObject, $str_pathToKey, $str_pathToCert, $str_pathToCA, NULL, NULL);
    mysqli_real_connect($dbConnectionObject, $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb);
} else {
    $dbConnectionObject = @mysqli_connect($str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb);
}

// Die on connection failures. Link to mailto:$str_supportEmail with a nice interface.
if(mysqli_connect_errno() != 0) {
    require "{$str_appLocation}views/errorMessages_en_dbConnect.php";
    showPrettyError($errorMessage, 'error', true);
}// end if statement

// Set the character set, for use with mysqli_real_escape_string
mysqli_set_charset($dbConnectionObject, $str_dbCharset);