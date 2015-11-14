<?php
/*
 * dashboardNavigation_conference.php v1.0.1	-	conference-registration
 */

echo '<strong>Lists:</strong>&nbsp;'
    . '<a href="index.php?action=listAllUsers">All Users</a>&nbsp;<strong>|</strong>&nbsp;'
    . '<a href="index.php?action=listRegistrants">Attending a Session</a>&nbsp;<strong>|</strong>&nbsp;'
    . '<a href="index.php?action=listUsersNR">Not attending a Session</a>&nbsp;<strong>|</strong>&nbsp;'
    . '<a href="index.php?action=listWorkshops">Sessions</a>&nbsp;<strong>|</strong>&nbsp;'
    . '<a href="index.php?action=listWorkshopsNotRegistrants">Sessions (Without Users)</a>&nbsp;<strong>|</strong>&nbsp;'
    . '<a href="index.php?action=listUsersFOTC">Attending FoTC</a>&nbsp;<strong>|</strong>&nbsp;'
    . '<a href="index.php?action=listWaitlisted">Waitlisted users</a>'
    . '<br><br><strong>Exports:</strong>&nbsp;'
    . '<a href="index.php?action=nametags">Generate Nametags</a>';
?>