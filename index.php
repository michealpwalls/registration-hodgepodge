<?php
/*
 * index.php   -       conference-registration
 */

// Begin output buffering
ob_start();

echo '<!DOCTYPE html>';

require 'data/environment.php';
require 'data/db.php';

$ctlIn = filter_input(INPUT_GET, 'ctl', FILTER_SANITIZE_NUMBER_INT);
if( !empty( $ctlIn ) ) {
    $ctlMember = (bool) true;
} else {
    $ctlMember = (bool) false;
}// end if statemenet

if($bln_isConference) {
    include 'index_en_conference.php';
} else {
    include 'index_en_studentRegistration.php';
}

// Make sure no db Connections are open
if (isset($dbConnectionObject)) {
    if (is_object(($dbConnectionObject))) {
        @mysqli_close($dbConnectionObject);
    }
    unset($dbConnectionObject);
}
?>
        </div>
    </body>
<?php
if($bln_isStudentReg) {
    include 'views/georgianFooter.php';
}
?>
</html>
<?php
// Flush (send) the output buffer's contents and close it
ob_end_flush();
?>