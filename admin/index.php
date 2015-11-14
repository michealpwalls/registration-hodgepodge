<!DOCTYPE html>
<?php
/*
 * index.php	-	conference-registration
 */

require "../data/environment.php";

require "{$str_appLocation}data/adminToken.php";
require "{$str_appLocation}data/db.php";
require "{$str_appLocation}lib/logging.php";
require "{$str_appLocation}lib/prettyErrors.php";
require "{$str_appLocation}views/headToBody_jqueryUiConfirmations.php";
require "{$str_appLocation}views/georgianHeader.php";

$optionsStripAll = Array("options" => Array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH));
$adminAction = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING, $optionsStripAll);
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
?>
        <nav class="dashboardMenu">
<?php
if($bln_isConference) {
    require $str_appLocation . 'views/dashboardNavigation_conference.php';
}

if($bln_isStudentReg) {
    require $str_appLocation . 'views/dashboardNavigation_students.php';
}

if($bln_isBeta) {
    require $str_appLocation . 'views/dashboardNavigation_adminControls.php';
}
?>
        </nav>
        <div class="main">
            <section>
                <span class="version"><?=$str_appName;?> <?=$str_appVersion;?></span>
                <h1>Administrative Dashboard</h1>
            </section>
            <section title="Administrative Dashboard">
<?php
if (empty($adminAction)) {
    /**
     * Default Action (Show overview of reports)
     */
    if ($bln_isConference) {
        require "{$str_appLocation}lib/reports_conference.php";
        reportsConference_showReportOverview();
    } else {
        require "{$str_appLocation}lib/dbConnect.php";
        require "{$str_appLocation}lib/reports_students.php";
        
        // Show a default report
        reports_defaultReport($dbConnectionObject);
        
        // Let the administrator choose another report to see
        require "{$str_appLocation}views/forms_en_reportPicker.php";
    }// end if statement
} else {
    require "{$str_appLocation}admin/index_en_shared.php";

    if ($bln_isConference) {
        require "{$str_appLocation}admin/index_en_conference.php";
    } else {
        require "{$str_appLocation}admin/index_en_students.php";
    }
}// end if statement

// Make sure no db Connections are open
if (isset($dbConnectionObject)) {
    if (is_object(($dbConnectionObject))) {
        mysqli_close($dbConnectionObject);
    }
    unset($dbConnectionObject);
}
?>
            </section>
        </div>
<?php
include "{$str_appLocation}views/georgianFooter.php";
?>
    </body>
</html>