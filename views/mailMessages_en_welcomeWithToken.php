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

$profileUrl = "{$str_appURL}index.php?stkn={$studentToken}";

$emailMessage = "<p>Hello " . htmlentities($firstName) . "</p>\r\n
            <p>Please click on the link below to continue the registration process.</p>\r\n
            <p><a href='{$profileUrl}'>Continue with registration</a></p>\r\n
            <p>If the link above does not redirect to the registration page, please copy and paste the following URL into a new browser window:<br>{$profileUrl}</p>\n
            <p>If you've received this mail in error, please forward this message to " . htmlentities($str_supportEmail) . " so that we can investigate the cause.</p>\n
            <p>Thanks.</p>\r\n";
?>