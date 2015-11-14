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

echo "<form class=\"upper-space left-margin\" action=\"index.php?action=setup&setupAction=import-{$str_targetDb}&admtkn={$adminTokenInput}\" method=\"post\" enctype=\"multipart/form-data\">\n
        <fieldset><legend>File Details</legend>\n
            <label for=\"{$fileNameIn}\">Input File:</label>\n
            <input type=\"file\" name=\"{$fileNameIn}\" id=\"{$fileNameIn}\"><br>\n
            <label for=\"fileDelimiter\">Delimiter (Character that seperates values)</label>\n
            <input type=\text\" name=\"fileDelimiter\" id=\"fileDelimiter\">\n
        </fieldset><br>\n
        <fieldset><legend>Data Format</legend>
            <label for=\"dataStructure\">Structure of the incoming data</label><br>\n
            <input type=\"text\" name=\"dataStructure\" size=\"95\" value=\"{$str_defaultStructure}\"><br>\n
            <input type=\"hidden\" name=\"setupAction\" value=\"import-{$str_targetDb}\">\n
            <br><input type=\"submit\" name=\"submit\" value=\"Process\">\n
        </fieldset>
    </form>\n";
?>