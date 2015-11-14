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

// Start buffering the output ( Makes using headers easier :P )
ob_start();

require "../data/environment.php";
require "{$str_appLocation}data/studentRegData.php";
require "{$str_appLocation}data/adminToken.php";
require "{$str_appLocation}lib/prettyErrors.php";
require "{$str_appLocation}lib/logging.php";

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
    require "{$str_appLocation}views/errorMessages_en_directComponentAccess.php";

    showPrettyError($errorMessage, 'error', true, 'Direct Component Access');
}// If ADMTKN not defined

$optionsStripEverything = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
$dataToExport = filter_input(INPUT_POST, 'dataToExport', FILTER_SANITIZE_STRING, $optionsStripEverything);
$exportFormat = filter_input(INPUT_POST, 'dataFormat', FILTER_SANITIZE_STRING, $optionsStripEverything);

if (empty($exportFormat)) {
    $exportFormat = "csv";
} else {
    $exportFormat = htmlentities($exportFormat);
}

header("Content-Type: text/{$exportFormat}; charset=utf-8");
header("Content-Disposition: attachment; filename=reportExport.{$exportFormat}");

if (!empty($dataToExport)) {
    echo $dataToExport;
}

// "Flush" (send) the buffer to the browser
ob_end_flush();