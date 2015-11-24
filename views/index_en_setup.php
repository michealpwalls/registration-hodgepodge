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

echo "<h3>Setup the {$str_appName} System.</h3><br>\n";
echoToConsole( "setupAction was not set, displaying form", true );

if($bln_isConference) {
    echo "<br><strong>Waitlist Controls:</strong>&nbsp;<br>\n"
        . "<a href=\"index.php?action=inviteWaitlisted\">Invite all Waitlisted users</a><br><br>\n";
}

echo "<form method=\"post\" action=\"index.php?action=setup&admtkn={$adminTokenInput}\">\n
    Select a setup action to perform:<br>\n
    &nbsp;&nbsp;<select name=\"setupAction\" id=\"setupAction\">\n
        <option value=\"default\" selected> -- Choose and Action -- </option>\n";

if($bln_isConference) {
    echo "<optgroup label=\"Keynote Actions\">
                <option value=\"load-keynotes\">Load New Keynotes</option>\n
                <option value=\"reset-keynotes\">Reset the Keynote Seats</option>\n
            </optgroup>
            <optgroup label=\"Workshop Actions\">
                <option value=\"load-workshops\">Load New Workshops</option>\n
            </optgroup>";
}

if($bln_isStudentReg) {
    echo "<optgroup label=\"Database Actions\">
                <option value=\"setup-database\">Create Database Schema</option>\n
                <option value=\"import-students\">Import Student Data</option>\n
                <option value=\"import-sessions\">Import Session Data</option>\n
                <option value=\"deleteDataFromTable\">Delete All Records From a Table</option>\n
                <option value=\"dropTable\">Drop a Table</option>\n
            </optgroup>\n
        </select>\n
        <input type=\"submit\" value=\"submit\">\n
        </form>";
}
?>