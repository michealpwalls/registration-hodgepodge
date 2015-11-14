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