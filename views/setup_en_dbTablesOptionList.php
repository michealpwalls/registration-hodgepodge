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