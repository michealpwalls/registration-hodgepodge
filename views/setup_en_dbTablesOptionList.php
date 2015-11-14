<?php
if($bln_isStudentReg) {
    echo "<option value=\"students\">Students</option>\n
        <option value=\"sessions\">Sessions</option>\n
        <option value=\"registrations\">Registrations</option>\n";
} else {
    echo "<option value=\"users\">Users</option>\n
        <option value=\"registered\">Registered</option>\n
        <option value=\"waitlist\">Waitlist</option>\n
        <option value=\"workshops\">Workshops</option>\n
        <option value=\"keynotes\">Keynotes</option>\n";
}// end if statement
?>