<?php
/**
 * loadParser.php v1.0.3	-	conference-registration
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

// If $str_appLocation is not set, environment.php must not
// have been run.
if (!isset( $str_appLocation)) {
    $str_basePath = (string) realpath(dirname(__FILE__));
    $ary_basePath = (array) explode('/', $str_basePath);

    // Rebuild the path without the last item. Last item of path
    // is the location of *this* script, in the ./lib subdirectory.
    $str_basePath = '';
    for($i = (int) 0; $i < count($ary_basePath)-1; $i++) {
        $str_basePath .= $ary_basePath[$i] . '/';
    }// end for loop

    // Include the missing data files and continue normal flow
    require_once $str_basePath . 'data/environment.php';

    // Destroy the temporary variables
    unset($ary_basePath, $str_basePath);
}// end if statement

// Load echoToConsole function
if(!function_exists('echoToConsole')) {
    require "{$str_appLocation}lib/logging.php";
}// end if statement

/**
 * The parseLoadFile function will convert a data file's contents,
 * from a string, into an array. Once the array is created, the
 * large string is destroyed and the array is returned.
 * 
 * @param $loadContentIn (string) File contents as a string.
 * @param $fileDelimiter (string) The character used as value delimiter
 * @return (array) A 2-dimensional array representation of the CSVs.
 */
function parseLoadFile($loadContentIn, $fileDelimiter) {
    $ary_loadContent_fullFile = (array) str_getcsv($loadContentIn, "\n");
    $ary_loadContent_multiDimensions = (array) Array();

    foreach ($ary_loadContent_fullFile as $str_record) {
        $ary_loadContent_record = (array) str_getcsv($str_record, $fileDelimiter, '"');
        $ary_loadContent_multiDimensions[] = $ary_loadContent_record;
    }// end foreach loop

    return (array) $ary_loadContent_multiDimensions;
}// end parseLoadFile function
?>