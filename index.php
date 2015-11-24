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